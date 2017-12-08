<?php

require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];


require_once('stripe/stripe/lib/Stripe.php');
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

$stripe = array(
  "secret_key"      => "sk_test_hJg0Ij3YDmTvpWMenFHf3MLn",
  "publishable_key" => "pk_test_YBtrxG7xwZU9RO1VY8SeaEe9"
);
Stripe::setApiKey($stripe['secret_key']);

$id = $_POST['user'];
$plan = $_POST['plan'];

$result = $con->prepare("SELECT * FROM usuarios WHERE Identif=?");
$result->bindValue(1, $id, PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);

if(isset($row['stripe_id']) && $row['stripe_id'] != null && strlen($row['stripe_id']) > 0)
{
    $customer = Stripe_Customer::retrieve($row['stripe_id']);
    $cards = Stripe_Customer::retrieve((htmlspecialchars($row['stripe_id'])))->cards->all(array('limit' => 10));
    if(count($cards["data"]) > 0)
    {
        $subs = Stripe_Customer::retrieve((htmlspecialchars($row['stripe_id'])))->subscriptions->all(array('limit'=>1));
        if(count($subs["data"]) > 0)
        {
            // upgrade subscription
            $subscription = $customer->subscriptions->retrieve($subs["data"][0]->id);
            if($plan == 1)
            {
                $subscription->plan = "g45lm34";
            }
            else if($plan == 2)
            {
                $subscription->plan = "75h23m4";
            }
            $subscription->save(); 
        }
        else
        {
            // create new subscription
            if($plan == 1)
            {
                $customer->subscriptions->create(array("plan" => "g45lm34"));
            }
            else if($plan == 2)
            {
                $customer->subscriptions->create(array("plan" => "75h23m4"));
            }
            
        }
        // update database
        if($plan == 1)
        {
            $result = $con->prepare("UPDATE usuarios SET plan = 'PREMIUM' WHERE Identif = ?");
            $result->bindValue(1, $id, PDO::PARAM_INT);
            $result->execute();
        }
        else if($plan == 2)
        {
            $result = $con->prepare("UPDATE usuarios SET plan = 'FAMILY', subsType = 'Owner', OwnerAcc = ? WHERE Identif = ?");
            $result->bindValue(1, $id, PDO::PARAM_INT);
            $result->bindValue(2, $id, PDO::PARAM_INT);
            $result->execute();
        }
        echo 'GOOD';
    }
    else
    {
        echo 'NCC';
    }
}
else
{
    echo 'NCC';
}



?>