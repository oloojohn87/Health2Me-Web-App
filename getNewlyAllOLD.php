<?php
 /*KYLE
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

$queUsu = $_GET['Doctor'];
$NReports = $_GET['NReports'];
$daysExplore=empty($_GET['days']) ? 30: $_GET['days'];

// First create an array with all Patient Ids that have activity (report created, messages, stages)
// This is "MyPatients" array: A) Patients created by me, plus B) Patients referred to me, plus C) Patients created by other members of my group
$result = mysql_query("SELECT IdGroup FROM doctorsgroups WHERE IdDoctor = '$queUsu'");
$row = mysql_fetch_array($result);
$MyGroup = $row['IdGroup'];
$query = "SELECT * FROM usuarios WHERE (IdCreator = '$queUsu' or Identif IN (SELECT IdUs FROM doctorslinkusers WHERE IdMED = '$queUsu')) OR (IdCreator IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = '$MyGroup')) ORDER BY Surname ASC";
$result = mysql_query($query);
$count=mysql_num_rows($result);
$counter1 = 0 ;
while ($row2 = mysql_fetch_array($result)) {
	$UsIds[$counter1] = $row2['Identif'];
	$counter1++;
}
$numberpatients = $counter1;
$n=0;
while ($n < $numberpatients)
{
	$IdPatient = $UsIds[$n];
	// Check if any of this patients has new reports
	$resultREPS = mysql_query("SELECT Content,DateTimeSTAMP,VIEWIdMed FROM bpinview WHERE VIEWIdUser = '$IdPatient' AND DATEDIFF(NOW(), DateTimeSTAMP) <= '$daysExplore' ORDER BY DateTimeSTAMP DESC ");
	$UpReports=0;
	$UpReportsOTHER=0;
	while ($rowREPS = mysql_fetch_array($resultREPS)) {
		if ($rowREPS['Content'] == 'Report Uploaded' || $rowREPS['Content'] == 'Report Uploaded (EMR)') 
		{
			$UpReports++;
			if ($rowREPS['VIEWIdMed'] !=  $queUsu) $UpReportsOTHER++;
		}
	}

	$n++;
}



$result2 = mysql_query("SELECT DISTINCT IdUsu FROM lifepin WHERE IdCreator = '$queUsu' AND DATEDIFF(NOW(), FechaInput) <= 30 ORDER BY FechaInput DESC ");
$counter1 = 0 ;
while ($row2 = mysql_fetch_array($result2)) {
	$UsIds[$counter1] = $row2['IdUsu'];
	$counter1++;
}

$cadena='';
$counter2 = 0 ;
while ($counter2 < ($counter1 - 1)) {
	$UserId = $UsIds[$counter2];
	$result2 = mysql_query("SELECT * FROM usuarios WHERE Identif = '$UserId'"); 
	$row2 = mysql_fetch_array($result2);

	$NameP = $row2['Name'];
	$SurnameP = $row2['Surname'];
	$seekthis = $row2['Identif'];

	// FOR MY DATA: Find Newest report date and number of reports created in this period

	// Find New messages for this patient
	$resultNM = mysql_query("SELECT * FROM message_infrasture WHERE receiver_id = '$queUsu' AND patient_id = '$seekthis' AND DATEDIFF(NOW(), fecha) <= 7 ORDER BY fecha DESC ");
	$count=0;
	$NumberMessages = 0;
	$LatestDate = '';
	while ($rowNM = mysql_fetch_array($resultNM)) {
		$NumberMessages++;
		$LatestDate = $rowNM['fecha'];
	}
	// Find New messages for this patient

	// Find New Reports for this patient BY OTHER DOCTORS
	$resultNM = mysql_query("SELECT * FROM lifepin WHERE IdUsu= '$seekthis' AND IdCreator != '$queUsu' AND DATEDIFF(NOW(), FechaInput) <= 7 ORDER BY FechaInput DESC ");
	$count=0;
	$NumberReports = mysql_num_rows($resultNM);
	$NumberViewedReports = 0;
	$LatestDateR = '';
	while ($rowNM = mysql_fetch_array($resultNM)) {
		$WhatReport = $rowNM['IdPin'];
		$resultBP = mysql_query("SELECT Content FROM bpinview WHERE IDPIN = '$WhatReport' and VIEWIdMed = '$queUsu'");
		echo "SELECT Content FROM bpinview WHERE IDPIN = '$WhatReport' and VIEWIdMed = '$queUsu'";
		echo 'ID: '.$WhatReport.'   ';
		$rowBP = mysql_fetch_array($resultBP);
		if ($rowBP['Content'] == 'Report Viewed') {
				$NumberViewedReports++;
		}
	}
	// Find New messages for this patient


	if ($counter2>0) $cadena.=',';    
	$cadena.='
    {
        "id":"'.$seekthis.'",
        "name":"'.$NameP.'",
        "surname":"'.$SurnameP.'",
        "numbermessages":"'.$NumberMessages.'",
        "latestmessage":"'.$LatestDate.'",
        "numberreports":"'.$NumberReports.'",
        "numberviewedreports":"'.$NumberViewedReports.'",
        "latestreport":"'.$LatestDateR.'"
        }';    


	$counter2++;
}

$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 

























    

?>