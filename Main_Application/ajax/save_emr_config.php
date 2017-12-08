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


$doctor = $_SESSION['MEDID'];
//$doctor = 28;
$personal = $_GET['ph'];
$family = $_GET['fh'];
$pastdx = $_GET['pastdx'];
$medications = $_GET['medications'];
$immunizations = $_GET['immunizations'];
$allergies = $_GET['allergies'];
$address = $_GET['address'];
$address2 = $_GET['address2'];
$city = $_GET['city'];
$state = $_GET['state'];
$country = $_GET['country'];
$notes = $_GET['notes'];
$fractures = $_GET['fractures'];
$surgeries = $_GET['surgeries'];
$otherknown = $_GET['otherknown'];
$obstetric = $_GET['obstetric'];
$othermed = $_GET['othermed'];
$father = $_GET['father'];
$mother = $_GET['mother'];
$siblings = $_GET['siblings'];

$phone=$_GET['phone'];
$insurance=$_GET['insurance'];


$query = $con->prepare("update emr_config set personal=? , family = ? , pastdx = ? , medications = ? , immunizations = ? , allergies = ? , address = ? 
         , address2 = ? , city = ? , state = ?, country = ? , notes = ? , fractures = ? , surgeries = ? , otherknown = ? , obstetric = ? 
		 , othermed = ? , father = ?, mother = ? , siblings = ? ,phone = ? , insurance = ? where userid=?");
$query->bindValue(1, $personal, PDO::PARAM_INT);
$query->bindValue(2, $family, PDO::PARAM_INT);
$query->bindValue(3, $pastdx, PDO::PARAM_INT);
$query->bindValue(4, $medications, PDO::PARAM_INT);
$query->bindValue(5, $immunizations, PDO::PARAM_INT);
$query->bindValue(6, $allergies, PDO::PARAM_INT);
$query->bindValue(7, $address, PDO::PARAM_INT);
$query->bindValue(8, $address2, PDO::PARAM_INT);
$query->bindValue(9, $city, PDO::PARAM_INT);
$query->bindValue(10, $state, PDO::PARAM_INT);
$query->bindValue(11, $country, PDO::PARAM_INT);
$query->bindValue(12, $notes, PDO::PARAM_INT);
$query->bindValue(13, $fractures, PDO::PARAM_INT);
$query->bindValue(14, $surgeries, PDO::PARAM_INT);
$query->bindValue(15, $otherknown, PDO::PARAM_INT);
$query->bindValue(16, $obstetric, PDO::PARAM_INT);
$query->bindValue(17, $othermed, PDO::PARAM_INT);
$query->bindValue(18, $father, PDO::PARAM_INT);
$query->bindValue(19, $mother, PDO::PARAM_INT);
$query->bindValue(20, $siblings, PDO::PARAM_INT);
$query->bindValue(21, $phone, PDO::PARAM_INT);
$query->bindValue(22, $insurance, PDO::PARAM_INT);
$query->bindValue(23, $doctor, PDO::PARAM_INT);
$query->execute();

//echo $query;


if($query)
{
	echo 'success';
}
else
{
	echo 'failure';
}
	
?>