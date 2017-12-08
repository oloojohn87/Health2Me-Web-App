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

$doctorId = $_POST['Doctor'];

$cadena = array();
$cadena_added = 0;
$query1 = $con->prepare("SELECT patient_id,message_id,fecha FROM message_infrastructureuser WHERE tofrom = 'to' and receiver_id=? and subject = 'Send Mail' and status ='new'");
$query1->bindValue(1, $doctorId, PDO::PARAM_INT);
$query1->execute();


while($row1 = $query1->fetch(PDO::FETCH_ASSOC))
{
    $patientId = $row1['patient_id'];
    $patient_name = '';
    $doctor_name = '';
    $message_id = $row1['message_id'];
    $date = $row1['fecha'];
    
    $query2 = $con->prepare("SELECT Name,Surname FROM usuarios WHERE Identif=?");
	$query2->bindValue(1, $patientId, PDO::PARAM_INT);
	$query2->execute();
	
    if ($row2 = $query2->fetch(PDO::FETCH_ASSOC))
    {
        $patient_name = $row2['Name'].' '.$row2['Surname'];
    }
    $query3 = $con->prepare("SELECT Name,Surname FROM doctors WHERE id=?");
	$query3->bindValue(1, $doctorId, PDO::PARAM_INT);
	$query3->execute();
	
    if ($row3 = $query3->fetch(PDO::FETCH_ASSOC))
    {
        $doctor_name = $row3['Name'].' '.$row3['Surname'];
    }
    $arr = array("type" => "review", "DOCTOR" => $doctor_name, "PATIENT" => $patient_name,"USER"=>$patientId,"MESSAGE_ID"=>$message_id, "date" => $date);
    array_push($cadena, $arr);
}


$encode = json_encode($cadena);
echo $encode; 

?>