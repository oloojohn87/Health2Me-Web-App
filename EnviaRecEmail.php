<?php

$IdMEDFIXED = $_GET['VIdUsFIXED'];

// LLAMADA PARA VERIFICAR EL TELEFONO DEL PACIENTE: ****************************************************

require_once 'MBCaller.php';
//$a = SendCallVERIF (34608754342);

// ENVÍA EL EMAIL AL USUARIO: ****************************************************

require_once 'lib/swift_required.php';

$IdMEDEmail = $_GET['email'];
$IdMEDFIXED = $_GET['IdUsFIXED'];
$IdMEDFIXEDNAME = $_GET['IdUsFIXEDNAME'];
$IdMEDRESERV= $_GET['IdUsRESERV'];

$aQuien = $IdMEDEmail;


$Tema = 'Inmers Password Retrieval';

$adicional ='<p>Your email: <span><h3>'.$IdMEDEmail.'</h3></span><p><p>Your Number id: <span><h3>'.$IdMEDFIXED.'</h3></span><p>Your Name id: <span><h3>'.$IdMEDFIXEDNAME.'</h3></span></p><p>Your Password: <span><h3>'.$IdMEDRESERV.'</h3></span></p><p>Please use your Name id for sign in purposes. Thank you.</p>';

$Sobre = $Tema;
$Body = '<p>We have received a request for password retrieval from this email address</p><p>This is the information in our database for this email account.</p>'.$adicional;


$transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
  ->setUsername('newmedibank@gmail.com')
  ->setPassword('ardiLLA98');

$mailer = Swift_Mailer::newInstance($transporter);


// Create the message
$message = Swift_Message::newInstance()
  
  // Give the message a subject
  ->setSubject($Sobre)

  // Set the From address with an associative array
  ->setFrom(array('no-reply@health2.me' => 'health2.me'))

  // Set the To addresses with an associative array
  ->setTo(array($aQuien))

  ->setBody($Body, 'text/html')

  ;


$result = $mailer->send($message);

$salto="location:SignIn.php";
header($salto);

?>