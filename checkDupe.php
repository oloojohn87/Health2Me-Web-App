<?php
require("environment_detail.php");
require("PasswordHash.php");

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
  
if(isset($_GET['type']) && $_GET['type'] == 'email'){
	$user_id = $_GET['user_id'];
	$email = $_POST['email'];

	$query = $con->prepare("SELECT * FROM usuarios WHERE email = ? && Identif != ?");
	$query->bindValue(1, $email, PDO::PARAM_STR);
	$query->bindValue(2, $user_id, PDO::PARAM_INT);
	$result = $query->execute();
	$count = $query->rowCount();

	if($count >= 1){
		echo "1";
	}else{
		echo "0";
	}
}

if(isset($_GET['type']) && $_GET['type'] == 'phone'){
	$user_id = $_GET['user_id'];
	$phone = $_POST['phone'];
	$phone = preg_replace('/[^0-9,]|,[0-9]*$/','',$phone);

	$query = $con->prepare("SELECT * FROM usuarios WHERE telefono LIKE ? && Identif != ?");
	$query->bindValue(1, '%'.$phone.'%', PDO::PARAM_STR);
	$query->bindValue(2, $user_id, PDO::PARAM_INT);
	$result = $query->execute();
	$count = $query->rowCount();

	if($count >= 1){
		echo "1";
	}else{
		echo "0";
	}
}

if(isset($_GET['update']) && $_GET['update'] == 'credentials'){
	$user_id = $_POST['user_id'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$pass1 = $_POST['pass1'];
	$pass2 = $_POST['pass2'];
	$phone = preg_replace('/[^0-9,]|,[0-9]*$/','',$phone);
	
	if($email != '' && $phone != '' && $pass1 != '' && $pass2 != '' && $pass1 == $pass2){
		$hashresult = explode(":", create_hash($pass1));
		$IdUsRESERV= $hashresult[3];
		$additional_string=$hashresult[2];
		
		$query = $con->prepare("UPDATE usuarios SET email = ?, telefono =?, IdUsRESERV = ?, salt = ? WHERE Identif = ?");
		$query->bindValue(1, $email, PDO::PARAM_STR);
		$query->bindValue(2, $phone, PDO::PARAM_STR);
		$query->bindValue(3, $IdUsRESERV, PDO::PARAM_STR);
		$query->bindValue(4, $additional_string, PDO::PARAM_STR);
		$query->bindValue(5, $user_id, PDO::PARAM_INT);
		$result = $query->execute();
	}
	if($result){
		echo "1";
	}else{
		echo "0";
	}
}
?>
