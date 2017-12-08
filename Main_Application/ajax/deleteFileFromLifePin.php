<?php
require("environment_detailForLogin.php");
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

$filename = $_GET['filename'];

$file = "$filename";

$query = $con->prepare("select max(idpin) as maxId from lifepin where orig_filename = ?");
$query->bindValue(1, $filename, PDO::PARAM_STR);
$query->execute();

$row = $query->fetch(PDO::FETCH_ASSOC);
$idpin = $row['maxId'];

$query1 = $con->prepare("delete from lifepin where orig_filename = ? and idpin = ?");
$query1->bindValue(1, $filename, PDO::PARAM_STR);
$query1->bindValue(2, $idpin, PDO::PARAM_INT);
$query1->execute();


unlink("C:\\xampp\\htdocs\\dropzone_uploads\\temporaryForFilePreview\\$file");


?>