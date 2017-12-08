<?php session_start();
 require("environment_detailForLogin.php");
 require("PasswordHash.php");
 
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];



$NombreEnt = 'x';
$PasswordEnt = 'x';

$name=$_POST['Nombre'];
$pass=$_POST['Password'];

//Below SQl lines were commented by Pallab
/*$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");*/

//Below SQl lines were added by Pallab

// Connect to server and select databse.
 $con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

	
	//$result = mysql_query("SELECT * FROM doctors where IdMEDEmail='$name' and IdMEDRESERV='$pass'");
	//Below SQL queries commented by Pallab
    /*$result = mysql_query("SELECT * FROM doctors where IdMEDEmail='$name'"); 
	$count=mysql_num_rows($result);
	$row = mysql_fetch_array($result);*/
	
    //Below SQL lines were added by Pallab
    //Start of new code added by Pallab
    $result = $con->prepare("SELECT * FROM doctors where IdMEDEmail=?");
    $result->bindValue(1,$name,PDO::PARAM_STR);
    $result->execute();
    $count = $result->rowCount();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    //End of new code added by Pallab

    $success ='NO';
    if($count == 0)
    {
        //Below SQl queries were commented by Pallab
        /*$result = mysql_query("SELECT * FROM doctors where IdMEDFIXEDNAME='$name'"); 
	    $count=mysql_num_rows($result);
	    $row = mysql_fetch_array($result);*/
        
        $result = $con->prepare("SELECT * FROM doctors where IdMEDFIXEDNAME=?");
        $result->bindValue(1,$name,PDO::PARAM_STR);
        $result->execute();
        $count = $result->rowCount();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        
    }
    //DOCTOR FOUND
	if($count==1)
	{
		
		$IdMedRESERV=$row['IdMEDRESERV'];
		$addstring=$row['salt'];
		$correcthash=PBKDF2_HASH_ALGORITHM .":". PBKDF2_ITERATIONS. ":" . $addstring . ":" .$IdMedRESERV;
		if(validate_password($pass,$correcthash)){
            echo '1';
            
        }else
        {
            // LOG LOGIN FAILED
            $failed = $con->prepare("INSERT INTO login_attempts SET date = NOW(), username = ?, ip = ?, success = 0, type = 'DOC'");
            $failed->bindValue(1, $row['IdMEDFIXEDNAME'], PDO::PARAM_STR);
            $failed->bindValue(2, $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
            $failed->execute();
            echo '0';
        }
    }else if($count>1)
    {
        // LOG LOGIN FAILED
        $failed = $con->prepare("INSERT INTO login_attempts SET date = NOW(), username = ?, ip = ?, success = 0, type = 'DOC'");
        $failed->bindValue(1, $name, PDO::PARAM_STR);
        $failed->bindValue(2, $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $failed->execute();
        echo '-1';
    }
     else
     {
         // LOG LOGIN FAILED
        $failed = $con->prepare("INSERT INTO login_attempts SET date = NOW(), username = ?, ip = ?, success = 0, type = 'DOC'");
        $failed->bindValue(1, $name, PDO::PARAM_STR);
        $failed->bindValue(2, $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $failed->execute();
        echo '-2';
    }

?>
