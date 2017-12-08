<?php
//include 'config.php';

 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="lifepin"; // Table name

$queUsu = $_GET['Usuario'];
//$queUsu = 32;

$sql = "select e.id, e.firstName, e.lastName, e.title, e.picture, count(r.id) reportCount " . 
		"from employee e left join employee r on r.managerId = e.id " .
		"group by e.id order by e.lastName, e.firstName";

//$sql="SELECT * FROM $tbl_name WHERE IdUsu = '$queUsu' AND approved != '0' ORDER BY Fecha DESC";
$sql="SELECT * FROM $tbl_name WHERE IdUsu = '$queUsu' AND (approved = '1' OR approved IS NULL) ORDER BY Fecha DESC";


try {
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->query($sql);  
	$employees = $stmt->fetchAll(PDO::FETCH_OBJ);
	$dbh = null;
	echo '{"items":'. json_encode($employees) .'}'; 
} catch(PDOException $e) {
	echo '{"error":{"text":'. $e->getMessage() .'}}'; 
}

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$dia = date("Y-m-d");
$hora = date("H:i:s");
$success='LM';
$result = $con->prepare("INSERT INTO userlog (idUs,date,time,mobile,success)
        VALUES (?,?,?,5,?)");
$result->bindValue(1, $queUsu, PDO::PARAM_INT);
$result->bindValue(2, $dia, PDO::PARAM_STR);
$result->bindValue(3, $hora, PDO::PARAM_STR);
$result->bindValue(4, $success, PDO::PARAM_STR);
$result->execute();
?>
