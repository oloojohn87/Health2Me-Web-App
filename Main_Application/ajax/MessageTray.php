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
$Add_doctor_id = $_GET['Add_doctor_id'];
$MType = $_GET['MType'];
$TMessage = $_GET['TMessage'];
$IconText = $_GET['IconText'];
$SubText = $_GET['SubText'];
$MainText = $_GET['MainText'];
$ColorText = $_GET['ColorText'];
$LinkText = $_GET['LinkText'];


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
}

$q2 = $con->prepare("INSERT INTO onscreentray SET IdUser =?, IdDoctor =?, IdDoctorADD =?, MType=?, Message=?, IconText=?, SubText=?, MainText=?, MColor=?, MLink=?, MRead='0' , DateTimeStamp=NOW() ");
$q2->bindValue(1, $patient_id, PDO::PARAM_INT);
$q2->bindValue(2, $otherdoc, PDO::PARAM_INT);
$q2->bindValue(3, $doctor_id, PDO::PARAM_INT);
$q2->bindValue(4, $MType, PDO::PARAM_INT);
$q2->bindValue(5, $TMessage, PDO::PARAM_STR);
$q2->bindValue(6, $IconText, PDO::PARAM_STR);
$q2->bindValue(7, $SubText, PDO::PARAM_STR);
$q2->bindValue(8, $MainText, PDO::PARAM_STR);
$q2->bindValue(9, $ColorText, PDO::PARAM_STR);
$q2->bindValue(10, $LinkText, PDO::PARAM_STR);
$q2->execute();
?>