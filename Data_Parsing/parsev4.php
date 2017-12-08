<?php
	//This file calls the Lexer utility to find the suggested date
	//It then updates the column suggesteddate1 in lifepin table
	
	require("..//environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

	$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
	mysql_select_db("$dbname")or die("cannot select DB");	

	$idp=$_GET['idp'];
	
	// Check connection
	//$link = mysql_connect("$host", "$username", "$password")or die("cannot connect");
	//mysql_select_db("$db_name")or die("cannot select DB");
	//echo "Connection Established";
	
	//-----------------------------------------------------
	$suggesteddate = shell_exec('Lexer');
	echo $suggesteddate;
	$query = "update lifepin set suggesteddate1='".$suggesteddate."' where idpin=".$idp;
	echo "<br>".$query;
	mysql_query($query);
	
?>