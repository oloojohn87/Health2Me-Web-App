<?php
//include 'config.php';

 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];



$userid = $_GET['idusu'];

$sql="select DATE_FORMAT(date(dob),'%m-%d-%Y') as dob,address,address2,city,state,country,notes,fractures,surgeries,otherknown,obstetric,othermed,fatheralive,fathercod,fatheraod,fatherrd,motheralive,mothercod,motheraod,motherrd,siblingsrd,phone,insurance from basicemrdata where idpatient=$userid";
//echo $sql;




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



?>
