from lien_db import get_db, close_db, execute_query, get_data
import random

def init_logs():
    """
    Pour chaque personne (étudiant et enseignant), on crée une ligne dans la table INFO_logs avec les informations suivantes:
    - identifiant = 7 premieres lettres du nom et premiere lettre du prenom
    - mot_de_passe = 8 caracteres aleatoires (au moins une minuscule et une majuscule plus un chiffre et un caractere special)

    TEMPORAIRE PARCE QUE PAS FOU NIVEAU SECURITE (mot de passe en clair dans la base de données)
    """
    db = get_db()
    etudiants = get_data(db, "INFO_etudiant")
    enseignants = get_data(db, "INFO_enseignant")

    alphabet = "abcdefghijklmnopqrstuvwxyz"
    alphabet_maj = alphabet.upper()
    chiffres = "0123456789"
    caracteres_speciaux = "!$%&?_"

    personnes = etudiants + enseignants

    for personne in personnes:
        nom = personne[1]
        prenom = personne[2]
        nom = nom.lower()
        prenom = prenom.lower()
        identifiant = nom[0:7] + prenom[0]

        mot_de_passe = ""
        for i in range(2):
            choix = ["min","maj","chf","car"]
            choix_shuffle = random.shuffle(choix)
            for c in choix:
                if c == "min":
                    mot_de_passe += random.choice(alphabet)
                elif c == "maj":
                    mot_de_passe += random.choice(alphabet_maj)
                elif c == "chf":
                    mot_de_passe += random.choice(chiffres)
                elif c == "car":
                    mot_de_passe += random.choice(caracteres_speciaux)


        query = f"INSERT INTO INFO_logs (identifiant, mot_de_passe) VALUES ('{identifiant}', '{mot_de_passe}')"
        execute_query(db, query)