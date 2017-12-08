<?php

require("environment_detail.php");
require("PasswordHash.php");
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

$pass = $_POST['pw'];

$correcthash = '';
if(isset($_POST['pat_id']))
{
    $user = $_POST['pat_id'];
    $res = $con->prepare("SELECT salt,IdUsRESERV FROM usuarios WHERE Identif=?");
    $res->bindValue(1, $user, PDO::PARAM_INT);
    $res->execute();

    $row = $res->fetch(PDO::FETCH_ASSOC);

    $correcthash = PBKDF2_HASH_ALGORITHM.":".PBKDF2_ITERATIONS.":".$row['salt'].":".$row['IdUsRESERV'];
}
if(isset($_POST['doc_id']))
{
    $doc = $_POST['doc_id'];
    $res = $con->prepare("SELECT salt,IdMEDRESERV FROM doctors WHERE id=?");
    $res->bindValue(1, $doc, PDO::PARAM_INT);
    $res->execute();
    $row = $res->fetch(PDO::FETCH_ASSOC);

    $correcthash = PBKDF2_HASH_ALGORITHM.":".PBKDF2_ITERATIONS.":".$row['salt'].":".$row['IdMEDRESERV'];
    
}

if(validate_password($pass,$correcthash))
{
    echo '1';
}
else
{
    echo '0';
}


?>