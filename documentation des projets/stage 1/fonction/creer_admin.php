<?php
require_once 'db.php';

try {
    $identifiant = "patron"; 
    $mot_de_passe_clair = "260425";

    $mot_de_passe_hache = password_hash($mot_de_passe_clair, PASSWORD_DEFAULT);

   $sql = "REPLACE INTO admin (id, identifiant, mot_de_passe) VALUES (1, ?, ?)";
    $requete = $db->prepare($sql);

    $requete->execute([$identifiant, $mot_de_passe_hache]);

    echo "✅ Le compte administrateur a été créé avec succès !";
    echo "<br>Identifiant : " . $identifiant;
    echo "<br>Note : Le mot de passe est maintenant crypté dans boulangerie.db";

} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage();
}
?>