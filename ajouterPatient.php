<?php
session_start();
require_once 'ajouterUser.php';
verifierAdmin();

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userId = ajouterUser(PATIENT);
        if ($userId) {

            $numVital = $_POST['num_vital'];
            $dossier = $_POST['dossier'];
            $adresseLigne1 = $_POST['adresse_ligne_1'];
            $adresseLigne2 = $_POST['adresse_ligne_2'];
            $ville = $_POST['ville'];
            $codePostal = $_POST['code_postal'];
            $pays = $_POST['pays'];
            $typeCartePaiement = $_POST['type_carte_paiement'];
            $numCarte = $_POST['num_carte'];
            $nomCarte = $_POST['nom_carte'];
            $dateExpirationCarte = $_POST['date_expiration_carte'];
            $codeSecurite = $_POST['code_securite'];
            
            $pdo = getDbConnexion();
            $stmt = $pdo->prepare("INSERT INTO patient (Id_U, Num_vital, Dossier, Adresse_Ligne_1, Adresse_Ligne_2, Ville, Code_Postal, Pays, Type_Carte_Paiement, Num_Carte, Nom_Carte, Date_Expiration_Carte, Code_Securite) 
                                   VALUES (:id_u, :num_vital, :dossier, :adresse_ligne_1, :adresse_ligne_2, :ville, :code_postal, :pays, :type_carte_paiement, :num_carte, :nom_carte, :date_expiration_carte, :code_securite)");
            $stmt->bindParam(':id_u', $userId);
            $stmt->bindParam(':num_vital', $numVital);
            $stmt->bindParam(':dossier', $dossier);
            $stmt->bindParam(':adresse_ligne_1', $adresseLigne1);
            $stmt->bindParam(':adresse_ligne_2', $adresseLigne2);
            $stmt->bindParam(':ville', $ville);
            $stmt->bindParam(':code_postal', $codePostal);
            $stmt->bindParam(':pays', $pays);
            $stmt->bindParam(':type_carte_paiement', $typeCartePaiement);
            $stmt->bindParam(':num_carte', $numCarte);
            $stmt->bindParam(':nom_carte', $nomCarte);
            $stmt->bindParam(':date_expiration_carte', $dateExpirationCarte);
            $stmt->bindParam(':code_securite', $codeSecurite);

            if ($stmt->execute()) {
                echo "Patient ajouté avec succès!";
            } else {
                throw new Exception("Erreur lors de l'ajout du patient.");
            }
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
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
                <form action="ajouterPatient.php" method="POST">
                    <?php afficherFormulaireUser(); ?>

                    <label for="num_vital">Numéro de Sécurité Sociale:</label>
                    <input type="text" id="num_vital" name="num_vital" required><br>

                    <label for="dossier">Dossier:</label>
                    <input type="text" id="dossier" name="dossier"><br>

                    <label for="adresse_ligne_1">Adresse Ligne 1:</label>
                    <input type="text" id="adresse_ligne_1" name="adresse_ligne_1"><br>

                    <label for="adresse_ligne_2">Adresse Ligne 2:</label>
                    <input type="text" id="adresse_ligne_2" name="adresse_ligne_2"><br>

                    <label for="ville">Ville:</label>
                    <input type="text" id="ville" name="ville"><br>

                    <label for="code_postal">Code Postal:</label>
                    <input type="text" id="code_postal" name="code_postal"><br>

                    <label for="pays">Pays:</label>
                    <input type="text" id="pays" name="pays"><br>

                    <label for="type_carte_paiement">Type de Carte de Paiement:</label>
                    <select id="type_carte_paiement" name="type_carte_paiement">
                        <option value="Visa">Visa</option>
                        <option value="MasterCard">MasterCard</option>
                        <option value="American Express">American Express</option>
                        <option value="PayPal">PayPal</option>
                    </select><br>

                    <label for="num_carte">Numéro de Carte:</label>
                    <input type="text" id="num_carte" name="num_carte"><br>

                    <label for="nom_carte">Nom sur la Carte:</label>
                    <input type="text" id="nom_carte" name="nom_carte"><br>

                    <label for="date_expiration_carte">Date d'Expiration de la Carte:</label>
                    <input type="date" id="date_expiration_carte" name="date_expiration_carte"><br>

                    <label for="code_securite">Code de Sécurité:</label>
                    <input type="text" id="code_securite" name="code_securite" maxlength="4"><br>

                    <button type="submit">Ajouter Patient</button>
                </form>
            </div>
        </main>
        
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>

