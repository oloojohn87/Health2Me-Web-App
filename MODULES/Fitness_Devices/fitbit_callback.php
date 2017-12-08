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

if(isset($_GET['user']) && isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])){
	$user_id = $_GET['user'];

	$query = $con->prepare("UPDATE usuarios SET fitbit_oauth_token = ?, fitbit_oauth_verifier = ? WHERE Identif=?");
	$query->bindValue(1, $_GET['oauth_token'], PDO::PARAM_STR);
	$query->bindValue(2, $_GET['oauth_verifier'], PDO::PARAM_STR);
	$query->bindValue(3, $user_id, PDO::PARAM_INT);
	$result = $query->execute();
	
	if($result){
		echo "Your account has been linked.  Thank you for using Health2.me!";
	}

}else{
	echo "Something went wrong.  We were not able to link your fitbit account.";
}

?>
