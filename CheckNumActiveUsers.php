<?php
require("environment_detail.php");
require("push_server.php");
//require_once("push_server.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 
 

$MEDID= $_GET['MEDID'];
$GROUPID= $_GET['GROUPID'];

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

    $result2 = $con->prepare("SELECT * FROM usuarios where Password IS NOT NULL AND  (IdCreator=? or IdCreator IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = ?) or Identif IN (SELECT IdUs FROM doctorslinkusers WHERE IdMED = ?))");
	$result2->bindValue(1, $MEDID, PDO::PARAM_INT);
	$result2->bindValue(2, $GROUPID, PDO::PARAM_INT);
	$result2->bindValue(3, $MEDID, PDO::PARAM_INT);
	$result2->execute();
	
	$NumActiveUsers = $result2->rowCount();
	echo $NumActiveUsers;

?>