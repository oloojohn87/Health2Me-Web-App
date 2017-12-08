<?php

require "Services/Twilio.php";
require("environment_detail.php");
require("push_server.php");
require_once('stripe/stripe/lib/Stripe.php');
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
require_once('push/push.php');
$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];

$stripe = array(
  "secret_key"      => "sk_test_hJg0Ij3YDmTvpWMenFHf3MLn",
  "publishable_key" => "pk_test_YBtrxG7xwZU9RO1VY8SeaEe9"
);
Stripe::setApiKey($stripe['secret_key']);

if(isset($_POST['pat_phone']))
{
    $pat_phone = "+".$_POST["pat_phone"];
    $doc_phone = "+".$_POST["doc_phone"];
    
    $pat_id = $_POST['pat_id'];
    $doc_id = $_POST['doc_id'];
    
    $pat_name = $_POST['pat_name'];
    $doc_name = $_POST['doc_name'];
}
else
{

    $pat_id = $argv[1];
    $doc_id = $argv[2];
    
    $name = explode("_", $argv[3]);
    $pat_name = $name[0]." ".$name[1];
    $name = explode("_", $argv[4]);
    $doc_name = $name[0]." ".$name[1];
    
    $pat_phone = "+".$argv[5];
    $doc_phone = "+".$argv[6];
    
    $dbhost = "localhost";
}
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$API_VERSION = '2010-04-01';
$AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
$AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
// Instantiate a new Twilio Rest Client
$client = new Services_Twilio($AccountSid, $AuthToken);

$conference_id = $doc_id."_".$pat_id;

$result = $con->prepare("SELECT id,in_consultation,stripe_id FROM doctors WHERE id=?");
$result->bindValue(1, $doc_id, PDO::PARAM_INT);
$result->execute();

$row = $result->fetch(PDO::FETCH_ASSOC);

$result = $con->prepare("SELECT stripe_id,plan,ownerAcc,subsType FROM usuarios WHERE Identif=?");
$result->bindValue(1, $pat_id, PDO::PARAM_INT);
$result->execute();

$pat_row = $result->fetch(PDO::FETCH_ASSOC);

// check if the doctor is in a video consultation
if($row['in_consultation'] != 1)
{
    $API_VERSION = '2010-04-01';
    $AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
    $AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
    // Instantiate a new Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
    
    $docs_in_consultation = array();
    foreach ($client->account->conferences->getIterator(0, 50, array("Status" => "in-progress")) as $conference)
    {
        $conference_name = explode("_", $conference->friendly_name);
        $doc_id = intval($conference_name[0]);
        if(!in_array($doc_id, $docs_in_consultation))
        {
            array_push($docs_in_consultation, $doc_id);
        }
    }
    
    // check if the doctor is in a phone consultaiton
    if(!in_array(intval($row['id']), $docs_in_consultation))
    {
        
        // check if the user has a credit card in their account
        $has_credit_card = false;
        if(isset($pat_row['stripe_id']) && $pat_row['stripe_id'] != null && strlen($pat_row['stripe_id']) > 0)
        {
            $customer = Stripe_Customer::retrieve($pat_row['stripe_id']);
            $cards = Stripe_Customer::retrieve((htmlspecialchars($pat_row['stripe_id'])))->cards->all(array('limit' => 10));
            
            // check if the user has a credit card in their account
            if(count($cards["data"]) > 0)
            {
                $has_credit_card = true;
            }
        }
        
        if(!$has_credit_card && $pat_row['plan'] == 'FAMILY' && $pat_row['subsType'] != 'Owner')
        {
            $result = $con->prepare("SELECT stripe_id FROM usuarios WHERE Identif IN (SELECT ownerAcc FROM usuarios WHERE Identif = ?)");
            $result->bindValue(1, $pat_id, PDO::PARAM_INT);
            $result->execute();

            $owner_row = $result->fetch(PDO::FETCH_ASSOC);
            
            if(isset($owner_row['stripe_id']) && $owner_row['stripe_id'] != null && strlen($owner_row['stripe_id']) > 0)
            {
                $customer = Stripe_Customer::retrieve($owner_row['stripe_id']);
                $cards = Stripe_Customer::retrieve((htmlspecialchars($owner_row['stripe_id'])))->cards->all(array('limit' => 10));

                // check if the user has a credit card in their account
                if(count($cards["data"]) > 0)
                {
                    $has_credit_card = true;
                }
            }
        }
        
        
    
        if($has_credit_card)
        {
            $contract = "null";
            $doc_names = explode(" ", $doc_name);
            $pat_names = explode(" ", $pat_name);
            $doc_firstname = $doc_names[0];
            $pat_firstname = $pat_names[0];
            $doc_lastname = $doc_names[1];
            for($i = 2; $i < count($doc_names); $i++)
            {
                $doc_lastname .= " ".$doc_names[$i];
            }
            $pat_lastname = $pat_names[1];
            for($i = 2; $i < count($pat_names); $i++)
            {
                $pat_lastname .= " ".$pat_names[$i];
            }
            $results = $con->prepare("INSERT INTO consults(contract,DateTime,status,type,Patient,Doctor,doctorName,doctorSurname,patientName,patientSurname,lastActive) values(null,NOW(),'In Progress','video',?,?,?,?,?,?,NOW())");
            $results->bindValue(1, $pat_id, PDO::PARAM_INT);
            $results->bindValue(2, $doc_id, PDO::PARAM_INT);
            $results->bindValue(3, $doc_firstname, PDO::PARAM_STR);
            $results->bindValue(4, $doc_lastname, PDO::PARAM_STR);
            $results->bindValue(5, $pat_firstname, PDO::PARAM_STR);
            $results->bindValue(6, $pat_lastname, PDO::PARAM_STR);
            $results->execute();



            $date = date_create();
            $timestamp = date_timestamp_get($date);
            $result = $con->prepare("UPDATE doctors SET consultation_pat=?, cons_req_time=?, telemed_type=2 WHERE id=?");
            $result->bindValue(1, $pat_id, PDO::PARAM_INT);
            $result->bindValue(2, $timestamp, PDO::PARAM_STR);
            $result->bindValue(3, $doc_id, PDO::PARAM_INT);
            $result->execute();

            $results = $con->prepare("SELECT id FROM doctorslinkusers WHERE IdMED = ? AND IdUs = ? AND IdPIN is null");
            $results->bindValue(1, $doc_id, PDO::PARAM_INT);
            $results->bindValue(2, $pat_id, PDO::PARAM_INT);
            $results->execute();
            $rowCount = $results->rowCount();
            if($rowCount == 0)
            {
                $results = $con->prepare("insert into doctorslinkusers (IdMED,IdUs,Fecha,IdPIN,estado) values(?,?,NOW(),null,2)");
                $results->bindValue(1, $doc_id, PDO::PARAM_INT);
                $results->bindValue(2, $pat_id, PDO::PARAM_INT);
                $results->execute();
            }

            str_replace("-", "", $doc_phone);

            $client->account->sms_messages->create('+19034018888', $doc_phone, $pat_name." would like to start a video consultation with you. Would you like to accept? (yes or no)");
            //$app_key = 'd869a07d8f17a76448ed';
            //$app_secret = '92f67fb5b104260bbc02';
            //$app_id = '51379';
            //$pusher = new Pusher($app_key, $app_secret, $app_id);
            $push = new Push();
            $arr = array("id" => $pat_id, "name" =>$pat_name);
            $push->send($doc_id, 'telemed_video_call', json_encode($arr));
            echo '1';
        }
        else
        {
            echo 'NC';
        }
    }
    else
    {
        echo 'IC';
    }

}
else
{
    echo 'IC';
}

?>


