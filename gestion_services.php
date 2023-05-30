<?php
session_start();
include_once 'db_connexion.php';

// Vérifie si l'utilisateur est connecté en tant qu'administrateur
if(!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

// Récupère les détails de l'administrateur
$pdo = connexion_bdd();
$stmt = $pdo->prepare("SELECT * FROM admin WHERE id_admin = :id_admin");
$stmt->execute(['id_admin' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: deconnexion.php");
    exit;
}

// Récupère tous les services
$stmt = $pdo->prepare("SELECT * FROM services");
$stmt->execute();
$services = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Services</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<a href="panel_admin.php">Retour au panel Admin</a>
<h1>Gestion des Services</h1>

<table>
    <thead>
    <tr>
        <th>Id</th>
        <th>Désignation</th>
        <th>Prix Unitaire</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($services as $service) : ?>
        <tr>
            <td><?php echo $service['id_service']; ?></td>
            <td><?php echo $service['designation']; ?></td>
            <td><?php echo $service['prix_unitaire']; ?> €</td>
            <td>
                <a href="modifier_service.php?id=<?php echo $service['id_service']; ?>">Modifier</a> |
                <a href="supprimer_service.php?id=<?php echo $service['id_service']; ?>">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<a href="ajouter_service.php">Ajouter un nouveau service</a>

</body>
</html>
