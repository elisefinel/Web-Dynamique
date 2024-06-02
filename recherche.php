<?php
require_once 'config.php';

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
            OR medecin.Spe LIKE :searchTerm
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
<?php include 'header.php'; ?>
<body>
    <div class="wrapper">
        <?php include 'bandeau.php'; ?>
        <main>
            <h2>Recherche</h2>
            <form method="POST" action="recherche.php">
                <input type="text" name="query" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Rechercher...">
                <button type="submit">Rechercher</button>
            </form>

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
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
