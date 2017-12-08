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


$userid = $_GET['userid']; 
$date = $_GET['date'];
$text = $_GET['text'];

$mm = strtok($date, "-");
$dd = strtok("-");
$yy = strtok("-");

//error_log($date);

//$date =  $yy.'-'.$mm.'-'.$dd;


$result = $con->prepare("INSERT INTO evolutions (userid,message_date,message) VALUES (?,?,?)");
$result->bindValue(1, $userid, PDO::PARAM_INT);
$result->bindValue(2, $date, PDO::PARAM_STR);
$result->bindValue(3, $text, PDO::PARAM_STR);

//echo $query;
if($result->execute())
{
	echo 'success';
}
else
{
	echo 'failure';
}



?>
