<?php
include 'bdd.php';

$logFile = __DIR__ . '/erreurs.log'; // Le fichier sera créé dans le même répertoire que bdd.php
error_log("Début du script d'inscription.", 3, 'erreurs.log');

if (isset($_POST['nom_utilisateur']) && isset($_POST['mot_de_passe']) && isset($_POST['securite_sociale']) && isset($_POST['date_naissance']) && isset($_POST['prenom']) && isset($_POST['nom'])) {
    $nom_utilisateur = $_POST['nom_utilisateur']; // string
    $mot_de_passe = hash('sha256', $_POST['mot_de_passe']); // string (crypté avec SHA-256)
    $securite_sociale = $_POST['securite_sociale']; // string (probablement une chaîne, car c'est souvent un identifiant alphanumérique)
    $date_naissance = $_POST['date_naissance']; // string (format de date, à vérifier selon ton modèle de BDD)
    $prenom = $_POST['prenom']; // string
    $nom = $_POST['nom']; // string

    // Insertion dans la base de données avec des placeholders `?`
    $sql = "INSERT INTO utilisateur (nom_utilisateur, mot_de_passe, securite_sociale, date_naissance, prenom, nom) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    // Préparation de la requête
    $stmt = $bdd->prepare($sql);
    
    if ($stmt === false) {
        error_log("Erreur de préparation de la requête : " . $bdd->error, 3, 'erreurs.log');
        die("Erreur de préparation de la requête.");
    }
    
    // Lier les paramètres : ici tout semble être des chaînes de caractères donc 'ssssss'
    // Si par exemple la sécurité sociale est un entier, tu utiliserais 'i' pour cet argument
    $stmt->bind_param('ssssss', $nom_utilisateur, $mot_de_passe, $securite_sociale, $date_naissance, $prenom, $nom);

    // Exécuter la requête
    if ($stmt->execute()) {
        // Redirection après inscription réussie
        header("Location: connexion.html");
        exit();
    } else {
        error_log("Erreur d'exécution de la requête : " . $stmt->error, 3, 'erreurs.log');
        echo "Erreur lors de l'inscription.";
    }

    // Fermer la déclaration
    $stmt->close();
}
?>
