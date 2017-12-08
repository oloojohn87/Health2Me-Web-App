<?php
 session_start();
 require("environment_detail.php");
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
require_once('push/push.php');
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];



$link = mysqli_connect("$dbhost", "$dbuser", "$dbpass","$dbname");

 if (!$link) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
 }
 

$docID = $_SESSION['MEDID'];
 
if(!isset($_GET['copy']))
{
    $startDate = $_GET['startDate'];
    $startTime =  $_GET['startTime'];
    $endTime =  $_GET['endTime'];
    $weekday = $_GET['weekday'];
    $timezone = $_GET['timezone'];
    
    if($endTime == '00:00:00')
    {
        $endTime = '24:00:00';
    }
    
    $query = "INSERT INTO timeslots(week,start_time,end_time,week_day,timezone,doc_id) values(?,?,?,?,?,?)";
    $insertstmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($insertstmt,"sssdsd",$startDate,$startTime,$endTime,$weekday,$timezone,$docID);
    mysqli_stmt_execute($insertstmt);
    echo mysqli_stmt_insert_id ( $insertstmt );
    
    //$app_key = 'd869a07d8f17a76448ed';
    //$app_secret = '92f67fb5b104260bbc02';
    //$app_id = '51379';
    //$pusher = new Pusher($app_key, $app_secret, $app_id);
    $push = new Push();
    $pusher->trigger($docID, 'doctorAppointmentScheduler', 'timeslot_created');
}
else
{
    $con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    if (!$con)
      {
      die('Could not connect: ' . mysql_error());
      }	
  
    $copyWeek = $_GET['copy'];
    $pasteWeek = $_GET['paste'];
    
    $res = $con->prepare("SELECT * FROM timeslots WHERE doc_id=? AND week=?");
	$res->bindValue(1, $docID, PDO::PARAM_INT);
	$res->bindValue(2, $copyWeek, PDO::PARAM_STR);
	$res->execute();
	
    while($row = $res->fetch(PDO::FETCH_ASSOC))
    {
        $result = $con->prepare("INSERT INTO timeslots SET start_time=?, end_time=?, week_day=?, timezone=?, doc_id=?, week=?");
		$result->bindValue(1, $row['start_time'], PDO::PARAM_STR);
		$result->bindValue(2, $row['end_time'], PDO::PARAM_STR);
		$result->bindValue(3, $row['week_day'], PDO::PARAM_INT);
		$result->bindValue(4, $row['timezone'], PDO::PARAM_STR);
		$result->bindValue(5, $row['doc_id'], PDO::PARAM_INT);
		$result->bindValue(6, $pasteWeek, PDO::PARAM_STR);
		$result->execute();
		
       
    }
}


?>