<?php
 
 require("environment_detailForLogin.php");
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
$query = $con->prepare("SELECT * FROM usuarios");
//$query = $con->prepare("SELECT * FROM usuarios WHERE (Surname like ? or name like ? or IdUsFIXEDNAME like ?)");
//	$query->bindValue(2, '%'.$queUsu.'%', PDO::PARAM_STR);
//	$query->bindValue(3, '%'.$queUsu.'%', PDO::PARAM_STR);
//	$query->bindValue(4, '%'.$queUsu.'%', PDO::PARAM_STR);

$result = $query->execute();
//$count=$query->rowCount();
$counter1 = 0 ;
while ($row2 = $query->fetch(PDO::FETCH_ASSOC)) {
	$ID[$counter1] = $row2['Identif'];
	if(isset($_GET['name'])){
	$Name[$counter1] = (htmlspecialchars($row2['Name']));
	}
	if(isset($_GET['surname'])){
	$Name[$counter1] = (htmlspecialchars($row2['Surname']));
	}
	if(isset($_GET['email'])){
	$Name[$counter1] = (htmlspecialchars($row2['email']));
	}
	if(isset($_GET['phone'])){
	if($row2['telefono'] == null)
	{
	$Name[$counter1] = '';
	}else{
	$Name[$counter1] = (htmlspecialchars($row2['telefono']));
	}
	}
	
	if ($counter1>0) $cadena.=',';    
	$cadena.='
    {
        "id":"'.$ID[$counter1].'",
        "name":"'.$Name[$counter1].'",
        "value":"'.$Name[$counter1].'",
        "label":"'.$Name[$counter1].'"
        }';    

	$counter1++;
}

$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 


?>