<?php
session_start();
require_once 'config.php';

try {
    $pdo = getDbConnexion();
    $stmt = $pdo->prepare("SELECT Id_Labo, Nom FROM labo");
    $stmt->execute();
    $laboratoires = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<style>
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
        td a {
            color: black; 
            text-decoration: none;
            font-weight: bold; 
        }
        td a:hover {
            text-decoration: underline;
        }
        h1 {
            text-align: center; 
        }
        .button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            color: white;
            background-color: #4CAF50;
            cursor: pointer;
            font-weight: bold;
            margin: 5px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #45a049;
        }
        .title-and-button {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .title-and-button h2 {
            flex-grow: 1;
            text-align: center;
            margin: 0;
        }
    </style>

<!DOCTYPE html>
<html lang="fr">
<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        
        <?php include 'bandeau.php'; ?>

        <main>
            <div class="title-and-button">
                <h2>Laboratoires de Biologie Médicale</h2>
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['Type'] === ADMIN): ?>
                    <button class="button" onclick="location.href='ajouterLabo.php'">Ajouter Laboratoire</button>
                <?php endif; ?>
            </div>
            
            <table>
                <tr>
                    <th>Nom du Laboratoire</th>
                </tr>

                <?php if (!empty($laboratoires)): ?>
                    <?php foreach ($laboratoires as $laboratoire): ?>
                        <tr>
                            <td>
                                <a href="ficheLabo.php?id=<?php echo htmlspecialchars($laboratoire['Id_Labo']); ?>">
                                    <?php echo htmlspecialchars($laboratoire['Nom']); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td>Aucun laboratoire trouvé.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </main>
        
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
