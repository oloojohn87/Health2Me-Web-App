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
$NReports=empty($_GET['NReports']) ? 30: $_GET['NReports'];
$daysExplore=empty($_GET['days']) ? 30: $_GET['days'];
$TypeEntry=empty($_GET['TypeEntry']) ? 0: $_GET['TypeEntry'];

if ($TypeEntry == 'in')
{
	$result = mysql_query("SELECT * FROM doctorslinkdoctors WHERE IdMED2 = '$queUsu' ORDER BY Fecha DESC LIMIT 15 ");
}else
{
	$result = mysql_query("SELECT * FROM doctorslinkdoctors WHERE IdMED = '$queUsu' ORDER BY Fecha DESC LIMIT 15 ");
}
//echo '  TypeEntry  '.$TypeEntry;
//echo '    '.'SELECT * FROM doctorslinkdoctors WHERE IdMED = '.$queUsu.' ORDER BY Fecha DESC LIMIT 15 '.'    ';
$n=0;
$cadena = '';

// Report all errors except E_NOTICE
error_reporting(E_ALL ^ E_NOTICE);
$IterationRef = array();

while ($row = mysql_fetch_array($result)) {

$referral_id = $row['id'];
$referral_date = $row['Fecha'];

//Adding changes for the new referrral type
$referral_type=$row['Type'];

$referral_dateCONF = $row['FechaConfirm'];
$result2B = mysql_query("select stage,datevisit from referral_stage where referral_id='$referral_id'");
$row2B = mysql_fetch_array($result2B);
$referral_stage=$row2B['stage'];
switch ($referral_stage){
    case 1: $LetStage='A';
            break;
    case 2: $LetStage='B';
            break;
    case 3: $LetStage='C';
            break;
    case 4: $LetStage='D';
            break;
}

$IdPac = $row['IdPac'];

if ($TypeEntry == 'in')
{
	$IdDoctor = $row['IdMED'];
	$IterationRef[$IdPac]=0;
}else
{
	$IdDoctor = $row['IdMED2'];
	$IterationRef[$IdPac]++;
	
}
$result2 = mysql_query("SELECT * FROM doctors WHERE id = '$IdDoctor' LIMIT 15");

while ($row2 = mysql_fetch_array($result2)) {
	$NameD2 = (htmlspecialchars($row2['Name']));
	$SurnameD2 = (htmlspecialchars($row2['Surname']));
	$RoleD2 = $row2['Role'];
	$TreatD2 = '';
	if ($RoleD2 == '1') $TreatD2 = 'Dr.';
}
$result2 = mysql_query("SELECT * FROM usuarios WHERE Identif = '$IdPac' LIMIT 15");

while ($row2 = mysql_fetch_array($result2)) {
	$NameP = (htmlspecialchars($row2['Name']));
	$SurnameP = (htmlspecialchars($row2['Surname']));
}


	// Check if any of this patients has new MESSAGES from DOCTORS
	$resultMES = mysql_query("SELECT sender_id,receiver_id,fecha,attachments,status FROM message_infrasture WHERE patient_id = '$IdPac' AND receiver_id = '$queUsu' AND DATEDIFF(NOW(), fecha) <= '$daysExplore' ORDER BY fecha DESC ");
	$MessagesReceived=0;
	$MessagesReceivedNEW=0;
	$NewestdateMessage=0;
	while ($rowMES = mysql_fetch_array($resultMES)) {
		if ($rowMES['status'] == 'new') { $MessagesReceivedNEW++; $NewestdateMessage = $rowMES['fecha'];}
		$MessagesReceived++;
	}



if ($n>0) $cadena.=',';    
$cadena.='
{
    "id":"'.$IdPac.'",
    "name":"'.$NameP.'",
    "surname":"'.$SurnameP.'",
    "iteration":"'.$IterationRef[$IdPac].'",
    "idDoctor":"'.$IdDoctor.'",
    "nameDoctor":"'.$NameD2.'",
    "surnameDoctor":"'.$SurnameD2.'",
	"referralType":"'.$referral_type.'",
    "stage":"'.$LetStage.'",
    "stageNum":"'.$referral_stage.'",
    "dateRef":"'.SanDate($referral_date).'",
    "dateRefCONF":"'.SanDate($referral_dateCONF).'",    
    "MessagesReceived":"'.$MessagesReceived.'",    
    "MessagesReceivedNEW":"'.$MessagesReceivedNEW.'"    
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