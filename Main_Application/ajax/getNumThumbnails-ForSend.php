<?php 
require("environment_detail.php");

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$Usuario = $_GET['Usuario'];

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$query = $con->prepare("select count(*) as count from lifepin where idusu = ?");
$query->bindValue(1, $Usuario, PDO::PARAM_INT);
$query->execute();

$row = $query->fetch(PDO::FETCH_ASSOC);

echo $row['count'];


?>