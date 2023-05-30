<?php
require_once('vendor/autoload.php');
\Stripe\Stripe::setApiKey('sk_test_51N2dVtEAVA2mzTaKMmxxO22mdUBwOrTfLv4R7gZpjBm4b3XVlyItU6a6K2G6YDQRwHZQx87E9XMV7hYPtE1p2P9d00E5j1FgRT');

$token = $_POST['stripeToken'];
$charge = \Stripe\Charge::create([
    'amount' => 2000,
    'currency' => 'eur',
    'description' => 'Exemple de charge',
    'source' => $token,
]);

// Traiter la réponse de la charge
if ($charge->paid == true) {
    echo 'Merci pour votre achat !';
} else {
    echo 'Une erreur est survenue lors du paiement.';
}
?>
