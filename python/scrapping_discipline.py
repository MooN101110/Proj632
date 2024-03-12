from bs4 import BeautifulSoup as bs
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.common.exceptions import NoSuchElementException
import time
import chromedriver_autoinstaller
import csv

import lien_db

url='https://www.polytech.univ-smb.fr/intranet/accueil.html'

#Ouverture de la page web
chromedriver_autoinstaller.install()

driver = webdriver.Chrome()

driver.get(url)

html = driver.page_source

soup= bs(html, 'html.parser')

#Connexion
username=driver.find_element(By.ID,'user')
password=driver.find_element(By.NAME,'pass')

#Récuperation information 
file=open("logs.txt", "r")
lines=file.readlines()

username.send_keys(lines[0])
password.send_keys(lines[1])

try:
    driver.find_element(By.NAME,"submit").submit()
except NoSuchElementException as e:
    pass

#Aller à la page programme
soup =bs(driver.page_source, "lxml")
lien=soup.find(title="Accéder aux programmes")["href"]
driver.get("https://www.polytech.univ-smb.fr"+lien)

soup= bs(driver.page_source, 'html.parser')

#Récupération des disciplines
recup_disciplines=soup.find('select',id="discipline")
disciplines=[]
for elt in (recup_disciplines):
    if elt!='\n':  #enlever les \n collés aux noms des disciplines
        if elt.get_text()!='---':  #e,elevr les premier elt qui n'est pas une discipline (ne peut pas être fait avec un if and)
            disciplines.append(elt.get_text())
            #Mettre cette ligne pour csv
            #disciplines.append([elt.get_text()])
            
driver.close()

# # Sauvegarde des données en csv
# with open("disciplines.csv", "wt+", newline="") as f:
#     writer = csv.writer(f,delimiter=',')
#     for row in disciplines:
#         writer.writerow(row)
        
#Sauvegarde des données dans la bd
bd=lien_db.get_db("logs_db.txt")
for elt in (disciplines):
    query= f"INSERT INTO INFO_discipline (nom) VALUES ('{elt}')"
    lien_db.execute_query(bd,query)

print(lien_db.get_data(bd,"INFO_discipline"))
lien_db.close_db(bd)
