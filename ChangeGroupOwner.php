<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 
 

$tbl_name="messages"; // Table name

	

$groupid=$_GET['groupid'];
$docid=$_GET['docid'];

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$count=0;
$result=$con->prepare("update groups set owner=? where id=?");
$result->bindValue(1, $docid, PDO::PARAM_INT);
$result->bindValue(2, $groupid, PDO::PARAM_INT);
$result->execute();

$count=$result->rowCount();

//mysql_query("delete from doctorslinkdoctors where id='$id'");
//mysql_query("delete from referral_stage where referral_id='$id'");
//mysql_query("delete from message_infrasture where connection_id='$id'");
//echo 'success';

?>