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


$cadena = '';
// First create an array with all Patient Ids that have activity (report created, messages, stages)
// This is "MyPatients" array: A) Patients created by me, plus B) Patients referred to me, plus C) Patients created by other members of my group
$result = mysql_query("SELECT IdGroup FROM doctorsgroups WHERE IdDoctor = '$queUsu'");
$row = mysql_fetch_array($result);
$MyGroup = $row['IdGroup'];
$query = "SELECT * FROM usuarios WHERE (IdCreator = '$queUsu' or Identif IN (SELECT IdPac FROM doctorslinkdoctors WHERE IdMED2 = '$queUsu')) OR (IdCreator IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = '$MyGroup')) ORDER BY Surname ASC";

$result = mysql_query($query);
$count=mysql_num_rows($result);
$counter1 = 0 ;
while ($row2 = mysql_fetch_array($result)) {
	$UsIds[$counter1] = $row2['Identif'];
	$UsName[$counter1] = (htmlspecialchars($row2['Name']));
	$UsSurname[$counter1] = (htmlspecialchars($row2['Surname']));
	$UsEmail[$counter1] = (htmlspecialchars($row2['email']));
	$counter1++;
}
$numberpatients = $counter1;
$n=0;

$resultREPS = mysql_query("SELECT Content,DateTimeSTAMP,VIEWIdMed,VIEWIdUser FROM bpinview WHERE VIEWIdUser IN (" . implode(',', $UsIds) . ") AND DATEDIFF(NOW(), DateTimeSTAMP) <= '$daysExplore' ORDER BY DateTimeSTAMP DESC");
$count=mysql_num_rows($resultREPS);
echo 'Patients:  '.$numberpatients.' Fit: '.$count;
while ($rowREPS = mysql_fetch_array($resultREPS)) {
  echo $rowREPS['Content'];
  echo '  --   ';
  echo $rowREPS['DateTimeSTAMP'];
  echo '   --  ';
  echo $rowREPS['VIEWIdMed'];
  echo '   --  ';
  echo $rowREPS['VIEWIdUser'];
  echo '     ';
  echo ' ***    ***    ***   ***   ***   ***   ***  ***   ***   ***   ';
};
die;

while ($n < $numberpatients)
{
	$IdPatient = $UsIds[$n];

	// Check if any of this patients has new reports
	$resultREPS = mysql_query("SELECT Content,DateTimeSTAMP,VIEWIdMed FROM bpinview WHERE VIEWIdUser = '$IdPatient' AND DATEDIFF(NOW(), DateTimeSTAMP) <= '$daysExplore' ORDER BY DateTimeSTAMP DESC");
	$UpReports=0;
	$UpReportsOTHER=0;
	$NewestReport='';
	$NewestReportOTHER='';
	while ($rowREPS = mysql_fetch_array($resultREPS)) {
		// Check NEW reports
		if ($rowREPS['Content'] == 'Report Uploaded' || $rowREPS['Content'] == 'Report Uploaded (EMR)') 
		{
			$UpReports++;
			if ($rowREPS['VIEWIdMed'] !=  $queUsu) {$UpReportsOTHER++; $NewestReportOTHER = $rowREPS['DateTimeSTAMP'];} else{$NewestReport = $rowREPS['DateTimeSTAMP'];}
		}
	}

	// Check if any of this patients has new MESSAGES from DOCTORS

	$resultMES = mysql_query("SELECT sender_id,receiver_id,fecha,attachments,status FROM message_infrasture WHERE patient_id = '$IdPatient' AND DATEDIFF(NOW(), fecha) <= '$daysExplore' ORDER BY fecha DESC");
	$MessagesReceived=0;
	$MessagesReceivedNEW=0;
	$NewestdateMessage=0;
	while ($rowMES = mysql_fetch_array($resultMES)) {
		if ($rowMES['status'] == 'new') { $MessagesReceivedNEW++; $NewestdateMessage = $rowMES['fecha'];}
		$MessagesReceived++;
	}


	// ADD DATA TO THE JSON ARRAY
	if ($n>0) $cadena.=',';    
	$cadena.='
    {
        "id":"'.$IdPatient.'",
        "name":"'.$UsName[$n].'",
        "surname":"'.$UsSurname[$n].'",
        "email":"'.$UsEmail[$n].'",
        "UpReports":"'.$UpReports.'",
        "NewestReport":"'.SanDate($NewestReport).'",
        "UpReportsOTHER":"'.$UpReportsOTHER.'",
        "NewestReportOTHER":"'.SanDate($NewestReportOTHER).'",
        "MessagesReceived":"'.$MessagesReceived.'",
        "MessagesReceivedNEW":"'.$MessagesReceivedNEW.'",
        "NewestdateMessage":"'.SanDate($NewestdateMessage).'"
        }';    


	$n++;
}


$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 

function SanDate($entrydate)
{
	$newDate = date("m/d/Y H:i:s", strtotime($entrydate));
	return $newDate;
}

?>