from v1.Ressource import Ressource
from v1.utilisateur import utilisateur

class Bibliotheque:
    def __init__(self, stockage=None, emprunts=None):
        self.stockage = stockage if stockage is not None else []
        self.emprunts = emprunts if emprunts is not None else []

    def ajouter_ressource(self, nom, type):
        if self.trouver_ressource(nom) is not None:
            return "Ressource deja existante."
        self.stockage.append(Ressource(nom, type, 1))
        return "La ressource a ete ajoutee avec succes."

    def emprunter(self, nom):
        ressource = self.trouver_ressource(nom)
        if not ressource in self.stockage:
            return "Ressource introuvable."
        if ressource.etat == 0:
            return "Desolee, cette ressource n'est pas disponible pour le moment."
        ressource.etat = 0
        self.emprunts.append(nom)
        return "La ressource a ete empruntee avec succes."

    def rendre(self, nom):
        ressource = self.trouver_ressource(nom)
        if ressource is None:
            return "Ressource introuvable."
        if nom not in self.emprunts:
            return "Ressource introuvable ou pas empruntee."
        if ressource.etat == 1:
            return "Cette ressource n'etait pas empruntee."
        ressource.etat = 1
        self.emprunts.remove(nom)
        return "La ressource a ete rendue avec succes."

    def afficher_ressources_disponibles(self):
        disponibles = [r.nom for r in self.stockage if r.etat == 1]
        if not disponibles:
            return "Aucune ressource disponible pour le moment."
        return "Ressources disponibles: " + ", ".join(disponibles)

    def trouver_ressource(self, nom):
        for ressource in self.stockage  :
            if ressource.nom == nom:
                return ressource
        return None