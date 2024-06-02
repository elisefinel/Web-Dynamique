<?php
define('ADMIN', 'A');
define('PATIENT', 'P');
define('MEDECIN', 'M');

function getDbConnexion() {
    $host = 'localhost';
    $dbname = 'medicare';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

function verifierAdmin() {
    // session_start();
    if (!isset($_SESSION['user']) || $_SESSION['user']['Type'] !== ADMIN) {
        header('Location: connexion.php');
        exit();
    }
}	
?>

