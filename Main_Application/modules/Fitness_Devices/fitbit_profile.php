<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require("../../environment_detailForLogin.php");

$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];

// Connect to server and select databse.
		$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		
		if (!$con){
			die('Could not connect: ' . mysql_error());
			}
			
if(isset($_GET['user_id'])){
	$user_id = $_GET['user_id'];
	
	$query = $con->prepare("SELECT * FROM usuarios WHERE Identif = ?");
	$query->bindValue(1, $user_id, PDO::PARAM_INT);
	$query->execute();
	
	$row = $query->fetch(PDO::FETCH_ASSOC);
	
	require '../fitbitphp-master/fitbitphp.php';

	$fitbit = new FitBitPHP('cd567983ef3d434488fe00f7b89c5f5f', 'b1f228c46b7f44edab503d307ab5610c');
	$fitbit->setOAuthDetails($row['fitbit_oauth_token'], $row['fitbit_oauth_verifier']);
	if($fitbit->sessionStatus() == 2){
		echo "authorized";
	}else{
		echo "not authorized";
	}
	$xml = $fitbit->getProfile();

	print_r($xml);
}else{
	echo "No user ID selected.";
}
?>
