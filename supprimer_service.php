<?php
session_start();
include_once 'db_connexion.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

$pdo = connexion_bdd();
$stmt = $pdo->prepare("SELECT * FROM admin WHERE id_admin = :id_admin");
$stmt->execute(['id_admin' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: deconnexion.php");
    exit;
}

if (isset($_GET['id'])) {
    // Supprime d'abord toutes les références au service dans la table `services_clients`
    $stmt = $pdo->prepare("DELETE FROM services_clients WHERE id_service = :id");
    $stmt->execute(['id' => $_GET['id']]);

    // Ensuite, supprime le service lui-même
    $stmt = $pdo->prepare("DELETE FROM services WHERE id_service = :id");
    $stmt->execute(['id' => $_GET['id']]);
}

header("Location: gestion_services.php");
exit;
?>

