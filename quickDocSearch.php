<?php
require("environment_detail.php");
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
  
$string = $_GET['search'];

$result = $con->prepare("SELECT * FROM doctors where Name LIKE ? OR Surname LIKE ? OR IdMEDEmail LIKE ?");
$result->bindValue(1, '%'.$string.'%', PDO::PARAM_STR);
$result->bindValue(2, '%'.$string.'%', PDO::PARAM_STR);
$result->bindValue(3, '%'.$string.'%', PDO::PARAM_STR);
$result->execute();

$row = $result->fetch(PDO::FETCH_ASSOC);

echo json_encode($row);
?>
