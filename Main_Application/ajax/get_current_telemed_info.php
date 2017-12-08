<?php

require "environment_detail.php";
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

$id = $_POST['id'];

$result = $con->prepare('SELECT consultation_pat,cons_req_time FROM doctors WHERE id = ?');
$result->bindValue(1, $id, PDO::PARAM_INT);
$result->execute();
$doc_row = $result->fetch(PDO::FETCH_ASSOC);

$result = $con->prepare('SELECT Name,Surname FROM usuarios WHERE Identif = ?');
$result->bindValue(1, $doc_row['consultation_pat'], PDO::PARAM_INT);
$result->execute();
$pat_row = $result->fetch(PDO::FETCH_ASSOC);

$result = $con->prepare('SELECT description,Patient FROM consults WHERE Doctor = ? && Status="In Progress" ORDER BY consultationId DESC LIMIT 1');
$result->bindValue(1, $id, PDO::PARAM_INT);
$result->execute();
$consult_row = $result->fetch(PDO::FETCH_ASSOC);



$cons_date = new DateTime();
$cons_date->setTimestamp(intval($doc_row['cons_req_time']));
$consultation_date = $cons_date->format('F j, Y g:i A e');

echo json_encode(array("Name" => $pat_row['Name'], "Surname" => $pat_row['Surname'], "Time" => $consultation_date, "ID" => $consult_row['Patient'], "Description"=>$consult_row['description']));



?>
