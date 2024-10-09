<?php
include 'bdd.php';
$import = false; // Change à true pour simuler l'import

$data = [];

if ($import) {
    // Si import est vrai, on utilise les API pour récupérer les données
    $api_url1 = "https://api.example.com/data1"; // Remplace par ton URL
    $api_url2 = "https://api.example.com/data2"; // Remplace par ton URL

    // Requête API 1
    $response1 = file_get_contents($api_url1);
    $result1 = json_decode($response1, true);

    // Requête API 2
    $response2 = file_get_contents($api_url2);
    $result2 = json_decode($response2, true);

    // Combiner les données récupérées
    if (!empty($result1) && !empty($result2)) {
        foreach ($result1 as $point) {
            $data[] = [$point['x'], $point['y']];
        }
        foreach ($result2 as $point) {
            $data[] = [$point['x'], $point['y']];
        }
    }
} else {
    // Si $import est faux, récupérer les données depuis la base de données
    $sql = "SELECT * FROM Info_Doc"; // Mise à jour de la requête SQL
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Supposons que les colonnes pertinentes sont nommées 'x' et 'y'
            if (isset($row['x']) && isset($row['y'])) {
                $data[] = ['x' => $row['x'], 'y' => $row['y']];
            }
        }
    }
}