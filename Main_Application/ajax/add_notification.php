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


$patient_id = $_POST['patient_id'];
$doctor_id = $_POST['doctor_id'];
$reports = $_POST['reports'];

$user_id = $_SESSION['BOTHID'];

$result = $con->prepare("SELECT pass FROM encryption_pass WHERE id = (SELECT MAX(id) FROM encryption_pass)");
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
$pass = $row['pass'];

$items = array();

// add entries to the $items array
addNotification($items, $patient_id, $doctor_id, $reports,  $con);

echo ($items);



/*
 *
 *  FUNCTIONS
 *
 *  Example:
 *  
 *      $items['38'] = array("Type" => "3", ...);
 *
 */

function addNotification(&$items, $patient_id, $doctor_id, $reports, &$con)
{
    // update dismission fields for this probe id
    $notifications = array();
    $notif_ = $con->prepare("INSERT INTO notification_phs SET timestamp = NOW(), type = 'send', report_ids = ?, id_member = ?, id_doctor = ?, priority = 2 ");
    $notif_->bindValue(1, $reports, PDO::PARAM_INT);
    $notif_->bindValue(2, $patient_id, PDO::PARAM_INT);
    $notif_->bindValue(3, $doctor_id, PDO::PARAM_INT);
    $count = $notif_->execute();
    
    // echo a message to say the UPDATE succeeded
    $items =  $notif_->rowCount() . " records ADDED successfully.  Rows affected:  ".$count;
  
}



?>
