<?php
session_start();
require_once 'config.php';

verifierPatient();

if (!isset($_GET['id'])) {
    die("ID du service manquant.");
}

$id_service = intval($_GET['id']);

function convertirJourEnFrancais($day) {
    $jours = [
        'Monday' => 'Lundi',
        'Tuesday' => 'Mardi',
        'Wednesday' => 'Mercredi',
        'Thursday' => 'Jeudi',
        'Friday' => 'Vendredi',
        'Saturday' => 'Samedi',
        'Sunday' => 'Dimanche'
    ];
    return $jours[$day];
}

$date_aujourdhui = date('Y-m-d');
$jours_ouvres = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
$dates_semaine = [];

$timestamp = strtotime($date_aujourdhui);
while (count($dates_semaine) < 5) {
    $day_name = date('l', $timestamp);
    if (in_array($day_name, $jours_ouvres)) {
        $dates_semaine[] = date('Y-m-d', $timestamp);
    }
    $timestamp = strtotime('+1 day', $timestamp);
}

try {
    $pdo = getDbConnexion();

    $stmt = $pdo->prepare("SELECT * FROM horaire");
    $stmt->execute();
    $horaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $rdvs = [];
    foreach ($dates_semaine as $date) {
        $stmt = $pdo->prepare("SELECT * FROM rdv WHERE Id_Service = :id_service AND Date = :date AND Statut = 'valide'");
        $stmt->bindParam(':id_service', $id_service, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();
        $rdvs[$date] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid black;
        padding: 10px;
        text-align: center;
    }
    th {
        background-color: #f2f2f2;
    }
    .rdv-pris {
        background-color: #FF735E;
        color: black;
    }
    .rdv-dispo {
        background-color: #8BBA8C;
    }
    .rdv-dispo a {
        text-decoration: none;
        color: black;
    }
</style>

<!DOCTYPE html>
<html>
<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php include 'bandeau.php'; ?>
        <main>
            <table>
                <thead>
                    <tr>
                        <th>Heures</th>
                        <?php foreach ($dates_semaine as $date): ?>
                            <th><?php echo convertirJourEnFrancais(date('l', strtotime($date))) . ' ' . date('d/m', strtotime($date)); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($horaires as $horaire): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($horaire['Label']); ?></td>
                            <?php foreach ($dates_semaine as $date): ?>
                                <?php
                                $rdv_trouve = false;
                                if (isset($rdvs[$date])) {
                                    foreach ($rdvs[$date] as $rdv) {
                                        if ($rdv['Id_horaire'] == $horaire['Id_horaire']) {
                                            $rdv_trouve = true;
                                            break;
                                        }
                                    }
                                }
                                ?>
                                <td class="<?php echo $rdv_trouve ? 'rdv-pris' : 'rdv-dispo'; ?>">
                                    <?php if ($rdv_trouve): ?>
                                        Occupé
                                    <?php else: ?>
                                        <a href="prendreRdvService2.php?id_service=<?php echo $id_service; ?>&id_horaire=<?php echo $horaire['Id_horaire']; ?>&date=<?php echo $date; ?>">Disponible</a>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
        
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>
