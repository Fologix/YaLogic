<?php
session_start();
include_once 'db_connexion.php';

// Vérifie si l'utilisateur est connecté
if(!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

// Récupère les détails de l'administrateur
$pdo = connexion_bdd();
$stmt = $pdo->prepare("SELECT * FROM admin WHERE id_admin = :id_admin");
$stmt->execute(['id_admin' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: deconnexion.php");
    exit;
}

// Récupère la liste des clients et des services
$stmt = $pdo->prepare("SELECT * FROM clients");
$stmt->execute();
$clients = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT * FROM services");
$stmt->execute();
$services = $stmt->fetchAll();

// Gère la soumission du formulaire
if (isset($_POST['submit'])) {
    $id_client = $_POST['id_client'];
    $id_services = $_POST['id_service'];

    // Prépare la requête une seule fois
    $stmt = $pdo->prepare("INSERT INTO services_clients (id_client, id_service) VALUES (:id_client, :id_service)");

    // Parcourt tous les services coches et les ajoute un par un
    foreach($id_services as $id_service) {
        $stmt->execute(['id_client' => $id_client, 'id_service' => $id_service]);
    }

    $message = "Les services ont été ajoutés au client.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Création de Service pour un Client</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<a href="deconnexion.php">Se déconnecter</a>
<h1>Bienvenue, <?php echo $user['nom_admin']; ?>!</h1>
<h2>Création de Service pour un Client</h2>
<?php if (isset($message)) { echo "<p>$message</p>"; } ?>
<form method="post">
    <label for="id_client">Client :</label>
    <select name="id_client" id="id_client" required>
        <?php foreach ($clients as $client) { ?>
            <option value="<?php echo $client['id_client']; ?>"><?php echo $client['nom_client']; ?></option>
        <?php } ?>
    </select>
    <br>
    <label>Services :</label>
    <?php foreach ($services as $service) { ?>
        <input type="checkbox" name="id_service[]" value="<?php echo $service['id_service']; ?>"><?php echo $service['designation']; ?><br>
    <?php } ?>
    <br>
    <input type="submit" name="submit" value="Ajouter">
</form>
<p><a href="panel_admin.php">Retour au Panneau d'administration</a></p>
</body>
</html>

