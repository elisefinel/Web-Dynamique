<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';

if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de l'utilisateur</title>
    <link rel="stylesheet" href="projet.css">
</head>
<body>
    <div class="wrapper">
        <?php include 'header.php'; ?>
        <main>
            <h2>Profil de l'utilisateur</h2>
            <table>
                <?php foreach ($_SESSION['user'] as $key => $value): ?>
                    <tr>
                        <th><?php echo htmlspecialchars($key); ?></th>
                        <td><?php echo htmlspecialchars($value); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </main>
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
