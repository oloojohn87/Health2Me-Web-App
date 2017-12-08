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

$idp = $_GET['idp'];
$height = $_GET['height'];
$weight = $_GET['weight'];
$hbp = $_GET['hbp'];
$lbp = $_GET['lbp'];
$daterec = $_GET['daterec'];

if($daterec=='')
{
	$query = $con->prepare("insert into changing_personal_history(idpatient,height,weight,hbp,lbp,date_rec) values(?,?,?,?,?,now())");
	$query->bindValue(1, $idp, PDO::PARAM_INT);
	$query->bindValue(2, $height, PDO::PARAM_STR);
	$query->bindValue(3, $weight, PDO::PARAM_STR);
	$query->bindValue(4, $hbp, PDO::PARAM_STR);
	$query->bindValue(5, $lbp, PDO::PARAM_STR);
	
}
else
{
	$query = $con->prepare("insert into changing_personal_history(idpatient,height,weight,hbp,lbp,date_rec) values(?,?,?,?,?,?)");
	$query->bindValue(1, $idp, PDO::PARAM_INT);
	$query->bindValue(2, $height, PDO::PARAM_STR);
	$query->bindValue(3, $weight, PDO::PARAM_STR);
	$query->bindValue(4, $hbp, PDO::PARAM_STR);
	$query->bindValue(5, $lbp, PDO::PARAM_STR);
	$query->bindValue(6, $daterec, PDO::PARAM_STR);
}

if($query->execute())
{
	echo 'success';
}



?>