<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['Type'] !== 'A') {
    header('Location: connexion.php');
    exit();
}

try {
    $pdo = getDbConnexion();
    $stmt = $pdo->prepare("SELECT * FROM utilisateur");
    $stmt->execute();
    $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php include 'bandeau.php'; ?>

        <main>
            <h2>Liste des Utilisateurs</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Type</th>
                    <th>Date de Création</th>
                    <th>Action</th>
                </tr>
                <?php if (!empty($utilisateurs)): ?>
                    <?php foreach ($utilisateurs as $utilisateur): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($utilisateur['Id_U']); ?></td>
                            <td><?php echo htmlspecialchars($utilisateur['Nom']); ?></td>
                            <td><?php echo htmlspecialchars($utilisateur['Prenom']); ?></td>
                            <td><?php echo htmlspecialchars($utilisateur['Email']); ?></td>
                            <td><?php echo htmlspecialchars($utilisateur['Telephone']); ?></td>
                            <td><?php echo htmlspecialchars($utilisateur['Type']); ?></td>
                            <td><?php echo htmlspecialchars($utilisateur['Date_creation']); ?></td>
                            <td>
                                <button onclick="location.href='effacerUtilisateur.php?id=<?php echo htmlspecialchars($utilisateur['Id_U']); ?>'">Effacer</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Aucun utilisateur trouvé.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </main>

        <?php include 'footer.php'; ?>
    </div>
</body>
</html>

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
    button {
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        color: white;
        background-color: #f44336;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s;
    }
    button:hover {
        background-color: #e31b0c;
    }
</style>
