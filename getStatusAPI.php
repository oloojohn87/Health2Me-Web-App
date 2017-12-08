<?php
//error_reporting(E_ALL);
ini_set('display_errors', '1');
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



$email = $_GET['email'];
$security = $_GET['security'];
$agent = $_GET['agent'];
$user_id = $_GET['user_id'];

$result = $con->prepare("SELECT salt, password FROM agents WHERE username = ?");
$result->bindValue(1, $agent, PDO::PARAM_STR);
$result->execute();

  $row = $result->fetch(PDO::FETCH_ASSOC);
  
  $blowfish_pre = '$2a$12$';
  $blowfish_end = '$';
  
  $hashed_pass = crypt($security, $blowfish_pre . $row['salt'] . $blowfish_end);

if($hashed_pass != $row['password']){
die("You have not entered the correct security code to request member status. Please contact Health2.me support.");
}

if(isset($_GET['user_id']) && !isset($_GET['email'])){
	$result2 = $con->prepare("SELECT * FROM usuarios WHERE Identif = ?");
	$result2->bindValue(1, $_GET['user_id'], PDO::PARAM_INT);
	$result2->execute();
}

if(!isset($_GET['user_id']) && isset($_GET['email'])){
	$result2 = $con->prepare("SELECT * FROM usuarios WHERE email = ?");
	$result2->bindValue(1, $_GET['email'], PDO::PARAM_INT);
	$result2->execute();
}

$row2 = $result2->fetch(PDO::FETCH_ASSOC);
$requested_status_count = $result2->rowCount();

if($row2['IPVALID'] == null){
	$status = 'Never Logged In';
}else{
	$status = 'Last Login from IP:'.$row2['IPVALID'];
}

if($requested_status_count == 1){
	$json_return = '';
	$json_return.='
	{
        "RESPONSE":"SUCCESS",
        "ID":"'.$row2['Identif'].'",
        "USER":"'.$row2['Name'].' '.$row2['Surname'].'",
        "EMAIL":"'.$row2['email'].'",
        "CREATED":"'.$row2['SignUpDate'].'",
        "STATUS":"'.$status.'"
    }';  
	}
	
if($_POST['email'] == '' && $_POST['user-id'] == ''){
	$json_return = '';
	$json_return.='
	{
        "CODE":"101",
        "ERROR":"You must enter an email or member ID."
    }';  
	}
	
if($_POST['email'] != '' && $_POST['user-id'] != ''){
	$json_return = '';
	$json_return.='
	{
        "CODE":"102",
        "ERROR":"You cannot enter both email and member ID."
    }';  
	}
	
if($requested_status_count == 0){
	$json_return = '';
	$json_return.='
	{
        "CODE":"201",
        "ERROR":"We cannot locate the member by id or email."
    }';  
	}
	
echo $json_return;
?>
