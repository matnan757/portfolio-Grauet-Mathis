<?php

require '../controller/guard.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$id_discussion = (int)$_GET['id_discussion'];




function getDb(): PDO
{
    require_once __DIR__ . '/../fonction/db.php';
    return clavarde_connect();
}
function deletmess(int $id_discussion) :void
{
    $pdo = getDb();
    $sql = 'DELETE FROM `discussion` WHERE `discussion`.`id_discussion` = :id_discussion' ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('id_discussion', $id_discussion, PDO::PARAM_INT);
    $stmt->execute();

    header("location: /../vue/conversations.php");

}

deletmess($id_discussion);



?>