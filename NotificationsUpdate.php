<?php
echo "<br>Triggered";
require "environment_detail.php";
require "push_server.php";

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


$patient_message = 'This is a reminder about your appointment with ';

$query = $con->prepare("SELECT * FROM events WHERE date(start) = date(now())");
$query->execute();
$num_rows = $query->rowCount();

while ($row = $query->fetch(PDO::FETCH_ASSOC)) 
{
    $query = $con->prepare("SELECT name,surname FROM doctors WHERE id = ");
    $query->bindValue(1, $row['userid'], PDO::PARAM_INT);
    $query->execute();
    $r = $query->fetch(PDO::FETCH_ASSOC);
    
	$doctor_name = 'Dr.'.$r['name'].' '.$r['surname'];
	
	$patient_id = $row['patient'];
	$msg_to_send = $patient_message.$doctor_name;
	$msg_to_send = $msg_to_send . '<p> <b>Appointment Details : </b></p>';
	$msg_to_send = $msg_to_send . '<p> Type : '.$row['title'] .'</p>';
	$msg_to_send = $msg_to_send . '<p> Time : '.date('h:i a', strtotime($row['start'])).' - '. date('h:i a', strtotime($row['end'])) .'</p>';
	//echo $msg_to_send;
	
	Push_Patient_Email($patient_id,$msg_to_send);
	
	echo '<br>Message sent to '.$patient_id;
}



?>