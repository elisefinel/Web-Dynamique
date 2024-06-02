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
    $id_pat = $_SESSION['user']['Id_U']; 
    $informations = $_POST['informations'];

    try {
        $pdo = getDbConnexion();

        $stmt = $pdo->prepare("SELECT Tarif, utilisateur.Nom, utilisateur.Prenom FROM medecin
                               INNER JOIN utilisateur ON medecin.Id_U = utilisateur.Id_U
                               WHERE medecin.Id_U = :id_med");
        $stmt->bindParam(':id_med', $id_med, PDO::PARAM_INT);
        $stmt->execute();
        $medecin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$medecin) {
            throw new Exception("Médecin non trouvé.");
        }

        $tarif = $medecin['Tarif'];
        $nom_medecin = $medecin['Nom'] . ' ' . $medecin['Prenom'];
        $statut = 'valide';

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
            $stmt = $pdo->prepare("SELECT Label FROM horaire WHERE Id_horaire = :id_horaire");
            $stmt->bindParam(':id_horaire', $id_horaire, PDO::PARAM_INT);
            $stmt->execute();
            $horaire = $stmt->fetch(PDO::FETCH_ASSOC);
            $label_horaire = $horaire['Label'];

            $contenu = "Votre rendez-vous avec le docteur $nom_medecin le $date à $label_horaire est confirmé.";
            $stmt = $pdo->prepare("INSERT INTO message (Id_expediteur, Id_destinataire, Contenu, Date_Heure, Statut) 
                                   VALUES (:id_expediteur, :id_destinataire, :contenu, NOW(), 'Pas lu')");
            $stmt->bindParam(':id_expediteur', $id_med, PDO::PARAM_INT);
            $stmt->bindParam(':id_destinataire', $id_pat, PDO::PARAM_INT);
            $stmt->bindParam(':contenu', $contenu);

            if ($stmt->execute()) {
                echo "Rendez-vous pris avec succès!";
            } else {
                throw new Exception("Erreur lors de l'envoi du message de confirmation.");
            }
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
