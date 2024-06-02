<?php
	session_start();
	require_once 'config.php';
	
	if (!isset($_GET['id'])) {
		die("ID du médecin manquant.");
	}
	
	$id = intval($_GET['id']);
	
	try {
		$pdo = getDbConnexion();
		$stmt = $pdo->prepare("SELECT utilisateur.Nom, utilisateur.Prenom, utilisateur.Email, utilisateur.Telephone, medecin.Photo, medecin.Num_identification, medecin.Spe, medecin.Tarif, medecin.Salle
							FROM utilisateur 
							INNER JOIN medecin ON utilisateur.Id_U = medecin.Id_U 
							WHERE utilisateur.Id_U = :id");
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$medecin = $stmt->fetch(PDO::FETCH_ASSOC);
	
		if (!$medecin) {
			die("Médecin non trouvé.");
		}
	} catch (PDOException $e) {
		echo "Erreur : " . $e->getMessage();
	}
?>

<script>
    function toggleCommOptions() {
        var commOptions = document.getElementById('commOptions');
        if (commOptions.style.display === "none" || commOptions.style.display === "") {
            commOptions.style.display = "block";
        } else {
            commOptions.style.display = "none";
        }
    }

    function textComm() {
        alert('Communication par texte non encore implémentée.');
    }

    function audioComm() {
        alert('Communication par audio non encore implémentée.');
    }

    function videoComm() {
        alert('Communication par vidéo non encore implémentée.');
    }

    function emailComm() {
        window.location.href = 'mailto:<?php echo htmlspecialchars($medecin['Email']); ?>';
    }

    function showCV() {
        var cvImage = document.getElementById('cvImage');
        if (cvImage.style.display === "none" || cvImage.style.display === "") {
            cvImage.style.display = "block";
        } else {
            cvImage.style.display = "none";
        }
    }
</script>

<style>
	.encadre_medecin {
		background-color: #f9f9f9;
		border: 1px solid #ddd;
		border-radius: 10px;
		padding: 20px;
		margin: 20px auto;
		display: flex;
		align-items: center;
		max-width: 1000px;
	}
	.encadre_medecin img {
		border-radius: 10px;
		max-width: 200px;
		margin-right: 20px;
	}
	.medecin_info {
		text-align: left;
	}
	.medecin_info h2 {
		margin-bottom: 10px;
	}
	.medecin_info p {
		margin-bottom: 5px;
	}
	.actions {
		margin-top: 10px;
	}
	.button {
		padding: 10px 20px;
		border: none;
		border-radius: 5px;
		color: white;
		cursor: pointer;
		font-weight: bold;
		margin: 5px;
		transition: background-color 0.3s;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.button i {
		margin-right: 8px;
	}
	.button-rdv {
		background-color: #4CAF50;
	}
	.button-rdv:hover {
		background-color: #45a049;
	}
	.button-comm {
		background-color: #008CBA;
	}
	.button-comm:hover {
		background-color: #007ba7;
	}
	.button-cv {
		background-color: #f44336;
	}
	.button-cv:hover {
		background-color: #e31b0c;
	}
	.comm-options {
		display: none;
		margin-top: 20px;
	}
	.comm-options button {
		display: block;
		margin: 5px 0;
		padding: 10px;
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
		background-color: #f0f0f0;
		border: 1px solid #ddd;
		border-radius: 5px;
		cursor: pointer;
		transition: background-color 0.3s;
	}
	.comm-options button i {
		margin-right: 10px;
	}
	.comm-options button:hover {
		background-color: #ddd;
	}
	#cvImage {
		display: none;
		margin: 20px auto;
		max-width: 1000px;
		text-align: center;
	}
	#cvImage img {
		max-width: 100%;
		border: 1px solid #ddd;
		border-radius: 10px;
	}
</style>

<!DOCTYPE html>
<html>
	<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php include 'bandeau.php'; ?>
        <main>
            <div class="encadre_medecin">
                <?php if (!empty($medecin['Photo'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($medecin['Photo']); ?>" alt="Photo de Dr. <?php echo htmlspecialchars($medecin['Nom']) . ' ' . htmlspecialchars($medecin['Prenom']); ?>">
                <?php else: ?>
                    <p>Aucune photo disponible</p>
                <?php endif; ?>
                <div class="medecin_info">
                    <h2>Dr. <?php echo htmlspecialchars($medecin['Nom']) . ' ' . htmlspecialchars($medecin['Prenom']); ?></h2>
                    <h2><?php echo htmlspecialchars($medecin['Spe']); ?></h2>
                    <p>Salle: <?php echo htmlspecialchars($medecin['Salle']); ?></p>
                    <p>Téléphone: <?php echo htmlspecialchars($medecin['Telephone']); ?></p>
                    <p>Email: <?php echo htmlspecialchars($medecin['Email']); ?></p>
                    <p>Tarif: <?php echo htmlspecialchars($medecin['Tarif']); ?> €</p>
                    <div class="actions">
                        <button class="button button-rdv" onclick="location.href='prendreRdv.php?id=<?php echo htmlspecialchars($id); ?>'">
                            <i class="fas fa-calendar-alt"></i> Prendre un RDV
                        </button>
                        <button class="button button-comm" onclick="toggleCommOptions()">
                            <i class="fas fa-comments"></i> Communiquer avec le médecin
                        </button>
                        <button class="button button-cv" onclick="showCV()">
                            <i class="fas fa-file-alt"></i> Voir son CV
                        </button>
                    </div>
                    <div id="commOptions" class="comm-options">
                        <button onclick="textComm()">
                            <i class="fas fa-sms"></i> Texte
                        </button>
                        <button onclick="audioComm()">
                            <i class="fas fa-microphone"></i> Audio
                        </button>
                        <button onclick="videoComm()">
                            <i class="fas fa-video"></i> Vidéo
                        </button>
                        <button onclick="emailComm()">
                            <i class="fas fa-envelope"></i> Courriel
                        </button>
                    </div>
                </div>
            </div>
            <div id="cvImage">
                <img src="cv1.jpg" alt="CV de Dr. <?php echo htmlspecialchars($medecin['Nom']) . ' ' . htmlspecialchars($medecin['Prenom']); ?>">
            </div>
        </main>
        <?php include 'footer.php'; ?>
    </div>

   
</body>
</html>
