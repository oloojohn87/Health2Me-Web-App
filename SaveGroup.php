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
$grpname=$_GET['grpname'];
$grpadd=$_GET['grpadd'];
$grpzip=$_GET['grpzip'];
$grpcity=$_GET['grpcity'];
$grpstate=$_GET['grpstate'];
$grpcountry=$_GET['grpcountry'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		


$result = $con->prepare("update groups set Name=?,Address=?,ZIP=?,City=?,State=?,Country=? where id=?"); 
$result->bindValue(1, $grpname, PDO::PARAM_STR);
$result->bindValue(2, $grpadd, PDO::PARAM_STR);
$result->bindValue(3, $grpzip, PDO::PARAM_STR);
$result->bindValue(4, $grpcity, PDO::PARAM_STR);
$result->bindValue(5, $grpstate, PDO::PARAM_STR);
$result->bindValue(6, $grpcountry, PDO::PARAM_STR);
$result->bindValue(7, $groupid, PDO::PARAM_INT);
$result->execute();

//$groupid= mysql_insert_id();
//mysql_query("insert into doctorsgroups (idDoctor,idGroup, Fecha) values ('$docid','$groupid',NOW())"); 
echo 1;

?>