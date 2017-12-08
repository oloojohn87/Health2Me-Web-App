<?php

require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

   $userId = $_GET['userId'];

   $result = $con->prepare("SELECT distinct(receiver_id) as doctorIds FROM message_infrastructureuser WHERE patient_id = ? && subject = 'Send Mail' and content = 'Mail From Patient'");
   $result->bindValue(1, $userId, PDO::PARAM_INT);
   $result->execute();
   $row = $result->fetch(PDO::FETCH_ASSOC);

   echo json_encode(array("DoctorIds" => $row));

?>