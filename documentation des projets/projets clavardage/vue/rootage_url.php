<?php

if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['is_logged']) || $_SESSION['is_logged'] !== true) {
    header("Location: start.php");
    exit;
}

//$allowed_pages = [
//    'index.php',
//    'login.php',
//    'signin.php',
//    'conversations.php',
//    'nouvelle_conversation.php',
//    'chat.php'
//];
//
//$current_page = basename($_SERVER['PHP_SELF']);
//
//if (!in_array($current_page, $allowed_pages)) {
//    header('Location: index.php');
//    exit;
//}


