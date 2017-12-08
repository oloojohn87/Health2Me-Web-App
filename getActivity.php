<?php
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


$tbl_name="doctors"; // Table name

//$link = mysql_connect("$dbhost", "$dbuser", "$dbpass") or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

$Days = $_GET['Days'];


$result2 = mysql_query("SELECT DISTINCT userid FROM session_log WHERE DATEDIFF(NOW(), fecha) <=  '$Days' AND usertype IS NULL ORDER BY fecha DESC");   
$counter1 = 0 ;
while ($row2 = mysql_fetch_array($result2)) {
	$MedIds[$counter1] = $row2['userid'];
	$counter1++;
}

$cadena='';
$counter2 = 0 ;
while ($counter2 < $counter1)
{

	$IdMed = $MedIds[$counter2];
	// Check time of last login
	$resultLL = mysql_query("SELECT DISTINCT fecha FROM session_log WHERE DATEDIFF(NOW(), fecha) <=  '$Days' AND usertype IS NULL AND userid = '$IdMed' ORDER BY fecha DESC");   
	$rowLL = mysql_fetch_array($resultLL); 
	$LastLogin = $rowLL['fecha'];

	$result2 = mysql_query("SELECT * FROM doctors WHERE id = '$IdMed'");   
	$row2 = mysql_fetch_array($result2); 
	$Name = $row2['Name'];
	$Surname = $row2['Surname'];
	$Email = $row2['IdMEDEmail'];

	// Get Session Time
	$sescount = 0;
	$resultST = mysql_query("SELECT * FROM session_log WHERE DATEDIFF(NOW(), fecha) <=  '$Days' AND usertype IS NULL AND userid = '$IdMed' ORDER BY fecha ASC");   
	$rowST = mysql_fetch_array($resultST); 
	$SessionTime = 0;
	$CompletedSessions = 0;
	while ($rowST= mysql_fetch_array($resultST))
	{
		$ArraySessions[$sescount] = $rowST['action'];
		$ArraySessionsTime[$sescount] = $rowST['fecha'];
		$ArraySessionsId[$sescount] = $rowST['id'];
		$sescount++;
	}	
	$cn=0;
	while ($cn<$sescount)
	{
		if ($ArraySessions[$cn] == 'Login' && $cn<($sescount-1))
		{
			if ($ArraySessions[$cn+1] == 'Abrupt Browser Close' || $ArraySessions[$cn+1] == 'Timed Out' || $ArraySessions[$cn+1] == 'Logout')
			{
				$THISSessionTime = 0;
				$THISSessionTime = (strtotime($ArraySessionsTime[$cn+1]) - strtotime($ArraySessionsTime[$cn]));	
				$THISSessionTime = $THISSessionTime/60; // This is number of minutes in this session
				//echo 'This session time = '.$THISSessionTime.'     ';
				$SessionTime = $SessionTime + $THISSessionTime;
				$CompletedSessions++;
			}
		}
		$cn++;
	}
	// (Get Session Time)
	
	// Get browsing activity
	$resultBA = mysql_query("SELECT * FROM bpinview USE INDEX(I1) WHERE DATEDIFF(NOW(), DateTimeSTAMP) <=  '$Days' AND VIEWIdMED = '$IdMed' ORDER BY DateTimeSTAMP DESC");   
	$RepViewed = 0;
	$RepVerified = 0;
	$RepUploaded = 0;
	while ($rowBA = mysql_fetch_array($resultBA))
	{
		$contentRow = $rowBA['Content'] ;
		switch ($contentRow)
		{
			case 'Report Viewed':			$RepViewed++;
											break;
			case 'Report Verified':			$RepVerified++;
											break;
			case 'Report Uploaded':			$RepUploaded++;
											break;
			case 'Report Uploaded (EMR)':	$RepUploaded++;
											break;
		}
	}
	// (Get browsing activity)

	// Get Referring activity
	$resultRA = mysql_query("SELECT * FROM doctorslinkdoctors WHERE DATEDIFF(NOW(), Fecha) <=  '$Days' AND IdMED = '$IdMed' ORDER BY Fecha DESC");   
	$count=0;
	$count=mysql_num_rows($resultRA);
	$ReferralsSent = $count;

	$resultRA = mysql_query("SELECT * FROM doctorslinkdoctors WHERE DATEDIFF(NOW(), Fecha) <=  '$Days' AND FechaConfirm IS NOT NULL AND IdMED = '$IdMed' ORDER BY Fecha DESC");   
	$count=0;
	$count=mysql_num_rows($resultRA);
	$ReferralsAck = $count;

	$resultRA = mysql_query("SELECT * FROM doctorslinkdoctors WHERE DATEDIFF(NOW(), Fecha) <=  '$Days' AND IdMED2 = '$IdMed' ORDER BY Fecha DESC");   
	$count=0;
	$count=mysql_num_rows($resultRA);
	$ReferralsRec = $count;

	$resultRA = mysql_query("SELECT * FROM doctorslinkdoctors WHERE DATEDIFF(NOW(), Fecha) <=  '$Days' AND FechaConfirm IS NOT NULL AND IdMED2 = '$IdMed' ORDER BY Fecha DESC");   
	$count=0;
	$count=mysql_num_rows($resultRA);
	$ReferralsCon = $count;
	// (Get Referring activity)
	
	// Get Messaging activity
	$resultRA = mysql_query("SELECT * FROM message_infrasture WHERE DATEDIFF(NOW(), fecha) <=  '$Days' AND sender_id = '$IdMed' ORDER BY fecha DESC");   
	$count=0;
	$count=mysql_num_rows($resultRA);
	$MessagesSent = $count;

	$resultRA = mysql_query("SELECT * FROM message_infrasture WHERE DATEDIFF(NOW(), fecha) <=  '$Days' AND receiver_id = '$IdMed' ORDER BY fecha DESC");   
	$count=0;
	$count=mysql_num_rows($resultRA);
	$MessagesRec = $count;
	// (Get Messaging activity)


//        "Email":"'.$Email.'",
//stripslashes($str)	
//htmlspecialchars(mysql_real_escape_string($Email))

if ($counter2>0) $cadena.=',';    
$cadena.='
    {
        "IdMed":"'.$IdMed.'",
        "NameMed":"'.Sanitize(strtoupper($Name)).'",
        "SurnameMed":"'.Sanitize(strtoupper($Surname)).'",
        "Email":"'.Sanitize($Email).'",
        "LastLogin":"'.$LastLogin.'",
        "UserViews":"'.$RepViewed.'",
        "UserVerified":"'.$RepVerified.'",
        "UserUploaded":"'.$RepUploaded.'",
        "ReferralsSent":"'.$ReferralsSent.'",
        "ReferralsAck":"'.$ReferralsAck.'",
        "ReferralsRec":"'.$ReferralsRec.'",
        "ReferralsCon":"'.$ReferralsCon.'",
        "MessagesSent":"'.$MessagesSent.'",
        "MessagesRec":"'.$MessagesRec.'",
        "SessionTime":"'.intval($SessionTime).'",
        "CompletedSessions":"'.$CompletedSessions.'"
        }';    

	$counter2++;

}




    
$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 


function makeSafe($array) {

    $arrayLength = count($array); //count how many values in the array

    for ($i = 0; $i < $arrayLength; $i++) { // start looping through

        $keyName[$i] = key($array); // get key name
        $name = $keyName[$i];
        $value[$i] = trim($array[$name]); // trim value
        $value[$i] = strip_tags($value[$i]); // strip value
        $value[$i] = mysql_real_escape_string($value[$i]); // sql escape value

        $safeArray[$name] = $value[$i]; // build safe array, key names+values
    }

    return array($safeArray); // return array
}

function Sanitize($MyString)
{
	return preg_replace('/[^a-zA-Z0-9_]/', '@', $MyString);

}

?>