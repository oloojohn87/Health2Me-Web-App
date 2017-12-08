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
$email = $_POST['email'];
$security = $_POST['security'];
$facility = $_POST['facility'];

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

if($security != "security"){
echo $security;
die("You have not entered the correct security code to add agents. Please contact Health2.me support.");
}

$counter1 = 0;

    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
        
		if (preg_match($regex, $email)) {
//     echo $email . " is a valid email. We can accept it.</br>";
	 $valid_email = 1;
} else { 
//     echo $email . " is an invalid email. Please try again.</br>";
	 $valid_email = 0;
} 

$check_dupe = $con->prepare("SELECT * FROM agents WHERE username = ?");
$check_dupe->bindValue(1, $agent, PDO::PARAM_STR);
$check_dupe->execute();

$checked_dupe = $check_dupe->fetch(PDO::FETCH_ASSOC);

if($checked_dupe['username'] == $_POST['agent']){
$is_dupe = 1;
}else{
$is_dupe = 0;
}



// Check IDs in H2M User Database
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];


$signupdate = date("Y-m-d H:i:s");

if($valid_email == 1  && $_POST['agent'] != "" && $_POST['password'] != "" && $_POST['password_repeat'] != "" && $_POST['security'] != "" && $_POST['facility'] != "" && $password_mismatch == 0 && $is_dupe == 0){
	$q = $con->prepare("INSERT INTO agents SET username = ?, password = ?, salt = ?, t_registered = '0', email = ?, created_date = ?, facility = ?"); //, Password='$pass'   
	$q->bindValue(1, $agent, PDO::PARAM_STR);
	$q->bindValue(2, $hashed_password, PDO::PARAM_STR);
	$q->bindValue(3, $salt, PDO::PARAM_STR);
	$q->bindValue(4, $email, PDO::PARAM_STR);
	$q->bindValue(5, $submit_date, PDO::PARAM_STR);
	$q->bindValue(6, $facility, PDO::PARAM_STR);
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
if($valid_email == 0){
$json_return = '';
$json_return.='
	{
        "CODE":"101",
        "ERROR":"Email is not valid."
    }';  
	echo $json_return;
	}
	
	if($data_success == 1){
$json_return = '';
$json_return.='
	{
        "CODE":"1",
        "SUCCESS":"Agent has been successfully added to the system."
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
	
	if($is_dupe == 1){
$json_return = '';
$json_return.='
	{
        "CODE":"103",
        "ERROR":"This agent ID is already in the system."
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