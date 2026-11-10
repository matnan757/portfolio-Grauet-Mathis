<?php


$login = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

function getDb(): PDO
{
    require_once __DIR__ . '/../fonction/db.php';
    return clavarde_connect();
}

function newuser(string $login, string $email, string $password): void
{
    $pdo = getDb();
    $sql = 'SELECT * FROM user WHERE login = :login';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['login' => $login]);
    $user = $stmt->fetch();
    if ($user) {
        var_dump("nom d'utilisateur déjà existant");
        header("location: ../vue/signin.html");
        exit();
    } else {
        $sql = 'INSERT INTO user (login, email, password) VALUES ( :login, :email, :password)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['login' => $login,'email' => $email,'password' => $password]);
        header("location: ../vue/login.html");
    }
}


if (!empty($login) && !empty($email) && !empty($password))
{
    newuser($login,$email,$password);

} else {
    var_dump('problem');
}

