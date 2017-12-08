<?php
 require("environment_detail.php");
$Tipo = $_GET['Tipo'];
$IdPac = $_GET['IdPac'];

$a = SendCallVERIF (34608754342);

//if ($Tipo==1) {$a = SendCallVERIFMED (34608754342);} else{$a = SendCallVERIF (34608754342);}



function SendCallVERIF($numero)
{

require "Services/Twilio.php";

$queNum = $numero; 
/* Set our AccountSid and AuthToken */
    $AccountSid = "ACc8d7e18334aea996eee97355a50fe672";
    $AuthToken = "484f8b70a66fe46fdb49f44900f891ec";
 
/* Your Twilio Number or an Outgoing Caller ID you have previously validated
    with Twilio */
$from = '34518880185';
 
/* Number you wish to call */
//$to= '34608754342';
$to= $queNum;
  
 
/* Directory location for callback.php file (for use in REST URL)*/
$url = $domain;
 
/* Instantiate a new Twilio Rest Client */
$client = new Services_Twilio($AccountSid, $AuthToken);

/*
if (!isset($_REQUEST['called'])) {
    $err = urlencode("Must specify your phone number");
    header("Location: index.php?msg=$err");
    die;
}
*/
 
/* make Twilio REST request to initiate outgoing call */
//$call = $client->account->calls->create($from, $to, $url . 'callback.php?number=' . $_REQUEST['called']);
  $call = $client->account->calls->create($from, $to, $url . 'callback.php?number=' . $to);
 
 
/* redirect back to the main page with CallSid */
$msg = urlencode("Connecting... ".$call->sid);
//header("Location: index.php?msg=$msg");
return 'OK';

}

function SendCallVERIFMED($numero)
{

require "Services/Twilio.php";

$queNum = $numero; 
/* Set our AccountSid and AuthToken */
    $AccountSid = "ACab25dece43f31dfc026f49f265bab68d";
    $AuthToken = "daf7b824e016c5a13cd076ca44353d9b";
 
/* Your Twilio Number or an Outgoing Caller ID you have previously validated
    with Twilio */
$from = '34902848201';
 
/* Number you wish to call */
//$to= '34608754342';
$to= $queNum;
  
 
/* Directory location for callback.php file (for use in REST URL)*/
$url = $domain;
 
/* Instantiate a new Twilio Rest Client */
$client = new Services_Twilio($AccountSid, $AuthToken);

/*
if (!isset($_REQUEST['called'])) {
    $err = urlencode("Must specify your phone number");
    header("Location: index.php?msg=$err");
    die;
}
*/
 
/* make Twilio REST request to initiate outgoing call */
//$call = $client->account->calls->create($from, $to, $url . 'callback.php?number=' . $_REQUEST['called']);
  $call = $client->account->calls->create($from, $to, $url . 'callbackMED.php?number=' . $to);
 
 
/* redirect back to the main page with CallSid */
$msg = urlencode("Connecting... ".$call->sid);
//header("Location: index.php?msg=$msg");
return 'OK';

}


 ?>