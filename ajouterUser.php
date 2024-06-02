<?php
require_once 'config.php';

function ajouterUser($type) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $telephone = $_POST['telephone'];
        
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        try {
            $pdo = getDbConnexion();

            $stmt = $pdo->prepare("INSERT INTO utilisateur (Nom, Prenom, Email, Mdp, Telephone, Type) VALUES (:nom, :prenom, :email, :mdp, :telephone, :type)");
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mdp', $hashedPassword);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':type', $type);

            if ($stmt->execute()) {
                return $pdo->lastInsertId();
            } else {
                throw new Exception("Erreur lors de l'ajout de l'utilisateur.");
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur : " . $e->getMessage());
        }
    }
    return false;
}

function afficherFormulaireUser() {
    echo '
        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" required><br>

        <label for="prenom">Prénom:</label>
        <input type="text" id="prenom" name="prenom" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="telephone">Téléphone:</label>
        <input type="text" id="telephone" name="telephone" required><br>
    ';
}
?>
