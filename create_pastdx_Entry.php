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
$dxname = $_GET['dxname'];
$icdcode = $_GET['icdcode'];
$dxstart = $_GET['dxstart'];
$dxend = $_GET['dxend'];

$query = $con->prepare("insert into pastdx(idpatient,name,icdcode,datestart,datestop) values(?,?,?,?,?)");
$query->bindValue(1, $idp, PDO::PARAM_INT);
$query->bindValue(2, $dxname, PDO::PARAM_STR);
$query->bindValue(3, $icdcode, PDO::PARAM_STR);
$query->bindValue(4, $dxstart, PDO::PARAM_STR);
$query->bindValue(5, $dxend, PDO::PARAM_STR);



if($query->execute())
{
	echo 'success';
}



?>