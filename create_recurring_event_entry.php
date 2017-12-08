<?php
session_start();
require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 
// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			

$userid = $_SESSION['MEDID'];
//$userid = 28;
$title=$_GET['title'];
$day_start=$_GET['day_start'];
$day_end=$_GET['day_end'];
$time_start=$_GET['time_start'];
$time_end = $_GET['time_end'];

$query = $con->prepare("insert into user_time_config(userid,title,day_start,day_end,start,end,type) 
values(?,?,?,?,?,?,2)");
$query->bindValue(1, $userid, PDO::PARAM_INT);
$query->bindValue(2, $title, PDO::PARAM_STR);
$query->bindValue(3, $day_start, PDO::PARAM_INT);
$query->bindValue(4, $day_end, PDO::PARAM_INT);
$query->bindValue(5, $time_start, PDO::PARAM_STR);
$query->bindValue(6, $time_end, PDO::PARAM_STR);
$query->execute();


?>