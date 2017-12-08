<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 
 

$tbl_name="messages"; // Table name

	

$id=$_GET['id'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			
  
$result = $con->prepare("Update doctorslinkdoctors set Archive=1 where id=?");
$result->bindValue(1, $id, PDO::PARAM_INT);
$result->execute();

//mysql_query("Update referral_stage set Archive=1 referral_id='$id'");
//mysql_query("delete from message_infrasture where connection_id='$id'");


?>