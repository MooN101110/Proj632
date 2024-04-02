"""
Fonctions pour executer des requêtes sur la base de données phpmyadmin
Normalement faut juste importer 
"""
import mysql.connector

def get_db(logs_path="logs_db.txt"):
    with open(logs_path, "r") as file:
        # Lire les 4 premières lignes du fichier (user/pwd/host/port/db)
        logs = file.readlines()
    
    db = mysql.connector.connect(
        user=logs[0][0:-1],
        password=logs[1][0:-1],
        host=logs[2][0:-1],
        port=logs[3][0:-1],
        database=logs[4][0:-1],
    )
    return db

def close_db(db):
    try:
        db.close()
        return 0
    except:
        return -1

def execute_query(db, query, logs_path="logs_db.txt"):
    """
    Fonction pour executer une requête sur la base de données
    Retourne 0 si la requête a été executée avec succès, -1 sinon
    """
    try:
        cursor = db.cursor()
        cursor.execute(query)
        db.commit()
        return 0
    except:
        return -1

def get_data(db, table_name, logs_path="logs_db.txt",columns="*",conditions=""):
    """
    Fonction pour récupérer les données d'une table
    conditions: doit être de la forme "WHERE condition1 (AND condition2...)"
    """
    cursor = db.cursor()
    query = f"SELECT {columns} FROM {table_name} {conditions}"
    cursor.execute(query)
    return cursor.fetchall()