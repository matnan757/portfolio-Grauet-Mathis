<?php
require __DIR__ . '/../fonction/db.php';
neptune_session();

$erreur = "";
$info   = isset($_GET['inscrit']) ? "Compte cree. Vous pouvez vous connecter." : "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login    = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === '' || $password === '') {
        $erreur = "Merci de remplir tous les champs.";
    } else {
        $db = neptune_db();
        $stmt = $db->prepare('SELECT * FROM client WHERE login = :login');
        $stmt->execute(['login' => $login]);
        $client = $stmt->fetch();

        if ($client && password_verify($password, $client['password'])) {
            $_SESSION['is_logged'] = true;
            $_SESSION['login']     = $client['login'];
            $_SESSION['admin']     = (int) $client['admin'];
            header('Location: accueil.php');
            exit;
        } else {
            $erreur = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>HOTEL NEPTUNE - CONNEXION</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="png" href="ressources/logo_neptune.png">
    <link rel="stylesheet" href="style.css">
</head>

<header>
    <h1> HOTEL NEPTUNE</h1>
    <h2> CONNEXION </h2>
</header>

<body>
    <div class="centrer">
        <?php if ($info !== ''): ?>
            <p class="message-info"><?= htmlspecialchars($info) ?></p>
        <?php endif; ?>
        <?php if ($erreur !== ''): ?>
            <p class="message-erreur"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>

        <form class="formulaire" method="post" action="login.php">
            <label for="login">Nom d'utilisateur</label>
            <input type="text" id="login" name="login" required
                   value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>

            <button class="bouton" type="submit">Se connecter</button>
        </form>

        <p>
            Pas encore de compte ? <a class="bouton" href="signin.php">S'inscrire</a>
        </p>
        <p><a class="bouton" href="accueil.php">Retour a l'accueil</a></p>
    </div>
</body>
</html>
