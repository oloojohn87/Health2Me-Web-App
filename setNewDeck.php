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
$NamePatient= $_GET['NamePatient'];
$VType= $_GET['Type'];
$Alert= $_GET['Alert'];
$VTime= $_GET['Time'];
$d = '';
if (isset($_GET['Date']))
{
    $d = $_GET['Date'];
}

$query_str = "";
if (strlen($d) == 0)
{
    $query_str = $con->prepare("insert into deck set IdDr=?,IdPatient=?,NamePatient=?,Type=?,Alert=?,Time=?, Date=NOW() ");
	$query_str->bindValue(1, $IdDr, PDO::PARAM_INT);
	$query_str->bindValue(2, $IdPatient, PDO::PARAM_INT);
	$query_str->bindValue(3, $NamePatient, PDO::PARAM_STR);
	$query_str->bindValue(4, $VType, PDO::PARAM_INT);
	$query_str->bindValue(5, $Alert, PDO::PARAM_INT);
	$query_str->bindValue(6, $VTime, PDO::PARAM_STR);
	
}
else
{
    $query_str = $con->prepare("insert into deck set IdDr=?,IdPatient=?,NamePatient=?,Type=?,Alert=?,Time=?, Date=?");
	$query_str->bindValue(1, $IdDr, PDO::PARAM_INT);
	$query_str->bindValue(2, $IdPatient, PDO::PARAM_INT);
	$query_str->bindValue(3, $NamePatient, PDO::PARAM_STR);
	$query_str->bindValue(4, $VType, PDO::PARAM_INT);
	$query_str->bindValue(5, $Alert, PDO::PARAM_INT);
	$query_str->bindValue(6, $VTime, PDO::PARAM_STR);
	$query_str->bindValue(7, $d.' 00:00:00', PDO::PARAM_STR);
}
$query=$query_str->execute();



?>


