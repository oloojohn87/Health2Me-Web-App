<?php
require_once('stripe/lib/Stripe.php');
 
$stripe = array(
  "secret_key"      => "sk_test_hJg0Ij3YDmTvpWMenFHf3MLn",
  "publishable_key" => "pk_test_YBtrxG7xwZU9RO1VY8SeaEe9"
);
 
Stripe::setApiKey($stripe['secret_key']);
?>