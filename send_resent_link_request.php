<?php

require("environment_detail.php");
$doctorName = $_POST['doctor_email'];
$email = $_POST['patient_email'];

echo $doctorName.' '.$email;

require_once 'lib/swift_required.php';
$Content = file_get_contents($domain.'/templates/RequestPatientToViewRecords-Send.html');



$Content = str_replace("**Var3**",$doctorName,$Content);
$Content = str_replace("**Var11**",$doctorName,$Content);

$body = $Content;

$subject = 'Resend Send Request: '.$doctorName;

$transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
->setUsername('dev.health2me@gmail.com')
->setPassword('health2me');

$mailer = Swift_Mailer::newInstance($transporter);


$message = Swift_Message::newInstance()
->setSubject($subject)
->setFrom(array('hub@inmers.us' => 'Health2Me Clinical Support Center'))
->setTo(array($email))
->setBody($body, 'text/html')
;

$result = $mailer->send($message);


?>