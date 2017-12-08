<?php

session_start();
 require("environment_detail.php");
 require("push_server.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 
 $probeID = $_GET['probeID'];
 $feedback = $_GET['feedback'];
 $datetime = $_GET['datetime'];
 $feedbackToken = $_GET['feedbackToken'];
 
 // Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		


//$query = "insert INTO proberesponse values($probeID,$feedback,'$datetime')";
//mysql_query($query);

$query = $con->prepare("select id as maxid FROM sentprobelog where probeID=? and method=1 and sid=?");
$query->bindValue(1, $probeID, PDO::PARAM_INT);
$query->bindValue(2, $feedbackToken, PDO::PARAM_STR);

$result = $query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);
$targetid=$row['maxid'];
$query = $con->prepare("UPDATE sentprobelog set result=?,requestTime=? where id=?");
$query->bindValue(1, $feedback, PDO::PARAM_INT);
$query->bindValue(2, $datetime, PDO::PARAM_STR);
$query->bindValue(3, $targetid, PDO::PARAM_INT);
$query->execute();

$string = getDoctorIDPatientName($probeID);
				list($doctorID, $patientName, ) = split('::', $string);
				
				
				$feeling = "";
				switch($feedback)
				{
					case 1:$feeling = "Very Bad";
							break;
					case 2:$feeling = "Bad";
							break;
					case 3:$feeling = "Normal";
							break;
					case 4:$feeling = "Good";
							break;
					case 5:$feeling = "Very Good";
							break;
					
				
				}
				
				Push_notification($doctorID,$patientName." is feeling ".$feeling);

				
	function getDoctorIDPatientName($probeID)
	{
		require("environment_detail.php");
				
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
		
		$query = $con->prepare("select p.doctorID,concat(u.Name,' ',u.Surname) as fullname from probe p,usuarios u where p.patientID = u.identif and p.probeID=?");
		$query->bindValue(1, $probeID, PDO::PARAM_INT);
		
		
		$result = $query->execute();
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$string=$row['doctorID']."::".$row['fullname'];
		return $string;
		
		
	
	}
	
?>