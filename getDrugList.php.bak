<?php
 
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


$cadena = '';
$query = $con->prepare("select * from drugs");

$result = $query->execute();
$count=$query->rowCount();
$counter1 = 0 ;
while ($row2 = $query->fetch(PDO::FETCH_ASSOC)) {
	$DrugID[$counter1] = $row2['drugcode'];
	$DrugName[$counter1] = rtrim($row2['drugname']);
	
	if ($counter1>0) $cadena.=',';    
	$cadena.='
    {
        "id":"'.$DrugID[$counter1].'",
        "name":"'.$DrugName[$counter1].'",
        "value":"'.$DrugName[$counter1].'",
        "label":"'.$DrugName[$counter1].'"
        }';    

	$counter1++;
}

$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 


?>