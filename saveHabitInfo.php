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

$cigarette = $_GET['cigarette'];
$alcohol = $_GET['alcohol'];
$exercise = $_GET['exercise'];
$sleep = $_GET['sleep'];

echo "Cigarette : ".$cigarette."<br>";
echo "Alcohol : ".$alcohol."<br>";
echo "Exercise : ".$exercise."<br>";
echo "Sleep : ".$sleep."<br>";

if($_SESSION['isPatient']==1)
{
	$edited=0;
}
else
{
	$edited = $_SESSION['MEDID'];
}

if($sleep == 0){
$sleep = 8;
}
/*echo "getting here";
$query = $con->prepare("SELECT * FROM p_habits where idpatient=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$result = $query->execute();

$count = $query->rowCount();
$row = $query->fetch(PDO::FETCH_ASSOC);
echo "count:".$count."<br>";

if($count==0)
{
    echo "getting in insert<br>";*/
    $query = $con->prepare("INSERT INTO p_habits SET idpatient=?,cigarettes=?,alcohol=?,exercise=?,sleep=?,edited=?,latest_update=NOW(),doctor_signed=?");
    $query->bindValue(1, $idpatient, PDO::PARAM_INT);
    $query->bindValue(2, $cigarette, PDO::PARAM_INT);
    $query->bindValue(3, $alcohol, PDO::PARAM_INT);
    $query->bindValue(4, $exercise, PDO::PARAM_INT);
    $query->bindValue(5, $sleep, PDO::PARAM_INT);
    $query->bindValue(6, $edited, PDO::PARAM_INT);
    $query->bindValue(7, $idauthor, PDO::PARAM_INT);
    $result = $query->execute();

/*}
else
{
     echo "getting in update";
     $query = $con->prepare("UPDATE p_habits SET idpatient=?,cigarettes=?,alcohol=?,exercise=?,sleep=?,edited=?,latest_update=NOW(),doctor_signed=? where idpatient=?");
     $query->bindValue(1, $idpatient, PDO::PARAM_INT);
     $query->bindValue(2, $cigarette, PDO::PARAM_INT);
     $query->bindValue(3, $alcohol, PDO::PARAM_INT);
     $query->bindValue(4, $exercise, PDO::PARAM_INT);
     $query->bindValue(5, $sleep, PDO::PARAM_INT);
     $query->bindValue(6, $edited, PDO::PARAM_INT);
     $query->bindValue(7, $idauthor, PDO::PARAM_INT);
     $query->bindValue(8, $idpatient, PDO::PARAM_INT);
     $result = $query->execute();
}*/

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














?>
