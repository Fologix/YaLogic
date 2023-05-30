<?php
session_start();

require_once 'dompdf/autoload.inc.php';
require_once 'db_connexion.php';

use Dompdf\Dompdf;
$dompdf = new Dompdf();
$pdo = connexion_bdd();

$id_facture = isset($_GET['id']) ? $_GET['id'] : null;

if($id_facture === null) {
    echo 'Aucun ID de facture spécifié.';
    exit;
}

// Sélection des informations client
$sqlClient = "SELECT c.id_client, c.nom_client, c.prenom_client, c.nom_societe, c.mail, c.password, f.id_facture, f.date_facture, s.designation, s.quantite, s.prix_unitaire
               FROM clients c
               INNER JOIN factures f ON c.id_client = f.id_client
               INNER JOIN services s ON f.id_facture = s.id_facture
               WHERE f.id_facture = :id";
$stmtClient = $pdo->prepare($sqlClient);
$stmtClient->execute(['id' => $id_facture]);
$clientData = $stmtClient->fetch();

if($clientData === false) {
    echo 'Aucune facture trouvée pour cet ID.';
    exit;
}

// Sélection de la date de prélèvement
$datePrelevement = date('d/m/Y', strtotime($clientData['date_facture']));

$html = '
<!DOCTYPE html>
<html>
<head>
<style>
body {
    background: linear-gradient(to right bottom, #021b3b, white);
    margin: 0;
    padding: 0;
}

h1 {
    color: white;
    background-color: #021b3b;
    padding: 10px;
}

table {
    width: 100%;
    margin-top: 20px;
}

thead th{
    background-color: #021b3b;
    color: white;
    padding: 15px; /* Espace ajouté autour des cellules du tableau */
    text-align: center; /* Centre le texte dans les cellules du tableau */
}

tbody td {
    padding: 15px; /* Espace ajouté autour des cellules du tableau */
    text-align: center; /* Centre le texte dans les cellules du tableau */
}

tfoot td {
    background-color: #021b3b;
    color: white;
    text-align: center; /* Centre le texte dans les cellules du tfoot */
}

#total-ht, #merci {
    font-size: 24px;
    color: #021b3b;
}

</style>
</head>
<body>

<h1>Facture</h1>

<div style="float: right;">
    <strong>Numéro de facture:</strong> ' . $clientData['id_facture'] . '<br>
</div>

<div>
    <h3>À l\'intention de:</h3>
    ' . $clientData["nom_client"] . ' ' . $clientData["prenom_client"] . '<br>
    ' . $clientData["nom_societe"] . '<br>
</div>

<div style="float: right;">
    <h3>De:</h3>
    YaLogic<br>
    07.67.45.45.75<br>
    yassine.verriez@hotmail.com<br>
    67 rue de la chaussée brunehaut<br>
    Feignies 59750<br>
</div>

<hr style="clear: both;">

<table>
    <thead>
        <tr>
            <th>Désignation</th>
            <th>Quantité</th>
            <th>Prix unitaire</th>
            <th>Montant HT</th>
        </tr>
    </thead>
    <tbody>';

$totalHT = 0;
do {
    $montantHT = $clientData["quantite"] * $clientData["prix_unitaire"];
    $totalHT += $montantHT;
    $html .= '
    <tr>
        <td style="text-align: center;">' . $clientData["designation"] . '</td>
        <td style="text-align: center;">' . $clientData["quantite"] . '</td>
        <td style="text-align: center;">' . number_format($clientData["prix_unitaire"], 2) . ' €</td>
        <td style="text-align: center;">' . number_format($montantHT, 2) . ' €</td>
    </tr>';
} while ($clientData = $stmtClient->fetch(PDO::FETCH_ASSOC));

$html .= '
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="text-align: center;"><strong>Montant HT :</strong></td>
            <td id="total-ht" style="color: white; text-align: center;">' . number_format($totalHT,2) . ' €</td>
        </tr>
    </tfoot>
</table>

<div style="margin-top: 30px;">
    <h3>Note :</h3>
    TVA non applicable, article 293 B du CGI<br>
    Condition de paiement : prélèvement automatique mensuel<br>
    Date du prélèvement : ' . $datePrelevement . '<br><br>
</div>

<div style="float: left; margin-top: 50px;">
    Cordialement,<br>
    Verriez Yassine<br>
    Directeur YaLogic
</div>

<div style="float: right;">
    <span id="merci" style="font-size: 32px;">Merci !</span>
</div>

<footer style="position: fixed; bottom: 0;left:0; width: 100%; text-align: center; background-color: #021b3b; color: white; padding: 5px;">
    67 rue de la chaussée brunehaut, Feignies 59750 | SIRET: XXXXXXXXXX
</footer>

</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("facture.pdf");
?>
