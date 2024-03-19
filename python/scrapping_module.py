from bs4 import BeautifulSoup as bs
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.common.exceptions import NoSuchElementException
import time
import random 
import chromedriver_autoinstaller
import csv
import json
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
data={}
data_csv=[]

entete_ok=['Code','Matiere','Semestre','UE','Responsable','Mel','Nb heures TD','Nb heures Cours','Nb heures TP','Nb heures de Travail Personnel Planifi\u00e9','Nb heures de Travail Personnel Planifié']
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
    data[f"'{i}'"]={'Code':code_module[i].get_text()[9:-11],'Matiere':liens_module[i][0]}
    for j in range (min(len(info_module),len(entete))):
        if entete[j] in entete_ok and info_module[j]!="":
            data[f"'{i}'"][entete[j]]=info_module[j]
    #Temps de pause pour éviter la detection du scraping
    #time.sleep(random.randrange(2,5))

driver.close()

# Sauvegarde des données en csv
with open("../data/modules.csv", "wt+", newline="") as f:
    writer = csv.writer(f,delimiter=';')
    for row in data_csv:
        writer.writerow(row)
        
#Sauvegarde des données en json
with open("../data/modules.json", "w",encoding='utf8') as f:
    json.dump(data, f,indent=4,ensure_ascii=False)
   
#Sauvegarde des données dans la bd
bd=lien_db.get_db("logs_db.txt")
for i in range (len(data)):
    key = f"'{i}'"
    print(data[key].keys())
    query= f"INSERT INTO INFO_module (code_module,nom,id_semestre,id_discipline) VALUES ('{data[key]['Code']}','{data[key]['Matiere']}','{data[key]['Semestre'][1:]}',(SELECT id_discipline FROM INFO_discipline WHERE nom LIKE '{data[key]['Code'][:-3]}%'))"
    print(lien_db.execute_query(bd,query))
    if "Nb heures Cours" in (data[key].keys()):
        query=f"UPDATE INFO_module SET hCM={data[key]['Nb heures Cours']} WHERE code_module LIKE '{data[key]['Code']}'"
        lien_db.execute_query(bd,query)
    if "Nb heures TD" in (data[key].keys()):
        query=f"UPDATE INFO_module SET hTD={data[key]['Nb heures TD']} WHERE code_module LIKE '{data[key]['Code']}'"
        lien_db.execute_query(bd,query)
    if "Nb heures TP" in (data[key].keys()):
        query=f"UPDATE INFO_module SET hTP={data[key]['Nb heures TP']} WHERE code_module LIKE '{data[key]['Code']}'"
        lien_db.execute_query(bd,query)
    
print(lien_db.get_data(bd,"INFO_module"))
lien_db.close_db(bd)

