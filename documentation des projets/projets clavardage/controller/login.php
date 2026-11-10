<?php


$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';




function getDb(): PDO
{
    require_once __DIR__ . '/../fonction/db.php';
    return clavarde_connect();
}

function authenticate(string $login, string $password): void
{
    $pdo = getDb();
    $sql = 'SELECT * FROM user WHERE login = :login AND password = :password';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['login' => $login, 'password' => $password]);
    $user = $stmt->fetch();
    $sql = 'SELECT admin FROM user WHERE login = :login AND password = :password';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['login' => $login, 'password' => $password]);
    $admin = $stmt->fetchColumn();
    if ($user) {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $_SESSION["is_logged"] = true;
        $_SESSION["username"] = $login;
        $_SESSION["admin"] = $admin;
        header("location: ../vue/conversations.php");
        exit;

//        var_dump($user);
    } else {
        header("location: ../vue/login.html");
//        echo "Identifiant ou mot de passe incorrect.";
    }
}

if (!empty($login) && !empty($password)) {

    authenticate($login, $password);
} else {
    echo "Veuillez remplir les champs.";
}



