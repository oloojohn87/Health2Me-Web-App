<?php

require "environment_detail.php";
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
require_once('push/push.php');
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

//$app_key = 'd869a07d8f17a76448ed';
//$app_secret = '92f67fb5b104260bbc02';
//$app_id = '51379';
//$pusher = new Pusher($app_key, $app_secret, $app_id);
$push = new Push();

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}	

$doc_id = $_POST['doc_id'];
$pat_id = $_POST['pat_id'];



$result = $con->prepare("SELECT Name,Surname FROM doctors WHERE id = ?");
$result->bindValue(1, $doc_id, PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
$doc_name = $row['Name'];
$doc_surname = $row['Surname'];

$result = $con->prepare("SELECT Name,Surname FROM usuarios WHERE Identif = ?");
$result->bindValue(1, $pat_id, PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
$pat_name = $row['Name'];
$pat_surname = $row['Surname'];


$result = $con->prepare("SELECT * FROM consults WHERE Doctor = ? AND Patient = ? ORDER BY DateTime DESC");
$result->bindValue(1, $doc_id, PDO::PARAM_INT);
$result->bindValue(2, $pat_id, PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);

$recent_id = $row['consultationId'];
	

$consultation_start_time = explode(" ", $row['DateTime']);
$startTime = strtotime($consultation_start_time[1]);//strtotime($result['startTime']);
$result = $con->prepare("SELECT CURTIME() as time");
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
$endTime = strtotime($row['time']);//strtotime($result['endTime']);
$timeDiff = $endTime - $startTime;
//$lengthOfCall = gmdate("H:i:s",$timeDiff);

//updating the consults table
echo "time diff: ".$timeDiff;
echo "consultation id: ".$recent_id;
$result = $con->prepare("UPDATE consults SET Status = 'Completed' where consultationId = ?");
$result->bindValue(1, $recent_id, PDO::PARAM_INT);
$result->execute();

//finding whether any new report created by the doctor
$result = $con->prepare("SELECT count(*) AS count FROM lifepin WHERE idcreator = ?");
$result->bindValue(1, $doc_id, PDO::PARAM_INT);
$result->execute();
$lifepin_count_row = $result->fetch(PDO::FETCH_ASSOC);
$countOfReportsInLifePin = $lifepin_count_row['count'];
$result = $con->prepare("SELECT reportsCreated FROM doctors_calls WHERE id = ?");
$result->bindValue(1, $doc_id, PDO::PARAM_INT);
$result->execute();
$reports_count_row = $result->fetch(PDO::FETCH_ASSOC);
$currentCountOfReports = $reports_count_row['reportsCreated'];
$diff = $countOfReportsInLifePin - $currentCountOfReports;
if($diff > 0)
{
  $currentCountOfReports = $countOfReportsInLifePin; 
}

//Updating the doctors_calls table
$result = $con->prepare("SELECT * FROM doctors_calls WHERE id = ?");
$result->bindValue(1, $doc_id, PDO::PARAM_INT);
$result->execute();
$reports_count_row = $result->rowCount();
if($reports_count_row > 0)
{
    $result = $con->prepare("UPDATE doctors_calls SET reportsCreated  = ? ,numberOfConsultations = numberOfConsultations + 1 where id = ?");
    $result->bindValue(1, $currentCountOfReports, PDO::PARAM_INT);
    $result->bindValue(2, $doc_id, PDO::PARAM_INT);
    $result->execute();
    
}
else
{
    $numberOfPatientsConsulted = 1;
    $numberOfConsultations = 1;
    $result = $con->prepare("INSERT INTO doctors_calls (id,name,surname,reportsCreated,numberOfConsultedPatients,numberOfConsultations,videoconferences) values(?,?,?,?,?,?, videoconferences + 1)");
    $result->bindValue(1, $doc_id, PDO::PARAM_INT);
    $result->bindValue(2, $doc_name, PDO::PARAM_STR);
    $result->bindValue(3, $doc_surname, PDO::PARAM_STR);
    $result->bindValue(4, $countOfReportsInLifePin, PDO::PARAM_INT);
    $result->bindValue(5, $numberOfPatientsConsulted, PDO::PARAM_INT);
    $result->bindValue(6, $numberOfConsultations, PDO::PARAM_INT);
    $result->execute();
}

//updating the usuarios table
$result = $con->prepare("UPDATE usuarios SET numberOfPhoneCalls = numberOfPhoneCalls + 1");
$result->execute();

$result = $con->prepare("SELECT Name,Surname FROM doctors WHERE id = ?");
$result->bindValue(1, $doc_id, PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
$doc_name = $row['Name'].' '.$row['Surname'];

$result = $con->prepare("SELECT Name,Surname FROM usuarios WHERE Identif = ?");
$result->bindValue(1, $pat_id, PDO::PARAM_INT);
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
$pat_name = $row['Name'].' '.$row['Surname'];


$push->send('Telemedicine', 'ameridoc', 'Consultation Between Doctor '.$doc_name.' '.$doc_surname.' and patient '.$pat_name.' '.$pat_surname.' ended.');

$result = $con->prepare("UPDATE doctors SET in_consultation=0,telemed_type=0 WHERE id = ?");
$result->bindValue(1, $doc_id, PDO::PARAM_INT);
$result->execute();

$result2 = $con->prepare("SELECT * FROM consults WHERE Doctor = ? && Status = 'In Progress'");
$result2->bindValue(1, $doc_id, PDO::PARAM_INT);
$result2->execute();

while($row = $result2->fetch(PDO::FETCH_ASSOC)){
$now = new DateTime();
$post = new DateTime($row['DateTime']);
$interval = $now->diff($post);

if($interval->h > 1){
$result3 = $con->prepare("UPDATE consults SET Status='Failed' WHERE consultationId='".$row['consultationId']."'");
$result3->execute();
}
}

$result2 = $con->prepare("SELECT * FROM consults WHERE Status = 'In Progress'");
$result2->execute();

while($row = $result2->fetch(PDO::FETCH_ASSOC)){
$now = new DateTime();
$post = new DateTime($row['DateTime']);
$interval = $now->diff($post);

if($interval->h > 24){
$result3 = $con->prepare("UPDATE consults SET Status='Failed' WHERE consultationId='".$row['consultationId']."'");
$result3->execute();
}
}

?>