<?php
session_start();
if (!isset($_SESSION['pdfHtml'])) {
    echo "Aucun PDF n'a été importé ou converti.";
    exit;
}
$htmlContentUrl = $_SESSION['pdfHtml'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contenu du PDF Converti</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Contenu du PDF Converti</h1>
    </header>

    <section class="container">
        <h2>Voici le contenu extrait du fichier PDF :</h2>
        <iframe src="<?php echo $htmlContentUrl; ?>" style="width: 100%; height: 600px;" frameborder="0"></iframe>
    </section>

    <footer>
        <p>Créé par Moi - 2024</p>
    </footer>
</body>
</html>

<?php
// Nettoyer la session après l'affichage
session_unset();
session_destroy();
?>
