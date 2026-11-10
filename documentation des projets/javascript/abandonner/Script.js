/*
--------------------------------------------------------------------------------------
-------------------------------Constante----------------------------------------------
--------------------------------------------------------------------------------------
*/

const burgers = [
    { name: 'Menu Burger', image: 'burger/burger1.jpg' },
    { name: 'Burger bonbon', image: 'burger/burger2.webp' },
    { name: 'Giga Burger', image: 'burger/burger3.jpg' },
    { name: 'Burger crevette', image: 'burger/burger4.jpg' },
    { name: 'BigMac', image: 'burger/burger5.jpg' },
    { name: 'Burger black', image: 'burger/burger6.jpg' },
    { name: 'Burger poisson', image: 'burger/burger7.webp' },
    { name: 'Burger poulet', image: 'burger/burger8.webp' },
    { name: 'Burger galette de pommes de terre', image: 'burger/burger9.webp' },
    { name: 'Burger kebab', image: 'burger/burger10.jpg' }
];


const ingredientsDeBase = [];
// On commence avec 10 pains.
const ingredientsCrees = [
    { nom: 'Pain', quantite: 10 }
];
const burgersCrees = [];
const boutonsNav = document.querySelectorAll('.nav-btn');

/*
--------------------------------------------------------------------------------------
-------------------------------Page acceuil-------------------------------------------
--------------------------------------------------------------------------------------
*/

const imageBurger = document.getElementById('slideshow-image');
const nomBurger = document.getElementById('burger-name-text');
const diaporama = document.getElementById('slideshow');
let indexBurger = 0;

function afficherBurger(index) {
    imageBurger.src = burgers[index].image;
    nomBurger.textContent = '';
}

function burgerSuivant() {
    indexBurger = (indexBurger + 1) % burgers.length;
    afficherBurger(indexBurger);
}

diaporama.addEventListener('click', function () {
    nomBurger.textContent = burgers[indexBurger].name;
});

afficherBurger(indexBurger);
setInterval(burgerSuivant, 10000);

/*
--------------------------------------------------------------------------------------
-------------------------------Création Ingrédient -----------------------------------
--------------------------------------------------------------------------------------
*/

function afficherMessage(element, texte, estErreur) {
    element.textContent = texte;
    element.className = estErreur ? 'erreur' : 'succes';
}

const champNomIngredient = document.getElementById('ingredient-nom');
const champQuantite = document.getElementById('ingredient-quantite');
const erreurIngredient = document.getElementById('erreur-ingredient');

document.getElementById('btn-creer-ingredient').addEventListener('click', creerIngredient);

// Cherche un ingrédient créé par son nom (sans tenir compte des majuscules)
function trouverIngredientCree(nom) {
    return ingredientsCrees.find(function (ingredient) {
        return ingredient.nom.toLowerCase() === nom.toLowerCase();
    });
}

// Vérifie si le nom existe déjà parmi les ingrédients de base
function estIngredientDeBase(nom) {
    return ingredientsDeBase.some(function (base) {
        return base.toLowerCase() === nom.toLowerCase();
    });
}

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
    if (estIngredientDeBase(nom)) {
        afficherMessage(erreurIngredient, '« ' + nom + ' » existe déjà comme ingrédient de base.', true);
        return;
    }

    const existant = trouverIngredientCree(nom);
    if (existant) {
        // L'ingrédient existe déjà : on empile la quantité, sans ajouter une nouvelle option
        existant.quantite += quantite;
        afficherMessage(erreurIngredient, 'Quantité de « ' + existant.nom + ' » augmentée (total : ' + existant.quantite + ').', false);
    } else {
        // Nouvel ingrédient : on le stocke et on l'ajoute aux listes
        ingredientsCrees.push({ nom: nom, quantite: quantite });
        ajouterAuxListes(nom);
        afficherMessage(erreurIngredient, 'Ingrédient « ' + nom + ' » créé !', false);
    }

    champNomIngredient.value = '';
    champQuantite.value = '';
}

/*
--------------------------------------------------------------------------------------
-------------------------------Création Burger----------------------------------------
--------------------------------------------------------------------------------------
*/

const champNomBurger = document.getElementById('burger-nom');
const listesIngredients = [
    document.getElementById('ingredient1'),
    document.getElementById('ingredient2'),
    document.getElementById('ingredient3')
];
const erreurBurger = document.getElementById('erreur-burger');

listesIngredients.forEach(function (liste) {
    liste.innerHTML = '<option value="">-- Choisir --</option>';
});

ingredientsCrees.forEach(function (ingredient) {
    ajouterAuxListes(ingredient.nom);
});

// Ajoute un ingrédient dans les 3 listes déroulantes
function ajouterAuxListes(nom) {
    listesIngredients.forEach(function (liste) {
        const option = document.createElement('option');
        option.value = nom;
        option.textContent = nom;
        liste.appendChild(option);
    });
}

document.getElementById('btn-creer-burger').addEventListener('click', creerBurger);

// Crée un burger : vérifie les champs et le stock, puis retire les quantités utilisées
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

    // Compte combien de fois chaque ingrédient est demandé (ex. Tomate 2 fois = besoin de 2)
    const besoins = {};
    choix.forEach(function (nomIngredient) {
        besoins[nomIngredient] = (besoins[nomIngredient] || 0) + 1;
    });

    // Vérifie que le stock suffit AVANT de retirer quoi que ce soit
    for (const nomIngredient of Object.keys(besoins)) {
        const ingredient = trouverIngredientCree(nomIngredient);
        if (ingredient && ingredient.quantite < besoins[nomIngredient]) {
            afficherMessage(erreurBurger,
                'Stock insuffisant pour « ' + nomIngredient + ' » : il reste ' +
                ingredient.quantite + ', il en faut ' + besoins[nomIngredient] + '.', true);
            return;
        }
    }

    // Stock suffisant : on retire les quantités utilisées
    Object.keys(besoins).forEach(function (nomIngredient) {
        const ingredient = trouverIngredientCree(nomIngredient);
        if (ingredient) {
            ingredient.quantite -= besoins[nomIngredient];
        }
    });

    // On stocke le burger
    burgersCrees.push({ nom: nom, ingredients: choix });

    // On vide le formulaire
    champNomBurger.value = '';
    listesIngredients.forEach(function (liste) {
        liste.value = '';
    });
    afficherMessage(erreurBurger, 'Burger « ' + nom + ' » créé !', false);
}

/*
--------------------------------------------------------------------------------------
-------------------------------Qui sommes-nous ?--------------------------------------
--------------------------------------------------------------------------------------
*/

const URL_API = 'https://opendata.agencebio.org/api/gouv/operateurs/?siret=79317749400028';

function chargerProducteur() {
    $.getJSON(URL_API)
        .done(function (data) {
            if (!data.items || data.items.length === 0) {
                console.error('Aucun opérateur trouvé.');
                return;
            }
            afficherProducteur(data.items[0]);
        })
        .fail(function () {
            console.error("Erreur lors de l'appel à l'API.");
        });
}

function afficherProducteur(producteur) {
    const adresse = producteur.adressesOperateurs[0];

    const phrase = 'Notre restaurant travaille avec des produits locaux provenant de la ferme bio numéro « ' +
        producteur.numeroBio + ' » de Monsieur « ' + producteur.gerant +
        ' » située à l\'adresse « ' + adresse.lieu + ' » « ' + adresse.codePostal +
        ' » « ' + adresse.ville + ' ». Cette ferme intervient dans les commerces :';
    $('#qui-sommes-texte').text(phrase);

    const liste = $('#qui-sommes-liste').empty();
    producteur.productions.forEach(function (production) {
        liste.append($('<li>').text('« ' + production.nom + ' »'));
    });
}