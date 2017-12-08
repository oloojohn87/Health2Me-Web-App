<?php
require("environment_detail.php");

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
  
$account = $_POST['mem_id'];

$query = $con->prepare("UPDATE usuarios SET suspended = 1 WHERE Identif = ?");
$query->bindValue(1,$account,PDO::PARAM_INT);
$result = $query->execute();

if($result){
	echo '1';
}else{
	echo '0';
}

?>
