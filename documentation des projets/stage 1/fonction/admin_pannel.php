<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

require_once 'db.php';
$produits = $db->query("SELECT * FROM produits")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Administration - Le Fournil</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

    <form action="ajouter_produit.php" method="POST" enctype="multipart/form-data">
        <h2>Ajouter un produit</h2>
    
        <label>Nom du produit :</label>
        <input type="text" name="nom" required>

        <label>Description :</label>
        <textarea name="description" rows="3"></textarea>

        <label>Catégorie :</label>
        <select name="categorie">
            <option value="pain">Pain</option>
            <option value="speciaux">Pains spéciaux</option>
            <option value="patisseries">Pâtisseries</option>
            <option value="trompe_oeil">Trompe-l'œil</option>
        </select>
        <label>Type de média :</label>
        <select name="type_media">
            <option value="image">Image (fixe)</option>
            <option value="video">Vidéo (animée)</option>
        </select>

        <label>Fichier (Image ou Vidéo) :</label>
        <input type="file" name="image_produit" accept="image/*,video/mp4" required>

        <button type="submit" name="ajouter">Ajouter au catalogue</button>
    </form>

    <form method="POST" enctype="multipart/form-data" action="modifier_produit.php">
        <h2>Modifier un produit</h2>
    
        <label>Choisir le produit :</label>
        <select name="id" id="select-produit">
            <?php foreach ($produits as $p): ?>
                <option 
                    value="<?= $p['id'] ?>"
                    data-nom="<?= htmlspecialchars($p['nom'], ENT_QUOTES) ?>"
                    data-description="<?= htmlspecialchars($p['description'], ENT_QUOTES) ?>"
                    data-categorie="<?= htmlspecialchars($p['categorie'], ENT_QUOTES) ?>"
                    data-type_media="<?= htmlspecialchars($p['type_media'] ?? 'image', ENT_QUOTES) ?>"
                ><?= htmlspecialchars($p['nom']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Nouveau nom :</label>
        <input type="text" name="nom" id="modifier-nom" required>

        <label>Nouvelle description :</label>
        <textarea name="description" id="modifier-description" rows="4"></textarea>

        <label>Nouvelle catégorie :</label>
        <select name="categorie" id="modifier-categorie">
            <option value="pain">Pains</option>
            <option value="speciaux">Pains spéciaux</option>
            <option value="patisseries">Pâtisseries</option>
            <option value="trompe_oeil">Trompes-l'oeil</option>
        </select>

        <label>Nouveau type de média :</label>
        <select name="type_media" id="modifier-type_media">
            <option value="image">Image (fixe)</option>
            <option value="video">Vidéo (animée)</option>
        </select>

        <label>Changer le fichier (optionnel) :</label>
        <input type="file" name="image_produit" accept="image/*,video/mp4">

        <button type="submit" name="modifier">Enregistrer les modifications</button>
    </form>

    <h2>Supprimer un produit</h2>
    <div class="delete-container">
        <?php
        $categories_noms = [
            'pain' => '🥖 Pains', 
            'speciaux' => '🌾 Pains spéciaux',
            'patisseries' => '🍰 Pâtisseries', 
            'trompe_oeil' => '🎨 Trompes-l\'oeil'
        ];
        foreach ($categories_noms as $key => $nom_affichage): 
            $req_cat = $db->prepare("SELECT * FROM produits WHERE categorie = ? ORDER BY nom ASC");
            $req_cat->execute([$key]);
            $liste_produits = $req_cat->fetchAll();
        ?>
            <details>
                <summary><?= $nom_affichage ?> (<?= count($liste_produits) ?>)</summary>
                <div style="padding: 10px;">
                    <?php foreach ($liste_produits as $p): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #eee;">
                            <span><?= $p['nom'] ?></span>
                            <div>
                                <button type="button" style="width: auto; background-color: #e74c3c; padding: 5px 10px;" onclick="showConfirm(this)">Supprimer</button>
                                <form action="supprimer_produit.php" method="POST" style="display:none; margin: 0; box-shadow: none; padding: 0; width: auto;">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <button type="submit" style="width: auto; background-color: #27ae60; padding: 5px 10px;">Confirmer</button>
                                    <button type="button" style="width: auto; background-color: #95a5a6; padding: 5px 10px;" onclick="hideConfirm(this)">Non</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </details>
        <?php endforeach; ?>
    </div>

    <a href="voir_produits.php" class="btn-action-ordre">
        <i class="fas fa-sort"></i> Gérer l'ordre des produits
    </a>

    <a href="../acceuil.php" class="back-link">Retour à l'accueil</a>

    <script>
    function showConfirm(btn) {
        btn.style.display = 'none';
        btn.nextElementSibling.style.display = 'flex';
        btn.nextElementSibling.style.gap = '5px';
    }
    function hideConfirm(btn) {
        const form = btn.parentElement;
        form.style.display = 'none';
        form.previousElementSibling.style.display = 'inline-block';
    }

    // Pré-remplissage du formulaire de modification
    function preRemplir() {
        const select = document.getElementById('select-produit');
        const option = select.options[select.selectedIndex];

        document.getElementById('modifier-nom').value         = option.dataset.nom;
        document.getElementById('modifier-description').value = option.dataset.description;

        const selectCat = document.getElementById('modifier-categorie');
        for (let opt of selectCat.options) {
            opt.selected = (opt.value === option.dataset.categorie);
        }

        const selectMedia = document.getElementById('modifier-type_media');
        for (let opt of selectMedia.options) {
            opt.selected = (opt.value === option.dataset.type_media);
        }
    }

    // Pré-remplir au chargement de la page
    document.addEventListener('DOMContentLoaded', preRemplir);

    // Pré-remplir à chaque changement de sélection
    document.getElementById('select-produit').addEventListener('change', preRemplir);
    </script>
</body>
</html>