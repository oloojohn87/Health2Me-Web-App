<?php

require('../environment_detail.php');

$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];
$hardcode = $env_var_db['hardcode'];

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}



$IdPin = $_POST['id'];
$classification = $_POST['classification'];

$update = $con->prepare("UPDATE lifepin SET Tipo = ? WHERE IdPin = ?");
$update->bindValue(1, $classification, PDO::PARAM_INT);
$update->bindValue(2, $IdPin, PDO::PARAM_INT);
$update->execute();

?>