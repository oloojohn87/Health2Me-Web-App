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

$user_id = $_POST['user_id'];
$doctor_id = $_POST['doctor_id'];

$res = $con->prepare("UPDATE usuarios SET personal_doctor = ? WHERE Identif = ?");
$res->bindValue(1, $doctor_id, PDO::PARAM_INT);
$res->bindValue(2, $user_id, PDO::PARAM_INT);
$res->execute();



?>