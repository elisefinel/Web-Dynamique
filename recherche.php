<?php

require_once 'config.php';

if (isset($_POST['query'])) {
    $searchTerm = $_POST['query'];
    $pdo = getDbConnexion();

    
    $stmt = $pdo->prepare("SELECT * FROM table_name WHERE column_name LIKE :searchTerm");
    $stmt->execute(['searchTerm' => '%' . $searchTerm . '%']);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la Recherche</title>
    <link rel="stylesheet" href="projet.css">
</head>
<body>
    <div class="wrapper">
        <header>
            <img src="logo.png" alt="Medicare Logo" class="logo">
            <h1>Medicare: Services Médicaux</h1>
        </header>
        <nav>
            <ul>
                <li><a href="accueil.html">Accueil</a></li>
                <li><a href="parcourir.html">Tout Parcourir</a></li>
                <li><a href="recherche.html">Recherche</a></li>
                <li><a href="rdv.html">Rendez-vous</a></li>
                <li><a href="compte.html">Votre Compte</a></li>
            </ul>
        </nav>
        <main>
            <h2>Résultats de la recherche :</h2>
            <?php if (isset($results) && count($results) > 0): ?>
                <ul>
                    <?php foreach ($results as $result): ?>
                        <li><?php echo htmlspecialchars($result['column_name']); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Pas de resultats pour:  "<?php echo htmlspecialchars($searchTerm); ?>"</p>
            <?php endif; ?>
        </main>
        <footer>
            <strong>CONTACTS</strong>
            <p>MAIL: medicare@omnesante.fr</p>
            <p>TELEPHONE : 06 45 29 78 11</p>
            <p>ADRESSE : 41 Avenue de la Santé, PARIS </p>
        </footer>
    </div>
</body>
</html>
