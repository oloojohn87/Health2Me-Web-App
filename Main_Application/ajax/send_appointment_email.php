<?php

require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

require_once 'lib/swift_required.php';

$id = $_GET['id'];
$type = $_GET['type'];

$res = $con->prepare("SELECT * FROM appointments WHERE id=?");
$res->bindValue(1, $id, PDO::PARAM_INT);
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);

$res = $con->prepare("SELECT * FROM usuarios WHERE Identif=?");
$res->bindValue(1, $row['pat_id'], PDO::PARAM_INT);
$res->execute();
$pat_row = $res->fetch(PDO::FETCH_ASSOC);

$res = $con->prepare("SELECT * FROM doctors WHERE id=?");
$res->bindValue(1, $row['med_id'], PDO::PARAM_INT);
$res->execute();
$doc_row = $res->fetch(PDO::FETCH_ASSOC);

$to = '';
$Var3 = $type;
$var4 = '';
if($Var3 == 'doctor')
{
    $Var4 = $doc_row['Name'].' '.$doc_row['Surname'];
    $to = $pat_row['email'];
}
else
{
    $Var4 = $pat_row['Name'].' '.$pat_row['Surname'];
    $to = $doc_row['IdMEDEmail'];
}

$date = new DateTime($row['date']);
$Var5 = $date->format('F j, Y');

$start_time = new DateTime($row['start_time']);
$Var6 = $start_time->format('g:i A');
$end_time = new DateTime($row['end_time']);
$Var7 = $end_time->format('g:i A');

$domain = 'http://'.strval($dbhost);
$Var10 = $domain;

$Content = file_get_contents('templates/AppointmentMessage.html');

$Content = str_replace("**Var3**",$Var3,$Content);
$Content = str_replace("**Var4**",$Var4,$Content);
$Content = str_replace("**Var5**",$Var5,$Content);
$Content = str_replace("**Var6**",$Var6,$Content);
$Content = str_replace("**Var7**",$Var7,$Content);
$Content = str_replace("**Var10**",$Var10,$Content);

if(strlen($to) > 0)
{
    $body = $Content;
    
    
    $subject = 'New Appointment Created';
    
    $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
    ->setUsername('dev.health2me@gmail.com')
    ->setPassword('health2me');
    
    $mailer = Swift_Mailer::newInstance($transporter);
    
    
    $message = Swift_Message::newInstance()
    ->setSubject($subject)
    ->setFrom(array('hub@inmers.us' => 'Health2Me Clinical Support Center'))
    ->setTo(array($to))
    ->setBody($body, 'text/html')
    ;
    
    $result = $mailer->send($message);
    echo '1';
}
else
{
    echo '0';
}


?>
