<?php
 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$queUsu = $_GET['Doctor'];
$NReports = $_GET['NReports'];
$daysExplore=empty($_GET['days']) ? 30: $_GET['days'];


$cadena = '';
// First create an array with all Patient Ids that have activity (report created, messages, stages)
// This is "MyPatients" array: A) Patients created by me, plus B) Patients referred to me, plus C) Patients created by other members of my group
$result = $con->prepare("SELECT IdGroup FROM doctorsgroups WHERE IdDoctor = ?");
$result->bindValue(1, $queUsu, PDO::PARAM_INT);
$result->execute();

$row = $result->fetch(PDO::FETCH_ASSOC);
$MyGroup = $row['IdGroup'];
$query = $con->prepare("SELECT * FROM usuarios WHERE (IdCreator = ? or Identif IN (SELECT IdPac FROM doctorslinkdoctors WHERE IdMED2 = ?)) OR (IdCreator IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = ?)) ORDER BY Surname ASC");
$query->bindValue(1, $queUsu, PDO::PARAM_INT);
$query->bindValue(2, $queUsu, PDO::PARAM_INT);
$query->bindValue(3, $MyGroup, PDO::PARAM_INT);
$result = $query->execute();

$count = $query->rowCount();
$counter1 = 0 ;
while ($row2 = $query->fetch(PDO::FETCH_ASSOC)) {
	$UsIds[$counter1] = $row2['Identif'];
	$UsName[$counter1] = $row2['Name'];
	$UsSurname[$counter1] = $row2['Surname'];
	$UsEmail[$counter1] = $row2['email'];
	$counter1++;
}
$numberpatients = $counter1;
$n=0;

//echo $numberpatients;
//echo $query; 
 
while ($n < $numberpatients)
{
	$IdPatient = $UsIds[$n];

	// Check if any of this patients has new reports
	$resultREPS = $con->prepare("SELECT Content,DateTimeSTAMP,VIEWIdMed FROM bpinview WHERE VIEWIdUser = ? AND DATEDIFF(NOW(), DateTimeSTAMP) <= ? ORDER BY DateTimeSTAMP DESC");
	$resultREPS->bindValue(1, $IdPatient, PDO::PARAM_INT);
	$resultREPS->bindValue(2, $daysExplore, PDO::PARAM_STR);
	$resultREPS->execute();
	
	$UpReports=0;
	$UpReportsOTHER=0;
	$NewestReport='';
	$NewestReportOTHER='';
	while ($rowREPS = $resultREPS->fetch(PDO::FETCH_ASSOC)) {
		// Check NEW reports
		if ($rowREPS['Content'] == 'Report Uploaded' || $rowREPS['Content'] == 'Report Uploaded (EMR)') 
		{
			$UpReports++;
			if ($rowREPS['VIEWIdMed'] !=  $queUsu) {$UpReportsOTHER++; $NewestReportOTHER = $rowREPS['DateTimeSTAMP'];} else{$NewestReport = $rowREPS['DateTimeSTAMP'];}
		}
	}

	// Check if any of this patients has new MESSAGES from DOCTORS

	$resultMES = $con->prepare("SELECT sender_id,receiver_id,fecha,attachments,status FROM message_infrasture WHERE patient_id = ? AND DATEDIFF(NOW(), fecha) <= ? ORDER BY fecha DESC");
	$resultMES->bindValue(1, $IdPatient, PDO::PARAM_INT);
	$resultMES->bindValue(2, $daysExplore, PDO::PARAM_STR);
	$resultMES->execute();
	
	$MessagesReceived=0;
	$MessagesReceivedNEW=0;
	$NewestdateMessage=0;
	while ($rowMES = $resultMES->fetch(PDO::FETCH_ASSOC)) {
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