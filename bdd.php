<?php
// Informations de connexion à la base de données
$host = 'localhost';  // Nom du serveur (ou IP)
$dbname = 'nom_de_la_base';  // Nom de ta base de données
$username = 'utilisateur';  // Nom d'utilisateur de la BDD
$password = 'mot_de_passe';  // Mot de passe de la BDD

// Définir le chemin du fichier de log pour les erreurs
$logFile = __DIR__ . '/erreurs.log'; // Le fichier sera créé dans le même répertoire que bdd.php

try {
    // Connexion à la base de données avec PDO
    $bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Définir le mode d'erreur de PDO sur exception
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Optionnel: message de succès pour tester la connexion
    // echo "Connexion réussie à la base de données";
    
} catch (PDOException $e) {
    // Enregistrer l'erreur dans le fichier erreurs.log
    error_log("[".date('Y-m-d H:i:s')."] Erreur de connexion : " . $e->getMessage() . "\n", 3, $logFile);

    // Afficher un message générique à l'utilisateur
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}
?>
