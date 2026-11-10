import sqlite3

class Passerelle:
    def __init__(self, db_name="stockage.db"):
        """Constructeur : Initialise le nom de la BDD et crée les tables."""
        self.db_name = db_name
        self.initialiser_db()

    def initialiser_db(self):
        """Crée les tables si elles n'existent pas."""
        conn = sqlite3.connect(self.db_name)
        curseur = conn.cursor()
        
        #Table Emprunt
        curseur.execute('''
            CREATE TABLE IF NOT EXISTS emprunt (
                id_emprunt INTEGER PRIMARY KEY AUTOINCREMENT,
                nom_objet TEXT NOT NULL,
                nom_users TEXT NOT NULL
            )
        ''')

        #Table Ressources
        curseur.execute('''
            CREATE TABLE IF NOT EXISTS ressources (
                id_ressources INTEGER PRIMARY KEY AUTOINCREMENT,
                nom_ressources TEXT NOT NULL,
                type_ressources TEXT NOT NULL,
                etat_ressources INTEGER DEFAULT 1
            )
        ''')

        #Table Utilisateurs
        curseur.execute('''
            CREATE TABLE IF NOT EXISTS utilisateurs (
                id_utilisateur INTEGER PRIMARY KEY AUTOINCREMENT,
                nom TEXT NOT NULL,
                prenom TEXT NOT NULL,
                email TEXT,
                statut TEXT
            )
        ''')

        conn.commit()
        conn.close()

    def recuperer_contenu_table(self, nom_table):
        conn = sqlite3.connect(self.db_name)
        curseur = conn.cursor()
        try:
            curseur.execute(f"SELECT * FROM {nom_table}")
            return curseur.fetchall()
        except:
            return []
        finally:
            conn.close()

    def ajouter_ressource_manuelle(self, nom, type_res):
        conn = sqlite3.connect(self.db_name)
        curseur = conn.cursor()
        curseur.execute('''
            INSERT INTO ressources (nom_ressources, type_ressources, etat_ressources) 
            VALUES (?, ?, 1)
        ''', (nom, type_res))
        conn.commit()
        conn.close()

    def verifier_disponibilite(self, id_res):
        conn = sqlite3.connect(self.db_name)
        curseur = conn.cursor()
        curseur.execute("SELECT etat_ressources FROM ressources WHERE id_ressources = ?", (id_res,))
        resultat = curseur.fetchone()
        conn.close()
        
        if resultat:
            etat = resultat[0]
            return etat == 1
        return False

    def enregistrer_emprunt(self, id_res, nom_objet, nom_utilisateur):
        """Enregistre l'emprunt SEULEMENT si l'objet est libre."""

        if not self.verifier_disponibilite(id_res):
            print("ERREUR: L'objet est déjà emprunté.")
            return False
            
        conn = sqlite3.connect(self.db_name)
        curseur = conn.cursor()
        try:
            curseur.execute("INSERT INTO emprunt (nom_objet, nom_users) VALUES (?, ?)", (nom_objet, nom_utilisateur))
            
            curseur.execute("UPDATE ressources SET etat_ressources = 0 WHERE id_ressources = ?", (id_res,))
            
            conn.commit()
            print(f"SUCCÈS: {nom_objet} emprunté par {nom_utilisateur}")
            return True
        except Exception as e:
            print(f"ERREUR SQL: {e}")
            return False
        finally:
            conn.close()

    def supprimer_ressource_db(self, id_res):
        conn = sqlite3.connect(self.db_name)
        curseur = conn.cursor()
        curseur.execute("DELETE FROM ressources WHERE id_ressources = ?", (id_res,))
        conn.commit()
        conn.close()

    def vider_table_complete(self, nom_table):
        conn = sqlite3.connect(self.db_name)
        curseur = conn.cursor()
        curseur.execute(f"DELETE FROM {nom_table}")
        curseur.execute("DELETE FROM sqlite_sequence WHERE name=?", (nom_table,))
        conn.commit()
        conn.close()

    def supprimer_table_definitivement(self, nom_table):
        """Détruit la table et sa structure."""
        conn = sqlite3.connect(self.db_name)
        curseur = conn.cursor()
        curseur.execute(f"DROP TABLE IF EXISTS {nom_table}")
        conn.commit()
        conn.close()

    def rendre_ressource(self, id_res, nom_objet):
        """Met fin à un emprunt."""
        conn = sqlite3.connect(self.db_name)
        curseur = conn.cursor()
        try:
            curseur.execute("UPDATE ressources SET etat_ressources = 1 WHERE id_ressources = ?", (id_res,))
            curseur.execute("DELETE FROM emprunt WHERE nom_objet = ?", (nom_objet,))
            conn.commit()
            print(f"Ressource {nom_objet} rendue.")
            return True
        except Exception as e:
            print(f"Erreur retour: {e}")
            return False
        finally:
            conn.close()

if __name__ == "__main__":
    db = Passerelle()
    print("Base de données initialisée via la classe Passerelle.")