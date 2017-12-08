<?php

require("environment_detail.php");
require_once('stripe/stripe/lib/Stripe.php');
$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];



$stripe = array(
  "secret_key"      => "sk_test_hJg0Ij3YDmTvpWMenFHf3MLn",
  "publishable_key" => "pk_test_YBtrxG7xwZU9RO1VY8SeaEe9"
);
Stripe::setApiKey($stripe['secret_key']);

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

$pat_id = $_POST['user'];
$doc_id = $_POST['doctor'];
$amount = 0;

$res = $con->prepare("SELECT * FROM usuarios WHERE Identif = ?");
$res->bindValue(1, $pat_id, PDO::PARAM_INT);
$res->execute();

$pat_row = $res->fetch(PDO::FETCH_ASSOC);

$res = $con->prepare("SELECT * FROM doctors WHERE id = ?");
$res->bindValue(1, $doc_id, PDO::PARAM_INT);
$res->execute();

$doc_row = $res->fetch(PDO::FETCH_ASSOC);
$amount = $doc_row['consult_price'];

if(substr($pat_row['GrantAccess'], 0, 3) == 'HTI')
{
    echo 0;
}
else
{

    
    $customer = '';

    if($pat_row['plan'] == null || $pat_row['plan'] == 'PREMIUM' || $pat_row['plan'] == 'FREE')
    {
        $customer = $pat_row['stripe_id'];
    }
    else
    {
        if(isset($pat_row['stripe_id']) && $pat_row['stripe_id'] != null && strlen($pat_row['stripe_id']) > 0)
        {
            $customer = Stripe_Customer::retrieve($pat_row['stripe_id']);
            $cards = Stripe_Customer::retrieve((htmlspecialchars($pat_row['stripe_id'])))->cards->all(array('limit' => 10));

            // check if the user has a credit card in their account
            if(count($cards["data"]) > 0)
            {
                $customer = $pat_row['stripe_id'];
            }
        }

        if(strlen($customer) == 0)
        {
            // if the current customer in family plan does not have a credit card, charge to family account's owner instead
            $res = $con->prepare("SELECT stripe_id FROM usuarios WHERE Identif IN (SELECT ownerAcc FROM usuarios WHERE Identif = ?)");
            $res->bindValue(1, $pat_id, PDO::PARAM_INT);
            $res->execute();

            $row = $res->fetch(PDO::FETCH_ASSOC);

            $customer = $row['stripe_id'];

        }
    }
	$amount = $amount + 1200;
    $charge_status = Stripe_Charge::create(array(
        "amount" =>$amount,
        "currency" => "usd",
        "customer" => $customer,
        "description" => "Charge for consultation between member ".$pat_row['Name'].' '.$pat_row['Surname'].' and doctor '.$doc_row['Name'].' '.$doc_row['Surname'].' on '.date('F j, Y')
    ));
    
    if($charge_status){
		$months = 0;
		$description = "Charge for consultation between member ".$pat_row['Name'].' '.$pat_row['Surname'].' and doctor '.$doc_row['Name'].' '.$doc_row['Surname'].' on '.date('F j, Y');
		
		$transaction = $con->prepare("INSERT INTO payments SET owner_id = ?, amount = ?, owner_name=?, owner_type='member', service=?, currency='usd', service_type='consultation', service_id = ?, months = ?, base_price = ?, payout_id = ?");
		$transaction->bindValue(1, $pat_row['Identif'], PDO::PARAM_INT);
		$transaction->bindValue(2, $amount, PDO::PARAM_INT);
		$transaction->bindValue(3, ($pat_row['Name'].' '.$pat_row['Surname']), PDO::PARAM_STR);
		$transaction->bindValue(4, $description, PDO::PARAM_STR);
		$transaction->bindValue(5, $pat_row['Identif'], PDO::PARAM_INT);
		$transaction->bindValue(6, $months, PDO::PARAM_INT);
		$transaction->bindValue(7, $doc_row['consult_price'], PDO::PARAM_INT);
		$transaction->bindValue(8, $doc_row['id'], PDO::PARAM_INT);
		$transaction->execute();
		
		$complete_consult = $con->prepare("UPDATE consults SET Status = 'Complete' WHERE Doctor = ? && Type='video'");
		$complete_consult->bindValue(1, $doc_id, PDO::PARAM_INT);
		$complete_consult->execute();
	}

    echo $amount;
}

?>
