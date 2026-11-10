<?php

require '../controller/guard.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }
function getDb(): PDO
{
    require_once __DIR__ . '/../fonction/db.php';
    return clavarde_connect();
}

function getNbDiscussion(string $login): int
{
    $pdo = getDb();
    $sql = 'SELECT count(id_discussion) FROM discussion WHERE membre1 = :login OR membre2 = :login' ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['login' => $login]);
    return $stmt->fetchColumn();

}

function getIdDiscussion(string $login, int $numDiscussion): string
{
    $pdo = getDb();
    $sql = 'SELECT id_discussion FROM discussion WHERE membre1 = :login OR membre2 = :login ORDER BY id_discussion LIMIT 1 OFFSET :numDiscussion' ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('login', $login, PDO::PARAM_STR);
    $stmt->bindValue('numDiscussion', $numDiscussion, PDO::PARAM_INT);
    $stmt->execute();
    $value = $stmt->fetchColumn();
    return(string)$value;

}
function getLastMessage(string $login, int $numDiscussion): string
{
    $id_discussion = getIdDiscussion($login, $numDiscussion);
    $pdo = getDb();
    $sql = 'SELECT message FROM chat WHERE id_discussion = :id_discussion ORDER BY date DESC LIMIT 1 OFFSET 0' ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('id_discussion',$id_discussion , PDO::PARAM_INT);
    $stmt->execute();
    $value = $stmt->fetchColumn();
    if ($value == "")
    {
        $value = "aucun message";
    }
    return (string)$value;

}


function getDestinataire(string $login, int $numDiscussion): string
{
    $pdo = getDb();
    $sql = 'SELECT membre1 FROM discussion WHERE membre1 = :login OR membre2 = :login ORDER BY id_discussion LIMIT 1 OFFSET :numDiscussion' ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('login', $login, PDO::PARAM_STR);
    $stmt->bindValue('numDiscussion', $numDiscussion, PDO::PARAM_INT);
    $stmt->execute();
    $membre1 = $stmt->fetchColumn();
    $sql = 'SELECT membre2 FROM discussion WHERE membre1 = :login OR membre2 = :login ORDER BY id_discussion LIMIT 1 OFFSET :numDiscussion' ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('login', $login, PDO::PARAM_STR);
    $stmt->bindValue('numDiscussion', $numDiscussion, PDO::PARAM_INT);
    $stmt->execute();
    $membre2 = $stmt->fetchColumn();
    if ($membre1 == $login)
    {
        $destinataire = $membre2;
    }
    elseif ($membre2 == $login)
    {
        $destinataire = $membre1;
    }
    else
    {
        var_dump('WTF');
    }
    
    return $destinataire;

}

function getNbDiscussionOverall(): int
{
    $pdo = getDb();
    $sql = 'SELECT count(id_discussion) FROM discussion' ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $value = $stmt->fetchColumn();
    return (int)$value;

}

function getAllIdDiscussion(int $numDiscussion): string
{
    $pdo = getDb();
    $sql = 'SELECT id_discussion FROM discussion ORDER BY id_discussion LIMIT 1 OFFSET :numDiscussion' ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('numDiscussion', $numDiscussion, PDO::PARAM_INT);
    $stmt->execute();
    $value = $stmt->fetchColumn();
    return(string)$value;

}
function getAllLastMessage(int $numDiscussion): string
{
    $id_discussion = getAllIdDiscussion( $numDiscussion);
    $pdo = getDb();
    $sql = 'SELECT message FROM chat ORDER BY date DESC LIMIT 1 OFFSET 0' ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('id_discussion',$id_discussion , PDO::PARAM_INT);
    $stmt->execute();
    $value = $stmt->fetchColumn();
    if ($value == "")
    {
        $value = "aucun message";
    }
    return (string)$value;

}


function getAllDestinataire(int $numDiscussion): string
{
    $pdo = getDb();
    $sql = 'SELECT membre1 FROM discussion ORDER BY id_discussion LIMIT 1 OFFSET :numDiscussion' ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('numDiscussion', $numDiscussion, PDO::PARAM_INT);
    $stmt->execute();
    $membre1 = $stmt->fetchColumn();
    $sql = 'SELECT membre2 FROM discussion ORDER BY id_discussion LIMIT 1 OFFSET :numDiscussion' ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('numDiscussion', $numDiscussion, PDO::PARAM_INT);
    $stmt->execute();
    $membre2 = $stmt->fetchColumn();

    $membresConv = $membre1 . " - " . $membre2;

    return $membresConv;

}

$login = $_SESSION['username'];
$admin = $_SESSION['admin'];

$conversations = [];
$nbDiscussion = getNbDiscussion($login);

for($i = 0 ;$i < $nbDiscussion; $i++)
{

    $username = getDestinataire($login, $i);
    $last_message = getLastMessage($login,$i);
    $id_discussion = getIdDiscussion($login,$i);
    $conversations[]=
        [
            'username' => $username,
            'last_message' => $last_message,
            'id_discussion' => $id_discussion
        ];
}

$conversations_admin = [];
$nbAllDiscussion = getNbDiscussionOverall();


for($i = 0 ;$i < $nbAllDiscussion; $i++)
{

    $username = getAllDestinataire($i);
    //$last_message = getAllLastMessage($i);
    $id_discussion = getAllIdDiscussion($i);
    $conversations_admin[]=
        [
            'username' => $username,
            //'last_message' => $last_message,
            'id_discussion' => $id_discussion
        ];
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Clavardon - Conversations</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="user-banner">
    <div class="user-info">
        <div class="user-avatar">👤</div>
        <div class="user-text">
            <span class="user-name" id="currentUser"><?php echo ($login); ?></span>
            <span class="user-status">● En ligne</span>
        </div>
    </div>
</header>

<header class="page-header conversations-header">
    <h1>💬 Mes conversations</h1>


</header>

<main class="page-conversations">


    <div class="conversation-layout">

        <?php if ($admin == 1): ?>
            <div class="admin-banner">
                <p>🛡️ Vous êtes connecté en mode <strong>Admin</strong>. Accès total aux conversations.</p>
            </div>
        <?php endif; ?>
        <!-- LISTE DES CONVERSATIONS -->
        <aside class="conversation-list" id="conversationList">
            <?php if ($admin == 1): ?>
                <?php foreach ($conversations_admin as $conversation): ?>
                    <div class="conversation-row">
                        <div href="chat.php?id_discussion=<?= $conversation['id_discussion'] ?>" class="conversation-item">
                            <strong class="conv-user"><?= htmlspecialchars($conversation['username']) ?></strong>
                            <span class="conv-msg"><?= htmlspecialchars($conversation['last_message']) ?></span>
                        </div>

                        <a href="/../controller/deletchat.php?id_discussion=<?= $conversation['id_discussion']?>"
                           class="delete-link"
                           onclick="return confirm('Supprimer ?');">
                            <span class="delete-icon">🗑</span>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach ($conversations as $conversation): ?>
                    <div class="conversation-row">
                        <a href="chat.php?id_discussion=<?= $conversation['id_discussion'] ?>" class="conversation-item">
                            <strong class="conv-user"><?= htmlspecialchars($conversation['username']) ?></strong>
                            <span class="conv-msg"><?= htmlspecialchars($conversation['last_message']) ?></span>
                        </a>

                        <a href="/../controller/deletchat.php?id_discussion=<?= $conversation['id_discussion']?>"
                           class="delete-link"
                           onclick="return confirm('Supprimer ?');">
                            <span class="delete-icon">🗑</span>
                        </a>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>
        </aside>

        <!-- BOUTON EN BAS -->
        <?php if ($admin == 0): ?>
            <div class="new-conversation-container">
                <a href="nouvelle_conversation.php" class="button-link">
                    <button class="new-conversation-button">
                        ＋ Nouvelle discussion
                    </button>
                </a>
            </div>
        <?php endif; ?>

    </div>

</main>

<footer>
    <p> © 2026 Clavardon</p>
    <p><a href="/controller/logout.php">Se déconnecter</a></p>
</footer>

</body>
</html>