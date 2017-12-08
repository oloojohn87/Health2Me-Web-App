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



$query = $con->prepare("SELECT * FROM p_family WHERE idpatient=?");
$query->bindValue(1, $queUsu, PDO::PARAM_INT);
$result = $query->execute();

$count = $query->rowCount();
$counter1 = 0 ;
$cadena = '';
while ($row2 = $query->fetch(PDO::FETCH_ASSOC)) {
	$Id[$counter1] = $row2['id'];
	$relative[$counter1] = $row2['relative'];
	$disease[$counter1] = $row2['disease'];
	$diseasegroup[$counter1] = $row2['diseasegroup'];
	$ICD10[$counter1] = $row2['ICD10'];
	$ICD9[$counter1] = $row2['ICD9'];
	$atage[$counter1] = $row2['atage'];
	$alive[$counter1] = $row2['alive'];
	$deleted[$counter1] = $row2['deleted'];

	if ($counter1>0) $cadena.=',';    
	$cadena.='
    {
        "id":"'.$Id[$counter1].'",
        "relative":"'.$relative[$counter1].'",
        "disease":"'.$disease[$counter1].'",
        "diseasegroup":"'.$diseasegroup[$counter1].'",
        "ICD10":"'.$ICD10[$counter1].'",
        "ICD9":"'.$ICD9[$counter1].'",
        "atage":"'.$atage[$counter1].'",
        "alive":"'.$alive[$counter1].'",
        "deleted":"'.$deleted[$counter1].'"
        }';    

	$counter1++;
}

$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 


?>
