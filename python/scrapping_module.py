from bs4 import BeautifulSoup as bs
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.common.exceptions import NoSuchElementException
import time
import random 
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
file=open('logs.txt', "r")
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

#Selection de la filière et de l'année (Ici IDU )

driver.find_element(By.ID,'2021-2025').click()
driver.find_element(By.ID,'idu_4').click()
driver.find_element(By.NAME, "tx_savfilters_default[submit]").submit()

#Récuperation des liens de page pour chaque matière
soup= bs(driver.page_source, 'html.parser')
liens=soup.find_all('a')
code_module=soup.find_all('li', class_="code")
liens_module =[]

for link in liens:
    if len(link["href"])>200:
        liens_module.append([link.get_text(), "https://www.polytech.univ-smb.fr"+link["href"]])

#Parcours de chaque page pour récupérer les infos
data=[]
data_csv=[]

for i in range (len(liens_module)):
    soup =bs(driver.page_source, "lxml")
    driver.get(liens_module[i][1])
    soup= bs(driver.page_source, 'html.parser') 
       
    recup_info=soup.find_all('div',class_='value')
    recup_entete=soup.find_all('div',class_='label')
    
    info_module=[div.get_text(strip=True) for div in recup_info]
    entete=[div.get_text(strip=True) for div in recup_entete]
    
    #Mise en forme des données pour csv
    data_csv.append(["Code","Matière"])
    for elt in (entete):
        data_csv[i*2].append(elt)
    data_csv.append([code_module[i].get_text()[9:-11],liens_module[i][0]])
    for elt in (info_module):
        data_csv[i*2+1].append(elt)
    
    #Mise en forme des données pour bd
    data.append([code_module[i].get_text()[9:-11],liens_module[i][0]])
    for elt in (info_module):
        data[i].append(elt)
        
    #Temps de pause pour éviter la detection du scraping
    time.sleep(random.randrange(2,5))

driver.close()

# Sauvegarde des données
with open("../data/modules.csv", "wt+", newline="") as f:
    writer = csv.writer(f,delimiter=';')
    for row in data:
        writer.writerow(row)
   
#Sauvegarde des données dans la bd
bd=lien_db.get_db("logs_db.txt")
for elt in (data):
    query= f"INSERT INTO INFO_module (code_module,nom,id_semestre,id_discipline) VALUES ('{elt[0]}','{elt[1]}','{elt[2][1:]}',(SELECT id_discipline FROM INFO_discipline WHERE nom LIKE '{elt[0][:-3]}'))"
    lien_db.execute_query(bd,query)
    print (query)
    
print(lien_db.get_data(bd,"INFO_module"))
lien_db.close_db(bd)

