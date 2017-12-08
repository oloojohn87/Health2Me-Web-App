<?php

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

     $userId = $_POST['userId'];
     $doctorId = $_POST['doctorId'];
     $messageId = $_POST['messageId'];
     
    echo $userId." ".$doctorId." ".$messageId; 
    $query = $con->prepare("update message_infrastructureuser set status = 'read' where receiver_id = ? and patient_id = ? and message_id = ?");
	$query->bindValue(1, $doctorId, PDO::PARAM_INT);
	$query->bindValue(2, $userId, PDO::PARAM_INT);
	$query->bindValue(3, $messageId, PDO::PARAM_INT);
	$query->execute();
	

?>
    

