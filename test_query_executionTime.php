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

$IdUsu = 1313;

$msc=microtime(true);
$resultPRE = $con->prepare("select count(*) as count from lifepin where idusu = 1313");
$resultPRE->bindValue(1, $IdUsu, PDO::PARAM_INT);
$resultPRE->execute();
$msc=microtime(true)-$msc;
echo $msc.' seconds';

?>

