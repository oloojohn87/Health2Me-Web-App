

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
 <head>
 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
 <title>table2</title>
 </head>
 
<body>
 <?php
 session_start();
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 $access = empty($_SESSION['accesstoken'])   ? null : $_SESSION['accesstoken'];
 
 if($access==23432)
 {
	echo "Validated via access code";
 }
 else
 {
 
	
	$name=empty($_POST['name'])   ? null : $_POST['name'];
	$pass=empty($_POST['pass'])   ? null : $_POST['pass'];;
	//echo "your UID is: $var1"."<br/>";
	//echo "your pass is: $var2"."<br/>";
	//echo "your access token is: $var3"."<br/>";
//	$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
	mysql_select_db("$dbname")or die("cannot select DB");


	$result = mysql_query("SELECT * FROM doctors where IdMEDEmail='$name' and IdMEDRESERV='$pass'");
	$count=mysql_num_rows($result);
	$row = mysql_fetch_array($result);
	$success ='NO';
	if($count==1)
	{
		$success ='SI';
		$MedID = $row['id'];
		$MedUserEmail= $row['IdMEDEmail'];
		$MedUserName = $row['Name'];
		$MedUserSurname = $row['Surname'];
		$MedUserLogo = $row['ImageLogo'];
		$IdMedFIXED = $row['IdMEDFIXED'];
		$IdMedFIXEDNAME = $row['IdMEDFIXEDNAME'];
		echo "Validated via Password";
		$_SESSION['accesstoken']=23432;
		$_SESSION['MEDID']=$MedID;
		$_SESSION['Nombre']=$name;
		$_SESSION['Password']=$pass;
		


	}
	else
	{
		$result2 = mysql_query("SELECT * FROM doctors where IdMEDFIXEDNAME='$name' and IdMEDRESERV='$pass'");
		$count2=mysql_num_rows($result2);
		$row2 = mysql_fetch_array($result2);
		$success ='NO';
		if($count2==1)
		{
			$success ='SI';
			$MedID = $row2['id'];
			$MedUserEmail= $row2['IdMEDEmail'];
			$MedUserName = $row2['Name'];
			$MedUserSurname = $row2['Surname'];
			$MedUserRole = $row2['Role'];
			$MedUserLogo = $row2['ImageLogo'];
			$IdMedFIXED = $row['IdMEDFIXED'];
			$IdMedFIXEDNAME = $row['IdMEDFIXEDNAME'];
			echo "Validated via Password";
			$_SESSION['accesstoken']=23432;
			$_SESSION['MEDID']=$MedID;
			$_SESSION['Nombre']=$name;
			$_SESSION['Password']=$pass;
		
			if ($MedUserRole=='1') $MedUserTitle ='Dr. '; else $MedUserTitle =' '; 
		}
		else
		{
			echo "MEDICAL USER NOT VALID. Incorrect credentials for login";
			echo "<br>\n"; 	
			echo "<h2><a href='".$domain."'>Return to Inmers HomePage</a></h2>";
			session_destroy();
			die;
		}
	}
 }

 echo "Assume this is the dashboard after successful validation";
 
 
 ?>
 </body>
 </html>