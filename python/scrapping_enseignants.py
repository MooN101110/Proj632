import re
import chromedriver_autoinstaller
from selenium import webdriver
from bs4 import BeautifulSoup as bs
from selenium.webdriver.common.by import By
from selenium.common.exceptions import WebDriverException
import time
from lien_db import get_db, execute_query, get_data, close_db

# Connexion à la base de données et initialisation de la requête
db=get_db()
query = ""

chromedriver_autoinstaller.install()

url=f"https://www.polytech.univ-smb.fr/intranet/accueil.html"
driver = webdriver.Chrome()

# Accéder à la page de connexion
driver.get(url)

# Remplir le formulaire de connexion
username = driver.find_element(By.NAME,"user")
password = driver.find_element(By.NAME,"pass")

#Récup des données de connexion
with open("logs.txt", "r") as file:
    # Lire les deux premières lignes du fichier
    logs = file.readlines()
    
username.send_keys(logs[0])
password.send_keys(logs[1])
# Soumettre le formulaire de connexion

driver.find_element(By.NAME, "submit").submit()

# Attendre un court instant pour permettre à la page de se charger
time.sleep(2)

explore_link = "/intranet/personnels-enseignants.html"
data_final = []

finished = False
while not finished :
    try:
        driver.get(f"https://www.polytech.univ-smb.fr{explore_link}")
    except WebDriverException:
        print("page down")

    data = []

    try:
        soup = bs(driver.page_source, "lxml")
        
        try:
            # Récupérer le lien de la page suivante pour continuer la récupération des données des enseignants
            explore_link =  soup.find("a",href=re.compile("nextPage"))["href"]
            if "https" in explore_link:
                explore_link = explore_link.replace("https://www.polytech.univ-smb.fr","")
        except:
            finished = True
            print("exception pas de page suivante")
        
        data = soup.find_all(class_="info")
        
        for personne in data:
            # Récupérer les informations de chaque personne
            nom_prenom = personne.find(class_="name").text
            telephones = personne.find(class_="telephones").text
            bureaux = personne.find(class_="bureaux").text
            email = personne.find(class_="email").text

            nom = nom_prenom.split(" ")[0]
            prenom = nom_prenom.split(" ")[1]
            liste_telephones = telephones[6:].split("\n")
            liste_bureaux = bureaux[6:].split("\n")
            email = email[6:]

            # Ajouter les informations à la requête sql
            query += f"INSERT INTO INFO_enseignant (nom, prenom, mail) VALUES ('{nom}', '{prenom}','{email}');\n"

    except Exception as e:
        print(f"Une exception s'est produite : {e}")
        
# Fermer le navigateur
driver.quit()

# Execute la requête pour ajouter les données dans la base de données
for i in query.split("\n"):
    if i != "":
        print(i)
        print(execute_query(db, i))
close_db(db)

print("done")
