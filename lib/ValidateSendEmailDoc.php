<?php
require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$email = $_GET['emailId'];
// Connect to server and select databse.
$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

$result = mysql_query("select IdMEDEmail from doctors where IdMEDEmail = '$email'");

$count = mysql_num_rows($result);

echo $count;
if($count == 1)
{
 require_once 'lib/swift_required.php';
 $Content = 'Hi';
 $body = $Content;

 $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
  ->setUsername('dev.health2me@gmail.com')
  ->setPassword('health2me');

$mailer = Swift_Mailer::newInstance($transporter);

// Create the message
$message = Swift_Message::newInstance()
  
  // Give the message a subject
  ->setSubject('Test Email')

  // Set the From address with an associative array
  ->setFrom(array('hub@inmers.us' => 'Health2Me Clinical Support Center'))

  // Set the To addresses with an associative array
  ->setTo(array($email))

  ->setBody($body, 'text/html')

  ;

$result = $mailer->send($message);
}



?>
