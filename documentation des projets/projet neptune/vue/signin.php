<?php
require __DIR__ . '/../fonction/db.php';
neptune_session();

$erreur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login    = trim($_POST['login'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === '' || $email === '' || $password === '') {
        $erreur = "Merci de remplir tous les champs.";
    } else {
        $db = neptune_db();
        $stmt = $db->prepare('SELECT id_client FROM client WHERE login = :login');
        $stmt->execute(['login' => $login]);

        if ($stmt->fetch()) {
            $erreur = "Ce nom d'utilisateur est deja pris.";
        } else {
            $stmt = $db->prepare('INSERT INTO client (login, email, password, admin)
                                  VALUES (:login, :email, :password, 0)');
            $stmt->execute([
                'login'    => $login,
                'email'    => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]);
            // Inscription reussie : on redirige vers la connexion.
            header('Location: login.php?inscrit=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>HOTEL NEPTUNE - INSCRIPTION</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="png" href="ressources/logo_neptune.png">
    <link rel="stylesheet" href="style.css">
</head>

<header>
    <h1> HOTEL NEPTUNE</h1>
    <h2> INSCRIPTION </h2>
</header>

<body>
    <div class="centrer">
        <?php if ($erreur !== ''): ?>
            <p class="message-erreur"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>

        <form class="formulaire" method="post" action="signin.php">
            <label for="login">Nom d'utilisateur</label>
            <input type="text" id="login" name="login" required
                   value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">

            <label for="email">Adresse e-mail</label>
            <input type="email" id="email" name="email" required
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>

            <button class="bouton" type="submit">S'inscrire</button>
        </form>

        <p>
            Deja un compte ? <a class="bouton" href="login.php">Se connecter</a>
        </p>
        <p><a class="bouton" href="accueil.php">Retour a l'accueil</a></p>
    </div>
</body>
</html>
