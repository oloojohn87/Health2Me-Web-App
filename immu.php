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

$immu1=$_GET['iname'];
$immu2=$_GET['idate'];
$immu3=$_GET['iage'];
$immu4=$_GET['ireaction'];

$sql=$con->prepare("INSERT INTO immu VALUES (?,?,?,?)");
$sql->bindValue(1, $immu1, PDO::PARAM_STR);
$sql->bindValue(2, $immu2, PDO::PARAM_STR);
$sql->bindValue(3, $immu3, PDO::PARAM_INT);
$sql->bindValue(4, $immu4, PDO::PARAM_STR);
$sql->execute();


if(!$sql)
{
die('Error: ' .mysql_error());
}
?>
