<?php

 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 session_start();

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	
$oldName = $_GET['oldName'];
$newName=$_GET['newName'];
if(file_exists("../htdocs/PatientImage/".$oldName))
 {
	rename("../htdocs/PatientImage/".$oldName,"../htdocs/RemovedImages/".$newName);
	echo "file exists";
 }
 $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
 foreach($fileTypes as $ext)
 {
	if(file_exists("../htdocs/PatientImage/".$_SESSION["MEDID"].".".$ext))
	{
		unlink("../htdocs/PatientImage/".$_SESSION["MEDID"].".".$ext);
		//echo "Session file unlinked";
		break;
	}
 }





?>