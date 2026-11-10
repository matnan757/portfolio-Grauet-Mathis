const champNomIngredient = document.getElementById('ingredient-nom');
const champQuantite = document.getElementById('ingredient-quantite');
const erreurIngredient = document.getElementById('erreur-ingredient');

document.getElementById('btn-creer-ingredient').addEventListener('click', creerIngredient);

// Crée un ingrédient, ou ajoute la quantité s'il existe déjà
function creerIngredient() {
    const nom = champNomIngredient.value.trim();
    const quantite = Number(champQuantite.value);

    if (nom === '') {
        afficherMessage(erreurIngredient, "Le nom de l'ingrédient est obligatoire.", true);
        return;
    }
    if (!(quantite > 0)) {
        afficherMessage(erreurIngredient, 'La quantité doit être un nombre supérieur à 0.', true);
        return;
    }

    const existant = trouverIngredientCree(nom);
    if (existant) {
        // Si déjà existant, on ajoute la quantité
        existant.quantite += quantite;
        afficherMessage(erreurIngredient, 'Quantité de « ' + existant.nom + ' » augmentée (total : ' + existant.quantite + ').', false);
    } else {
        // sinon, on crée un nouvel ingrédient et on l'ajoute aux listes
        ingredientsCrees.push({ nom: nom, quantite: quantite });
        afficherMessage(erreurIngredient, 'Ingrédient « ' + nom + ' » créé !', false);
    }

    sauvegarder();

    champNomIngredient.value = '';
    champQuantite.value = '';
}