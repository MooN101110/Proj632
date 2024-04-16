# -*- coding: utf-8 -*-
"""
@author: Axelle ROY
"""

### import des bibliothèques ###
import getpass
import os
import chromedriver_autoinstaller
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from bs4 import BeautifulSoup as bs
from selenium.webdriver.common.by import By
import time

chromedriver_autoinstaller.install()
driver=webdriver.Chrome()

# définition de l'url à scrapper
url_connexion="https://intranet.univ-smb.fr/"
driver.get(url_connexion)
html_connexion=driver.page_source
soup=bs(html_connexion, "lxml")

# connexion à la page :
username=driver.find_element(By.ID, "username")
password=driver.find_element(By.NAME, "password")

# récupération de l'identifiant et du mot de passe
"""
# partie où l'identifiant et le mot de passe sont récupéré automatiquement depuis un fichier texte
login="logs.txt"
with open(login, 'r') as fichier :
    lines=fichier.readlines()
    id=lines[0]
    mdp=lines[1]
"""
# partie où on demande de rentrer son identifiant et son mot de passe
id=input("Entrez votre identifiant :")
mdp=getpass.getpass("Entrer votre mot de passe (l'affichage est caché): ")

# on envoie les identifiants et mots de passe dans la page
username.send_keys(id)
password.send_keys(mdp)

# on valide les informations rentrées
password.send_keys(Keys.RETURN)
time.sleep(1)

# on créé l'url de l'espace étudiant à partir de l'id
url="https://monprofil.univ-smb.fr/profil/fr/"+id
driver.get(url)
html=driver.page_source
soup=bs(html, "lxml")
tableau=soup.find('table', class_="table table-bordered table-condensed")

prenom_th=tableau.find('th', text="Prénom")
nom_th=tableau.find('th', text="Nom usuel")
naissance_th=tableau.find('th', text="Date de naissance")
if  nom_th==None or prenom_th==None or naissance_th==None:
    print("Erreur dans la récupération des informations")
    prenom=id
    nom=id
    naissance=0
else :
    prenom_td=prenom_th.find_next('td')
    nom_td=nom_th.find_next('td')
    naissance_td=naissance_th.find_next('td')
    if  nom_td==None or prenom_td == None or naissance_td==None:
        print("Erreur dans la récupération des infos")
        prenom=id
        nom=id
        naissance=0
    else :
        prenom=prenom_td.text
        nom=nom_td.text
        naissance=naissance_td.text.strip()
print(nom, prenom, naissance)

# on enregistre les infos de l'élève dans un fichier texte :
chemin_python=os.path.dirname(__file__) # on récupère le chemin du fichier actuel
chemin_relatif=os.path.join(chemin_python, "..") # on remonte d'un niveau pour arriver dans le dossier Proj632
chemin_projet=os.path.abspath(chemin_relatif) # on converti le chemin relatif en chemin absolu
chemin_data=os.path.join(chemin_projet, "data") # on va dans le dossier data
fichier="infos_eleve.txt"
enregistrement=os.path.join(chemin_data, fichier)
with open(enregistrement, "wt+") as f :
    f.write(f"{nom}\n")
    f.write(f"{prenom}\n")
    f.write(f"{naissance}\n")