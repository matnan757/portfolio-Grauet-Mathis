const champNomBurger = document.getElementById('burger-nom');
const listesIngredients = [
    document.getElementById('ingredient1'),
    document.getElementById('ingredient2'),
    document.getElementById('ingredient3')
];
const erreurBurger = document.getElementById('erreur-burger');

// Ajoute un ingrédient dans le stock pour les listes déroulantes
function ajouterAuxListes(nom) {
    listesIngredients.forEach(function (liste) {
        const option = document.createElement('option');
        option.value = nom;
        option.textContent = nom;
        liste.appendChild(option);
    });
}

// Initialise les listes, d'abord vide puis on rajoute l'ingrédient de base et ceux créés
listesIngredients.forEach(function (liste) {
    liste.innerHTML = '<option value="">-- Choisir --</option>';
});
ingredientsDeBase.forEach(function (nom) {
    ajouterAuxListes(nom);
});
ingredientsCrees.forEach(function (ingredient) {
    ajouterAuxListes(ingredient.nom);
});

document.getElementById('btn-creer-burger').addEventListener('click', creerBurger);

// Crée un burger, vérifie les champs et le stock, puis retire les quantités utilisées
function creerBurger() {
    const nom = champNomBurger.value.trim();

    const choix = [];
    listesIngredients.forEach(function (liste) {
        choix.push(liste.value);
    });

    if (nom === '') {
        afficherMessage(erreurBurger, 'Le nom du burger est obligatoire.', true);
        return;
    }
    if (choix.indexOf('') !== -1) {
        afficherMessage(erreurBurger, 'Veuillez choisir les 3 ingrédients.', true);
        return;
    }

    // Compte combien de fois chaque ingrédient est demandé
    const besoins = {};
    choix.forEach(function (nomIngredient) {
        besoins[nomIngredient] = (besoins[nomIngredient] || 0) + 1;
    });

    // vérifie qu'on possede assez de stock pour chaque ingrédient demandé
    for (const nomIngredient of Object.keys(besoins)) {
        const ingredient = trouverIngredientCree(nomIngredient);
        if (ingredient && ingredient.quantite < besoins[nomIngredient]) {
            afficherMessage(erreurBurger,
                'Stock insuffisant pour « ' + nomIngredient + ' » : il reste ' +
                ingredient.quantite + ', il en faut ' + besoins[nomIngredient] + '.', true);
            return;
        }
    }

    // Quand le stock est suffisant, on retire les quantités utilisées
    Object.keys(besoins).forEach(function (nomIngredient) {
        const ingredient = trouverIngredientCree(nomIngredient);
        if (ingredient) {
            ingredient.quantite -= besoins[nomIngredient];
        }
    });

    burgersCrees.push({ nom: nom, ingredients: choix });
    sauvegarder();

    champNomBurger.value = '';
    listesIngredients.forEach(function (liste) {
        liste.value = '';
    });
    afficherMessage(erreurBurger, 'Burger « ' + nom + ' » créé !', false);
}