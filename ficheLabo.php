<?php
session_start();
require_once 'config.php';

if (!isset($_GET['id'])) {
    die("ID du laboratoire manquant.");
}

$id = intval($_GET['id']);

try {
    $pdo = getDbConnexion();
    $stmt = $pdo->prepare("SELECT Nom, Salle, Photo, Email, Telephone FROM labo WHERE Id_Labo = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $labo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$labo) {
        die("Laboratoire non trouvé.");
    }

    $stmt = $pdo->prepare("SELECT Nom_service, Salle, Tarif FROM service WHERE Id_Labo = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<style>
        .encadre_labo {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin: 20px auto;
            display: flex;
            align-items: center;
            max-width: 1000px;
        }
        .encadre_labo img {
            border-radius: 10px;
            max-width: 200px;
            margin-right: 20px;
        }
        .labo_info {
            text-align: left;
        }
        .labo_info h2 {
            margin-bottom: 10px;
        }
        .labo_info p {
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
        .button-service {
            background-color: #4CAF50;
        }
        .button-service:hover {
            background-color: #45a049;
        }
        .button-add-service {
            background-color: #008CBA;
        }
        .button-add-service:hover {
            background-color: #007ba7;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center; 
            vertical-align: middle; 
            color: black; 
            font-weight: bold; 
        }
        th {
            background-color: #649FCB;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2; 
        }
        tr:nth-child(odd) {
            background-color: #e6f7ff;
        }
        tr:hover {
            background-color: #cce7ff;
        }
        .title-and-button {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>

<!DOCTYPE html>
<html lang="fr">
<?php include 'header.php'; ?>
<body>
    <div class="wrapper">
        <?php include 'bandeau.php'; ?>
        <main>
            <div class="encadre_labo">
                <?php if (!empty($labo['Photo'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($labo['Photo']); ?>" alt="Photo de <?php echo htmlspecialchars($labo['Nom']); ?>">
                <?php else: ?>
                    <p>Aucune photo disponible</p>
                <?php endif; ?>
                <div class="labo_info">
                    <h2><?php echo htmlspecialchars($labo['Nom']); ?></h2>
                    <p>Salle: <?php echo htmlspecialchars($labo['Salle']); ?></p>
                    <p>Téléphone: <?php echo htmlspecialchars($labo['Telephone']); ?></p>
                    <p>Email: <?php echo htmlspecialchars($labo['Email']); ?></p>
                    <div class="actions">
                        <button class="button button-service" onclick="toggleServiceTable()">
                            <i class="fas fa-concierge-bell"></i> Services
                        </button>
                    </div>
                </div>
            </div>
            <div id="serviceTable" style="display: none;">
                <div class="title-and-button">
                    <h2>Services</h2>
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['Type'] === ADMIN): ?>
                        <button class="button button-add-service" onclick="location.href='ajouterService.php?id=<?php echo htmlspecialchars($id); ?>'">
                            <i class="fas fa-plus"></i> Ajouter Service
                        </button>
                    <?php endif; ?>
                </div>
                <table>
                    <tr>
                        <th>Nom du Service</th>
                        <th>Salle</th>
                        <th>Tarif</th>
                    </tr>
                    <?php if (!empty($services)): ?>
                        <?php foreach ($services as $service): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($service['Nom_service']); ?></td>
                                <td><?php echo htmlspecialchars($service['Salle']); ?></td>
                                <td><?php echo htmlspecialchars($service['Tarif']); ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">Aucun service trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </main>
        <?php include 'footer.php'; ?>
    </div>

    <script>
        function toggleServiceTable() {
            var serviceTable = document.getElementById('serviceTable');
            if (serviceTable.style.display === "none" || serviceTable.style.display === "") {
                serviceTable.style.display = "block";
            } else {
                serviceTable.style.display = "none";
            }
        }
    </script>
</body>
</html>
