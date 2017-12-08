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

$idpatient = null;
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

echo "Author ".$idauthor;

$drugname = $_GET['drugname'];
$drugcode = $_GET['drugcode'];
$frequency = $_GET['frequency'];
$dossage = $_GET['dossage'];
$numDays = $_GET['numDays'];
$idrow = $_GET['rowediter'];

if(isset($_GET['rowediter'])){
$query = $con->prepare("UPDATE p_medication SET deleted=1 WHERE id=?");
$query->bindValue(1, $idrow, PDO::PARAM_INT);
$result = $query->execute();
}


if(isset($_GET['nomeds']) && $_GET['nomeds'] == 'yes'){
$query = $con->prepare("DELETE FROM p_medication WHERE idpatient=? and deleted=0");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$result = $query->execute();

$drugname = "No Medications";
$drugcode = "No Medications";
}

if(!isset($_GET['nomeds'])){
$query = $con->prepare("DELETE FROM p_medication WHERE idpatient=? and deleted=0 and drugname='No Medications'");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$result = $query->execute();
}

if($_SESSION['isPatient']==1)
{
	$edited=0;
}
else
{
	$edited = $_SESSION['MEDID'];
}

//,latest_update=NOW(),doctor_signed='$idauthor'

$query = $con->prepare("INSERT INTO p_medication SET idpatient=?,drugname=?,drugcode=?,frequency=?,dossage=?,edited=?,numDays=?,latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $drugname, PDO::PARAM_STR);
$query->bindValue(3, $drugcode, PDO::PARAM_STR);
$query->bindValue(4, $frequency, PDO::PARAM_STR);
$query->bindValue(5, $dossage, PDO::PARAM_STR);
$query->bindValue(6, $edited, PDO::PARAM_INT);
$query->bindValue(7, $numDays, PDO::PARAM_INT);
$query->bindValue(8, $idauthor, PDO::PARAM_INT);
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

$query = $con->prepare("INSERT INTO p_log SET IdUsu = ?, IdAuthor = ?, Description = ?, Timestamp = NOW()");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $idauthor, PDO::PARAM_INT);
$query->bindValue(3, "Medications - Added ".$drugname, PDO::PARAM_STR);
$query->execute();

/*
$query = "INSERT INTO p_medication(idpatient,drugname,drugcode,frequency,dossage,edited,numDays,latest_update,doctor_signed) values(?,?,?,?,?,?,?,?,?)";
$insertstmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($insertstmt,"issssiisi",$idpatient,$drugname,$drugcode,$frequency,$dossage,$edited,$numDays,NOW(),$idauthor);
mysqli_stmt_execute($insertstmt);
*/

?>
