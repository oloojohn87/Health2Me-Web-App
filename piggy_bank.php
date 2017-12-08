<?php
//Author: Kyle Austin
//Date: 12/2/14
//Purpose: To build a piggy back verification process for bank accounts.  
//Basically we charge two small amounts to their card and they verify those amounts.  This verifies that the card they used is indeed theirs...

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

$res = $con->prepare("SELECT * FROM usuarios WHERE Identif = ?");
$res->bindValue(1, $pat_id, PDO::PARAM_INT);
$res->execute();

$pat_row = $res->fetch(PDO::FETCH_ASSOC);

if($pat_row['GrantAccess'] == 'HTI-COL')
{
    echo 0;
}
else
{

    $amount1 = rand(50,100);;
    $amount2 = rand(50,100);
    $customer = '';

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
        
        $charge1 = Stripe_Charge::create(array(
			"amount" =>$amount1,
			"currency" => "usd",
			"customer" => $customer,
			"description" => "Health2me - Charge for verifying identity of ".$pat_row['Name'].' '.$pat_row['Surname'].' on '.date('F j, Y')
		));
    
		$charge2 = Stripe_Charge::create(array(
			"amount" =>$amount2,
			"currency" => "usd",
			"customer" => $customer,
			"description" => "Health2me - Charge for verifying identity of ".$pat_row['Name'].' '.$pat_row['Surname'].' on '.date('F j, Y')
		));

    echo $amount1;
    echo $amount2;
    

		$query = $con->prepare("UPDATE usuarios SET charge1 = ?, charge2 = ? WHERE Identif = ?");
		$query->bindValue(1, $amount1, PDO::PARAM_INT);
		$query->bindValue(2, $amount2, PDO::PARAM_INT);
        $query->bindValue(3, $pat_id, PDO::PARAM_INT);
        $query->execute();
	
        
    }

    


?>
