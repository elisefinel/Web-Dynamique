<?php
session_start();
require_once 'config.php';

verifierAdmin();

if (!isset($_GET['id'])) {
    die("ID du laboratoire manquant.");
}

$id = intval($_GET['id']);

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nom_service = $_POST['nom_service'];
        $salle = $_POST['salle'];
        $tarif = $_POST['tarif'];

        $pdo = getDbConnexion();
        $stmt = $pdo->prepare("INSERT INTO service (Id_Labo, Nom_service, Salle, Tarif) 
                               VALUES (:id_labo, :nom_service, :salle, :tarif)");
        $stmt->bindParam(':id_labo', $id);
        $stmt->bindParam(':nom_service', $nom_service);
        $stmt->bindParam(':salle', $salle);
        $stmt->bindParam(':tarif', $tarif);

        if ($stmt->execute()) {
            echo "Service ajouté avec succès!";
        } else {
            throw new Exception("Erreur lors de l'ajout du service.");
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php include 'bandeau.php'; ?>
    
        <main>
            <h2>Ajouter un Service</h2>
            <div class="register-form">
                <form action="ajouterService.php?id=<?php echo htmlspecialchars($id); ?>" method="POST">
                    <label for="nom_service">Nom du Service:</label>
                    <input type="text" id="nom_service" name="nom_service" required><br>

                    <label for="salle">Salle:</label>
                    <input type="text" id="salle" name="salle" required><br>

                    <label for="tarif">Tarif:</label>
                    <input type="number" step="0.01" id="tarif" name="tarif" required><br>

                    <button type="submit">Ajouter Service</button>
                </form>
            </div>
        </main>
    
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
