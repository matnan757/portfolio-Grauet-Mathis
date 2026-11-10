<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'] ?? '';
    $desc = $_POST['description'] ?? '';
    $cat = $_POST['categorie'] ?? '';
    $type_media = $_POST['type_media'] ?? 'image';

    if (isset($_FILES['image_produit']) && $_FILES['image_produit']['error'] == 0) {
        
        $extension = strtolower(pathinfo($_FILES['image_produit']['name'], PATHINFO_EXTENSION));
        $extensions_autorisees = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'mov', 'webm'];

        if (!in_array($extension, $extensions_autorisees)) {
            die("Erreur : Le format .$extension n'est pas autorisé.");
        }

        $nomNettoye = "produit_" . time() . "." . $extension;

        if ($type_media === 'video') {
            $destination_physique = __DIR__ . "/video/" . $nomNettoye;
            $chemin_bdd = "fonction/video/" . $nomNettoye;
        } else {
            $destination_physique = __DIR__ . "/img/" . $nomNettoye;
            $chemin_bdd = "fonction/img/" . $nomNettoye;
        }

        if (move_uploaded_file($_FILES['image_produit']['tmp_name'], $destination_physique)) {
            try {
                $ins = $db->prepare("INSERT INTO produits (nom, description, categorie, image_url, type_media) VALUES (?, ?, ?, ?, ?)");
                $ins->execute([$nom, $desc, $cat, $chemin_bdd, $type_media]);

                header('Location: admin_pannel.php?success=1');
                exit();
            } catch (PDOException $e) {
                die("Erreur base de données : " . $e->getMessage());
            }
        } else {
            die("Erreur : Impossible de déplacer le fichier vers le dossier. Vérifiez les permissions du dossier.");
        }
    } else {
        $error = $_FILES['image_produit']['error'] ?? 'Inconnu';
        if ($error == 1 || $error == 2) {
            die("Erreur : La vidéo est trop lourde pour le serveur. Augmentez 'upload_max_filesize' dans PHP.ini.");
        }
        die("Erreur : Fichier non reçu ou non valide (Code erreur: $error).");
    }
}
?>