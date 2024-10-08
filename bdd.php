<?php
// Informations de connexion à la base de données
$host = 'localhost';  // Nom du serveur (ou IP)
$dbname = 'workshop';  // Nom de ta base de données
$username = 'root@localhost';  // Nom d'utilisateur de la BDD
$password = '';  // Mot de passe de la BDD

try {
    // Connexion à la base de données avec PDO
    $bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Définir le mode d'erreur de PDO sur exception
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Optionnel: message de succès pour tester la connexion
    // echo "Connexion réussie à la base de données";
    
} catch (PDOException $e) {
    // En cas d'erreur, afficher le message
    die("Erreur de connexion : " . $e->getMessage());
}
?>
