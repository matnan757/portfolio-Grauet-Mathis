// Clés utilisées dans sessionStorage
const CLE_INGREDIENTS = 'ingredientsCrees';
const CLE_BURGERS = 'burgersCrees';

// Aucun ingrédient de base
const ingredientsDeBase = [];

// Ingrédients avec stock : { nom, quantite } comme avec le pain de base, mais on peut en créer d'autres
const ingredientsCrees = JSON.parse(sessionStorage.getItem(CLE_INGREDIENTS)) || [
    { nom: 'Pain', quantite: 10 }
];

// Burgers créés : { nom, ingredients }
const burgersCrees = JSON.parse(sessionStorage.getItem(CLE_BURGERS)) || [];

// Enregistre l'état actuel pour les autres pages
function sauvegarder() {
    sessionStorage.setItem(CLE_INGREDIENTS, JSON.stringify(ingredientsCrees));
    sessionStorage.setItem(CLE_BURGERS, JSON.stringify(burgersCrees));
}

// Cherche un ingrédient créé par son nom (sans tenir compte des majuscules)
function trouverIngredientCree(nom) {
    return ingredientsCrees.find(function (ingredient) {
        return ingredient.nom.toLowerCase() === nom.toLowerCase();
    });
}

// Affiche un message erreur ou succès
function afficherMessage(element, texte, estErreur) {
    element.textContent = texte;
    element.className = estErreur ? 'erreur' : 'succes';
}