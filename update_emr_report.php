<?php
	
session_start();
require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 
// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$idcreator = $_SESSION['MEDID'];
//$userid = 28;
$id = $_GET['id'];


$query = $con->prepare("select idpin from lifepin where idusu=? and emr_report=1 and emr_old=0");
$query->bindValue(1, $id, PDO::PARAM_INT);

$result = $query->execute();
$num_rows = $query->rowCount();
$row = $query->fetch(PDO::FETCH_ASSOC);
if($num_rows == 1)
{
	//update old report
	$query1 = $con->prepare("update lifepin set emr_old=1 where idpin=?");
	$query1->bindValue(1, $row['idpin'], PDO::PARAM_INT);
	$query1->execute();
	$query2 = "update lifepin set emr_old=1 where idpin=?";
	echo $query2;
	
}
else
{
	echo 'here';
}
	
?>