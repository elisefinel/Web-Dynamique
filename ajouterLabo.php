<?php
session_start();
require_once 'config.php';

verifierAdmin();

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nom = $_POST['nom'];
        $salle = $_POST['salle'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $photo = file_get_contents($_FILES['photo']['tmp_name']);

        $pdo = getDbConnexion();
        $stmt = $pdo->prepare("INSERT INTO labo (Nom, Salle, Photo, Email, Telephone) 
                               VALUES (:nom, :salle, :photo, :email, :telephone)");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':salle', $salle);
        $stmt->bindParam(':photo', $photo, PDO::PARAM_LOB);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telephone', $telephone);

        if ($stmt->execute()) {
            echo "Laboratoire ajouté avec succès!";
        } else {
            throw new Exception("Erreur lors de l'ajout du laboratoire.");
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php include 'bandeau.php'; ?>

        <main>
            <div class="register-form">
                <form action="ajouterLabo.php" method="POST" enctype="multipart/form-data">
                    <label for="nom">Nom du Laboratoire:</label>
                    <input type="text" id="nom" name="nom" required><br>

                    <label for="salle">Salle:</label>
                    <input type="text" id="salle" name="salle" required><br>

                    <label for="photo">Photo:</label>
                    <input type="file" id="photo" name="photo" required><br>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email"><br>

                    <label for="telephone">Téléphone:</label>
                    <input type="text" id="telephone" name="telephone"><br>

                    <button type="submit">Ajouter Laboratoire</button>
                </form>
            </div>
        </main>
        
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
