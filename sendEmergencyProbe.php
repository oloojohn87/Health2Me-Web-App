<?php
 
 session_start();
 require("environment_detail.php");
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

$probeID = $_GET['probeID'];

//Get probe details
$query1 = $con->prepare("select * from probe where probeID=?");
$query1->bindValue(1, $probeID, PDO::PARAM_INT);
$result1=$query1->execute();

$row1 = $query1->fetch(PDO::FETCH_ASSOC);

//Get DoctorName
$doctorNameQuery = $con->prepare("select name,surname from doctors where id=?");
$doctorNameQuery->bindValue(1, $row1['doctorID'], PDO::PARAM_INT);
$res=$doctorNameQuery->execute();

$row2 = $doctorNameQuery->fetch(PDO::FETCH_ASSOC);
$dn = $row2['name'];
$ds = $row2['surname'];
$doctorname = $dn.' '.$ds;

//Get patient Email and Phone
$patientContactQuery = $con->prepare("select name,surname,email,telefono from usuarios where identif=?");
$patientContactQuery->bindValue(1, $row1['patientID'], PDO::PARAM_INT);

//echo $patientContactQuery;
$res = $patientContactQuery->execute();
$row2 = $patientContactQuery->fetch(PDO::FETCH_ASSOC);
$patientemail = $row2['email'];
$patientphone = $row2['telefono'];
$patientname = $row2['name'];
$patientsurname = $row2['surname'];


if($row1['emailRequest']==1)
{
	echo 'Email Sent at '.$patientemail;
	Push_Probe_Email($doctorname,$patientemail,$probeID,1);
	//logRequest($probeID,1);

}
else if($row1['phoneRequest']==1)
{
	echo 'Patient Called on '.$patientphone;
	Health_Feedback_Call($dn,$ds,$patientname,$patientsurname,$patientphone,$probeID,1);
	//logRequest($probeID,2);
			
}
else if($row1['smsRequest']==1)
{
	//Send SMS if allowed
	//echo 'Sent SMS to '.$patientname.' '.$patientsurname.' on '.$patientphone;
	echo 'SMS sent on '.$patientphone;
	Send_Feedback_SMS($ds,$patientname.' '.$patientsurname,$patientphone,$probeID,1);
	//logRequest($probeID,3);
	
}
	

	
function logRequest($probeID,$method)
{
	require("environment_detail.php");
 
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

	$query = $con->prepare("insert INTO sentprobelog(probeID,method,requestTime) values(?,?,now())");
	$query->bindValue(1, $probeID, PDO::PARAM_INT);
	$query->bindValue(2, $method, PDO::PARAM_INT);
	$query->execute();
	
}	

?>
