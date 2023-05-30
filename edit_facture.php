<?php
require_once 'db_connexion.php';
$pdo = connexion_bdd();

$id_facture = isset($_GET['id']) ? $_GET['id'] : null;

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date_facture = $_POST['date_facture'];
    $sqlUpdate = "UPDATE factures SET date_facture = :date_facture WHERE id_facture = :id_facture";
    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->execute(['date_facture' => $date_facture, 'id_facture' => $id_facture]);
    header('Location: admin_facture.php');
    exit;
}

$sqlFacture = "SELECT * FROM factures WHERE id_facture = :id_facture";
$stmtFacture = $pdo->prepare($sqlFacture);
$stmtFacture->execute(['id_facture' => $id_facture]);
$factureData = $stmtFacture->fetch();

if($factureData === false) {
    echo 'Aucune facture trouvée pour cet ID.';
    exit;
}
?>
<form method="POST">
    <label>Date de la facture</label>
    <input type="date" name="date_facture" value="<?php echo $factureData['date_facture']; ?>">
    <button type="submit">Enregistrer les modifications</button>
</form>
