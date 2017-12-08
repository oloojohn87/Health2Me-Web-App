<?php
define('INCLUDE_CHECK',1);
require "logger.php";
require("environment_detail.php");
require("PasswordHash.php");

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
$IdUsu = $_GET['IdUsu'];
$email = $_GET['email'];
$phone = $_GET['phone'];

$result = $con->prepare("SELECT * FROM usuarios where Identif=?");
$result->bindValue(1, $IdUsu, PDO::PARAM_INT);
$result->execute();

$countA=$result->rowCount();
$rowA = $result->fetch(PDO::FETCH_ASSOC);
$successC ='NO';
if($countA==1){
	$hashresult = explode(":", create_hash($Token));
	$IdUsRESERV= $hashresult[3];
	$additional_string=$hashresult[2];
	$successC ='YES';
	$IdEntry = $rowA['Identif'];
	$q = $con->prepare("UPDATE usuarios SET Password = ?, IdUsRESERV = ?, TempoPass='', email=?,telefono=?,salt=?,notify_email=1 WHERE Identif =? "); 
	$q->bindValue(1, $IdUsRESERV, PDO::PARAM_STR);
	$q->bindValue(2, $IdUsRESERV, PDO::PARAM_STR);
	$q->bindValue(3, $email, PDO::PARAM_STR);
	$q->bindValue(4, $phone, PDO::PARAM_STR);
	$q->bindValue(5, $additional_string, PDO::PARAM_STR);
	$q->bindValue(6, $IdUsu, PDO::PARAM_INT);
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
