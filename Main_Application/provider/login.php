<?php session_start();
 require("../ajax/environment_detailForLogin.php");
 require("../ajax/PasswordHash.php");
 require_once("../ajax/displayExitClass.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];



$NombreEnt = 'x';
$PasswordEnt = 'x';
$Catapult_Access = false;

//	$access = empty($_SESSION['Acceso'])   ? null : $_SESSION['Acceso'];
 
/* if($access==23432)
 {
	echo "Validated via access code";
 }
 else
 {*/
	
	/*$name=empty($_POST['Nombre'])   ? $_SESSION['Nombre'] : $_POST['Nombre'];
	$pass=empty($_POST['Password'])   ? $_SESSION['Password'] : $_POST['Password'];*/

	//$name=empty($_POST['Nombre'])   ? $_SESSION['Nombre'] : $_POST['Nombre'];
	//$pass=empty($_POST['Password'])   ? $_SESSION['Password'] : $_POST['Password'];
    
    $name=$_POST['Nombre'];
	$pass=$_POST['Password'];

	
    $_SESSION['refid']= empty($_POST['refid']) ? '01010' : $_POST['refid'];
		
	// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

	
	//$result = mysql_query("SELECT * FROM doctors where IdMEDEmail='$name' and IdMEDRESERV='$pass'");
	$result = $con->prepare("SELECT * FROM doctors where IdMEDEmail=?"); 
	$result->bindValue(1, $name, PDO::PARAM_STR);
	$result->execute();
	
	$count=$result->rowCount();
	$row = $result->fetch(PDO::FETCH_ASSOC);
	$success ='NO';
    if($count == 0)
    {
        $result = $con->prepare("SELECT * FROM doctors where IdMEDFIXEDNAME=?"); 
		$result->bindValue(1, $name, PDO::PARAM_STR);
		$result->execute();
		
	    $count=$result->rowCount();
	    $row = $result->fetch(PDO::FETCH_ASSOC);
    }

	if($row['previlege'] == 3){
		//die;
	}

    //CATAPULT ACCOUNT VERIFICATION
    if(strpos($row['type'], 'CATA') !== false) $Catapult_Access = true;

    //DOCTOR FOUND
	if($count==1)
	{
	
	//GENERATE SESSION HASH......
		$charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$str = '';
		$length = 255;
			$count = strlen($charset);
			while ($length--) {
				$str .= $charset[mt_rand(0, $count-1)];
			}
		$new_hash = $str;
		
		$result = $con->prepare("UPDATE doctors SET session_hash = ? where id=?"); 
		$result->bindValue(1, $new_hash, PDO::PARAM_STR);
		$result->bindValue(2, $row['id'], PDO::PARAM_INT);
		$result->execute();
		
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
            $privilege=$row['previlege'];
            //echo "Privelege".$privilege;
            //echo "Validated via Password";
            $_SESSION['Acceso']=23432;
            $_SESSION['MEDID']=$MedID;
			$_SESSION['BOTHID']=$MedID;
			$_SESSION['session_hash_doctor']=$new_hash;
            
            $_SESSION['Nombre']=$name;
            $_SESSION['Password']=$pass;
            $_SESSION['Previlege']=$privilege;
            $_SESSION['CustomLook']="NONE";
            if ($privilege == '3') {
                $_SESSION['CustomLook']='COL'; }    
            if ($privilege == '4') {
                $_SESSION['CustomLook']='VIT'; }    
		}else {
		
		 $exit_display = new displayExitClass();

		$exit_display->displayFunction(2);
			session_destroy();
			die;
		
		
		}

	}
	/*
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
			$privilege=$row2['previlege'];
			//echo "Privelege ".$privilege;
			//echo "Validated via Password";
			$_SESSION['Acceso']=23432;
			$_SESSION['MEDID']=$MedID;
			$_SESSION['Nombre']=$name;
			$_SESSION['Password']=$pass;
			$_SESSION['Previlege']=$privilege;
            
		
			if ($MedUserRole=='1') $MedUserTitle ='Dr. '; else $MedUserTitle =' '; 
			}else {
			echo "MEDICAL USER NOT VALID. Incorrect credentials for login";
			echo "<br>\n"; 	
			echo "<h2><a href='".$domain."'>Return to Inmers HomePage</a></h2>";
			session_destroy();
			die;			
			
			}
		}*/
		else
		{
			$exit_display = new displayExitClass();

		$exit_display->displayFunction(2);
			session_destroy();
			die;
		}
	//}

	
	//For checking multiple logins
	
	$query = $con->prepare("select * from ongoing_sessions where userid=?");
	$query->bindValue(1, $_SESSION['MEDID'], PDO::PARAM_INT);
	
	
	$result=$query->execute();
	$count=$query->rowCount();
	$ip = $_SERVER['REMOTE_ADDR'];	
	if($count==0)
	{
		//die("Here");
		$q = $con->prepare("insert into  ongoing_sessions values(?,now(),?)");
		$q->bindValue(1, $_SESSION['MEDID'], PDO::PARAM_INT);
		$q->bindValue(2, $ip, PDO::PARAM_STR);
		$q->execute();
		
		//$q = "insert into  session_log values(".$_SESSION['MEDID'].",'Login',now(),'".$ip."')";
		//echo $q;
		//mysql_query($q);
	}
	else
	{
		$q = $con->prepare("select * from ongoing_sessions where userid=? and ip=?");
		$q->bindValue(1, $_SESSION['MEDID'], PDO::PARAM_INT);
		$q->bindValue(2, $ip, PDO::PARAM_STR);
		
		
		//die($q);
		$res=$q->execute();
		$cnt=$q->rowCount();
		if($cnt==1)
		{
			//The same user came back after abrupt logout (and before service could detect)
			//DO NOTHING
		}
		else
		{
			//echo "Other users are Accessing this account";
			//$q = "insert into  ongoing_sessions values(".$_SESSION['MEDID'].",now(),'".$ip."')";
			//mysql_query($q);
			//header( 'Location: double_login.php' ) ;
			//$q = "insert into  session_log values(".$_SESSION['MEDID'].",'Login',now(),'".$ip."')";
			//mysql_query($q);
		}
	}
//Create a folder for user if not already present
if (!file_exists('../../temp/'.$_SESSION['MEDID'])) 
{
    mkdir('../../temp/'.$_SESSION['MEDID'], 0777, true);
	mkdir('../../temp/'.$_SESSION['MEDID'].'/Packages_Encrypted', 0777, true);
	mkdir('../../temp/'.$_SESSION['MEDID'].'/PackagesTH_Encrypted', 0777, true);
}

$result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$result->execute();

$row = $result->fetch(PDO::FETCH_ASSOC);
$_SESSION['decrypt']=$row['pass'];

	
if($_SESSION['refid']=='9a9a2rtd'){
     header('Location:patientdetailMED-new.php?IdUsu='.$_POST['PatientID']);

}else{
	//echo $_SESSION['Previlege'];

	//if($_SESSION['Previlege']==1){
    if($Catapult_Access) header('Location:maindashboard/html/MainDashboardCATA.php');
	else header('Location:maindashboard/html/MainDashboard.php');
	//}else{
	//if($_SESSION['Previlege']==2){
	//header('Location:MainDashboard.php');
	//header('Location:patients.php');
	//}
        
        
	//}
}	


/*
$anchoDisp = 700;
$escala = .6;
$anchoDisp = 1000;
$escala = 1;



//BLOCKLIFEPIN $result = mysql_query("SELECT * FROM blocks");
$result = mysql_query("SELECT * FROM lifepin");*/

?>
