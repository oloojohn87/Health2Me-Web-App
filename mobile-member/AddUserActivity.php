<?php
header("Access-Control-Allow-Origin: *");

 
 require("../environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="user_activity"; // Table name

$userid=$_GET['userid'];
$doctorid=$_GET['doctorid'];
$type=$_GET['type'];
$status=$_GET['status'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$date = date('Y-m-d H:i:s');

$result = $con->prepare("insert into user_activity set IdUser=?, IdDoctor=?, Type=?, Status=?, Date=? ");
$result->bindValue(1, $userid, PDO::PARAM_INT);
$result->bindValue(2, $doctorid, PDO::PARAM_INT);
$result->bindValue(3, $type, PDO::PARAM_INT);
$result->bindValue(4, $status, PDO::PARAM_INT);
$result->bindValue(5, $date, PDO::PARAM_INT);
$result->execute();


?>