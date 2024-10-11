<?php
session_start();

// Vérifie si l'URL du fichier uploadé est présente dans la session
if (!isset($_SESSION['pdfUrl'])) {
    die('Erreur : URL du fichier non trouvée. Veuillez uploader un PDF d\'abord.');
}

// Clé API PDF.co
$apiKey = 'madam.eisi24@eleve-irup.com_ckwl1vjK7ibNHA5kjTew3MXniacZ2dgaduGkyYc2EClzNGTOIoHsDFBIsKSpqWPf'; // Remplacez par votre clé API

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
    'Accept: application/json', // Assurez-vous que l'API renvoie un JSON
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
// echo "Réponse brute de l'API :\n$response\n"; // Décommenter pour débogage

// Convertir la réponse JSON en tableau associatif
$responseData = json_decode($response, true);

// Vérifiez si la réponse est valide JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    die('Erreur lors du décodage JSON : ' . json_last_error_msg());
}

// Vérifiez si l'API a renvoyé une erreur
if (isset($responseData['error']) && $responseData['error']) {
    die('Erreur lors de la conversion : ' . $responseData['message']);
}

// Vérifiez si l'URL du XML converti est présente
if (isset($responseData['body'])) {
    // Décodez les caractères échappés
    $xmlString = html_entity_decode($responseData['body']); // Cela remplacera les entités HTML par les caractères correspondants

    // Charger le XML
    $xmlDoc = new DOMDocument();
    libxml_use_internal_errors(true); // Ignore les erreurs de parsing
    if (!$xmlDoc->loadXML($xmlString)) {
        die('Erreur lors du chargement du XML : ' . libxml_get_errors()[0]->message);
    }

    // Récupérer tous les éléments <text>
    $texts = $xmlDoc->getElementsByTagName('text');
    $textArray = [];

    foreach ($texts as $text) {
        $textValue = $text->nodeValue;

        // Vérifiez si le texte ne contient pas de caractères de nouvelle ligne, "Aucune vaccination renseignée", ou "-"
        if (strpos($textValue, "\n") === false && strpos($textValue, "Aucune vaccination renseignée") === false && strpos($textValue, "-") === false) {
            $textArray[] = $textValue; // Ajouter la valeur de chaque élément <text> au tableau si les conditions sont remplies
        }
    }

    // Au lieu d'afficher, on retourne le tableau des textes
    // Par exemple, vous pouvez renvoyer le tableau sous forme de JSON
    echo json_encode($textArray); // Renvoyer le tableau en JSON

} else {
    die('Erreur lors de la conversion : réponse inattendue de l\'API.');
}
?>
