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

    // Requête pour vérifier les identifiants (avec un point d'interrogation pour le placeholder)
    $sql = "SELECT * FROM utilisateur WHERE nom_utilisateur = ?";
    
    // Préparation de la requête
    $stmt = $bdd->prepare($sql);

    if ($stmt === false) {
        // En cas d'erreur de préparation, on l'enregistre dans le fichier erreurs.log
        error_log("Erreur de préparation de la requête: " . $bdd->error, 3, 'erreurs.log');
        echo "Erreur de préparation de la requête. Veuillez réessayer plus tard.";
        exit();
    }

    // Lier le paramètre (ici 's' pour string)
    $stmt->bind_param('s', $nom_utilisateur);

    try {
        // Exécuter la requête
        $stmt->execute();
        
        // Obtenir les résultats
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Vérification du mot de passe haché
        if ($user && $user['mot_de_passe'] === $mot_de_passe) {
            // Connexion réussie
            $_SESSION['user'] = $user['nom_utilisateur'];
            header("Location: dashboard.php"); // Redirection vers le tableau de bord
            exit();
        } else {
            // Erreur d'authentification
            $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
            error_log($error_message, 3, 'erreurs.log'); // Enregistre l'erreur dans le fichier erreurs.log
            echo $error_message; // Affiche le message d'erreur (pour le développement, à retirer en prod)
        }
    } catch (Exception $e) {
        // Enregistre les exceptions dans le fichier erreurs.log
        error_log("Erreur de base de données: " . $e->getMessage(), 3, 'erreurs.log');
        echo "Erreur de base de données. Veuillez réessayer plus tard."; // Afficher une erreur plus générique
    }
} else {
    // Enregistre l'erreur si le formulaire n'a pas été soumis correctement
    $error_message = "Le formulaire n'a pas été soumis correctement.";
    error_log($error_message, 3, 'erreurs.log');
    echo $error_message;
}
?>
