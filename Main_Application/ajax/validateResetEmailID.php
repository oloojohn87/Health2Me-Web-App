<?php

require("environment_detailForLogin.php");
//require("PasswordHash.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$token = $_GET['token'];
$whichone = $_GET['whichone'];
//$email=$_GET['ID'];
//$email='';
$res='';
//1 for doctor and 0 for patients
if($whichone==1){
    $res = $con->prepare("SELECT id,IdMEDEmail FROM doctors WHERE token=?");


    $res->bindValue(1, $token, PDO::PARAM_STR);
    $res->execute();
    $count = $res->rowCount();
    
    if($count==1){
        $row = $res->fetch(PDO::FETCH_ASSOC);
        $email=$row['IdMEDEmail'];
        echo $email;
        
    }else{
        echo '0';
    }
    
  
    //if($email==$row['IdMEDEmail']){echo '1';}else{echo '0';}
    //echo $email;
    //$res = $con->prepare("SELECT Identif FROM usuarios WHERE Identif=?");
}else if($whichone==0){
    $res = $con->prepare("SELECT Identif,email FROM usuarios WHERE confirmcode=?");


    $res->bindValue(1, $token, PDO::PARAM_STR);
    $res->execute();
    $count = $res->rowCount();
    
    if($count==1){
        $row = $res->fetch(PDO::FETCH_ASSOC);
        $email=$row['email'];
        echo $email;
        
    }else{
        echo '0';
    }
    
    //$row = $res->fetch(PDO::FETCH_ASSOC);

    //if($email==$row['email']){echo '1';}else{echo '0';}
}

?>
