<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit();
}

if (!isset($_GET['id_destinataire'])) {
    die("ID du destinataire manquant.");
}

$id_expediteur = $_SESSION['user']['Id_U'];
$id_destinataire = intval($_GET['id_destinataire']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contenu = $_POST['contenu'];
    $date_heure = date('Y-m-d H:i:s');
    $statut = 'Pas lu';

    try {
        $pdo = getDbConnexion();

        $stmt = $pdo->prepare("INSERT INTO message (Id_expediteur, Id_destinataire, Contenu, Date_Heure, Statut) 
                               VALUES (:id_expediteur, :id_destinataire, :contenu, :date_heure, :statut)");
        $stmt->bindParam(':id_expediteur', $id_expediteur, PDO::PARAM_INT);
        $stmt->bindParam(':id_destinataire', $id_destinataire, PDO::PARAM_INT);
        $stmt->bindParam(':contenu', $contenu, PDO::PARAM_STR);
        $stmt->bindParam(':date_heure', $date_heure, PDO::PARAM_STR);
        $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "Message envoyé avec succès!";
        } else {
            throw new Exception("Erreur lors de l'envoi du message.");
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
            <h2>Envoyer un Message</h2>
            <div class="register-form">
                <form action="envoyerMessage.php?id_destinataire=<?php echo htmlspecialchars($id_destinataire); ?>" method="POST">
                    <label for="contenu">Message:</label>
                    <textarea id="contenu" name="contenu" required></textarea><br>

                    <button type="submit">Envoyer</button>
                </form>
            </div>
        </main>
    
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>

<style>
    .register-form {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
    }
    .register-form label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
    }
    .register-form textarea {
        width: 100%;
        height: 100px;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .register-form button {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        color: white;
        background-color: #4CAF50;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s;
    }
    .register-form button:hover {
        background-color: #45a049;
    }
</style>
