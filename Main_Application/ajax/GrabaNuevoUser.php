<?php
//$dbhost="54.225.226.163"; // Host name
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="usuarios"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$quePin = $_GET['IdPin'];
$queIdUsFIXED = $_GET['IdUsFIXED'];
$queIdUsFIXEDNAME = $_GET['IdUsFIXEDNAME'];
$queIdMed = $_GET['IdMed'];

$Nombre = strstr($queIdUsFIXEDNAME, '.', true);
$ApePRE = strstr($queIdUsFIXEDNAME, '.');
$Apellido = substr($ApePRE,1,strlen($ApePRE));

$duplicado = "";
$q = $con->prepare("SELECT * FROM usuarios WHERE  IdUsFIXEDNAME =? ");
$q->bindValue(1, $queIdUsFIXEDNAME, PDO::PARAM_STR);
$q->execute();   
   
$count=$q->rowCount();
if ($count>0) $duplicado = "same IdUsFIXEDNAME = ".$count." ";
$q = $con->prepare("SELECT * FROM usuarios WHERE  IdUsFIXED =? ");  
$q->bindValue(1, $queIdUsFIXED, PDO::PARAM_INT);
$q->execute();
 
$count2=$q->rowCount();
if ($count2>0) $duplicado = $duplicado."same IdUsFIXED = ".$count2." ";

if ($count>0 || $count2>0) 
{

echo "USER ALREADY EXISTS: ".$queIdUsFIXEDNAME." -".$queIdUsFIXED.". (".$duplicado.").";

}
else
{


$q = $con->prepare("INSERT INTO usuarios SET Alias=?, IdUsFIXEDNAME =?, IdUsFIXED =? , Name=?, Surname=?, IdCreator=? ");  
$q->bindValue(1, $queIdUsFIXEDNAME, PDO::PARAM_STR);
$q->bindValue(2, $queIdUsFIXEDNAME, PDO::PARAM_STR);
$q->bindValue(3, $queIdUsFIXED, PDO::PARAM_INT);
$q->bindValue(4, $Nombre, PDO::PARAM_STR);
$q->bindValue(5, $Apellido, PDO::PARAM_STR);
$q->bindValue(6, $queIdMed, PDO::PARAM_INT);
$q->execute();
 
$IdMsg = mysql_insert_id();

echo "ID OBTENIDO = ".$IdMsg;

$queModo = 1;

if ($queModo == '1')
{
	$q = $con->prepare("UPDATE lifepin SET IdUsu=?, suggestedid=?, IdUsFIXED=?, IdUsFIXEDNAME=?, ValidationStatus=0 , NextAction='OK. Data assigned to user by doctor.',vs=1  WHERE IdPin=?"); 
    $q->bindValue(1, $IdMsg, PDO::PARAM_INT);
	$q->bindValue(2, $IdMsg, PDO::PARAM_STR);
	$q->bindValue(3, $queIdUsFIXED, PDO::PARAM_INT);
	$q->bindValue(4, $queIdUsFIXEDNAME, PDO::PARAM_STR);
	$q->bindValue(5, $quePin, PDO::PARAM_INT);
	$q->execute();
	
    //LogMov($IdUsFIXED, $fromname, $fromaddress, '3', 'File Upload', 'FTP file UPLOADED SUCCESSFULLY', $subject, $email_number);
    
    // Se inserta un registro de autorización (TIPO 1: MÉDICO HA CREADO AL PACIENTE (ACCESO RESTRINGIDO) 
    $queConf = 'Created by Dr., Id= '.$queIdMed;
    $q = $con->prepare("INSERT INTO doctorslinkusers SET IdMED=?, IdUs=?, Fecha=NOW(), Estado=2, Confirm=?, Tipo=1  ");  
	$q->bindValue(1, $queIdMed, PDO::PARAM_INT);
	$q->bindValue(2, $IdMsg, PDO::PARAM_INT);
	$q->bindValue(3, $queConf, PDO::PARAM_STR);
	$q->execute();
    
    // update dicom session to set ready for transfer if applicable
    $query = $con->prepare("SELECT isDicom FROM lifepin WHERE IdPin=?");
	$query->bindValue(1, $quePin, PDO::PARAM_INT);
	$q_res = $query->execute();
	
    $rft = 0;
    if($row = $query->fetch(PDO::FETCH_ASSOC))
    {
        if($row['isDicom'] == 1 && $IdMsg != 0)
        {
            $query = $con->prepare("UPDATE dicom_sessions SET ready_for_transfer=1 WHERE id=?");
			$query->bindValue(1, $quePin, PDO::PARAM_INT);
			$q_res = $query->execute();
        }
    } 

}
else
{
	$q = $con->prepare("UPDATE lifepin SET IdUsu=?, suggestedid=?, IdUsFIXED=?, IdUsFIXEDNAME=?, ValidationStatus=0 , NextAction='OK. Data assigned to user by doctor.',vs=1  WHERE IdPin=?");
	$q->bindValue(1, $IdMsg, PDO::PARAM_INT);
	$q->bindValue(2, $IdMsg, PDO::PARAM_STR);
	$q->bindValue(3, $queIdUsFIXED, PDO::PARAM_INT);
	$q->bindValue(4, $queIdUsFIXEDNAME, PDO::PARAM_STR);
	$q->bindValue(5, $quePin, PDO::PARAM_INT);
	$q->execute();
	
    //LogMov($IdUsFIXED, $fromname, $fromaddress, '3', 'File Upload', 'FTP file UPLOADED SUCCESSFULLY', $subject, $email_number);

    // Se inserta un registro de autorización (TIPO 1: MÉDICO HA CREADO AL PACIENTE (ACCESO RESTRINGIDO) 
    $queConf = 'Created by Dr., Id= '.$queIdMed;
    $q = $con->prepare("INSERT INTO doctorslinkusers SET IdMED=?, IdUs=?, Fecha=NOW(), Estado=2, Confirm=?, Tipo=1  ");   
	$q->bindValue(1, $queIdMed, PDO::PARAM_INT);
	$q->bindValue(2, $IdMsg, PDO::PARAM_INT);
	$q->bindValue(3, $queConf, PDO::PARAM_STR);
	$q->execute();
}


}
?>