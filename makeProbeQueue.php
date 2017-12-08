<?php
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

$user_id = $_POST['user_id'];
$doc_id = $_POST['doc_id'];
$protocol_id = $_POST['protocol_id'];
$desired_time = $_POST['desired_time'];
$timezone = $_POST['timezone'];
$probe_interval = $_POST['probe_interval'];
$email_request = $_POST['email_request'];
$phone_request = $_POST['phone_request'];
$sms_request = $_POST['sms_request'];

$current_date = date("Y-m-d H:i:s");

$query = $con->prepare("INSERT INTO probes SET doctorID = ?, patientID = ?, protocolID = ?, desiredTime = ?, timezone = ?, probeInterval = ?, doctorPermission = '1', patientPermission = '1', emailRequest = ?, phoneRequest = ?, smsRequest = ?, creationDate = ?, currentQuestion = '1'");
$query->bindValue(1, $doc_id, PDO::PARAM_INT);
$query->bindValue(2, $user_id, PDO::PARAM_INT);
$query->bindValue(3, $protocol_id, PDO::PARAM_INT);
$query->bindValue(4, $desired_time, PDO::PARAM_STR);
$query->bindValue(5, $timezone, PDO::PARAM_INT);
$query->bindValue(6, $probe_interval, PDO::PARAM_INT);
$query->bindValue(7, $email_request, PDO::PARAM_INT);
$query->bindValue(8, $phone_request, PDO::PARAM_INT);
$query->bindValue(9, $sms_request, PDO::PARAM_INT);
$query->bindValue(10, $current_date, PDO::PARAM_STR);
$query->execute();
?>
