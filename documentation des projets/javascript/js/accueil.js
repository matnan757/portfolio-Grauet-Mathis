const burgers = [
    { name: 'Menu Burger', image: 'burger/burger1.jpg' },
    { name: 'Burger bonbon', image: 'burger/burger2.webp' },
    { name: 'Giga Burger', image: 'burger/burger3.jpg' },
    { name: 'Burger crevette', image: 'burger/burger4.jpg' },
    { name: 'Big Mac', image: 'burger/burger5.jpg' },
    { name: 'Burger black', image: 'burger/burger6.jpg' },
    { name: 'Burger poisson', image: 'burger/burger7.webp' },
    { name: 'Burger poulet', image: 'burger/burger8.webp' },
    { name: 'Burger galette de pommes de terre', image: 'burger/burger9.webp' },
    { name: 'Burger kebab', image: 'burger/burger10.jpg' }
];

const imageBurger = document.getElementById('slideshow-image');
const nomBurger = document.getElementById('burger-name-text');
const diaporama = document.getElementById('slideshow');
let indexBurger = 0;

// Affiche l'image du burger par ordre d'index dans le tableau burgers
function afficherBurger(index) {
    imageBurger.src = burgers[index].image;
    nomBurger.textContent = '';
}

// On repasse au premier burger si on est au dernier, sinon on passe au suivant
function burgerSuivant() {
    indexBurger = (indexBurger + 1) % burgers.length;
    afficherBurger(indexBurger);
}

// Cliquer affiche le nom du burger
diaporama.addEventListener('click', function () {
    nomBurger.textContent = burgers[indexBurger].name;
});

afficherBurger(indexBurger);
setInterval(burgerSuivant, 10000); // pour changer d'image chaque 10 secondes