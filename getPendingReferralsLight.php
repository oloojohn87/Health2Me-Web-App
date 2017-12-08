<?php
 
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

$queUsu = $_POST['Doctor'];


$cadena = array();
$cadena_added = 0;
$result = $con->prepare("SELECT id,IdMED,IdPac,Fecha FROM doctorslinkdoctors WHERE IdMED2=? AND estado=1");
$result->bindValue(1, $queUsu, PDO::PARAM_INT);
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC))
{
    $referral_id = $row['id'];
    $sender = $row['IdMED'];
    $patient = $row['IdPac'];
    $date = $row['Fecha'];
    $patient_name = '';
    $doctor_name = '';
    $res2 = $con->prepare("SELECT Name,Surname FROM usuarios WHERE Identif=?");
	$res2->bindValue(1, $patient, PDO::PARAM_INT);
	$res2->execute();

    if ($row2 = $res2->fetch(PDO::FETCH_ASSOC))
    {
        $patient_name = $row2['Name'].' '.$row2['Surname'];
    }
    $res3 = $con->prepare("SELECT Name,Surname FROM doctors WHERE id=?");
	$res3->bindValue(1, $sender, PDO::PARAM_INT);
	$res3->execute();
	
    if ($row3 = $res3->fetch(PDO::FETCH_ASSOC))
    {
        $doctor_name = $row3['Name'].' '.$row3['Surname'];
    }
    $arr = array("type" => "referral", "DOCTOR" => $doctor_name, "PATIENT" => $patient_name, "PATIENT_ID" => $patient, "ID" => $referral_id, "date" => $date);
    array_push($cadena, $arr);
}


$encode = json_encode($cadena);
echo $encode; 

?>