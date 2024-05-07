# -*- coding: utf-8 -*-
"""
Created on Fri Feb 16 15:37:29 2024

@author: pulci
"""
import re
import csv
import json
import time
from bs4 import BeautifulSoup
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver import Chrome, ChromeOptions
from webdriver_manager.chrome import ChromeDriverManager
from selenium.common.exceptions import WebDriverException
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import random

# Hide the browser
options = ChromeOptions()
options.headless = True

# update executable_path as required
#driver = Chrome(ChromeDriverManager().install(), options=options)
# Initialize the Chrome driver with the options
driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=options)

# Accéder à la page de connexion
driver.get("https://www.polytech.univ-smb.fr/intranet/accueil.html")

# Partie accepter cookies
try:
    WebDriverWait(driver, 20).until(EC.element_to_be_clickable((By.ID, "tarteaucitronPersonalize"))).click()
except:
    print("Could not click")
    pass

# Remplir le formulaire de connexion
username = driver.find_element(By.NAME,"user")
password = driver.find_element(By.NAME,"pass")

#Récup des données de connexion
with open("meme.txt", "r") as file:
    # Lire les deux premières lignes du fichier
    mot1 = file.readline().strip()  # Lire et supprimer les espaces blancs
    mot2 = file.readline().strip()  # Lire et supprimer les espaces blancs
    
username.send_keys(mot1)
password.send_keys(mot2)

# Soumettre le formulaire de connexion
password.send_keys(Keys.RETURN)

# Attendre un court instant pour permettre à la page de se charger
time.sleep(2)

# Accéder à la page protégée après la connexion
driver.get("https://www.polytech.univ-smb.fr/intranet/accueil.html")

soup = BeautifulSoup(driver.page_source, "lxml")

#lien trombinoscope
#explore_link = "https://www.polytech.univ-smb.fr/intranet/eleves-ingenieurs.html"
explore_link = soup.find(title="Accéder au Trombinoscope")["href"]

#data (liste de liste) exemple: [["FI4 - EIT", "Tapis", "Bernard"], ["FI3 - IDU", "Balkany", "Patrick"]]
data_final = [] #liste csv
data_json = []

finished = False
while not finished :
    time.sleep(random.randint(5, 10))
    try:
        driver.get("https://www.polytech.univ-smb.fr" + explore_link)
    except WebDriverException:
        print("page down")

    data = []

    try:
        soup = BeautifulSoup(driver.page_source, "lxml")
        
        #extraction lien page suivante
        try:
            #print("extraction lien page suivante")
            explore_link =  soup.find("a",href=re.compile("nextPage"))["href"]

            #rustine utilisee pour eviter les corruptions url
            if "https" in explore_link:
                explore_link = explore_link.replace("https://www.polytech.univ-smb.fr","")
        except:
            #pas de page suivante
            finished = True
            print("exception pas de page suivante")
        
        #recup des donnees brutes:
        #recup tout les etudiants dans un tableau qui contient la classe etudiant
        data = soup.find_all(class_="etudiant")
        
        #ensuite tu parcours ce tableau en extrayant la filière, l'annee et le nom et prenom dans un autre tableau data_final
        for etu in data:
            annee_filiere = etu.find(class_="statut").text[1:]
            nom_prenom = etu.find(class_="name").text

            annee = annee_filiere.split(" ")[0]
            filiere = annee_filiere.split(" ")[2]
            nom = nom_prenom.split(" ")[0]
            prenom = nom_prenom.split(" ")[1]

            data_final.append([annee, filiere, nom, prenom])
            data_json.append({"Année :":annee,"Filière :":filiere,"Nom :":nom,"Prénom :":prenom})
    except Exception as e:
        print(f"Une exception s'est produite : {e}")
        
# Fermer le navigateur
driver.quit()

# save data_final as csv
with open("liste_etudiants.csv", "w", encoding="utf-8", newline="") as f:
    writer = csv.writer(f)
    for row in data_final:
        writer.writerow(row)


# save data as JSON
with open("liste_etudiants.json", "w", encoding="utf-8") as json_file:
    json.dump(data_json, json_file, ensure_ascii=False, indent=2)
        