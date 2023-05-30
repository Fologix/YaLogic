<?php
session_start();
include_once 'db_connexion.php';
$pdo = connexion_bdd();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "Vous n'êtes pas connecté";
    exit;
}

// Ajouter un service
if(isset($_POST['add_service'])) {
    $designation = $_POST['designation'];
    $prix_unitaire = $_POST['prix_unitaire'];

    $sql = "INSERT INTO services (designation, prix_unitaire) VALUES (?, ?)";
    $stmt= $pdo->prepare($sql);
    $stmt->execute([$designation, $prix_unitaire]);
}

// Modifier un service
if(isset($_POST['edit_service'])) {
    $id_service = $_POST['id_service'];
    $designation = $_POST['designation'];
    $prix_unitaire = $_POST['prix_unitaire'];

    $sql = "UPDATE services SET designation = ?, prix_unitaire = ? WHERE id_service = ?";
    $stmt= $pdo->prepare($sql);
    $stmt->execute([$designation, $prix_unitaire, $id_service]);
}

// Supprimer un service
if(isset($_POST['delete_service'])) {
    $id_service = $_POST['id_service'];

    $sql = "DELETE FROM services WHERE id_service = ?";
    $stmt= $pdo->prepare($sql);
    $stmt->execute([$id_service]);
}

// Récupérer tous les services
$sql = "SELECT id_service, designation, prix_unitaire FROM services";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$services = $stmt->fetchAll();

?>

<a href="edit_facture.php">Facture</a>
<a href="service.php"> Services</a>

<!-- Formulaire d'ajout de service -->
<form method="POST">
    <input type="text" name="designation" placeholder="Désignation du service">
    <input type="number" name="prix_unitaire" placeholder="Prix unitaire">
    <input type="submit" name="add_service" value="Ajouter un service">
</form>

<!-- Formulaire de modification de service -->
<form method="POST">
    <select name="id_service">
        <?php foreach($services as $service): ?>
            <option value="<?= $service['id_service'] ?>"><?= $service['designation'] ?></option>
        <?php endforeach; ?>
    </select>
    <input type="text" name="designation" placeholder="Nouvelle désignation">
    <input type="number" name="prix_unitaire" placeholder="Nouveau prix unitaire">
    <input type="submit" name="edit_service" value="Modifier le service">
</form>

<!-- Formulaire de suppression de service -->
<form method="POST">
    <select name="id_service">
        <?php foreach($services as $service): ?>
            <option value="<?= $service['id_service'] ?>"><?= $service['designation'] ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" name="delete_service" value="Supprimer le service">
</form>

