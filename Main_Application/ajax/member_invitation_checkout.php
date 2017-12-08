<?php
session_start();
require("environment_detail.php");
require("NotificationClass.php");
require_once('stripe/stripe/lib/Stripe.php');
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$stripe = array(
  "secret_key"      => "sk_test_hJg0Ij3YDmTvpWMenFHf3MLn",
  "publishable_key" => "pk_test_YBtrxG7xwZU9RO1VY8SeaEe9"
);
Stripe::setApiKey($stripe['secret_key']);

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

$notifications = new Notifications();

$IdUsu = -1;
$IdMed = -1;
$email = '';
$phone = '';
$stripe_token = '';
$paid = false;
$end_date = '';


if(isset($_POST['paid']))
{
    $paid = $_POST['paid'];
}
if(isset($_POST['IdUsu']))
{
    $IdUsu = $_POST['IdUsu'];
}
if(isset($_POST['IdMed']))
{
    $IdMed = $_POST['IdMed'];
}
if(isset($_POST['email']))
{
    $email = $_POST['email'];
}
if(isset($_POST['phone']))
{
    $phone = str_replace('+', '', $_POST['phone']);
    $phone = str_replace(' ', '', $phone);
    $phone = str_replace('(', '', $phone);
    $phone = str_replace(')', '', $phone);
    $phone = str_replace('-', '', $phone);
}
if(isset($_POST['stripe_token']))
{
    $stripe_token = $_POST['stripe_token'];
}


if(isset($_POST['end_date'])){
	$end_date = $_POST['end_date'];    
	$end_date = date("Y-m-d H:i:s", strtotime(('+'.$end_date.' months'),strtotime(date("Y-m-d H:i:s"))));
}

if(isset($_POST['send'])){
	$patient_perm = 1;
}else{
	$patient_perm = 0;
}



$member = $con->prepare("SELECT CONCAT(name, ' ', surname) full_name FROM usuarios WHERE Identif = ?");
$member->bindValue(1, $IdUsu, PDO::PARAM_INT);
$member->execute();
$member_row = $member->fetch(PDO::FETCH_ASSOC);

$update = $con->prepare("UPDATE usuarios SET email = ?, telefono = ?, personal_doctor = ?, personal_doctor_accepted = 1 WHERE Identif = ?");
$update->bindValue(1, $email, PDO::PARAM_STR);
$update->bindValue(2, $phone, PDO::PARAM_STR);
$update->bindValue(3, $IdMed, PDO::PARAM_INT);
$update->bindValue(4, $IdUsu, PDO::PARAM_INT);
$update->execute();

$start_probe = false;

$probe_id = 0;

$probe_row = null;

if($paid)
{
    
    $result = 0;
    
	$check_existing = $con->prepare("SELECT * FROM probe WHERE patientID = ? && doctorID = ?");
	$check_existing->bindValue(1, $IdUsu, PDO::PARAM_INT);
	$check_existing->bindValue(2, $IdMed, PDO::PARAM_INT);
	$check_existing->execute();
	$check_existing_row = $check_existing->rowCount();
	
	if($check_existing_row == 0)
    {

		$ins = $con->prepare("INSERT INTO probe (doctorID, patientID, doctorPermission, patientPermission) VALUES (?, ?, 0, 1)");
        $ins->bindValue(1, $IdMed, PDO::PARAM_INT);
        $ins->bindValue(2, $IdUsu, PDO::PARAM_INT);
        $ins->execute();

        $notifications->add('NEWPRB', $IdMed, true, $IdUsu, false, null);
    
	}
    else
    {
        $probe_row = $check_existing->fetch(PDO::FETCH_ASSOC);
        if($probe_row['protocolID'] > 0 && strlen($probe_row['desiredTime']) > 0 && $probe_row['probeInterval'] > 0 && ($probe_row['emailRequest'] == 1 || $probe_row['smsRequest'] == 1 || $probe_row['phoneRequest'] == 1))
        {
            $ins = $con->prepare("UPDATE probe SET patientPermission = ".$patient_perm.", doctorPermission = 1, doctorID = ?, patientID = ?, scheduledEndDate = ?, scheduledMonthCount = ? WHERE patientID = ? && doctorID = ?");

            $ins->bindValue(1, $IdMed, PDO::PARAM_INT);
            $ins->bindValue(2, $IdUsu, PDO::PARAM_INT);
            $ins->bindValue(3, $end_date, PDO::PARAM_STR);
            $ins->bindValue(4, $_POST['end_date'], PDO::PARAM_INT);
            $ins->bindValue(5, $IdUsu, PDO::PARAM_INT);
            $ins->bindValue(6, $IdMed, PDO::PARAM_INT);


            $ins->execute();
            
            if($patient_perm == 1)
            {
                $start_probe = true;
            }
            else
            {
                $notifications->add('NEWPRB', $IdMed, true, $IdUsu, false, null);
            }
        }
        else
        {
            $ins = $con->prepare("UPDATE probe SET patientPermission = ".$patient_perm.", doctorPermission = 0, doctorID = ?, patientID = ?, scheduledEndDate = ?, scheduledMonthCount = ? WHERE patientID = ? && doctorID = ?");

            $ins->bindValue(1, $IdMed, PDO::PARAM_INT);
            $ins->bindValue(2, $IdUsu, PDO::PARAM_INT);
            $ins->bindValue(3, $end_date, PDO::PARAM_STR);
            $ins->bindValue(4, $_POST['end_date'], PDO::PARAM_INT);
            $ins->bindValue(5, $IdUsu, PDO::PARAM_INT);
            $ins->bindValue(6, $IdMed, PDO::PARAM_INT);

            $ins->execute();
            
            if($patient_perm == 0)
            {
                $notifications->add('NEWPRB', $IdMed, true, $IdUsu, false, null);
            }
        }
	}
    
    

    
    if($start_probe == 1)
    {
    
        $tok = strtok($probe_row['desiredTime'], ":");
        $hours = $tok;
        $tok = strtok(":");
        $ampm = substr($tok,strlen($tok)-2);
        $minutes = substr($tok,0,strlen($tok)-2);
        //$time = $hours.':'.$minutes.' '.$ampm;
        //echo $time;
        
        if($hours == '12' && $ampm == 'am')
            $hours = '00';
        else if($ampm == 'pm' && $hours != '12')
            $hours = strval(intval($hours) + 12);
        if(strlen($hours) == 1)
            $hours = '0'.$hours;
            
        $userTime = date('Y-m-d').' '.$hours.':'.$minutes.':00';
        
        $tzq = $con->prepare("SELECT offset FROM timezones WHERE id = ?");
        $tzq->bindValue(1, $probe_row['timezone'], PDO::PARAM_INT);
        $tzq->execute();
        $tzr = $tzq->fetch(PDO::FETCH_ASSOC);
        $tz = $tzr['offset'];
        
        $correctDateQ = $con->prepare("SELECT CONVERT_TZ(?, ?, '+00:00') AS tz");
        $correctDateQ->bindValue(1, $userTime, PDO::PARAM_STR);
        $correctDateQ->bindValue(2, $tz, PDO::PARAM_STR);
        $correctDateQ->execute();
        $correctDateR = $correctDateQ->fetch(PDO::FETCH_ASSOC);
        $correctDate = $correctDateR['tz'];
        
        $del = $con->prepare("DELETE FROM pendingprobes WHERE probeID = ?");
        $del->bindValue(1, $probe_row['probeID'], PDO::PARAM_INT);
        $del->execute();
        
        $ins2 = $con->prepare("INSERT INTO pendingprobes SET probeID = ?, requestTime = ?");
        $ins2->bindValue(1, $probe_row['probeID'], PDO::PARAM_INT);
        $ins2->bindValue(2, $correctDate, PDO::PARAM_STR);
        $ins2->execute();
        
        $ins3 = $con->prepare("SELECT TIMESTAMPDIFF(MINUTE, NOW(), requestTime) AS diff FROM pendingprobes WHERE probeID = ?");
        $ins3->bindValue(1, $probe_row['probeID'], PDO::PARAM_INT);
        $ins3->execute();
        $ins3_row = $ins3->fetch(PDO::FETCH_ASSOC);
        $diff = $ins3_row['diff'];
        if(intval($diff) <= -4)
        {
            $upd = $con->prepare("UPDATE pendingprobes SET requestTime = DATE_ADD(requestTime, INTERVAL 1 DAY) WHERE probeID = ?");
            $upd->bindValue(1, $probe_row['probeID'], PDO::PARAM_INT);
            $upd->execute();
        }
        
        $update = $con->prepare("UPDATE probe SET doctorPermission = 1 WHERE probeID = ?");
        $update->bindValue(1, $probe_row['probeID'], PDO::PARAM_INT);
        $update->execute();
        $res = 1;
    }
    
    


//    Stripe_Charge::create(array(
//        "amount" => 1500,
//        "currency" => "usd",
//        "card" => $stripe_token,
//        "description" => 'Charge for probe subscription for member '.$member_row['full_name'].' on '.date('F j, Y')
//    ));


    
    
}

if(isset($_POST['send_doctor_message']))
{
    $sql_que = $con->prepare("Insert into message_infrastructureuser set Subject=?,content=?,sender_id=?,receiver_id=?,tofrom='to',patient_id=?,connection_id=0,fecha=now(),status='new'");
    $sql_que->bindValue(1, 'Probe Activated', PDO::PARAM_STR);
    $sql_que->bindValue(2, 'I have activated my probe.', PDO::PARAM_STR);
    $sql_que->bindValue(3, $IdMed, PDO::PARAM_INT);
    $sql_que->bindValue(4, $IdMed, PDO::PARAM_INT);
    $sql_que->bindValue(5, $IdUsu, PDO::PARAM_INT);
    $res = $sql_que->execute();

    $notifications->add('NEWMES', $IdUsu, false, $IdMed, true, $con->lastInsertId());
}


?>

