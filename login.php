<?php
session_start();
include 'header.php';
include 'bandeau.php';
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $pdo = getDbConnexion();

        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE Email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['Mdp'])) {
			$_SESSION['user'] = $user;
            echo "Connexion réussie!";
            header('Location: index.php');
            exit();
        } else {
            echo "Email ou mot de passe incorrect!";
        }
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }
}

include 'footer.php';
?>
