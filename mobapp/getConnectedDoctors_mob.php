<?php

 require("../environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="DOCTORS"; // Table name

$USERID = $_GET['UESRID'];
//$queUsu = 32;

// Connect to server and select databse.
$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

$result = mysql_query("select distinct(IdMED) from doctorslinkusers where IdUs='$USERID' and estado=2");
$count=mysql_num_rows($result);
$i=0;
while($row = mysql_fetch_array($result)){

	$idmed=$row['IdMED'];
	$res = mysql_query("select Name,Surname from doctors where id='$idmed'");
	$num_rows = mysql_num_rows($res);
	if($num_rows==1)
	{
		
		$row1 = mysql_fetch_array($res,MYSQL_ASSOC);
		//echo 'Accepted '.$row1['Name'];
		$doctor[$i]=$row1;
		$i += 1;
	}
}

header('Content-type: application/json');
$encode = json_encode($doctor);
echo '{"items":'. json_encode($doctor) .'}'; 

/*try {
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->query($sql);  
	$doctors = $stmt->fetchAll(PDO::FETCH_OBJ);
	$dbh = null;
	echo '{"items":'. json_encode($doctor) .'}'; 
} catch(PDOException $e) {
	echo '{"error":{"text":'. $e->getMessage() .'}}'; 
}*/
    

?>