<?php

session_start();
 require("push_server.php");	
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 
 $probeID = $_GET['probeID'];
 
 // Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		


$query = $con->prepare("update probe set patientPermission=0 where probeID=?");
$query->bindValue(1, $probeID, PDO::PARAM_INT);
$query->execute();

$query = $con->prepare("select max(id) as maxid FROM sentprobelog where probeID=? and method=1 and result=0");
$query->bindValue(1, $probeID, PDO::PARAM_INT);

$result = $query->execute();
$row=$query->fetch(PDO::FETCH_ASSOC);
$targetid=$row['maxid'];
$query = $con->prepare("UPDATE sentprobelog set result=5 where id=?");
$query->bindValue(1, $targetid, PDO::PARAM_INT);
$query->execute();

$string = getDoctorIDPatientName($requestID);
list($doctorID, $patientName, ) = split('::', $string);
Push_notification($doctorID,$patientName." does not wish to receive any more probe requests.");


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
		$row=$query->fetch(PDO::FETCH_ASSOC);
		$string=$row['doctorID']."::".$row['fullname'];
		return $string;
		
		
	
	}




	
?>