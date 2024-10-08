<?php
session_start();

// Charger Guzzle via Composer
require 'vendor/autoload.php'; 
use GuzzleHttp\Client;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['pdfFile'])) {
    // Vérifie si le fichier téléchargé est un PDF
    if ($_FILES['pdfFile']['type'] == 'application/pdf') {
        $pdfFilePath = $_FILES['pdfFile']['tmp_name'];

        // Remplacez par votre clé API PDF.co
        $apiKey = 'sknoploch.eisi24@eleve-irup.com_uMJWdKRnmD7zwzKwiK6Ejb9hcVkj9u5gRgm3GjyAR1eBcg6UWTuefirnr6baxXDZ';

        // Créer une instance du client Guzzle
        $client = new Client();

        try {
            // Préparer la requête vers l'API PDF.co pour convertir le PDF en HTML
            $response = $client->post('https://api.pdf.co/v1/pdf/convert/to/html', [
                'headers' => [
                    'x-api-key' => $apiKey,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'url' => '', // Laissez vide si vous n'avez pas d'URL
                    'file' => base64_encode(file_get_contents($pdfFilePath)) // Encodez le fichier en base64
                ]
            ]);

            // Décoder la réponse de l'API
            $result = json_decode($response->getBody(), true);

            // Vérifiez si la conversion a réussi
            if (isset($result['error']) && $result['error']) {
                // Rediriger vers la page d'erreur avec le message
                $_SESSION['error_message'] = "Erreur lors de la conversion : " . $result['message'];
                header('Location: error.php'); // Redirection vers la page d'erreur
                exit;
            }

            // Récupérez l'URL du contenu HTML converti
            $htmlContentUrl = $result['url']; // URL du fichier HTML converti

            // Stocker l'URL du HTML dans une session pour l'afficher plus tard
            $_SESSION['pdfHtml'] = $htmlContentUrl;

            // Rediriger vers la page d'affichage
            header('Location: display.php');
            exit; // Important d'exiter après avoir envoyé l'en-tête

        } catch (Exception $e) {
            // Rediriger vers la page d'erreur avec le message
            $_SESSION['error_message'] = "Erreur lors de la requête API : " . $e->getMessage();
            header('Location: error.php'); // Redirection vers la page d'erreur
            exit;
        }
    } else {
        // Rediriger vers la page d'erreur si le fichier n'est pas un PDF
        $_SESSION['error_message'] = "Erreur : Veuillez importer un fichier PDF valide.";
        header('Location: error.php'); // Redirection vers la page d'erreur
        exit;
    }
} else {
    // Rediriger vers la page d'erreur si la méthode n'est pas POST
    $_SESSION['error_message'] = "Le formulaire n'a pas été soumis correctement.";
    header('Location: error.php'); // Redirection vers la page d'erreur
    exit;
}
?>