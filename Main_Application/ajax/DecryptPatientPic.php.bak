<?php
 session_start();
require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$UserId = $_GET['UserId'];
$pass=$_SESSION['decrypt'];	

 $fileName = "PatientImage/".$UserId.".jpg";
 if(file_exists($fileName))
 {
	shell_exec('Decrypt_Image.bat PatientImage '.$UserId.'.jpg '.$_SESSION['MEDID'].' '.$pass.' 2>&1');
	//echo 'Decrypt_Image.bat PatientImage '.$USERID.'.jpg '.$_SESSION['MEDID'].' '.$pass.' 2>&1';
	$file = "temp/".$_SESSION['MEDID']."/".$UserId.".jpg";
	$style = "max-height: 80px; max-width:80px;";
 }
 else
 {
	$file = "/PatientImage/defaultDP.jpg";
	$style = "max-height: 80px; max-width:80px;";
 }

echo $file;

?>