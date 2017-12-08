<?php

//   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
ini_set('display_errors',1);
error_reporting(E_ALL);

 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="user_activity"; // Table name

$idActivity = $_GET['idActivity'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$result = $con->prepare("update user_activity set Dismiss=1 where id=? ");
$result->bindValue(1, $idActivity, PDO::PARAM_INT);
$result->execute();

$count = $result->rowCount();
echo $count;

?>