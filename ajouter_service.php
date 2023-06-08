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
        $stmt = $pdo->prepare("INSERT INTO services (designation, prix_unitaire, type_service, id_produit_stripe) VALUES (:designation, :prix_unitaire, :type_service, :id_produit_stripe)");
        $stmt->execute([
            'designation' => $designation,
            'prix_unitaire' => $prix_unitaire,
            'type_service' => $type_service,
            'id_produit_stripe' => $id_produit_stripe,
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
    <title>Ajouter Service</title>
</head>
<body>
<h1>Ajouter Service</h1>
<?php if (isset($error)) { echo "<p>$error</p>"; } ?>
<form method="post">
    <label for="designation">Désignation :</label>
    <input type="text" id="designation" name="designation" required>
    <br>
    <label for="prix_unitaire">Prix unitaire :</label>
    <input type="text" id="prix_unitaire" name="prix_unitaire" required>
    <br>
    <label for="type_service">Type de service :</label>
    <select id="type_service" name="type_service" required>
        <option value="unique">Unique</option>
        <option value="récurrent">Récurrent</option>
    </select>
    <br>
    <label for="id_produit_stripe">Clé du produit Stripe :</label>
    <input type="text" id="id_produit_stripe" name="id_produit_stripe" required>
    <br>
    <input type="submit" value="Ajouter">
</form>

</body>
</html>

