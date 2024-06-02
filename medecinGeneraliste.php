<?php
session_start();
require_once 'config.php';

try {
    $pdo = getDbConnexion();
    $stmt = $pdo->prepare("SELECT utilisateur.Id_U, utilisateur.Nom, utilisateur.Prenom 
                           FROM utilisateur 
                           INNER JOIN medecin ON utilisateur.Id_U = medecin.Id_U 
                           WHERE medecin.Spe = 'Medecin Generaliste'");
    $stmt->execute();
    $medecins = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center; 
        vertical-align: middle; 
        color: black; 
        font-weight: bold; 
    }
    th {
        background-color: #649FCB;
        color: white;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2; 
    }
    tr:nth-child(odd) {
        background-color: #e6f7ff;
    }
    tr:hover {
        background-color: #cce7ff;
    }
    td a {
        color: black; 
        text-decoration: none;
        font-weight: bold; 
    }
    td a:hover {
        text-decoration: underline;
    }
    h1 {
        text-align: center; 
    }
    h2 {
        text-align: center; 
    }
</style>

<!DOCTYPE html>
<html lang="fr">
<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php include 'bandeau.php'; ?>

        <main>
            <table>
                <tr>
                    <th>Médecins Généralistes</th>
                </tr>

                <?php if (!empty($medecins)): ?>
                    <?php foreach ($medecins as $medecin): ?>
                        <tr>
                            <td>
                                <a href="ficheMedecin.php?id=<?php echo htmlspecialchars($medecin['Id_U']); ?>">
                                    Dr. <?php echo htmlspecialchars($medecin['Nom']) . ' ' . htmlspecialchars($medecin['Prenom']); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td>Aucun médecin généraliste trouvé.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </main>
        
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
