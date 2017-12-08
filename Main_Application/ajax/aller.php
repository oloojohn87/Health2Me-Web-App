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

$aller1=$_GET['name'];
$aller2=$_GET['type'];
$aller3=$_GET['rec'];
$aller4=$_GET['desc'];

$sql=$con->prepare("INSERT INTO aller VALUES (?,?,?,?)");
$sql->bindValue(1, $aller1, PDO::PARAM_STR);
$sql->bindValue(2, $aller2, PDO::PARAM_STR);
$sql->bindValue(3, $aller3, PDO::PARAM_STR);
$sql->bindValue(4, $aller4, PDO::PARAM_STR);
$sql->execute();


if(!$sql)
{
die('Error: MYSQL');
}
?>
