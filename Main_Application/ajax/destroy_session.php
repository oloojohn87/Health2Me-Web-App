<?php
session_start();
ini_set("display_errors", 0);
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

$query = $con->prepare("select * from ongoing_sessions where userid=?");
$query->bindValue(1, $_SESSION['MEDID'], PDO::PARAM_INT);

$result=$query->execute();
$count=$query->rowCount();
if($count==1)
{
	shell_exec("logout.bat ".$_SESSION['MEDID']);
}

$query = $con->prepare("delete from ongoing_sessions where userid=? and ip=?");
$query->bindValue(1, $_SESSION['MEDID'], PDO::PARAM_INT);
$query->bindValue(2, $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);

$query->execute();

unset($_SESSION['Acceso']); 
//unset($_SESSION['decrypt']);

if(isset($_SESSION['UserID'])){
//unset($_SESSION['UserID']);
session_destroy();
}
?>