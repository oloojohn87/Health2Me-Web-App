<?php
require("environment_detail.php");
require("NotificationClass.php");
$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];
$hardcode = $env_var_db["hardcode"];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}		

$notifications = new Notifications();

$id = $_POST['id'];
$specific_time = "";
if(isset($_POST['specific_time']))
{
    $time = $_POST['specific_time'];
    if($time == 'n' || $time == 'null' || $time == null || strlen($time) == 0)
    {
        $specific_time = 'null';
    }
    else
    {
        $time_exp = explode(":", $time);
        $hour = intval($time_exp[0]);
        $minute = substr($time_exp[1], 0, 2);
        $meridiem = substr($time_exp[1], 2, 2);

        if($meridiem == 'pm' && $hour < 12)
        {
            $hour += 12;
        }
        if($hour < 10)
        {
            $specific_time = "0";
        }
        else
        {
            $specific_time = "";
        }
        $specific_time .= $hour.":".$minute.":00";
    }
}

echo $specific_time;

if($specific_time == 'null')
{
    $res = $con->prepare("UPDATE appointments SET specific_time = null WHERE id=?");
    $res->bindValue(1, $id, PDO::PARAM_INT);
    $res->execute();
}
else if(strlen($specific_time) > 0)
{
    $res = $con->prepare("UPDATE appointments SET specific_time=? WHERE id=?");

    $res->bindValue(1, $specific_time, PDO::PARAM_STR);
    $res->bindValue(2, $id, PDO::PARAM_INT);
    $res->execute();

    $query = $con->prepare("SELECT * FROM appointments WHERE id=?");
    $query->bindValue(1, $id, PDO::PARAM_INT);
    $query->execute();

    $row = $query->fetch(PDO::FETCH_ASSOC);

    $query2 = $con->prepare("SELECT * FROM doctors WHERE id=?");
    $query2->bindValue(1, $row['med_id'], PDO::PARAM_INT);
    $query2->execute();

    $row2 = $query2->fetch(PDO::FETCH_ASSOC);

    $query3 = $con->prepare("SELECT * FROM usuarios WHERE Identif=?");
    $query3->bindValue(1, $row['pat_id'], PDO::PARAM_INT);
    $query3->execute();

    $row3 = $query3->fetch(PDO::FETCH_ASSOC);

    $time_calced = substr($specific_time, 0, 2);
    $time_mincalced = substr($specific_time, 2, 3);
    if($time_calced % 12 > 0){
        $ampm = 'pm';
        $time_calced = ($time_calced % 12).$time_mincalced;
    }else{
        $ampm = 'am';
        $time_calced = $time_calced.$time_mincalced;
    }
    
    $notifications->add('APPUPD', $row['med_id'], true, $row['pat_id'], false, $row['date'].' '.$specific_time);

    require_once 'lib/swift_required.php';
    $Content = file_get_contents('templates/SendExactAppointmentTimeToMember.html');
    $Var1 = 'Appointment Confirmed';
    $Var3 = $row2['Name'].' '.$row2['Surname'];
    $Var4 = date("g:i A", strtotime($specific_time)).' '.date("F j, Y", strtotime($row['date']));
    $Var10 = $hardcode;
    
    $Content = str_replace("**Var1**",$Var1,$Content);
    $Content = str_replace("**Var3**",$Var3,$Content);
    $Content = str_replace("**Var4**",$Var4,$Content);
    $Content = str_replace("**Var10**",$Var10,$Content);

    $body = $Content;

    $subject = 'Exact appointment time has been Specified for your appointment by Dr. '.$row2['Name'].' '.$row2['Surname'].'.';//.$userName.' '.$userSurname;

    $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
        ->setUsername('dev.health2me@gmail.com')
        ->setPassword('health2me');

    $mailer = Swift_Mailer::newInstance($transporter);


    $message = Swift_Message::newInstance()
        ->setSubject($subject)
        ->setFrom(array('hub@inmers.us' => $Var3.' via Health2Me'))
        ->setTo(array($row3['email']))
        ->setBody($body, 'text/html')
        ;

    $result = $mailer->send($message);

    require_once "Services/Twilio.php";		     
    $AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
    $AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
    // Instantiate a new Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
    /* Your Twilio Number or Outgoing Caller ID */
    $from = '+19034018888'; 
    $numbersOnly = preg_replace('/[^0-9,]|,[0-9]*$/','',$row3['telefono']);
    $to= '+'.$numbersOnly; 
    $body = 'Dr. '.$row2['Name'].' '.$row2['Surname'].' has confirmed your appointment at '.date("g:i A", strtotime($specific_time)).' '.date("F j, Y", strtotime($row['date']));
    try{
        $client->account->sms_messages->create($from, $to, $body);
    }catch(Exception $e){
        echo "Twilio could not text message number.  Number is most likely incorrect.".$e;
    }

}


?>
