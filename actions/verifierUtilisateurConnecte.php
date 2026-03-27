<?php
// Vérifier si la session n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['auth']) || $_SESSION['auth'] !== true){
    header('Location: loginFraisScolaire.php');
    exit();
}