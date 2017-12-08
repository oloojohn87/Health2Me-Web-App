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

 
 
$idpatient = $_SESSION['UserID'];
$iddoctor = $_SESSION['MEDID'];
if ($iddoctor == $idpatient) $idauthor = -1; else $idauthor = $iddoctor;


$bill_name = $_GET['billname'];
$bill_date = $_GET['billdate'];
$select_type = $_GET['selecttype'];
$invoice_amount = $_GET['invoiceamount'];
$taxes = $_GET['taxes'];
$fees = $_GET['fees'];
$paid = $_GET['paid'];
$superid = $_GET['superid'];
$cpt = $_GET['cpt'];
$mod1 = $_GET['mod1'];
$mod2 = $_GET['mod2'];
$mod3 = $_GET['mod3'];
$mod4 = $_GET['mod4'];

$query = $con->prepare("INSERT INTO bill SET name=?, bill_date=?, bill_type=?, invoice=?, taxes=?, fees=?, paid=?, member_id=?, created_date=NOW(), super_id=?, cpt=?, mod1=?, mod2=?, mod3=?, mod4=?");
$query->bindValue(1, $bill_name, PDO::PARAM_STR);
$query->bindValue(2, $bill_date, PDO::PARAM_STR);
$query->bindValue(3, $select_type, PDO::PARAM_INT);
$query->bindValue(4, ($invoice_amount*100), PDO::PARAM_STR);
$query->bindValue(5, ($taxes*100), PDO::PARAM_STR);
$query->bindValue(6, ($fees*100), PDO::PARAM_STR);
$query->bindValue(7, ($paid*100), PDO::PARAM_STR);
$query->bindValue(8, $idpatient, PDO::PARAM_INT);
$query->bindValue(9, $superid, PDO::PARAM_INT);
$query->bindValue(10, $cpt, PDO::PARAM_STR);
$query->bindValue(11, $mod1, PDO::PARAM_STR);
$query->bindValue(12, $mod2, PDO::PARAM_STR);
$query->bindValue(13, $mod3, PDO::PARAM_STR);
$query->bindValue(14, $mod4, PDO::PARAM_STR);
$result = $query->execute();


?>