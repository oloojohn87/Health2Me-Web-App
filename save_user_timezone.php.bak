<?php

session_start();
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 
 $userid = $_GET['userid'];
 $timez = $_GET['timez'];
 
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		


$query = $con->prepare("update user_timezone_config set timez=? where userid=?");
$query->bindValue(1, $timez, PDO::PARAM_INT);
$query->bindValue(2, $userid, PDO::PARAM_INT);
$query->execute();
	
?>