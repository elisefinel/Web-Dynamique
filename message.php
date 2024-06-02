<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit();
}

$id_user = $_SESSION['user']['Id_U'];

try {
    $pdo = getDbConnexion();

    $stmt = $pdo->prepare("
        SELECT message.Id_message, message.Contenu, message.Date_Heure, message.Statut,
               utilisateur.Nom, utilisateur.Prenom, utilisateur.Id_U AS IdUtilisateur,
               CASE 
                   WHEN message.Id_expediteur = :id_user THEN 'envoye'
                   WHEN message.Id_destinataire = :id_user THEN 'recu'
               END AS TypeMessage
        FROM message
        LEFT JOIN utilisateur ON (message.Id_expediteur = utilisateur.Id_U AND message.Id_destinataire = :id_user) 
                              OR (message.Id_destinataire = utilisateur.Id_U AND message.Id_expediteur = :id_user)
        WHERE message.Id_expediteur = :id_user OR message.Id_destinataire = :id_user
        ORDER BY message.Date_Heure DESC
    ");
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<?php include 'header.php'; ?>

<body>
    <div class="wrapper">
        <?php include 'bandeau.php'; ?>
    
        <main>
            <h2>Mes Messages</h2>
            <div class="messages-container">
                <?php if (!empty($messages)): ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="message <?php echo $message['TypeMessage'] == 'envoye' ? 'message-right' : 'message-left'; ?>">
                            <p><strong><?php echo date('d/m/Y H:i', strtotime($message['Date_Heure'])); ?></strong></p>
                            <p><?php echo $message['TypeMessage'] == 'envoye' ? 'À : ' : 'De : '; ?><?php echo htmlspecialchars($message['Nom']) . ' ' . htmlspecialchars($message['Prenom']); ?></p>
                            <p><?php echo htmlspecialchars($message['Contenu']); ?></p>
                            <?php if ($message['TypeMessage'] == 'recu'): ?>
                                <button onclick="location.href='envoyerMessage.php?id_destinataire=<?php echo htmlspecialchars($message['IdUtilisateur']); ?>'">Répondre</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun message trouvé.</p>
                <?php endif; ?>
            </div>
        </main>
    
        <?php include 'footer.php'; ?>
    </div>
</body>
</html>

<style>
    .messages-container {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    .message {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        margin: 10px 0;
        width: 48%;
    }
    .message-right {
        align-self: flex-end;
        background-color: #e6f7ff;
    }
    .message-left {
        align-self: flex-start;
        background-color: #f2f2f2;
    }
    .message p {
        margin: 5px 0;
    }
    .message button {
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        color: white;
        background-color: #008CBA;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s;
    }
    .message button:hover {
        background-color: #007ba7;
    }
</style>
