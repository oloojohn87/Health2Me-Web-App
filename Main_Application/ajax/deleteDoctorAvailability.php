<?php
 session_start(); 
 require("environment_detail.php");
 require("push_server.php");
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
require_once('push/push.php');
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$docID = $_SESSION['MEDID'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

if(!isset($_GET['clear']))
{
    $eventID = $_GET['eventID'];
    $query4 = $con->prepare("DELETE FROM timeslots WHERE doc_id=? and id=?");
	$query4->bindValue(1, $docID, PDO::PARAM_INT);
	$query4->bindValue(2, $eventID, PDO::PARAM_INT);
    $result4 = $query4->execute();
    
    //$app_key = 'd869a07d8f17a76448ed';
    //$app_secret = '92f67fb5b104260bbc02';
    //$app_id = '51379';
    //$pusher = new Pusher($app_key, $app_secret, $app_id);
    $push = new Push();
    $push->send($docID, 'doctorAppointmentScheduler', 'timeslot_deleted');
}
else
{
    $clear = $_GET['clear'];
    $query4 = $con->prepare("DELETE FROM timeslots WHERE doc_id=? AND week=?");
	$query4->bindValue(1, $docID, PDO::PARAM_INT);
	$query4->bindValue(2, $clear, PDO::PARAM_STR);
    $query4->execute();
}

    
    

?>


