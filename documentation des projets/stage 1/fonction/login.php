<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Administration</title>
    <link rel="stylesheet" href="../acceuil.css">
    <style>
        .login-box {
            background-color: #5d4037;
            color: #f3e5ab;
            width: 350px;
            margin: 100px auto;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
        }
        input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: none; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Accès Fournil</h2>
        <form action="auth.php" method="POST">
            <input type="text" name="user" placeholder="Identifiant" required>
            <input type="password" name="mdp" placeholder="Mot de passe" required>
            <button type="submit" class="cta-button">Entrer</button>
        </form>
    </div>
</body>
</html>