import lien_db 
import csv
#http://gpu-epu.univ-savoie.fr:48080/
#creation de la table dans la base de données si elle n'existe pas déjà
query ="CREATE TABLE IF NOT EXISTS TEST_etudiant(id_etudiant INT NOT NULL AUTO_INCREMENT,nom VARCHAR(255),prenom VARCHAR(255),annee VARCHAR(255),filiere VARCHAR(12),PRIMARY KEY (id_etudiant))"
#voir avec les contraintes pour la suite

db = lien_db.db
print(lien_db.execute_query(db,query))

# Ouvrir le fichier CSV en mode lecture
with open('liste_etudiants.csv', 'r') as fichier:
    # Créer un lecteur CSV à partir du fichier
    lecteur = csv.reader(fichier, delimiter=',')

    # Parcourir toutes les lignes du fichier CSV
    for ligne in lecteur:
        # Récupérer le nom et le prénom à partir de la ligne courante
        annee = ligne[0]
        filiere = ligne[1]
        nom = ligne[2]
        prenom = ligne[3]
        # Afficher le nom et le prénom
        #print("annee :", annee)
        #print("filiere :", filiere)
        #print("nom :", nom)
        #print("Prénom :", prenom)

         #insertion d'elt de la table 
        
        query = f"INSERT INTO TEST_etudiant(`nom`, `prenom`, `annee`, `filiere`) VALUES ('{nom}','{prenom}','{annee}','{filiere}')"
        
        print(lien_db.execute_query(db,query))
        
    print("fini")