<?php
session_start();
require_once 'config.php';

if (!isset($_GET['id_rdv'])) {
    die("ID du rendez-vous manquant.");
}

$id_rdv = intval($_GET['id_rdv']);

try {
    $pdo = getDbConnexion();

    // Update the status of the appointment to 'annulé'
    $stmt = $pdo->prepare("UPDATE rdv SET Statut = 'annulé' WHERE Id_rdv = :id_rdv");
    $stmt->bindParam(':id_rdv', $id_rdv, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        echo "Rendez-vous annulé avec succès!";
        // Optionally, redirect to the RDV list page
        header('Location: rdv.php');
        exit();
    } else {
        throw new Exception("Erreur lors de l'annulation du rendez-vous.");
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
