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

if (isset($_POST['designation'], $_POST['prix_unitaire'])) {
    $designation = $_POST['designation'];
    $prix_unitaire = $_POST['prix_unitaire'];

    if (empty($designation) || empty($prix_unitaire)) {
        $error = "Veuillez remplir tous les champs.";
    } elseif (!is_numeric($prix_unitaire) || $prix_unitaire < 0) {
        $error = "Le prix unitaire doit être un nombre positif.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO services (designation, prix_unitaire) VALUES (:designation, :prix_unitaire)");
        $stmt->execute([
            'designation' => $designation,
            'prix_unitaire' => $prix_unitaire,
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
    <input type="text" name="designation" id="designation" required>
    <br>
    <label for="prix_unitaire">Prix Unitaire :</label>
    <input type="number" name="prix_unitaire" id="prix_unitaire" strep="0.01" required>
    <br>
    <input type="submit" value="Ajouter">
</form>
</body>
</html>

