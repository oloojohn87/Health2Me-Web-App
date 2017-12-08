<?php
require("environment_detail.php");
require("NotificationClass.php");
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
  
$reports_to_show = $_POST['reports'];
$explode_reports = explode(" ", $reports_to_show);
  
foreach ($explode_reports as $exploded){
	$q = $con->prepare("UPDATE lifepin SET hide_from_member = 0 WHERE IdPin = ?");
	$q->bindValue(1, $exploded, PDO::PARAM_INT);
	$q->execute();

	$qt = $con->prepare("SELECT * FROM lifepin WHERE IdPin = ?");
	$qt->bindValue(1, $exploded, PDO::PARAM_INT);
	$qt->execute();

	$row = $qt->fetch(PDO::FETCH_ASSOC);

	$query = $con->prepare("SELECT * FROM usuarios WHERE Identif = ?");
	$query->bindValue(1, $row['IdUsu'], PDO::PARAM_INT);
	$query->execute();

	$row2 = $query->fetch(PDO::FETCH_ASSOC);
	$personal_doc = $row2['personal_doctor'];

}
	     $notifications = new Notifications();
	     $notifications->add('REPUPL', $_SESSION['MEDID'], true, $row2['Identif'], false, null);
?>
