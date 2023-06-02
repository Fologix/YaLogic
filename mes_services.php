<?php
session_start();
include_once 'db_connexion.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

$pdo = connexion_bdd();

// Récupère les détails du client
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id_client = :id_client");
$stmt->execute(['id_client' => $_SESSION['user_id']]);
$client = $stmt->fetch();

// Récupère les services affectés au client
$stmt = $pdo->prepare("SELECT services.designation, services.prix_unitaire, services.type_service, services_clients.id AS service_client_id, services_clients.id_service, services.id_produit_stripe FROM services_clients JOIN services ON services_clients.id_service = services.id_service WHERE services_clients.id_client = :id_client");
$stmt->execute(['id_client' => $_SESSION['user_id']]);
$services = $stmt->fetchAll();

// Vérifie si des services sont disponibles pour le paiement
if (empty($services)) {
    exit('Aucun service disponible pour le paiement.');
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement des Services</title>
</head>
<body>
<a href="deconnexion.php">Déconnexion</a>
<h1>Paiement des Services</h1>

<table>
    <thead>
    <tr>
        <th>Désignation</th>
        <th>Prix Unitaire</th>
        <th>Type de Service</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($services as $service) : ?>
        <tr>
            <td><?php echo $service['designation']; ?></td>
            <td><?php echo $service['prix_unitaire']; ?> €</td>
            <td><?php echo ucfirst($service['type_service']); ?></td>
            <td>
                <button onclick="startStripeCheckout('<?php echo $service['id_produit_stripe']; ?>')">Payer</button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<!-- Ajout du script Stripe -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    function startStripeCheckout(priceId) {
        // Créez un objet de paiement avec le prix ID
        var stripe = Stripe('pk_test_51N2dVtEAVA2mzTaKn70tvrsWlG53gm4byt5ghL1DuSlu2EQEHJvtZN6of8cdyVDWbZ7dPUnkwM97cKgRG22Un7ff00jwriARY8');

        stripe.redirectToCheckout({
            lineItems: [{ price: priceId, quantity: 1 }],
            mode: 'payment',
            successUrl: 'https://yassineverriez.com',
            cancelUrl: 'https://yassineverriez.com'
        }).then(function(result) {
            // Gérez les erreurs
            if (result.error) {
                console.error(result.error.message);
            }
        });
    }
</script>

</body>
</html>
