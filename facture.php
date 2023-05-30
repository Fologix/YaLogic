<?php
session_start();
include_once 'db_connexion.php';
$pdo = connexion_bdd();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "Vous devez être connecté pour voir vos factures.";
    exit;
}

// Récupération du prénom de l'utilisateur
$stmt = $pdo->prepare("SELECT prenom_client FROM clients WHERE id_client = :id");
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();
$prenom = $user['prenom_client'];

// Récupération des factures de l'utilisateur
$stmt = $pdo->prepare('SELECT id_facture, date_facture FROM factures WHERE id_client = ?');
$stmt->execute([$_SESSION['user_id']]);
$factures = $stmt->fetchAll();

// Affichage du prénom de l'utilisateur
echo "Bonjour, " . $prenom . ". Voici vos factures:<br>";

// Affichage des factures
foreach ($factures as $facture) {
    echo '<a href="facturepdf.php?id=' . $facture['id_facture'] . '">Facture n°' . $facture['id_facture'] . ' du ' . $facture['date_facture'] . '</a><br>';
}
?>
