import sqlite3

def inserer_exemples():
    conn = sqlite3.connect("stockage.db")
    curseur = conn.cursor()
    
    objets = [
        ("Salle informatique A", "Salle", 1),
        ("Vidéoprojecteur Epson", "Matériel", 1),
        ("Dictionnaire Larousse", "Livre", 1),
        ("Appareil Photo Canon", "Matériel", 1),
        ("Gymnase", "Salle", 1),
        ("Tablette iPad n°12", "Matériel", 1),
        ("Roman - Le Petit Prince", "Livre", 1),
        ("Microphone sans fil", "Matériel", 1)
    ]
    
    curseur.executemany('''
        INSERT INTO ressources (nom_ressources, type_ressources, etat_ressources) 
        VALUES (?, ?, ?)
    ''', objets)
    
    conn.commit()
    conn.close()
    print("Exemples insérés avec succès !")

inserer_exemples()