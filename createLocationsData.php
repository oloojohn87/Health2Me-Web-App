<?php
session_start();
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			

 
if(isset($_GET['practice'])){
$doc_id = $_GET['docid'];
$practice_name = $_GET['practice'];


$query = $con->prepare("INSERT INTO script_practices SET name = ?, doc_owner = ?");
$query->bindValue(1, $practice_name, PDO::PARAM_STR);
$query->bindValue(2, $doc_id, PDO::PARAM_INT);
$result = $query->execute();

}else{

$clinic_name = $_GET['cname'];
$address1 = $_GET['address1'];
$address2 = $_GET['address2'];
$city = $_GET['city'];
$state = $_GET['state'];
$zip = $_GET['zip'];
$phone = $_GET['phone'];
$fax = $_GET['fax'];
$doc_id = $_GET['docid'];
$practice_id = $_GET['pid'];


$query = $con->prepare("INSERT INTO script_locations SET clinic_name = ?, address1 = ?, address2 = ?, city = ?, state = ?, zip = ?, phone = ?, fax = ?, doc_owner = ?, practice_id = ?");
$query->bindValue(1, $clinic_name, PDO::PARAM_STR);
$query->bindValue(2, $address1, PDO::PARAM_STR);
$query->bindValue(3, $address2, PDO::PARAM_STR);
$query->bindValue(4, $city, PDO::PARAM_STR);
$query->bindValue(5, $state, PDO::PARAM_STR);
$query->bindValue(6, $zip, PDO::PARAM_STR);
$query->bindValue(7, $phone, PDO::PARAM_STR);
$query->bindValue(8, $fax, PDO::PARAM_STR);
$query->bindValue(9, $doc_id, PDO::PARAM_INT);
$query->bindValue(10, $practice_id, PDO::PARAM_INT);
$result = $query->execute();
}
?>