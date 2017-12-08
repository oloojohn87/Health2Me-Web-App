<?php
session_start();
require("environment_detail.php");
require("NotificationClass.php");
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

$notifications = new Notifications();

$idpatient = 0;
if(isset($_GET['IdUsu']))
{
    $idpatient = $_GET['IdUsu'];
}
else
{
    $idpatient = $_SESSION['UserID'];
}
if($_SESSION['MEDID'] == $_SESSION['BOTHID']){
	$iddoctor = $_SESSION['MEDID'];
}else{
	$iddoctor = 0;
}
//if ($iddoctor == $idpatient) $idauthor = -1; else $idauthor = $iddoctor;
if ($iddoctor > 0) $idauthor = $iddoctor; else $idauthor = -1; 

$VaccCode = $_GET['VaccCode'];
$VaccName = $_GET['VaccName'];
$AllerCode = $_GET['AllerCode'];
$AllerName = $_GET['AllerName'];
$intensity = $_GET['intensity'];
$dateEvent = $_GET['dateEvent'];
$ageEvent = $_GET['ageEvent'];
$dateEvent = $dateEvent.'-01';
$dob = '';
if(isset($_GET['dob']) && $_GET['dob'] != NULL && strlen($_GET['dob']) > 0)
{
    $dob = $_GET['dob'];
}
else
{
    $fetch_dob = $con->prepare("SELECT DOB FROM basicemrdata WHERE IdPatient = ?");
    $fetch_dob->bindValue(1, $idpatient, PDO::PARAM_INT);
    $fetch_dob->execute();
    if($fetch_dob->rowCount() > 0)
    {
        $row = $fetch_dob->fetch(PDO::FETCH_ASSOC);
        $dob = $row['DOB'];
        if(strlen($dob) > 10)
            $dob = substr($dob, 0, 10); // exclude time
    }
    else
    {
        $dob = '1970-01-01';
    }
}


$idrow = $_GET['rowediter'];

if(isset($_GET['rowediter'])){
    $query = $con->prepare("UPDATE p_immuno SET deleted=1 WHERE id=?");
    $query->bindValue(1, $idrow, PDO::PARAM_INT);
    $result = $query->execute();
}


if(isset($_GET['noallergies']) && $_GET['noallergies'] == 'yes'){
    $query = $con->prepare("DELETE FROM p_immuno WHERE idpatient=? and deleted=0");
    $query->bindValue(1, $idpatient, PDO::PARAM_INT);
    $result = $query->execute();

    $ageEvent = 0;
    $AllerName = "Nothing";
    $AllerCode = "Nothing";
}

if(!isset($_GET['noallergies'])){
    $query = $con->prepare("DELETE FROM p_immuno WHERE idpatient=? and deleted=0 and AllerName='Nothing'");
    $query->bindValue(1, $idpatient, PDO::PARAM_INT);
    $result = $query->execute();
}

if(isset($_GET['dateEvent']) && $_GET['dateEvent'] != "")
{
    $years = substr($dateEvent,0,4);
    $months = substr($dateEvent,5,2);
    $days = substr($dateEvent,8,2);

    $dob_years = substr($dob,0,4);
    $dob_months = substr($dob,5,2);
    $dob_days = substr($dob,8,2);

    $bday = new DateTime(''.$days.'.'.$months.'.'.$years.'');//dd.mm.yyyy
    $today = new DateTime(''.$dob_days.'.'.$dob_months.'.'.$dob_years.''); // Current date
    $diff = $today->diff($bday);
    //     printf('%d years, %d month, %d days', $diff->y, $diff->m, $diff->d);
    //	 var_dump($diff);


    if($diff->y > 0)
    {
        $year_hold = $diff->y." y";
    }
    else
    {
        $year_hold = $diff->m." m";
    }

    $ageEvent = $year_hold;
}
else
{
    $ageEvent = $ageEvent." y";
}

//echo $year_hold."</br>".$ageEvent;

if($VaccName != ""){
    $VaccCode = $VaccName;
}

if($AllerCode == ''){
    $AllerCode = substr($AllerName,0,4);
}

if($ageEvent == '' or $ageEvent == 0){
    $ageEvent = '0 y';
}

$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode=?,VaccName=?,AllerCode=?,AllerName=?,intensity=?,date=?,ageevent=?,latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $VaccCode, PDO::PARAM_STR);
$query->bindValue(3, $VaccCode, PDO::PARAM_STR);
$query->bindValue(4, $AllerCode, PDO::PARAM_STR);
$query->bindValue(5, $AllerName, PDO::PARAM_STR);
$query->bindValue(6, $intensity, PDO::PARAM_INT);
$query->bindValue(7, $dateEvent, PDO::PARAM_STR);
$query->bindValue(8, $ageEvent, PDO::PARAM_STR);
$query->bindValue(9, $idauthor, PDO::PARAM_INT);
$result = $query->execute();

if($_SESSION['BOTHID'] == $_SESSION['MEDID'])
{
    // doctor
    $notifications->add('SUMEDT', $iddoctor, true, $idpatient, false, null);
}
else
{
    $pers_doc = $con->prepare("SELECT personal_doctor FROM usuarios WHERE Identif = ?");
    $pers_doc->bindValue(1, $idpatient, PDO::PARAM_INT);
    $pers_doc->execute();
    $pers_doc_row = $pers_doc->fetch(PDO::FETCH_ASSOC);
    if($pers_doc_row['personal_doctor'] != NULL)
    {
        $notifications->add('SUMEDT', $idpatient, false, $pers_doc_row['personal_doctor'], true, null);
    }
}


/*
$query = "INSERT INTO p_immuno(idpatient,VaccCode,VaccName,AllerCode,AllerName,intensity,date,ageevent,latest_update,doctor_signed) values(?,?,?,?,?,?,?,?,?,?)";
$insertstmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($insertstmt,"issssisssi",$idpatient,$VaccCode,$VaccName,$AllerCode,$AllerName,$intensity,$dateEvent,$ageEvent,NOW(),$idauthor);
mysqli_stmt_execute($insertstmt);
*/


?>
