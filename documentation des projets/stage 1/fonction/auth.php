<?php
session_set_cookie_params(['path' => '/']);
session_start();

require_once 'db.php';

if (isset($_POST['user']) && isset($_POST['mdp'])) {
    
    $user = $_POST['user'];
    $mdp = $_POST['mdp'];

    $requete = $db->prepare("SELECT * FROM admin WHERE identifiant = ?");
    $requete->execute([$user]);
    $admin = $requete->fetch();

    if ($admin && password_verify($mdp, $admin['mot_de_passe'])) {
        $_SESSION['admin'] = $admin['identifiant'];
        
        header('Location: admin_pannel.php');
        exit();
    } else {
        header('Location: login.php?erreur=1');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}