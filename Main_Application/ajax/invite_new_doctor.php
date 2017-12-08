<?php

require("environment_detail.php");

$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}	
$doc_id = $_POST['doc_id'];
$pat_id = $_POST['pat_id'];

$res = $con->prepare("SELECT * FROM my_doctors WHERE id = ?");
$res->bindValue(1, $doc_id, PDO::PARAM_INT);
$res->execute();
$doc_row = $res->fetch(PDO::FETCH_ASSOC);

$res = $con->prepare("SELECT * FROM usuarios WHERE Identif = ?");
$res->bindValue(1, $pat_id, PDO::PARAM_INT);
$res->execute();
$pat_row = $res->fetch(PDO::FETCH_ASSOC);

if($doc_row['Email'] != NULL)
{
    require_once 'lib/swift_required.php';

    $to = $doc_row['Email'];
    $Subject = 'Health2Me - '.$pat_row['Name'].' '.$pat_row['Surname'].' invited you to join Health2Me';

    $FromText = $pat_row['Name'].' '.$pat_row['Surname'];

    $Content = file_get_contents($domain.'/templates/invite_doctor.html'); //Changed from Referral1 to referral1 - Pallab

    $Content = str_replace("**Var1**",$FromText,$Content);
    $Content = str_replace("**Var6**",$domain,$Content);
    



    $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
    ->setUsername('dev.health2me@gmail.com')
    ->setPassword('health2me');

    $mailer = Swift_Mailer::newInstance($transporter);

    // Create the message
    $message = Swift_Message::newInstance()

    // Give the message a subject
    ->setSubject($Subject)

    // Set the From address with an associative array

    ->setFrom(array('hub@inmers.us' => $FromText))

    // Set the To addresses with an associative array
    ->setTo(array($to))

    ->setBody($Content, 'text/html')

    ;

    $result = $mailer->send($message);
}
else
{
    echo 'NE';
}


?>