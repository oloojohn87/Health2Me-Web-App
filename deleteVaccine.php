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

$id = $_GET['id'];

if($_SESSION['isPatient']==1)
{
	$edited=0;
}
else
{
	$edited = $_SESSION['MEDID'];
}



$query = $con->prepare("update p_immuno set deleted=1,edited=? where id=?");
$query->bindValue(1, $edited, PDO::PARAM_INT);
$query->bindValue(2, $id, PDO::PARAM_INT);
$result = $query->execute();

$count = $query->rowCount();

?>