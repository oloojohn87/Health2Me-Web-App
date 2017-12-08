<?php

require("environment_detail.php");
$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$id = $_POST['id'];

if(isset($_POST['timezone']))
{
    $timezone = $_POST['timezone'];
    $result = '';
    if($timezone[0] == '-')
    {
        $result = '-';
    }
    if(abs(floatval($timezone)) < 10.0)
    {
        $result .= '0';
    }
    $hours = floor(abs(floatval($timezone)));
    $minutes = intval((abs(floatval($timezone)) - $hours) * 60);
    $result .= $hours.':';
    if($minutes < 10)
    {
        $result .= '0';
    }
    $result .= $minutes.':00';
    $res = $con->prepare("UPDATE usuarios SET timezone=? WHERE Identif=?");
	$res->bindValue(1, $result, PDO::PARAM_STR);
	$res->bindValue(2, $id, PDO::PARAM_INT);
	$res->execute();
	
    return 1;
}
else if(isset($_POST['location']))
{
    $location1 = str_replace(",", ":", $_POST['location']);
    $loc = '';
    if(isset($_POST['location2']) && strlen($_POST['location2']) > 0)
    {
        $loc = $_POST['location2'].', ';
    }
    $loc .= $location1;
    $res = $con->prepare("UPDATE usuarios SET location=? WHERE Identif=?");
	$res->bindValue(1, $loc, PDO::PARAM_STR);
	$res->bindValue(2, $id, PDO::PARAM_INT);
	$res->execute();
	
        
}
else if(isset($_POST['email']) || isset($_POST['phone']))
{
    $updateFlag = true;
    $email = '';
    if(strlen($_POST['email']) > 0)
    {
        $email = $_POST['email'];
    }
    $phone = '';
    if(strlen($_POST['phone']) > 0)
    {
        $phone = $_POST['phone'];
    }
    
    // email verification:
    // first, check if the given email is already the user's email
    $result = $con->prepare("SELECT email FROM usuarios WHERE Identif = ?");
    $result->bindValue(1, $id, PDO::PARAM_INT);
    $result->execute();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $current_user_email = $row['email'];
    if($current_user_email != $email && $email != '')
    {
        // if it is don't do anything, but if it is, check if the given email is being used by another user
        $result = $con->prepare("SELECT Identif FROM usuarios WHERE email = ?");
        $result->bindValue(1, $email, PDO::PARAM_STR);
        $result->execute();
        $count_rows = $result->rowCount();
        if($count_rows > 0)
        {
            // if there is at least one result, that means another doctor is already using this email. so change the update value to his current email
            $email = $current_user_email;
            $updateFlag = false;
            echo "This email is already used by another user";
        }
        
        
    }
    if($_POST['email'] != ''){
		if($email_1 = strstr($email, '@'))
		{
			if($email_2 = strstr($email_1, '.'))
			{
				$updateFlag = true;
			}
			else
			{
				$updateFlag = false;
				echo "Invalid Email";
			}
		}
		else
		{
			$updateFlag = false;
			echo "Invalid Email";
		}
	}
    
    $phone = str_replace("-", "", $phone);
    $phone = str_replace(" ", "", $phone);
    $phone = str_replace("+", "", $phone);
    
    if(strlen($phone) == 0 && $email == '')
    {
        $updateFlag = false;
        echo "Invalid Phone Number";
    }
    if($updateFlag)
    {
        $res = $con->prepare("UPDATE usuarios SET email = ?, telefono = ? WHERE Identif = ?");
        $res->bindValue(1, $email, PDO::PARAM_STR);
        $res->bindValue(2, $phone, PDO::PARAM_STR);
        $res->bindValue(3, $id, PDO::PARAM_INT);
        $res->execute();
    }
	
        
}
?>
