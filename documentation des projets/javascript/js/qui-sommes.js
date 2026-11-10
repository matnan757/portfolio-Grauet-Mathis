const URL_API = 'https://opendata.agencebio.org/api/gouv/operateurs/?siret=79317749400028';

// L'appel part dès l'ouverture de la page
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

// Remplit la phrase et la liste de toutes les productions
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

chargerProducteur();