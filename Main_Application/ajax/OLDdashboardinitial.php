<?php
$NombreEnt = 'x';
$PasswordEnt = 'x';

//	$access = empty($_SESSION['Acceso'])   ? null : $_SESSION['Acceso'];
 
/* if($access==23432)
 {
	echo "Validated via access code";
 }
 else
 {*/
	
	$name=empty($_POST['Nombre'])   ? $_SESSION['Nombre'] : $_POST['Nombre'];
	$pass=empty($_POST['Password'])   ? $_SESSION['Password'] : $_POST['Password'];

	//KYLE$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
	mysql_select_db("$dbname")or die("cannot select DB");

	
	//$result = mysql_query("SELECT * FROM doctors where IdMEDEmail='$name' and IdMEDRESERV='$pass'");
	$result = mysql_query("SELECT * FROM doctors where IdMEDEmail='$name'"); 
	$count=mysql_num_rows($result);
	$row = mysql_fetch_array($result);
	$success ='NO';
	if($count==1)
	{
		
		$IdMedRESERV=$row['IdMEDRESERV'];
		$addstring=$row['salt'];
		$correcthash=PBKDF2_HASH_ALGORITHM .":". PBKDF2_ITERATIONS. ":" . $addstring . ":" .$IdMedRESERV;
		if(validate_password($pass,$correcthash)){
		$success ='SI';
		$MedID = $row['id'];
		$MedUserEmail= $row['IdMEDEmail'];
		$MedUserName = $row['Name'];
		$MedUserSurname = $row['Surname'];
		$MedUserLogo = $row['ImageLogo'];
		$IdMedFIXED = $row['IdMEDFIXED'];
		$IdMedFIXEDNAME = $row['IdMEDFIXEDNAME'];
		
		//echo "Validated via Password";
		$_SESSION['Acceso']=23432;
		$_SESSION['MEDID']=$MedID;
		
		$_SESSION['Nombre']=$name;
		$_SESSION['Password']=$pass;
		
		}else {
		
		 echo "MEDICAL USER NOT VALID. Incorrect credentials for login";
			echo "<br>\n"; 	
			echo "<h2><a href='".$domain."'>Return to Inmers HomePage</a></h2>";
			session_destroy();
			die;
		
		
		}

	}
	else
	{
		//$result2 = mysql_query("SELECT * FROM doctors where IdMEDFIXEDNAME='$name' and IdMEDRESERV='$pass'");
		$result2 = mysql_query("SELECT * FROM doctors where IdMEDFIXEDNAME='$name'");
		$count2=mysql_num_rows($result2);
		$row2 = mysql_fetch_array($result2);
		$success ='NO';
		if($count2==1)
		{
			$IdMedRESERV=$row2['IdMEDRESERV'];
			$addstring=$row2['salt'];
			$correcthash=PBKDF2_HASH_ALGORITHM .":". PBKDF2_ITERATIONS .":".$addstring.":".$IdMedRESERV;
		    if(validate_password($pass,$correcthash)){
			$success ='SI';
			$MedID = $row2['id'];
			$MedUserEmail= $row2['IdMEDEmail'];
			$MedUserName = $row2['Name'];
			$MedUserSurname = $row2['Surname'];
			$MedUserRole = $row2['Role'];
			$MedUserLogo = $row2['ImageLogo'];
			$IdMedFIXED = $row2['IdMEDFIXED'];
			$IdMedFIXEDNAME = $row2['IdMEDFIXEDNAME'];
			//echo "Validated via Password";
			$_SESSION['Acceso']=23432;
			$_SESSION['MEDID']=$MedID;
			$_SESSION['Nombre']=$name;
			$_SESSION['Password']=$pass;
		
			if ($MedUserRole=='1') $MedUserTitle ='Dr. '; else $MedUserTitle =' '; 
			}else {
			echo "MEDICAL USER NOT VALID. Incorrect credentials for login";
			echo "<br>\n"; 	
			echo "<h2><a href='".$domain."'>Return to Inmers HomePage</a></h2>";
			session_destroy();
			die;			
			
			}
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

	
	//For checking multiple logins
	
	$query = "select * from ongoing_sessions where userid=".$_SESSION['MEDID'];
	$result=mysql_query($query);
	$count=mysql_num_rows($result);
	$ip = $_SERVER['REMOTE_ADDR'];	
	if($count==0)
	{
		//die("Here");
		$q = "insert into  ongoing_sessions values(".$_SESSION['MEDID'].",now(),'".$ip."')";
		mysql_query($q);
		
		//$q = "insert into  session_log values(".$_SESSION['MEDID'].",'Login',now(),'".$ip."')";
		//echo $q;
		//mysql_query($q);
	}
	else
	{
		$q = "select * from ongoing_sessions where userid=".$_SESSION['MEDID']." and ip='".$ip."'";
		//die($q);
		$res=mysql_query($q);
		$cnt=mysql_num_rows($res);
		if($cnt==1)
		{
			//The same user came back after abrupt logout (and before service could detect)
			//DO NOTHING
		}
		else
		{
			echo "Other users are Accessing this account";
			$q = "insert into  ongoing_sessions values(".$_SESSION['MEDID'].",now(),'".$ip."')";
			mysql_query($q);
			header( 'Location: double_login.php' ) ;
			//$q = "insert into  session_log values(".$_SESSION['MEDID'].",'Login',now(),'".$ip."')";
			//mysql_query($q);
		}
	}
	
	
	
//Create a folder for user if not already present
if (!file_exists('temp/'.$_SESSION['MEDID'])) 
{
    mkdir('temp/'.$_SESSION['MEDID'], 0777, true);
	mkdir('temp/'.$_SESSION['MEDID'].'/Packages_Encrypted', 0777, true);
	mkdir('temp/'.$_SESSION['MEDID'].'/PackagesTH_Encrypted', 0777, true);
}

$result = mysql_query("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$row = mysql_fetch_array($result);
$_SESSION['decrypt']=$row['pass'];

?>