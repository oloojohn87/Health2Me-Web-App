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
	 
	$userID = $_GET['userid'];
	$ip = $_SERVER['REMOTE_ADDR'];
	
	
	$query = $con->prepare("update ongoing_sessions set lastseen=now() where userid=? and ip=?");
	$query->bindValue(1, $userID, PDO::PARAM_INT);
	$query->bindValue(2, $ip, PDO::PARAM_STR);
	$r = $query->execute();
	
	//echo $query;
//	$r=mysql_query($query);
	if($r)
	{
		echo 1;
	}
	else
	{
		echo 0;
	}
	
?>