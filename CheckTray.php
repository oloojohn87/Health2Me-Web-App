<?php
require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$tbl_name="usuarios"; // Table name

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$doctor_id = $_GET['doctor_id'];

$sql="SELECT *,DATEDIFF(CURDATE(), DateTimeStamp) AS DateDif, TIME_TO_SEC(TIMEDIFF(NOW(), DateTimeStamp)) AS TimeDif, NOW() AS ActualDate  FROM onscreentray where IdDoctor='$doctor_id' AND MRead='0' ";
//$sql="SELECT * FROM $tbl_name WHERE id = '$queUsu'";

try {
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->query($sql);  
	$employees = $stmt->fetchAll(PDO::FETCH_OBJ);
	$dbh = null;
	echo '{"items":'. json_encode($employees) .'}'; 
} catch(PDOException $e) {
	echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	
}

$result = $con->prepare("UPDATE onscreentray SET MRead='1' where IdDoctor=? AND MRead='0' ");
$result->bindValue(1, $doctor_id, PDO::PARAM_INT);
$result->execute();



?>