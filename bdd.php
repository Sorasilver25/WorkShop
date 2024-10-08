<?php
$host = '127.0.0.1'; // Ou l'adresse de ton serveur de base de données
$dbname = 'workshop'; // Remplace par le nom de ta base de données
$username = 'root'; // Remplace par ton nom d'utilisateur
$password = ''; // Remplace par ton mot de passe

// Créer la connexion
$bdd = new mysqli($host, $username, $password, $dbname);

// Vérifier la connexion
if ($bdd->connect_error) {
    // Enregistre l'erreur dans erreurs.log
    error_log("Erreur de connexion à la base de données: " . $bdd->connect_error, 3, 'erreurs.log');
    // Affiche un message d'erreur dans le navigateur
    echo "Erreur de connexion à la base de données : " . $bdd->connect_error;
    exit();
}

// Optionnel : Définit le jeu de caractères pour la connexion
$bdd->set_charset("utf8");
?>
