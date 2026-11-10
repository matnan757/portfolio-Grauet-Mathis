<?php
require_once 'db.php';
session_start();

// 1. Sécurité : Vérifier que c'est bien l'admin
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
        $reqImage = $db->prepare("SELECT image_url FROM produits WHERE id = ?");
        $reqImage->execute([$id]);
        $produit = $reqImage->fetch();

        if ($produit && !empty($produit['image_url'])) {
            $cheminComplet = __DIR__ . '/../' . $produit['image_url']; // image_url part de la racine du site

            if (file_exists($cheminComplet)) {
                unlink($cheminComplet);
            }
        }

        $delete = $db->prepare("DELETE FROM produits WHERE id = ?");
        $delete->execute([$id]);

        header('Location: admin_pannel.php?success=1');
        exit();

    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}