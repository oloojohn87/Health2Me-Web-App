<?php
ini_set("display_errors", 0);
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

$UserID = $_SESSION['UserID'];

$Query= $con->prepare("select * from p_habits where idpatient=?");
$Query->bindValue(1, $UserID, PDO::PARAM_INT);
$result=$Query->execute();

$count = $Query->rowCount();





?>