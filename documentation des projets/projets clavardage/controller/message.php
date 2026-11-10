<?php

require '../controller/guard.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$login = $_SESSION['username'];
$id_discussion = $_SESSION['id_discussion'];
$message = $_POST["message"] ?? '';
$date = date("Y-m-d H:i:s");
$url = $_SESSION['url'];

//var_dump($id_discussion);
//var_dump($message);
//var_dump($date);
//var_dump($url);

function getDb(): PDO
{
    require_once __DIR__ . '/../fonction/db.php';
    return clavarde_connect();
}
function newmessage(string $login, string $id_discussion, string $date, string $message): void
{
    $pdo = getDb();
    $sql = 'INSERT INTO chat (envoyeur, message, date, id_discussion) VALUES (:envoyeur, :message, :date, :id_discussion)';
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        'envoyeur'      => $login,
        'message'       => $message,
        'date'          => $date,
        'id_discussion' => $id_discussion
    ]);

    if ($success) {
        header("Location: " . $_SESSION['url']);
        //var_dump("Message envoyé avec succès !");

    } else {
        var_dump("Erreur lors de l'envoi.");
        exit();
    }
}


return newmessage($login, $id_discussion, $date, $message);

?>