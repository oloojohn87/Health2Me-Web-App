<?php
define('INCLUDE_CHECK',1);
require "logger.php";
require("environment_detail.php");
require("NotificationClass.php");

$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];
$hardcode = $env_var_db['hardcode'];
$domain = $hardcode;

$notifications = new Notifications();


$Tipo = $_GET['Tipo'];
$token = $_GET['token'];
$IdPac = $_GET['IdPac'];
$IdDoc = $_GET['IdDoc'];
$IdDocOrigin = $_GET['IdDocOrigin'];
$NameDocOrigin = $_GET['NameDocOrigin'];
$SurnameDocOrigin = $_GET['SurnameDocOrigin'];
$ToEmail = $_GET['ToEmail'];
//$From = $_GET['From'];
//$FromEmail = $_GET['FromEmail'];
//$Subject = $_GET['Subject'];
//$Content = $_GET['Content'];
$Leido = $_GET['Leido'];
$Push = $_GET['Push'];
$estado = $_GET['estado'];
$CallPhone = $_GET['CallPhone'];
$CellPhone=empty($_GET['CellPhone']) ? 0:$_GET['CellPhone'];
$attachments=empty($_GET['attachments']) ? 0:$_GET['attachments'];

$Onbehalf=$_GET['sendOnbehalf'];
$Onbehalfdoc=$_GET['Onbehalfdoc'];
$reftype=$_GET['reftype'];

$Idpin=0;
//$IdDoc=0;
$existingemail=false;
$FromText='';
$tbl_name="doctorslinkdoctors"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}	

$confirm_code=md5(uniqid(rand()));


if($Tipo!='1'){
    $result1 = $con->prepare("SELECT id from doctors where IdMEDEmail = ?");
    $result1->bindValue(1, $ToEmail, PDO::PARAM_STR);
    $result1->execute();

    $count = $result1->rowCount();
    if($count==1){
        $row1 = $result1->fetch(PDO::FETCH_ASSOC);
        $Tipo='1';
        $IdDoc=$row1['id'];	
        $existingemail=true;
    }
}


if($Tipo=='1'){
    $getinfo=$con->prepare("select id from doctorslinkdoctors where IdMED2=? and IdPac = ? and estado in (1,2)");
    $getinfo->bindValue(1, $IdDoc, PDO::PARAM_INT);
    $getinfo->bindValue(2, $IdPac, PDO::PARAM_INT);
    $getinfo->execute();
    $count1=$getinfo->rowCount();
    if($count1>=1){
        echo 3;
        return;
    }

}



if ($Tipo=='1')  // Referral Doctor is already a User
{



    $q = $con->prepare("INSERT INTO doctorslinkdoctors SET IdMED = ?, IdMED2 = ? , IdPac = ?, Fecha = NOW(), estado = 1, attachments=?,type=?, Confirm =? "); 
    $q->bindValue(1, $IdDocOrigin, PDO::PARAM_INT);
    $q->bindValue(2, $IdDoc, PDO::PARAM_INT);
    $q->bindValue(3, $IdPac, PDO::PARAM_INT);
    $q->bindValue(4, $attachments, PDO::PARAM_STR);
    $q->bindValue(5, $reftype, PDO::PARAM_INT);
    $q->bindValue(6, $confirm_code, PDO::PARAM_STR);
    $q->execute();
    
    $notifications->add('NEWREF', $IdDocOrigin, true, $IdDoc, true, $IdPac);

    //	$IdRef = $q->lastInsertId(); 



    $result = $con->prepare("SELECT * FROM doctors WHERE id = ? LIMIT 15");
    $result->bindValue(1, $IdDoc, PDO::PARAM_INT);
    $result->execute();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $Name = $row['Name'];
        $Surname = $row['Surname'];
        $Role = $row['Role'];
        $Treat = '';
        if ($Role=='1') $Treat = 'Dr.';
        $Email = $row['IdMEDEmail'];
        $Numero = $row['phone'];
        $DateSent = $row['Fecha'];

    }

    $result = $con->prepare("SELECT * FROM usuarios WHERE Identif = ? LIMIT 15");
    $result->bindValue(1, $IdPac, PDO::PARAM_INT);
    $result->execute();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $NamePac = $row['Name'];
        $SurnamePac = $row['Surname'];
    }

    $Scenario = ''; //????
    $Reminder1 = '';
    $Reminder2 = '';
    if ($Scenario == 'reminder')
    {
        $Reminder1 = 'REMINDER: ';
        $Reminder2 = ' on '.$DateSent.'.';
    }else
    {
        $Reminder1 = '';
        $Reminder2 = '.';
    }

    // SEND EMAIL TO REFERRAL:
    require_once 'lib/swift_required.php';

    $NameDoctor = $Treat.' '.$Name.' '.$Surname;
    $NamePatient = $NamePac.' '.$SurnamePac;

    $aQuien = $ToEmail;
    $Sobre = 'I referred a patient to you: '.$NamePatient;

    $FromText = 'Dr. '.$NameDocOrigin.' '.$SurnameDocOrigin.' via Health2Me';
    //echo 'first val11'.$FromText;
    if($Onbehalf==1){

        $FromText = $NameDocOrigin.' On behalf of Dr.'.$Onbehalfdoc.' via Health2Me';
        //$FromText = 'On behalf of Dr.'.$Onbehalfdoc.' via Health2Me';
    }

    //echo 'second val11'.$FromText;


    $Content = '<p style="font-size:14px; color:blue;">Health2Me Referrals Network.</p><p>Hello '.$NameDoctor.',</p>';
    $ContenidoAdic = '<p>'.$NameDocOrigin.' '.$SurnameDocOrigin.' is referring the patient '.$NamePatient.' to you. Please click this link to confirm: </p><p style="font-size:18px;">'.$hardcode.'patientdetailMED-REF.php?Nombre=nametest.surnametest&Password=11111111&IdMed='.$IdDoc.'&IdUsu='.$IdPac.'&Acceso=23432&Confirm='.$confirm_code.'</p>';

    $ContenidoAdic='';

    $Content = file_get_contents($hardcode.'templates/referral2.html'); //Changed Referral2 to referral2 and removed hardcoded url http://dev.health2.me to $domain

    $Var1 = $Reminder1;
    $Var2 = $Reminder2;
    $Var3 = $NamePatient;
    $Var4 = $NameDocOrigin;
    $Var5 = $SurnameDocOrigin;
    $Var6 = $domain;
    $Var7 = $IdDoc;
    $Var8 = $IdPac;
    $Var9 = $confirm_code;
    $Var10 = '';
    $Var11 = '';
    $Var12 = '';
    $Var13 = '';
    $Var14 = '';
    $Var15 = '';
    $Content = str_replace("**Var1**",$Var1,$Content);
    $Content = str_replace("**Var2**",$Var2,$Content);
    $Content = str_replace("**Var3**",$Var3,$Content);
    $Content = str_replace("**Var4**",$Var4,$Content);
    $Content = str_replace("**Var5**",$Var5,$Content);
    $Content = str_replace("**Var6**",$Var6,$Content);
    $Content = str_replace("**Var7**",$Var7,$Content);
    $Content = str_replace("**Var8**",$Var8,$Content);
    $Content = str_replace("**Var9**",$Var9,$Content);
    $Content = str_replace("**Var10**",$Var10,$Content);
    $Content = str_replace("**Var11**",$Var11,$Content);
    $Content = str_replace("**Var12**",$Var12,$Content);
    $Content = str_replace("**Var13**",$Var13,$Content);
    $Content = str_replace("**Var14**",$Var14,$Content);
    $Content = str_replace("**Var15**",$Var15,$Content);

    $Body = $Content.$ContenidoAdic;

}
else            // Referral Doctor is NOT a User
{
    //$IdDoc=md5($ToEmail);

    //echo "Entering Non H2M section";

    /*$result = mysql_query("SELECT id from doctors where IdMEDEmail = '$ToEmail'");
  $count=mysql_num_rows($result);
  if($count==1){
    $row = mysql_fetch_array($result);

    echo 0;
	return;*/
    //}else if ($count==0){



    $q = $con->prepare("UPDATE doctors SET IdMEDEmail = ? where token=?");
    $q->bindValue(1, $ToEmail, PDO::PARAM_STR);
    $q->bindValue(2, $token, PDO::PARAM_STR);
    $q->execute();

    $res = $con->prepare("SELECT id from doctors where IdMEDEmail = ?");
    $res->bindValue(1, $ToEmail, PDO::PARAM_STR);
    $res->execute();

    $row = $res->fetch(PDO::FETCH_ASSOC);
    $id_doc = $row['id'];

    $q = $con->prepare("INSERT INTO doctorslinkdoctors SET IdMED = ?, IdMED2 = ? , IdPac = ?, Fecha = NOW(), estado = 1, attachments=?, type=?,Confirm =? ");
    $q->bindValue(1, $IdDocOrigin, PDO::PARAM_INT);
    $q->bindValue(2, $id_doc, PDO::PARAM_INT);
    $q->bindValue(3, $IdPac, PDO::PARAM_INT);
    $q->bindValue(4, $attachments, PDO::PARAM_STR);
    $q->bindValue(5, $reftype, PDO::PARAM_INT);
    $q->bindValue(6, $confirm_code, PDO::PARAM_STR);
    $q->execute();
    
    $notifications->add('NEWREF', $IdDocOrigin, true, $id_doc, true, $IdPac);

    $result1 = $con->prepare("SELECT * FROM usuarios WHERE Identif = ? LIMIT 15");
    $result1->bindValue(1, $IdPac, PDO::PARAM_INT);
    $result1->execute();

    while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) {
        $NamePac = $row1['Name'];
        $SurnamePac = $row1['Surname'];
    }


    // SEND EMAIL TO REFERRAL:
    require_once 'lib/swift_required.php';

    $NamePatient = $NamePac.' '.$SurnamePac;

    $aQuien = $ToEmail;
    $Sobre = 'Health2Me Referrals Network.- '.$NameDocOrigin.' sends you Patient: '.$NamePatient;

    $FromText = 'Dr. '.$NameDocOrigin.' '.$SurnameDocOrigin.' via Health2Me';
    //echo 'first val'.$FromText;
    if($Onbehalf==1){

        $FromText = $NameDocOrigin.' On behalf of Dr.'.$Onbehalfdoc.' via Health2Me';
        //$FromText = 'On behalf of Dr.'.$Onbehalfdoc.' via Health2Me';
    }
    //echo 'second val'.$FromText;

    /*
$Content = '<p style="font-size:14px; color:blue;">Health2Me Referrals Network.</p><p>Hello Doctor,</p>';
$ContenidoAdic = '<p>'.$NameDocOrigin.' '.$SurnameDocOrigin.' is referring the patient '.$NamePatient.' to you. Please click <a href="'.$domain.'/SignUpNU.php?tempID='.$id_doc.'&IdUsu='.$IdPac.'&Confirm='.$confirm_code.'">here</a> for signup and confirmation.</p>';
*/

    $ContenidoAdic='';

    $Content = file_get_contents($domain.'/templates/referral1.html'); //Changed from Referral1 to referral1 - Pallab

    $Var1 = $Reminder1;
    $Var2 = $Reminder2;
    $Var3 = $NamePatient;
    $Var4 = $NameDocOrigin;
    $Var5 = $SurnameDocOrigin;
    $Var6 = $domain;
    $Var7 = $id_doc;
    $Var8 = $IdPac;
    $Var9 = $ToEmail;
    //$Var10 = $confirm_code;
    $Var10 = $confirm_code;
    $Var11 = '';
    $Var12 = '';
    $Var13 = '';
    $Var14 = '';
    $Var15 = '';
    $Content = str_replace("**Var1**",$Var1,$Content);
    $Content = str_replace("**Var2**",$Var2,$Content);
    $Content = str_replace("**Var3**",$Var3,$Content);
    $Content = str_replace("**Var4**",$Var4,$Content);
    $Content = str_replace("**Var5**",$Var5,$Content);
    $Content = str_replace("**Var6**",$Var6,$Content);
    $Content = str_replace("**Var7**",$Var7,$Content);
    $Content = str_replace("**Var8**",$Var8,$Content);
    $Content = str_replace("**Var9**",$Var9,$Content);
    $Content = str_replace("**Var10**",$Var10,$Content);
    $Content = str_replace("**Var11**",$Var11,$Content);
    $Content = str_replace("**Var12**",$Var12,$Content);
    $Content = str_replace("**Var13**",$Var13,$Content);
    $Content = str_replace("**Var14**",$Var14,$Content);
    $Content = str_replace("**Var15**",$Var15,$Content);

    $Body = $Content.$ContenidoAdic;

    //}  
}

$transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
    ->setUsername('dev.health2me@gmail.com')
    ->setPassword('health2me');

$mailer = Swift_Mailer::newInstance($transporter);

// Create the message
$message = Swift_Message::newInstance()

    // Give the message a subject
    ->setSubject($Sobre)

    // Set the From address with an associative array

    ->setFrom(array('hub@inmers.us' => $FromText))

    // Set the To addresses with an associative array
    ->setTo(array($aQuien))

    ->setBody($Body, 'text/html')

    ;

$result = $mailer->send($message);
// SEND EMAIL TO REFERRAL ******************************

//die;

if ($CallPhone == '1')
{
    // SEND SMS TEXT TO REFERRAL
    require "Services/Twilio.php";

    $AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
    $AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
    // Instantiate a new Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
    /* Your Twilio Number or Outgoing Caller ID */
    $from = '+19034018888'; 
    $to= '+'.$CellPhone; 
    $body = 'Health2Me.- Patient '.$NamePatient.' has been referred to you. Access your account here https://www.health2.me ';
    $client->account->sms_messages->create($from, $to, $body);
    //echo "Sent message to $name";
    // SEND SMS TEXT TO REFERRAL
    //return 'OK';
}

if($existingemail)
    echo 0;
else
    echo 1;
?>
