<?php
session_start();  // Démarrer la session

// Détruire toutes les variables de session
$_SESSION = [];

// Détruire la session en cours
session_destroy();

// Rediriger vers la page de connexion (ou page d’accueil)
header("Location: login.php");
exit;
?>
