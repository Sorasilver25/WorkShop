<?php
$host = '127.0.0.1'; // Ou l'adresse de ton serveur de base de données
$dbname = 'workshop'; // Remplace par le nom de ta base de données
$username = 'root@localhost'; // Remplace par ton nom d'utilisateur
$password = ''; // Remplace par ton mot de passe

try {
    $bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Définit le mode d'erreur de PDO à Exception
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Enregistre l'erreur dans erreurs.log
    error_log("Erreur de connexion à la base de données: " . $e->getMessage(), 3, 'erreurs.log');
    // Affiche un message d'erreur dans le navigateur
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit();
}
?>
