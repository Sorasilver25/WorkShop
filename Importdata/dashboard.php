<?php
if (!isset($_GET['file'])) {
    echo "Aucun PDF n'a été importé.";
    exit;
}

$htmlFile = 'uploads/' . $_GET['file'];
if (!file_exists($htmlFile)) {
    echo "Le fichier HTML n'existe pas.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Lecture du PDF</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        header {
            background-color: #007bff;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: auto;
            margin-top: 2rem;
        }
        iframe {
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        footer {
            text-align: center;
            margin-top: 2rem;
            padding: 1rem;
            background-color: #f1f1f1;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <header>
        <h1>Tableau de Bord - Contenu du PDF Converti</h1>
    </header>

    <section class="container">
        <h2>Voici le contenu extrait du fichier PDF :</h2>
        <iframe src="<?php echo htmlspecialchars($htmlFile); ?>" style="width:100%; height:600px;"></iframe>
    </section>

    <footer>
        <p>&copy;e-note-health - Tous droits réservés - 2024</p>
    </footer>
</body>
</html>
