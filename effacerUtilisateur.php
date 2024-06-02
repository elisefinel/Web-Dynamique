<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['Type'] !== 'A') {
    header('Location: connexion.php');
    exit();
}

if (!isset($_GET['id'])) {
    die("ID de l'utilisateur manquant.");
}

$id = intval($_GET['id']);

try {
    $pdo = getDbConnexion();
    $stmt = $pdo->prepare("DELETE FROM utilisateur WHERE Id_U = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        header('Location: listeUtilisateur.php');
    } else {
        throw new Exception("Erreur lors de la suppression de l'utilisateur.");
    }
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
