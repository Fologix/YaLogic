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

    // Création de la facture pour le client
    $stmt = $pdo->prepare("INSERT INTO factures (id_client, date_facture) VALUES (:id_client, NOW())");
    $stmt->execute(['id_client' => $id_client]);
    $id_facture = $pdo->lastInsertId(); // Récupère l'ID de la facture créée

    // Prépare la requête une seule fois pour ajouter les services et l'ID de la facture
    $stmt = $pdo->prepare("INSERT INTO services_clients (id_client, id_service, id_facture) VALUES (:id_client, :id_service, :id_facture)");

    // Prépare la requête pour ajouter des entrées à la table factures_services
    $stmt2 = $pdo->prepare("INSERT INTO factures_services (id_facture, id_service, quantite) VALUES (:id_facture, :id_service, :quantite)");

    // Parcourt tous les services cochés et les ajoute un par un avec l'ID de la facture
    foreach($id_services as $id_service) {
        $stmt->execute(['id_client' => $id_client, 'id_service' => $id_service, 'id_facture' => $id_facture]);

        // Ajoute une entrée dans la table factures_services
        // Remplacez 1 par la quantité appropriée pour chaque service
        $stmt2->execute(['id_facture' => $id_facture, 'id_service' => $id_service, 'quantite' => 1]);
    }

    $message = "Les services ont été ajoutés au client et une facture a été créée.";
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
    <select name="id_client" id="id_client">
        <?php foreach ($clients as $client) { ?>
            <option value="<?php echo $client['id_client']; ?>"><?php echo $client['nom_client']; ?></option>
        <?php } ?>
    </select>
    <br>
    <label>Services :</label>
    <br>
    <?php foreach ($services as $service) { ?>
        <input type="checkbox" name="id_service[]" value="<?php echo $service['id_service']; ?>">
        <label for="id_service[]"><?php echo $service['designation']; ?></label>
        <br>
    <?php } ?>
    <br>
    <button type="submit" name="submit">Créer le service pour le client</button>
</form>
</body>
</html>
