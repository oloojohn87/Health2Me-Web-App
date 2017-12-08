<?php
//Code for validating doctor email entered by patient and sending mail further to the doctor
require("environment_detail.php");
require("NotificationClass.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$email = $_GET['emailId'];
$user = $_GET['user'];
$idpins = '';
$idpins_array = array();
if(isset($_GET['idpins']))
{
    $idpins = $_GET['idpins'];
    $idpins_array = explode("_", $idpins);
}

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}	

$notifications = new Notifications();

//Selecting the Name, Surname and IdMEDEmail if the doctor email which the patient enetered is present in DB
$query1 = $con->prepare("select id,Name,Surname,IdMEDEmail from doctors where IdMEDEmail = ? AND salt is not null");
$query1->bindValue(1, $email, PDO::PARAM_STR);
$query1->execute();
$row1 = $query1->fetch(PDO::FETCH_ASSOC);

$doctorName = $row1['Name'];
$doctorSurname = $row1['Surname'];
$doctorId = $row1['id'];

echo $doctorId;

$count1 = $query1->rowCount();
unset($query1);

//Finding whether the doctor exists in the nonh2m_doctors table

/*
$query1 = $con->prepare("select count(*) as count from nonh2m_doctors where IdMEDEmail = ?");
$query1->bindValue(1, $email, PDO::PARAM_STR);
$query1->execute();

$row1 = $query1->fetch(PDO::FETCH_ASSOC);
$count2 = $row1['count'];
*/

$confirm_code = md5(uniqid(rand()));

//New code added by Pallab for getting the DOB of the patient
$dob = $con->prepare("select DOB from basicemrdata where IdPatient = ?");
$dob->bindValue(1,$user,PDO::PARAM_INT);
$dob->execute();
$result = $dob->fetch(PDO::FETCH_ASSOC);
$temp = explode(' ',$result['DOB']);
$DOB = $temp[0];


$query2 = $con->prepare("select email,identif,Alias,telefono from usuarios where Identif = ?");
$query2->bindValue(1, $user, PDO::PARAM_INT);
$query2->execute();
$row2 = $query2->fetch(PDO::FETCH_ASSOC);
unset($query2);
$alias = $row2['Alias'];
$splittedString = explode(".",$alias);
$userName = $splittedString[0];
$userSurname = $splittedString[1];
$userId = $row2['identif'];
$emailUser = $row2['email'];
$phone = $row2['telefono'];


// The doctor is a user of the H2M system if we have a row and count is 1
if($count1 == 1) 
{
    $query = $con->prepare("insert into message_infrastructureuser (subject,content,tofrom,receiver_id,fecha,patient_id,status) values('Send Mail','Mail from Patient','to',?,now(),?,'new')");
    $query->bindValue(1, $doctorId, PDO::PARAM_INT);
    $query->bindValue(2, $userId, PDO::PARAM_INT);
    $query->execute();
    for($i = 0; $i < count($idpins_array); $i++)
    {
        $results = $con->prepare("SELECT id FROM doctorslinkusers WHERE IdMED = ? AND IdUs = ? AND IdPIN = ?");
        $results->bindValue(1, $doctorId, PDO::PARAM_INT);
        $results->bindValue(2, $userId, PDO::PARAM_INT);
        $results->bindValue(3, $idpins_array[$i], PDO::PARAM_INT);
        $results->execute();
        $rowCount = $results->rowCount();
        if($rowCount == 0)
        {
            $results = $con->prepare("insert into doctorslinkusers (IdMED,IdUs,Fecha,IdPIN,estado) values(?,?,NOW(),?,2)");
            $results->bindValue(1, $doctorId, PDO::PARAM_INT);
            $results->bindValue(2, $userId, PDO::PARAM_INT);
            $results->bindValue(3, $idpins_array[$i], PDO::PARAM_INT);
            $results->execute();
        }
    }

    $notifications->add('REVREQ', $userId, false, $doctorId, true, null);
    echo "domain is ".$domain;
    require_once 'lib/swift_required.php';
    $Content = file_get_contents('templates/SendMessageFromPatientToDoc.html');
    $Var3 = $userName." ".$userSurname;
    $Var4 = $doctorName;
    $Var5 = $doctorSurname;
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
    }
    $body = $Content;

    $subject = 'View Medical Records Of: '.$userName.' '.$userSurname;

    $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
        ->setUsername('dev.health2me@gmail.com')
        ->setPassword('health2me');

    $mailer = Swift_Mailer::newInstance($transporter);


    $message = Swift_Message::newInstance()
        ->setSubject($subject)
        ->setFrom(array('hub@inmers.us' => $userName.' '.$userSurname.' via Health2Me'))
        ->setTo(array($email))
        ->setBody($body, 'text/html')
        ;

    $result = $mailer->send($message);

    /* $storingMessage = $con->prepare("INSERT INTO user_activity (IdUser,IdDoctor,Message) values(?,?,'Send Mail')");
         $storingMessage->bindValue(1, $doctorId, PDO::PARAM_INT);
         $storingMessage->bindValue(2, $userId, PDO::PARAM_INT);
         $storingMessage->execute();
         unset($storingMessage); */
}



else if($count1 == 0)
{
    // If the doctor is not into the doctors table then insert him there
    $doctorId = 0;

    // Check if this non-H2M doctor has already been added as a non-privileged doctor
    $query1 = $con->prepare("SELECT id FROM doctors WHERE IdMEDEmail = ?");
    $query1->bindValue(1, $email, PDO::PARAM_STR);
    $query1->execute();
    $query1_count = $query1->rowCount();

    if($query1_count == 0)
    {
        // Id he/she isn't there, add them to the doctor's table as a non-privileged doctor
        $query3 = $con->prepare("INSERT INTO doctors SET IdMEDEmail = ?,previlege=1,token=?");
        $query3->bindValue(1, $email, PDO::PARAM_STR);
        $query3->bindValue(2, $confirm_code, PDO::PARAM_STR);
        $query3->execute();
        $doctorId = $con->lastInsertId();
        unset($query3);
    }
    else
    {
        $row4 = $query1->fetch(PDO::FETCH_ASSOC);
        $doctorId = $row4['id'];
    }
    unset($query1);

    $temp_access = $con->prepare("INSERT INTO temporary_doctor_access SET doctorId = ?, patientId = ?, sendTime = NOW(), expirationTime = NOW() + INTERVAL 1 DAY");
    $temp_access->bindValue(1, $doctorId, PDO::PARAM_INT);
    $temp_access->bindValue(2, $userId, PDO::PARAM_INT);
    $temp_access->execute();
    unset($temp_access);

    $notifications->add('REVREQ', $userId, false, $doctorId, true, null);

    require_once 'lib/swift_required.php';
    $Content = file_get_contents('templates/SendMessageFromPatientToNonH2MDoc.html');
    $Var3 = $userName." ".$userSurname;
    //$Var4 = $doctorName; // Commented out the Var4 and Var5 as there is no entry for the nonH2M doctor in the database 
    //$Var5 = $doctorSurname;
    $Var7 = $doctorId;
    $Var8 = $user;
    $Var9 = $confirm_code;
    $Var6 = $domain;

    $Content = str_replace("**Var3**",$Var3,$Content);
    //$Content = str_replace("**Var4**",$Var4,$Content);
    //$Content = str_replace("**Var5**",$Var5,$Content);
    $Content = str_replace("**Var7**",$Var7,$Content);
    $Content = str_replace("**Var8**",$Var8,$Content);
    $Content = str_replace("**Var9**",$Var9,$Content);
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
    }

    $body = $Content;

    $subject = 'View Medical Records Of: '.$userName.' '.$userSurname;

    $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
        ->setUsername('dev.health2me@gmail.com')
        ->setPassword('health2me');

    $mailer = Swift_Mailer::newInstance($transporter);


    $message = Swift_Message::newInstance()
        ->setSubject($subject)
        ->setFrom(array('hub@inmers.us' => $userName.' '.$userSurname.' via Health2Me'))
        ->setTo(array($email))
        ->setBody($body, 'text/html')
        ;

    $result = $mailer->send($message);

    // Inserting into Message Infrastructure user table to track to whom user has sent SEND requests
    /*  $storingMessage = $con->prepare("insert into message_infrastructureuser (subject,content,tofrom,receiver_id,fecha,patient_id,status) values('Send Mail','Mail from Patient','to',?,now(),?,'new')");
            $storingMessage->bindValue(1, $doctorId, PDO::PARAM_INT);
            $storingMessage->bindValue(2, $userId, PDO::PARAM_INT);
            $storingMessage->execute();
            unset($storingMessage); */

    echo $result;
}
?>
