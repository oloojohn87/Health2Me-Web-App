<?php

require "Services/Twilio.php";
require("environment_detail.php");
require("push_server.php");
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
require_once('push/push.php');
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

$API_VERSION = '2010-04-01';
$AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
$AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
// Instantiate a new Twilio Rest Client
$client = new Services_Twilio($AccountSid, $AuthToken);

$doc_id = $_POST['doc'];
$pat_id = $_POST['pat'];

$doc = $con->prepare("SELECT Name,Surname,phone FROM doctors WHERE id = ?");
$doc->bindValue(1, $doc_id, PDO::PARAM_INT);
$doc->execute();
$doc_row = $doc->fetch(PDO::FETCH_ASSOC);

$pat = $con->prepare("SELECT Name,Surname,telefono FROM usuarios WHERE Identif = ?");
$pat->bindValue(1, $pat_id, PDO::PARAM_INT);
$pat->execute();
$pat_row = $pat->fetch(PDO::FETCH_ASSOC);

$doc_name = $doc_row['Name'].' '.$doc_row['Surname'];
$pat_name = $pat_row['Name'].' '.$pat_row['Surname'];
$doc_phone = $doc_row['phone'];
$pat_phone = $pat_row['telefono'];
$conference_id = $doc_id.'_'.$pat_id;

$consult = $prepare("SELECT consultationid FROM consults WHERE Doctor = ? AND Patient = ? ORDER BY DateTime DESC LIMIT 1");
$consult->bindValue(1, $doc_id, PDO::PARAM_INT);
$consult->bindValue(2, $pat_id, PDO::PARAM_INT);
$consult->execute();
$consult_row = $consult->fetch(PDO::FETCH_ASSOC);

$recent_consult = $consult_row['consultationid'];

$doc_url = 'http://'.$_SERVER['HTTP_HOST'].'/phone_appointment_ask.php?pat_name='.$pat_name.'&conference_id='.$conference_id;
$pat_url = 'http://'.$_SERVER['HTTP_HOST'].'/phone_appointment_wait.php?info='.$doc_id.'-'.$doc_name.'-'.$conference_id.'-'.$pat_id.'-'.$recent_consult;
str_replace("-", "", $doc_phone);
str_replace("-", "", $pat_phone);

//THIS CATCHED TWILIO ERRORS AND ADDS THEM TO TWILIO_ERRORS TABLE

/*
try
{
    $client->account->calls->create('+19034018888', $doc_phone, $doc_url);
} 
catch (Exception $e)
{
    $error = $e->getMessage();
    $results2 = $con->prepare("INSERT INTO twilio_errors SET consult_id = ?, doc_id = ?, error = ?, patient_number = ?, doc_number = ?, type = ?");
    $results2->bindValue(1, $recent_id, PDO::PARAM_INT);
    $results2->bindValue(2, $doc_id, PDO::PARAM_INT);
    $results2->bindValue(3, $error, PDO::PARAM_STR);
    $results2->bindValue(4, $pat_phone, PDO::PARAM_STR);
    $results2->bindValue(5, $doc_phone, PDO::PARAM_STR);
    $results2->bindValue(6, 'phone', PDO::PARAM_STR);
    $results2->execute();
}
*/
try
{
    $call = $client->account->calls->create('+19034018888', $pat_phone, $pat_url);
} 
catch (Exception $e)
{
    $error = $e->getMessage();
    $results2 = $con->prepare("INSERT INTO twilio_errors SET consult_id = ?, patient_id = ?, error = ?, patient_number = ?, doc_number = ?, type = ?");
    $results2->bindValue(1, $recent_id, PDO::PARAM_INT);
    $results2->bindValue(2, $pat_id, PDO::PARAM_INT);
    $results2->bindValue(3, $error, PDO::PARAM_STR);
    $results2->bindValue(4, $pat_phone, PDO::PARAM_STR);
    $results2->bindValue(5, $doc_phone, PDO::PARAM_STR);
    $results2->bindValue(6, 'phone', PDO::PARAM_STR);
    $results2->execute();
}
$sid = $call->sid;

//$app_key = 'd869a07d8f17a76448ed';
//$app_secret = '92f67fb5b104260bbc02';
//$app_id = '51379';
//$pusher = new Pusher($app_key, $app_secret, $app_id);
$push = new Push();
$push->send($pat_id, 'call_update_'.$pat_id, $sid);

echo $sid;

?>