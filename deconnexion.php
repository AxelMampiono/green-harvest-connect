<?php
session_start();

// Supprimer toutes les variables de session
$_SESSION = array();

// Détruire la session
session_destroy();

// Redirection vers la page de connexion ou votre page d'accueil
header("Location: index.php");
exit();
