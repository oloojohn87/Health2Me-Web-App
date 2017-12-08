<?php session_start();
 require("../environment_detail.php");
 require("../PasswordHash.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$NombreEnt = 'x';
$PasswordEnt = 'x';

//	$access = empty($_SESSION['Acceso'])   ? null : $_SESSION['Acceso'];
 
/* if($access==23432)
 {
	echo "Validated via access code";
 }
 else
 {*/
	
	/*$name=empty($_POST['Nombre'])   ? $_SESSION['Nombre'] : $_POST['Nombre'];
	$pass=empty($_POST['Password'])   ? $_SESSION['Password'] : $_POST['Password'];*/

	$name=empty($_POST['Nombre'])   ? $_SESSION['Nombre'] : $_POST['Nombre'];
	$pass=empty($_POST['Password'])   ? $_SESSION['Password'] : $_POST['Password'];
	
    //$_SESSION['refid']= empty($_POST['refid']) ? '01010' : $_POST['refid'];
		
	$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
	mysql_select_db("$dbname")or die("cannot select DB");

	
	
	$result = mysql_query("SELECT * FROM Usuarios where email='$name'"); 
	$count=mysql_num_rows($result);
	$row = mysql_fetch_array($result);
	$success ='NO';
	if($count==1)
	{
		
		$IdUsRESERV=$row['IdUsRESERV'];
		$addstring=$row['salt'];
		$correcthash=PBKDF2_HASH_ALGORITHM .":". PBKDF2_ITERATIONS. ":" . $addstring . ":" .$IdUsRESERV;
		if(validate_password($pass,$correcthash)){
            $success ='SI';
            $UserID = $row['Identif'];
            $UserEmail= $row['email'];
            $UserName = $row['Name'];
            $UserSurname = $row['Surname'];
            //$UserLogo = $row['ImageLogo'];
            $IdUsFIXED = $row['IdUsFIXED'];
            $IdUsFIXEDNAME = $row['IdUsFIXEDNAME'];
            //$privilege=$row['previlege'];
            //echo "Privelege".$privilege;
            //echo "Validated via Password";
            $_SESSION['Acceso']=23432;
            $_SESSION['USERID']=$UserID;
            
            $_SESSION['Nombre']=$name;
            $_SESSION['Password']=$pass;
            //$_SESSION['Previlege']=$privilege;
		}else {
		
		 echo "USER NOT VALID. Incorrect credentials for login";
			echo "<br>\n"; 	
			echo "<h2><a href='".$domain."/mobapp/Index.html'>Return to Inmers HomePage</a></h2>";
			session_destroy();
			die;
		
		
		}

	}
	else
	{
		//$result2 = mysql_query("SELECT * FROM DOCTORS where IdMEDFIXEDNAME='$name' and IdMEDRESERV='$pass'");
		$result2 = mysql_query("SELECT * FROM Usuarios where IdUsFIXEDNAME='$name'");
		$count2=mysql_num_rows($result2);
		$row2 = mysql_fetch_array($result2);
		$success ='NO';
		if($count2==1)
		{
			$IdUsRESERV=$row2['IdUsRESERV'];
			$addstring=$row2['salt'];
			$correcthash=PBKDF2_HASH_ALGORITHM .":". PBKDF2_ITERATIONS .":".$addstring.":".$IdUsRESERV;
		    if(validate_password($pass,$correcthash)){
			$success ='SI';
			$UserID = $row2['Identif'];
			$UserEmail= $row2['email'];
			$UserName = $row2['Name'];
			$UserSurname = $row2['Surname'];
			//$MedUserRole = $row2['Role'];
			//$MedUserLogo = $row2['ImageLogo'];
			$IdUsFIXED = $row2['IdUsFIXED'];
			$IdUsFIXEDNAME = $row2['IdUsFIXEDNAME'];
			//$privilege=$row2['previlege'];
			//echo "Privelege ".$privilege;
			//echo "Validated via Password";
			$_SESSION['Acceso']=23432;
			$_SESSION['USERID']=$UserID;
			$_SESSION['Nombre']=$name;
			$_SESSION['Password']=$pass;
			//$_SESSION['Previlege']=$privilege;
		
			//if ($MedUserRole=='1') $MedUserTitle ='Dr. '; else $MedUserTitle =' '; 
			}else {
			echo "USER NOT VALID. Incorrect credentials for login";
			echo "<br>\n"; 	
			echo "<h2><a href='".$domain."/mobapp/Index.html'>Return to Inmers HomePage</a></h2>";
			session_destroy();
			die;			
			
			}
		}
		else
		{
			echo "USER NOT VALID. Incorrect credentials for login";
			echo "<br>\n"; 	
			echo "<h2><a href='".$domain."/mobapp/Index.html'>Return to Inmers HomePage</a></h2>";
			session_destroy();
			die;
		}
	}

	
	//For checking multiple logins
	
	/*$query = "select * from ongoing_sessions where userid=".$_SESSION['MEDID'];
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
	}*/
//Create a folder for user if not already present
if (!file_exists('temp/'.$_SESSION['USERID'])) 
{
    mkdir('temp/'.$_SESSION['USERID'], 0777, true);
	mkdir('temp/'.$_SESSION['USERID'].'/Packages_Encrypted', 0777, true);
	mkdir('temp/'.$_SESSION['USERID'].'/PackagesTH_Encrypted', 0777, true);
}

$result = mysql_query("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$row = mysql_fetch_array($result);
$_SESSION['decrypt']=$row['pass'];

	
//echo $_SESSION['USERID'];
//echo $_SESSION['Previlege'];
//if($_SESSION['Previlege']==1){
header('Location:dashboard_mob.php');
/*}else{
if($_SESSION['Previlege']==2){
header('Location:patients.php');
}
}	*/


?>