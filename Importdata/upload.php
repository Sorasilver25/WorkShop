<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['pdfFile'])) {
    if ($_FILES['pdfFile']['type'] == 'application/pdf') {
        // Lire le fichier PDF
        $pdfContent = file_get_contents($_FILES['pdfFile']['tmp_name']);
        $base64Pdf = base64_encode($pdfContent);
        $_SESSION['pdfBase64'] = $base64Pdf;

        // Uploader le fichier PDF en base64 vers PDF.co pour obtenir un lien URL
        $apiKey = 'aniceto.eisi24@eleve-irup.com_QgZ3EFeQIvkGEdqVOcaQEq5lrOna9re8xMEw6FbBs4UXyILIIdhqlMxTByZCn49A'; // Remplacez par votre clé API
        $data = array(
            'file' => $base64Pdf,
            'name' => 'uploaded-file.pdf'
        );

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.pdf.co/v1/file/upload/base64');
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'x-api-key: ' . $apiKey,
            'Content-Type: application/json'
        ));
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        // **Désactiver la vérification SSL**
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($curl);
        
        // Vérifier les erreurs de CURL
        if (curl_errno($curl)) {
            echo json_encode(['success' => false, 'message' => 'Erreur CURL : ' . curl_error($curl)]);
            curl_close($curl);
            exit;
        }

        curl_close($curl);

        $responseData = json_decode($response, true);

        // Afficher la réponse de l'API pour mieux comprendre l'erreur
        if ($responseData) {
            if (isset($responseData['url'])) {
                $_SESSION['pdfUrl'] = $responseData['url']; // Stocke l'URL du fichier dans la session
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Réponse API : ' . json_encode($responseData)]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la communication avec l\'API. Réponse brute : ' . $response]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Veuillez importer un fichier PDF valide.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Aucun fichier PDF trouvé.']);
}
