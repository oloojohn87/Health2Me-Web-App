<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
 

 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="usuarios"; // Table name

//$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
//mysql_select_db("$dbname")or die("cannot select DB");	

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }																								
																								
$queUsu = $_GET['Usuario'];
$IdDoc = $_GET['IdDoc'];



$result = $con->prepare("SELECT IdGroup FROM doctorsgroups WHERE IdDoctor = ?");
$result->bindValue(1, $IdDoc, PDO::PARAM_INT);
$result->execute();

$row = $result->fetch(PDO::FETCH_ASSOC);
$MyGroup = $row['IdGroup'];
//echo 'MY GROUP = '.$MyGroup.' **********  ';


$TotMsg = 0;
$TotUpDr = 0;
$TotUpUs = 0;
//$row = mysql_fetch_array($result);


//Get PAtients Connected Data
//$connQuery = "SELECT * FROM usuarios WHERE Password IS NOT NULL AND ( IdCreator = '$IdDoc' OR IdCreator IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = '$MyGroup') OR Identif IN (SELECT IdUs FROM doctorslinkusers WHERE IdMED = '$IdDoc')) AND Surname like '%$queUsu%' ORDER BY Surname ASC";
$connQuery = $con->prepare("SELECT * from (
select identif from usuarios where idcreator=? 
UNION
select identif from usuarios where idcreator in (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = ?) 
UNION
SELECT IdUs as identif FROM doctorslinkusers WHERE IdMED = '$IdDoc'
) as idus 
");
$connQuery->bindValue(1, $IdDoc, PDO::PARAM_INT);
$connQuery->bindValue(2, $MyGroup, PDO::PARAM_INT);
$connQuery->bindValue(3, $IdDoc, PDO::PARAM_INT);
$connQuery->execute();


//$connResult = mysql_query($connQuery);
$UConn=0;
while($row = $connQuery->fetch(PDO::FETCH_ASSOC))
{
	$patID = $row['identif'];
	$result = $con->prepare("select * from usuarios where identif=? and IdUsRESERV is not null");
	$result->bindValue(1, $patID, PDO::PARAM_INT);
	$result->execute();
	
	
	$count = $result->rowCount();
	if($count==1)
	{
		$UConn++;
	}
}

//$count=mysql_num_rows($connResult);
//$UConn =  $count;


//Get Probe Data
$probeQuery = $con->prepare("select * from probe where doctorid=?");
$probeQuery->bindValue(1, $IdDoc, PDO::PARAM_INT);
$probeQuery->execute();


//$probeResult = mysql_query($probeQuery);
$count = $probeQuery->rowCount();
$UProbe = $count;


//Get Patients Total Data
//$totalResult = mysql_query("SELECT * FROM usuarios WHERE Surname like '%$queUsu%' AND (IdCreator = '$IdDoc' OR IdCreator IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = '$MyGroup') OR Identif IN (SELECT IdUs FROM doctorslinkusers WHERE IdMED = '$IdDoc') OR Identif IN (SELECT IdPac FROM doctorslinkdoctors WHERE IdMED2 = '$IdDoc')) ORDER BY Surname ASC");
$totalQuery = $con->prepare("SELECT * from (
select identif from usuarios where idcreator=?
UNION
select identif from usuarios where idcreator in (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = ?)
UNION
SELECT IdUs as identif FROM doctorslinkusers WHERE IdMED = ?
UNION
SELECT IdPac as identif FROM doctorslinkdoctors WHERE IdMED2 = ?
) as idus; 
");
$totalQuery->bindValue(1, $IdDoc, PDO::PARAM_INT);
$totalQuery->bindValue(2, $MyGroup, PDO::PARAM_INT);
$totalQuery->bindValue(3, $IdDoc, PDO::PARAM_INT);
$totalQuery->bindValue(4, $IdDoc, PDO::PARAM_INT);
$totalQuery->execute();


$totalResult = $totalQuery->rowCount();
$count=$totalResult;
$UTotal =  $count;

while($row = $totalQuery->fetch(PDO::FETCH_ASSOC))
{
	$PatientId = $row['identif'];
	
	//Get Total Messages
	$result2 = $con->prepare("SELECT * FROM message_infrastructureuser WHERE sender_id = ? AND patient_id = ? AND status='new' ");
	$result2->bindValue(1, $IdDoc, PDO::PARAM_INT);
	$result2->bindValue(2, $PatientId, PDO::PARAM_INT);
	$result2->execute();

	
    $count2=$result2->rowCount();
	
	if ($count2 >0)
    {    
        $visible_baloon = 'visible';
        $unread = $count2;
        $message_color ='#22aeff';
        $addiclass='CENVELOPE';
    } 
    else
    {
        $visible_baloon = 'hidden';
        $unread = 0;
        $message_color ='#e0e0e0';
        $addiclass='';
    }
    $TotMsg = $TotMsg + $unread;
	
	//TotUpDr
	$result2 = $con->prepare("SELECT * FROM bpinview USE INDEX(I1) WHERE VIEWIdUser = ? AND VIEWIdMed <> ? AND (DateTimeSTAMP BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) AND (Content = 'Report Uploaded') ");
	$result2->bindValue(1, $PatientId, PDO::PARAM_INT);
	$result2->bindValue(2, $PatientId, PDO::PARAM_INT);
	$result2->execute();

    $count2=$result2->rowCount();
    if ($count2 >0)
    {    
        $visible_baloon3 = 'visible';
        $NewRepDoc = $count2;
        $repactivUpDr_color ='#22aeff';
    } 
    else
    {
        $visible_baloon3 = 'hidden';
        $NewRepDoc = 0;
        $repactivUpDr_color ='#e0e0e0';
    }
    $TotUpDr = $TotUpDr + $NewRepDoc;
    
	//TotUpUs
	$result2 = $con->prepare("SELECT * FROM bpinview USE INDEX(I1) WHERE VIEWIdUser = ? AND VIEWIdMed = ? AND (DateTimeSTAMP BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) AND (Content = 'Report Uploaded') ");
	$result2->bindValue(1, $PatientId, PDO::PARAM_INT);
	$result2->bindValue(2, $PatientId, PDO::PARAM_INT);
	$result2->execute();
	
    $count2=$result2->rowCount();
    if ($count2 >0)
    {    
        $visible_baloon2 = 'visible';
        $NewRepPat = $count2;
        $repactivUpUser_color ='#54bc00';
    } 
    else
    {
        $visible_baloon2 = 'hidden';
        $NewRepPat =0;
        $repactivUpUser_color ='#e0e0e0';
    }
    $TotUpUs = $TotUpUs + $NewRepPat;
    
	

}


echo $UConn."::".$UTotal."::".$UProbe."::".$TotMsg."::".$TotUpDr."::".$TotUpUs;

?>