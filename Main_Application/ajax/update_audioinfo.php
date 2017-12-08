<?php
 session_start();
 
	set_time_limit(120);
	define('INCLUDE_CHECK',1);
	require "logger.php";
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


$tbl_name="lifepin"; // Table name

$fromname=$_POST['idmed'];
$queId = $_POST['queId'];
$IdPin=$_POST['Idpin'];

						$PatientDetails=getPatientName($queId);
						$IdUsu=$PatientDetails['Identif'];
						if($IdUsu==null)
							$IdUsu=0;
						$IdUsFIXED=$PatientDetails['IdUsFIXED'];
						$IdUsFIXEDNAME=$PatientDetails['IdUsFIXEDNAME'];

//KYLE$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

mysql_query("UPDATE lifepin SET IdUsFIXED='$IdUsFIXED', IdUsFIXEDNAME='$IdUsFIXEDNAME', IdMedEmail = '$fromname' WHERE IdPin='$IdPin'");

function getPatientDetails($firstname,$lastname)	{
				
		
			 require("environment_detail.php");
			 $dbhost = $env_var_db['dbhost'];
			 $dbname = $env_var_db['dbname'];
			 $dbuser = $env_var_db['dbuser'];
			 $dbpass = $env_var_db['dbpass'];
	

				// Connect to server and select databse.
				//KYLE$link1 = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
				mysql_select_db("$dbname")or die("cannot select DB");
				
				echo"<br>";
				echo "Fetching Patient ID details......";
				
				$query="SELECT * FROM usuarios where Name='$firstname' and Surname='$lastname'";
				$result1 = mysql_query($query);
				$count1=mysql_num_rows($result1);
				echo "<br>";
				echo "query: ".$query;
				echo "<br>";
				echo "count: ".$count1;
				$i=-1;
				if ($count1>1){
					
				}else if ($count1==0){					
					$i=0;					
				}else if ($count1==1){
					$row=mysql_fetch_assoc($result1);
					//$i=$row['Identif'];
					return $row;
				}
		       
			    mysql_close($link1);
			    return $i;
			   
	}
 function getPatientName($patientID)	{
				
		
			 require("environment_detail.php");
			 $dbhost = $env_var_db['dbhost'];
			 $dbname = $env_var_db['dbname'];
			 $dbuser = $env_var_db['dbuser'];
			 $dbpass = $env_var_db['dbpass'];
	

				// Connect to server and select databse.
				//KYLE$link1 = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
				mysql_select_db("$dbname")or die("cannot select DB");
				
				echo"<br>";
				echo "Fetching Patient ID details......";
				
				$query="SELECT * FROM usuarios where Identif='$patientID'";
				$result1 = mysql_query($query);
				$count1=mysql_num_rows($result1);
				echo "<br>";
				echo "query: ".$query;
				echo "<br>";
				echo "count: ".$count1;
				$i=-1;
				if ($count1>1){
					
				}else if ($count1==0){					
					$i=0;					
				}else if ($count1==1){
					$row=mysql_fetch_assoc($result1);
					//$i=$row['Identif'];
					return $row;
				}
		       
			    mysql_close($link1);
			    return $i;
			   
	}	
	
?>
