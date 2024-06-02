<?php
session_start();
require_once 'ajouterUser.php';

verifierAdmin();

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userId = ajouterUser(ADMIN);
        if ($userId) {
            echo "Administrateur ajouté avec succès!";
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
                <form action="ajouterAdmin.php" method="POST">
                    <?php afficherFormulaireUser(); ?>
                    <button type="submit">Ajouter Administrateur</button>
                </form>
            </div>
        </main>
        
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
