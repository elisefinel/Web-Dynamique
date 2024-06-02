<?php
session_start();
require_once 'config.php';

if (!isset($_GET['id_service']) || !isset($_GET['id_horaire']) || !isset($_GET['date'])) {
    die("Paramètres manquants.");
}

$id_service = intval($_GET['id_service']);
$id_horaire = intval($_GET['id_horaire']);
$date = $_GET['date'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pat = $_SESSION['user']['Id_U']; 
    $informations = $_POST['informations'];

    try {
        $pdo = getDbConnexion();

        $stmt = $pdo->prepare("SELECT Tarif FROM service WHERE Id_Service = :id_service");
        $stmt->bindParam(':id_service', $id_service, PDO::PARAM_INT);
        $stmt->execute();
        $service = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$service) {
            throw new Exception("Service non trouvé.");
        }

        $tarif = $service['Tarif'];
        $statut = 'valide';

        $stmt = $pdo->prepare("INSERT INTO rdv (Id_pat, Id_Service, Date, Id_horaire, Statut, Tarif, Informations) 
                               VALUES (:id_pat, :id_service, :date, :id_horaire, :statut, :tarif, :informations)");
        $stmt->bindParam(':id_pat', $id_pat, PDO::PARAM_INT);
        $stmt->bindParam(':id_service', $id_service, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':id_horaire', $id_horaire, PDO::PARAM_INT);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':tarif', $tarif);
        $stmt->bindParam(':informations', $informations);

        if ($stmt->execute()) {
            echo "Rendez-vous pris avec succès!";
        } else {
            throw new Exception("Erreur lors de la prise du rendez-vous.");
        }
    } catch (Exception $e) {
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
            <h2>Prendre un Rendez-vous</h2>
            <div class="register-form">
                <form action="prendreRdvService2.php?id_service=<?php echo htmlspecialchars($id_service); ?>&id_horaire=<?php echo htmlspecialchars($id_horaire); ?>&date=<?php echo htmlspecialchars($date); ?>" method="POST">
                    <label for="informations">Informations:</label>
                    <textarea id="informations" name="informations" required></textarea><br>

                    <button type="submit">Confirmer le Rendez-vous</button>
                </form>
            </div>
        </main>
    
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
