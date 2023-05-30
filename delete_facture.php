<?php
require_once 'db_connexion.php';
$pdo = connexion_bdd();

$id_facture = isset($_GET['id']) ? $_GET['id'] : null;

$sqlDelete = "DELETE FROM factures WHERE id_facture = :id_facture";
$stmtDelete = $pdo->prepare($sqlDelete);
$stmtDelete->execute(['id_facture' => $id_facture]);

header('Location: admin_facture.php');
exit;
?>

