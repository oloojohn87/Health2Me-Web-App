<?php

//   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="user_activity"; // Table name

$userid=$_GET['userid'];
$doctorid=$_GET['doctorid'];
$type='1';
$status='2';

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$date = date('Y-m-d H:i:s');

$result = $con->prepare("update user_activity set Type=?, Status=?, DateChange=? where IdUser=? and IdDoctor=? ");
$result->bindValue(1, $type, PDO::PARAM_INT);
$result->bindValue(2, $status, PDO::PARAM_INT);
$result->bindValue(3, $date, PDO::PARAM_INT);
$result->bindValue(4, $userid, PDO::PARAM_INT);
$result->bindValue(5, $doctorid, PDO::PARAM_INT);

$result->execute();


?>