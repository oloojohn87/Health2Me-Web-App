<?php
require("environment_detailForLogin.php");
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
  
$res = $con->prepare("SELECT * FROM consults WHERE Doctor_Status is null && (Patient_Status is null OR Patient_Status = 1) && Status = 'In Progress'");
$res->execute();

$now = new DateTime();
while($row = $res->fetch(PDO::FETCH_ASSOC)){
$date = new DateTime($row['DateTime']);

$diff_min = $date->diff($now)->format("%i");
$diff_hour = $date->diff($now)->format("%h");
$diff_day = $date->diff($now)->format("%d");
//echo $row['consultationId']."-".$diff_min."-".$diff_hour."-".$diff_day;

if(($diff_min > 5 or $diff_hour >= 1 or $diff_day >= 1) && ($row['Patient_Status'] != 1 && $row['Doctor_Status'] != 1)){
$res2 = $con->prepare("UPDATE consults SET Status = 'Not answered by member or doctor.' WHERE consultationId = '".$row['consultationId']."'");
$res2->execute();
}elseif(($diff_min > 5 or $diff_hour >= 1 or $diff_day >= 1) && ($row['Patient_Status'] == 1 && $row['Doctor_Status'] != 1)){
$res2 = $con->prepare("UPDATE consults SET Status = 'Not answered by doctor.' WHERE consultationId = '".$row['consultationId']."'");
$res2->execute();
}
} 
  
?>