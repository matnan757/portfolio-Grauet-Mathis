<?php

require '../controller/guard.php';

?>

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
    <h1> Avec qui souhaitez vous démarrer une conversation ? </h1>

    <a href="conversations.php" class="chat-back"><h1 id="chatUser">Au final je veux pas </h1></a>


</header>

<main>
    <div>
        <form id="signinForm" method="post" action="/../controller/createchat.php">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required>


            <button type="submit">Confirmer</button>
        </form>

    </div>
</main>

<footer>
    <p>&copy; 2026 Clavardon. Tous droits réservés.</p>
</footer>

</body>
</html>