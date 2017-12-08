<?php

require("environment_detail.php");
require_once('push_server.php');
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

$doc = $_POST['doc'];
$pat = $_POST['pat'];

$probe = $con->prepare("SELECT probeID,emailRequest,smsRequest,phoneRequest,patientPermission,doctorPermission,protocolID FROM probe WHERE doctorID = ? AND patientID = ?");
$probe->bindValue(1, $doc, PDO::PARAM_INT);
$probe->bindValue(2, $pat, PDO::PARAM_INT);
$probe->execute();
$probe_row = $probe->fetch(PDO::FETCH_ASSOC);

$doctor = $con->prepare("SELECT Name,Surname FROM doctors WHERE id = ?");
$doctor->bindValue(1, $doc, PDO::PARAM_INT);
$doctor->execute();
$doctor_row = $doctor->fetch(PDO::FETCH_ASSOC);

$patient = $con->prepare("SELECT Name,Surname,telefono,email FROM usuarios WHERE Identif = ?");
$patient->bindValue(1, $pat, PDO::PARAM_INT);
$patient->execute();
$patient_row = $patient->fetch(PDO::FETCH_ASSOC);

if($probe_row['patientPermission'] == 1)
{
    if($probe_row['protocolID'] != 0 && ($probe_row['emailRequest'] == 1 || $probe_row['smsRequest'] == 1 || $probe_row['phoneRequest'] == 1))
    {
        if($probe_row['emailRequest'] == 1)
        {
            Push_Probe_Email($doctor_row['Surname'], $patient_row['email'], $probe_row['probeID'], 0);
        }
        else if($probe_row['smsRequest'] == 1)
        {
            Send_Feedback_SMS($doctor_row['Surname'], $patient_row['Name'].' '.$patient_row['Surname'], $patient_row['telefono'], $probe_row['probeID'], 0, 1);
        }
        else if($probe_row['phoneRequest'] == 1)
        {
            Health_Feedback_Call($doctor_row['Name'], $doctor_row['Surname'], $patient_row['Name'], $patient_row['Surname'], $patient_row['telefono'], $probe_row['probeID'], 0);
        }
        echo "1";
    }
    else
    {
        echo '-2';
    }
}
else
{
	echo "-1";
}

?>
