<?php
$servername = "localhost"; // Remplacez par le nom de votre serveur MySQL
$username = "root"; // Remplacez par votre nom d'utilisateur MySQL
$password = ""; // Remplacez par votre mot de passe MySQL
$dbname = "greenharvestconnect"; // Remplacez par le nom de votre base de données

try {
    // Établir une connexion PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // Définir le mode d'erreur de PDO sur Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Utiliser la connexion pour exécuter des requêtes SQL
    // ...

} catch (PDOException $e) {
    // En cas d'erreur de connexion, afficher l'erreur
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
