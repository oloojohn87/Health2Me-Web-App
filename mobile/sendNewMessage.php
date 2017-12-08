<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("../environment_detail.php");
require_once('..realtime-notifications/pusherlib/lib/Pusher.php');
 
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="MESSAGES"; // Table name

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

$IdMED = $_POST['IdMED'];
$scenario = 0;
if (isset($_POST['scenario']))
{
    $scenario =  $_POST['scenario'];
}
$subject = '';
if (isset($_POST['SUBJECT']))
{
    $subject =  $_POST['SUBJECT'];
}
$content = '';
if (isset($_POST['MESSAGE']))
{
    $content =  $_POST['MESSAGE'];
}
$IdRECEIVER = -1;
$RECEIVER = '';
$username = '';
if (isset($_POST['RECEIVER']))
{
    $RECEIVER =  $_POST['RECEIVER'];
    $username = str_replace(" ", ".", $RECEIVER);
}
$WhatDB = '';
if ($scenario == 'patient') 
{
	$WhatDB = 'message_infrastructureuser'; 
	$SwitchDr = 0;
} else 
{ 
	$WhatDB = 'message_infrasture'; 
	$SwitchDr = 1;
}

$IdNAME;
$token;
$email;
$q = "SELECT id,IdMEDFIXEDNAME,token,IdMEDEmail FROM doctors WHERE IdMEDFIXEDNAME='".$username."' OR IdMEDEmail='".$RECEIVER."' OR phone='".$RECEIVER."'";
$result = mysql_query($q);
if ($row = mysql_fetch_array($result))
{
    $IdRECEIVER = $row['id'];
    $IdNAME = $row['IdMEDFIXEDNAME'];
    $token = $row['token'];
    $email = $row['IdMEDEmail'];
}

if($IdRECEIVER != -1 && strlen($IdNAME) > 4)
{
    
    if ($scenario == 'patient') 
    {
        $q = "INSERT INTO message_infrastructureuser SET Subject='".$subject."', content='".$content."', tofrom='from', sender_id=".$IdMED.", receiver_id=".$IdMED.", fecha=NOW(), status='new', patient_id=".$IdRECEIVER.", inbox=1, outbox=1, connection_id=0";
    }
    else
    {
        $q = "INSERT INTO message_infrasture SET Subject='".$subject."', content='".$content."', sender_id=".$IdMED.", receiver_id=".$IdRECEIVER.", fecha=NOW(), status='new', inbox=1, outbox=1, connection_id=0, is_mobile=1";
        
    }
    $result = mysql_query($q);
    
    $app_key = 'd869a07d8f17a76448ed';
    $app_secret = '92f67fb5b104260bbc02';
    $app_id = '51379';
    $pusher = new Pusher($app_key, $app_secret, $app_id);
    $pusher->trigger($IdRECEIVER, 'notification', 'You have a new message');
    
    echo $IdRECEIVER;
}
else if($IdRECEIVER != -1)
{
    if ($scenario == 'patient') 
    {
        $q = "INSERT INTO message_infrastructureuser SET Subject='".$subject."', content='".$content."', tofrom='from', sender_id=".$IdMED.", receiver_id=".$IdMED.", fecha=NOW(), status='new', patient_id=".$IdRECEIVER.", inbox=1, outbox=1, connection_id=0";
    }
    else
    {
        $q = "INSERT INTO message_infrasture SET Subject='".$subject."', content='".$content."', sender_id=".$IdMED.", receiver_id=".$IdRECEIVER.", fecha=NOW(), status='new', inbox=1, outbox=1, connection_id=0, is_mobile=1";
        
    }
    $result = mysql_query($q);
    
    sendInvitationEmail($IdRECEIVER, $token, $email);
    
    echo $IdRECEIVER;
    
    
}
else if(strstr($RECEIVER, '@') != false && strstr($RECEIVER, '.') != false)
{
    $confirm_code=md5(uniqid(rand()));
    $q = mysql_query("INSERT INTO DOCTORS SET IdMEDEmail = '".$RECEIVER."',previlege=2,token='$confirm_code'");
    $res = mysql_query("SELECT id from doctors where IdMEDEmail = '".$RECEIVER."'");
    
    $row = mysql_fetch_array($res);
    $id_doc = $row['id'];
    
    $q = mysql_query("INSERT INTO doctorslinkdoctors SET IdMED = '".$IdMED."', IdMED2 = '$id_doc', Fecha = NOW(), estado = 1,  type=1,Confirm ='$confirm_code' ");
    
    sendInvitationEmail($id_doc, $confirm_code, $RECEIVER);
    
    $q = "INSERT INTO message_infrasture SET Subject='".$subject."', content='".$content."', sender_id=".$IdMED.", receiver_id=".$id_doc.", fecha=NOW(), status='new', inbox=1, outbox=1, connection_id=0, is_mobile=1";
    $result = mysql_query($q);
    echo $id_doc;
}
else
{
    echo 0;
}

function sendEmail($IdMEDEmail,$IdMEDFIXED,$IdMEDFIXEDNAME,$confirm_code)
{
	require_once '../MBCaller.php';

	require_once '../lib/swift_required.php';

	$aQuien = $IdMEDEmail;
	$Tema = 'Inmers Account Confirmation';

	$adicional ='<p>Please follow the link to verify your identity and complete the sign up process.</p><p>Your email: <span><h3>'.$IdMEDEmail.'</h3></span><p><p>Your Number id: <span><h3>'.$IdMEDFIXED.'</h3></span><p><p>Your Name id: <span><h3>'.$IdMEDFIXEDNAME.'</h3></span></p><p>Please use your Name id for sign in purposes.</p>';

	$Sobre = $Tema;
	$Body = '<p>Thanks for your interest in our services!</p><p>Please click on the following link to confirm your new Inmers account.</p><p><?php $domain?>/ConfirmaUser.php?token='.$confirm_code.'</p>'.$adicional;


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


} 

function sendInvitationEmail($id, $confirm, $email)
{
    // SEND EMAIL TO REFERRAL:
    require_once '../lib/swift_required.php';
    
      
     $aQuien = $email;
    $Sobre = 'Health2Me Referrals Network.- You have been invited to join Health2Me';
    
        
    $Body = '<p>Please click <a href='.$domain.'/patientdetailMED-REF.php?Nombre=nametest.surnametest&Password=11111111&IdMed='.$id.'&Acceso=23432&Confirm='.$confirm.'">here</a> to create your new account.</p>';
      
    
     $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
      ->setUsername('dev.health2me@gmail.com')
      ->setPassword('health2me');
    
    $mailer = Swift_Mailer::newInstance($transporter);
    
    // Create the message
    $message = Swift_Message::newInstance()
      
      // Give the message a subject
      ->setSubject($Sobre)
    
      // Set the From address with an associative array
    
      ->setFrom(array('hub@inmers.us' => "Health2Me"))
    
      // Set the To addresses with an associative array
      ->setTo(array($aQuien))
    
      ->setBody($Body, 'text/html')
    
      ;
    
    $result = $mailer->send($message);
    // SEND EMAIL TO REFERRAL ******************************
}


?>