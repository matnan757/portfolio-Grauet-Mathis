from classe.base_donnee import *

ma_db = Passerelle("stockage.db")

conn = sqlite3.connect(ma_db)
curseur = conn.cursor()
curseur.execute('''
        ALTER TABLE utilisateurs
        RENAME COLUMN classe TO statut
    ''')
    
conn.commit()
conn.close()