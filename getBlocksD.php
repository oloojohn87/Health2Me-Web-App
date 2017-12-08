<?php
//include 'config.php';

 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

//blockslifepin$tbl_name="BLOCKS"; // Table name
$tbl_name="lifepin"; // Table name


$queUsu = $_GET['Usuario'];
//$queUsu = 32;

//$sql="SELECT * FROM $tbl_name WHERE IdUsu = '$queUsu' AND approved != '0' ORDER BY Fecha DESC";
$sql="SELECT * FROM $tbl_name ORDER BY FechaInput DESC LIMIT 20";


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
mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

$dia = date("Y-m-d");
$hora = date("H:i:s");
$success='LM';
//mysql_query("INSERT INTO userlog (idUs,date,time,mobile,success)
//        VALUES ('$queUsu','$dia','$hora',5,'$success')");
?>
