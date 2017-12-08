<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//Test comment
require("environment_detailForLogin.php");
require("PasswordHash.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 
 

$tbl_name="doctors"; // Table name


// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		
/*
$IdMEDFIXED = $_GET['VIdUsFIXED'];
$IdMEDFIXEDNAME = $_GET['VIdUsFIXEDNAME'];
$Gender = $_GET['Gender'];
$OrderOB = $_GET['OrderOB'];
$Name = $_GET['Vname'];
$Surname = $_GET['Vsurname'];
//$IdMEDRESERV = $_GET['Password'];
$IdMEDEmail = $_GET['email'];
$phone = $_GET['phone'];*/


$IdMEDFIXED = $_POST['VIdUsFIXED'];
$IdMEDFIXEDNAME = $_POST['VIdUsFIXEDNAME'];
$Gender = $_POST['Gender'];
$OrderOB = $_POST['OrderOB'];
$Name = $_POST['Vname'];
$Surname = $_POST['Vsurname'];
//$IdMEDRESERV = $_POST['Password'];
$IdMEDEmail = $_POST['email'];
$access_code = $_POST['code'];
$phone = str_replace('+','',$_POST['phone']);

if($access_code=='HTICOL01' || $access_code=='HTIUSA01' || $access_code=='HTIMEX01' || $access_code=='HTICR01' || $access_code=='HTIECU01' || $access_code=='HTIPR01'){
$previlege = 3;
}else{
$previlege = 1;
}

$queModo = 1;

$confirm_code=md5(uniqid(rand()));

//Changes for adding encryption
$hashresult = explode(":", create_hash($_POST['Password']));
$IdMEDRESERV= $hashresult[3];
$additional_string=$hashresult[2];

//$IdMEDRESERV=create_hash($_POST['Password']);
/*echo $hashresult;
echo "<br>";
echo $IdMEDRESERV;
echo "<br>";
echo $additional_string;*/

if ($queModo == '1')
{
	$q = $con->prepare("INSERT INTO doctors SET IdMEDFIXED = ?, IdMEDFIXEDNAME = ?,  Gender = ?, OrderOB = ?, Name = ?, Surname = ? , IdMEDRESERV = ?, phone = ?, IdMEDEmail = ? , Verificado = '0' , token=?,salt=?, registered_code = ?, previlege = ?, speciality = 'General Practice'");   
	$q->bindValue(1, $IdMEDFIXED, PDO::PARAM_INT);
	$q->bindValue(2, $IdMEDFIXEDNAME, PDO::PARAM_STR);
	$q->bindValue(3, $Gender, PDO::PARAM_INT);
	$q->bindValue(4, $OrderOB, PDO::PARAM_INT);
	$q->bindValue(5, $Name, PDO::PARAM_STR);
	$q->bindValue(6, $Surname, PDO::PARAM_STR);
	$q->bindValue(7, $IdMEDRESERV, PDO::PARAM_STR);
	$q->bindValue(8, $phone, PDO::PARAM_INT);
	$q->bindValue(9, $IdMEDEmail, PDO::PARAM_STR);
	$q->bindValue(10, $confirm_code, PDO::PARAM_STR);
	$q->bindValue(11, $additional_string, PDO::PARAM_STR);
	$q->bindValue(12, $access_code, PDO::PARAM_STR);
	$q->bindValue(13, $previlege, PDO::PARAM_INT);
	$q->execute();
}
else
{
$q = $con->prepare("INSERT INTO doctors SET IdMEDFIXED = ?, IdMEDFIXEDNAME = ?,  Gender = ?, OrderOB = ?, Name = ?, Surname = ? , IdMEDRESERV = ?, phone = ?, IdMEDEmail = ? , Verificado = '0' , token=?,salt=?, registered_code = ?, previlege = ?, speciality = 'General Practice'");   
	$q->bindValue(1, $IdMEDFIXED, PDO::PARAM_INT);
	$q->bindValue(2, $IdMEDFIXEDNAME, PDO::PARAM_STR);
	$q->bindValue(3, $Gender, PDO::PARAM_INT);
	$q->bindValue(4, $OrderOB, PDO::PARAM_INT);
	$q->bindValue(5, $Name, PDO::PARAM_STR);
	$q->bindValue(6, $Surname, PDO::PARAM_STR);
	$q->bindValue(7, $IdMEDRESERV, PDO::PARAM_STR);
	$q->bindValue(8, $phone, PDO::PARAM_INT);
	$q->bindValue(9, $IdMEDEmail, PDO::PARAM_STR);
	$q->bindValue(10, $confirm_code, PDO::PARAM_STR);
	$q->bindValue(11, $additional_string, PDO::PARAM_STR);
	$q->bindValue(12, $access_code, PDO::PARAM_STR);
	$q->bindValue(13, $previlege, PDO::PARAM_INT);
	$q->execute();
	}

// LLAMADA PARA VERIFICAR EL TELEFONO DEL PACIENTE: ****************************************************

require_once 'MBCaller.php';
//$a = SendCallVERIF (34608754342);

// ENVÍA EL EMAIL AL PACIENTE: ****************************************************
require_once 'lib/swift_required.php';

$aQuien = $IdMEDEmail;
$Tema = 'Inmers Account Confirmation';

$info_block = '<ul style="display:block;margin:15px 20px;padding:0;list-style:none;border-top:1px solid #eee">
<li style="display:block;margin:0;padding:5px 0;border-bottom:1px solid #eee"><strong>Your Email:</strong>   <a href="mailto:'.$IdMEDEmail.'" target="_blank">'.$IdMEDEmail.'</a></li>
<li style="display:block;margin:0;padding:5px 0;border-bottom:1px solid #eee"><strong>Your Number Id:</strong> '.$IdMEDFIXED.' </li>
<li style="display:block;margin:0;padding:5px 0;border-bottom:1px solid #eee"><strong>Your Name Id:</strong>       '.$IdMEDFIXEDNAME.'</li>
</ul>';


$adicional ='<p>Please follow the link to verify your identity and complete the sign up process.</p><br><p>For your records here is a copy of the information you submitted to us...</p>'.$info_block;

$confirm_button = '<a href='.$domain.'/ConfirmaUser.php?token='.$confirm_code.' style="cursor:auto; color:#ffffff;display:inline-block;font-family:\'Helvetica\',Arial,sans-serif;width:auto;white-space:nowrap;min-height:32px;margin:5px 5px 0 0;padding:0 22px;text-decoration:none;text-align:center;font-weight:bold;font-style:normal;font-size:15px;line-height:32px;border:0;border-radius:4px;vertical-align:top;background-color:#3498db" target="_blank"><span style="display:inline;font-family:\'Helvetica\',Arial,sans-serif;text-decoration:none;font-weight:bold;font-style:normal;font-size:15px;line-height:32px;border:none;background-color:#3498db;color:#ffffff">Yes, confirm my account.</span></a>';

$Sobre = $Tema;
$Body = '<a href="#"><img src="'.$domain.'/images/health2me_horizontal.png"></a></p><p>Thank you for your interest in our services!</p><p><h1>Please Confirm Account</h1></p><p>'.$confirm_button.'</p>'.$adicional;


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

$salto="location:SignIn.html";
header($salto);

?>