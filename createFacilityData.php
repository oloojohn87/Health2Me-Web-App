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


$name = $_GET['name'];
$street = $_GET['street'];
$city = $_GET['city'];
$state = $_GET['state'];
$zip = $_GET['zip'];
$doc_id = $_GET['docid'];


$query = $con->prepare("INSERT INTO schedule_facility SET name=?, street=?, city=?, state=?, zip=?, doctor_id=?");
$query->bindValue(1, $name, PDO::PARAM_STR);
$query->bindValue(2, $street, PDO::PARAM_STR);
$query->bindValue(3, $city, PDO::PARAM_INT);
$query->bindValue(4, $state, PDO::PARAM_INT);
$query->bindValue(5, $zip, PDO::PARAM_STR);
$query->bindValue(6, $doc_id, PDO::PARAM_INT);
$result = $query->execute();


?>