<?php
include '../fonction/bdd.php';

if (isset($_POST['nom_utilisateur']) && isset($_POST['mot_de_passe']) && isset($_POST['securite_sociale']) && isset($_POST['date_naissance']) && isset($_POST['prenom']) && isset($_POST['nom'])) {
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $mot_de_passe = hash('sha256', $_POST['mot_de_passe']); // Cryptage du mot de passe avec SHA-256
    $securite_sociale = $_POST['securite_sociale'];
    $date_naissance = $_POST['date_naissance'];
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];

    // Insertion dans la base de données
    $sql = "INSERT INTO utilisateurs (nom_utilisateur, mot_de_passe, securite_sociale, date_naissance, prenom, nom) 
            VALUES (:nom_utilisateur, :mot_de_passe, :securite_sociale, :date_naissance, :prenom, :nom)";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
    $stmt->bindParam(':mot_de_passe', $mot_de_passe);
    $stmt->bindParam(':securite_sociale', $securite_sociale);
    $stmt->bindParam(':date_naissance', $date_naissance);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':nom', $nom);
    $stmt->execute();

    // Redirection après inscription réussie
    header("Location: connexion.html");
    exit();
}
?>
