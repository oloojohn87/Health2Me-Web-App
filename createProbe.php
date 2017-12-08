<?php

session_start();
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


$DoctorID = $_SESSION['MEDID'];
$PatientID = $_GET['idusu'];
$ProbeTime = $_GET['time'];        
$PatientTimezone = $_GET['timezone'];
$ProbeInterval   = $_GET['interval'];
$DoctorPermission  = 1;
$PatientPermission = 1;
$EmailRequest      = $_GET['email'];
$PhoneRequest	  = $_GET['phone'];
$MessageRequest = $_GET['message'];
$contactinfo = $_GET['contact'];
$probeID = $_GET['probeID'];






//Convert Regular Time to Military Time
$tok = strtok($ProbeTime, ":");
$hours = $tok;
$tok = strtok(":");
$ampm = substr($tok,strlen($tok)-2);
$minutes = substr($tok,0,strlen($tok)-2);
$time = $hours.':'.$minutes.' '.$ampm;
$userTime =  strftime("%H:%M",strtotime($time));


//Get Name for Timezone
$query = $con->prepare("select name from timezones where id=?");
$query->bindValue(1, $PatientTimezone, PDO::PARAM_INT);
$result=$query->execute();

$row = $query->fetch(PDO::FETCH_ASSOC);
$timezone=$row['name'];



//Get Current Date
$currentDate =  Date('Y-m-d');


//Convert CurrentDate+UserTime to GMT (because MYSQL server follows GMT)
$date_str = $currentDate.' '.$userTime; 
$GMTDateTime = convertToGMT($timezone,$date_str);



//Extract time from GMTDateTime
$tok = strtok($GMTDateTime, " ");
$GMTTime  = strtok(" ");


$query = $con->prepare("select timediff(?,curtime()) as timediff,curdate() as currdate,curtime() as currtime");
$query->bindValue(1, $GMTTime, PDO::PARAM_STR);
$result=$query->execute();

$row = $query->fetch(PDO::FETCH_ASSOC);
$timediff = $row['timediff'];
$currdate = $row['currdate'];
$currtime = $row['currtime'];

//echo 'Current Server Time : '.$currdate.' '.$currtime;

$correctDate = $currdate.' '.$GMTTime;

if(substr($timediff,0,1)=="-")
{
	//forward day by 1 day
	//echo 'need to forward date';
	$newDate = date('Y-m-d', strtotime('+1 day', strtotime($currdate)));
	$correctDate = $newDate.' '.$GMTTime;
}
else
{
	//date is correct
	//echo 'no need to forward'; 
}

//echo '<br>Estimate :'.$correctDate;







if($probeID != -111)  //if it is an edit request
{
	//update the probe
	$query = $con->prepare("update probe set desiredTime=?,timezone=?,probeInterval=?,doctorPermission=?,patientPermission=?,emailRequest=?,phoneRequest=?,smsRequest=? where probeID=?"); 
	$query->bindValue(1, $ProbeTime, PDO::PARAM_STR);
	$query->bindValue(2, $PatientTimezone, PDO::PARAM_INT);
	$query->bindValue(3, $ProbeInterval, PDO::PARAM_INT);
	$query->bindValue(4, $DoctorPermission, PDO::PARAM_INT);
	$query->bindValue(5, $PatientPermission, PDO::PARAM_INT);
	$query->bindValue(6, $EmailRequest, PDO::PARAM_INT);
	$query->bindValue(7, $PhoneRequest, PDO::PARAM_INT);
	$query->bindValue(8, $MessageRequest, PDO::PARAM_INT);
	$query->bindValue(9, $probeID, PDO::PARAM_INT);
	$query->execute();
	

	//delete all pending probes for this probeID
	//$query = $con->prepare("delete FROM probe where probeID=?");
	//$query->bindValue(1, $probeID, PDO::PARAM_INT);
	//$query->execute();
	
	
	$lastProbe=$probeID;
	
	$query2 = $con->prepare("UPDATE pendingprobes SET requestTime = ? WHERE probeID = ?");
	$query2->bindValue(1, $correctDate, PDO::PARAM_STR);
	$query2->bindValue(2, $lastProbe, PDO::PARAM_INT);

}
else		//if it is a new request
{
	//Create Entry in Probe table
	$query = $con->prepare("insert into probe(doctorID,patientID,desiredTime,timezone,probeInterval,doctorPermission,patientPermission,emailRequest,phoneRequest,smsRequest,creationDate) 
	values(?,?,?,?,?,?,?,?,?,?,now())");
	$query->bindValue(1, $DoctorID, PDO::PARAM_INT);
	$query->bindValue(2, $PatientID, PDO::PARAM_INT);
	$query->bindValue(3, $ProbeTime, PDO::PARAM_STR);
	$query->bindValue(4, $PatientTimezone, PDO::PARAM_INT);
	$query->bindValue(5, $ProbeInterval, PDO::PARAM_INT);
	$query->bindValue(6, $DoctorPermission, PDO::PARAM_INT);
	$query->bindValue(7, $PatientPermission, PDO::PARAM_INT);
	$query->bindValue(8, $EmailRequest, PDO::PARAM_INT);
	$query->bindValue(9, $PhoneRequest, PDO::PARAM_INT);
	$query->bindValue(10, $MessageRequest, PDO::PARAM_INT);
	
	//echo $query;
	$query->execute() or die('failure');

	//Get ProbeID
	$query = $con->prepare("select max(probeID) as lastProbe from probe");
	$result=$query->execute();
	
	$row = $query->fetch(PDO::FETCH_ASSOC);
	$lastProbe = $row['lastProbe'];
	
	$query = $con->prepare("select max(id) as lastPendingProbe from pendingprobes");
	$result=$query->execute();
	
	$row = $query->fetch(PDO::FETCH_ASSOC);
	$lastPendingProbe = $row['lastPendingProbe'] + 1;
	
	$query2 = $con->prepare("insert into pendingprobes SET probeID = ?, requestTime = ?");
	$query2->bindValue(1, $lastProbe, PDO::PARAM_INT);
	$query2->bindValue(2, $correctDate, PDO::PARAM_STR);
//	$query2->bindValue(3, $lastPendingProbe, PDO::PARAM_INT);


}



//Make an entry into pendingProbes table


$query2->execute() or die('failure');


//update contact information in the database
$tok = strtok($contactinfo, "::");
$tok1 = strtok("::");

if($tok==1)
{
	$query = $con->prepare("update usuarios set email=? where identif=?");
	$query->bindValue(1, $tok1, PDO::PARAM_STR);
	$query->bindValue(2, $PatientID, PDO::PARAM_INT);
	
}
else if($tok==2)
{
	$tok1 = cleanPhoneNumber($tok1);
	$query = $con->prepare("update usuarios set telefono=? where identif=?");
	$query->bindValue(1, $tok1, PDO::PARAM_STR);
	$query->bindValue(2, $PatientID, PDO::PARAM_INT);
}
$query->execute();







echo 'success';


//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

function convertToGMT($timezone,$date_str)
{
	$default_tz = date_default_timezone_get();
	date_default_timezone_set($timezone); // Set this to user's timezone (obtain user's timezone through js)

	$datetime = new DateTime($date_str);
	$cst = new DateTimeZone('GMT');
	$datetime->setTimezone($cst);

	date_default_timezone_set($default_tz);

	$GMTTime = $datetime->format('Y-m-d H:i:s');
	return $GMTTime;
}

function cleanPhoneNumber($phone)
{
	return preg_replace("/[^0-9]/", "",$phone);
}

?>