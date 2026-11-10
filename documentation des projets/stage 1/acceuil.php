<?php 
require_once 'fonction/db.php'; 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Fournil des Corbières | Boulangerie Artisanale</title>
    <link rel="stylesheet" href="acceuil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <header>
        <a href="https://www.google.fr/maps/place/Le+fournil+des+corbi%C3%A8res/@43.2009393,2.7604925,19z/data=!4m6!3m5!1s0x12b1cb45afd5a83f:0x11925d37e2fd779c!8m2!3d43.2011394!4d2.7602915!16s%2Fg%2F11l6sky6vt?entry=ttu&g_ep=EgoyMDI2MDUxMy4wIKXMDSoASAFQAw%3D%3D" class="cta-button" target="_blank" rel="noopener noreferrer">Nous trouver</a>
    </header>

    <nav>
        <a href="#">Accueil</a>
        <a href="#pains">Nos Pains</a>
        <a href="#speciaux">Pains spéciaux</a>
        <a href="#patisseries">Pâtisseries</a>
        <a href="#trompe_oeil">Trompes-l'oeil</a>
        <a href="#histoire">Notre Histoire</a>
        <a href="#contact">Contact</a>
    </nav>

    <div class="container" id="pains">
        <section class="specialties">
            <h2>Nos Pains</h2>
            <p>Du levain naturel, des farines locales et beaucoup de passion.</p>
            <div class="products-grid">
                <?php
                $requete = $db->query("SELECT * FROM produits WHERE categorie = 'pain' ORDER BY ordre ASC");
                while ($p = $requete->fetch()): ?>
                    <div class="product-card" onclick="openLightbox(this)" style="cursor: pointer;">
                        <div class="product-img-frame">
                            <?php 
                            $nom_fichier = basename($p['image_url']); 
                            if (isset($p['type_media']) && $p['type_media'] === 'video'): ?>
                                <video autoplay muted loop playsinline>
                                    <source src="fonction/video/<?= $nom_fichier ?>" type="video/mp4">
                                </video> 
                            <?php else: ?>
                                <img src="fonction/img/<?= $nom_fichier ?>" alt="<?= htmlspecialchars($p['nom']) ?>">
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

        <section id="speciaux" class="specialties">
            <h2>Nos Pains Spéciaux</h2>
            <p>Des pains uniques, préparés avec des ingrédients sélectionnés et une technique artisanale.</p>
            <div class="products-grid">
                <?php
                $requete = $db->query("SELECT * FROM produits WHERE categorie = 'speciaux' ORDER BY ordre ASC");
                while ($p = $requete->fetch()): ?>
                    <div class="product-card" onclick="openLightbox(this)" style="cursor: pointer;">
                        <div class="product-img-frame">
                            <?php 
                            $nom_fichier = basename($p['image_url']); 
                            if (isset($p['type_media']) && $p['type_media'] === 'video'): ?>
                                <video autoplay muted loop playsinline>
                                    <source src="fonction/video/<?= $nom_fichier ?>" type="video/mp4">
                                </video>
                            <?php else: ?>
                                <img src="fonction/img/<?= $nom_fichier ?>" alt="<?= htmlspecialchars($p['nom']) ?>">
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

        <section id="patisseries" class="specialties">
            <h2>Nos Pâtisseries</h2>
            <p>Des douceurs sucrées pour accompagner votre café.</p>
            <div class="products-grid">
                <?php
                $req_pat = $db->query("SELECT * FROM produits WHERE categorie = 'patisseries' ORDER BY ordre ASC");
                while ($p = $req_pat->fetch()): ?>
                    <div class="product-card" onclick="openLightbox(this)" style="cursor: pointer;">
                        <div class="product-img-frame">
                            <?php 
                            $nom_fichier = basename($p['image_url']); 
                            if (isset($p['type_media']) && $p['type_media'] === 'video'): ?>
                                <video autoplay muted loop playsinline>
                                    <source src="fonction/video/<?= $nom_fichier ?>" type="video/mp4">
                                </video>
                            <?php else: ?>
                                <img src="fonction/img/<?= $nom_fichier ?>" alt="<?= htmlspecialchars($p['nom']) ?>">
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

        <section id="trompe_oeil" class="specialties">
            <h2>Nos trompes-l'oeil</h2>
            <p>Des créations surprenantes pour éveiller vos sens.</p>
            <div class="products-grid">
                <?php
                $req_trompe = $db->query("SELECT * FROM produits WHERE categorie = 'trompe_oeil' ORDER BY ordre ASC");
                while ($p = $req_trompe->fetch()): ?>
                    <div class="product-card" onclick="openLightbox(this)" style="cursor: pointer;">
                        <div class="product-img-frame">
                            <?php 
                            $nom_fichier = basename($p['image_url']); 
                            if (isset($p['type_media']) && $p['type_media'] === 'video'): ?>
                                <video autoplay muted loop playsinline>
                                    <source src="fonction/video/<?= $nom_fichier ?>" type="video/mp4">
                                </video>
                            <?php else: ?>
                                <img src="fonction/img/<?= $nom_fichier ?>" alt="<?= htmlspecialchars($p['nom']) ?>">
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

    <div class="container">
        <section id="histoire" class="history-section">
            <h2>Une Tradition Familiale</h2>
            <p>Situé entre mer et montagnes, notre fournil perpétue les méthodes de panification traditionnelles. Nous travaillons avec les agriculteurs locaux pour vous garantir une qualité irréprochable et un goût unique.</p>
        </section>
    </div>

    <footer id="contact">
        <p>&copy; 2026 Le Fournil des Corbières - Ouvert du Mardi au SAMEDI (6h30 / 19h30) Dimanche (6h30 - 13h30)</p>
        <p class="social-link">
            <i class="fab fa-instagram"></i> 
            Suivez-nous : <a href="https://www.instagram.com/le_fournil_des_corbieres/" target="_blank">@le_fournil_des_corbieres</a>
        </p>
        <p>📍 13 avenue wilson, 11200 Lézignan-Corbières</p>
        <p>📞 Numéro fixe : 04 34 36 83 89</p>
        <div class="admin-access">
            <a href="fonction/login.php"><i class="fas fa-lock"></i> Accès Admin</a>
        </div>
    </footer>

    <div id="productLightbox" class="lightbox" onclick="closeLightbox()">
        <span class="lightbox-close">&times;</span>
        <div class="lightbox-content" onclick="event.stopPropagation()">
            <div id="lightboxMediaContainer"></div>
            <div id="lightboxInfoContainer">
                <h3 id="lightboxTitle"></h3>
                <p id="lightboxDescription"></p>
            </div>
        </div>
    </div>

    <script src="script/lightbox.js"></script>
</body>
</html>