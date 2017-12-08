<?php
 session_start();
 require("environment_detail.php");
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

$email = $_POST['email'];
$type = $_POST['type'];
$idpatient = -1;
if(isset($_POST['IdUsu']) && $_POST['IdUsu'] != NULL && $_POST['IdUsu'] != 0)
{
    $idpatient = $_POST['IdUsu'];
}
else if(isset($_SESSION['UserID']))
{
    $idpatient = $_SESSION['UserID'];
}
$iddoctor = -1;
if(isset($_POST['IdMed']) && $_POST['IdMed'] != NULL && $_POST['IdMed'] != 0)
{
    $iddoctor = $_POST['IdMed'];
}
else if(isset($_SESSION['MEDID']))
{
    $iddoctor = $_SESSION['MEDID'];
}
$result = 0;

if($at = strstr($email, '@'))
{
    if($dot = strstr($at, '.'))
    {
        $result = 1;
    }
    else
    {
        $result = 2;
    }
}
else
{
    $result = 2;
}

if($result == 1)
{
    if($type == '0')
    {
        // patients
        
        $query = $con->prepare("SELECT Identif FROM usuarios WHERE email = ? AND Identif <> ?");
        $query->bindValue(1, $email, PDO::PARAM_STR);
        $query->bindValue(2, $idpatient, PDO::PARAM_INT);
        $query->execute();
        $count = $query->rowCount();
        if($count == 0)
        {
            $result = 1;
        }
        else
        {
            $result = 0;
        }
    }
    else if($type == '1')
    {
        // doctors
        
        $query = $con->prepare("SELECT id FROM doctors WHERE IdMEDEmail = ? AND id <> ?");
        $query->bindValue(1, $email, PDO::PARAM_STR);
        $query->bindValue(2, $iddoctor, PDO::PARAM_INT);
        $query->execute();
        $count = $query->rowCount();
        if($count == 0)
        {
            $result = 1;
        }
        else
        {
            $result = 0;
        }
    }
}

echo $result;
?>