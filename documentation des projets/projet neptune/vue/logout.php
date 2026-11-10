<?php
require __DIR__ . '/../fonction/db.php';
neptune_session();

$_SESSION = [];
session_destroy();

header('Location: accueil.php');
exit;
