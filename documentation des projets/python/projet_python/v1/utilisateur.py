class utilisateur:
    def __init__(self, nom, prenom, email, classe):
        self.nom = nom
        self.prenom = prenom
        self.email = email
        self.classe = classe
    
    def afficher_info(self):
        return f"Nom: {self.nom}, Prenom: {self.prenom}, Email: {self.email}"