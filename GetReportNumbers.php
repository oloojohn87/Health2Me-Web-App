<?php
 require("environment_detail.php");
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

$UserID = $_GET['User'];

$AdminData = 0;
$PastDx = 0;
$Count = array_fill(0, 12, 0);

$result = $con->prepare("SELECT * FROM tipopin");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $idType = $row['Id'];
    $Group[$idType] = $row['Agrup'];
    if ($Group[$idType] == '999') $Group[$idType] = 4;
};

$result = $con->prepare("SELECT * FROM lifepin WHERE IdUsu = ? && Tipo != 77 && markfordelete IS NULL && hide_from_member = 0");
$result->bindValue(1, $UserID, PDO::PARAM_INT);
$result->execute();

$TotalReports = 0;
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	$TotalReports++;
	//$Group = substr($row['Tipo'], 0, 1);  // Not enough
    
    $Type = $row['Tipo']; 
	$Count[$Group[$Type]]++;
    //echo 'Type: '.$Type.'    Group: '.$Group[$Type].'    *******     ';

};

$cadena = '';
$m = 1;
while ($m < 10) {
		if ($m>1) $cadena.=',';
		$cadena.='
	    {
	        "number":"'.$Count[$m].'"
	        }';    
		$m++;
};

$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 



?>
