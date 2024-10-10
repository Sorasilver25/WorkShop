<?php
require 'vendor/autoload.php'; // Charger Guzzle via Composer
use GuzzleHttp\Client;

session_start();

// Remplacez par votre clé API
$apiKey = 'aniceto.eisi24@eleve-irup.com_H5oYJLrDgJAfvfsIqAXVRJQEnNlgKJ7VrUGS4vkZtIxpsbYniyDg9I8neQk6CRTb'; // Remplacez par votre clé API
$client = new Client();

try {
    // Tester un endpoint valide pour vérifier la connexion
    $response = $client->post('https://api.pdf.co/v1/info', [
        'headers' => [
            'x-api-key' => $apiKey,
            'Content-Type' => 'application/json'
        ],
        'verify' => false // Désactiver la vérification SSL
    ]);

    // Vérifier le code de réponse
    if ($response->getStatusCode() == 200) {
        echo "Connexion réussie à l'API PDF.co !";
    } else {
        echo "Erreur lors de la connexion à l'API. Code de réponse : " . $response->getStatusCode();
    }
} catch (Exception $e) {
    echo "Erreur lors de la requête API : " . $e->getMessage();
}
?>
