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

$type = $_POST['type'];
$id = $_POST['id'];

if($type == 1) // doctor
{
    $update = $con->prepare("UPDATE appointments SET doctor_ack = 1 WHERE id = ?");
    $update->bindValue(1, $id, PDO::PARAM_INT);
    $update->execute();
    echo '1';
}
else // patient
{
    $update = $con->prepare("UPDATE appointments SET patient_ack = 1 WHERE id = ?");
    $update->bindValue(1, $id, PDO::PARAM_INT);
    $update->execute();
    echo '1';
}