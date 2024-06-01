<!DOCTYPE html>
<html>
<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
	<?php include 'bandeau.php'; ?>
		
	<main>
		<div class="login-form">
			<form action="login.php" method="POST">
				<label for="email">Email:</label>
				<input type="email" id="email" name="email" required><br>
	
				<label for="password">Mot de passe:</label>
				<input type="password" id="password" name="password" required><br>
	
				<button type="submit">Connexion</button>
			</form>
		</div>
	</main>

	<?php include 'footer.php'; ?>
    </div>
</body>
</html>