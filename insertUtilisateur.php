<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $type = $_POST['type'];

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        $pdo = getDbConnexion();

        $stmt = $pdo->prepare("INSERT INTO utilisateur (Nom, Prenom, Email, Mdp, Type) VALUES (:nom, :prenom, :email, :mdp, :type)");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mdp', $hashedPassword);
        $stmt->bindParam(':type', $type);

        if ($stmt->execute()) {
            echo "Utilisateur ajouté avec succès!";
        } else {
            echo "Erreur lors de l'ajout de l'utilisateur.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php include 'bandeau.php'; ?>

        <main>
            <div class="register-form">
                <form action="insertUtilisateur.php" method="POST">
                    <label for="nom">Nom:</label>
                    <input type="text" id="nom" name="nom" required><br>

                    <label for="prenom">Prénom:</label>
                    <input type="text" id="prenom" name="prenom" required><br>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required><br>

                    <label for="password">Mot de passe:</label>
                    <input type="password" id="password" name="password" required><br>

                    <label for="type">Type:</label>
                    <select id="type" name="type" required>
                        <option value="A">A</option>
                        <option value="P">P</option>
                        <option value="M">M</option>
                    </select><br>

                    <button type="submit">Ajouter Utilisateur</button>
                </form>
            </div>
        </main>
        
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
