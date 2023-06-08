<?php
session_start();
include_once 'db_connexion.php';

// Vérifie si l'utilisateur est connecté
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

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<a href="deconnexion.php">Se déconnecter</a>
<h1>Bienvenue, <?php echo $user['nom_admin']; ?>!</h1>

<h2>Panneau de contrôle administrateur</h2>

<p>Voici quelques liens vers les différentes sections de votre site:</p>

<ul>
    <li><a href="gestion_client.php">Gestion des clients</a></li>
    <li><a href="gestion_factures.php">Gestion des factures</a></li>
    <li><a href="gestion_services.php">Gestion des services</a></li>
    <li><a href="affectation_service.php">Affectation des services</a></li>
    <li><a href="rapports.php">Rapports</a></li>
    <li><a href="parametres.php">Paramètres</a></li>
</ul>

</body>
</html>
