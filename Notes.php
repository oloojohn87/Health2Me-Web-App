<?php
require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

// Connect to server and select databse.
//KYLE$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

$notes1=$_GET["ntext"];
$notes2=$_GET["ndate"];


$sql="INSERT INTO notes VALUES ('$notes1','$notes2')";

if(!mysql_query($sql))
{
die('Error: ' .mysql_error());
}
?>
