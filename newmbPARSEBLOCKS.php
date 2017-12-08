<?php
define('INCLUDE_CHECK',1);
require "logger.php";

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

echo "Ver 1.1";
echo "<br>\n"; 	
// Grabación de un registro con fecha en la dB para comprobar la VITALIDAD
$IsqlX=$con->prepare("UPDATE vitalidad SET Fecha=NOW(), Programa = 'newmbPARSEBLOCKS.php' WHERE IdProg = 2");

$qX = $IsqlX->execute();
// Grabación de un registro con fecha en la dB para comprobar la VITALIDAD

// EnviaMail2('lapspart@me.com', 'Email de prueba EnviaMail2', 0, 9999, 'test@test.com', 'Cuenta de Test',8888, 9);  		 // PLANTILLA DE FUNCIÓN PARA ENVIAR E-MAIL CON SWIFTMAIL.
// LogBLOCK (1111, 'Contenido del LOG aqui' ,  'jvinals@gmail.com', 19660706, 19660808, 'javier&vinals', 999999, 1);    	 // PLANTILLA DE FUNCIÓN DE REGISTRO DEL LOG.


//BLOCKSLIFEPIN $q = mysql_query("SELECT * FROM blocks WHERE NeedACTION=1 ORDER BY id DESC ");
$q = $con->prepare("SELECT * FROM lifepin WHERE NeedACTION=1 and CANAL!=7 ORDER BY IdPin DESC ");
$q->execute();
$count=$q->rowCount();
echo "<br>\n"; 	
echo "N = ";
echo $count;
echo "<br>\n"; 	
echo "<br>\n"; 	


while($row=$q->fetch(PDO::FETCH_ASSOC))     										// Va pasando por todos los BLOCKS que están marcados con NeedACTION = 1 (Necesitan que se haga algo)
{

$ID = $row['IdPin'];
$EmailDOC = $row['IdMEDEmail'];
$IdUsFIXED = $row['IdUsFIXED']; 
$FechaEmail = $row['FechaEmail']; 

echo "Id = ";
echo $ID;
echo "<br>\n"; 	
echo "Email = ";
echo $EmailDOC;
echo "<br>\n"; 	
echo "<br>\n"; 	


// Se comprueba: 
//   1: Que esté bien el ORIGEN
//   2: Que esté bien el DESTINATARIO (PACIENTE/USER)
//   3: Que tenga un fichero correcto (PDF,JPG, VIDEO) DESCARGADO
//   4: Que esté ETIQUETADO y CLASIFICADO

// COMPROBACIÓN (1)  ORIGEN **********************************************************************************************************************
$ComprobOrigen=-1;
$Origen = $row['IdMEDEmail'];
$q2 = $con->prepare("SELECT * FROM doctors WHERE IdMEDEmail = ? ");
$q2->bindValue(1, $Origen, PDO::PARAM_STR);
$q2->execute();

$count2 = $q2->rowCount();

$row2=$q2->fetch(PDO::FETCH_ASSOC);
$NameDoctor = $row2['Name'];
$SurnameDoctor = $row2['Surname'];
$IdMed = $row2['id'];

//-------------------------------------------------------------------------------------------------------------------------------------------
//Logger Information added by Ankit
$IdPin=$ID;
$content = "Report Uploaded";
$VIEWIdUser=0;   //left 0 because at this point we do not know about the user
$VIEWIdMed=$IdMed;
$ip="via email";
$MEDIO=0;
LogBLOCKAMP ($IdPin, $content, $VIEWIdUser, $VIEWIdMed, $ip, $MEDIO);	
//-------------------------------------------------------------------------------------------------------------------------


if ($count2 > 1){
	$ComprobOrigen=-1;
	die ('MORE THAN 1 ACCOUNT FOR THIS EMAIL ADDRESS REGISTERED .... STOPPING HERE. PLEASE CHECK AND AUDIT TRACK.');
}

if ($count2 == 0){   																				// ORIGEN 1:A // EL MÉDICO NO ESTÁ EN LA BASE DE DATOS: Procedimiento de envío de link para que se de de alta.
$ComprobOrigen=0;
$message = 'Dear ';
$message.= $EmailDOC.' : ';
$message.= ' We have received a clinical information package at MediBANK from you regarding patient ';
$message.= '<span style="color: green;">';
$message.= $IdUsFIXED;
$message.= '<span style="color: black;">';
$message.= ' at ';
$message.= '<span style="color: green;">';
$message.= $FechaEmail;
$message.= '<span style="color: black;">';
$message.= '. In order to activate this information for you and for your patient, you need to sign up at MediBANK. Please follow this link to create your account: ';
$confirm_code=md5(uniqid(rand()));
$message.=$domain.'/confirmationDoc.php?passkey=$confirm_code';

EnviaMail2($EmailDOC, $IdUsFIXED, 'MediBANK clinical validation system','Please sign up in MediBANK to access and validate your clinical record information.', $message, 0, 9999, 'Grace@health2.me', 'Inmers',$ID, 9);  		 	// ENVIA E-MAIL PARA QUE EL MEDICOI SE DE DE ALTA.
LogBLOCK ($ID, 'Doctor not active in database. Sent email with instructions to sign up', $EmailDOC, '', $IdUsFIXED, '', '', 1);           	// Registra en BTRACKER todo esto
//BLOCKSLIFEPIN $q3 = mysql_query("UPDATE blocks SET NeedACTION=0, ValidationStatus=2, NextAction='Doctor not active in database. Sent email with instructions to sign up.'  WHERE id='$ID'");   // PONE OTRA VEZ EL FLAG NeedACTION a CERO
$q3 = $con->prepare("UPDATE lifepin SET NeedACTION=0, ValidationStatus=2, NextAction='Doctor not active in database. Sent email with instructions to sign up.'  WHERE IdPin=?");   // PONE OTRA VEZ EL FLAG NeedACTION a CERO
$q3->bindValue(1, $ID, PDO::PARAM_INT);
$q3->execute();


echo '---:'.'Doctor not active in database. Sent email with instructions to sign up.';
echo "<br>\n"; 			
}
else
{																									// ORIGEN 1:B // EL MÉDICO ESTÁ EN LA BASE DE DATOS: Solo se añade el registro en BTRACKER
$ComprobOrigen=1;
LogBLOCK ($ID, 'Doctor acknowledged in MB Database.', $EmailDOC, '', $IdUsFIXED, '', '', 1);           // Registra en BTRACKER todo esto
//BLOCKSLIFEPIN $q3 = mysql_query("UPDATE blocks SET NeedACTION=0, ValidationStatus=0, NextAction='Doctor acknowledged in MB Database.' WHERE id='$ID'");   // PONE OTRA VEZ EL FLAG NeedACTION a CERO
$q3 = $con->prepare("UPDATE lifepin SET NeedACTION=0, IdMed=?, CreatorType=1, IdCreator=?, ValidationStatus=0, NextAction='Doctor acknowledged in MB Database.' WHERE IdPin=?");   // PONE OTRA VEZ EL FLAG NeedACTION a CERO
$q3->bindValue(1, $IdMed, PDO::PARAM_INT);
$q3->bindValue(2, $IdMed, PDO::PARAM_INT);
$q3->bindValue(3, $ID, PDO::PARAM_INT);
$q3->execute();

echo '---:'.'Doctor acknowledged in MB Database.';
echo "<br>\n"; 	
}

if ($ComprobOrigen<1) goto salida;    // -------------------------------> Importante: SI HAY DEFECTOS EN (1) SALTA DIRECTAMENTE AL FINAL DEL BUCLE

// COMPROBACIÓN (2)  DESTINATARIO (PACIENTE/USUARIO)   *******************************************************************************************
$ComprobDestino=-1;
$Destino = $row['IdUsFIXED'];
$DestinoNAME = $row['IdUsFIXEDNAME'];
$q2 = $con->prepare("SELECT * FROM usuarios WHERE IdUsFIXED = ? AND IdUsFIXEDNAME=? ");    
$q2->bindValue(1, $Destino, PDO::PARAM_INT);
$q2->bindValue(2, $DestinoNAME, PDO::PARAM_STR);
$q2->execute();

$count2 = $q2->rowCount();
$row2=$q2->fetch(PDO::FETCH_ASSOC);

$NameUsu = $row2['Name'];
$SurnameUsu = $row2['Surname'];


echo "<br>\n"; 	
$cadSQL =$con->prepare("SELECT * FROM usuarios WHERE IdUsFIXED = ? AND IdUsFIXEDNAME=? ");
$cadSQL->bindValue(1, $Destino, PDO::PARAM_INT);
$cadSQL->bindValue(2, $DestinoNAME, PDO::PARAM_STR);
$cadSQL->execute();
$cadSQL2 = "SELECT * FROM usuarios WHERE IdUsFIXED = ? AND IdUsFIXEDNAME=? ";

echo $cadSQL2;
echo "<br>\n"; 	

if ($count2 > 1){
	$ComprobDestino=-1;
	die ('MORE THAN 1 ACCOUNT FOR THIS USER .... TRIGGER CHECK.');
}

if ($count2==0){
$q3 = $con->prepare("SELECT * FROM usuarios WHERE IdUsFIXED = '$Destino'");  
$q3->bindValue(1, $Destino, PDO::PARAM_INT);
$q3->execute();
$count3 = $q3->rowCount();
if ($count3==0){
	$ComprobDestino = 0; 										// El Usuario NO ESTÁ EN LA BASE DE DATOS, NI SIQUIERA CON EL "FIXED"	
	LogBLOCK ($ID, 'Patient not present in MB Database. No previous records or wrong id.', $EmailDOC, '', $Destino, $DestinoNAME, '', 1);           // Registra en BTRACKER todo esto
//BLOCKSLIFEPIN 	$q3 = mysql_query("UPDATE blocks SET NeedACTION=0, ValidationStatus=2, NextAction='Patient not present in MB Database. No previous records or wrong id.'  WHERE id='$ID'");   // PONE OTRA VEZ EL FLAG NeedACTION a CERO
	$q3 = $con->prepare("UPDATE lifepin SET NeedACTION=0, ValidationStatus=2, NextAction='Patient not present in MB Database. No previous records or wrong id.'  WHERE IdPin=?");   // PONE OTRA VEZ EL FLAG NeedACTION a CERO
	$q3->bindValue(1, $ID, PDO::PARAM_INT);
	$q3->execute();
	
	echo '---:'.'Patient not present in MB Database. No previous records or wrong id.';
	echo "<br>\n"; 
}else
{
	$ComprobDestino = 1; 										// El Usuario ESTÁ EN LA BASE DE DATOS, PERO NO SE HA VALIDADO NUNCA (O LA CLAVE FIXEDNAME ESTÁ MAL)
	LogBLOCK ($ID, 'Patient FOUND in MB Database but no validation yet.', $EmailDOC, '', $Destino, $DestinoNAME, '', 1);           									// Registra en BTRACKER todo esto
//BLOCKSLIFEPIN	$q3 = mysql_query("UPDATE blocks SET NeedACTION=0, ValidationStatus=2, NextAction='Patient FOUND in MB Database but no validation yet.'  WHERE id='$ID'");   	// PONE OTRA VEZ EL FLAG NeedACTION a CERO
	$q3 = $con->prepare("UPDATE lifepin SET NeedACTION=0, ValidationStatus=3, NextAction='Patient FOUND in MB Database but no validation yet.'  WHERE IdPin=?");   	// PONE OTRA VEZ EL FLAG NeedACTION a CERO
	$q3->bindValue(1, $ID, PDO::PARAM_INT);
	$q3->execute();
	
	echo '---:'.'Patient FOUND in MB Database but no validation yet.';
	echo "<br>\n"; 
																// 		Situación A: No está el paciente creado
																// 		Situación B: La clave está mal o incompleta
		
}	
}
else
{
	$ComprobDestino = 2; 										// El Usuario ESTÁ EN LA BASE DE DATOS.- Enviar email de confirmación al paciente.
	$queIdUsu = $row2['Identif'];
	
	$PatientName = $row2['Name'];
	$PatientSurname = $row2['Surname'];
	$PatientEmail = $row2['email'];
	$PatientIdUsFIXED = $row2['IdUsFIXED'];
	$PatientIdUsFIXEDNAME = $row2['IdUsFIXEDNAME'];
	
	LogBLOCK ($ID, 'Patient Cleared: FIXED and FIXEDNAME found in MB Database.', $EmailDOC, '', $Destino, $DestinoNAME, '', 1);           									// Registra en BTRACKER todo esto
	//BLOCKSLIFEPIN $q3 = mysql_query("UPDATE blocks SET NeedACTION=0, ValidationStatus=0, NextAction='Patient Cleared: FIXED and FIXEDNAME found in MB Database.'  WHERE id='$ID'");   	// PONE OTRA VEZ EL FLAG NeedACTION a CERO
	$cadenaSql = $con->prepare("UPDATE lifepin SET NeedACTION=0, IdUsu = ?, ValidationStatus=4, NextAction='Patient Cleared: FIXED and FIXEDNAME found in MB Database.'  WHERE IdPin=?");
	$cadenaSql->bindValue(1, $queIdUsu, PDO::PARAM_INT);
	$cadenaSql->bindValue(2, $ID, PDO::PARAM_INT);
	
	
	$q3 = $cadenaSql->execute();  	// PONE OTRA VEZ EL FLAG NeedACTION a CERO
	$cadenaSql2 = "UPDATE lifepin SET NeedACTION=0, IdUsu = ?, ValidationStatus=4, NextAction='Patient Cleared: FIXED and FIXEDNAME found in MB Database.'  WHERE IdPin=?";
	echo "<br>\n"; 
	echo "ACTUALIZACION DE INFORMACION (PATIENT CLEARED): ";
	echo "<br>\n"; 
	echo $cadenaSql2;
	echo "<br>\n"; 
	echo '---:'.'Patient Cleared: FIXED and FIXEDNAME found in MB Database.';
	echo "<br>\n"; 
	echo "REGISTROS CAMBIADOS: ".$cadenaSql->rowCount();
	echo "<br>\n"; 	

$message = 'Dear Dr. ';
$message.= $NameDoctor.' '.$SurnameDoctor.' ';
$message.= '( '.$EmailDOC.' ) : ';
$message.= ' We have received correctly your clinical information package regarding patient ';
$message.= '<span style="color: green;">';
$message.= $NameUsu.' '.$SurnameUsu;
$message.= '<span style="color: black;">';
$message.= ' ( ';
$message.= '<span style="color: green;">';
$message.= $IdUsFIXED;
$message.= '<span style="color: black;">';
$message.= ' ) at ';
$message.= '<span style="color: green;">';
$message.= $FechaEmail;
$message.= '<span style="color: black;">';
$message.= '. The information has been updated and is available at eMapLife. ';

EnviaMail2($EmailDOC, $IdUsFIXED,'eMapLife clinical validation system','Patient information added.', $message, 0, 9999, 'Grace@health2.me', 'Inmers',$ID, 9);  		 	// ENVIA E-MAIL PARA  EL MEDICO


$message = 'Dear ';
//$message.= $NameDoctor.' '.$SurnameDoctor.' ';
$message.= $PatientName.': ';
//$message.= '( '.$PatientIdUsFIXED.' ['.$PatientIdUsFIXEDNAME.']'.' ) : ';
$message.= ' Dr. '.$NameDoctor.' '.$SurnameDoctor.' added a new report to your Medical Record.';
$message.= '(Ids.: '.$PatientIdUsFIXED.' ['.$PatientIdUsFIXEDNAME.']'.' ) : ';
$message.= ' Date: ';
$message.= '<span style="color: green;">';
$message.= $FechaEmail;
$message.= '<span style="color: black;">';
$message.= '. The information has been updated and is available at eMapLife. ';

EnviaMail2($PatientEmail, $IdUsFIXED,'eMapLife clinical validation system','Patient information added.', $message, 0, 9999, 'Grace@health2.me', 'Inmers',$ID, 9);  		 	// ENVIA E-MAIL PARA EL PACIENTE


}

salida:    // Se utilizan saltos directos desde las comprobaciones arriba para evitar duplicidades



}  // CIERRE DEL BUCLE DE RECORRIDO DE TODOS LOS REGISTROS QUE TIENEN FLAG NeedACTION = 1




function EnviaMail2($objetivo, $IdUsFIXED, $sobre, $message0, $message, $codExito, $IdU, $fro, $froa ,$quePIN, $queCodigo)
{
require_once 'lib/swift_required.php';

/*
echo "<br>\n"; 	
echo "Objetivo = ";
echo $objetivo;
echo "<br>\n"; 	
echo "sobre = ";
echo $sobre;
echo "<br>\n"; 	
echo "message = ";
echo $message;
echo "<br>\n"; 	
echo "codExito = ";
echo $codExito;
echo "<br>\n"; 	
echo "IdU = ";
echo $IdU;
echo "<br>\n"; 	
echo "fro = ";
echo $fro;
echo "<br>\n"; 	
echo "froa = ";
echo $froa;
echo "<br>\n"; 	
echo "quePIN = ";
echo $quePIN;
echo "<br>\n"; 	
echo "queCodigo = ";
echo $queCodigo;
echo "<br>\n"; 	
*/

if ($codExito==0)
{
//LogMov($IdUsu, $fromname, $fromaddress, '4', 'dB INSERTION', 'PIN INSERTED, Database UPDATED' , $subject, $email_number);
	$Sobre = $sobre;
	$Body = '<h1>'.$message0.'</h1>';
	$Body .= '<h2>Patient.: '.$IdUsFIXED.'</h2>';
	
	$confirm_code = $queCodigo;
	
	$message2= '***** HIPAA AND STANDARD COMPLIANCE PROCEDURES DISCLAIMER *****  :   ';
	$message2.= ' Text of the Legal, Disclaimer and technical explanations here ... Lore Ipsum';
	
	
    $Body.='<h3>';
    $Body.= $message;
    $Body.='</h3>';
   
    $Body.='<h3>';
    $Body.= $message2;
    $Body.='</h3>';

}
else
{
}


$transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
  ->setUsername('HUB@health2.me')
  ->setPassword('mnibjb007');

$mailer = Swift_Mailer::newInstance($transporter);


// Create the message
$message = Swift_Message::newInstance()

  // Give the message a subject
  ->setSubject($Sobre)

  // Set the From address with an associative array
  ->setFrom(array('HUB@health2.me' => 'eMapLife Clinical HUB'))

  // Set the To addresses with an associative array
  ->setTo(array($objetivo))

  // Give it a body
 // ->setBody('Here is the message itself')
  ->setBody('Here is the message itself')

  // And optionally an alternative body
//  ->addPart('<q>Here is the message itself</q>', 'text/html')
  ->addPart($Body, 'text/html')

  // Optionally add any attachments
 // ->attach(Swift_Attachment::fromPath('my-document.pdf'))
  ;


$result = $mailer->send($message);


}  

function LogBLOCK ($IDBLOCK, $Content, $IdMEDEmail, $IdMEDRESERV, $IdUsFIXED, $IdUsFIXEDNAME, $IdUsRESERV , $Canal)
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


	$q = $con->prepare("INSERT INTO btracker SET  IDBLOCK =?, Content=?, DateTimeSTAMP = NOW(), TimeSTAMP = NOW(),  IdMEDEmail =?, IdMEDRESERV =?, IdUsFIXED = ?, IdUsFIXEDNAME = ?, IdUsRESERV =? , CANAL = ? ");
	$q->bindValue(1, $IDBLOCK, PDO::PARAM_INT);
	$q->bindValue(2, $Content, PDO::PARAM_STR);
	$q->bindValue(3, $IdMEDEmail, PDO::PARAM_STR);
	$q->bindValue(4, $IdMEDRESERV, PDO::PARAM_INT);
	$q->bindValue(5, $IdUsFIXED, PDO::PARAM_INT);
	$q->bindValue(6, $IdUsFIXEDNAME, PDO::PARAM_STR);
	$q->bindValue(7, $IdUsRESERV, PDO::PARAM_INT);
	$q->bindValue(8, $Canal, PDO::PARAM_INT);
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



?>