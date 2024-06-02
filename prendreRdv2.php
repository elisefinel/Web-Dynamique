<?php
session_start();
require_once 'config.php';

if (!isset($_GET['id_med']) || !isset($_GET['id_horaire']) || !isset($_GET['date'])) {
    die("Paramètres manquants.");
}

$id_med = intval($_GET['id_med']);
$id_horaire = intval($_GET['id_horaire']);
$date = $_GET['date'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pat = $_SESSION['user']['Id_U']; // Assuming the user is logged in and their ID is stored in the session
    $informations = $_POST['informations'];

    try {
        $pdo = getDbConnexion();

        // Retrieve the tariff from the medecin table
        $stmt = $pdo->prepare("SELECT Tarif FROM medecin WHERE Id_U = :id_med");
        $stmt->bindParam(':id_med', $id_med, PDO::PARAM_INT);
        $stmt->execute();
        $medecin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$medecin) {
            throw new Exception("Médecin non trouvé.");
        }

        $tarif = $medecin['Tarif'];
        $statut = 'valide';

        // Insert the appointment into the rdv table
        $stmt = $pdo->prepare("INSERT INTO rdv (Id_pat, Id_med, Date, Id_horaire, Statut, Tarif, Informations) 
                               VALUES (:id_pat, :id_med, :date, :id_horaire, :statut, :tarif, :informations)");
        $stmt->bindParam(':id_pat', $id_pat, PDO::PARAM_INT);
        $stmt->bindParam(':id_med', $id_med, PDO::PARAM_INT);
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
                <form action="prendreRdv2.php?id_med=<?php echo htmlspecialchars($id_med); ?>&id_horaire=<?php echo htmlspecialchars($id_horaire); ?>&date=<?php echo htmlspecialchars($date); ?>" method="POST">
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
