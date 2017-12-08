<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="messages"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$queBlock = $_GET['queBlock'];            //idpin
$queUser = $_GET['queUser'];              //idusu
$queERU = $_GET['queERU'];                //evrupunt
$queEvento = $_GET['queEvento'];          //evento
$queTipo = $_GET['queTipo'];              //tipo
$idusfixed = $_GET['idusfixed'];          //idusfixed
$idusfixedname= $_GET['idusfixedname'];   //idusfixedname
$fecha = $_GET['fecha'];   
$Idmed=$_GET['idmed'];                //report date

//dev.health2.me/GrabaClasif.php?queBlock=3495&queUser=1485&queERU=2&queEvento=99&queTipo=30&idusfixed=820198211&idusfixedname=john.smith&fecha=2014-02-06 18:30:29&idmed=151

$month = strtok($fecha,"-");
$day = strtok("-");
$year = strtok("-");

/*
switch ($month)
{
case "January":  $mon="01";  break;
case "February":  $mon="02";  break;
case "March":  $mon="03";  break;
case "April":  $mon="04";  break;
case "May":  $mon="05";  break;
case "June":  $mon="06";  break;
case "July":  $mon="07";  break;
case "August":  $mon="08";  break;
case "September":  $mon="09";  break;
case "October":  $mon="10";  break;
case "November":  $mon="11";  break;
case "December":  $mon="12";  break;
}
*/
$dt=$year."-".$month."-".$day;

//BLOCKSLIFEPIN $q = mysql_query("UPDATE blocks SET EvRuPunt='$queERU', Evento='$queEvento', Tipo='$queTipo' WHERE id='$queBlock' ");

// update dicom session to set ready for transfer if applicable
$query = $con->prepare("SELECT isDicom FROM lifepin WHERE IdPin=?");
$query->bindValue(1, $queBlock, PDO::PARAM_INT);
$q = $query->execute();
$rft = 0;
if($row = $query->fetch(PDO::FETCH_ASSOC))
{
    if($row['isDicom'] == 1 && $queUser != 0)
    {
        $query = $con->prepare("UPDATE dicom_sessions SET ready_for_transfer=1 WHERE id=?");
		$query->bindValue(1, $queBlock, PDO::PARAM_INT);
		$q = $query->execute();
    }
}
$query = $con->prepare("UPDATE lifepin SET EvRuPunt=?, Evento=?, Tipo=?, suggestedid=?, idusfixed=?,idusfixedname=?,fecha=?,vs=1,IsPrivate=null WHERE IdPin=? ");
$query->bindValue(1, $queERU, PDO::PARAM_INT);
$query->bindValue(2, $queEvento, PDO::PARAM_INT);
$query->bindValue(3, $queTipo, PDO::PARAM_INT);
$query->bindValue(4, $queUser, PDO::PARAM_STR);
$query->bindValue(5, $idusfixed, PDO::PARAM_INT);
$query->bindValue(6, $idusfixedname, PDO::PARAM_STR);
$query->bindValue(7, $dt, PDO::PARAM_STR);
$query->bindValue(8, $queBlock, PDO::PARAM_INT);
$q = $query->execute();

$sql1=$con->prepare("Select IdPIN from doctorslinkusers where IdMed=? and IdUs=?");
$sql1->bindValue(1, $Idmed, PDO::PARAM_INT);
$sql1->bindValue(2, $queUser, PDO::PARAM_INT);
$q1= $sql1->execute();

$hasAccess=0;
while ($row = $sql1->fetch(PDO::FETCH_ASSOC)) {
    if(($row['IdPIN']==$queBlock)or($row['IdPIN']==null)){
        //echo 'Row: '.$row['IdPIN'];
        //echo '<br>';
        $hasAccess=1;
        break;
    }
}
//echo '<br>';
//echo 'AccessFlag'.$hasAccess;
if($hasAccess==0){
	$sql11=$con->prepare("insert into doctorslinkusers set IdMED=?,IdUs=?, IdPIN=? , estado=2 , Fecha=now(), confirm='Report created by Dr. ?'");
	$sql11->bindValue(1, $Idmed, PDO::PARAM_INT);
	$sql11->bindValue(2, $queUser, PDO::PARAM_INT);
	$sql11->bindValue(3, $queBlock, PDO::PARAM_INT);
	$sql11->bindValue(4, $Idmed, PDO::PARAM_INT);
	$q1= $sql11->execute();
}
echo "Success"
//die( $query);
?>