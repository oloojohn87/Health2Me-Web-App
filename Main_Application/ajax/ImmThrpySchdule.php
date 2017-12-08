<?php
session_start();
require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


$json=$_GET ['json'];

$obj1 = json_decode($json,true);

//echo $obj[0]["pre_patenctemp"];
var_dump($obj1);
?>