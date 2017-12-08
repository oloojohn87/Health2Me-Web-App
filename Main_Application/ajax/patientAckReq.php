<?php
define('INCLUDE_CHECK',1);
require "logger.php";
require("environment_detail.php");

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


$tbl_name="doctorslinkusers"; // Table name

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		


$Token = $_GET['Confirm'];

$result = $con->prepare("SELECT * FROM doctorslinkusers where Confirm=?");
$result->bindValue(1, $Token, PDO::PARAM_STR);
$result->execute();

$countA=$result->rowCount();
$rowA = $result->fetch(PDO::FETCH_ASSOC);
$successC ='NO';
if($countA==1){
	$successC ='YES';
	$IdEntry = $rowA['id'];
	$IdUsu = $rowA['IdUs'];
	$TempoEmail = $rowA['TemporaryEmail'];
	$TempoPhone = $rowA['TemporaryPhone'];
	$TempoPass = $rowA['TempoPass'];
	$q = $con->prepare("UPDATE doctorslinkusers SET Confirm = '*******' , Fecha = NOW(), estado = '2' WHERE id =? ");  
	$q->bindValue(1, $IdEntry, PDO::PARAM_INT);
	$q->execute();
	
	$q = $con->prepare("UPDATE usuarios SET TempoPass = ?, email=?, telefono=? WHERE Identif =? ");   
	$q->bindValue(1, $TempoPass, PDO::PARAM_STR);
	$q->bindValue(2, $TempoEmail, PDO::PARAM_STR);
	$q->bindValue(3, $TempoPhone, PDO::PARAM_STR);
	$q->bindValue(4, $IdUsu, PDO::PARAM_INT);
	$q->execute();
	
	
	echo '<h2 style="color:blue;">Health2Me Patient Portal</h2>';
	echo '<h3>Connection Confirmed</h3>';
	echo '<p style="font-size:8px;">********************* (Entry = '.$IdEntry.')</p>';
}
else
{
echo "Confirmation token not valid.";
if ($Token!='1111') die;
}


?>