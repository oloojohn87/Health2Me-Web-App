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

$pers1=$_GET['frac'];
$pers2=$_GET['surg'];
$pers3=$_GET['oknown'];
$pers4=$_GET['obs'];
$pers5=$_GET['omed'];
$sql=$con->prepare("INSERT INTO personal VALUES ('$pers1','$pers2','$pers3','$pers4','$pers5')");
$sql->bindValue(1, $pers1, PDO::PARAM_STR);
$sql->bindValue(2, $pers2, PDO::PARAM_STR);
$sql->bindValue(3, $pers3, PDO::PARAM_STR);
$sql->bindValue(4, $pers4, PDO::PARAM_STR);
$sql->bindValue(5, $pers5, PDO::PARAM_STR);
$sql->execute();

if(!$sql)
{
die('Error: ' .mysql_error());
}
?>
