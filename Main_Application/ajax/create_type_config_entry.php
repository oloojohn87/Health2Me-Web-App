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
  
  
//$id = $_GET['id'];
$userid = $_SESSION['MEDID'];
//$userid = 28;
$title=$_GET['title'];
$hours=$_GET['hours'];
$minutes=$_GET['minutes'];
$colour=$_GET['colour'];
echo $title;
echo $hours;
echo $minutes;
echo $colour;
$query = $con->prepare("insert into user_event_config(userid,title,hours,minutes,colour) values(?,?,?,?,?)");
$query->bindValue(1, $userid, PDO::PARAM_INT);
$query->bindValue(2, $title, PDO::PARAM_STR);
$query->bindValue(3, $hours, PDO::PARAM_INT);
$query->bindValue(4, $minutes, PDO::PARAM_INT);
$query->bindValue(5, $colour, PDO::PARAM_STR);
echo $query;
$query->execute();


?>