<?php
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			

$queUsu = $_GET['Doctor'];
$NReports = $_GET['NReports'];
$daysExplore=empty($_GET['days']) ? 30: $_GET['days'];


$cadena = '';

//Get Information about Referrals
$result = $con->prepare("SELECT id FROM doctorslinkdoctors WHERE IdMED = ? ");
$result->bindValue(1, $queUsu, PDO::PARAM_INT);
$result->execute();
$Number_Patients_IReferred = $result->rowCount();

$result = $con->prepare("SELECT distinct(IdMED2) FROM doctorslinkdoctors WHERE IdMED = ? ");
$result->bindValue(1, $queUsu, PDO::PARAM_INT);
$result->execute();
$Number_Doctors_IReferred = $result->rowCount();

$result = $con->prepare("SELECT id FROM doctorslinkdoctors WHERE IdMED2 = ? ");
$result->bindValue(1, $queUsu, PDO::PARAM_INT);
$result->execute();
$Number_Patients_Referred2Me = $result->rowCount();

$result = $con->prepare("SELECT distinct(IdMED) FROM doctorslinkdoctors WHERE IdMED2 = ? ");
$result->bindValue(1, $queUsu, PDO::PARAM_INT);
$result->execute();
$Number_Doctors_Referred2Me = $result->rowCount();
      
//	if ($n>0) $cadena.=',';    
	$cadena.='
    {
        "IN":"'.$Number_Patients_Referred2Me.'",
        "OUT":"'.$Number_Patients_IReferred.'",
        "DRIN":"'.$Number_Doctors_Referred2Me.'",
        "DROUT":"'.$Number_Doctors_IReferred.'"
        }';    

$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 


?>