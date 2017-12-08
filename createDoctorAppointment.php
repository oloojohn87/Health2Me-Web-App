<?php
 require("environment_detail.php");
require("NotificationClass.php");
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

$notifications = new Notifications();

$member = $_GET['member'];
$doctor = $_GET['doctor'];
$time_start = $_GET['timestart'];
$time_end = $_GET['timeend'];
$date = $_GET['date'];  
$type = $_GET['type'];

$timezone = $con->prepare("SELECT * FROM doctors where id=?");
$timezone->bindValue(1, $doctor, PDO::PARAM_INT);
$timezone->execute();

$row = $timezone->fetch(PDO::FETCH_ASSOC);
																								
$result = $con->prepare("INSERT INTO appointments SET med_id = ?, pat_id = ?, start_time = ?, end_time = ?, timezone = ?, video = ?, date = ?");
$result->bindValue(1, $doctor, PDO::PARAM_INT);
$result->bindValue(2, $member, PDO::PARAM_INT);
$result->bindValue(3, $time_start, PDO::PARAM_STR);
$result->bindValue(4, $time_end, PDO::PARAM_STR);
$result->bindValue(5, $row['timezone'], PDO::PARAM_STR);
$result->bindValue(6, $type, PDO::PARAM_INT);
$result->bindValue(7, $date, PDO::PARAM_STR);
$result->execute();

$notifications->add('NEWAPP', $doctor, true, $member, false, null);
?>