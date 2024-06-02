<?php
session_start();
require_once 'ajouterUser.php';
verifierAdmin();

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userId = ajouterUser(MEDECIN);
        if ($userId) {
            $num_identification = $_POST['num_identification'];
            $spe = $_POST['spe'];
            $tarif = $_POST['tarif'];
            $cv = file_get_contents($_FILES['cv']['tmp_name']);
            $photo = file_get_contents($_FILES['photo']['tmp_name']);
            $photo2 = !empty($_FILES['photo2']['tmp_name']) ? file_get_contents($_FILES['photo2']['tmp_name']) : null;
            $video = !empty($_FILES['video']['tmp_name']) ? file_get_contents($_FILES['video']['tmp_name']) : null;
            $coordonnes = !empty($_POST['coordonnes']) ? $_POST['coordonnes'] : null;

            $pdo = getDbConnexion();
            $stmt = $pdo->prepare("INSERT INTO medecin (Id_U, Num_identification, Spe, Tarif, CV, Photo, Photo2, Video, Coordonnes) VALUES (:id_u, :num_identification, :spe, :tarif, :cv, :photo, :photo2, :video, :coordonnes)");
            $stmt->bindParam(':id_u', $userId);
            $stmt->bindParam(':num_identification', $num_identification);
            $stmt->bindParam(':spe', $spe);
            $stmt->bindParam(':tarif', $tarif);
            $stmt->bindParam(':cv', $cv, PDO::PARAM_LOB);
            $stmt->bindParam(':photo', $photo, PDO::PARAM_LOB);
            $stmt->bindParam(':photo2', $photo2, PDO::PARAM_LOB);
            $stmt->bindParam(':video', $video, PDO::PARAM_LOB);
            $stmt->bindParam(':coordonnes', $coordonnes);

            if ($stmt->execute()) {
                echo "Médecin ajouté avec succès!";
            } else {
                throw new Exception("Erreur lors de l'ajout du médecin.");
            }
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
                <form action="ajouterMedecin.php" method="POST" enctype="multipart/form-data">
                    <?php afficherFormulaireUser(); ?>
                    
                    <label for="num_identification">Numéro d'identification:</label>
                    <input type="text" id="num_identification" name="num_identification" required><br>

                    <label for="spe">Spécialité:</label>
                    <select id="spe" name="spe" required>
                        <option value="Medecin Generaliste">Médecin Généraliste</option>
                        <option value="Addictologie">Addictologie</option>
                        <option value="Andrologie">Andrologie</option>
                        <option value="Cardiologie">Cardiologie</option>
                        <option value="Dermatologie">Dermatologie</option>
                        <option value="Gastro-Hepato-Enterologie">Gastro-Hépato-Entérologie</option>
                        <option value="Gynecologie">Gynécologie</option>
                        <option value="I.S.T.">I.S.T.</option>
                        <option value="Osteopathie">Ostéopathie</option>
                    </select><br>

                    <label for="tarif">Tarif:</label>
                    <input type="number" step="0.01" id="tarif" name="tarif" required><br>

                    <label for="cv">CV:</label>
                    <input type="file" id="cv" name="cv" required><br>

                    <label for="photo">Photo:</label>
                    <input type="file" id="photo" name="photo" required><br>

                    <label for="photo2">Photo 2:</label>
                    <input type="file" id="photo2" name="photo2"><br>

                    <label for="video">Vidéo:</label>
                    <input type="file" id="video" name="video"><br>

                    <label for="coordonnes">Coordonnées:</label>
                    <input type="text" id="coordonnes" name="coordonnes"><br>

                    <button type="submit">Ajouter Médecin</button>
                </form>
            </div>
        </main>
        
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
