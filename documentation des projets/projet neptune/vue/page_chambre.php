<?php
require __DIR__ . '/../fonction/db.php';
neptune_session();

$connecte = !empty($_SESSION['is_logged']);
$login    = $_SESSION['login'] ?? '';

$id = (int) ($_GET['id'] ?? 0);
$db = neptune_db();
$stmt = $db->prepare('SELECT * FROM chambre WHERE id_chambre = :id');
$stmt->execute(['id' => $id]);
$chambre = $stmt->fetch();

$message = "";
if ($chambre && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$connecte) {
        header('Location: login.php');
        exit;
    }
    // Reservation simple : on confirme la demande (pas de table de reservation ici).
    $message = "Votre demande de réservation pour la " . $chambre['nom'] . " a bien été enregistrée.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>HOTEL NEPTUNE - <?= $chambre ? htmlspecialchars($chambre['nom']) : 'Chambre' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="png" href="ressources/logo_neptune.png">
    <link rel="stylesheet" href="style.css">
</head>

<header>
    <a class="lien-acceuil" href="accueil.php">HOTEL NEPTUNE</a>
</header>

<body>
    <?php if (!$chambre): ?>
        <h1 class="titre-acceuil">Chambre introuvable</h1>
        <div class="centrer"><a class="bouton" href="chambres.php">Retour aux chambres</a></div>
    <?php else: ?>
        <h1 class="titre-acceuil"><?= htmlspecialchars($chambre['nom']) ?></h1>
        <div class="centrer">
            <div class="chambre-carte" style="max-width:520px; margin:20px auto; text-align:left;">
                <p class="chambre-desc"><?= htmlspecialchars($chambre['description']) ?></p>
                <p class="chambre-infos">
                    <span class="chambre-prix"><?= number_format($chambre['prix'], 2, ',', ' ') ?> € / nuit</span>
                    <span>👤 <?= (int) $chambre['capacite'] ?> personnes</span>
                </p>

                <?php if ($message !== ''): ?>
                    <p class="message-info"><?= htmlspecialchars($message) ?></p>
                <?php elseif (!$chambre['disponible']): ?>
                    <p class="message-erreur">Cette chambre est complète.</p>
                <?php elseif ($connecte): ?>
                    <form method="post" action="page_chambre.php?id=<?= (int) $chambre['id_chambre'] ?>">
                        <button class="bouton" type="submit">Réserver cette chambre</button>
                    </form>
                <?php else: ?>
                    <p>Vous devez être connecté pour réserver.</p>
                    <a class="bouton" href="login.php">Se connecter</a>
                    <a class="bouton" href="signin.php">S'inscrire</a>
                <?php endif; ?>
            </div>
            <a class="bouton" href="chambres.php">Retour aux chambres</a>
        </div>
    <?php endif; ?>
</body>
</html>
