<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de Recherche</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .result {
            margin: 20px;
        }
        h2 {
            color: #333;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 10px 0;
            padding: 10px;
            background-color: #f4f4f4;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="result">
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medicare";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Erreur connexion : " . $conn->connect_error);
}

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    
    $sql = "SELECT * FROM services WHERE nom LIKE ? OR description LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%$query%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);

    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        
        echo "<h2>Résultats de la recherche pour '$query'</h2>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li><strong>" . htmlspecialchars($row['nom']) . ":</strong> " . htmlspecialchars($row['description']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Aucun résultat trouvé pour '$query'</p>";
    }

    
    $stmt->close();
} else {
    echo "<p>Veuillez faire une recherche.</p>";
}

$conn->close();
?>
</div>
</body>
</html>
