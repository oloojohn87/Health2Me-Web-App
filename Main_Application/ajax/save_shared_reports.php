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

$doctor = $_POST['doctor'];
$patient = $_POST['patient'];
$doctor_files = json_decode($_POST['doctor_files']);
$patient_files = json_decode($_POST['patient_files']);
$num_patient_files = count($patient_files);
$num_doctor_files = count($doctor_files);
foreach($doctor_files as $key => $value)
{
    $num_reports = count($value);
    $attachments = '';
    for($k = 0; $k < $num_reports; $k++)
    {
        $attachments .= $value[$k].' ';
    }
    
    $update_doc = $con->prepare("UPDATE doctorslinkdoctors SET attachments = ? WHERE IdMED = ? AND IdPac = ? AND IdMED2 = ?");
    $update_doc->bindValue(1, $attachments, PDO::PARAM_STR);
    $update_doc->bindValue(2, $doctor, PDO::PARAM_INT);
    $update_doc->bindValue(3, $patient, PDO::PARAM_INT);
    $update_doc->bindValue(4, $key, PDO::PARAM_INT);
    $update_doc->execute();
}

$files = '';
for($i = 0; $i < $num_patient_files; $i++)
{
    $files .= $patient_files[$i];
    if($i < $num_patient_files - 1)
        $files .= ',';
}

$update_pat = $con->prepare("UPDATE lifepin SET hide_from_member = 0 WHERE IdPin IN (".$files.")");
$update_pat->execute();

echo json_encode($doctor_files);
echo '<br/><br/>';
echo json_encode($patient_files);

?>