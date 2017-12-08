<?php
    header('Content-type: text/xml');
    echo '<?xml version="1.0" encoding="UTF-8"?>';
 
    echo '<Response>';
    $requestID = $_GET['IdRef'];

	
 

    # @start snippet
    $user_pushed = (int) $_REQUEST['Digits'];
    # @end snippet
 
	$flag=0;
    switch ($user_pushed){
	    
		case 1:	echo '<Say language="en" voice="man" >We have registered that you selected 1 (Very Bad).</Say>';
				recordAnswer($requestID,1);
				$flag=1;
				echo '<Say language="en" voice="woman">Thank you!. Your answer has been recorded.</Say>';
				echo '<Say language="en" voice="woman">Greetings from Health 2 Me.</Say>';
				echo '<Say language="en" voice="woman">GoodBye!</Say>';
	    		break;
		case 2:	echo '<Say language="en" voice="man" >We have registered that you selected 2 (Bad).</Say>';
	    		recordAnswer($requestID,2);
				$flag=1;
				echo '<Say language="en" voice="woman">Thank you!. Your answer has been recorded.</Say>';
				echo '<Say language="en" voice="woman">Greetings from Health 2 Me.</Say>';
				echo '<Say language="en" voice="woman">GoodBye!</Say>';
				break;
		case 3:	echo '<Say language="en" voice="man" >We have registered that you selected 3 (Normal).</Say>';
	    		recordAnswer($requestID,3);
				$flag=1;
				echo '<Say language="en" voice="woman">Thank you!. Your answer has been recorded.</Say>';
				echo '<Say language="en" voice="woman">Greetings from Health 2 Me.</Say>';
				echo '<Say language="en" voice="woman">GoodBye!</Say>';
				break;
		case 4:	echo '<Say language="en" voice="man" >We have registered that you selected 4 (Good).</Say>';
	    		recordAnswer($requestID,4);
				$flag=1;
				echo '<Say language="en" voice="woman">Thank you!. Your answer has been recorded.</Say>';
				echo '<Say language="en" voice="woman">Greetings from Health 2 Me.</Say>';
				echo '<Say language="en" voice="woman">GoodBye!</Say>';
				break;		
	    case 5:	echo '<Say language="en" voice="man" >We have registered that you selected 5 (Very Good).</Say>';
				recordAnswer($requestID,5);
				echo '<Say language="en" voice="woman">Thank you!. Your answer has been recorded.</Say>';
				echo '<Say language="en" voice="woman">Greetings from Health 2 Me.</Say>';
				echo '<Say language="en" voice="woman">GoodBye!</Say>';
				$flag=1;
	    		break;
				
		case 7: stopCalling($requestID);
				echo '<Say language="en" voice="man" >Thank You. You will no longer receive phone calls</Say>';
				echo '<Say language="en" voice="woman">GoodBye!</Say>';
				break;
				
		case 8: echo '<Redirect method="GET">phoneoptions.php?id='.$requestID.'</Redirect>';
		default:
				
	    		break;
				
		
    }
 
    echo '</Response>';
	
	
	
	function stopCalling($requestID)
	{
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

		$query = $con->prepare("update probe set patientPermission=0 where probeID=?");
		$query->bindValue(1, $requestID, PDO::PARAM_INT);
		$query->execute();
		
		
		$query = $con->prepare("select max(id) as maxid FROM sentprobelog where probeID=?");
		$query->bindValue(1, $requestID, PDO::PARAM_INT);
		
		
		$result = $query->execute();
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$targetid=$row['maxid'];
		$query = $con->prepare("UPDATE sentprobelog set result=15 where id=?");
		$query->bindValue(1, $targetid, PDO::PARAM_INT);
		$query->execute();
		
		
		$string = getDoctorIDPatientName($requestID);
		list($doctorID, $patientName, ) = split('::', $string);
		Push_notification($doctorID,$patientName." does not wish to receive any more probe requests.");
	
	
	}
	
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
	
	
	
	function recordAnswer($requestID,$response)
	{
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

				
				//Get Name for Timezone
				$query = $con->prepare("select name from timezones where id=(select timezone from probe where probeID=?)");
				$query->bindValue(1, $requestID, PDO::PARAM_INT);
				
				
				$result=$query->execute();
				$row = $query->fetch(PDO::FETCH_ASSOC);
				$timezone=$row['name'];

				$query = $con->prepare("select now() as currentDateTime");
				$result=$query->execute();
				$row = $query->fetch(PDO::FETCH_ASSOC);
				$date_str=$row['currentDateTime'];	
				
				//Convert current GMT to Patient Timezone bacause we are storing the patient response time in the database
				$PatientDateTime = convertFromGMT($timezone,$date_str);


			
				//$query = "insert INTO proberesponse values($requestID,$response,'$PatientDateTime')";
				//mysql_query($query);
				
				$query = $con->prepare("select max(id) as maxid FROM sentprobelog where probeID=?");
				$query->bindValue(1, $requestID, PDO::PARAM_INT);
				
				$result = $query->execute();
				$row = $query->fetch(PDO::FETCH_ASSOC);
				$targetid=$row['maxid'];
				$query = $con->prepare("UPDATE sentprobelog set result=? where id=?");
				$query->bindValue(1, $response, PDO::PARAM_INT);
				$query->bindValue(2, $targetid, PDO::PARAM_INT);
				$query->execute();
				
				
				$string = getDoctorIDPatientName($requestID);
				list($doctorID, $patientName, ) = split('::', $string);
				
				
				$feeling = "";
				switch($response)
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

	
	}
	/*
	function convertFromGMT($timezone,$date_str)
	{
		$default_tz = date_default_timezone_get();
		date_default_timezone_set('GMT'); // Set this to user's timezone (obtain user's timezone through js)

		$datetime = new DateTime($date_str);
		$cst = new DateTimeZone($timezone);
		$datetime->setTimezone($cst);

		date_default_timezone_set($default_tz);

		$GMTTime = $datetime->format('Y-m-d H:i:s');
		return $GMTTime;
	}
*/
	
?>