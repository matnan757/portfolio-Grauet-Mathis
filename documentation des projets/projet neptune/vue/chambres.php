<?php
require __DIR__ . '/../fonction/db.php';
neptune_session();

$connecte = !empty($_SESSION['is_logged']);
$login    = $_SESSION['login'] ?? '';

$db = neptune_db();
$chambres = $db->query('SELECT * FROM chambre ORDER BY prix')->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>HOTEL NEPTUNE - NOS CHAMBRES</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="png" href="ressources/logo_neptune.png">
    <link rel="stylesheet" href="style.css">
</head>

<header>
    <a class="lien-acceuil" href="accueil.php">HOTEL NEPTUNE</a>
    <h2 style="color:white; margin:0 0 0 auto;">
        <?php if ($connecte): ?>
            <?= htmlspecialchars($login) ?> ·
            <a class="lien-acceuil" href="logout.php" style="font-size:1rem;">Déconnexion</a>
        <?php else: ?>
            <a class="lien-acceuil" href="login.php" style="font-size:1rem;">Connexion</a>
        <?php endif; ?>
    </h2>
</header>

<body>
    <h1 class="titre-acceuil">Nos chambres</h1>

    <div class="chambres-grille">
        <?php foreach ($chambres as $chambre): ?>
            <article class="chambre-carte">
                <h3><?= htmlspecialchars($chambre['nom']) ?></h3>
                <p class="chambre-desc"><?= htmlspecialchars($chambre['description']) ?></p>
                <p class="chambre-infos">
                    <span class="chambre-prix"><?= number_format($chambre['prix'], 2, ',', ' ') ?> € / nuit</span>
                    <span>👤 <?= (int) $chambre['capacite'] ?> pers.</span>
                </p>
                <?php if ($chambre['disponible']): ?>
                    <span class="badge-dispo">Disponible</span>
                    <a class="bouton" href="page_chambre.php?id=<?= (int) $chambre['id_chambre'] ?>">Réserver</a>
                <?php else: ?>
                    <span class="badge-indispo">Complet</span>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>

    <div class="centrer">
        <a class="bouton" href="accueil.php">Retour à l'accueil</a>
    </div>
</body>
</html>
