<?php

require("environment_detailForLogin.php");
require("PasswordHash.php"); 
require_once 'lib/swift_required.php';

$dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass']; 
 $hardcode = $env_var_db['hardcode']; 

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$name = $_GET['name'];
$surname = $_GET['surname'];
$email = $_GET['email'];
$password = $_GET['password'];
$date = $_GET['date'];
$gender = $_GET['gender'];
$emailDoc = $_GET['emailDoc'];
$mem_id = $_GET['memid'];
$pins = $_GET['pins'];

//Gender in doctors is integer. So, setting appropriate gender values for male and female
if ($gender == "male")
{
    $gender = 0;
}
else
{
    $gender = 1;
}


$IdMEDFIXEDNAME = $name.'.'.$surname;

//adding encryption to the password
$hashresult = explode(":", create_hash($password));
$IdMEDRESERV= $hashresult[3];
$additional_string=$hashresult[2];

//finding the id of the new doctor to be added
$query1 = $con->prepare("select id from doctors where idMEDEmail = ?");
$query1->bindValue(1, $emailDoc, PDO::PARAM_STR);
$query1->execute();

$row1 = $query1->fetch(PDO::FETCH_ASSOC);
$IDDoc = $row1['id'];

$confirm_code = md5(uniqid(rand()));

//Updating the account values for the doctor who creates his account and who earlier had dropped files for a patient using     quickdropzone link on receiving request mail from patient
$q = $con->prepare("UPDATE doctors SET IdMEDFIXEDNAME = ?,  Gender = ?, Name = ?, Surname = ? , IdMEDRESERV = ?, IdMEDEmail = ? , token=?,salt=? where id=?"); 
$q->bindValue(1, $IdMEDFIXEDNAME, PDO::PARAM_STR);
$q->bindValue(2, $gender, PDO::PARAM_INT);
$q->bindValue(3, $name, PDO::PARAM_STR);
$q->bindValue(4, $surname, PDO::PARAM_STR);
$q->bindValue(5, $IdMEDRESERV, PDO::PARAM_STR);
$q->bindValue(6, $email, PDO::PARAM_STR);
$q->bindValue(7, $confirm_code, PDO::PARAM_STR);
$q->bindValue(8, $additional_string, PDO::PARAM_STR);
$q->bindValue(9, $IDDoc, PDO::PARAM_INT);
$q->execute();

$exp_pins = explode("_", $pins);
foreach($exp_pins as $pin_holder) {
    //echo $pin_holder;
	$query = $con->prepare("INSERT INTO doctorslinkusers SET IdMED = ?, IdUs = ?, IdPIN = ?, estado = 2, Fecha = NOW()"); 
	$query->bindValue(1, $IDDoc, PDO::PARAM_INT);
	$query->bindValue(2, $mem_id, PDO::PARAM_INT);
	$query->bindValue(3, $pin_holder, PDO::PARAM_INT);
	$query->execute();
}


$Content = file_get_contents($hardcode.'templates/RequestNewDocToAccessAccount.html');
$Var10 = $domain;
$Content = str_replace("**Var10**",$Var10,$Content);

 $body = $Content;

$domain = 'http://'.strval($dbhost);

 $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
  ->setUsername('dev.health2me@gmail.com')
  ->setPassword('health2me');

$mailer = Swift_Mailer::newInstance($transporter);


$message = Swift_Message::newInstance()
  ->setSubject('Access Your Free HEALTH2ME Account')
  ->setFrom(array('hub@inmers.us' => 'Health2Me Clinical Support Center'))
  ->setTo(array($emailDoc))
  ->setBody($body, 'text/html')
  ;

$result = $mailer->send($message);

?>
