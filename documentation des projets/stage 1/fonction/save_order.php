<?php
// On se connecte à la base de données SQLite
require_once 'db.php';

// On récupère les données envoyées par le JavaScript
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['ids']) && is_array($data['ids'])) {
    // Requête pour mettre à jour la colonne 'ordre' du produit selon son ID
    $query = $db->prepare("UPDATE produits SET ordre = ? WHERE id = ?");
    
    foreach ($data['ids'] as $index => $id) {
        // L'index (0, 1, 2...) devient sa nouvelle position
        $query->execute([$index, $id]);
    }
    
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Données invalides']);
}