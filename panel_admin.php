<?php
session_start();
require_once 'db_connexion.php';
$pdo = connexion_bdd();

if (!isset($_SESSION['user_id'])) {
    echo "Vous n'êtes pas connecté";
    exit;
}

$sqlFactures = "SELECT c.id_client, c.nom_client, c.prenom_client, c.nom_societe, c.mail, f.id_facture, f.date_facture, SUM(fs.quantite * s.prix_unitaire) as total
               FROM clients c
               INNER JOIN factures f ON c.id_client = f.id_client
               INNER JOIN factures_services fs ON f.id_facture = fs.id_facture
               INNER JOIN services s ON fs.id_service = s.id_service
               GROUP BY f.id_facture";
$stmtFactures = $pdo->prepare($sqlFactures);
$stmtFactures->execute();
$facturesData = $stmtFactures->fetchAll();

echo "<table>
    <thead>
        <tr>
            <th>Client ID</th>
            <th>Nom du client</th>
            <th>Prénom du client</th>
            <th>Nom de la société</th>
            <th>Email</th>
            <th>ID de la facture</th>
            <th>Date de la facture</th>
            <th>Total de la facture</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>";

foreach ($facturesData as $facture) {
    echo "<tr>
        <td>{$facture['id_client']}</td>
        <td>{$facture['nom_client']}</td>
        <td>{$facture['prenom_client']}</td>
        <td>{$facture['nom_societe']}</td>
        <td>{$facture['mail']}</td>
        <td>{$facture['id_facture']}</td>
        <td>{$facture['date_facture']}</td>
        <td>{$facture['total']}</td>
        <td>
            <a href=\"edit_facture.php?id={$facture['id_facture']}\">Edit</a> | 
            <a href=\"delete_facture.php?id={$facture['id_facture']}\">Delete</a>
        </td>
    </tr>";
}

echo "</tbody></table>";

?>

<a href="service.php">services</a>