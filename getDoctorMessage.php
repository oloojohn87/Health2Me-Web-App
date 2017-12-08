<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("environment_detail.php");
 
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="messages"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$msg_id= $_GET['msgid'];

$result = $con->prepare("select * from message_infrastructureuser where message_id=?");
$result->bindValue(1, $msg_id, PDO::PARAM_INT);
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
		$IdDoctor=$row['sender_id'];
	}
$result = $con->prepare("select * from doctors where id=?");
$result->bindValue(1, $IdDoctor, PDO::PARAM_INT);
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC)){
		$NameDoctor=$row['Name'];
		$SurnameDoctor=$row['Surname'];
	}
	    
echo $IdDoctor.','.$NameDoctor.','.$SurnameDoctor;
    
?>


