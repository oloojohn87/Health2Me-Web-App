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

$relativename = $_GET['relativename'];
$relativecode = $_GET['relativecode'];
$diseasename = $_GET['diseasename'];
$diseasegroup = $_GET['diseasegroup'];
$ICD10 = empty($_GET['ICD10']) ? 0: $_GET['ICD10'];
$ICD9 = empty($_GET['ICD9']) ? 0: $_GET['ICD9'];
$ageevent = $_GET['ageevent'];
$dateevent = $_GET['dateevent'];
$idrow = $_GET['rowediter'];

if(isset($_GET['rowediter'])){
$query = $con->prepare("UPDATE p_family SET deleted=1 WHERE id=?");
$query->bindValue(1, $idrow, PDO::PARAM_INT);
$result = $query->execute();
}


/*
$idpatient = 1514;
$drugname = 'Test';
$drugcode = 'Test';
$frequency = 'Test';
$dossage = 'Test';
$edited = 1;
$numdays = 1;
*/

/*
if($_SESSION['isPatient']==1)
{
	$edited=0;
}
else
{
	$edited = $_SESSION['MEDID'];
}
*/


$query = $con->prepare("INSERT INTO p_family SET idpatient=?,relative=?,relativetype=?,disease=?,diseasegroup=?,ICD10=?,ICD9=?,atage=?,latest_update=NOW(),doctor_signed=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$query->bindValue(2, $relativename, PDO::PARAM_STR);
$query->bindValue(3, $relativecode, PDO::PARAM_INT);
$query->bindValue(4, $diseasename, PDO::PARAM_STR);
$query->bindValue(5, $diseasegroup, PDO::PARAM_INT);
$query->bindValue(6, $ICD10, PDO::PARAM_INT);
$query->bindValue(7, $ICD9, PDO::PARAM_INT);
$query->bindValue(8, $ageevent, PDO::PARAM_INT);
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
$query = "INSERT INTO p_family(idpatient,relative,relativetype,disease,diseasegroup,ICD10,ICD9,atage,latest_update,doctor_signed) values(?,?,?,?,?,?,?,?,?,?)";
$insertstmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($insertstmt,"isisissisi",$idpatient,$relativename,$relativecode,$diseasename,$diseasegroup,$ICD10,$ICD9,$ageevent,NOW(),$idauthor);
mysqli_stmt_execute($insertstmt);
*/


?>
