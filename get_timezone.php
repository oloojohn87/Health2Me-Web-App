<?php 
//session_start();
require("environment_detail.php");
require("PasswordHash.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


//$UserID = $_SESSION['UserID'];

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$timezone = "-05:00:00";
$type = "user";
if (isset($_GET['timezone'])) {
     $timezone = $_GET['timezone'];  
     $email = $_GET['email'];
    if (isset($_GET['type'])) {
        $type = $_GET['type'];
    }    
}

    echo $type."\n";
    echo $timezone."\n";
    echo $email."\n";
 
    if ($type == "doctor"){
        $timezone = $_GET['timezone'];
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
        echo "UPDATE doctors SET timezone = '$result' where IdMEDEmail = '$email'";
	   $q = $con->prepare("UPDATE doctors SET timezone = ? where IdMEDEmail = ? "); 
	   $q->bindValue(1, $result, PDO::PARAM_STR);
	   $q->bindValue(2, $email, PDO::PARAM_STR);
	   $q->execute();

	   
    } else {
       $q = $con->prepare("UPDATE usuarios SET timezone = ? where email = ? "); 
	   $q->bindValue(1, $timezone, PDO::PARAM_STR);
	   $q->bindValue(2, $email, PDO::PARAM_STR);
	   $q->execute();
    }    

?>