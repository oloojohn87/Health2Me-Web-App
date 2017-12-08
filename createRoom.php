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

 
 
/*$idpatient = $_SESSION['UserID'];
$iddoctor = $_SESSION['MEDID'];
if(!isset($iddoctor)){
$iddoctor = 0;
}
if ($iddoctor == $idpatient) $idauthor = -1; else $idauthor = $iddoctor;
*/

$id = $_GET['id'];


$query = $con->prepare("INSERT INTO schedule_rooms SET facility_id=?");
$query->bindValue(1, $id, PDO::PARAM_INT);
$result = $query->execute();
?>