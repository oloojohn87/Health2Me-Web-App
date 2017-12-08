<?php
require "Services/Twilio.php";
require("environment_detailForLogin.php");
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
require_once('push/push.php');
$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];
// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

//$app_key = 'd869a07d8f17a76448ed';
//$app_secret = '92f67fb5b104260bbc02';
//$app_id = '51379';
//$pusher = new Pusher($app_key, $app_secret, $app_id);
$push = new Push();

$info = explode("_", $_GET['info']);
$pat_id = $info[1];
$doc_id = $info[0];
$recent_id = $info[2];
$res = $con->prepare("SELECT * FROM usuarios WHERE Identif=?");
$res->bindValue(1, $pat_id, PDO::PARAM_INT);
$res->execute();

$row = $res->fetch(PDO::FETCH_ASSOC);
$pat_name = $row['Name'].' '.$row['Surname'];
$res = $con->prepare("SELECT * FROM doctors WHERE id=?");
$res->bindValue(1, $doc_id, PDO::PARAM_INT);
$res->execute();

$row = $res->fetch(PDO::FETCH_ASSOC);
$doc_name = $row['Name'].' '.$row['Surname'];



echo '<?xml version="1.0" encoding="UTF-8" ?>';

if(isset($_REQUEST['Digits']) && isset($_REQUEST['CallStatus']) && $_REQUEST['CallStatus'] == 'in-progress' )
{
	$d_status = $con->prepare("UPDATE consults SET Doctor_Status = 1 WHERE consultationId = ?");
	$d_status->bindValue(1, $recent_id, PDO::PARAM_INT);
	$d_status->execute();

    $push->send('Telemedicine', 'ameridoc', 'Connecting Doctor '.$doc_name.' to patient '.$pat_name);
    echo '<Response>';

    echo '<Dial><Conference record="record-from-start" maxParticipants="2" endConferenceOnExit="true" eventCallbackUrl="phone_appointment_callback.php?info='.$info[0].'_'.$info[1].'_'.$recent_id.'">'.$info[0].'_'.$info[1].'</Conference></Dial>';

    echo '</Response>';
}
else if(isset($_REQUEST['CallStatus']) && $_REQUEST['CallStatus'] == 'in-progress' )
{
    $push->send('Telemedicine', 'ameridoc', 'Asking Doctor '.$doc_name.' to accept consultation with patient '.$pat_name);
    echo '<Response>';
    echo '<Gather action="http://'.$_SERVER['HTTP_HOST'].'/live/ameridoc_call_doctor.php?info='.$info[0].'_'.$info[1].'_'.$recent_id.'" method="GET" timeout="4" numDigits="1">';
    echo '<Say language="en">'.$pat_name.' would like to start a phone consultation with you. Would you like to accept? Please press any digit to accept the consultation.</Say>';
    echo '</Gather>';
    echo '<Say language="en">Consultation denied. Good bye!</Say>';
    echo '<Hangup/>';
    echo '</Response>';
}
?>
