<?php
require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];



$tbl_name="usuarios"; // Table name

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$patient_id = $_GET['patient_id'];
$doctor_id = $_GET['doctor_id'];

$otherdoc = -1;

$sql=$con->prepare("SELECT * FROM doctorslinkdoctors where Idpac =? and (IdMED=? or IdMED2=?) and estado=2 ");
$sql->bindValue(1, $patient_id, PDO::PARAM_INT);
$sql->bindValue(2, $doctor_id, PDO::PARAM_INT);
$sql->bindValue(3, $doctor_id, PDO::PARAM_INT);
$q = $sql->execute();

if($q){
	$row=$sql->fetch(PDO::FETCH_ASSOC);
	if($row['IdMED2']==$doctor_id){
	    $otherdoc=$row['IdMED'];
		$referral_id=$row['id'];
		
	}else if($row['IdMED']==$doctor_id) {
		$otherdoc=$row['IdMED2'];
		$referral_id=$row['id'];			
	}

$sql=$con->prepare("SELECT * FROM doctors where id=? ");
$sql->bindValue(1, $doctor_id, PDO::PARAM_INT);
$q2 = $sql->execute();

$NameDr = '';
if($q2){
	$row2=$sql->fetch(PDO::FETCH_ASSOC);
	$NameDr = 'Dr '.$row2['Name'].' '.$row2['Surname'];
}
$sql=$con->prepare("SELECT * FROM usuarios where Identif=? ");
$sql->bindValue(1, $patient_id, PDO::PARAM_INT);
$q2 = $sql->execute();

$NameUs = '';
if($q2){
	$row2=$sql->fetch(PDO::FETCH_ASSOC);
	$NameUs = $row2['Name'].' '.$row2['Surname'];
}


}



echo $otherdoc.','.$NameDr.','.$NameUs;
//echo $IdDoctor.','.$NameDoctor.','.$SurnameDoctor;

?>