<?php
session_start();
include 'header.php';
include 'bandeau.php';
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $pdo = getDbConnexion();

        $stmt = $pdo->prepare("SELECT id_U, Mdp FROM utilisateur WHERE Email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['Mdp'])) {
            $_SESSION['id_U'] = $user['id_U'];
            echo "Connexion réussie!";
            header('Location: protected_page.php');
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
