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
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id_service = :id");
    $stmt->execute(['id' => $_GET['id']]);
    $service = $stmt->fetch();

    if (!$service) {
        exit('Service non trouvé.');
    }
} else {
    exit('Pas d\'ID de service spécifié.');
}

if (isset($_POST['designation'], $_POST['prix_unitaire'], $_POST['type_service'], $_POST['id_produit_stripe'])) {
    $designation = $_POST['designation'];
    $prix_unitaire = $_POST['prix_unitaire'];
    $type_service = $_POST['type_service'];
    $id_produit_stripe = $_POST['id_produit_stripe'];

    if (empty($designation) || empty($prix_unitaire) || empty($id_produit_stripe)) {
        $error = "Veuillez remplir tous les champs.";
    } elseif (!is_numeric($prix_unitaire) || $prix_unitaire < 0) {
        $error = "Le prix unitaire doit être un nombre positif.";
    } else {
        $stmt = $pdo->prepare("UPDATE services SET designation = :designation, prix_unitaire = :prix_unitaire, type_service = :type_service, id_produit_stripe = :id_produit_stripe WHERE id_service = :id");
        $stmt->execute([
            'designation' => $designation,
            'prix_unitaire' => $prix_unitaire,
            'type_service' => $type_service,
            'id_produit_stripe' => $id_produit_stripe,
            'id' => $_GET['id'],
        ]);

        header("Location: gestion_services.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Service</title>
</head>
<body>
<h1>Modifier Service</h1>
<?php if (isset($error)) { echo "<p>$error</p>"; } ?>
<form method="post">
    <label for="designation">Désignation :</label>
    <input type="text" name="designation" id="designation" value="<?php echo $service['designation']; ?>" required>
    <br>
    <label for="prix_unitaire">Prix Unitaire :</label>
    <input type="number" name="prix_unitaire" id="prix_unitaire" step="0.01" value="<?php echo $service['prix_unitaire']; ?>" required>
    <br>
    <label for="type_service">Type de service :</label>
    <select id="type_service" name="type_service" required>
        <option value="unique" <?php echo $service['type_service'] == 'unique' ? 'selected' : ''; ?>>Unique</option>
        <option value="récurrent" <?php echo $service['type_service'] == 'récurrent' ? 'selected' : ''; ?>>Récurrent</option>
    </select>
    <br>
    <label for="id_produit_stripe">Clé du produit Stripe :</label>
    <input type="text" name="id_produit_stripe" id="id_produit_stripe" value="<?php echo $service['id_produit_stripe']; ?>" required>
    <br>
    <input type="submit" value="Modifier">
</form>
</body>
</html>
