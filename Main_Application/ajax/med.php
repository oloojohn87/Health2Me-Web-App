<?php
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

$med1=$_GET['mname'];
$med2=$_GET['mcode'];
$med3=$_GET['mdose'];
$med4=$_GET['mnum'];
$med5=$_GET['mstart'];
$med6=$_GET['mend'];

$sql=$con->prepare("INSERT INTO medic VALUES (?,?,?,?,?,?)");
$sql->bindValue(1, $med1, PDO::PARAM_STR);
$sql->bindValue(2, $med2, PDO::PARAM_STR);
$sql->bindValue(3, $med3, PDO::PARAM_STR);
$sql->bindValue(4, $med4, PDO::PARAM_INT);
$sql->bindValue(5, $med5, PDO::PARAM_STR);
$sql->bindValue(6, $med6, PDO::PARAM_STR);
$sql->execute();


if(!$sql)
{
die('Error: MYSQL');
}
?>
