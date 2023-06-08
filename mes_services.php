<?php
session_start();
include_once 'db_connexion.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

$pdo = connexion_bdd();

$stmt = $pdo->prepare("SELECT * FROM clients WHERE id_client = :id_client");
$stmt->execute(['id_client' => $_SESSION['user_id']]);
$client = $stmt->fetch();

// Récupération des liens de paiement pour ce client
$stmt = $pdo->prepare("SELECT * FROM paiements WHERE id_client = :id_client");
$stmt->execute(['id_client' => $_SESSION['user_id']]);
$paiements = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Client</title>
    <link rel="stylesheet" href="client.css">
</head>
<body>
<h1>Bienvenue, <?php echo $client['nom_client']; ?>!</h1>

<?php if(!empty($paiements)): ?>
    <h2>Vos liens de paiement :</h2>
    <?php foreach($paiements as $paiement): ?>
        <p>
            Lien de paiement : <a href="<?php echo $paiement['payment_link']; ?>" target="_blank"><?php echo $paiement['payment_link']; ?></a><br>
            Statut du paiement : <?php echo $paiement['payment_status']; ?>
        </p>
    <?php endforeach; ?>
<?php else: ?>
    <p>Il n'y a pas de lien de paiement pour l'instant.</p>
<?php endif; ?>

</body>
</html>
