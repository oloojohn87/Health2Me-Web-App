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
$dob = $_GET['dob'];
$sexo = $_GET['gender']; //to be updated in usuarios
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

if(!$faod)
{
	$faod='null';
}

if(!$maod)
{
	$maod = 'null';
}


$query = $con->prepare("update usuarios set sexo=? where identif=?");
$query->bindValue(1, $sexo, PDO::PARAM_INT);
$query->bindValue(2, $idPatient, PDO::PARAM_INT);
$query->execute();

//Commented by Pallab 
//$query = "insert into basicemrdata values($idPatient,'$dob','$address','$address2','$city','$state','$country','$notes','$fractures','$surgeries','$otherknown','$obstetric','$othermed',$fatheralive,'$fcod',$faod,'$frd',$motheralive,'$mcod',$maod,'$mrd','$siblingsrd','$phone','$insurance')";

// New query added by Pallab, the above query was drawing errors
$query = $con->prepare("insert into basicemrdata (IdPatient,DOB,Address,Address2,City,state,country,Notes,fractures,surgeries,otherknown,obstetric,otherMed,fatherAlive,fatherCoD,fatherAoD,fatherRD,motherAlive,MotherCoD,motherAoD,motherRD,siblingsRD,phone,insurance) 
values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
$query->bindValue(1, $idPatient, PDO::PARAM_INT);
$query->bindValue(2, $dob, PDO::PARAM_STR);
$query->bindValue(3, $address, PDO::PARAM_STR);
$query->bindValue(4, $address2, PDO::PARAM_STR);
$query->bindValue(5, $city, PDO::PARAM_STR);
$query->bindValue(6, $state, PDO::PARAM_STR);
$query->bindValue(7, $country, PDO::PARAM_STR);
$query->bindValue(8, $notes, PDO::PARAM_STR);
$query->bindValue(9, $fractures, PDO::PARAM_STR);
$query->bindValue(10, $surgeries, PDO::PARAM_STR);
$query->bindValue(11, $otherknown, PDO::PARAM_STR);
$query->bindValue(12, $obstetric, PDO::PARAM_STR);
$query->bindValue(13, $othermed, PDO::PARAM_STR);
$query->bindValue(14, $fatheralive, PDO::PARAM_INT);
$query->bindValue(15, $fcod, PDO::PARAM_STR);
$query->bindValue(16, $faod, PDO::PARAM_INT);
$query->bindValue(17, $frd, PDO::PARAM_STR);
$query->bindValue(18, $motheralive, PDO::PARAM_INT);
$query->bindValue(19, $mcod, PDO::PARAM_STR);
$query->bindValue(20, $maod, PDO::PARAM_INT);
$query->bindValue(21, $mrd, PDO::PARAM_STR);
$query->bindValue(22, $siblingsrd, PDO::PARAM_STR);
$query->bindValue(23, $phone, PDO::PARAM_STR);
$query->bindValue(24, $insurance, PDO::PARAM_STR);

//echo $query;


if($query->execute())
{

	echo 'success';
    
	//create_emr_pdf($idPatient,$_SESSION['MEDID']);
	return;
}
echo 'failure';


?>