<?php
session_start();
include_once 'db_connexion.php';

// Vérifie si l'utilisateur est connecté
if(!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

$pdo = connexion_bdd();

// Récupère les détails du client
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id_client = :id_client");
$stmt->execute(['id_client' => $_SESSION['user_id']]);
$client = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Membre</title>
    <link rel="stylesheet" href="client.css">
</head>
<body>
<a href="deconnexion.php">Déconnexion</a>
<h1>Bienvenue, <?php echo $client['prenom_client'] . ' ' . $client['nom_client']; ?></h1>

<p>Que souhaitez-vous faire ?</p>

<ul>
    <li><a href="mes_services.php">Voir mes services</a></li>
    <li><a href="payer.php">Procéder au paiement</a></li>
    <li><a href="mise_a_jour_info.php">Mettre à jour mes informations</a></li>
    <li><a href="facture.php">Voir mes factures</a></li>
</ul>

</body>
</html>
