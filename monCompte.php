<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';

if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $id_texto = $_POST['id_texto'];
    $id_audio = $_POST['id_audio'];
    $id_video = $_POST['id_video'];

    try {
        $pdo = getDbConnexion();

        $stmt = $pdo->prepare("UPDATE utilisateur SET Nom = :nom, Prenom = :prenom, Email = :email, Id_texto = :id_texto, Id_audio = :id_audio, Id_video = :id_video WHERE Id_U = :id_U");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id_texto', $id_texto);
        $stmt->bindParam(':id_audio', $id_audio);
        $stmt->bindParam(':id_video', $id_video);
        $stmt->bindParam(':id_U', $_SESSION['user']['Id_U']);

        if ($stmt->execute()) {
            $rowsAffected = $stmt->rowCount();
            if ($rowsAffected > 0) {
                $_SESSION['user']['Nom'] = $nom;
                $_SESSION['user']['Prenom'] = $prenom;
                $_SESSION['user']['Email'] = $email;
                $_SESSION['user']['Id_texto'] = $id_texto;
                $_SESSION['user']['Id_audio'] = $id_audio;
                $_SESSION['user']['Id_video'] = $id_video;
                echo "Informations mises à jour avec succès!";
            } else {
                echo "Aucune information n'a été mise à jour.";
            }
        } else {
            echo "Erreur lors de la mise à jour des informations.";
        }
    } catch (PDOException $e) {
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
            <h2>Profil de l'utilisateur</h2>
            <form action="monCompte.php" method="POST">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($_SESSION['user']['Nom']); ?>" required><br>

                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($_SESSION['user']['Prenom']); ?>" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user']['Email']); ?>" required><br>

                <label for="id_texto">ID Texto:</label>
                <input type="text" id="id_texto" name="id_texto" value="<?php echo htmlspecialchars($_SESSION['user']['Id_texto']); ?>"><br>

                <label for="id_audio">ID Audio:</label>
                <input type="text" id="id_audio" name="id_audio" value="<?php echo htmlspecialchars($_SESSION['user']['Id_audio']); ?>"><br>

                <label for="id_video">ID Vidéo:</label>
                <input type="text" id="id_video" name="id_video" value="<?php echo htmlspecialchars($_SESSION['user']['Id_video']); ?>"><br>

                <button type="submit">Mettre à jour</button>
            </form>
        </main>
        
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
