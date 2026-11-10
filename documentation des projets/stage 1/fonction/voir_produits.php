<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

require_once 'db.php'; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration | Réorganiser les Produits</title>
    <link rel="stylesheet" href="../acceuil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="voir.css">
</head>
<body>

    <div class="admin-header">
        <div>
            <h1>Réorganiser l'ordre des produits</h1>
            <p>Glissez-déposez les cartes de produits pour changer leur ordre d'affichage.</p>
        </div>
        <a href="admin_pannel.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Retour au Panel Admin
        </a>
    </div>

    <div class="container">
        
        <section class="specialties">
            <h2>Nos Pains (1ère section)</h2>
            <div class="products-grid js-sortable-grid">
                <?php
                // Modifié pour trier par ordre
                $requete = $db->query("SELECT * FROM produits WHERE categorie = 'pain' ORDER BY ordre ASC");
                while ($p = $requete->fetch()): ?>
                    <div class="product-card" data-id="<?= $p['id'] ?>">
                        <div class="product-img-frame">
                            <?php 
                            $nom_fichier = basename($p['image_url']); 
                            if (isset($p['type_media']) && $p['type_media'] === 'video'): ?>
                                <video autoplay muted loop playsinline>
                                    <source src="video/<?= $nom_fichier ?>" type="video/mp4">
                                </video>
                            <?php else: ?>
                                <img src="img/<?= $nom_fichier ?>" alt="<?= htmlspecialchars($p['nom']) ?>">
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($p['nom']) ?></h3>
                            <p><?= htmlspecialchars($p['description']) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <section class="specialties">
            <h2>Nos Pains Spéciaux (2ème section)</h2>
            <div class="products-grid js-sortable-grid">
                <?php
                $requete = $db->query("SELECT * FROM produits WHERE categorie = 'speciaux' ORDER BY ordre ASC");
                while ($p = $requete->fetch()): ?>
                    <div class="product-card" data-id="<?= $p['id'] ?>">
                        <div class="product-img-frame">
                            <?php 
                            $nom_fichier = basename($p['image_url']); 
                            if (isset($p['type_media']) && $p['type_media'] === 'video'): ?>
                                <video autoplay muted loop playsinline>
                                    <source src="video/<?= $nom_fichier ?>" type="video/mp4">
                                </video>
                            <?php else: ?>
                                <img src="img/<?= $nom_fichier ?>" alt="<?= htmlspecialchars($p['nom']) ?>">
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($p['nom']) ?></h3>
                            <p><?= htmlspecialchars($p['description']) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <section class="specialties">
            <h2>Nos Pâtisseries (3ème section)</h2>
            <div class="products-grid js-sortable-grid">
                <?php
                $req_pat = $db->query("SELECT * FROM produits WHERE categorie = 'patisseries' ORDER BY ordre ASC");
                while ($p = $req_pat->fetch()): ?>
                    <div class="product-card" data-id="<?= $p['id'] ?>">
                        <div class="product-img-frame">
                            <?php 
                            $nom_fichier = basename($p['image_url']); 
                            if (isset($p['type_media']) && $p['type_media'] === 'video'): ?>
                                <video autoplay muted loop playsinline>
                                    <source src="video/<?= $nom_fichier ?>" type="video/mp4">
                                </video>
                            <?php else: ?>
                                <img src="img/<?= $nom_fichier ?>" alt="<?= htmlspecialchars($p['nom']) ?>">
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($p['nom']) ?></h3>
                            <p><?= htmlspecialchars($p['description']) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <section class="specialties">
            <h2>Nos trompes-l'oeil (4ème section)</h2>
            <div class="products-grid js-sortable-grid">
                <?php
                $req_trompe = $db->query("SELECT * FROM produits WHERE categorie = 'trompe_oeil' ORDER BY ordre ASC");
                while ($p = $req_trompe->fetch()): ?>
                    <div class="product-card" data-id="<?= $p['id'] ?>">
                        <div class="product-img-frame">
                            <?php 
                            $nom_fichier = basename($p['image_url']); 
                            if (isset($p['type_media']) && $p['type_media'] === 'video'): ?>
                                <video autoplay muted loop playsinline>
                                    <source src="video/<?= $nom_fichier ?>" type="video/mp4">
                                </video>
                            <?php else: ?>
                                <img src="img/<?= $nom_fichier ?>" alt="<?= htmlspecialchars($p['nom']) ?>">
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($p['nom']) ?></h3>
                            <p><?= htmlspecialchars($p['description']) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>
    <script>
        const grids = document.querySelectorAll('.js-sortable-grid');

        grids.forEach(grid => {
            new Sortable(grid, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                
                // Quand on lâche un produit déplacé :
                onEnd: function () {
                    // On récupère la liste ordonnée des identifiants (IDs)
                    const updatedIds = Array.from(grid.querySelectorAll('.product-card')).map(card => {
                        return card.getAttribute('data-id');
                    });

                    fetch('save_order.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ ids: updatedIds })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.status !== 'success') {
                            alert("Erreur lors de la sauvegarde de l'ordre.");
                        }
                    })
                    .catch(error => console.error('Erreur:', error));
                }
            });
        });
    </script>
</body>
</html>