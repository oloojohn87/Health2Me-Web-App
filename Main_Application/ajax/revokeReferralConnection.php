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
$docid=$_GET['docid'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$result = $con->prepare("insert into canceled_referrals select d.*,? from doctorslinkdoctors d where id=?"); 
$result->bindValue(1, $docid, PDO::PARAM_INT);
$result->bindValue(2, $id, PDO::PARAM_INT);
$result->execute();

$result = $con->prepare("delete from doctorslinkdoctors where id=?");
$result->bindValue(1, $id, PDO::PARAM_INT);
$result->execute();

$result = $con->prepare("delete from referral_stage where referral_id=?");
$result->bindValue(1, $id, PDO::PARAM_INT);
$result->execute();
//mysql_query("delete from message_infrasture where connection_id='$id'");
//echo 'success';

?>