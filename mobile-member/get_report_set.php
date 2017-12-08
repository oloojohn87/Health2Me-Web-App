<?php
 header("Access-Control-Allow-Origin: *");
 require("../environment_detailForLogin.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		


$AdminData = 0;
$PastDx = 0;


$result = $con->prepare("SELECT * FROM tipopin order by Agrup");
$result->execute();
// WHERE IdPatient = '$UserID'");

$prevvalue = 0;
$m = 1;
$cadena = '';

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

	if ($row['Agrup'] != $prevvalue) 
	{
		$id[$m] = $row['Agrup'];
		$title[$m] = (htmlspecialchars($row['GroupName']));
		$abrev[$m] = (htmlspecialchars($row['NombreCorto']));
		$icon[$m] = $row['Icon'];
		$color[$m] = $row['Color'];

		if ($m>1) $cadena.=',';
		$cadena.='
	    {
	        "id":"'.$id[$m].'",
	        "title":"'.$title[$m].'",
	        "abrev":"'.$abrev[$m].'",
	        "icon":"'.$icon[$m].'",
	        "color":"'.$color[$m].'"
	        }';    
		$prevvalue = $row['Agrup'];
//		echo $m.' '.$id[$m].' '.$title[$m];
		$m++;

	}

};

$PastDx = 12;

$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 


//$encode = json_encode($cadena);
//echo $encode; 

?>