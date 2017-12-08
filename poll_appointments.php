<?php

require "environment_detail.php";
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$appointment_id = $_POST['appointment_id'];
$type = $_POST['type'];
$obj = "";

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

if($type == 2)
{
    // get doctor webrtc description object for the patient
    $query = $con->prepare("SELECT med_desc FROM appointments WHERE id=?");
	$query->bindValue(1, $appointment_id, PDO::PARAM_INT);
	$res = $query->execute();
	
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $obj = $row['med_desc'];
}
else if($type == 1)
{
    // get patient webrtc description object for the doctor
    $query = $con->prepare("SELECT pat_desc FROM appointments WHERE id=?");
	$query->bindValue(1, $appointment_id, PDO::PARAM_INT);
	$res = $query->execute();
	
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $obj = $row['pat_desc'];
}
else if($type == 4)
{
    // get patient webrtc ice candidates for the doctor
    $query = $con->prepare("SELECT pat_candidates FROM appointments WHERE id=?");
	$query->bindValue(1, $appointment_id, PDO::PARAM_INT);
	$res = $query->execute();
	
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $obj = $row['pat_candidates'];
}
else if($type == 3)
{
    // get doctor webrtc ice candidates for the patient
    $query = $con->prepare("SELECT med_candidates FROM appointments WHERE id=?");
	$query->bindValue(1, $appointment_id, PDO::PARAM_INT);
	$res = $query->execute();
	
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $obj = $row['med_candidates'];
}

echo $obj;

?>