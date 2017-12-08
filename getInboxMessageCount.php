<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 /*KYLE
 require("environment_detail.php");
 
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="messages"; // Table name

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

$IdMED= $_GET['IdMED'];
$patientid=$_GET['patient'];

$result = mysql_query("SELECT * FROM message_infrasture WHERE receiver_id='$IdMED' and patient_id='$patientid' ORDER BY fecha DESC");
$count=mysql_num_rows($result);

echo $count;    
    

?>


