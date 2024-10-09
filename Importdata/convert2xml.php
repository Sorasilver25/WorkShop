<?php
session_start();

// Vérifie si l'URL du fichier uploadé est présente dans la session
if (!isset($_SESSION['pdfUrl'])) {
    die('Erreur : URL du fichier non trouvée. Veuillez uploader un PDF d\'abord.');
}

// Clé API PDF.co
$apiKey = 'sknoploch.eisi24@eleve-irup.com_uMJWdKRnmD7zwzKwiK6Ejb9hcVkj9u5gRgm3GjyAR1eBcg6UWTuefirnr6baxXDZ'; // Remplacez par votre clé API

// Récupère l'URL du fichier PDF uploadé
$uploadedFileUrl = $_SESSION['pdfUrl'];

// Création de l'objet de données pour la conversion en XML
$data = array(
    "url" => $uploadedFileUrl, // URL du fichier PDF uploadé
    "inline" => true, // Affiche le fichier dans le navigateur
    "pages" => "0-", // Toutes les pages
    "async" => false, // Non asynchrone
    "name" => "result.xml" // Nom du fichier de sortie
);

// Envoie une requête POST à l'API pour convertir en XML
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://api.pdf.co/v1/pdf/convert/to/xml');
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Accept: application/json',
    'Content-Type: application/json',
    'x-api-key: ' . $apiKey // Clé API PDF.co
));
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

// **Désactiver la vérification SSL (pour les tests)**
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

// Exécution de la requête
$response = curl_exec($curl);

// Vérifiez les erreurs cURL
if (curl_errno($curl)) {
    die('Erreur cURL : ' . curl_error($curl));
}

curl_close($curl);

// Afficher la réponse brute pour le débogage
echo '<pre>';
echo "Réponse brute de l'API :\n$response\n";
echo '</pre>';

// Conversion de la réponse JSON
$responseData = json_decode($response, true);

// Vérifiez si la réponse est valide JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    die('Erreur lors du décodage JSON : ' . json_last_error_msg());
}

// Affichez la réponse de l'API pour le débogage
echo '<pre>';
print_r($responseData);
echo '</pre>';

if (isset($responseData['error']) && $responseData['error']) {
    die('Erreur lors de la conversion : ' . $responseData['message']);
}

// Si la conversion est réussie, ouvrez le fichier XML converti dans un nouvel onglet
if (isset($responseData['url'])) {
    echo '<h1>Conversion réussie !</h1>';
    echo '<a href="' . $responseData['url'] . '" target="_blank">Afficher le fichier XML converti</a>';
} else {
    die('Erreur lors de la conversion : réponse inattendue de l\'API.');
}
?>
