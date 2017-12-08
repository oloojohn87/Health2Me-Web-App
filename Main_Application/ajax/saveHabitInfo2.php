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
 
 
$idpatient = $_SESSION['UserID'];
echo "Id patient : ".$idpatient."<br>";
$iddoctor = $_SESSION['MEDID'];
if ($iddoctor == $idpatient) $idauthor = -1; else $idauthor = $iddoctor;

$cigarette = $_GET['cigarette'];
$alcohol = $_GET['alcohol'];
$exercise = $_GET['exercise'];
$sleep = $_GET['sleep'];

if($_SESSION['isPatient']==1)
{
	$edited=0;
}
else
{
	$edited = $_SESSION['MEDID'];
}
//
if($sleep == 0){
$sleep = 8;
}
$query = $con->prepare("SELECT * FROM p_habits where idpatient=?");
$query->bindValue(1, $idpatient, PDO::PARAM_INT);
$result = $query->execute();

$count = $query->rowCount();
$row = $query->fetch(PDO::FETCH_ASSOC);

echo "Patient ID is:".$idpatient;
echo "Count is :".$count;

if($count==0)
	{
		
        echo " In IF";
        $query = $con->prepare("INSERT INTO p_habits SET idpatient=?,cigarettes=?,alcohol=?,exercise=?,sleep=?,edited=?,latest_update=NOW(),doctor_signed=?");
		$query->bindValue(1, $idpatient, PDO::PARAM_INT);
		$query->bindValue(2, $cigarette, PDO::PARAM_INT);
		$query->bindValue(3, $alcohol, PDO::PARAM_INT);
		$query->bindValue(4, $exercise, PDO::PARAM_INT);
		$query->bindValue(5, $sleep, PDO::PARAM_INT);
		$query->bindValue(6, $edited, PDO::PARAM_INT);
		$query->bindValue(7, $idauthor, PDO::PARAM_INT);
		$result = $query->execute();
	
	}
	else
	{

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
	}
















?>