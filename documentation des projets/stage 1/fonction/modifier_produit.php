<?php
require_once 'db.php';
session_start();

// 1. Sécurité : Vérifier si l'admin est connecté
if (!isset($_SESSION['admin'])) { 
    header('Location: login.php'); 
    exit(); 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $nom = $_POST['nom'] ?? '';
    $desc = $_POST['description'] ?? '';
    $cat = $_POST['categorie'] ?? '';
    $type_media = $_POST['type_media'] ?? 'image';

    if (!$id) { 
        die("Erreur : ID du produit manquant."); 
    }

    // 2. Gestion du fichier (si un nouveau fichier est téléchargé)
    if (isset($_FILES['image_produit']) && $_FILES['image_produit']['error'] == 0) {
        
        // Récupérer l'ancien fichier pour le supprimer du serveur
        $getOld = $db->prepare("SELECT image_url FROM produits WHERE id = ?");
        $getOld->execute([$id]);
        $oldFile = $getOld->fetchColumn();

        // Le chemin en BDD est "fonction/img/..." ou "fonction/video/..."
        // Comme 'modifier_produit.php' et les dossiers 'img'/'video' sont tous dans 'fonction/',
        // on a juste besoin d'enlever 'fonction/' pour obtenir le bon dossier local ('img/...' ou 'video/...')
        if ($oldFile) {
            // L'ancien fichier est soit dans fonction/img/, soit dans fonction/video/
            $nomAncien = basename($oldFile);
            $dossierAncien = (strpos($oldFile, 'video') !== false) ? __DIR__ . '/video/' : __DIR__ . '/img/';
            $cheminPhysiqueAncien = $dossierAncien . $nomAncien;
            
            if (file_exists($cheminPhysiqueAncien)) {
                unlink($cheminPhysiqueAncien);
            }
        }

        // Préparer le nouveau fichier
        $extension = strtolower(pathinfo($_FILES['image_produit']['name'], PATHINFO_EXTENSION));
        
        $nomFichierBase = str_replace(' ', '_', strtolower($nom));
        $nomFichierBase = preg_replace('/[^A-Za-z0-9\_]/', '', $nomFichierBase);
        $nomFinal = $nomFichierBase . "_" . time() . "." . $extension;

        // Stockage dans fonction/img/ ou fonction/video/
        if ($type_media === 'video') {
            $destination_physique = __DIR__ . "/video/" . $nomFinal;
            $chemin_bdd = "fonction/video/" . $nomFinal;
        } else {
            $destination_physique = __DIR__ . "/img/" . $nomFinal;
            $chemin_bdd = "fonction/img/" . $nomFinal;
        }

        if (move_uploaded_file($_FILES['image_produit']['tmp_name'], $destination_physique)) {
            // Mise à jour avec nouveau fichier
            $upd = $db->prepare("UPDATE produits SET nom = ?, description = ?, categorie = ?, image_url = ?, type_media = ? WHERE id = ?");
            $upd->execute([$nom, $desc, $cat, $chemin_bdd, $type_media, $id]);
        } else {
            die("Erreur lors du transfert du nouveau fichier.");
        }
    } else {
        // 3. Mise à jour simple (uniquement les textes et le type de média, sans changer le fichier)
        $upd = $db->prepare("UPDATE produits SET nom = ?, description = ?, categorie = ?, type_media = ? WHERE id = ?");
        $upd->execute([$nom, $desc, $cat, $type_media, $id]);
    }

    header('Location: admin_pannel.php?success=1');
    exit();
}
?>