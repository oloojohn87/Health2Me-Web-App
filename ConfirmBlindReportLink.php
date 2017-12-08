<?php
define('INCLUDE_CHECK',1);
require "logger.php";
 
/*$To = $_GET['To'];
$ToEmail = $_GET['ToEmail'];
$From = $_GET['From'];
$FromEmail = $_GET['FromEmail'];
$Subject = $_GET['Subject'];
$Content = $_GET['Content'];
$Leido = $_GET['Leido'];
$Push = $_GET['Push'];
$estado = $_GET['estado'];
*/

$CodigoBusca = $_GET['Confirm'];

$IdUsu = $_GET['User'];
$IdMed = $_GET['Doctor'];
$tipo=$_GET['tipo']; 
 
$Fecha = date ('Y-m-d H:i:s');	

require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
$tbl_name="messages"; // Table name
					
// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		





$q = $con->prepare("UPDATE messages SET Fecha = NOW(), Leido=1 ,Confirm='********' WHERE Confirm=? ");
$q->bindValue(1, $CodigoBusca, PDO::PARAM_STR);
$q->execute();


if($tipo==0){
$q2 = $con->prepare("UPDATE doctorslinkusers SET Fecha = NOW(),  estado = 2, Confirm='Blind Report Access Confirmed' WHERE Confirm=? ");
$q2->bindValue(1, $CodigoBusca, PDO::PARAM_STR);
$q2->execute();

}else{
 $q2 = $con->prepare("UPDATE DOCTORSlinkdoctors SET Fecha = NOW(),  estado = 2, Confirm='Blind Report Access Confirmed' WHERE Confirm=? ");
 $q2->bindValue(1, $CodigoBusca, PDO::PARAM_STR);
 $q2->execute();
 
}
/*echo 'CODIGO = ';
echo $CodigoBusca;
echo "CONFIRMATION";*/

###Send notification to the doctor who had requested for unlocking the report####

	require_once 'lib/swift_required.php';
	
	
	$ToEmail=$_GET['email'];
	$Sobre="Report Unlock Notification";
	
	$aQuien = $ToEmail;

	$msg= '<p>Dear Doctor,<p><p> Your request to unlock report(s) of patientID '.$IdUsu.' has been confirmed. 
	Please login into '.$domain.' to see the report details!</p><p>Thank You,</p>';
	
	$message2= '***** HIPAA AND STANDARD COMPLIANCE PROCEDURES DISCLAIMER *****  :   ';
	$message2.= ' Text of the Legal, Disclaimer and technical explanations here ... Lore Ipsum';
	
	
    $Body='<h3>';
    $Body.= $msg;
    $Body.='</h3>';
   
    $Body.='<h3>';
    $Body.= $message2;
    $Body.='</h3>';
$transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
  ->setUsername('newmedibank@gmail.com')
  ->setPassword('ardiLLA98'); 
	  
	/* $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
	  ->setUsername('dev.health2me@gmail.com')
	  ->setPassword('health2me');*/
	 
	
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
/*
if($tipo==1){

	//$salto='location:medicalConnections.php?Acceso=23432&MEDID='.$IdMed.'&Nombre=&Password=&IdUsu='.$IdUsu;
}else {
	//$salto='location:patientConnections.php?Acceso=23432&USERID='.$IdUsu.'&Nombre=&Password=&IdUsu='.$IdUsu;
}*/

?>
<html>
<head></head>
<body>
<script type="text/javascript" >
alert("Thank you for the confirmation.Best Wishes!");
window.location='<?php echo $domain?>';
</script>
</body>
</html>