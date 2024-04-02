# -*- coding: utf-8 -*-
"""
@author: Axelle ROY
"""

### import des bibliothèques ###
import csv
import getpass
import os
import chromedriver_autoinstaller
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from bs4 import BeautifulSoup as bs
from selenium.webdriver.common.by import By
import time

import lien_db

chromedriver_autoinstaller.install()
driver=webdriver.Chrome()


# définition de l'url à scrapper
url_connexion="https://www.polytech.univ-smb.fr/intranet/accueil.html"
driver.get(url_connexion)
html_connexion=driver.page_source
soup=bs(html_connexion, "lxml")

# connexion à la page :
username=driver.find_element(By.ID, "user")
password=driver.find_element(By.NAME, "pass")

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
time.sleep(2)

# on recharge la page espace-personnel :
url="https://www.polytech.univ-smb.fr/intranet/pages-speciales/espace-etudiants/espace-personnel.html"
driver.get(url)
html=driver.page_source
soup=bs(html, "lxml")
polypoints=soup.find_all('div', class_='value')
#print(polypoints)
informations=[]
for point in polypoints :
# grâce au print, on a vu que la class "value" contenait les polypoints et les stages effectués. 
# il faut donc générer une exception quand on a pas les intitulés de classes qu'on recherche 
    try :
        intitule=point.find('li', class_='intitule').text
        tache=point.find('li', class_='intitule_tache').text
        nb_points=point.find('li', class_='nb_points').text
        annee=point.find('li', class_='annee').text
        informations.append([intitule, tache,  nb_points, annee])

    except:
        # quand on a pas les informations liées aux polypoints, on ne fait rien
        pass

"""
# on enregistre ensuite chaque information dans un fichier csv dans le dossier data
    chemin_python=os.path.dirname(__file__) # on récupère le chemin du fichier actuel
    chemin_relatif=os.path.join(chemin_python, "..") # on remonte d'un niveau
    chemin_projet=os.path.abspath(chemin_relatif) # on converti le chemin relatif en chemin absolu
    chemin_data=os.path.join(chemin_projet, "data")
    fichier="polypoints.csv"
    enregistrement=os.path.join(chemin_data, fichier)
    with open(enregistrement, "wt+", encoding="utf-8", newline="") as fichier :
        writer=csv.writer(fichier)
        writer.writerow(['Intitulé', 'Tâche', 'Nombre de points', 'Année'])
        for info in informations :
            writer.writerow(info)
"""
driver.close()

# enregistrement dans la base de donnée
bd=lien_db.get_db("logs_db.txt")
for info in informations:
    intitule=info[0]
    tache=info[1]
    nb_points=info[2]
    annee=info[3]
    query= f"INSERT INTO INFO_polypoint (intitule, tache, nb_points, annee, id_etudiant) VALUES ('{intitule}', '{tache}', '{nb_points}', '{annee}', '{id}');"
    lien_db.execute_query(bd,query)

print(lien_db.get_data(bd,"INFO_polypoint"))
lien_db.close_db(bd)
        