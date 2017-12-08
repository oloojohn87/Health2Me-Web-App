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

//$queUsu = $_GET['Doctor'];
//$groupid = $_GET['groupid'];



$cadena = array();
// create an array with all Doctor Ids that have are not in this group id

$result = $con->prepare("Select id,Name,Surname from doctors WHERE Name NOT LIKE '% %' AND Name IS NOT NULL AND Surname NOT LIKE '% %' AND Surname IS NOT NULL");
$result->execute();

//$row2 = $result->fetch(PDO::FETCH_ASSOC);
$counter1=0;
while ($row2 = $result->fetch(PDO::FETCH_ASSOC)) {
	$DocIds[$counter1] = $row2['id'];
	$DocName[$counter1] = $row2['Name'];
	$DocSurname[$counter1] = $row2['Surname'];
	
	array_push($cadena, array("id" => $DocIds[$counter1], "name" => $DocName[$counter1], "surname" => $DocSurname[$counter1], "value" => $DocName[$counter1].' '.$DocSurname[$counter1], "label" => $DocName[$counter1].' '.$DocSurname[$counter1]));
    

	$counter1++;
}

$encode = json_encode($cadena);
echo $encode;//'{"items":['.($cadena).']}'; 

?>