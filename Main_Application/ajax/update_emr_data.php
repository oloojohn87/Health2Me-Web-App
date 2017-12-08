<?php
//session_start();
require("environment_detail.php");
//require("h2pdf.php");

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 
// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$idPatient = $_GET['idp'];
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
$othermed = $_GET['other'];
$fatheralive = $_GET['fatheralive'];
$fcod = $_GET['fcod'];
$faod = $_GET['faod'];
$frd = $_GET['frd'];
$motheralive = $_GET['motheralive'];
$mcod = $_GET['mcod'];
$maod = $_GET['maod'];
$mrd = $_GET['mrd'];
$siblingsrd = $_GET['srd'];

$phone = $_GET['phone'];
$insurance = $_GET['insurance'];
$dob = $_GET['dob'];
$name = $_GET['name'];
$surname = $_GET['surname'];
$initial = $_GET['initial'];
$gender = $_GET['gender'];



if(!$faod)
{
	$faod='null';
}

if(!$maod)
{
	$maod = 'null';
}





$query = $con->prepare("update usuarios set name=?,surname=?,mi=?,sexo=? where identif=?");
$query->bindValue(1, $name, PDO::PARAM_STR);
$query->bindValue(2, $surname, PDO::PARAM_STR);
$query->bindValue(3, $initial, PDO::PARAM_STR);
$query->bindValue(4, $gender, PDO::PARAM_INT);
$query->bindValue(5, $idPatient, PDO::PARAM_INT);


if($query->execute())
{










/*
$query = "update usuarios set sexo=$sexo where identif=$idPatient";
mysql_query($query); 
*/

//$query = "insert into basicemrdata values($idPatient,'$dob','$address','$address2','$city','$state','$country','$notes','$fractures','$surgeries','$otherknown','$obstetric','$othermed',$fatheralive,'$fcod',$faod,'$frd',$motheralive,'$mcod',$maod,'$mrd','$siblingsrd')";

$query = $con->prepare("update basicemrdata set dob=?,address=?,address2=?,city=?,state=?,country=?,notes=?,fractures=?,surgeries=?,obstetric=?,otherknown=?,othermed=?,fatheralive=?,fathercod=?,fatheraod=?,fatherrd=?,motheralive=?,mothercod=?,motheraod=?,motherrd=?,siblingsrd=?,phone=?,insurance=? where idpatient=?");
$query->bindValue(1, $dob, PDO::PARAM_STR);
$query->bindValue(2, $address, PDO::PARAM_STR);
$query->bindValue(3, $address2, PDO::PARAM_STR);
$query->bindValue(4, $city, PDO::PARAM_STR);
$query->bindValue(5, $state, PDO::PARAM_STR);
$query->bindValue(6, $country, PDO::PARAM_STR);
$query->bindValue(7, $notes, PDO::PARAM_STR);
$query->bindValue(8, $fractures, PDO::PARAM_STR);
$query->bindValue(9, $surgeries, PDO::PARAM_STR);
$query->bindValue(10, $obstetric, PDO::PARAM_STR);
$query->bindValue(11, $otherknown, PDO::PARAM_STR);
$query->bindValue(12, $othermed, PDO::PARAM_STR);
$query->bindValue(13, $fatheralive, PDO::PARAM_INT);
$query->bindValue(14, $fcod, PDO::PARAM_STR);
$query->bindValue(15, $faod, PDO::PARAM_INT);
$query->bindValue(16, $frd, PDO::PARAM_STR);
$query->bindValue(17, $motheralive, PDO::PARAM_INT);
$query->bindValue(18, $mcod, PDO::PARAM_STR);
$query->bindValue(19, $maod, PDO::PARAM_INT);
$query->bindValue(20, $mrd, PDO::PARAM_STR);
$query->bindValue(21, $siblingsrd, PDO::PARAM_STR);
$query->bindValue(22, $phone, PDO::PARAM_STR);
$query->bindValue(23, $insurance, PDO::PARAM_STR);
$query->bindValue(24, $idPatient, PDO::PARAM_INT);




//echo $query;


																																													



if($query->execute())
{

	echo 'success';
	//create_emr_pdf($idPatient,$_SESSION['MEDID']);
	return;
}
echo 'failure';
}
else
{
	echo 'failure';
}

?>