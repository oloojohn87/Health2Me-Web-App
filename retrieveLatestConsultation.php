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

$doctor = $_POST['doctor'];
$patient = $_POST['patient'];

$result = $con->prepare("SELECT * FROM consults WHERE Doctor = ? AND Patient = ? ORDER BY DateTime DESC");
$result->bindValue(1, $doctor, PDO::PARAM_INT);
$result->bindValue(2, $patient, PDO::PARAM_INT);
$result->execute();

$row = $result->fetch(PDO::FETCH_ASSOC);

echo json_encode($row);


?>