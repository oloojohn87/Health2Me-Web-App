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

 


$patientrxid = $_POST['patientrxid'];
$provideraccid = $_POST['provideraccid'];
$providername = $_POST['providername'];
$otherprescriber = $_POST['otherprescriber'];
$locationacctid = $_POST['locationacctid'];
$rxdate = $_POST['rxdate'];
$drug = $_POST['drug'];
$drugid = $_POST['drugid'];
$druggenericid = $_POST['druggenericid'];
$deaclass = $_POST['deaclass'];
$drugname = $_POST['drugname'];
$dosage_id = $_POST['dosage_id'];
$dosage = $_POST['dosage'];
$route_id = $_POST['route_id'];
$route = $_POST['route'];
$doseform_id = $_POST['doseform_id'];
$dosageform = $_POST['dosageform'];
$supplyflag = $_POST['supplyflag'];
$compoundflag = $_POST['compundflag'];
$directions = $_POST['directions'];
$dispnumber = $_POST['dispnumber'];
$dispunitcode = $_POST['dispunitcode'];
$dispunitname = $_POST['dispunitname'];
$refills = $_POST['refills'];
$dayssupply = $_POST['dayssupply'];
$suballowedflag = $_POST['suballowedflag'];
$pharmacynote = $_POST['pharmacynote'];
$internalcomment = $_POST['internalcomment'];
$formularystatus = $_POST['formularystatus'];
$interactiondispflag = $_POST['interactiondispflag'];




$query = $con->prepare("INSET INTO scripts_written SET patientrxid = ?, provideraccid = ?, providername = ?, otherprescriber = ?, locationacctid = ?, rxdate = ?, drug = ?, drugid = ?, druggenericid = ?, deaclass = ?, drugname = ?, dosage_id = ?,
dosage = ?, route_id = ?, route = ?, doseform_id = ?, dosageform = ?, supplyflag = ?, compoundflag = ?, directions = ?, dispnumber = ?, dispunitname = ?, refills = ?, dayssupply = ?, suballowedflag = ?, pharmacynote = ?, internalcomment = ?, formularystatus = ?, interactiondispflag = ?");
$query->bindValue(1, $patientrxid, PDO::PARAM_INT);
$query->bindValue(2, $provideraccid, PDO::PARAM_INT);
$query->bindValue(3, $providername, PDO::PARAM_STR);
$query->bindValue(4, $otherprescriber, PDO::PARAM_STR);
$query->bindValue(5, $locationacctid, PDO::PARAM_INT);
$query->bindValue(6, $rxdate, PDO::PARAM_STR);
$query->bindValue(7, $drug, PDO::PARAM_STR);
$query->bindValue(8, $drugid, PDO::PARAM_INT);
$query->bindValue(9, $druggenericid, PDO::PARAM_INT);
$query->bindValue(10, $deaclass, PDO::PARAM_STR);
$query->bindValue(11, $drugname, PDO::PARAM_STR);
$query->bindValue(12, $dosage_id, PDO::PARAM_INT);
$query->bindValue(13, $dosage, PDO::PARAM_INT);
$query->bindValue(14, $route_id, PDO::PARAM_INT);
$query->bindValue(15, $route, PDO::PARAM_STR);
$query->bindValue(16, $doseform_id, PDO::PARAM_INT);
$query->bindValue(17, $dosageform, PDO::PARAM_STR);
$query->bindValue(18, $supplyflag, PDO::PARAM_INT);
$query->bindValue(19, $compoundflag, PDO::PARAM_INT);
$query->bindValue(20, $directions, PDO::PARAM_STR);
$query->bindValue(21, $dispnumber, PDO::PARAM_STR);
$query->bindValue(22, $dispunitcode, PDO::PARAM_STR);
$query->bindValue(23, $dispunitname, PDO::PARAM_STR);
$query->bindValue(24, $refills, PDO::PARAM_INT);
$query->bindValue(25, $dayssupply, PDO::PARAM_INT);
$query->bindValue(26, $suballowedflag, PDO::PARAM_INT);
$query->bindValue(27, $pharmacynote, PDO::PARAM_STR);
$query->bindValue(28, $internalcomment, PDO::PARAM_STR);
$query->bindValue(29, $formularystatus, PDO::PARAM_STR);
$query->bindValue(30, $interactiondispflag, PDO::PARAM_INT);
$result = $query->execute();
?>