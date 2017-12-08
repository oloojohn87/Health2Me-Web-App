<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("environment_detail.php");
 
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="messages"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$IdMED= $_GET['IdMED'];
$patientid=$_GET['patient'];
$sendingdoc=empty($_GET['sendingdoc']) ? 0: $_GET['sendingdoc'];
$unread=empty($_GET['unread']) ? 0: $_GET['unread'];
$scenario=empty($_GET['scenario']) ? 0: $_GET['scenario'];
$numDisplay = $_GET['numDisplay'];
$start = $_GET['start'];

$cutByDays = '';
if ($unread == 0) $cutByDays = 'AND DATEDIFF(NOW(), fecha) <=  60';
if ($scenario == 'patient') 
{
	$WhatDB = 'message_infrastructureuser'; 
	$SwitchDr = 0;
} else 
{ 
	$WhatDB = 'message_infrasture'; 
	$SwitchDr = 1;
}

if($sendingdoc==0){
	if($patientid==-1){
		$setQuery = $con->prepare('SELECT * FROM '.$WhatDB.' WHERE receiver_id=? '.$cutByDays.' ORDER BY fecha DESC LIMIT ?, ?');
		$setQuery->bindValue(1, $IdMED, PDO::PARAM_INT);
		$setQuery->bindValue(2, $start, PDO::PARAM_INT);
		$setQuery->bindValue(3, $numDisplay, PDO::PARAM_INT);
		
		//$result = mysql_query("SELECT * FROM message_infrasture WHERE receiver_id='$IdMED' ORDER BY fecha DESC");
	}else
	{
		$setQuery = $con->prepare('SELECT * FROM '.$WhatDB.'  WHERE receiver_id=? and patient_id=?  '.$cutByDays.' ORDER BY fecha DESC LIMIT ?, ?');
		$setQuery->bindValue(1, $IdMED, PDO::PARAM_INT);
		$setQuery->bindValue(2, $patientid, PDO::PARAM_INT);
		$setQuery->bindValue(3, $start, PDO::PARAM_INT);
		$setQuery->bindValue(4, $numDisplay, PDO::PARAM_INT);
		
		//$result = mysql_query("SELECT * FROM message_infrasture WHERE receiver_id='$IdMED' and patient_id='$patientid' ORDER BY fecha DESC");
	}
}else{
	if($patientid==-1){
		$setQuery = $con->prepare('SELECT * FROM '.$WhatDB.'  WHERE receiver_id=?  and sender_id=?  '.$cutByDays.' ORDER BY fecha DESC LIMIT ?, ?');
		$setQuery->bindValue(1, $IdMED, PDO::PARAM_INT);
		$setQuery->bindValue(2, $sendingdoc, PDO::PARAM_INT);
		$setQuery->bindValue(3, $start, PDO::PARAM_INT);
		$setQuery->bindValue(4, $numDisplay, PDO::PARAM_INT);
		
		//$result = mysql_query("SELECT * FROM message_infrasture WHERE receiver_id='$IdMED' and sender_id='$sendingdoc' ORDER BY fecha DESC");
	}else
	{
		$setQuery = $con->prepare('SELECT * FROM '.$WhatDB.'  WHERE receiver_id=?  and patient_id=? and sender_id=? '.$cutByDays.' ORDER BY fecha DESC LIMIT ?, ?');
		$setQuery->bindValue(1, $IdMED, PDO::PARAM_INT);
		$setQuery->bindValue(2, $patientid, PDO::PARAM_INT);
		$setQuery->bindValue(3, $sendingdoc, PDO::PARAM_INT);
		$setQuery->bindValue(4, $start, PDO::PARAM_INT);
		$setQuery->bindValue(5, $numDisplay, PDO::PARAM_INT);
		
		//$result = mysql_query("SELECT * FROM message_infrasture WHERE receiver_id='$IdMED' and patient_id='$patientid' and sender_id='$sendingdoc' ORDER BY fecha DESC");
	}
}

//echo $setQuery;

$result = $setQuery->execute();

$num_msg = 0;
$cadena = '';

while ($row = $setQuery->fetch(PDO::FETCH_ASSOC)) {
$hasAttachments=false;
$id=$row['message_id'];
$subject=$row['Subject'];
$content=$row['content'];
$senderid=$row['sender_id'];
$patientid=$row['patient_id'];
$status=$row['status'];
$reportids=$row['attachments'];

$senderID = $senderid;

if($reportids!=null and $reportids!=0){
$hasAttachments=true;
}

if ($SwitchDr == 1)
{
	$getname = $con->prepare("select * from doctors where id=?");
	$getname->bindValue(1, $senderid, PDO::PARAM_INT);
	$getname->execute();
	
	$row11 = $getname->fetch(PDO::FETCH_ASSOC);
	$senderid=$row11['IdMEDFIXEDNAME'];
	$initials = strtoupper(substr($row11['Name'], 0, 1)).' '.strtoupper(substr($row11['Surname'], 0, 1));
	$NameSender = strtoupper(substr($row11['Name'], 0, 1)).substr($row11['Name'], 1).' '.strtoupper(substr($row11['Surname'], 0, 1)).substr($row11['Surname'], 1) ;
};

$getname = $con->prepare("select * from usuarios where Identif=?");
$getname->bindValue(1, $patientid, PDO::PARAM_INT);
$getname->execute();

$row11 = $getname->fetch(PDO::FETCH_ASSOC);
$initialsP = strtoupper(substr($row11['Name'], 0, 1)).' '.strtoupper(substr($row11['Surname'], 0, 1));
$NamePatient = strtoupper(substr($row11['Name'], 0, 1)).substr($row11['Name'], 1).' '.strtoupper(substr($row11['Surname'], 0, 1)).substr($row11['Surname'], 1) ;
if ($SwitchDr == 0)
{
	$senderid=$patientid;
	$initials = $initialsP;
	$NameSender = $NamePatient;
};

//$date=$row['fecha'];
$rep_date = substr($row['fecha'],0,10);
$year=strtok($rep_date,"-");
$month=strtok("-");
$day = strtok("-");
$rep_date = $month."-".$day."-".$year;
// unread 0 means ALL MESSAGES, unread 1 means ONLY NEW MESSAGES
if ($unread == '0' or ($unread != '0' and $status == 'new'))
{
	if ($num_msg>0) $cadena.=',';    
	$cadena.='
	    {
	        "MessageID":"'.$id.'",
	        "MessageIdPat":"'.$patientid.'",
	        "MessageInitialsPat":"'.$initialsP.'",
	        "MessageNamePat":"'.$NamePatient.'",
	        "MessageIdSender":"'.$senderID.'",
	        "MessageNameSender":"'.$NameSender.'",
	        "MessageStatus":"'.$status.'",
	        "MessageSUBJ":"'.$subject.'",
	        "MessageCONT":"'.$content.'",
	        "MessageRIDS":"'.$reportids.'",
	        "MessageSEND":"'.$senderid.'",
	        "MessageINIT":"'.$initials.'",
	        "MessageDATE":"'.$rep_date.'"
	        }';    
	$num_msg++;
}

}

$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 




    
    

?>


