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
$password_override = false;
$null_pass = false;


// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

	
	//$result = mysql_query("SELECT * FROM doctors where IdMEDEmail='$name' and IdMEDRESERV='$pass'");

	$result = $con->prepare("SELECT * FROM usuarios where email=? OR Alias = ?"); 
	$result->bindValue(1, $name, PDO::PARAM_STR);
	$result->bindValue(2, $name, PDO::PARAM_STR);
	$result->execute();
	
	$count=$result->rowCount();
	$row = $result->fetch(PDO::FETCH_ASSOC);

	$identif = $row['Identif'];

	$success ='NO';
    if($count == 0)
    {
        $result = $con->prepare("SELECT * FROM usuarios where IdUsRESERV=?"); 
		$result->bindValue(1, $name, PDO::PARAM_STR);
		$result->execute();
		
	    $count=$result->rowCount();
	    $row = $result->fetch(PDO::FETCH_ASSOC);
    }
    //DOCTOR FOUND
	if($count==1)
	{
		
		$IdUsRESERV=$row['IdUsRESERV'];

		if($IdUsRESERV == null){
			$temp_pass = $con->prepare("SELECT * FROM key_chain where short_hash=?"); 
			$temp_pass->bindValue(1, $pass, PDO::PARAM_STR);
			$temp_pass->execute();
			$row_temp_pass = $temp_pass->fetch(PDO::FETCH_ASSOC);
			$temp_pass_id = $row_temp_pass['owner'];
			$null_pass = true;
			$override = $identif.'+'.$temp_pass_id.'+'.$pass;
			
			if($identif == $temp_pass_id){
				$password_override = true;
				$override = 'true';
			}
		}else{
			$password_override = false;
			$override = 'false';
		}
		$addstring=$row['salt'];
		if(!$password_override && !$null_pass)$correcthash=PBKDF2_HASH_ALGORITHM .":". PBKDF2_ITERATIONS. ":" . $addstring . ":" .$IdUsRESERV;
		if(validate_password($pass,$correcthash) || $password_override){


            echo '1';
            
        }else
            echo '0';
    }else if($count>1){echo '-1';}
    else{
        echo '-2';
    }



?>
