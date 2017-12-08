<?php
 session_start();
 require("../ajax/environment_detailForLogin.php");
 require("../ajax/PasswordHash.php");
  require_once("../ajax/displayExitClass.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	


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
	
    $name=$_POST['Nombre'];
    $pass = '';
    $override_pw = false;
    if(isset($_POST['Password']))
    {
        $pass = $_POST['Password'];
    }
    else
    {
        // the following code is used for switching users if they are using a family account
        if(isset($_SESSION['UserID']))
        {
            $result = $con->prepare("SELECT email FROM usuarios WHERE OwnerAcc = ?");
			$result->bindValue(1, $_SESSION['Original_ID'], PDO::PARAM_INT);
			$result->execute();
			
            while($row = $result->fetch(PDO::FETCH_ASSOC))
            {
                if($row['email'] == $name)
                {
                    $override_pw = true;
                }
            }
            
            if($override_pw == false && $_SESSION['Original_User_Access'] == 'Admin')
            {
                $result = $con->prepare("SELECT email FROM usuarios WHERE OwnerAcc IN (SELECT OwnerAcc FROM usuarios WHERE Identif = ?)");
				$result->bindValue(1, $_SESSION['Original_ID'], PDO::PARAM_INT);
				$result->execute();
				
                while($row = $result->fetch(PDO::FETCH_ASSOC))
                {
                    if($row['email'] == $name)
                    {
                        $override_pw = true;
                    }
                }
            }
        }
    }
	//$pass=$_POST['Password'];


  //Checking for whether NotesAndSummary flag is set or not
    $notesAndSummary = "";
    $consultationId = "";
    
  /*  if(isset($_POST['notesAndSummary']))
       {
          $notesAndSummary = $_POST['notesAndSummary'];
          $consultationId = $_POST['consultationId'];
       }

     if($notesAndSummary == 1)
     {
               $link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
               mysql_select_db("$dbname")or die("cannot select DB");
               $query = mysql_query("SELECT identif FROM usuarios where email='$name'"); 

               $result = mysql_fetch_array($query); 
               $UserID = $result['identif'];
               $_SESSION['Acceso'] = 23432;
               $_SESSION['UserID'] = $UserID;

               if (!file_exists('temp/'.$UserID)) 
                    {
                        mkdir('temp/'.$UserID, 0777, true);
                        mkdir('temp/'.$UserID.'/Packages_Encrypted', 0777, true);
                    }

               header('Location:showFiles.php?consultationId='.$consultationId);

     }
	
   else
   {*/

    $_SESSION['refid']= empty($_POST['refid']) ? '01010' : $_POST['refid'];
	
 //   echo $name;
 //   echo '*** ';
 //   echo $pass;
 //   echo '*** ';
 //   echo $_SESSION['refid'];
 //   echo '*** ';
    
		

    $result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
	$result->execute();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $_SESSION['decrypt']=$row['pass'];

	
	//$result = mysql_query("SELECT * FROM doctors where IdMEDEmail='$name' and IdMEDRESERV='$pass'");
	$result = $con->prepare("SELECT * FROM usuarios where IdUsFIXEDNAME=? or Alias=? or email=?"); 
	$result->bindValue(1, $name, PDO::PARAM_STR);
	$result->bindValue(2, $name, PDO::PARAM_STR);
	$result->bindValue(3, $name, PDO::PARAM_STR);
	$result->execute();
	
    $count=$result->rowCount();
	$row = $result->fetch(PDO::FETCH_ASSOC);
	$success ='NO';
    if($count==1)
	{
		
		$IdUsRESERV=$row['IdUsRESERV'];


		
		if($IdUsRESERV == null){

			$temp_pass = $con->prepare("SELECT * FROM key_chain where short_hash=?"); 
			$temp_pass->bindValue(1, $pass, PDO::PARAM_STR);
			$temp_pass->execute();
			$row_temp_pass = $temp_pass->fetch(PDO::FETCH_ASSOC);
			$temp_pass_id = $row_temp_pass['owner'];
			
			if($row['Identif'] == $temp_pass_id){
                echo 'po';
				$override_pw = true;
			}
		}

        
		//Commented as its not required anymore
        /*$TempoPass=$row['TempoPass'];
        $Password=$row['Password'];*/
		
        /* RETURN TO NORMAL WHEN ENCRYPTED PASSWORDS IMPLEMENTED AGAIN*/
        $addstring=$row['salt'];


		if(!$override_pw)$correcthash=PBKDF2_HASH_ALGORITHM .":". PBKDF2_ITERATIONS. ":" . $addstring . ":" .$IdUsRESERV;


		if(validate_password($pass,$correcthash) || $override_pw){
		/*RETURN TO NORMAL WHEN ENCRYPTED PASSWORDS IMPLEMENTED AGAIN */
		/*if ($pass==$TempoPass || $pass==$Password){*/
        $success ='SI';

        /* RETURN TO NORMAL WHEN ENCRYPTED PASSWORDS IMPLEMENTED AGAIN
        $MedID = $row['id'];
		$MedUserEmail= $row['IdMEDEmail'];
		$MedUserName = $row['Name'];
		$MedUserSurname = $row['Surname'];
		$MedUserLogo = $row['ImageLogo'];
		$IdMedFIXED = $row['IdMEDFIXED'];
		$IdMedFIXEDNAME = $row['IdMEDFIXEDNAME'];
		$privilege=$row['previlege'];
		RETURN TO NORMAL WHEN ENCRYPTED PASSWORDS IMPLEMENTED AGAIN */
		
        $UserID = $row['Identif'];
		$UserEmail= $row['email'];
		$UserName = $row['Name'];
		$UserSurname = $row['Surname'];
        $GrantAccess = $row['GrantAccess']; 
        echo $GrantAccess;
		
		//GENERATE SESSION HASH///////////////////////////////////////////////////////////////////
		$charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$str = '';
		$length = 255;
			$count = strlen($charset);
			while ($length--) {
				$str .= $charset[mt_rand(0, $count-1)];
			}
		$new_hash = $str;
		
		$result = $con->prepare("UPDATE usuarios SET session_hash = ? where Identif=?"); 
		$result->bindValue(1, $new_hash, PDO::PARAM_STR);
		$result->bindValue(2, $row['Identif'], PDO::PARAM_INT);
		$result->execute();
		/////////////////////////////////////////////////////////////////////////////////////////
        
		//$UserLogo = $row['ImageLogo'];
		$IdUsFIXED = $row['IdUsFIXED'];
		$IdUsFIXEDNAME = $row['IdUsFIXEDNAME'];
		$privilege=1;

        $_SESSION['Acceso']=23432;
		$_SESSION['UserID']=$UserID;
		$_SESSION['BOTHID']=$UserID;
		$_SESSION['session_hash_member']=$new_hash;
		
		$_SESSION['Nombre']=$name;
		$_SESSION['Password']=$pass;
		$_SESSION['Previlege']=$privilege; 
        $_SESSION['CustomLook']='NONE';
        if(!$override_pw)
        {
            $_SESSION['Original_User'] = $UserEmail;
            $_SESSION['Original_ID'] = $UserID;
            if(isset($row['subsType']) && $row['subsType'] != null)
            {
                $_SESSION['Original_User_Access'] = $row['subsType'];
            }
            else
            {
                $_SESSION['Original_User_Access'] = '';
            }
        }
        if ($GrantAccess == 'HTI-COL' || $GrantAccess == 'HTI-RIVA' || $GrantAccess == 'HTI-24X7' || $GrantAccess == 'HTI-SPA' || $GrantAccess == 'HTI-SPA' || $GrantAccess == 'HTI-PR'|| $GrantAccess == 'HTI-CR' || $GrantAccess == 'HTI-MEX' || $GrantAccess == 'HTI-ECU'){    
            $_SESSION['CustomLook']='COL';    
        }elseif($GrantAccess == 'CATA'){
			$_SESSION['CustomLook']='CATA'; 
		}
		
		}else {
		
		 $exit_display = new displayExitClass();

		$exit_display->displayFunction(1);
			session_destroy();
			die;
		
		
		}

	} 
    
    else
	{
		//$result2 = mysql_query("SELECT * FROM doctors where IdMEDFIXEDNAME='$name' and IdMEDRESERV='$pass'");
		$result2 = $con->prepare("SELECT * FROM usuarios where email=?");
		$result2->bindValue(1, $name, PDO::PARAM_STR);
		$result2->execute();
		
		$count2=$result2->rowCount();
		$row2 = $result2->fetch(PDO::FETCH_ASSOC);
		$success ='NO';
		if($count2==1)
		{
			$IdUsRESERV=$row2['IdUsRESERV'];
			$addstring=$row2['salt'];
			$correcthash=PBKDF2_HASH_ALGORITHM .":". PBKDF2_ITERATIONS .":".$addstring.":".$IdUsRESERV;
		    if(validate_password($pass,$correcthash) || $override_pw){
			$success ='SI';
			/*$MedID = $row2['id'];
			$MedUserEmail= $row2['IdMEDEmail'];
			$MedUserName = $row2['Name'];
			$MedUserSurname = $row2['Surname'];
			$MedUserRole = $row2['Role'];
			$MedUserLogo = $row2['ImageLogo'];
			$IdMedFIXED = $row2['IdMEDFIXED'];
			$IdMedFIXEDNAME = $row2['IdMEDFIXEDNAME'];
			$privilege=$row2['previlege'];*/
			//echo "Privelege ".$privilege;
			//echo "Validated via Password";
			
			//GENERATE SESSION HASH/////////////////////////////////////////////
		$charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$str = '';
		$length = 255;
			$count = strlen($charset);
			while ($length--) {
				$str .= $charset[mt_rand(0, $count-1)];
			}
		$new_hash = $str;
		
		$result = $con->prepare("UPDATE usuarios SET session_hash = ? where Identif=?"); 
		$result->bindValue(1, $new_hash, PDO::PARAM_STR);
		$result->bindValue(2, $row2['Identif'], PDO::PARAM_INT);
		$result->execute();
		//////////////////////////////////////////////////////////////////
			
			$UserID = $row2['Identif'];
			$UserEmail= $row2['email'];
			$UserName = $row2['Name'];
			$UserSurname = $row2['Surname'];
            $GrantAccess = $row2['GrantAccess'];    
			//$UserLogo = $row['ImageLogo'];
			$IdUsFIXED = $row2['IdUsFIXED'];
			$IdUsFIXEDNAME = $row2['IdUsFIXEDNAME'];
			$privilege=1;

			$_SESSION['Acceso']=23432;
			$_SESSION['UserID']=$UserID;
			$_SESSION['BOTHID']=$UserID;
			$_SESSION['session_hash_member']=$new_hash;
			
			$_SESSION['Nombre']=$name;
			$_SESSION['Password']=$pass;
			$_SESSION['Previlege']=1;
            $_SESSION['CustomLook']='NONE'; 
            if(!$override_pw)
            {
                $_SESSION['Original_User'] = $UserEmail;
                $_SESSION['Original_ID'] = $UserID;
                if(isset($row2['subsType']) && $row2['subsType'] != null)
                {
                    $_SESSION['Original_User_Access'] = $row2['subsType'];
                }
                else
                {
                    $_SESSION['Original_User_Access'] = '';
                }
            }
            if ($GrantAccess == 'HTI-COL' || $GrantAccess == 'HTI-RIVA' || $GrantAccess == 'HTI-24X7' || $GrantAccess == 'HTI-SPA' || $GrantAccess == 'HTI-SPA' || $GrantAccess == 'HTI-PR'|| $GrantAccess == 'HTI-CR' || $GrantAccess == 'HTI-MEX' || $GrantAccess == 'HTI-ECU'){    
                $_SESSION['CustomLook']='COL';    
            }    
                
		
			//if ($MedUserRole=='1') $MedUserTitle ='Dr. '; else $MedUserTitle =' '; 
			}else {
			$exit_display = new displayExitClass();

		$exit_display->displayFunction(2);
			session_destroy();
			die;			
			
			}
		}
		else
		{
			$exit_display = new displayExitClass();

		$exit_display->displayFunction(2);
			session_destroy();
			die;
		}
	}
       
    
	//For checking multiple logins
	
	//echo 'SESSION: '.$_SESSION['MEDID'];
	
	$query = $con->prepare("select * from ongoing_sessions where userid=?");
	$query->bindValue(1, $UserID, PDO::PARAM_INT);
	
	
	$result=$query->execute();
	$count=$query->rowCount();
	$ip = $_SERVER['REMOTE_ADDR'];	
	if($count==0)
	{
		//die("Here");
		$q = $con->prepare("insert into ongoing_sessions values(?,now(),?)");
		$q->bindValue(1, $UserID, PDO::PARAM_INT);
		$q->bindValue(2, $ip, PDO::PARAM_STR);
		$q->execute();
		
		//$q = "insert into  session_log values(".$_SESSION['MEDID'].",'Login',now(),'".$ip."')";
		//echo $q;
		//mysql_query($q);
	}
	else
	{
		$q = $con->prepare("select * from ongoing_sessions where userid=? and ip=?");
		$q->bindValue(1, $UserID, PDO::PARAM_INT);
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
			$q = $con->prepare("insert into  ongoing_sessions values(?,now(),?)");
			$q->bindValue(1, $UserID, PDO::PARAM_INT);
			$q->bindValue(2, $ip, PDO::PARAM_STR);
			
			$q->execute();
			//header( 'Location: double_login.php' ) ;
			//$q = "insert into  session_log values(".$_SESSION['MEDID'].",'Login',now(),'".$ip."')";
			//mysql_query($q);
		}
	}
//Create a folder for user if not already present
if (!file_exists('../../temp/'.$UserID)) 
{
    mkdir('../../temp/'.$UserID, 0777, true);
	mkdir('../../temp/'.$UserID.'/Packages_Encrypted', 0777, true);
	mkdir('../../temp/'.$UserID.'/PackagesTH_Encrypted', 0777, true);
}

/*
echo '    Database: '.$TempoPass;
echo '    Entered: '.$pass;
echo '    Privilege: '.$_SESSION['Previlege'];
die;
*/

	
/*if($_SESSION['refid']=='9a9a2rtd'){

header('Location:patientdetailMED-new.php?IdUsu='.$_POST['PatientID']);

}else{*/
//echo $_SESSION['Previlege'];
// if($_SESSION['Previlege']==1){
// header('Location:UserDashboard.php');
// }else{
// if($_SESSION['Previlege']==2){
// header('Location:patients.php');
// }

//Added for notesandsummary
     if(isset($_POST['notesAndSummary']) || isset($_POST['consultationId']))
     {
          $notesAndSummary = $_POST['notesAndSummary'];
          $consultationId = $_POST['consultationId'];
    
            /*   $link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
               mysql_select_db("$dbname")or die("cannot select DB");
               $query = mysql_query("SELECT identif FROM usuarios where email='$name'"); 

               $result = mysql_fetch_array($query); 
               $UserID = $result['identif'];
               $_SESSION['Acceso'] = 23432;
               $_SESSION['UserID'] = $UserID;

               if (!file_exists('temp/'.$UserID)) 
                    {
                        mkdir('temp/'.$UserID, 0777, true);
                        mkdir('temp/'.$UserID.'/Packages_Encrypted', 0777, true);
                    }
                 */
            header('Location:showFiles.php?consultationId='.$consultationId);

     }else{
	      
                 
         header('Location:userdashboard/html/UserDashboard.php');
     }
       
//}

//}
	


/*
$anchoDisp = 700;
$escala = .6;
$anchoDisp = 1000;
$escala = 1;



//BLOCKLIFEPIN $result = mysql_query("SELECT * FROM blocks");
$result = mysql_query("SELECT * FROM lifepin");*/

?>
