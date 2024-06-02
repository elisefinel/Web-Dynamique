<?php
session_start();
?>
<header>
    <img src="logo.png" alt="Medicare Logo" class="logo">
    <h1>Medicare: Services Médicaux</h1>
</header>
<nav>
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="parcourir.php">Tout Parcourir</a></li>
        <li><a href="recherche.php">Recherche</a></li>
        <?php if (isset($_SESSION['user'])): ?>
            <li><a href="rdv.php">Rendez-vous</a></li>
        <?php endif; ?>
        <li><a href="connexion.php">Votre Compte</a></li>
    </ul>
</nav>
