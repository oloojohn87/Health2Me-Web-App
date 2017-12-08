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

$dx1=$_GET['NameDX'];
$dx2=$_GET['ICDCODE'];
$dx3=$_GET['DateStart'];
$dx4=$_GET['DateStop'];

$sql=$con->prepare("INSERT INTO dx VALUES (?,?,?,?)");
$sql->bindValue(1, $dx1, PDO::PARAM_STR);
$sql->bindValue(2, $dx2, PDO::PARAM_STR);
$sql->bindValue(3, $dx3, PDO::PARAM_STR);
$sql->bindValue(4, $dx4, PDO::PARAM_STR);
$sql->execute();


if(!$sql)
{
die('Error: MYSQL');
}
?>
