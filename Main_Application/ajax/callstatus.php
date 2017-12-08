<?php
require "Services/Twilio.php";
		
/*
//https://www.twilio.com/docs/quickstart/php/rest/call-request
//https://www.twilio.com/help/faq/voice/can-twilio-tell-whether-a-call-was-answered-by-a-human-or-machine
//https://www.twilio.com/docs/api/rest/making-calls
*/
$sid = $_POST['CallSid'];
$status = $_POST['CallStatus'];
//$answeredBy = $_GET['AnsweredBy'];

require("environment_detail.php");
		require("push_server.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		



if($status=='busy')
{
	$query = $con->prepare("UPDATE sentprobelog set result=12 where sid=?");
	$query->bindValue(1, $sid, PDO::PARAM_STR);
	$query->execute();

}
else if($status=='failed')
{
	$query = $con->prepare("UPDATE sentprobelog set result=14 where sid=?");
	$query->bindValue(1, $sid, PDO::PARAM_STR);
	$query->execute();
}
else if($status=='completed')
{
	//do nothing because response may have been received or no response
}



?>

