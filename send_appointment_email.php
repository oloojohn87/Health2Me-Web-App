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
$email_type = 'h2m';
if(isset($_GET['email_type']))
{
    $email_type = $_GET['email_type'];
}

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

$Var10 = $domain;
$Content = null;
$from = 'Health2Me Clinical Support Center';
if($email_type == 'h2m')
{
    $Content = file_get_contents('templates/AppointmentMessage.html');
    $Content = str_replace("**Var3**",$Var3,$Content);
    $Content = str_replace("**Var4**",$Var4,$Content);
    $Content = str_replace("**Var5**",$Var5,$Content);
    $Content = str_replace("**Var6**",$Var6,$Content);
    $Content = str_replace("**Var7**",$Var7,$Content);
    $Content = str_replace("**Var10**",$Var10,$Content);
}
else if($email_type == 'catapult')
{
    $from = 'Catapult Health';
    $specific_time = new DateTime($row['specific_time']);
    $Var6 = $specific_time->format('g:i A');
    $Content = file_get_contents('templates/AppointmentMessageCatapult.html');
    $Content = str_replace("**Var4**",$Var4,$Content);
    $Content = str_replace("**Var5**",$Var5,$Content);
    $Content = str_replace("**Var6**",$Var6,$Content);
    $Content = str_replace("**Var10**",$Var10,$Content);
    
    if($pat_row['telefono'] != null)
    {
    
        require_once "Services/Twilio.php";		     
        $AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
        $AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";

        // Instantiate a new Twilio Rest Client
        $client = new Services_Twilio($AccountSid, $AuthToken);

        $text_from = '+19034018888'; 
        $text_to= '+'.$pat_row['telefono']; 

        //SMS BODY
        $text_body = 'A new appointment has been created for you with NP '.$Var4.' on '.$Var5.' at '.$Var6;

        //TRY SENDING MESSAGE
        try{
        $client->account->sms_messages->create($text_from, $text_to, $text_body);
        }catch(Exception $e){
        echo "Twilio could not text message number.  Number is most likely incorrect.".$e;
        }
    }
}


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
    ->setFrom(array('hub@inmers.us' => $from))
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
