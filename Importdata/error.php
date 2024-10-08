<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur</title>
    <link rel="stylesheet" href="style.css"> <!-- Lien vers votre CSS si nécessaire -->
</head>
<body>
    <div class="container">
        <h1>Erreur</h1>
        <p>
            <?php
            // Vérifier si un message d'erreur est stocké dans la session
            if (isset($_SESSION['error_message'])) {
                echo $_SESSION['error_message'];
                // Effacer le message après l'affichage
                unset($_SESSION['error_message']);
            } else {
                echo "Une erreur inconnue est survenue.";
            }
            ?>
        </p>
        <a href="test.php">Retour au formulaire</a> <!-- Lien vers la page de téléchargement -->
    </div