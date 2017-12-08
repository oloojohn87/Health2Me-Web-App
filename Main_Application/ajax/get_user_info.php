<?php

require_once('../modules/stripe/stripe/lib/Stripe.php');
require("environment_detail.php");
$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];

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

$id = $_POST['id'];

$res = $con->prepare("SELECT * FROM usuarios WHERE Identif=?");
$res->bindValue(1, $id, PDO::PARAM_INT);
$res->execute();

$row = $res->fetch(PDO::FETCH_ASSOC);
$time_info = explode(":", $row['timezone']);
$timezone = strval(intval($time_info[0])).'.'.strval(floatval($time_info[1]) / 60.0);
$location = explode(", ", (htmlspecialchars($row['location'])));
$return_array = array("identif" => $row['Identif'], "timezone" => (htmlspecialchars($timezone)), "email" => (htmlspecialchars($row['email'])), "phone" => (htmlspecialchars($row['telefono'])));
if(count($location) == 1)
{
    $return_array["location"] = str_replace(":", ",", $location[0]);
}
else if(count($location) >= 2)
{
    $return_array["location2"] = str_replace(":", ",", $location[0]);
    $return_array["location"] = str_replace(":", ",", $location[1]);
}

if(isset($row['stripe_id']) && $row['stripe_id'] != null)
{
    $cards = Stripe_Customer::retrieve((htmlspecialchars($row['stripe_id'])))->cards->all(array('limit' => 10));
    if(count($cards["data"]) > 0)
    {
        $return_array["cards"] = array();
        foreach($cards["data"] as $card)
        {
            $brand = str_replace(" ", "_", $card->brand);
            $brand = strtolower($brand);
            $img = "images/credit_card_icons/generic_1.png";
            if(file_exists("images/credit_card_icons/".$brand.".png"))
            {
                $img = "images/credit_card_icons/".$brand.".png";
            }

            array_push($return_array["cards"], array("number" => $card->last4, "icon" => $img, "id" => $card->id, "stripe_id"=>$row['stripe_id']));

        }
    }
}

echo json_encode($return_array);

?>
