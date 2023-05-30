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

if (isset($_POST['designation'], $_POST['prix_unitaire'], $_POST['type_service'])) {
    $designation = $_POST['designation'];
    $prix_unitaire = $_POST['prix_unitaire'];
    $type_service = $_POST['type_service'];

    if (empty($designation) || empty($prix_unitaire)) {
        $error = "Veuillez remplir tous les champs.";
    } elseif (!is_numeric($prix_unitaire) || $prix_unitaire < 0) {
        $error = "Le prix unitaire doit être un nombre positif.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO services (designation, prix_unitaire, type_service) VALUES (:designation, :prix_unitaire, :type_service)");
        $stmt->execute([
            'designation' => $designation,
            'prix_unitaire' => $prix_unitaire,
            'type_service' => $type_service,
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
    <input type="submit" value="Ajouter">
</form>

</body>
</html>

