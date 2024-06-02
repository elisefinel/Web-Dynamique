<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';
?>
<header>
    <img src="logo.png" alt="Medicare Logo" class="logo">
    <h1>Medicare: Services Médicaux</h1>
    <?php if (isset($_SESSION['user'])): ?>
        <h3><?php echo htmlspecialchars($_SESSION['user']['Email']); ?></h3>
    <?php endif; ?>
</header>
<nav>
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="parcourir.php">Tout Parcourir</a></li>
        <li><a href="recherche.php">Recherche</a></li>
        <?php if (isset($_SESSION['user'])): ?>
            <li><a href="rdv.php">Rendez-vous</a></li>
			<li><a href="message.php">Mes Messages</a></li>
            <?php if ($_SESSION['user']['Type'] === ADMIN): ?>
                <li><a href="insertUtilisateur.php">Ajouter Utilisateur</a></li>
            <?php endif; ?>			
        <?php endif; ?>
        <li><a href="monCompte.php">Votre Compte</a></li>
		<?php if (isset($_SESSION['user'])): ?>
			<li><a href="logout.php">Deconnexion</a></li>
		<?php endif; ?>
    </ul>
</nav>
