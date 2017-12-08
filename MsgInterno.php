<?php
define('INCLUDE_CHECK',1);
require "logger.php";
require("environment_detail.php");


$Tipo = $_GET['Tipo'];
$IdPac = $_GET['IdPac'];
$To = $_GET['To'];
$ToEmail = $_GET['ToEmail'];
$From = $_GET['From'];
$FromEmail = $_GET['FromEmail'];
$Subject = $_GET['Subject'];
$Content = $_GET['Content'];
$Leido = $_GET['Leido'];
$Push = $_GET['Push'];
$estado = $_GET['estado'];
$callphone=$_GET['callphone'];
$Name=$_GET['NameDoctor'];
$Surname=$_GET['SurnameDoctor'];
$NameDocOrigin=$_GET['NameDoctorOrigin'];
$SurnameDocOrigin=$_GET['SurnameDoctorOrigin'];
$NamePac=$_GET['NamePatient'];
$SurnamePac=$_GET['SurnamePatient'];
$IdRef=1;
$Idpin=0;

//Changes added for the blind reports.

if($_GET['Idpin']!=null){
	$Idpin=$_GET['Idpin'];
}
 
$confirm_code=md5(uniqid(rand()));


															// Graba el Mensaje en la TABLA DE MENSAJES INTERNA:

//echo $retorno = MsgInterno ($Tipo, $To, $ToEmail, $From, $FromEmail, $Subject, $Content, $Leido, $Push , $confirm_code);



 //echo "Idpin:".$Idpin;
if($Idpin==-111){
	//echo "reached here first11: ";
	
		// echo "reached here first: ";
 if ($Tipo==1) {$retorno2 = unlockreportMED  ($To, $From, $estado, $IdPac, $confirm_code,$Idpin);}
 else{	$retorno2 = unlockreportAll  ($To, $From, $estado, $IdPac, $confirm_code,$Idpin, $Tipo); } 

}else
{
	if($Idpin!=0){
		echo "reached here: ";
		if ($Tipo==1) {$retorno2 = unlockreportMED  ($To, $From, $estado, $IdPac, $confirm_code,$Idpin);} 
		else { $retorno2 = unlockreport($To, $From, $estado, $confirm_code,$Idpin); }
	}else
	{
	//echo "reached here second11: ";
	if ($Tipo==1) {$retorno2 = SetEnlaceMED  ($To, $From, $estado, $IdPac, $confirm_code);} 
	else { $retorno2 = SetEnlace  ($To, $From, $estado, $confirm_code); }
	}
		
	
}




															// LLAMA AL PACIENTE:
require_once 'MBCaller.php';
//if ($Tipo==1) {$a = SendCallVERIFMED (19728242428);} else{$a = SendCallVERIF (19728242428);}

//if ($Tipo==1) {$a = SendCallVERIFMED (34608754342);} else{$a = SendCallVERIF (34608754342);}
//if ($Tipo==1) {$a = SendCallVERIFMED (14692680295);} else{$a = SendCallVERIF (14692680295);}

//if ($Tipo==1) {$a = SendCallVERIFMED (12144031933);} else{$a = SendCallVERIF (12144031933);}

//$a = SendCallVERIF (12144031933);

//echo ' ************ '.$a;
															// ENVÍA EL EMAIL AL PACIENTE:

require_once 'lib/swift_required.php';

$aQuien = $ToEmail;
$Tema = $Subject;

//Changes for supporting blind reports
if($Idpin!=0){
	if ($Tipo==1) {$ContenidoAdic = '  Click this link to confirm: <p>'.$domain.'/ConfirmBlindReportLink.php?User='.$To.'&Doctor='.$From.'&email='.$FromEmail.'&tipo='.$Tipo.'&Confirm='.$confirm_code .'</p>';}else
	{
		$ContenidoAdic = '  Click this link to confirm: <p>'.$domain.'/ConfirmBlindReportLink.php?User='.$To.'&Doctor='.$From.'&email='.$FromEmail.'&tipo=0&Confirm='.$confirm_code.'</p>';
	}
}
else{
	
	if ($Tipo==1) {$ContenidoAdic = ' Click this link to confirm:'.$domain.'/ConfirmaLinkMED.php?User='.$To.'&Doctor='.$From.'&Confirm='.$confirm_code;}else
	{
		//$Tipo=0;
		$ContenidoAdic = ' Click this link to confirm:'.$domain.'/ConfirmaLink.php?User='.$To.'&Doctor='.$From.'Confirm='.$confirm_code;
	}
}
  


$Sobre = $Tema;
$Body = $Content.$ContenidoAdic;

  
 $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
  ->setUsername('dev.health2me@gmail.com')
  ->setPassword('health2me');
 

$mailer = Swift_Mailer::newInstance($transporter);


// Create the message
$message = Swift_Message::newInstance()
  
  // Give the message a subject
  ->setSubject($Sobre)

  // Set the From address with an associative array
  ->setFrom(array('hub@inmers.us' => 'Health2Me Health Social Network'))

  // Set the To addresses with an associative array
  ->setTo(array($aQuien))

  ->setBody($Body, 'text/html')

  ;


$result = $mailer->send($message);
   
 if($Idpin!=0){
	
if($callphone!=null) 
{

// CALL REFERRAL
require "Services/Twilio.php";

$AccountSid = "ACc8d7e18334aea996eee97355a50fe672";
$AuthToken = "484f8b70a66fe46fdb49f44900f891ec";
$fromnum= '+19034018888';
 
$url = $domain;
// Instantiate a new Twilio Rest Client 
$client = new Services_Twilio($AccountSid, $AuthToken);
//$CallString = $url . '/callback.php?IdRef=1&NameDoctor='.$NameDoctor.'&NameDoctorRequest='.$NameDocOrigin.'&NamePatient='.$NamePatient.'&number=' . $to;
//$CallString = $url . '/callback.php?IdRef='.$IdRef.'&NameDoctor=Dr Javier Vinals&NameDoctorRequest=Dr dos dos&NamePatient=Nombre Paciente&number=23';



$NameDoctor = str_replace(' ', '', $Name);
$SurnameDoctor = str_replace(' ', '', $Surname);
$NameDoctorOrigin = str_replace(' ', '', $NameDocOrigin);
$SurnameDoctorOrigin = str_replace(' ', '',$SurnameDocOrigin);
$NamePatient = str_replace(' ', '', $NamePac );
$SurnamePatient = str_replace(' ', '', $SurnamePac);

$CallString =  $url . '/callbackblindreports.php?IdRef='.$IdRef.'&NameDoctor='.$NameDoctor.'&SurnameDoctor='.$SurnameDoctor.'&NameDoctorOrigin='.$NameDoctorOrigin.'&SurnameDoctorOrigin='.$SurnameDoctorOrigin.'&NamePatient='.$NamePatient.'&SurnamePatient='.$SurnamePatient.'&number=' . $callphone;  // ALERT:  THIS STRING DOES NOT ALLOW BLANK SPACES WITHIN IT ***************
$call = $client->account->calls->create($fromnum, $callphone, $CallString);

// redirect back to the main page with CallSid 
$msg = urlencode("Connecting... ".$call->sid);
//header("Location: index.php?msg=$msg");
//return 'OK';
echo $msg;
echo '\n';
// CALL REFERRAL  *********************************
} }

echo "User request sent. You will be notified at ".$FromEmail.",once the user has confirmed";
 

?>