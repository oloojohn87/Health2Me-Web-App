<?php

if(!defined('INCLUDE_CHECK')) die('You are not allowed to execute this file directly');

function LogMov($IdUsuario, $OrigenName, $OrigenAddress, $CodNum, $CodAlfa, $Contenido, $subject , $emailno)
{
	$retorno = array();
	$MensajeError = "";

	$Fecha = date ('Y-m-d H:i:s');	

 require("environment_detailForLogin.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 $tbl_name="usuarios"; // Table name
					
// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		


	$q = $con->prepare("INSERT INTO logmbe SET UsId=?, Fecha=?, fromname=?, fromaddress=?, CodLog=?, AlfaLog=?, Contenido=?, Subject=?, emailno=?");
	$q->bindValue(1, $IdUsuario, PDO::PARAM_INT);
	$q->bindValue(2, $Fecha, PDO::PARAM_STR);
	$q->bindValue(3, $OrigenName, PDO::PARAM_STR);
	$q->bindValue(4, $OrigenAddress, PDO::PARAM_STR);
	$q->bindValue(5, $CodNum, PDO::PARAM_INT);
	$q->bindValue(6, $CodAlfa, PDO::PARAM_STR);
	$q->bindValue(7, $Contenido, PDO::PARAM_STR);
	$q->bindValue(8, $subject, PDO::PARAM_STR);
	$q->bindValue(9, $emailno, PDO::PARAM_INT);
	$q->execute();
	

echo mysql_errno($con) . ": " . mysql_error($con). "\n";
	
/*
	$retorno[0]=$Cambio;					//PUNTOS SUMADOS AHORA
	$retorno[1]=$Cambio / 50;				//CREDITOS SUMADOS AHORA
	$retorno[2]= $NuevoSaldoPrevio;			//SALDO EN PUNTOS  	 ACTUAL
	$retorno[3]= $NumeroCreditos;			//SALDO EN CREDITOS  ACTUAL
	$retorno[4]= $SaldoPrev;				//SALDO EN PUNTOS  	 **ANTERIOR**
	$retorno[5]= $NumeroCreditosAnt;		//SALDO EN CREDITOS  **ANTERIOR**
	$retorno[6]= $FechaPrevia;				//FECHA DE LA ENTRADA ANTERIOR
	$retorno[7]= $MensajeError;				//MENSAJE
*/

	
return $retorno;
}

function LogMsg($IdMsg, $IdUsuario, $OrigenName, $OrigenAddress, $CodNum, $CodAlfa, $Contenido, $subject , $emailno)
{
	$retorno = array();
	$MensajeError = "";

	$Fecha = date ('Y-m-d H:i:s');	

		 require("environment_detailForLogin.php");		
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

					$tbl_name="usuarios"; // Table name
					
					// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	


	$q = $con->prepare("INSERT INTO logmbe SET UsId=?, Fecha=?, fromname=?, fromaddress=?, CodLog=?, AlfaLog=?, Contenido=?, Subject=?, emailno=?");
	$q->bindValue(1, $IdUsuario, PDO::PARAM_INT);
	$q->bindValue(2, $Fecha, PDO::PARAM_STR);
	$q->bindValue(3, $OrigenName, PDO::PARAM_STR);
	$q->bindValue(4, $OrigenAddress, PDO::PARAM_STR);
	$q->bindValue(5, $CodNum, PDO::PARAM_INT);
	$q->bindValue(6, $CodAlfa, PDO::PARAM_STR);
	$q->bindValue(7, $Contenido, PDO::PARAM_STR);
	$q->bindValue(8, $subject, PDO::PARAM_STR);
	$q->bindValue(9, $emailno, PDO::PARAM_INT);
	$q->execute();

echo mysql_errno($con) . ": " . mysql_error($con). "\n";
	
/*
	$retorno[0]=$Cambio;					//PUNTOS SUMADOS AHORA
	$retorno[1]=$Cambio / 50;				//CREDITOS SUMADOS AHORA
	$retorno[2]= $NuevoSaldoPrevio;			//SALDO EN PUNTOS  	 ACTUAL
	$retorno[3]= $NumeroCreditos;			//SALDO EN CREDITOS  ACTUAL
	$retorno[4]= $SaldoPrev;				//SALDO EN PUNTOS  	 **ANTERIOR**
	$retorno[5]= $NumeroCreditosAnt;		//SALDO EN CREDITOS  **ANTERIOR**
	$retorno[6]= $FechaPrevia;				//FECHA DE LA ENTRADA ANTERIOR
	$retorno[7]= $MensajeError;				//MENSAJE
*/

	
return $retorno;
}

function MsgInterno ($Tipo, $To, $ToEmail, $From, $FromEmail, $Subject, $Content, $Leido, $Push , $confirm_code)
{
	$retorno = array();
	$MensajeError = "";

	$Fecha = date ('Y-m-d H:i:s');	
 require("environment_detailForLogin.php");
	
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];// Database name
	$tbl_name="usuarios"; // Table name
					
	// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

	$Contenido = $Content.'</br><input type="button" href="ConfirmaLink.php?User='.$To.'&Doctor='.$From.'&Confirm='.$confirm_code.'" class="btn btn-success" value="Confirm" id="ConfirmaLink" style="margin-top:10px; margin-bottom:10px;"/></br> Or follow this link: <a href="ConfirmaLink.php?User='.$To.'&Doctor='.$From.'&Confirm='.$confirm_code.'">CONFIRM LINK</a></br>';

	$q = $con->prepare("INSERT INTO messages SET Fecha = NOW(), ToType=?, ToId = ?, ToEmail=?, FromId = ?, FromEmail=?, Subject=?, Content=?, Leido=0, Push=? ,Confirm=? ");
	$q->bindValue(1, $Tipo, PDO::PARAM_INT);
	$q->bindValue(2, $To, PDO::PARAM_INT);
	$q->bindValue(3, $ToEmail, PDO::PARAM_STR);
	$q->bindValue(4, $From, PDO::PARAM_INT);
	$q->bindValue(5, $FromEmail, PDO::PARAM_STR);
	$q->bindValue(6, $Subject, PDO::PARAM_STR);
	$q->bindValue(7, $Contenido, PDO::PARAM_STR);
	$q->bindValue(8, $Push, PDO::PARAM_INT);
	$q->bindValue(9, $confirm_code, PDO::PARAM_STR);
	$q->execute();
	
	
	//$q = mysql_query("INSERT INTO messages SET Fecha = NOW(), ToId = '$To'"); 
 	//$q = mysql_query($Isql);
	$IdMsg = $con->lastInsertId(); 
	//echo mysql_errno($link) . ": " . mysql_error($link). "\n";
	
/*
	$retorno[0]=$Cambio;					//PUNTOS SUMADOS AHORA
	$retorno[1]=$Cambio / 50;				//CREDITOS SUMADOS AHORA
	$retorno[2]= $NuevoSaldoPrevio;			//SALDO EN PUNTOS  	 ACTUAL
	$retorno[3]= $NumeroCreditos;			//SALDO EN CREDITOS  ACTUAL
	$retorno[4]= $SaldoPrev;				//SALDO EN PUNTOS  	 **ANTERIOR**
	$retorno[5]= $NumeroCreditosAnt;		//SALDO EN CREDITOS  **ANTERIOR**
	$retorno[6]= $FechaPrevia;				//FECHA DE LA ENTRADA ANTERIOR
	$retorno[7]= $MensajeError;				//MENSAJE
*/

	$retornoB = $confirm_code;					//Ver arriba para referencia
	
return $IdMsg;
}

function SetEnlace ( $To,  $From,  $estado, $confirm_code)
{
	$retorno = array();
	$MensajeError = "";

	$Fecha = date ('Y-m-d H:i:s');	
 require("environment_detailForLogin.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

	$tbl_name="usuarios"; // Table name
					
	// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	


	$q = $con->prepare("INSERT INTO doctorslinkusers SET Fecha = NOW(), IdMED = ?,  IdUs=?, estado = 1, Confirm=? ");
	$q->bindValue(1, $From, PDO::PARAM_INT);
	$q->bindValue(2, $To, PDO::PARAM_INT);
	$q->bindValue(3, $confirm_code, PDO::PARAM_STR);
	$q->execute();
	
	//$q = mysql_query("INSERT INTO messages SET Fecha = NOW(), ToId = '$To'"); 

	//echo mysql_errno($link) . ": " . mysql_error($link). "\n";
	
/*
	$retorno[0]=$Cambio;					//PUNTOS SUMADOS AHORA
	$retorno[1]=$Cambio / 50;				//CREDITOS SUMADOS AHORA
	$retorno[2]= $NuevoSaldoPrevio;			//SALDO EN PUNTOS  	 ACTUAL
	$retorno[3]= $NumeroCreditos;			//SALDO EN CREDITOS  ACTUAL
	$retorno[4]= $SaldoPrev;				//SALDO EN PUNTOS  	 **ANTERIOR**
	$retorno[5]= $NumeroCreditosAnt;		//SALDO EN CREDITOS  **ANTERIOR**
	$retorno[6]= $FechaPrevia;				//FECHA DE LA ENTRADA ANTERIOR
	$retorno[7]= $MensajeError;				//MENSAJE
*/

	
return $retorno;
}

function SetEnlaceMED ( $To,  $From,  $estado, $IdPac, $confirm_code)
{
	$retorno = array();
	$MensajeError = "";

	$Fecha = date ('Y-m-d H:i:s');	
 require("environment_detailForLogin.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
	$tbl_name="usuarios"; // Table name
					
	// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		


	$q = $con->prepare("INSERT INTO doctorslinkdoctors SET Fecha = NOW(), IdMED = ?,  IdMED2=?, IdPac=?, estado = 1, Confirm=? ");
	$q->bindValue(1, $From, PDO::PARAM_INT);
	$q->bindValue(2, $To, PDO::PARAM_INT);
	$q->bindValue(3, $IdPac, PDO::PARAM_INT);
	$q->bindValue(4, $confirm_code, PDO::PARAM_STR);
	$q->execute();
	

return $retorno;
}

function unlockreport ( $To,  $From,  $estado, $confirm_code, $Idpin)
{
	$retorno = array();
	$MensajeError = "";

	$Fecha = date ('Y-m-d H:i:s');	
 require("environment_detailForLogin.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

	$tbl_name="usuarios"; // Table name
					
	// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		


	$q = $con->prepare("INSERT INTO doctorslinkusers SET Fecha = NOW(), IdMED = ?,  IdUs=?,IdPIN=?, estado = 1, Confirm=? ");
	$q->bindValue(1, $From, PDO::PARAM_INT);
	$q->bindValue(2, $To, PDO::PARAM_INT);
	$q->bindValue(3, $Idpin, PDO::PARAM_INT);
	$q->bindValue(4, $confirm_code, PDO::PARAM_STR);
	$q->execute();
	
	//$q = mysql_query("INSERT INTO messages SET Fecha = NOW(), ToId = '$To'"); 

	//echo mysql_errno($link) . ": " . mysql_error($link). "\n";
	

	
return $retorno;
}

function unlockreportMED( $To,  $From,  $estado, $IdPac, $confirm_code, $Idpin)
{
	$retorno = array();
	$MensajeError = "";

	$Fecha = date ('Y-m-d H:i:s');	
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
	$tbl_name="usuarios"; // Table name
					
	// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	


	$q = $con->prepare("INSERT INTO doctorslinkdoctors SET Fecha = NOW(), IdMED2 = ?,  IdMED=?, IdPac=?, estado = 1, Confirm=? ");
	$q->bindValue(1, $From, PDO::PARAM_INT);
	$q->bindValue(2, $To, PDO::PARAM_INT);
	$q->bindValue(3, $IdPac, PDO::PARAM_INT);
	$q->bindValue(4, $confirm_code, PDO::PARAM_STR);
	$q->execute();
	
	
	//$q = mysql_query("INSERT INTO doctorslinkUSERS SET Fecha = NOW(), IdMED = '$From',  IdUs='$IdPac',IdPIN=$Idpin, estado = 1, Confirm='$confirm_code' ");
return $retorno;
}


function unlockreportALL( $To,  $From,  $estado, $IdPac, $confirm_code, $Idpin, $tipo){
	
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
	
	if($IdPac==0){
		$IdPac=$To;
	}
	
	//$sql1="SELECT Idpin, Idmed, Idcreator FROM lifepin where IdUsu ='$IdPac' and IdMed IS NOT NULL and (IdMed NOT IN ((select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$From'))) and IdMed NOT IN (Select idmed from doctorslinkdoctors where idmed2='$From' and IdPac='$IdPac'))";
	
	
	
	$sql_query=$con->prepare("select distinct(idDoctor) from doctorsgroups where idDoctor IN (select Idcreator FROM usuarios where Identif=?) or idGroup IN (select idGroup from doctorsgroups where idDoctor IN (select Idcreator FROM usuarios where Identif=?))");
	$sql_query->bindValue(1, $IdPac, PDO::PARAM_INT);
	$sql_query->bindValue(2, $IdPac, PDO::PARAM_INT);
	
	
	$res=$sql_query->execute();
	
	$privateDoctorID=array();
	$num=0;
	while($rowp=$sql_query->fetch(PDO::FETCH_ASSOC)){
		$privateDoctorID[$num]=$rowp['idDoctor'];
		$num++;
	}

	$sql_que=$con->prepare("select Id from tipopin where Agrup=9");
	$res=$sql_que->execute();
	
	$privatetypes=array();
	$num1=0;
	while($rowpr=$sql_que->fetch(PDO::FETCH_ASSOC)){
		$privatetypes[$num1]=$rowpr['Id'];
		$num1++;
	}
	
	$sql1=$con->prepare("SELECT Idpin,Idmed FROM lifepin where IdUsu =? and Tipo NOT IN (select Id from tipopin where Agrup=9) 
	and IdMed!=0 and IdMed IS NOT NULL and IdMed!=? and IdMed NOT IN (select distinct(idDoctor) 
	from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor=?)) 
	and IdMed NOT IN (select idmed from doctorslinkdoctors where idmed2=? and IdPac=?) 
	and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups 
	where idDoctor IN (Select idmed from doctorslinkdoctors where idmed2=? and IdPac=?)))");
	$sql1->bindValue(1, $IdPac, PDO::PARAM_INT);
	$sql1->bindValue(2, $From, PDO::PARAM_INT);
	$sql1->bindValue(3, $From, PDO::PARAM_INT);
	$sql1->bindValue(4, $From, PDO::PARAM_INT);
	$sql1->bindValue(5, $IdPac, PDO::PARAM_INT);
	$sql1->bindValue(6, $From, PDO::PARAM_INT);
	$sql1->bindValue(7, $IdPac, PDO::PARAM_INT);
	
	
	//$sql1="select Idpin,Idmed from lifepin where IdUsu ='$IdPac' and Idmed!=0 and IdMed IS NOT NULL and (idmed not in (select distinct(idDoctor) from doctorsgroups where idGroup in (select idGroup from doctorsgroups where idDoctor='$From')) and idmed not in (select distinct(idDoctor) from doctorsgroups where idGroup in (select idGroup from doctorsgroups where idDoctor in (select idmed from doctorslinkdoctors where idmed2='$From' and idpac='$IdPac'))))and idmed not in (select idmed from doctorslinkdoctors where idmed2='$From' and idpac='$IdPac'))";
	//$sql1="SELECT Idpin,Tipo FROM lifepin where IdUsu ='$queUsu' and IdMed!=0 and IdMed IS NOT NULL and IdMed!='$MedID' and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor='$MedID')) and IdMed NOT IN (select idmed from doctorslinkdoctors where idmed2='$MedID' and IdPac='$queUsu') and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (Select idmed from doctorslinkdoctors where idmed2='$MedID' and IdPac='$queUsu')))";
	//$q1=mysql_query($sql1);

	$q1=$sql1->execute();
	
	
	$retorno2 = array();
	while($row1=$sql1->fetch(PDO::FETCH_ASSOC)){
		
		$ReportId=$row1['Idpin'];
		$Iddoc=$row1['Idmed'];
		
			$query=$con->prepare("SELECT estado FROM doctorslinkusers where IdMed=? and IdUs=? and Idpin=? ");
			$query->bindValue(1, $From, PDO::PARAM_INT);
			$query->bindValue(2, $IdPac, PDO::PARAM_INT);
			$query->bindValue(3, $ReportId, PDO::PARAM_INT);
			
			
			$q11=$query->execute();
			if($rowes=$query->fetch(PDO::FETCH_ASSOC)){
				$estad=$rowes['estado'];
				if($estad==1 or $estado==2){
					continue;
				}
			}else{
				 $retorno2 = unlockreport($To, $From, $estado, $confirm_code,$ReportId); 
				}
			}
	return $retorno2;
		
	
	
}

function LogBLOCKAMP ($IDPIN, $Content, $VIEWIdUser, $VIEWIdMed, $VIEWIP, $MEDIO)
{
	$retorno = array();
	$MensajeError = "";

	$Fecha = date ('Y-m-d H:i:s');	

 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

					$tbl_name="usuarios"; // Table name
					
					// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	


	$q = $con->prepare("INSERT INTO bpinview  SET  IDPIN =?, Content=?, DateTimeSTAMP = NOW(), VIEWIdUser = ?, VIEWIdMed = ?, VIEWIP = ?, MEDIO = ? ");
	$q->bindValue(1, $IDPIN, PDO::PARAM_INT);
	$q->bindValue(2, $Content, PDO::PARAM_STR);
	$q->bindValue(3, $VIEWIdUser, PDO::PARAM_INT);
	$q->bindValue(4, $VIEWIdMed, PDO::PARAM_INT);
	$q->bindValue(5, $VIEWIP, PDO::PARAM_STR);
	$q->bindValue(6, $MEDIO, PDO::PARAM_INT);
	$q->execute();
	

//echo mysql_errno($con) . ": " . mysql_error($con). "\n";
	

	
return $retorno;
}



?>

