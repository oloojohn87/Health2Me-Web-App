<?php
require("environment_detailForLogin.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

$valor = $_GET['valor'];
$queTipo = $_GET['queTipo'];

$result = mysql_query("SELECT * FROM doctors WHERE IdMEDEmail='$valor'  ");
$count=mysql_num_rows($result);

echo $count;

return $count;

?>