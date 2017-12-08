<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
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

$tbl_name="messages"; // Table name

	

$Idpin = $_GET['Idpin'];
$Iddoc= $_GET['Iddoc'];
$Idusu=$_GET['Idusu'];
//$state = $_GET['state'];
//$type= $_GET['type'];

//$sql1="SELECT Idmed FROM lifepin where IdUsu ='$Idusu' and IdMed IS NOT NULL and (IdMed NOT IN ((select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$Iddoc'))) 
//and IdMed NOT IN (Select idmed from doctorslinkdoctors where idmed2='$Iddoc' and IdPac='$Idusu'))";
//$q1=mysql_query($sql1);

//Query for finding all the blind reports for the doctor Idmed of patient IDusu- Debraj
if($Idpin==-111)
{
//$sql="select * from doctors where Id IN (SELECT Idmed FROM lifepin where IdUsu ='$Idusu' and IdMed IS NOT NULL and IdMed NOT IN ((select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$Iddoc'))) and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (Select idmed from doctorslinkdoctors where idmed2='$Iddoc' and IdPac='$Idusu'))))";
/*$sql="select * from doctors where Id IN (select distinct(idmed) from lifepin where IdUsu ='$Idusu' 
and (idmed not in (select distinct(idDoctor) from doctorsgroups where idGroup in (select idGroup from doctorsgroups where idDoctor='$Iddoc')) 
and idmed not in (select distinct(idDoctor) from doctorsgroups where idGroup in (select idGroup from doctorsgroups where idDoctor in (select idmed from doctorslinkdoctors where idmed2='$Iddoc' and idpac='$Idusu'))))
and idmed not in (select idmed from doctorslinkdoctors where idmed2='$Iddoc' and idpac='$Idusu'))";*/
$creator_array = array(-1);
$pre_sql = $con->prepare("(SELECT DISTINCT IdCreator FROM lifepin WHERE IdUsu = ?)");
$pre_sql->bindValue(1, $Idusu, PDO::PARAM_INT);
$pre_sql->execute();
while($sql_row = $pre_sql->fetch(PDO::FETCH_ASSOC))
{
    array_push($creator_array, $sql_row['IdCreator']);
}
$sql = "SELECT * FROM doctors WHERE id IN (".implode(',', $creator_array).")";
}else{
$creator_array = array(-1);
$pre_sql = $con->prepare("(SELECT DISTINCT IdCreator FROM lifepin WHERE IdPin = ?)");
$pre_sql->bindValue(1, $Idpin, PDO::PARAM_INT);
$pre_sql->execute();
while($sql_row = $pre_sql->fetch(PDO::FETCH_ASSOC))
{
    array_push($creator_array, $sql_row['IdCreator']);
}
$sql="select * from doctors where Id IN (".implode(',', $creator_array).")";
}

//$sql="select * from doctors where Id IN (SELECT Idmed FROM lifepin where IdUsu ='$Idusu' and IdMed IS NOT NULL and (IdMed NOT IN ((select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$Iddoc'))) and IdMed NOT IN (select distinct(idDoctor) where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (Select idmed from doctorslinkdoctors where idmed2='$Iddoc' and IdPac='$Idusu')))))";
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



?>