<?php
session_start();
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			

 
 
$idpatient = $_SESSION['UserID'];
$iddoctor = $_SESSION['MEDID'];
if(!isset($iddoctor)){
$iddoctor = 0;
}
if ($iddoctor == $idpatient) $idauthor = -1; else $idauthor = $iddoctor;


$bill_name = $_GET['superbillname'];
$bill_date = $_GET['superbilldate'];
$bill_edate = $_GET['superbilledate'];
$service_location = $_GET['servicelocation'];


$query = $con->prepare("INSERT INTO bill_super SET name=?, sdate=?, patient_id=?, doctor_id=?, edate=?, service_location=?");
$query->bindValue(1, $bill_name, PDO::PARAM_STR);
$query->bindValue(2, $bill_date, PDO::PARAM_STR);
$query->bindValue(3, $idpatient, PDO::PARAM_INT);
$query->bindValue(4, $iddoctor, PDO::PARAM_INT);
$query->bindValue(5, $bill_edate, PDO::PARAM_STR);
$query->bindValue(6, $service_location, PDO::PARAM_STR);
$result = $query->execute();


?>