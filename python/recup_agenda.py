"""Premier test de scraping 

le but est de recupérer les titre des article, les descrition et le lien de la page du monde du 24/01/2024 et de les ranger ensemble"""
"""from bs4 import BeautifulSoup as bs
import requests

import csv

# importation de l'url 
url = "https://www.lemonde.fr/"

#telecharchement de la page 

request=requests.get(url)

donnee=request.content

soup = bs(donnee, "lxml")

# affichage des tous les titres

links = soup.find_all("p", class_="article__title")

# uncomment next lines to display the href attribute values of all link tags

for link in links:
    print(link.get_text())





#compréhension Ical
    
urlcal="https://ade-usmb-ro.grenet.fr/jsp/custom/modules/plannings/direct_cal.jsp?data=b5cfb898a9c27be94975c12c6eb30e9233bdfae22c1b52e2cd88eb944acf5364c69e3e5921f4a6ebe36e93ea9658a08f,1&resources=4941&projectId=1&calType=ical&lastDate=2042-08-14"
request.get(urlcal)
donnee"""


import chromedriver_autoinstaller
from selenium import webdriver
from bs4 import BeautifulSoup as bs
from selenium.webdriver.common.by import By
from selenium.webdriver.support.select import Select
import time
import os
import re

import icalendar
from urllib.request import urlretrieve

chromedriver_autoinstaller.install()

print("ok")
url=f"https://cas-uds.grenet.fr/login?service=https%3A%2F%2Fade-usmb-ro.grenet.fr%2Fdirect%2F"
driver = webdriver.Chrome()
#driver.set_window_position(-10000,0)

driver.get(url)
html = driver.page_source
#print(html)

soup = bs(html, "html.parser")

username = driver.find_element(By.ID, "username")
password = driver.find_element(By.ID, "password")

#recup infos dans le fichier logs.txt
file = open("logs.txt", "r")
lines = file.readlines()
username.send_keys(lines[0])
password.send_keys(lines[1])

username.send_keys()
password.send_keys()

driver.find_element(By.NAME, "submitBtn").submit()

time.sleep(3)

driver.find_element(By.ID, "x-auto-16").click()
driver.find_element(By.CLASS_NAME, "x-btn-text").click()



time.sleep(3)
driver.find_element(By.ID,"x-auto-137-input").send_keys("idu-3-G2")

time.sleep(1)

driver.find_element(By.CSS_SELECTOR,'[aria-describedby="x-auto-32"]').click()
time.sleep(8)
driver.find_element(By.CSS_SELECTOR,'[aria-describedby="x-auto-27"]').click()
time.sleep(5)
#Trouver l'élément d'entrée de date
print("mincraft")

driver.find_element("xpath","//button[contains(text(),'Générer URL')]").click()
time.sleep(3)
#href=re.compile("https://ade-usmb-ro.grenet.fr/jsp/custom/modules/")

print("merde")
soup2=bs(driver.page_source,"html.parser")
print("\n\n\n soup2\n\n\n\n", soup2)
time.sleep(3)
link=soup.find_all("div",class_="x-ade-labelForcedSize x-component")
print("affichage des liens AAAAA")
print(link)


fichier = "file.ical"
urlretrieve(url, fichier)

with open(fichier) as f:
    calendar = icalendar.Calendar.from_ical(f.read())

for event in calendar.walk('VEVENT'):
    print(event.get('SUMMARY'))




"""driver.execute_script("document.getElementById('elementId').click();")

driver.find_element(By.ID,"x-auto-530-input")
date_input=driver.find_element(By.ID,"x-auto-530-input")
time.sleep(3)
print("iuebgnouearbg")
#Utiliser JavaScript pour supprimer l'attribut readonly
driver.execute_script("arguments[0].removeAttribute('readonly');", date_input)
time.sleep(2)
#Envoyer les touches correspondant à la date souhaitée
date_input.send_keys('01/09/2024')

#Si nécessaire, vous pouvez également déclencher l'événement onchange pour l'élément d'entrée
#driver.execute_script("arguments[0].dispatchEvent(new Event('change'));", date_input)"""

#Attendre que la page se mette à jour si nécessaire

time.sleep(60)
driver.close()
