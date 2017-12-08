<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 
 

$tbl_name="messages"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }				

$queBlock = $_GET['queBlock'];
$queUser = $_GET['queUser'];
$UltimoEvento = $_GET['UltimoEvento'];
$Nombre = $_GET['Nombre'];

$UltimoEvento++;

$q = $con->prepare("INSERT INTO usueventos SET IdUsu=?, IdEvento=?, Nombre=? ");
$q->bindValue(1, $queUser, PDO::PARAM_INT);
$q->bindValue(2, $UltimoEvento, PDO::PARAM_INT);
$q->bindValue(3, $Nombre, PDO::PARAM_STR);
$q->execute();

?>