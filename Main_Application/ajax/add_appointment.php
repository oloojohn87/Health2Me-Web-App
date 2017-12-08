<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("environment_detail.php");
 require("NotificationClass.php");
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
require_once('push/push.php');
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="messages"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$notifications = new Notifications();

$medid = $_POST["medid"];
$patid = $_POST["patid"];
$date = $_POST["date"];
$timezone = "";
if(isset($_POST['timezone']))
    $timezone = $_POST['timezone'];
$video = $_POST['video'];
$type = 'doc';
if(isset($_POST['type']))
{
    $type = $_POST['type'];
}

$start_time = "";
$end_time = "";
$specific_time = "";
$specific_time_str = "";

if(isset($_POST['start_time']))
    $start_time = $_POST['start_time'];
if(isset($_POST['end_time']))
    $end_time = $_POST['end_time'];
if(isset($_POST['time']))
{
    $specific_time = $_POST['time'];
    $specific_time_str = ", specific_time = ?";
    
    $time = explode(":", $specific_time);
    if($time[0] % 2 == 0)
    {
        $start_time = "";
        $end_time = "";
        if($time[0] < 10)
            $start_time = "0";
        $start_time .= $time[0].":00:00";
        
        if($time[0] < 8)
            $end_time = "0";
        $end_time .= strval($time[0] + 2).":00:00";
        
    }
   else 
    {
        $start_time = "";
        $end_time = "";
        if($time[0] < 11)
            $start_time = "0";
        $start_time .= strval($time[0] - 1).":00:00";
        
        if($time[0] < 9)
            $end_time = "0";
        $end_time .= strval($time[0] + 1).":00:00";
        
    }
}
if(!isset($_POST['timezone']))
{
    $timezone_req = $con->prepare("SELECT timezone FROM doctors WHERE id = ?");
    $timezone_req->bindValue(1, $medid, PDO::PARAM_INT);
    $timezone_req->execute();
    $timezone_row = $timezone_req->fetch(PDO::FETCH_ASSOC);
    if($timezone_req->rowCount() > 0 && isset($timezone_row['timezone']) && $timezone_row['timezone'] != NULL)
        $timezone = $timezone_row['timezone'];
    else
        $timezone = "-05:00:00";
}

$query = $con->prepare("INSERT INTO appointments SET med_id = ?, pat_id = ?, date = ?, date_created = NOW(), start_time = ?, end_time = ?, timezone = ?, video = ?, doctor_ack = ?".$specific_time_str);
$query->bindValue(1, $medid, PDO::PARAM_INT);
$query->bindValue(2, $patid, PDO::PARAM_INT);
$query->bindValue(3, $date, PDO::PARAM_STR);
$query->bindValue(4, $start_time, PDO::PARAM_STR);
$query->bindValue(5, $end_time, PDO::PARAM_STR);
$query->bindValue(6, $timezone, PDO::PARAM_STR);
$query->bindValue(7, $video, PDO::PARAM_INT);
//echo $type;
if($type == 'doc')
{
    $query->bindValue(8, 1, PDO::PARAM_INT);
}
else
{
    $query->bindValue(8, 0, PDO::PARAM_INT);
}
if(strlen($specific_time_str) > 0)
{
    $query->bindValue(9, $specific_time, PDO::PARAM_STR);
}
$result = $query->execute();

$app_id = $con->lastInsertId();

// if the doctor is creating the appointment, then we need to notify the patient by adding an entry in the notification_phs table
if($type == 'doc')
{
    
    $notifications->add('NEWAPP', $medid, true, $patid, false, null);
}
else
{
    $notifications->add('NEWAPP', $patid, false, $medid, true, null);
}

if($result)
{
    echo $con->lastInsertId(); 
    //$app_key = 'd869a07d8f17a76448ed';
    //$app_secret = '92f67fb5b104260bbc02';
    //$app_id = '51379';
    //$pusher = new Pusher($app_key, $app_secret, $app_id);
    $push = new Push();
    $push->send($medid, 'doctorAppointmentScheduler', 'appointment_created');
}
else
{
    echo '-1';
}
    

?>


