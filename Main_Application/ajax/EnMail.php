<?php
require_once 'lib/swift_required.php';

$aQuien = $_GET['aQuien'];
$Tema = $_GET['Tema'];
$Contenido = $_GET['Contenido'];


$Sobre = $Tema;
$Body = $Contenido;

$transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
  ->setUsername('newmedibank@gmail.com')
  ->setPassword('ardiLLA98');

$mailer = Swift_Mailer::newInstance($transporter);


// Create the message
$message = Swift_Message::newInstance()

  // Give the message a subject
  ->setSubject($Sobre)

  // Set the From address with an associative array
  ->setFrom(array('newMediBANK@gmail.com' => 'newMediBANK'))

  // Set the To addresses with an associative array
  ->setTo(array($aQuien))

  // Give it a body
 // ->setBody('Here is the message itself')
  ->setBody('-')

  // And optionally an alternative body
//  ->addPart('<q>Here is the message itself</q>', 'text/html')
  ->addPart($Body, 'text/html')

  // Optionally add any attachments
 // ->attach(Swift_Attachment::fromPath('my-document.pdf'))
  ;


$result = $mailer->send($message);

?>
