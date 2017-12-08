<?php

session_start();
ini_set("display_errors", 0);
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

$result = $con->prepare("SELECT Patient FROM consults where consultationId = ?");
$result->bindValue(1, $_POST['id'], PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);

$result = $con->prepare("SELECT email FROM usuarios where Identif = ?");
$result->bindValue(1, $row['Patient'], PDO::PARAM_INT);
$result->execute();
$row2 = $result->fetch(PDO::FETCH_ASSOC);

echo (htmlspecialchars($row2['email']));


?>