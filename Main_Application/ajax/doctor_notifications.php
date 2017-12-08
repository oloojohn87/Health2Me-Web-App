<?php
session_start();
require("environment_detailForLogin.php");
require ("push_server.php");

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

$query = $con->prepare("select distinct(userid) as userid from events where date(gmt_start) = date(now())");

$result = $query->execute();
$num_rows = $query->rowCount();
echo $num_rows.' events today';
while($row = $query->fetch(PDO::FETCH_ASSOC))
{


	//Get minutes of user
	$min_q = $con->prepare("select minutes from notification_config where userid = ?");
	$min_q->bindValue(1, $row['userid'], PDO::PARAM_INT);
	$min_query = $min_q->execute();
	
	$min_qq = $min_q->fetch(PDO::FETCH_ASSOC);
	$minutes = $min_qq['minutes'];
	
	
	$q = $con->prepare("select * from events where date(gmt_start) = date(now()) and doctor_notify is null and timestampdiff(MINUTE,now(),gmt_start) = ?");
	$q->bindValue(1, $minutes, PDO::PARAM_INT);
	$res = $q->execute();
	
	/*$qqq = "select title,timestampdiff(MINUTE,now(),start) as offset from events where date(start) = date(now()) having min(timestampdiff(MINUTE,now(),start))";
	$rrr = mysql_query($qqq);
	$rowrrr = mysql_fetch_array($rrr);
	$nr = mysql_num_rows($rrr);
	if($nr)
	{
		echo '<br>Next event is '.$rowrrr['offset'].' minutes away';
	}
	else
	{
		echo '<br>No more events left to be reminded today';
	}
	*/
	
	while($row1 = $q->fetch(PDO::FETCH_ASSOC))
	{
		$q1 = $con->prepare("select idusfixedname from usuarios where Identif = ?");
		$q1->bindValue(1, $row1['patient'], PDO::PARAM_INT);
		
		$res1 = $q1->execute();
		$row2 = $q1->fetch(PDO::FETCH_ASSOC);
		$patient_name = $row2['idusfixedname'];
		
		$message = "This is a reminder that you have a ". $row1['title'] ." with ".$patient_name;
		$message = $message . " in ".$minutes." minutes";
	
		//send $message to $row1['userid'];
		Push_notification($row1['userid'],$message);
		//Push_Email($row1['userid'],$message);
		Push_Reminder_Email($row1['userid'],$message);
		$update_time = $con->prepare("update events set doctor_notify=now() where id=?");
		$update_time->bindValue(1, $row1['id'], PDO::PARAM_INT);
		$update_time->execute();
		
		echo '<br>Reminded '.$row1['userid'].' of '.$row1['title'].' with '.$patient_name;
	}
}





?>