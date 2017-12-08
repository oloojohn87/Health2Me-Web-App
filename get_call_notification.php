<?php

require_once("Services/Twilio.php"); // Loads the library
$API_VERSION = '2010-04-01';
$AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
$AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";

$client = new Services_Twilio($AccountSid, $AuthToken);

$notification = $client->account->calls->get($_POST['sid'])->notifications;
$notification = str_replace('["', '', $notification);
$notification = str_replace('"]', '', $notification);
$notification = str_replace('\"', '"', $notification);
$not = json_decode($notification);

$error_type = 2;
if(isset($not->log))
{
    $error_type = $not->log;
}


$code = "";
if(isset($not->error_code))
{
    $code = $not->error_code;
}

$message_text = "";
if(isset($not->message_text))
{
    $message_text = $not->message_text;
}

echo json_encode(array("error_type" => $error_type, "error_code" => $code, "error_message" => urldecode($message_text)));

?>