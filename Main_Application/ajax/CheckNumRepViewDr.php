<?php
/*KYLE
require("environment_detail.php");
require("push_server.php");
//require_once("push_server.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$IdMED= $_GET['IdMED'];

// Connect to server and select databse.
$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

$result2 = mysql_query("SELECT * FROM bpinview USE INDEX(I1) WHERE VIEWIdUser = 0 AND (Content = 'Report Viewed') AND (DateTimeSTAMP BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) ");
$NumRepViewDr = mysql_num_rows($result2);
echo $NumRepViewDr;

?>