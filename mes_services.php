<?php
session_start();
include_once 'db_connexion.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

$pdo = connexion_bdd();

// Récupère les détails du client
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id_client = :id_client");
$stmt->execute(['id_client' => $_SESSION['user_id']]);
$client = $stmt->fetch();

// Récupère les services affectés au client
$stmt = $pdo->prepare("SELECT services.designation, services.prix_unitaire, services.type_service, services_clients.id AS service_client_id FROM services_clients JOIN services ON services_clients.id_service = services.id_service WHERE services_clients.id_client = :id_client");
$stmt->execute(['id_client' => $_SESSION['user_id']]);
$services = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Services</title>
    <link rel="stylesheet" href="client.css">
</head>
<body>
<a href="deconnexion.php">Déconnexion</a>
<h1>Mes Services</h1>

<table>
    <thead>
    <tr>
        <th>Désignation</th>
        <th>Prix Unitaire</th>
        <th>Type de Service</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($services as $service) : ?>
        <tr>
            <td><?php echo $service['designation']; ?></td>
            <td><?php echo $service['prix_unitaire']; ?> €</td>
            <td><?php echo ucfirst($service['type_service']); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
