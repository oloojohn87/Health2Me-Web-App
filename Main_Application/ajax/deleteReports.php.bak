<?php
//include 'config.php';

 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="lifepin"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$quePin = $_GET['IdPin'];
$state=$_GET['state'];

if($state){
$q2 = $con->prepare("update $tbl_name set markfordelete=1 WHERE IdPin = ?");
$q2->bindValue(1, $quePin, PDO::PARAM_INT);
$q2->execute();

//echo "$quePin";
}else{
$q2 = $con->prepare("delete from $tbl_name WHERE IdPin = ?");
$q2->bindValue(1, $quePin, PDO::PARAM_INT);
$q2->execute();

}
echo "$quePin";
?>


