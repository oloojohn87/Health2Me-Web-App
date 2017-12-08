<?php
header("Access-Control-Allow-Origin: *");
//Code for sending email to an external doctor and request him to send reports through either email or drop onto H2M or Fax
 
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

 $email = $_GET['emailId'];
 $user = $_GET['user'];
 $originalMessageForDoc = $_GET['message'];
 
 //Removing quotes from the message string
 $message1 = chop($originalMessageForDoc,"'");
 $messageAfterRemovingQoutes = ltrim($message1,"'");
 
 

 require_once 'lib/swift_required.php';
 
 

 $query1 = $con->prepare("select telefono,email,Alias from usuarios where Identif = ?");
 $query1->bindValue(1, $user, PDO::PARAM_INT);
 $query1->execute();
 
 $row1 = $query1->fetch(PDO::FETCH_ASSOC);

 $alias = $row1['Alias'];

 echo $alias;
 $splittedString = explode(".",$alias);
 $userName = $splittedString[0];
 $userSurname = $splittedString[1];
 $emailUser = $row1['email'];
 $phone = $row1['telefono'];
 
 echo $emailUser;
 $Content = file_get_contents($domain.'/templates/RequestReportsForNonH2M.html');
 $Var3 = $userName." ".$userSurname;
 $Var4 = $messageAfterRemovingQoutes;
 $Var10 = $domain;
 $Content = str_replace("**Var3**",$Var3,$Content);
 $Content = str_replace("UserID", $user,$Content);
 $Content = str_replace("**Var4**",$Var4,$Content);
 $Content = str_replace("**Var10**",$Var10,$Content);
 $Content = str_replace("**Var5**",$email,$Content);//The email id of the doctor is embedded into the HTML page which will be    later used in quickDropzoneForNonH2MDoc.php page while sending back mail to user through the FINISH button
 $Content = str_replace("**Var7**",$emailUser,$Content);
 $Content = str_replace("**phone**",$phone,$Content);



 $body = $Content;


$transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
  ->setUsername('dev.health2me@gmail.com')
  ->setPassword('health2me');


 $mailer = Swift_Mailer::newInstance($transporter);




    
$message = Swift_Message::newInstance()
  ->setSubject('Concerning Medical Records Of: '.$userName.' '.$userSurname)
  ->setFrom(array('hub@inmers.us' => $userName.' '.$userSurname.' via Health2Me'))
  ->setTo(array($email))
  ->setBody($body, 'text/html')
  ;


 
   $result = $mailer->send($message);

//Below code commented by Pallab
/*// Pass a variable name to the send() method
if (!$mailer->send($message, $failures))
{
  echo "Failures:";
  print_r($failures);
}*/


//checking whether the doctor is a temporary nonh2m doctor by checking in the doctors table as well as in the nonh2m_doctors, if not

$query2 = $con->prepare("select count(*) as count from doctors where IdMEDEmail = ?");
$query2->bindValue(1, $email, PDO::PARAM_STR);
$query2->execute();

$row2 = $query2->fetch(PDO::FETCH_ASSOC);
$count1 = $row2['count'];

/*
$query1 = $con->prepare("select count(*) as count from nonh2m_doctors where IdMEDEmail = ?");
$query1->bindValue(1, $email, PDO::PARAM_STR);
$query1->execute();

$row2 = $query2->fetch(PDO::FETCH_ASSOC);
$count2 = $row2['count'];
*/

//if(($count1 == 0 || $count1 == 1) && ($count2 == 0 || $count == 1))
//{
        // If the doctor is not into the doctors table then insert him into the doctors table
         if($count1 == 0)
          {
             $query = $con->prepare("insert into doctors (IdMEDEmail,previlege) values(?,1)");
             $query->bindValue(1,$email,PDO::PARAM_STR);
             $query->execute();
          }
    /*
          if($count2 == 0)
          {
             $query3 = $con->prepare("insert into nonh2m_doctors (IdMEDEmail,previlege) values(?,2)");
             $query3->bindValue(1, $email, PDO::PARAM_STR);
             $query3->execute();
          }
          */
//}


?>
