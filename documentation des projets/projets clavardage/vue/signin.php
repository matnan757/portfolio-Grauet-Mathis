<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Clavardon - Inscription</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="page-header">
    <img src="ressources/clavardage_rond.png" alt="Logo de Clavardon">
    <h1>Inscription à Clavardon</h1>
</header>

<main>
    <div>
        <form id="signinForm" method="post" action="/../controller/signin.php">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Adresse email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">S'inscrire</button>
        </form>

        <!-- BOUTON RETOUR -->
        <a href="../index.php" class="button-link">
            <button type="button" class="back-button">← Retour à l'accueil</button>
        </a>
    </div>
</main>

<footer>
    <p>&copy; 2026 Clavardon. Tous droits réservés.</p>
</footer>

</body>
</html>
