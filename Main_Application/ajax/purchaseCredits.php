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

$doc_id = $_POST['doctor'];
$quantity = $_POST['quantity'] * 100;

$res = $con->prepare("SELECT * FROM doctors WHERE id = ?");
$res->bindValue(1, $doc_id, PDO::PARAM_INT);
$res->execute();

$doc_row = $res->fetch(PDO::FETCH_ASSOC);
if(isset($_POST['token']))
{
    $token = $_POST['token'];
    $charge = false;
    $amount = $quantity;
    try {
        $charge = Stripe_Charge::create(array(
            "amount" => $amount, // amount in cents, again
            "currency" => "usd",
            "card" => $token,
            "description" => "Charge for purchasing credits for doctor ".$doc_row['Name']." ".$doc_row['Surname']." on ".date('F j, Y'))
        );
    } catch(Stripe_CardError $e) {
        echo -1;
    }
    
    if($charge){
        $credits = $con->prepare("UPDATE doctors SET credits = ? WHERE id = ?");
        $credits->bindValue(1, ($doc_row['credits']+$quantity), PDO::PARAM_INT);
        $credits->bindValue(2, $doc_row['id'], PDO::PARAM_INT);
        $credits->execute();

        $transaction = $con->prepare("INSERT INTO payments SET owner_id = ?, amount = ?, owner_name=?, owner_type='doctor', service='buy credits', service_type='buy credits', currency='usd'");
        $transaction->bindValue(1, $doc_row['id'], PDO::PARAM_INT);
        $transaction->bindValue(2, ($amount), PDO::PARAM_INT);
        $transaction->bindValue(3, ($doc_row['Name'].' '.$doc_row['Surname']), PDO::PARAM_STR);
        $transaction->execute();
    }
    
    $res = 0;
    if($doc_row['credits'] != NULL)
        $res = $doc_row['credits'];
    $res += $quantity;

    echo $res;
}
else
{


    $amount = $quantity;
    $customer = '';

    
        if(isset($doc_row['stripe_id']) && $doc_row['stripe_id'] != null && strlen($doc_row['stripe_id']) > 0)
        {
            $customer = Stripe_Customer::retrieve($doc_row['stripe_id']);
            $cards = Stripe_Customer::retrieve((htmlspecialchars($doc_row['stripe_id'])))->cards->all(array('limit' => 10));

            // check if the user has a credit card in their account
            if(count($cards["data"]) > 0)
            {
                $customer = $doc_row['stripe_id'];
            }
            
            $charge = Stripe_Charge::create(array(
				"amount" =>$amount,
				"currency" => "usd",
				"customer" => $customer,
				"description" => "Charge for purchasing credits for doctor ".$doc_row['Name']." ".$doc_row['Surname']." on ".date('F j, Y')
			));
			
			if($charge){
				$credits = $con->prepare("UPDATE doctors SET credits = ? WHERE id = ?");
				$credits->bindValue(1, ($doc_row['credits']+$quantity), PDO::PARAM_INT);
				$credits->bindValue(2, $doc_row['id'], PDO::PARAM_INT);
				$credits->execute();
				
				$transaction = $con->prepare("INSERT INTO payments SET owner_id = ?, amount = ?, owner_name=?, owner_type='doctor', service_type='buy credits', service='purchase credits', currency='usd'");
				$transaction->bindValue(1, $doc_row['id'], PDO::PARAM_INT);
				$transaction->bindValue(2, $amount, PDO::PARAM_INT);
				$transaction->bindValue(3, ($doc_row['Name'].' '.$doc_row['Surname']), PDO::PARAM_STR);
				$transaction->execute();
			}

			echo $amount;
        }
    

    
}

?>
