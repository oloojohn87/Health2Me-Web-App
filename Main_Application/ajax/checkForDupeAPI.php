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
  $name = $_GET['name'];
  $surname = $_GET['surname'];
  $dob = substr($_GET['dob'], 0, -2);
  $email = $_GET['email'];
  $phone = preg_replace('/[^0-9,]|,[0-9]*$/','',$_GET['phone']);

$query = $con->prepare("SELECT * FROM usuarios WHERE (Name = ? AND Surname = ? AND IdUsFIXED LIKE ?) OR (email = ? OR telefono LIKE ?)");
	$query->bindValue(1, $name, PDO::PARAM_STR);
	$query->bindValue(2, $surname, PDO::PARAM_STR);
	$query->bindValue(3, $dob.'%', PDO::PARAM_STR);
	$query->bindValue(4, $email, PDO::PARAM_STR);
	$query->bindValue(5, '%'.$phone.'%', PDO::PARAM_STR);

$result = $query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);

if($row['Name'] != ''){
$cadena.='
    {
        "id":"'.$row['Identif'].'",
        "name":"'.$row['Name'].'",
        "surname":"'.$row['Surname'].'",
        "email":"'.$row['email'].'",
		"phone":"'.$row['telefono'].'"
        }';
$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 
}

?>