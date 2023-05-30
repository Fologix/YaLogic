<?php
session_start();
include_once 'db_connexion.php';
$pdo = connexion_bdd();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "Vous n'êtes pas connecté";
    exit;
}
?>

<a href="facture.php">facture</a>

