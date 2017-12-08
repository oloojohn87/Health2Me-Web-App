<?php
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

		// Connect to server and select databse.
		$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		
		if (!$con){
			die('Could not connect: ' . mysql_error());
			}
			
$user_id = $_POST['user_id'];
$type = $_POST['type'];


$result2 = $con->prepare("SELECT * FROM usuarios where Identif=?");
$result2->bindValue(1, $user_id, PDO::PARAM_INT);
$result2->execute();

//$count = $result->rowCount();
$row2 = $result2->fetch(PDO::FETCH_ASSOC);

if($type == 1 && $row2['telefono'] != ''){
	echo "1";
}elseif($type == 1){
	echo "0";
}elseif($type == 2 && $row2['telefono'] != ''){
	echo "1";
}elseif($type == 2){
	echo "0";
}elseif($type == 3 && $row2['email'] != ''){
	echo "1";
}else{
	echo "0";
}

?>
