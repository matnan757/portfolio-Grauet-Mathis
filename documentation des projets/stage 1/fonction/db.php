<?php
// La base de données est rangée dans le dossier bd/ à la racine du site
$db_path = __DIR__ . '/../bd/boulangerie.db';

try {
    // Connexion à SQLite
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Création des tables si elles n'existent pas
    $db->exec("CREATE TABLE IF NOT EXISTS admin (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        identifiant VARCHAR(50) NOT NULL,
        mot_de_passe VARCHAR(255) NOT NULL
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS produits (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nom VARCHAR(100) NOT NULL,
        description TEXT,
        categorie VARCHAR(50),
        image_url VARCHAR(255),
        type_media VARCHAR(50),
        ordre INTEGER DEFAULT 0
    )");
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>