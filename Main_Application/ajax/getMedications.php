<?php
 session_start();
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];



// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$queUsu = $_GET['IdUsu']; //Changing it to $_GET from $_SESSION for fixing no data found in medications area



$query = $con->prepare("SELECT * FROM p_medication WHERE idpatient=?");
$query->bindValue(1, $queUsu, PDO::PARAM_INT);


$result = $query->execute();
$count=$query->rowCount();
$counter1 = 0 ;
$cadena = '';
while ($row2 = $query->fetch(PDO::FETCH_ASSOC)) {
	$Id[$counter1] = $row2['id'];
	$DrugName[$counter1] = $row2['drugname'];
	$DrugId[$counter1] = $row2['drugcode'];
	$frequency[$counter1] = $row2['frequency'];
	$dossage[$counter1] = $row2['dossage'];
	$deleted[$counter1] = $row2['deleted'];

	if ($counter1>0) $cadena.=',';    
	$cadena.='
    {
        "id":"'.$Id[$counter1].'",
        "drugname":"'.$DrugName[$counter1].'",
        "drugcode":"'.$DrugId.'",
        "dossage":"'.$frequency[$counter1].'",
        "frequency":"'.$dossage[$counter1].'",
        "deleted":"'.$deleted[$counter1].'"
        }';    

	$counter1++;
}

$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 


?>
