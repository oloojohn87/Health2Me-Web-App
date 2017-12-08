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

$queUsu = $_GET['IdUsu'];

$query = $con->prepare("SELECT * FROM basicemrdata WHERE IdPatient=?");
$query->bindValue(1, $queUsu, PDO::PARAM_INT);
$result = $query->execute();

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
	$DOB = substr($row['DOB'],0,10);
}

$cadena='
    {
        "id":"'.$queUsu.'",
        "dob":"'.$DOB.'"
        }';    


$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 


?>