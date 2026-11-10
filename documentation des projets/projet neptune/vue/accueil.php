<?php
require __DIR__ . '/../fonction/db.php';
neptune_session();

$connecte = !empty($_SESSION['is_logged']);
$login    = $_SESSION['login'] ?? '';
$estAdmin = !empty($_SESSION['admin']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>HOTEL NEPTUNE - ACCUEIL</title>
    <link rel="icon" type="png" href="ressources/logo_neptune.png">
    <link rel="stylesheet" href="style.css">
</head>

<header>
    <img class="logo" alt="logo" src="ressources/accueil_neptune.png">
    <h1> HOTEL NEPTUNE</h1>

</header>
<body>
    <?php if ($connecte): ?>
        <div class="centrer">
            <p>Bienvenue <strong><?= htmlspecialchars($login) ?></strong> !</p>
            <a class="bouton" href="chambres.php">Voir les chambres</a>
            <a class="bouton" href="logout.php">Se déconnecter</a>
        </div>
        <?php if ($estAdmin): ?>
            <div class="centrer">
                <p>Vous êtes administrateur. <a class="bouton" href="admin/accueil_admin.php">Espace admin</a></p>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="centrer">
            <a class="bouton" href="signin.php"> S'inscrire</a>
            <a class="bouton" href="login.php"> Se connecter</a>
        </div>
        <div class="centrer">
            <p> Vous êtes membre administrateur ? Connectez vous <a class="bouton" href="login.php"> ICI</a> </p>
        </div>
    <?php endif; ?>
</body>
</html>