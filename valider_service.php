<?php
session_start();
include_once 'db_connexion.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

$pdo = connexion_bdd();

// Récupère l'identifiant du service_client
$id_service_client = isset($_GET['id']) ? $_GET['id'] : null;

if ($id_service_client === null) {
    echo 'Aucun ID de service spécifié.';
    exit;
}

// Récupère le service_client
$stmt = $pdo->prepare("SELECT services_clients.id AS service_client_id, services_clients.statut, services.designation, services.prix_unitaire, services.type_service
                       FROM services_clients
                       JOIN services ON services_clients.id_service = services.id_service
                       WHERE services_clients.id = :id_service_client");
$stmt->execute(['id_service_client' => $id_service_client]);
$service_client = $stmt->fetch();

if ($service_client === false) {
    echo 'Aucun service trouvé pour cet ID.';
    exit;
}

// Valider le service en mettant à jour le statut
$stmt = $pdo->prepare("UPDATE services_clients SET statut = 'validé' WHERE id = :id_service_client");
$stmt->execute(['id_service_client' => $id_service_client]);

// Rediriger vers la page mes_services.php
header("Location: mes_services.php");
exit;
?>
