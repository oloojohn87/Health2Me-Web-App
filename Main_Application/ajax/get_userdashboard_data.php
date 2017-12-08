<?php
//include 'config.php';
session_start();
require("environment_detail.php");

$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$userid = $_SESSION['MEDID'];
//echo "User Id : ".$userid."<br>";
$sql = "select timezone, location from usuarios where Identif = ".$userid;
 

	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->query($sql);  
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //var_dump($row)."<br>";
    echo $row[0]["timezone"].";";
    echo $row[0]["location"];
	//echo $timezone.",".$place; 
