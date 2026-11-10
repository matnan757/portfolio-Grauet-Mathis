import sqlite3

# Assure-toi que c'est bien le même nom que dans base_donnee.py
DB_NAME = "stockage.db"

def ajouter_les_utilisateurs():
    print(f"Connexion à {DB_NAME}...")
    conn = sqlite3.connect(DB_NAME)
    curseur = conn.cursor()

    # 1. Liste des 5 Professeurs (Nom, Prénom, Email, Statut)
    profs = [
        ("Dubois", "Jean", "jean.dubois@ecole.fr", "Professeur"),
        ("Martin", "Sophie", "sophie.martin@ecole.fr", "Professeur"),
        ("Lefebvre", "Pierre", "pierre.lefebvre@ecole.fr", "Professeur"),
        ("Leroy", "Isabelle", "isabelle.leroy@ecole.fr", "Professeur"),
        ("Moreau", "Lucas", "lucas.moreau@ecole.fr", "Professeur")
    ]

    # 2. Liste des 25 Élèves
    eleves = [
        ("Petit", "Thomas", "thomas.petit@eleve.fr", "Elève"),
        ("Roux", "Emma", "emma.roux@eleve.fr", "Elève"),
        ("Fournier", "Léa", "lea.fournier@eleve.fr", "Elève"),
        ("Girard", "Hugo", "hugo.girard@eleve.fr", "Elève"),
        ("Clement", "Chloé", "chloe.clement@eleve.fr", "Elève"),
        ("Meyer", "Nathan", "nathan.meyer@eleve.fr", "Elève"),
        ("David", "Camille", "camille.david@eleve.fr", "Elève"),
        ("Bertrand", "Enzo", "enzo.bertrand@eleve.fr", "Elève"),
        ("Deschamps", "Manon", "manon.deschamps@eleve.fr", "Elève"),
        ("Garnier", "Louis", "louis.garnier@eleve.fr", "Elève"),
        ("Faure", "Jade", "jade.faure@eleve.fr", "Elève"),
        ("Dupuis", "Gabriel", "gabriel.dupuis@eleve.fr", "Elève"),
        ("Fontaine", "Lola", "lola.fontaine@eleve.fr", "Elève"),
        ("Robin", "Arthur", "arthur.robin@eleve.fr", "Elève"),
        ("Masson", "Inès", "ines.masson@eleve.fr", "Elève"),
        ("Sanchez", "Adam", "adam.sanchez@eleve.fr", "Elève"),
        ("Gerard", "Sarah", "sarah.gerard@eleve.fr", "Elève"),
        ("Nguyen", "Léo", "leo.nguyen@eleve.fr", "Elève"),
        ("Boyer", "Alice", "alice.boyer@eleve.fr", "Elève"),
        ("Denis", "Raphaël", "raphael.denis@eleve.fr", "Elève"),
        ("Lemaire", "Mila", "mila.lemaire@eleve.fr", "Elève"),
        ("Duval", "Maël", "mael.duval@eleve.fr", "Elève"),
        ("Gauthier", "Louise", "louise.gauthier@eleve.fr", "Elève"),
        ("Roger", "Jules", "jules.roger@eleve.fr", "Elève"),
        ("Roche", "Lina", "lina.roche@eleve.fr", "Elève")
    ]

    liste_totale = profs + eleves

    try:
        # Important : On utilise bien 'statut' ici
        curseur.executemany('''
            INSERT INTO utilisateurs (nom, prenom, email, statut) 
            VALUES (?, ?, ?, ?)
        ''', liste_totale)
        
        conn.commit()
        print(f"✅ Succès : {len(liste_totale)} utilisateurs ont été ajoutés dans la base.")
        
    except sqlite3.OperationalError as e:
        print(f"❌ Erreur SQL : {e}")
        print("Vérifie que la table 'utilisateurs' existe bien et a la colonne 'statut'.")
    except Exception as e:
        print(f"❌ Erreur inattendue : {e}")
    finally:
        conn.close()

if __name__ == "__main__":
    ajouter_les_utilisateurs()