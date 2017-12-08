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
$groupid=$_GET['groupid'];
$docid=$_GET['docid'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$result = $con->prepare("delete from doctorsgroups where id=? and idGroup=?");
$result->bindValue(1, $id, PDO::PARAM_INT);
$result->bindValue(2, $groupid, PDO::PARAM_INT);
$result->execute();

$result = $con->prepare("delete from group_roles where Idmed=? and groupid=?");
$result->bindValue(1, $docid, PDO::PARAM_INT);
$result->bindValue(2, $groupid, PDO::PARAM_INT);
$result->execute();

/*mysql_query("insert into canceled_referrals select d.*,$docid from doctorslinkdoctors d where id='$id'"); 
mysql_query("delete from doctorslinkdoctors where id='$id'");
mysql_query("delete from referral_stage where referral_id='$id'");
//mysql_query("delete from message_infrasture where connection_id='$id'");
//echo 'success';*/

?>