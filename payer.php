<?php
require_once('vendor/autoload.php');

\Stripe\Stripe::setApiKey('sk_test_51N2dVtEAVA2mzTaKMmxxO22mdUBwOrTfLv4R7gZpjBm4b3XVlyItU6a6K2G6YDQRwHZQx87E9XMV7hYPtE1p2P9d00E5j1FgRT');

$serviceId = $_GET['service_id'];

include_once 'db_connexion.php';

$pdo = connexion_bdd();

$stmt = $pdo->prepare("SELECT prix_unitaire FROM services WHERE id_service = :id_service");
$stmt->execute(['id_service' => $serviceId]);
$service = $stmt->fetch();
$total = $service['prix_unitaire'] * 100;

$checkout_session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'currency' => 'eur',
            'product_data' => [
                'name' => 'Facturation des services',
            ],
            'unit_amount' => $total,
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => 'https://yassineverriez.com/success.php?session_id={CHECKOUT_SESSION_ID}&service_id=' . $serviceId,
    'cancel_url' => 'https://yassineverriez.com',
]);

header("Location: " . $checkout_session->url);
exit;
?>
