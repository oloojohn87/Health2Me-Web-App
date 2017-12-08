<?php
define('INCLUDE_CHECK',1);
require "logger.php";
require("environment_detail.php");

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


$Tipo = $_GET['Tipo'];
$IdPac = $_GET['IdPac'];
$IdDoc = $_GET['IdDoc'];
$IdDocOrigin = $_GET['IdDocOrigin'];

$tbl_name="doctorslinkdoctors"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			
 
if($Tipo=='1'){
$getinfo=$con->prepare("select id from doctorslinkdoctors where IdMED=? and IdPac = ? and IdMED2=?");
$getinfo->bindValue(1, $IdDocOrigin, PDO::PARAM_INT);
$getinfo->bindValue(2, $IdPac, PDO::PARAM_INT);
$getinfo->bindValue(3, $IdDoc, PDO::PARAM_INT);
$getinfo->execute();

$count1=$getinfo->rowCount();
while ($row = $getinfo->fetch(PDO::FETCH_ASSOC)) {
    echo $row['id'];
}
}
 

?>