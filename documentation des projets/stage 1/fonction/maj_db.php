<?php
// Connexion
require_once 'db.php';

// On vérifie si la colonne existe déjà pour ne pas faire planter le site
$check = $db->query("PRAGMA table_info(produits)");
$colonnes = $check->fetchAll(PDO::FETCH_ASSOC);
$existe = false;

foreach ($colonnes as $col) {
    if ($col['name'] === 'type_media') {
        $existe = true;
        break;
    }
}

if (!$existe) {
    $db->exec("ALTER TABLE produits ADD COLUMN type_media TEXT DEFAULT 'image'");
    echo "Colonne ajoutée !";
} else {
    echo "La colonne existe déjà, rien à faire.";
}
?>