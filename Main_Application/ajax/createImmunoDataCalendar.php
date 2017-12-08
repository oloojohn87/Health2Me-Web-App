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
$iddoctor = $_SESSION['MEDID'];
//if ($iddoctor == $idpatient) $idauthor = -1; else $idauthor = $iddoctor;
if ($iddoctor > 0) $idauthor = $iddoctor; else $idauthor = -1; 

$countryCode = $_GET['country'];
$currentAge = $_GET['dob'];

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

$years = substr($dob,0,4);
$months = substr($dob,5,2);
$days = substr($dob,8,2);

$bday = new DateTime(''.$days.'.'.$months.'.'.$years.'');//dd.mm.yyyy
     $today = new DateTime('00:00:00'); // Current date
     $diff = $today->diff($bday);
//     printf('%d years, %d month, %d days', $diff->y, $diff->m, $diff->d);
//	 var_dump($diff);

echo $diff->y."</br>";
echo $diff->m."</br>";
echo $diff->d;


$result = null;
//THIS IS FOR USA VACCINATION CALENDAR/////////////////////////////////////////////////////////////////////////////////////////////
if($countryCode == "USA"){

$query = $con->prepare("UPDATE p_immuno SET deleted=1 WHERE idpatient=? && VaccCode != ''");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$result = $query->execute();


//This adds vaccines at birth
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HEPB',VaccName='HEPB',ageevent='0 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();

//This adds vaccines at 1 month
if($diff->y > 0 or $diff->m >= 1){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HEPB',VaccName='HEPB',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//These add vaccines at 2 months
if($diff->y > 0 or $diff->m >= 2){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='ROTAVIRUS',VaccName='ROTAVIRUS',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 0 or $diff->m >= 2){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTAP',VaccName='DTAP',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 0 or $diff->m >= 2){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HIB',VaccName='HIB',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 0 or $diff->m >= 2){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='PNEUMO_CONJ',VaccName='PNEUMO_CONJ',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 0 or $diff->m >= 2){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='IPV',VaccName='IPV',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//These adds vaccines at 4 months
if($diff->y > 0 or $diff->m >= 4){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='ROTAVIRUS',VaccName='ROTAVIRUS',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 0 or $diff->m >= 4){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HIB',VaccName='HIB',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 0 or $diff->m >= 4){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='PNEUMO_CONJ',VaccName='PNEUMO_CONJ',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 0 or $diff->m >= 4){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='IPV',VaccName='IPV',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//These add vaccines for 6 months
if($diff->y > 0 or $diff->m >= 6){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HEPB',VaccName='HEPB',ageevent='6 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 0 or $diff->m >= 6){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTAP',VaccName='DTAP',ageevent='6 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 0 or $diff->m >= 6){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='PNEUMO_CONJ',VaccName='PNEUMO_CONJ',ageevent='6 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 0 or $diff->m >= 6){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='IPV',VaccName='IPV',ageevent='6 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 0 or $diff->m >= 6){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='INFLUENZA',VaccName='INFLUENZA',ageevent='6 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//These add vaccines for 12 months
if($diff->y >= 1 or $diff->m >= 12){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HIB',VaccName='HIB',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 1 or $diff->m >= 12){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='PNEUMO_CONJ',VaccName='PNEUMO_CONJ',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 1 or $diff->m >= 12){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MMR',VaccName='MMR',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 1 or $diff->m >= 12){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='VARICELLA',VaccName='VARICELLA',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 1 or $diff->m >= 12){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HEPA',VaccName='HEPA',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//This adds vaccines for 15 months
if($diff->y >= 2 or ($diff->y >= 1 && $diff->m >= 3)){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTAP',VaccName='DTAP',ageevent='15 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//This adds vaccines for 2 years
if($diff->y >= 2){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='INFLUENZA',VaccName='INFLUENZA',ageevent='2 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//These add vaccines for 4 years
if($diff->y >= 4){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTAP',VaccName='DTAP',ageevent='4 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 4){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='IPV',VaccName='IPV',ageevent='4 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 4){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MMR',VaccName='MMR',ageevent='4 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 4){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='VARICELLA',VaccName='VARICELLA',ageevent='4 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//These add vaccines for 11 years
if($diff->y >= 11){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTAP',VaccName='DTAP',ageevent='11 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 11){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MENC_CONJ',VaccName='MENC_CONJ',ageevent='11 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 11){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HPV',VaccName='HPV',ageevent='11 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//This add vaccines for 13 years
if($diff->y >= 13){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MENC_CONJ',VaccName='MENC_CONJ',ageevent='13 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//These add vaccines for 19 years
if($diff->y >= 19){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='TD',VaccName='TD',ageevent='19 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 29){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='TD',VaccName='TD',ageevent='29 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 39){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='TD',VaccName='TD',ageevent='39 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 49){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='TD',VaccName='TD',ageevent='49 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 59){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='TD',VaccName='TD',ageevent='59 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 69){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='TD',VaccName='TD',ageevent='69 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 60){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MENC_CONJ',VaccName='MENC_CONJ',ageevent='60 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

}

//THIS IS FOR COLOMBIA VACCINATION CALENDAR/////////////////////////////////////////////////////////////////////////////////////////////
if($countryCode == "COL"){

$query = $con->prepare("DELETE FROM p_immuno WHERE idpatient=? && VaccCode != ''");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$result = $query->execute();

//HEPB
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HEPB',VaccName='HEPB',ageevent='0 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();



//ROTAVIRUS
if($diff->y > 0 or $diff->m >= 2){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='ROTAVIRUS',VaccName='ROTAVIRUS',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 0 or $diff->m >= 4){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='ROTAVIRUS',VaccName='ROTAVIRUS',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//ORAL POLIO VACCINE
if($diff->y > 0 or $diff->m >= 2){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='OPV',VaccName='OPV',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 0 or $diff->m >= 4){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='OPV',VaccName='OPV',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 0 or $diff->m >= 6){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='OPV',VaccName='OPV',ageevent='6 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 2 or ($diff->y >= 1 && $diff->m >= 6)){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='OPV',VaccName='OPV',ageevent='18 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 5){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='OPV',VaccName='OPV',ageevent='5 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//Pneumo_conj
if($diff->y > 0 or $diff->m >= 2){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='PNEUMO_CONJ',VaccName='PNEUMO_CONJ',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 0 or $diff->m >= 4){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='PNEUMO_CONJ',VaccName='PNEUMO_CONJ',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}
if($diff->y >= 1 or $diff->m >= 12){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='PNEUMO_CONJ',VaccName='PNEUMO_CONJ',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//DT
if($diff->y > 0 or $diff->m >= 2){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DT',VaccName='DT',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 0 or $diff->m >= 4){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DT',VaccName='DT',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 0 or $diff->m >= 6){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DT',VaccName='DT',ageevent='6 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 2 or ($diff->y >= 1 && $diff->m >= 6)){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DT',VaccName='DT',ageevent='18 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 5){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DT',VaccName='DT',ageevent='5 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//DTwP
if($diff->y >= 2 or ($diff->y >= 1 && $diff->m >= 6)){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTWP',VaccName='DTWP',ageevent='18 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 5){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTWP',VaccName='DTWP',ageevent='5 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//Yellow Fever
if($diff->y >= 1){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='YF',VaccName='YF',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//MMR
if($diff->y >= 1 or $diff->m >= 12){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MMR',VaccName='MMR',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 5){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MMR',VaccName='MMR',ageevent='5 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//HEPA
if($diff->y >= 1 or $diff->m >= 12){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HEPA',VaccName='HEPA',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

}


//THIS IS FOR SPAIN VACCINATION CALENDAR/////////////////////////////////////////////////////////////////////////////////////////////
if($countryCode == "SPA"){

$query = $con->prepare("DELETE FROM p_immuno WHERE idpatient=? && VaccCode != ''");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$result = $query->execute();

//HEPB
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HEPB',VaccName='HEPB',ageevent='0 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();

//MenC_conj
if($diff->y > 0 or $diff->m >= 2){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MENC_CONJ',VaccName='MENC_CONJ',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//DTaPHibHepIPV
if($diff->y > 0 or $diff->m >= 2){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTAPHIBHEPIPV',VaccName='DTAPHIBHEPIPV',ageevent='2 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 0 or $diff->m >= 4){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MENC_CONJ',VaccName='MENC_CONJ',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//DTaPHiblPV
if($diff->y > 0 or $diff->m >= 4){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTAPHIBIPV',VaccName='DTAPHIBIPV',ageevent='4 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y > 0 or $diff->m >= 6){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTAPHIBHEPIPV',VaccName='DTAPHIBHEPIPV',ageevent='6 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 1 or $diff->m >= 12){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MENC_CONJ',VaccName='MENC_CONJ',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//MMR
if($diff->y >= 1 or $diff->m >= 12){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MMR',VaccName='MMR',ageevent='1 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 2 or ($diff->y >= 1 && $diff->m >= 6)){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='DTAPHIBIPV',VaccName='DTAPHIBIPV',ageevent='18 m',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 3){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='MMR',VaccName='MMR',ageevent='3 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//Tdap
if($diff->y >= 6){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='TDAP',VaccName='TDAP',ageevent='6 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//VARICELLA
if($diff->y >= 10){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='VARICELLA',VaccName='VARICELLA',ageevent='10 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//HPV
if($diff->y >= 11){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HPV',VaccName='HPV',ageevent='11 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 12){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='VARICELLA',VaccName='VARICELLA',ageevent='12 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 12){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HPV',VaccName='HPV',ageevent='12 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

if($diff->y >= 13){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='HPV',VaccName='HPV',ageevent='13 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//TD
if($diff->y >= 14){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='TD',VaccName='TD',ageevent='14 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}

//INFLUENZA
if($diff->y >= 64){
$query = $con->prepare("INSERT INTO p_immuno SET idpatient=?,VaccCode='INFLUENZA',VaccName='INFLUENZA',ageevent='64 y',latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$result = $query->execute();
}


}

if($result != null)
{
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
}

//$query = "INSERT INTO p_immuno SET idpatient=?,VaccCode='$VaccCode',VaccName='$VaccCode',AllerCode='$AllerCode',AllerName='$AllerName',intensity='$intensity',date='$dateEvent',ageevent='$ageEvent',latest_update=NOW(),doctor_signed=?";
//$result = mysql_query($query);

/*
$query = "INSERT INTO p_immuno(idpatient,VaccCode,VaccName,AllerCode,AllerName,intensity,date,ageevent,latest_update,doctor_signed) values(?,?,?,?,?,?,?,?,?,?)";
$insertstmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($insertstmt,"issssisssi",$idpatient,$VaccCode,$VaccName,$AllerCode,$AllerName,$intensity,$dateEvent,$ageEvent,NOW(),$idauthor);
mysqli_stmt_execute($insertstmt);
*/


?>