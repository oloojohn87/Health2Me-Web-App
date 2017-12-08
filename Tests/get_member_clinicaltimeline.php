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


$pat_id = $_POST['pat_id'];
$user_id = $_SESSION['BOTHID'];

$result = $con->prepare("SELECT pass FROM encryption_pass WHERE id = (SELECT MAX(id) FROM encryption_pass)");
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
$pass = $row['pass'];

$items = array();

// add entries to the $items array
//getMemberNotifications($items, $pat_id, $con);
//$items = [1,2,3,4];
getMemberEpisodes($items, $pat_id, $con);

// sort the results by date in descending form
krsort($items);

echo json_encode($items);



/*
 *
 *  FUNCTIONS
 *
 *  Example:
 *  
 *      $items['38'] = array("Type" => "3", ...);
 *
 */

function getMemberEpisodes(&$items, $pat_id, &$con)
{
    // get all probe responses from this patient to this doctor
    $episodes = array();
    $episodes_ = $con->prepare("SELECT * FROM p_diagnostics WHERE idpatient = ? AND deleted = 0 ORDER BY dxstart");
    $episodes_->bindValue(1, $pat_id, PDO::PARAM_INT);
    $episodes_->execute();
    $n = 0;
    while($row = $episodes_->fetch(PDO::FETCH_ASSOC))
    {
       $items[($n)] = array("id" => $row['id'], "doctor" => $row['doctor_signed'], "icd_code" => $row['dxcode'], "name" => $row['dxname'], "start" => $row['dxstart'],"end" => $row['dxend'],"area" => $row['area']);
       $n++;
    }

}

function getMemberNotifications(&$items, $pat_id, &$con)
{
    // get all probe responses from this patient to this doctor
    $notifications = array();
    $notif_ = $con->prepare("SELECT * FROM notification_phs WHERE id_member = ? AND dismissed_via IS NULL ORDER BY timestamp");
    $notif_->bindValue(1, $pat_id, PDO::PARAM_INT);
    $notif_->execute();
    $n = 0;
    while($row = $notif_->fetch(PDO::FETCH_ASSOC))
    {
       $items[($n)] = array("notification_id" => $row['id'], "Type" => $row['type'], "Reports" => $row['report_ids'], "Report_Type" => $row['report_type'], "Id_Doctor" => $row['id_doctor'], "Id_Doctorb" => $row['id_doctor2'], "Timestamp" => $row['timestamp'], "Clickable_Link" => $row['clickable_link'], "Priority" => $row['priority'], "Sent_Via" => $row['sent_via'], "Sent_Timestamp" => $row['sent_timestamp']);
       $n++;
    }

}


?>
