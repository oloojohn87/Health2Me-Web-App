<?php
require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

// Connect to server and select databse.
//$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

$demo1=$_GET['namedemo'];
$demo2=$_GET['middleinit'];
$demo3=$_GET['sur'];
$demo4=$_GET['gend'];
$demo5=$_GET['dob'];
$demo6=$_GET['addr'];
$demo7=$_GET['addr2'];
$demo8=$_GET['citydemo'];
$demo9=$_GET['statedemo'];
$demo10=$_GET['countrydemo'];
$demo11=$_GET['notesdemo'];
$sql="INSERT INTO basicemrdata1 VALUES ('$demo1','$demo2','$demo3','$demo4','$demo5','$demo6','$demo7','$demo8','$demo9','$demo10','$demo11')";

if(!mysql_query($sql))
{
die('Error: ' .mysql_error());
}
else
{
echo 'Success';
}
?>
