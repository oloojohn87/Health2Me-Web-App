<?php
 //header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Origin: *");
 require("../environment_detailForLogin.php");


 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

    $name=$_POST['user'];
	$pass=$_POST['pass'];
 
	//$name = "lsmith@inmers.us";
	//$pass = "cisco001";

	//check the password ok    

	$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

	if (!$con)
  	{
  		die('Could not connect: ' . mysql_error());
  	}


	//need to remember to check the password

    $result = $con->prepare("SELECT * FROM usuarios where email=?");
	$result->bindValue(1, $name, PDO::PARAM_STR);
	$result->execute();
	$count = $result->rowCount();
	//echo "Count".$count."<br>";
	$row = $result->fetch(PDO::FETCH_ASSOC);

    if($count==1)
	{
        
		//Commented as its not required anymore
       /* RETURN TO NORMAL WHEN ENCRYPTED PASSWORDS IMPLEMENTED AGAIN*/
 /*       $addstring=$row['salt'];
		$correcthash=PBKDF2_HASH_ALGORITHM .":". PBKDF2_ITERATIONS. ":" . $addstring . ":" .$IdMedRESERV;
		if(validate_password($pass,$correcthash)){
		/*RETURN TO NORMAL WHEN ENCRYPTED PASSWORDS IMPLEMENTED AGAIN */

        
 /*       $success ='SI';*/
		
        $userid = $row['Identif'];
		$first_name = $row['Name'];
		$sur_name = $row['Surname'];
		$owner_acct = $row['ownerAcc'];
		$plan = $row['plan'];

		//echo($output);
		
		//if it is a family   
		//?? owner account
		$plan == "none";
		if ($plan == "FAMILY") {
    		$resultD = $con->prepare("SELECT Name,Surname,Identif,email,relationship,subsType,grant_access,floor(datediff(curdate(),DOB) / 365) as age FROM usuarios U INNER JOIN basicemrdata B where U.ownerAcc = ? AND U.Identif != ? AND U.Identif = B.IdPatient");
     		$resultD->bindValue(1, $owner_acct, PDO::PARAM_INT);
     		$resultD->bindValue(2, $userid, PDO::PARAM_INT);
     		$resultD->execute();
			$count = $resultD->rowCount();
			//$user_accts = array();
			$family_arr=array();
	 		while($rowAcct = $resultD->fetch(PDO::FETCH_ASSOC))
        	{
				$family = "";
				$family .= '{"fname" : "'.$rowAcct['Name'].' '.$rowAcct['Surname'].'",';
				$family .= '"fid" : '.$rowAcct['Identif'].'}';
				//echo $rowAcct['Name']."<br>";
				//echo $rowAcct['Surname']."<br>";
				//echo $rowAcct['Identif']."<br>";
				array_push($family_arr, $family);
				
        	}		

			$output='
    		{
        		"user":"'.$userid.'",
        		"firstname":"'.$first_name.'",
        		"surname":"'.$sur_name.'",';
			$family_output = implode(",", $family_arr);
			$output.='
				"family": {
						"members" : [
										'.$family_output.'
									] 
				  	}
				';
			
        	$output.='
			}';
			
			
			//return ajax call
			//echo($output);*/
		}//end if plan is family
		else {
			$output='
    		{
        		"user":"'.$userid.'",
        		"firstname":"'.$first_name.'",
        		"surname":"'.$sur_name.'"
			}';
		}// end if no family plan
		
		echo($output);
	}
	else {
		
		 	echo "Login Failed";
			//session_destroy();
		
			die;
				
	}
/*
	} else
	{
		//$result2 = mysql_query("SELECT * FROM DOCTORS where IdMEDFIXEDNAME='$name' and IdMEDRESERV='$pass'");
		$result2 = mysql_query("SELECT * FROM usuarios where email='$name'");
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
			//$UserLogo = $row['ImageLogo'];
			$IdUsFIXED = $row2['IdUsFIXED'];
			$IdUsFIXEDNAME = $row2['IdUsFIXEDNAME'];
			$privilege=1;

			$_SESSION['Acceso']=23432;
			$_SESSION['UserID']=$UserID;
			$_SESSION['MEDID']=$UserID;
			
			$_SESSION['Nombre']=$name;
			$_SESSION['Password']=$pass;
			$_SESSION['Previlege']=1;
		
			//if ($MedUserRole=='1') $MedUserTitle ='Dr. '; else $MedUserTitle =' '; 
			}else {
			echo "Login Failed";
			session_destroy();
			die;			
			
			}
		}
		else
		{
			echo "Login Failed";
			session_destroy();
			die;
		}
	}
	
	//For checking multiple logins
	
	//echo 'SESSION: '.$_SESSION['MEDID'];
	
	$query = "select * from ongoing_sessions where userid=".$_SESSION['MEDID'];
	$result=mysql_query($query);
	$count=mysql_num_rows($result);
	$ip = $_SERVER['REMOTE_ADDR'];	
	if($count==0)
	{
		//die("Here");
		$q = "insert into ongoing_sessions values(".$_SESSION['MEDID'].",now(),'".$ip."')";
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
			echo "Login Failed";

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



echo $UserID;*/



?>