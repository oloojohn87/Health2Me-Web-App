<?php
session_start();
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


$id = $_GET['id'];
$minutes = $_GET['minutes'];

$query = $con->prepare("update notification_config set minutes = ? where id=?");
$query->bindValue(1, $minutes, PDO::PARAM_INT);
$query->bindValue(2, $id, PDO::PARAM_INT);
echo $query;
$query->execute();


?>