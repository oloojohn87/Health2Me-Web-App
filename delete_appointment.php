<?php

require("environment_detail.php");
require("NotificationClass.php");
$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			

$notifications = new Notifications();

$id = $_GET['id'];

//Start of new code added by Pallab
$search = $con->prepare("select med_id, pat_id,date,start_time,end_time from appointments where id =?");
$search->bindValue(1,$id,PDO::PARAM_INT);
$search->execute();
$result = $search->fetch(PDO::FETCH_ASSOC);



$doctorName = $con->prepare("select id,Name,Surname from doctors where id =?");
$doctorName->bindValue(1,$result['med_id'],PDO::PARAM_INT);
$doctorName->execute();
$result1 = $doctorName->fetch(PDO::FETCH_ASSOC);
$doctorname = $result1['Name'];
$doctorSurname = $result1['Surname'];

$userName = $con->prepare("select Identif,Name,Surname,email from usuarios where Identif =?");
$userName->bindValue(1,$result['pat_id'],PDO::PARAM_INT);
$userName->execute();
$result2 = $userName->fetch(PDO::FETCH_ASSOC);

$userEmail = $result2['email'];


//End of new code added by Pallab

// instead of deleting the entry from the appointments table, just set it as processed
/*
$res = $con->prepare("DELETE FROM appointments WHERE id=?");
$res->bindValue(1, $id, PDO::PARAM_INT);
$res->execute();
*/

$res = $con->prepare("UPDATE appointments SET processed = 1 WHERE id = ?");
$res->bindValue(1, $id, PDO::PARAM_INT);
$res->execute();

if($res)
{
    echo '1';
    
    $notifications->add('APPCNL', $result1['id'], true, $result2['Identif'], false, null);
}
else
{
    echo '0';
}

$domain = 'http://'.strval($dbhost);

//Code for sending mail notification to the member

         require_once 'lib/swift_required.php';
         $Content = file_get_contents('templates/SendAppointmentCancelMessageToMember.html');
         $Var3 = $doctorname." ".$doctorSurname;
         $Var4 = date("F j, Y", strtotime($result['date']));
         $Var1 = date("g:i A", strtotime($result['start_time'])).' - '.date("g:i A", strtotime($result['end_time']));
         $Var10 = $domain;
         $Content = str_replace("**Var3**",$Var3,$Content);
         $Content = str_replace("**Var1**",$Var1,$Content);
         $Content = str_replace("**Var4**",$Var4,$Content);
         $Content = str_replace("**Var10**",$Var10,$Content);
         
         /*$Var5 = $doctorSurname;
         $Var6 = $domain;

         $Content = str_replace("**Var3**",$Var3,$Content);
         $Content = str_replace("**Var4**",$Var4,$Content);
         $Content = str_replace("**Var5**",$Var5,$Content);
         $Content = str_replace("**Var6**",$Var6,$Content);
         $Content = str_replace("**Var11**",$emailUser,$Content);
         $Content = str_replace("**DOB**",$DOB,$Content);
         $Content = str_replace("**phone**",$phone,$Content);
    
        if(strlen($idpins) > 0)
        {
            $Content = str_replace("**Var10**", "&idpins=".$idpins, $Content);
        }
        else
        {
            $Content = str_replace("**Var10**", "", $Content);
        }*/
         $body = $Content;


         $domain = 'http://'.strval($dbhost);

         $subject = 'Cancel Appointment Notification: ';//.$userName.' '.$userSurname;

         $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
          ->setUsername('dev.health2me@gmail.com')
          ->setPassword('health2me');

         $mailer = Swift_Mailer::newInstance($transporter);


         $message = Swift_Message::newInstance()
          ->setSubject($subject)
          ->setFrom(array('hub@inmers.us' => $doctorname.' '.$doctorSurname.' via Health2Me'))
          ->setTo(array($userEmail))
          ->setBody($body, 'text/html')
          ;

         $result = $mailer->send($message);

?>
