<?php
//ini_set("display_errors", 0);
require("environment_detailForLogin.php");
require("PasswordHash.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

//$allow = array('107.21.7.32', '54.86.146.59');

//if(!in_array($_SERVER['REMOTE_ADDR'], $allow)){
//die("This is a not a dedicated AARP IP Address!");
//}



// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			


$agent = $_POST['agent'];
$password = $_POST['password'];
$password_repeat = $_POST['password_repeat'];
$security = $_POST['security'];

if($_POST['password'] == $_POST['password_repeat']){
$password_mismatch = 0;
$submit_date = date("Y-m-d");
  
  $blowfish_pre = '$2a$12$';
  $blowfish_end = '$';
  
  $allowed_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./';
  $chars_len = 63;
  
  $salt_length = 21;
  $salt= "";
  
  for($i = 0; $i < $salt_length; $i++){
  $salt .= $allowed_chars[mt_rand(0,$chars_len)];
  }
  
  $bcrypt_salt = $blowfish_pre . $salt . $blowfish_end;
  
  $hashed_password = crypt($password, $bcrypt_salt);
}else{
$password_mismatch = 1;
}

if($security != "Reset Password"){
die("You have not entered the correct security code to change agent passwords. Please contact Health2.me support.");
}



// Check IDs in H2M User Database
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];


$signupdate = date("Y-m-d H:i:s");

if($_POST['agent'] != "" && $_POST['password'] != "" && $_POST['password_repeat'] != "" && $_POST['security'] != "" && $password_mismatch == 0){
	$q = $con->prepare("UPDATE agents SET password = ?, salt = ? WHERE username=?"); //, Password='$pass'     
	$q->bindValue(1, $hashed_password, PDO::PARAM_STR);
	$q->bindValue(2, $salt, PDO::PARAM_STR);
	$q->bindValue(3, $agent, PDO::PARAM_STR);
	$q->execute();
$data_success = 1;



	if($q){	
	} else{
		$value = 'ERROR';
		$logvalue='MySQL insertion error';
	}


}else{
$data_success = 0;
}
	
	if($data_success == 1){
$json_return = '';
$json_return.='
	{
        "CODE":"1",
        "SUCCESS":"Agent password has been successfully changed."
    }';  
	echo $json_return;
	}
	
	if($password_mismatch == 1){
$json_return = '';
$json_return.='
	{
        "CODE":"102",
        "ERROR":"The passwords entered do not match."
    }';  
	echo $json_return;
	}
	
	
	if($_POST['security'] == ""){
$json_return = '';
$json_return.='
	{
        "CODE":"306",
        "MISSING":"Security code is required."
    }';  
	echo $json_return;
	}
?>
