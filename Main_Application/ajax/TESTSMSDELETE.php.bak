<?php

// SEND SMS TEXT TO REFERRAL
require "Services/Twilio.php";
  
$CellPhone = '14692680295';  
$NamePatient = ' TEST SMS ';
     
$AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
$AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
// Instantiate a new Twilio Rest Client
$client = new Services_Twilio($AccountSid, $AuthToken);
/* Your Twilio Number or Outgoing Caller ID */
$from = '+19034018888'; 
$to= '+'.$CellPhone; 
$body = 'H2Me.- Patient '.$NamePatient.' has been referred to you. Access your account here https://www.health2.me ';
$client->account->sms_messages->create($from, $to, $body);
//echo "Sent message to $name";
// SEND SMS TEXT TO REFERRAL


?>