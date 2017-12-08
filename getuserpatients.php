<?php
//include 'config.php';
session_start();
set_time_limit(150);

 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$userid = $_SESSION['MEDID'];
//$userid=38;
//$sql="select identif,idusfixedname from usuarios where idcreator in (select iddoctor from doctorsgroups where idgroup in (select idgroup from doctorsgroups where iddoctor =".$userid."))";
//$sql = "select identif,idusfixedname from usuarios where identif in (select idus from doctorslinkusers where idmed =".$userid." or idmed in (select iddoctor from doctorsgroups where idgroup in (select idgroup from doctorsgroups where iddoctor = ".$userid.")))";

//echo $sql;
/*
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
*/


$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	


/*$result = $con->prepare("select distinct(idus) from doctorslinkusers where  idmed = ? or idmed in (select iddoctor from doctorsgroups where idgroup in (select idgroup from doctorsgroups where iddoctor = ?))");
$result->bindValue(1, $userid, PDO::PARAM_INT);
$result->bindValue(2, $userid, PDO::PARAM_INT);
$result->execute();

$i = 0;*/

$result = $con->prepare("SELECT U.Identif, U.IdUsFIXEDNAME FROM (SELECT DISTINCT IdUs FROM doctorslinkusers WHERE IdMED = ? OR IdMED IN 
                        (
                            SELECT DISTINCT DG2.idDoctor FROM doctorsgroups DG1 INNER JOIN doctorsgroups DG2 ON DG1.idGroup = DG2.idGroup AND DG1.idDoctor = ?
                        )) AS DLU
                        LEFT JOIN usuarios U ON DLU.IdUs = U.Identif");
$result->bindValue(1, $userid, PDO::PARAM_INT);
$result->bindValue(2, $userid, PDO::PARAM_INT);
$result->execute();

$i = 0;


while($row = $result->fetch(PDO::FETCH_ASSOC))
{


    if($row['IdUsFIXEDNAME'] != NULL && $row['Identif'] != NULL)
    {
        $chat[$i] = $row;

        $i += 1;
    }

/*  
$res = $con->prepare("select identif,idusfixedname from usuarios where identif = ?");
$res->bindValue(1, $row['idus'], PDO::PARAM_INT);
$res->execute();

$num_rows = $res->rowCount();
if($num_rows==1)
{
    //echo 'Accepted '.$row['idus'];
    $row1 = $res->fetch(PDO::FETCH_ASSOC);
    //echo $row1['idusfixedname'];
    $chat[$i] = $row1;

    $i += 1;
}
else
{
    //echo 'Rejected '.$row['idus'];
}*/
}
  
  
$result = $con->prepare("SELECT U.Identif, U.IdUsFIXEDNAME FROM 
                        (
                            
                            SELECT DISTINCT IdPac FROM doctorslinkdoctors WHERE IdMED2 = ?
                        
                        ) AS DLD
                        
                        LEFT JOIN
                        
                        usuarios U
                        
                        ON
                            
                            DLD.IdPac = U.Identif");

$result->bindValue(1, $userid, PDO::PARAM_INT);
$result->execute();

while($row = $result->fetch(PDO::FETCH_ASSOC))
{
    /*
	$res = $con->prepare("select identif,idusfixedname from usuarios where identif = ?");
	$res->bindValue(1, $row['idpac'], PDO::PARAM_INT);
	$res->execute();
		
	$num_rows = $res->rowCount();
	if($num_rows==1)
	{
		//echo 'Accepted '.$row['idpac'];
		$row1 = $res->fetch(PDO::FETCH_ASSOC);
		$chat[$i]=$row1;
		$i += 1;
	}
	else
	{
		//echo 'Rejected '.$row['idpac'];
	}*/
    if($row['IdUsFIXEDNAME'] != NULL && $row['Identif'] != NULL)
    {
        $chat[$i] = $row;

        $i += 1;
    }

}

  
header('Content-type: application/json');
//$encode = json_encode($chat);
echo '{"items":'. json_encode($chat) .'}'; 


?>
