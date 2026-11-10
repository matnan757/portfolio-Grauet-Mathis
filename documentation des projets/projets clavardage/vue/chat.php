<?php

require '../controller/guard.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$login = $_SESSION['username'];


if (isset($_GET['id_discussion'])) {
    $id_discussion = (int)$_GET['id_discussion'];

    $_SESSION['id_discussion'] = $id_discussion;
} else {
    $id_discussion = $_SESSION['id_discussion'] ?? 0;
}
//var_dump($_SESSION['url']);
//var_dump($url);
//var_dump($id_discussion);
//var_dump($login);

function getURl(): string
{
    $adresse = $_SERVER['PHP_SELF'];
    $i = 0;
    foreach($_GET as $cle => $valeur){
        $adresse .= ($i == 0 ? '?' : '&').$cle.($valeur ? '='.$valeur : '');
        $i++;
    }
    return $adresse;
}



function getDb(): PDO
{
    require_once __DIR__ . '/../fonction/db.php';
    return clavarde_connect();
}

function getDestinataire(string $login, int $id_discussion): string
{
    $pdo = getDb();
    $sql = 'SELECT membre1 FROM discussion WHERE id_discussion = :id_discussion';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('id_discussion', $id_discussion, PDO::PARAM_INT);
    $stmt->execute();
    $membre1 = $stmt->fetchColumn();
    $sql = 'SELECT membre2 FROM discussion WHERE id_discussion = :id_discussion';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('id_discussion', $id_discussion, PDO::PARAM_INT);
    $stmt->execute();
    $membre2 = $stmt->fetchColumn();
    if ($membre1 == $login) {
        $destinataire = $membre2;
    } elseif ($membre2 == $login) {
        $destinataire = $membre1;
    } else {
        var_dump('WTF');
        $destinataire = 'wtf';
    }

    return $destinataire;
}

function getNbMessages(int $id_discussion): int
{

    $pdo = getDb();
    $sql = 'SELECT count(id_message) FROM chat WHERE id_discussion = :id_discussion' ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_discussion' => $id_discussion]);
    return $stmt->fetchColumn();


}

function getEnvoyeur(int $id_discussion, int $numMessage): string
{
    $pdo = getDb();
    $sql = 'SELECT envoyeur FROM chat WHERE id_discussion = :id_discussion ORDER BY date LIMIT 1 OFFSET :numMessage';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('id_discussion', $id_discussion, PDO::PARAM_INT);
    $stmt->bindValue('numMessage', $numMessage, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn();
}


function getMessage(int $id_discussion, int $numMessage): string
{
    $pdo = getDb();
    $sql = 'SELECT message FROM chat WHERE id_discussion = :id_discussion ORDER BY date LIMIT 1 OFFSET :numMessage';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('id_discussion', $id_discussion, PDO::PARAM_INT);
    $stmt->bindValue('numMessage', $numMessage, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn();
}

$url = getURl();

$_SESSION['url'] = $url;

$id_discussion = (int)$id_discussion;

$destinataire = getDestinataire($login,$id_discussion);

$envoyeur = getEnvoyeur(4,0);




$messages = [];
$nbMessages = getNbMessages($id_discussion);

for($i = 0 ;$i < $nbMessages; $i++)
{
    $envoyeur = getEnvoyeur($id_discussion,$i);
    $message = getMessage($id_discussion,$i);

    $messages[]=
        [
            'envoyeur' => $envoyeur,
            'message' => $message
        ];
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Clavardon - Discussion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="chat-header">
    <a href="conversations.php" class="chat-back">←</a>
    <h1 id="chatUser">Discussion avec <?php echo ($destinataire); ?></h1>
</header>
<main class="page-conversations">

    <div class="chat-layout">

        <!-- LISTE DES CONVERSATIONS -->
        <aside class="chat-list" id="conversationList">
            <section class="chat-messages">
                <?php foreach ($messages as $message):
                    if ($message['envoyeur'] == $destinataire): ?>
                        <div class="message received"><?php echo $message['message']; ?></div>
                    <?php elseif ($message['envoyeur'] == $login): ?>
                        <div class="message sent"><?php echo $message['message']; ?></div>
                    <?php endif;
                endforeach; ?>
            </section>
        </aside>

        <form class="chat-input" method="post" action="/../controller/message.php">
            <input
                    type="text"
                    name="message"
                    placeholder="Écrire un message..."
                    required
            >
            <button type="submit">➤</button>
        </form>

    </div>

</main>


<footer>
    <p>&copy; 2026 Clavardon</p>
</footer>

</body>
</html>
