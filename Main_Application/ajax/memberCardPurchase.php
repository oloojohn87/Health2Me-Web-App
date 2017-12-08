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

$user_id = $_POST['user_id'];
$doc_id = $_POST['doc_id'];
$quantity = $_POST['quantity'];
$months = $_POST['months'];

$res = $con->prepare("SELECT * FROM usuarios WHERE Identif = ?");
$res->bindValue(1, $user_id, PDO::PARAM_INT);
$res->execute();

$user_row = $res->fetch(PDO::FETCH_ASSOC);

$query = $con->prepare("SELECT * FROM doctors WHERE id = ?");
$query->bindValue(1, $doc_id, PDO::PARAM_INT);
$query->execute();

$doc_row = $query->fetch(PDO::FETCH_ASSOC);

    $amount = $quantity;
    $customer = '';

    
        if((isset($user_row['stripe_id']) && $user_row['stripe_id'] != null && strlen($user_row['stripe_id']) > 0) || isset($_POST['token']))
        {
			if(isset($_POST['token'])){
				$token = $_POST['token'];
			}else{
				$token = $user_row['stripe_id'];
				
				$customer = Stripe_Customer::retrieve($token);
				$cards = Stripe_Customer::retrieve((htmlspecialchars($token)))->cards->all(array('limit' => 10));

				// check if the user has a credit card in their account
				if(count($cards["data"]) > 0)
				{
					$customer = $user_row['stripe_id'];
				}
			}
			
            
            
            $description = "Charge for probe initiated by member on ".date('F j, Y');
            
            if(isset($_POST['doc_id'])){
				$res2 = $con->prepare("SELECT * FROM doctors WHERE id = ?");
				$res2->bindValue(1, $doc_id, PDO::PARAM_INT);
				$res2->execute();

				$doc_row = $res2->fetch(PDO::FETCH_ASSOC);
				
				$description = "Charge for probe initiated by doctor ".$doc_row['Name']." ".$doc_row['Surname']." on ".date('F j, Y');
			}
			
			if(isset($_POST['token'])){
				$token = $_POST['token'];
				$charge = Stripe_Charge::create(array(
				"amount" =>$amount,
				"currency" => "usd",
				"card" => $token,
				"description" => $description
			));
			}else{
				$charge = Stripe_Charge::create(array(
				"amount" =>$amount,
				"currency" => "usd",
				"customer" => $customer,
				"description" => $description
			));
			}
			
			if($charge){
				$transaction = $con->prepare("INSERT INTO payments SET owner_id = ?, amount = ?, owner_name=?, owner_type='member', service=?, currency='usd', service_type='probe', service_id = ?, months = ?, base_price = ?, payout_id = ?");
				$transaction->bindValue(1, $user_row['Identif'], PDO::PARAM_INT);
				$transaction->bindValue(2, $amount, PDO::PARAM_INT);
				$transaction->bindValue(3, ($user_row['Name'].' '.$user_row['Surname']), PDO::PARAM_STR);
				$transaction->bindValue(4, $description, PDO::PARAM_STR);
				$transaction->bindValue(5, $user_row['Identif'], PDO::PARAM_INT);
				$transaction->bindValue(6, $months, PDO::PARAM_INT);
				$transaction->bindValue(7, $doc_row['tracking_price'], PDO::PARAM_INT);
				$transaction->bindValue(8, $doc_row['id'], PDO::PARAM_INT);
				$transaction->execute();
			}

			echo $amount;
        }else{
			echo 'FAIL';
		}
    

    


?>
