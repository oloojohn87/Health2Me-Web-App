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

 


$id = $_GET['id'];

if(isset($_GET['show'])){
$query = $con->prepare("SELECT * FROM script_practices WHERE id = ?");
$query->bindValue(1, $id, PDO::PARAM_INT);
$result = $query->execute();

$row = $query->fetch(PDO::FETCH_ASSOC);

        $arr = '{"practice":"'.$row["name"].'","id":"'.$row['id'].'"}';
     $cadena = json_encode($arr);
	 echo '{"items":['.$arr.']}'; 
}else{

$query = $con->prepare("SELECT * FROM script_locations WHERE id = ?");
$query->bindValue(1, $id, PDO::PARAM_INT);
$result = $query->execute();

$row = $query->fetch(PDO::FETCH_ASSOC);
$cname = $row["clinic_name"];

        $arr = '{"cname":"'.$row["clinic_name"].'","pname":"'.$row["practice_name"].'","address1":"'.$row["address1"].'","address2":"'.$row["address2"].'","city":"'.$row["city"].'","state":"'.$row["state"].'","zip":"'.$row["zip"].'","phone":"'.$row["phone"].'","fax":"'.$row["fax"].'","id":"'.$row['id'].'"}';
     $cadena = json_encode($arr);
	 echo '{"items":['.$arr.']}'; 
	 }
?>