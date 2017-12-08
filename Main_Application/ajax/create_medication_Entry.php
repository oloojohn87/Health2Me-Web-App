<?php
session_start();
require("environment_detail.php");
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

$idp = $_GET['idp'];
$drugname = $_GET['drugname'];
$drugcode = $_GET['drugcode'];
$dossage = $_GET['dossage'];
$numperday = $_GET['numperday'];
$start = $_GET['start'];
$end = $_GET['end'];

if($numperday=='')
{
	$numperday='null';
}



$query = $con->prepare("insert into medications(idpatient,name,drugcode,dossage,numberday,datestart,datestop) values(?,?,?,?,?,?,?)");
$query->bindValue(1, $idp, PDO::PARAM_INT);
$query->bindValue(2, $drugname, PDO::PARAM_STR);
$query->bindValue(3, $drugcode, PDO::PARAM_STR);
$query->bindValue(4, $dossage, PDO::PARAM_STR);
$query->bindValue(5, $numperday, PDO::PARAM_INT);
$query->bindValue(6, $start, PDO::PARAM_STR);
$query->bindValue(7, $end, PDO::PARAM_STR);


//echo $query;
if($query->execute())
{
	echo 'success';
}

?>