<?php
require("environment_detailForLogin.php");
require("PasswordHash.php");
require("displaySuccessClass.php");
require_once("displayExitClass.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$name=$_POST['Nombre'];
//$IdMed=$_POST['id'];
$queModo = 1;

//$confirm_code=md5(uniqid(rand()));

//Changes for adding encryption
$hashresult = explode(":", create_hash($_POST['Password']));
$IdMEDRESERV= $hashresult[3];
$additional_string=$hashresult[2];


$q = $con->prepare("Update doctors SET token='*********', IdMEDRESERV =?,salt=? where IdMEDEmail=?"); 
$q->bindValue(1, $IdMEDRESERV, PDO::PARAM_STR);
$q->bindValue(2, $additional_string, PDO::PARAM_STR);
$q->bindValue(3, $name, PDO::PARAM_STR);
$q->execute();

if($q){

    $exit_display = new displaySuccessClass();
    $exit_display->displayFunction(6);
    die;

    //$salto="location:PasswordResetSuccess.html";
    //header($salto);
}else{
    $exit_display = new displayExitClass();
    $exit_display->displayFunction(6);
    die;
    //$salto="location:Error.html";
    //header($salto);
}

?>
