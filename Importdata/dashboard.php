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
    <link rel="stylesheet" href="style.css">
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
        <p>Créé par Moi - 2024</p>
    </footer>
</body>
</html>
