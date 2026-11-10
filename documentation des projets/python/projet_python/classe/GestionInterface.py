import tkinter as tk
from tkinter import messagebox, simpledialog
from classe.base_donnee import Passerelle

class GestionInterface:
    def __init__(self):
        
        self.db = Passerelle("stockage.db")
        



                                            #FENETRE INTERFACE
        self.app = tk.Tk()
        self.app.title("Gestion École")
        self.app.geometry("550x600")

        #Variables de contrôle
        self.variable_type = tk.StringVar(self.app)
        self.variable_selection = tk.StringVar(self.app, value="ressources")
        
        #Construction de l'interface
        self.setup_ui()
        
        self.actualiser_affichage()

    def lancer(self):
        self.app.mainloop()

    def setup_ui(self):
        # Formulaire
        cadre_form = tk.LabelFrame(self.app, text="Ajout Rapide", padx=10, pady=10)
        cadre_form.pack(pady=10, padx=10, fill="x")

        self.entree_nom = tk.Entry(cadre_form)
        self.entree_nom.pack(side=tk.LEFT, padx=5, expand=True, fill="x")

        types = ["Salle", "Matériel", "Livre"]
        self.variable_type.set(types[0])
        tk.OptionMenu(cadre_form, self.variable_type, *types).pack(side=tk.LEFT, padx=5)

        tk.Button(cadre_form, text="Ajouter", command=self.action_ajouter, bg="#4CAF50", fg="white").pack(side=tk.LEFT)

        #Menu Table
        tk.Label(self.app, text="Table affichée :").pack(pady=5)
        tables = ["ressources", "emprunt", "utilisateurs"]
        tk.OptionMenu(self.app, self.variable_selection, *tables, command=self.actualiser_affichage).pack()

        #Liste
        cadre_liste = tk.Frame(self.app)
        cadre_liste.pack(pady=5, padx=10, fill="both", expand=True)
        
        scroll = tk.Scrollbar(cadre_liste)
        scroll.pack(side=tk.RIGHT, fill=tk.Y)
        
        self.liste_visuelle = tk.Listbox(cadre_liste, yscrollcommand=scroll.set, height=15)
        self.liste_visuelle.pack(side=tk.LEFT, fill="both", expand=True)
        scroll.config(command=self.liste_visuelle.yview)

        #Boutons Bas
        cadre_bas = tk.Frame(self.app)
        cadre_bas.pack(side=tk.BOTTOM, fill="x", pady=10, padx=10)

        tk.Button(cadre_bas, text="Vider Table", command=self.action_vider, bg="orange").pack(side=tk.LEFT)
        tk.Button(cadre_bas, text="Supprimer", command=self.action_supprimer, bg="#d32f2f", fg="white").pack(side=tk.RIGHT, padx=5)
        tk.Button(cadre_bas, text="Emprunter", command=self.action_emprunter, bg="#2196F3", fg="white").pack(side=tk.RIGHT, padx=5)
        tk.Button(cadre_bas, text="Rendre", command=self.action_rendre, bg="#9C27B0", fg="white").pack(side=tk.RIGHT, padx=5)







                                                    #LES FONCTIONS

    def actualiser_affichage(self, event=None):
        table = self.variable_selection.get()
        self.liste_visuelle.delete(0, tk.END)
        
        #Appel via la classe db
        donnees = self.db.recuperer_contenu_table(table)
        
        for ligne in donnees:
            if table == "ressources":
                etat = "Libre" if ligne[3] == 1 else "Pris"
                self.liste_visuelle.insert(tk.END, f"ID: {ligne[0]} | {ligne[1]} ({ligne[2]}) | {etat}")
            elif table == "utilisateurs":
                 self.liste_visuelle.insert(tk.END, f"ID: {ligne[0]} | {ligne[1]} {ligne[2]} ({ligne[4]})")
            else:
                self.liste_visuelle.insert(tk.END, f"ID: {ligne[0]} | {ligne[1]} -> {ligne[2]}")

    def action_ajouter(self):
        nom = self.entree_nom.get()
        type_res = self.variable_type.get()
        
        if nom.strip():
            #Appel via la classe db
            self.db.ajouter_ressource_manuelle(nom, type_res)
            messagebox.showinfo("Succès", f"{nom} ajouté !")
            self.entree_nom.delete(0, tk.END)
            self.actualiser_affichage()
        else:
            messagebox.showwarning("Erreur", "Le nom ne peut pas être vide.")

    def action_supprimer(self):
        try:
            index = self.liste_visuelle.curselection()[0]
            texte = self.liste_visuelle.get(index)
            id_obj = int(texte.split('|')[0].replace('ID:', '').strip())
            
            if messagebox.askyesno("Confirmation", "Supprimer cet élément ?"):
                # Appel via la classe db
                self.db.supprimer_ressource_db(id_obj)
                self.actualiser_affichage()
        except IndexError:
            messagebox.showwarning("Attention", "Sélectionnez une ligne d'abord.")

    def action_emprunter(self):
        try:
            index = self.liste_visuelle.curselection()[0]
            texte_ressource = self.liste_visuelle.get(index)
            
            if self.variable_selection.get() != "ressources":
                messagebox.showwarning("Erreur", "Placez-vous sur la table 'ressources' pour emprunter.")
                return

            if "Pris" in texte_ressource:
                messagebox.showerror("Oups", "Cet objet est déjà emprunté !")
                return

            id_obj = int(texte_ressource.split('|')[0].replace('ID:', '').strip())
            nom_obj = texte_ressource.split('|')[1].split('(')[0].strip()

                                                
                                                
                                                
                                                
                                                
                                                # FENETRE D'INTERFACE POPUP


            fenetre_users = tk.Toplevel(self.app)
            fenetre_users.title("Qui emprunte ?")
            fenetre_users.geometry("300x400")
            
            tk.Label(fenetre_users, text=f"Emprunt de : {nom_obj}", font=("Arial", 10, "bold")).pack(pady=10)
            tk.Label(fenetre_users, text="Sélectionnez un utilisateur :").pack()

            cadre_liste_u = tk.Frame(fenetre_users)
            cadre_liste_u.pack(fill="both", expand=True, padx=10, pady=5)
            
            scroll_u = tk.Scrollbar(cadre_liste_u)
            scroll_u.pack(side=tk.RIGHT, fill=tk.Y)
            
            liste_users_box = tk.Listbox(cadre_liste_u, yscrollcommand=scroll_u.set)
            liste_users_box.pack(side=tk.LEFT, fill="both", expand=True)
            scroll_u.config(command=liste_users_box.yview)

            tous_les_users = self.db.recuperer_contenu_table("utilisateurs")
            
            for u in tous_les_users:
                liste_users_box.insert(tk.END, f"{u[1]} {u[2]} ({u[4]})")

            def valider_choix():
                try:
                    index_u = liste_users_box.curselection()[0]
                    infos_user = liste_users_box.get(index_u)
                    
                    nom_final = infos_user.split('(')[0].strip()

                    reussite = self.db.enregistrer_emprunt(id_obj, nom_obj, nom_final)
                    
                    if reussite:
                        messagebox.showinfo("Succès", f"{nom_obj} prêté à {nom_final}.")
                        self.actualiser_affichage()
                        fenetre_users.destroy()
                    else:
                        messagebox.showerror("Erreur", "Problème lors de l'emprunt.")
                        
                except IndexError:
                    messagebox.showwarning("Attention", "Veuillez sélectionner une personne dans la liste.")

            tk.Button(fenetre_users, text="VALIDER L'EMPRUNT", command=valider_choix, bg="#4CAF50", fg="white").pack(pady=10)

        except IndexError:
            messagebox.showwarning("Attention", "Sélectionnez d'abord un objet à emprunter.")

    def action_rendre(self):
        try:
            index = self.liste_visuelle.curselection()[0]
            texte = self.liste_visuelle.get(index)
            
            if self.variable_selection.get() != "ressources":
                messagebox.showwarning("Erreur", "Placez-vous sur la table 'ressources' pour rendre un objet.")
                return

            id_obj = int(texte.split('|')[0].replace('ID:', '').strip())
            nom_obj = texte.split('|')[1].split('(')[0].strip()

            if "Libre" in texte:
                messagebox.showinfo("Info", "Cet objet est déjà libre !")
                return

            if messagebox.askyesno("Retour", f"Confirmer le retour de : {nom_obj} ?"):
                self.db.rendre_ressource(id_obj, nom_obj)
                self.actualiser_affichage()
                messagebox.showinfo("Succès", "L'objet est rendu.")
                
        except IndexError:
            messagebox.showwarning("Attention", "Sélectionnez un objet à rendre.")

    def action_vider(self):
        table = self.variable_selection.get()
        if messagebox.askyesno("Danger", f"Vider toute la table {table} ?"):
            self.db.vider_table_complete(table)
            self.actualiser_affichage()