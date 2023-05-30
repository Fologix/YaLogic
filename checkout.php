<?php
require_once('vendor/autoload.php');
\Stripe\Stripe::setApiKey('sk_test_51N2dVtEAVA2mzTaKMmxxO22mdUBwOrTfLv4R7gZpjBm4b3XVlyItU6a6K2G6YDQRwHZQx87E9XMV7hYPtE1p2P9d00E5j1FgRT');
?>

<form action="/charge.php" method="post">
    <script
        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
        data-key="pk_test_51N2dVtEAVA2mzTaKn70tvrsWlG53gm4byt5ghL1DuSlu2EQEHJvtZN6of8cdyVDWbZ7dPUnkwM97cKgRG22Un7ff00jwriARY8"
        data-amount="2000"
        data-name="YaLogic"
        data-description="2 widgets"
        data-image="https://www.yalogic.com/images/logo.png"
        data-locale="auto"
        data-currency="eur">
    </script>
</form>

