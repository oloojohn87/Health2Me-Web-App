<?php
session_start();
require("environment_detail.php");
//require "logger.php";

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

$idpin = $_GET['idpin'];
$fecha = $_GET['fecha'];
$tipo  = $_GET['tipo'];
$idusu = $_GET['user'];

/*
$month = strtok($fecha,"/");
$day = strtok("/");
$year = strtok("/");

$fecha = $year.'-'.$month.'-'.$day;
*/

$query = $con->prepare("update lifepin set fecha=?,tipo=? ,vs=1 where idpin = ?");
$query->bindValue(1, $fecha, PDO::PARAM_STR);
$query->bindValue(2, $tipo, PDO::PARAM_INT);
$query->bindValue(3, $idpin, PDO::PARAM_INT);
$query->execute();


//mysql_query($query) or die('error');


	//Log that report has been verified
	$IdPin=$idpin;
	$content = "Report Verified";
	$VIEWIdUser=$idusu;
	$VIEWIdMed=$_SESSION['MEDID'];
	$ip=$_SERVER['REMOTE_ADDR'];
	$MEDIO=0;
	$q = $con->prepare("INSERT INTO bpinview  SET  IDPIN =?, Content=?, DateTimeSTAMP = NOW(), VIEWIdUser = ?, VIEWIdMed = ?, VIEWIP = ?, MEDIO = ? ");
	$q->bindValue(1, $IdPin, PDO::PARAM_INT);
	$q->bindValue(2, $content, PDO::PARAM_STR);
	$q->bindValue(3, $VIEWIdUser, PDO::PARAM_INT);
	$q->bindValue(4, $VIEWIdMed, PDO::PARAM_INT);
	$q->bindValue(5, $ip, PDO::PARAM_STR);
	$q->bindValue(6, $MEDIO, PDO::PARAM_INT);
	$q->execute();
	
	//LogBLOCKAMP ($IdPin, $content, $VIEWIdUser, $VIEWIdMed, $ip, $MEDIO);
	

echo 'success';


?>