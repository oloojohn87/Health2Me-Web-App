<?php

require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

$doc = $_POST['doctor'];
$pat = $_POST['patient'];
$protocol = $_POST['protocol'];
$time = $_POST['time'];
$timezone = $_POST['timezone'];
$interval = $_POST['interval'];
$request = $_POST['request'];
$start_val = $_POST['start_val'];
$exp_val = $_POST['exp_val'];
$exp_day_1 = $_POST['exp_day_1'];
$exp_day_2 = $_POST['exp_day_2'];
$tolerance = $_POST['tolerance'];
$probeLanguage = $_POST['probeLanguage'];

$res = 1;
if($protocol == 0 || strlen($time) == 0 || $timezone == 0 || $interval == 0)
    $res = -1;

// see if there already is an entry in the probe table for this probe
$probe = null;
$check = $con->prepare("SELECT * FROM probe WHERE doctorID = ? AND patientID = ?");
$check->bindValue(1, $doc, PDO::PARAM_INT);
$check->bindValue(2, $pat, PDO::PARAM_INT);
$check->execute();
if($check->rowCount() > 0)
{
    $probe = $check->fetch(PDO::FETCH_ASSOC);
}
else
{
    $ins = $con->prepare("INSERT INTO probe (doctorID, patientID, doctorPermission, patientPermission) VALUES (?, ?, 0, 0)");
    $ins->bindValue(1, $doc, PDO::PARAM_INT);
    $ins->bindValue(2, $pat, PDO::PARAM_INT);
    $ins->execute();
    
    $check = $con->prepare("SELECT * FROM probe WHERE doctorID = ? AND patientID = ?");
    $check->bindValue(1, $doc, PDO::PARAM_INT);
    $check->bindValue(2, $pat, PDO::PARAM_INT);
    $check->execute();
    
    $probe = $check->fetch(PDO::FETCH_ASSOC);
}

$email = 0;
$phone = 0;
$sms = 0;
if($request == '1')
    $sms = 1;
else if($request == '2')
    $phone = 1;
else if($request == '3')
    $email = 1;
    
if($email == 0 && $phone == 0 && $sms == 0)
    $res = -1;


$ins = $con->prepare("UPDATE probe SET protocolID = ?, desiredTime = ?, timezone = ?, probeInterval = ?, emailRequest = ?, phoneRequest = ?, smsRequest = ?, probeLanguage = ?, creationDate = NOW() WHERE probeID = ?");
$ins->bindValue(1, $protocol, PDO::PARAM_INT);
$ins->bindValue(2, $time, PDO::PARAM_STR);
$ins->bindValue(3, $timezone, PDO::PARAM_INT);
$ins->bindValue(4, $interval, PDO::PARAM_INT);
$ins->bindValue(5, $email, PDO::PARAM_INT);
$ins->bindValue(6, $phone, PDO::PARAM_INT);
$ins->bindValue(7, $sms, PDO::PARAM_INT);
$ins->bindValue(8, $probeLanguage, PDO::PARAM_STR);
$ins->bindValue(9, $probe['probeID'], PDO::PARAM_INT);
$ins->execute();

$check = $con->prepare("SELECT id FROM probe_alerts WHERE probe = ? AND question = 1");
$check->bindValue(1, $probe['probeID'], PDO::PARAM_INT);
$check->execute();
if($check->rowCount() > 0)
{
    $upd = $con->prepare("UPDATE probe_alerts SET start_value = ?, exp_value = ?, exp_day_1 = ?, exp_day_2 = ?, tolerance = ? WHERE probe = ? AND question = 1");
    $upd->bindValue(1, $start_val, PDO::PARAM_INT);
    $upd->bindValue(2, $exp_val, PDO::PARAM_INT);
    $upd->bindValue(3, $exp_day_1, PDO::PARAM_INT);
    $upd->bindValue(4, $exp_day_2, PDO::PARAM_INT);
    $upd->bindValue(5, $tolerance, PDO::PARAM_INT);
    $upd->bindValue(6, $probe['probeID'], PDO::PARAM_INT);
    $upd->execute();
}
else
{
    $ins = $con->prepare("INSERT INTO probe_alerts SET start_value = ?, exp_value = ?, exp_day_1 = ?, exp_day_2 = ?, tolerance = ?, probe = ?, question = 1");
    $ins->bindValue(1, $start_val, PDO::PARAM_INT);
    $ins->bindValue(2, $exp_val, PDO::PARAM_INT);
    $ins->bindValue(3, $exp_day_1, PDO::PARAM_INT);
    $ins->bindValue(4, $exp_day_2, PDO::PARAM_INT);
    $ins->bindValue(5, $tolerance, PDO::PARAM_INT);
    $ins->bindValue(6, $probe['probeID'], PDO::PARAM_INT);
    $ins->execute();
}

echo $res;

?>