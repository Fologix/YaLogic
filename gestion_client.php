<?php
session_start();
include_once 'db_connexion.php';

if (!isset($_SESSION['user_id'])) {
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

$stmt = $pdo->prepare("SELECT * FROM clients");
$stmt->execute();
$clients = $stmt->fetchAll();

if (isset($_POST['submit'])) {
    $id_client = $_POST['id_client'];
    $payment_link = $_POST['payment_link'];

    $stmt = $pdo->prepare("INSERT INTO paiements (id_client, payment_link, payment_status) VALUES (:id_client, :payment_link, 'Non payé')");
    $stmt->execute(['payment_link' => $payment_link, 'id_client' => $id_client]);

    $message = "Le lien de paiement a été ajouté pour le client.";
}

if (isset($_POST['update'])) {
    $payment_id = $_POST['payment_id'];
    $payment_status = $_POST['payment_status'];

    $stmt = $pdo->prepare("UPDATE paiements SET payment_status = :payment_status WHERE id = :payment_id");
    $stmt->execute(['payment_status' => $payment_status, 'payment_id' => $payment_id]);

    $message = "Le statut de paiement a été mis à jour.";
}

$stmt = $pdo->prepare("SELECT p.*, c.nom_client FROM paiements p LEFT JOIN clients c ON p.id_client = c.id_client");
$stmt->execute();
$paiements = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un lien de paiement pour un client</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<a href="deconnexion.php">Se déconnecter</a>
<h1>Bienvenue, <?php echo $user['nom_admin']; ?>!</h1>
<h2>Ajouter un lien de paiement pour un client</h2>
<?php if (isset($message)) { echo "<p>$message</p>"; } ?>
<form method="post">
    <label for="id_client">Client :</label>
    <select name="id_client" id="id_client">
        <?php foreach ($clients as $client) { ?>
            <option value="<?php echo $client['id_client']; ?>"><?php echo $client['nom_client']; ?></option>
        <?php } ?>
    </select>
    <br>
    <label for="payment_link">Lien de paiement :</label>
    <input type="text" name="payment_link" id="payment_link" required>
    <br>
    <button type="submit" name="submit">Ajouter le lien de paiement</button>
</form>

<h2>Liste des paiements</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Client</th>
        <th>Lien de paiement</th>
        <th>Statut de paiement</th>
        <th>Modifier</th>
    </tr>
    <?php foreach ($paiements as $paiement) { ?>
        <tr>
            <td><?php echo $paiement['id']; ?></td>
            <td><?php echo $paiement['nom_client']; ?></td>
            <td><?php echo $paiement['payment_link']; ?></td>
            <td><?php echo $paiement['payment_status']; ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="payment_id" value="<?php echo $paiement['id']; ?>">
                    <select name="payment_status">
                        <option value="Non payé" <?php if ($paiement['payment_status'] == 'Non payé') echo 'selected'; ?>>Non payé</option>
                        <option value="Payé" <?php if ($paiement['payment_status'] == 'Payé') echo 'selected'; ?>>Payé</option>
                    </select>
                    <button type="submit" name="update">Mettre à jour</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
</body>
</html>
