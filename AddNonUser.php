<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require("environment_detailForLogin.php");
require("PasswordHash.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 $hardcode = $env_var_db['hardcode'];
 
 

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
$phone = $_POST['phone'];
$confirm_code=$_POST['confirmcode'];
$tempID=$_POST['tempID'];
$patientID=$_POST['PatientID'];


$queModo = 1;

//$confirm_code=md5(uniqid(rand()));

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
	$q = $con->prepare("UPDATE doctors SET IdMEDFIXED = ?, IdMEDFIXEDNAME = ?,  Gender = ?, OrderOB = ?, Name = ?, Surname = ? , IdMEDRESERV = ?, phone = ?, IdMEDEmail = ? , Verificado = '0' , token='*******',salt=? where id=?"); 
	$q->bindValue(1, $IdMEDFIXED, PDO::PARAM_INT);
	$q->bindValue(2, $IdMEDFIXEDNAME, PDO::PARAM_STR);
	$q->bindValue(3, $Gender, PDO::PARAM_INT);
	$q->bindValue(4, $OrderOB, PDO::PARAM_INT);
	$q->bindValue(5, $Name, PDO::PARAM_STR);
	$q->bindValue(6, $Surname, PDO::PARAM_STR);
	$q->bindValue(7, $IdMEDRESERV, PDO::PARAM_STR);
	$q->bindValue(8, $phone, PDO::PARAM_STR);
	$q->bindValue(9, $IdMEDEmail, PDO::PARAM_STR);
	$q->bindValue(10, $additional_string, PDO::PARAM_STR);
	$q->bindValue(11, $tempID, PDO::PARAM_INT);
	$q->execute();
}
else
{
	$q = $con->prepare("UPDATE doctors SET IdMEDFIXED = ?, IdMEDFIXEDNAME = ?,  Gender = ?, OrderOB = ?, Name = ?, Surname = ? , IdMEDRESERV = ?, phone = ?, IdMEDEmail = ? , Verificado = '0' , token='*******',salt=? where id=?");   
	$q->bindValue(1, $IdMEDFIXED, PDO::PARAM_INT);
	$q->bindValue(2, $IdMEDFIXEDNAME, PDO::PARAM_STR);
	$q->bindValue(3, $Gender, PDO::PARAM_INT);
	$q->bindValue(4, $OrderOB, PDO::PARAM_INT);
	$q->bindValue(5, $Name, PDO::PARAM_STR);
	$q->bindValue(6, $Surname, PDO::PARAM_STR);
	$q->bindValue(7, $IdMEDRESERV, PDO::PARAM_STR);
	$q->bindValue(8, $phone, PDO::PARAM_STR);
	$q->bindValue(9, $IdMEDEmail, PDO::PARAM_STR);
	$q->bindValue(10, $additional_string, PDO::PARAM_STR);
	$q->bindValue(11, $tempID, PDO::PARAM_INT);
	$q->execute();
}

//$result = mysql_query("SELECT id from doctors where IdMEDFIXED = '$IdMEDFIXED' and IdMEDEmail = '$IdMEDEmail'");
//$row = mysql_fetch_array($result);
//$id_doc = $row['id'];
//echo $id_doc ;

$result = $con->prepare("SELECT id from doctorslinkdoctors where Confirm=? and Idpac=?");
$result->bindValue(1, $confirm_code, PDO::PARAM_STR);
$result->bindValue(2, $patientID, PDO::PARAM_INT);
$result->execute();

$row = $result->fetch(PDO::FETCH_ASSOC);
$IdEntry=$row['id'];

$query =  $con->prepare("update doctorslinkdoctors set estado=2,FechaConfirm=now() ,Confirm='***N***' where id=?");
$query->bindValue(1, $IdEntry, PDO::PARAM_INT);
$query->execute();

$query=$con->prepare("insert into referral_stage set referral_id=?,stage=1");
$query->bindValue(1, $IdEntry, PDO::PARAM_INT);
$query->execute();

// LLAMADA PARA VERIFICAR EL TELEFONO DEL PACIENTE: ****************************************************

require_once 'MBCaller.php';
//$a = SendCallVERIF (34608754342);

// ENVÍA EL EMAIL AL PACIENTE: ****************************************************
require_once 'lib/swift_required.php';

$aQuien = $IdMEDEmail;

$adicional ='<p>Thank you for signing up with Health2me.</p><p>Your email: <span><h3>'.$IdMEDEmail.'</h3></span><p><p>Your Name id: <span><h3>'.$IdMEDFIXEDNAME.'</h3></span></p><p>Please use your Email id for sign in purposes.</p>';

$Sobre = $Tema;
$Body = $adicional;

$Content = file_get_contents('templates/InmersAccountCreation.html');
    $Var1 = 'Account Created';
    $Var3 = $adicional;
    //$Var4 = date("g:i A", strtotime($specific_time)).' '.date("F j, Y", strtotime($row['date']));
    $Var10 = $hardcode;
    
    $Content = str_replace("**Var1**",$Var1,$Content);
    $Content = str_replace("**Var3**",$Var3,$Content);
    //$Content = str_replace("**Var4**",$Var4,$Content);
    $Content = str_replace("**Var10**",$Var10,$Content);

    $Body = $Content;

    $subject = 'Inmers Account Confirmation.';//.$userName.' '.$userSurname;


/*$transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
  ->setUsername('newmedibank@gmail.com')
  ->setPassword('ardiLLA98');*/

$domain = 'http://'.strval($dbhost);

$transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
  ->setUsername('dev.health2me@gmail.com')
  ->setPassword('health2me');

$mailer = Swift_Mailer::newInstance($transporter);


// Create the message
$message = Swift_Message::newInstance()
  
  // Give the message a subject
  ->setSubject($subject)

  // Set the From address with an associative array
  ->setFrom(array('no-reply@health2.me' => 'health2.me'))

  // Set the To addresses with an associative array
  ->setTo(array($aQuien))

  ->setBody($Body, 'text/html')

  ;


$result = $mailer->send($message);
//echo 'Done';
$salto="location:SignIn.php";
//$loc=$domain.'/SignIn_Ref.php?userlogin='.$IdMEDEmail.'&idp='.$patientID ;

//echo "location:".$loc;
//$salto="location:".$loc;
header($salto);

?>
