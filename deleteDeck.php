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

$IdDr= $_GET['IdDr'];
$IdPatient= $_GET['IdPatient'];

$query = $con->prepare("DELETE FROM deck WHERE IdDr=? AND IdPatient=? ");
$query->bindValue(1, $IdDr, PDO::PARAM_INT);
$query->bindValue(2, $IdPatient, PDO::PARAM_INT);
$query->execute();


?>


