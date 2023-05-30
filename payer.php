<?php
require_once('vendor/autoload.php');
\Stripe\Stripe::setApiKey('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'); // Utilisez votre clé secrète Stripe ici

// Récupérer le montant total à facturer au client.
// Pour le moment, je vais simplement mettre un montant statique pour cet exemple.
$total = 50; // Ce sera en centimes. Donc 10000 centimes est équivalent à 100 euros.

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
    'success_url' => 'https://yassineverriez.com/success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => 'https://yassineverriez.com',
]);

// Vous pouvez ensuite rediriger le client vers la page de paiement.
header("Location: " . $checkout_session->url);
exit;
?>

