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
$name = $_GET['name'];
$type = $_GET['type'];
$date = $_GET['date'];
$description = $_GET['description'];

$query = $con->prepare("insert into allergies(idpatient,name,type,daterec,description) values(?,?,?,?,?)");
$query->bindValue(1, $idp, PDO::PARAM_INT);
$query->bindValue(2, $name, PDO::PARAM_STR);
$query->bindValue(3, $type, PDO::PARAM_STR);
$query->bindValue(4, $date, PDO::PARAM_STR);
$query->bindValue(5, $description, PDO::PARAM_STR);


if($query->execute())
{
	echo 'success';
}



?>