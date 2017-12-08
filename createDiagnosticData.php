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

$dxname = $_GET['dxname'];
$dxcode = $_GET['dxcode'];
$sdate = $_GET['sdate'].'-01';
$edate = $_GET['edate'];
$notes = $_GET['notes'];
$idrow = $_GET['rowediter'];

if(isset($_GET['rowediter'])){
$query = $con->prepare("UPDATE p_diagnostics SET deleted=1 WHERE id=?");
$query->bindValue(1, $idrow, PDO::PARAM_INT);
$result = $query->execute();
}


if(isset($_GET['nodiag']) && $_GET['nodiag'] == 'yes'){
$query = $con->prepare("DELETE FROM p_diagnostics WHERE idpatient=? and deleted=0");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$result = $query->execute();

$dxname = 'No History to Report';
$dxcode = 'No History to Report';
}

if(!isset($_GET['nodiag'])){
$query = $con->prepare("DELETE FROM p_diagnostics WHERE idpatient=? and deleted=0 and dxname='No History to Report'");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$result = $query->execute();
}

if($edate!=null or $edate!='')
{
	$edate = $edate.'-01';
}
else
{
	$edate=null;
}


if($_SESSION['isPatient']==1)
{
	$edited=0;
}
else
{
	$edited = $_SESSION['MEDID'];
}


echo $sdate;

$query = $con->prepare("INSERT INTO p_diagnostics SET idpatient=?,dxname=?,dxcode=?,dxstart=?,dxend=?,notes=?,edited=?,latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $dxname, PDO::PARAM_STR);
$query->bindValue(3, $dxcode, PDO::PARAM_STR);
$query->bindValue(4, $sdate, PDO::PARAM_STR);
$query->bindValue(5, $edate, PDO::PARAM_STR);
$query->bindValue(6, $notes, PDO::PARAM_STR);
$query->bindValue(7, $edited, PDO::PARAM_INT);
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


/*
$query = "INSERT INTO p_diagnostics(idpatient,dxname,dxcode,dxstart,dxend,notes,edited,latest_update,doctor_signed) values(?,?,?,?,?,?,?,?,?)";
$insertstmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($insertstmt,"isssssisi",$idpatient,$dxname,$dxcode,$sdate,$edate,$notes,$edited,NOW(),$idauthor);
mysqli_stmt_execute($insertstmt);
*/

?>
