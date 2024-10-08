<?php
session_start();
include 'bdd.php'; // Assure-toi que le chemin est correct

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_log("Début du script de connexion.", 3, 'erreurs.log');
// Vérifie si les données du formulaire ont été soumises
if (isset($_POST['nom_utilisateur']) && isset($_POST['mot_de_passe'])) {
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $mot_de_passe = hash('sha256', $_POST['mot_de_passe']); // Cryptage du mot de passe avec SHA-256

    // Requête pour vérifier les identifiants
    $sql = "SELECT * FROM Utilisateur WHERE nom_utilisateur = :nom_utilisateur";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
    
    try {
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['mot_de_passe'] === $mot_de_passe) {
            // Connexion réussie
            $_SESSION['user'] = $user['nom_utilisateur'];
            header("Location: dashboard.php");
            exit();
        } else {
            // Erreur d'authentification
            $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
            error_log($error_message, 3, 'erreurs.log'); // Enregistre l'erreur dans le fichier erreurs.log
        }
    } catch (Exception $e) {
        // Enregistre les exceptions dans le fichier erreurs.log
        error_log("Erreur de base de données: " . $e->getMessage(), 3, 'erreurs.log');
    }
} else {
    // Enregistre l'erreur si le formulaire n'est pas soumis correctement
    $error_message = "Le formulaire n'a pas été soumis correctement.";
    error_log($error_message, 3, 'erreurs.log');
}
?>
