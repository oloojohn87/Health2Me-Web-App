<?php
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

//KYLE$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

$usuario = $_GET['usuario'];
$password = $_GET['password'];

$result = mysql_query("SELECT * FROM doctors WHERE IdMEDEmail='$usuario' AND IdMEDRESERV='$password'");
$count=mysql_num_rows($result);
$row = mysql_fetch_array($result);

//echo $count;
$cuenta =sprintf ( '%1$01d',$count);
$idmed =sprintf ( '%1$06d',$row['id']);

echo $cuenta.$idmed;
echo '****** SALIDA DE PHP';

?>