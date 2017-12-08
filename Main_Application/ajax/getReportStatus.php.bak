<?php
//include 'config.php';

 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="lifepin"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			

$quePin = $_GET['IdPin'];

$q2 = $con->prepare("select a_s,emr_report,markfordelete from $tbl_name where IdPin = ?");
$q2->bindValue(1, $quePin, PDO::PARAM_INT);
$q2->execute();

if($row = $q2->fetch(PDO::FETCH_ASSOC))
{
	if($row['markfordelete'] == 1)
	{
		echo '1';   //implies report has already been deleted
		return;
	}
	else if($row['emr_report'] == 1)
	{
		echo '2';   // implies emr_report and cannot be deleted
		return;
	}
	echo '3';		//implies you can delete the report
	return;		
}
else
{
	echo 0;
}
?>
