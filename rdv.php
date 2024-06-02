<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit();
}

$id_pat = $_SESSION['user']['Id_U'];

try {
    $pdo = getDbConnexion();
    
    // Requête pour les rendez-vous avec les médecins
    $stmt_med = $pdo->prepare("
        SELECT rdv.Id_rdv, rdv.Date, horaire.Label AS Heure, medecin.Salle, rdv.Tarif, rdv.Statut,
               CONCAT('Dr. ', utilisateur.Nom, ' ', utilisateur.Prenom) AS Nom
        FROM rdv
        LEFT JOIN horaire ON rdv.Id_horaire = horaire.Id_horaire
        LEFT JOIN medecin ON rdv.Id_med = medecin.Id_U
        LEFT JOIN utilisateur ON medecin.Id_U = utilisateur.Id_U
        WHERE rdv.Id_pat = :id_pat AND rdv.Id_med IS NOT NULL
        ORDER BY rdv.Date, horaire.Label
    ");
    $stmt_med->bindParam(':id_pat', $id_pat, PDO::PARAM_INT);
    $stmt_med->execute();
    $rdvs_med = $stmt_med->fetchAll(PDO::FETCH_ASSOC);

    // Requête pour les rendez-vous de services
    $stmt_serv = $pdo->prepare("
        SELECT rdv.Id_rdv, rdv.Date, horaire.Label AS Heure, service.Salle, rdv.Tarif, rdv.Statut,
               service.Nom_service AS Nom
        FROM rdv
        LEFT JOIN horaire ON rdv.Id_horaire = horaire.Id_horaire
        LEFT JOIN service ON rdv.Id_Service = service.Id_Service
        WHERE rdv.Id_pat = :id_pat AND rdv.Id_Service IS NOT NULL
        ORDER BY rdv.Date, horaire.Label
    ");
    $stmt_serv->bindParam(':id_pat', $id_pat, PDO::PARAM_INT);
    $stmt_serv->execute();
    $rdvs_serv = $stmt_serv->fetchAll(PDO::FETCH_ASSOC);

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
    button {
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        color: white;
        background-color: #f44336;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s;
    }
    button:hover {
        background-color: #e31b0c;
    }
</style>


<!DOCTYPE html>
<html lang="fr">
<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php include 'bandeau.php'; ?>
    
        <main>
            <h2>Mes Rendez-vous</h2>
			<br>
            
            <h3>Rendez-vous avec les Médecins</h3>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Salle</th>
                    <th>Tarif</th>
                    <th>Nom</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
                <?php if (!empty($rdvs_med)): ?>
                    <?php foreach ($rdvs_med as $rdv): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($rdv['Date']); ?></td>
                            <td><?php echo htmlspecialchars($rdv['Heure']); ?></td>
                            <td><?php echo htmlspecialchars($rdv['Salle']); ?></td>
                            <td><?php echo htmlspecialchars($rdv['Tarif']); ?> €</td>
                            <td><?php echo htmlspecialchars($rdv['Nom']); ?></td>
                            <td><?php echo htmlspecialchars($rdv['Statut']); ?></td>
                            <td>
                                <?php if ($rdv['Statut'] === 'valide'): ?>
                                    <button onclick="location.href='annulerRdv.php?id_rdv=<?php echo htmlspecialchars($rdv['Id_rdv']); ?>'">Annuler</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Aucun rendez-vous trouvé.</td>
                    </tr>
                <?php endif; ?>
            </table>

            <h3>Rendez-vous de Services</h3>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Salle</th>
                    <th>Tarif</th>
                    <th>Nom du Service</th>
                    <th>Statut du RDV</th>
                    <th>Action</th>
                </tr>
                <?php if (!empty($rdvs_serv)): ?>
                    <?php foreach ($rdvs_serv as $rdv): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($rdv['Date']); ?></td>
                            <td><?php echo htmlspecialchars($rdv['Heure']); ?></td>
                            <td><?php echo htmlspecialchars($rdv['Salle']); ?></td>
                            <td><?php echo htmlspecialchars($rdv['Tarif']); ?> €</td>
                            <td><?php echo htmlspecialchars($rdv['Nom']); ?></td>
                            <td><?php echo htmlspecialchars($rdv['Statut']); ?></td>
                            <td>
                                <?php if ($rdv['Statut'] === 'valide'): ?>
                                    <button onclick="location.href='annulerRdv.php?id_rdv=<?php echo htmlspecialchars($rdv['Id_rdv']); ?>'">Annuler</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Aucun rendez-vous trouvé.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </main>
    
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
