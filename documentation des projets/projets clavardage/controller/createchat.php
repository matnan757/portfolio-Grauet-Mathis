<?php

require '../controller/guard.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$login = $_SESSION['username'];
$destinataire = $_POST['username'];

function getDb(): PDO
{
    require_once __DIR__ . '/../fonction/db.php';
    return clavarde_connect();
}

function newchat(string $login, string $destinataire, array $allConversation): void
{
    $pdo = getDb();
    $sql = 'SELECT * FROM user WHERE login = :login';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['login' => $destinataire]);
    $user = $stmt->fetch();
    if ($destinataire == $login)
    {
        var_dump('vous ne pouver pas faire une conversation avec vous-même');
    }
    else
    {
        foreach ($allConversation as $conversation)
        {
            if(($conversation['membre1'] == $login or $conversation['membre1'] == $destinataire) AND ($conversation['membre2'] == $destinataire or $conversation['membre2'] == $login))
            {
                var_dump('conversation déjà existante');
            }
            else
            {
                if ($user) {
                    $sql = "INSERT INTO `discussion` (`membre1`, `membre2`) VALUES ( :membre1, :membre2);";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['membre1' => $login,'membre2' => $destinataire]);
                    header("location: ../vue/conversations.php");
                } else {
                    var_dump('utilisateur inconnu');
                }
            }
        }
    }


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

function getMembre1(int $nb_discussion): string
{
    $pdo = getDb();
    $sql = 'SELECT membre1 FROM discussion LIMIT 1 OFFSET :nb_discussion';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('nb_discussion', $nb_discussion, PDO::PARAM_INT);
    $stmt->execute();
    $membre1 = $stmt->fetchColumn();
    return $membre1;
}

function getMembre2(int $nb_discussion): string
{
    $pdo = getDb();
    $sql = 'SELECT membre2 FROM discussion LIMIT 1 OFFSET :nb_discussion';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('nb_discussion', $nb_discussion, PDO::PARAM_INT);
    $stmt->execute();
    $membre2 = $stmt->fetchColumn();
    return $membre2;
}


$allDiscussion = [];
$nbDiscussionOverall = getNbDiscussionOverall();

for($i = 0 ;$i < $nbDiscussionOverall; $i++)
{

    $membre1 = getMembre1($i);
    $membre2 = getMembre2($i);
    $allDiscussion[]=
        [
            'membre1' => $membre1,
            'membre2' => $membre2
        ];
}



if (!empty($login) && !empty($destinataire)) {
    newchat($login,$destinataire,$allDiscussion);

} else {
    var_dump("");
}

