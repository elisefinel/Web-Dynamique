<?php

require_once __DIR__ . '/config.php';

$searchTerm = '';

if (isset($_POST['query'])) {
    $searchTerm = $_POST['query'];
    $pdo = getDbConnexion();

    try {
        $stmt = $pdo->prepare("
            SELECT 'medecin' AS type, utilisateur.Nom AS Nom, medecin.Spe AS detail 
            FROM medecin 
            JOIN utilisateur ON medecin.Id_U = utilisateur.Id_U 
            WHERE utilisateur.Nom LIKE :searchTerm
            UNION
            SELECT 'service' AS type, Nom_service AS Nom, '' AS detail 
            FROM service 
            WHERE Nom_service LIKE :searchTerm
            UNION
            SELECT 'laboratoire' AS type, Nom AS Nom, Salle AS detail 
            FROM labo 
            WHERE Nom LIKE :searchTerm
        ");
        $stmt->execute(['searchTerm' => '%' . $searchTerm . '%']);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
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
                        <li>
                            <?php if ($result['type'] == 'medecin'): ?>
                                Médecin: <?php echo htmlspecialchars($result['Nom']); ?>, Spécialité: <?php echo htmlspecialchars($result['detail']); ?>
                            <?php elseif ($result['type'] == 'service'): ?>
                                Service: <?php echo htmlspecialchars($result['Nom']); ?>
                            <?php elseif ($result['type'] == 'laboratoire'): ?>
                                Laboratoire: <?php echo htmlspecialchars($result['Nom']); ?>, Salle: <?php echo htmlspecialchars($result['detail']); ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Rien ne correspond à votre recherche.</p>
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
