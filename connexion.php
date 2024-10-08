<?php
session_start();
include '../bdd.php';

if (isset($_POST['nom_utilisateur']) && isset($_POST['mot_de_passe'])) {
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $mot_de_passe = hash('sha256', $_POST['mot_de_passe']); // Cryptage du mot de passe avec SHA-256

    // Requête pour vérifier les identifiants
    $sql = "SELECT * FROM Utilisateur WHERE nom_utilisateur = :nom_utilisateur";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
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
    }
}
?>
