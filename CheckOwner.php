<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 
 

$tbl_name="messages"; // Table name

$docid=$_GET['docid'];
$groupid=$_GET['groupid'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$result = $con->prepare("select owner from groups where id=? and owner=?");
$result->bindValue(1, $groupid, PDO::PARAM_INT);
$result->bindValue(2, $docid, PDO::PARAM_INT);
$result->execute();

$count = $result->rowCount();

if($count==0){
$result = $con->prepare("select Idmed from group_roles where groupid=? and Idmed=?");
$result->bindValue(1, $groupid, PDO::PARAM_INT);
$result->bindValue(2, $docid, PDO::PARAM_INT);
$result->execute();

$count = $result->rowCount();
}
echo $count;

?>