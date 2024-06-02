<?php
session_start();
require_once 'config.php';
verifierAdmin();
?>

<!DOCTYPE html>
<html>
<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php include 'bandeau.php'; ?>

        <main>
            <main>
            <h2>Ajouter un utilisateur</h2>
            <div class="button-container">
                <form action="ajouterAdmin.php" method="get">
                    <button type="submit">Ajouter Administrateur</button>
                </form>
                <form action="ajouterPatient.php" method="get">
                    <button type="submit">Ajouter Patient</button>
                </form>
                <form action="ajouterMedecin.php" method="get">
                    <button type="submit">Ajouter Médecin</button>
                </form>
            </div>
        </main>
        </main>
        
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
