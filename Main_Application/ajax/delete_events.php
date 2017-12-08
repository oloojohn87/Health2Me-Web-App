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

/* Values received via ajax */
$id = $_GET['id'];

// connection to the database
$sql = $con->prepare("delete from events where id=?");
$sql->bindValue(1, $id, PDO::PARAM_INT);
$sql->execute();

if($sql)
	echo 'success';
else
	echo 'failure';
	
	
?>