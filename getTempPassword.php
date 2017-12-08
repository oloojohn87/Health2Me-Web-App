<?php
require("environment_detail.php");

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	
  
$owner = $_POST['id'];
  
$query = $con->prepare("SELECT * FROM key_chain WHERE owner = ? && type='register' ORDER BY id DESC LIMIT 1");
$query->bindValue(1, $owner, PDO::PARAM_INT);
$query->execute();

$row = $query->fetch(PDO::FETCH_ASSOC);

echo $row['short_hash'];
?>
