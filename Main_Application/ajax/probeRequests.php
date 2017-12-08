<?php
//session_start();
//set_include_path('/var/www/vhost1/');
//chdir(dirname(__FILE__));
require_once("environment_detailForLogin.php");
require ("push_server.php");

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 $hardcode = $env_var_db['hardcode'];

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

//THIS HANDLES THE APPOINTMENT REMINDERS////////////////////////////////////////////////////////////////////////////

$query = $con->prepare("SELECT *
, TIMESTAMPDIFF(MINUTE, ADDTIME(DATE_ADD(NOW(), INTERVAL 1 HOUR), `timezone`), CONCAT(`date`,' ',`specific_time`)) as time_diff

FROM `appointments` WHERE CONCAT(`date`,' ',`specific_time`)

BETWEEN DATE_SUB(ADDTIME(DATE_ADD(NOW(), INTERVAL 1 HOUR), `timezone`), INTERVAL 1 MINUTE) 

AND DATE_ADD(ADDTIME(DATE_ADD(NOW(), INTERVAL 1 HOUR), `timezone`), INTERVAL 9 MINUTE)");
$query->execute();

while($row = $query->fetch(PDO::FETCH_ASSOC)){

echo $row['time_diff'];
$query2 = $con->prepare("select * FROM usuarios WHERE Identif = ?");
$query2->bindValue(1, $row['pat_id'], PDO::PARAM_INT);
$result2 = $query2->execute();
$row2 = $query2->fetch(PDO::FETCH_ASSOC);

$query3 = $con->prepare("select * FROM doctors WHERE id = ?");
$query3->bindValue(1, $row['med_id'], PDO::PARAM_INT);
$result3 = $query3->execute();
$row3 = $query3->fetch(PDO::FETCH_ASSOC);
	
	require_once "Services/Twilio.php";		     
		$AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
		$AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
		// Instantiate a new Twilio Rest Client
		//MEMBER MESSAGE
		$client = new Services_Twilio($AccountSid, $AuthToken);

		$from = '+19034018888'; 
		$numbersOnly = preg_replace('/[^0-9,]|,[0-9]*$/','',$row3['phone']);
		$to= '+'.$numbersOnly; 
		$body = 'Your appointment with Dr. '.$row3['Name'].' '.$row3['Surname'].' is starting within '.$row['time_diff'].' minutes.  Please login to health2.me to receive the appointment.';
		try{
		$client->account->sms_messages->create($from, $to, $body);
		}catch(Exception $e){
		echo "Twilio could not text message number.  Number is most likely incorrect.".$e;
		}
		//DOCTOR MESSAGE
		$client2 = new Services_Twilio($AccountSid, $AuthToken);

		$from = '+19034018888'; 
		$numbersOnly = preg_replace('/[^0-9,]|,[0-9]*$/','',$row2['telefono']);
		$to= '+'.$numbersOnly; 
		$body = 'Your appointment with '.$row2['Name'].' '.$row2['Surname'].' is starting within '.$row['time_diff'].' minutes.  Please login to health2.me to receive the appointment.';
		try{
		$client2->account->sms_messages->create($from, $to, $body);
		}catch(Exception $e){
		echo "Twilio could not text message number.  Number is most likely incorrect.".$e;
		}
		
		//////////////////////MEMBER EMAIL////////////////////////////
		require_once 'lib/swift_required.php';
         $Content = file_get_contents('templates/SendAppointmentReminder.html');
         $Var1 = 'Dr. '.$row3['Name'].' '.$row3['Surname'];
         $Content = str_replace("**Var1**",$Var1,$Content);
         $Content = str_replace("**Var2**",$row['time_diff'],$Content);
         $Content = str_replace("**Var10**",$hardcode,$Content);
         
         $body = $Content;

         $subject = 'Your appointment with Dr. '.$row3['Name'].' '.$row3['Surname'].' starts within '.$row['time_diff'].' minutes.';//.$userName.' '.$userSurname;

         $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
          ->setUsername('dev.health2me@gmail.com')
          ->setPassword('health2me');

         $mailer = Swift_Mailer::newInstance($transporter);
		 
		 if($row3['IdMEDEmail'] == ''){
			 $doc_email = 'correct@format.com';
		 }else{
			 $doc_email = $row3['IdMEDEmail'];
		 }

         $message = Swift_Message::newInstance()
          ->setSubject($subject)
          ->setFrom(array('hub@inmers.us' => $row2['Name'].' '.$row2['Surname'].' via Health2Me'))
          ->setTo(array($doc_email))
          ->setBody($body, 'text/html')
          ;

         $result = $mailer->send($message);
         
         ////////////////////////////DOCTOR EMAIL/////////////////
         $Content = file_get_contents('templates/SendAppointmentReminder.html');
         $Var1 = $row2['Name'].' '.$row2['Surname'];
         $Content = str_replace("**Var1**",$Var1,$Content);
         $Content = str_replace("**Var2**",$row['time_diff'],$Content);
         $Content = str_replace("**Var10**",$hardcode,$Content);
         
         $body = $Content;

         $subject = 'Your appointment with '.$row2['Name'].' '.$row2['Surname'].' starts within '.$row['time_diff'].' minutes.';//.$userName.' '.$userSurname;

         $transporter2 = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
          ->setUsername('dev.health2me@gmail.com')
          ->setPassword('health2me');

         $mailer2 = Swift_Mailer::newInstance($transporter);
		
		 if($row2['email'] == ''){
			 $mem_email = 'correct@format.com';
		 }else{
			 $mem_email = $row2['email'];
		 }

         $message2 = Swift_Message::newInstance()
          ->setSubject($subject)
          ->setFrom(array('hub@inmers.us' => $row3['Name'].' '.$row3['Surname'].' via Health2Me'))
          ->setTo(array($row2['email']))
          ->setBody($body, 'text/html')
          ;

         $result = $mailer2->send($message2);
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$minutes = 2;

$query = $con->prepare("SELECT * FROM pendingprobes WHERE date(requestTime) = date(NOW()) and timestampdiff(MINUTE, NOW(), requestTime) <= ?");
$query->bindValue(1, $minutes, PDO::PARAM_INT);
$result = $query->execute();

$nu_query = $con->prepare("INSERT INTO debug_table SET two='yes'");
$nu_query->execute();

while($row = $query->fetch(PDO::FETCH_ASSOC))
{
	$probeID = $row['probeID'];
	//echo $probeID;
	//Fetch data of the Probe
	$query1 = $con->prepare("select * from probe where probeID=?");
	$query1->bindValue(1, $probeID, PDO::PARAM_INT);
	
	$result1=$query1->execute();
	$row1 = $query1->fetch(PDO::FETCH_ASSOC);
	
	if(strtotime($row1['scheduledEndDate']) >= time()){
	
	//If both doctor and patient have given permission for probes
        if($row1['doctorPermission']==1 && $row1['patientPermission']==1)
        {
            //Get DoctorName
            $doctorNameQuery = $con->prepare("select name,surname from doctors where id=?");
            $doctorNameQuery->bindValue(1, $row1['doctorID'], PDO::PARAM_INT);

            $res = $doctorNameQuery->execute();
            $row2 = $doctorNameQuery->fetch(PDO::FETCH_ASSOC);
            $dn = $row2['name'];
            $ds = $row2['surname'];
            $doctorname = $dn.' '.$ds;

            //Get patient Email and Phone
            $patientContactQuery = $con->prepare("select name,surname,email,telefono from usuarios where identif=?");
            $patientContactQuery->bindValue(1, $row1['patientID'], PDO::PARAM_INT);

            //echo $patientContactQuery;
            $res = $patientContactQuery->execute();
            $row2 = $patientContactQuery->fetch(PDO::FETCH_ASSOC);
            $patientemail = $row2['email'];
            $patientphone = $row2['telefono'];
            $patientname = $row2['name'];
            $patientsurname = $row2['surname'];

            $flag=0;

            // reset current question flag
            $reset = $con->prepare("UPDATE probe SET currentQuestion = 1 WHERE probeID = ?");
            $reset->bindValue(1, $probeID, PDO::PARAM_INT);
            $reset->execute();
            
            //update the entry in pendingProbe for next reminder
            $incrementInterval = $row1['probeInterval'];
            //$setNewProbeQuery = "UPDATE pendingprobes SET requestTime = DATE_ADD(requestTime,INTERVAL $incrementInterval DAY) WHERE probeID = $probeID";	
            $setNewProbeQuery = $con->prepare("UPDATE pendingprobes SET requestTime = concat(date(DATE_ADD(requestTime,INTERVAL ? DAY)),' ',time(requestTime)) WHERE probeID = ?");
            $setNewProbeQuery->bindValue(1, $incrementInterval, PDO::PARAM_INT);
            $setNewProbeQuery->bindValue(2, $probeID, PDO::PARAM_INT);
            $setNewProbeQuery->execute();

            //mysql_query($setNewProbeQuery);

            //send email to recipient if allowed
            if($row1['emailRequest']==1)
            {
                echo 'Sent E-Mail to '.$patientemail.' at '.Date('Y-m-d H:m') ;
                Push_Probe_Email($doctorname, $patientemail, $probeID, 0);
                $flag=1;


                //create log
                //logRequest($probeID,1);
            }
            else if($row1['phoneRequest']==1)
            {
                //call recipient if allowed

                echo ' CALLING...';

                echo 'Called '.$patientname.' '.$patientsurname.' on '.$patientphone;
                Health_Feedback_Call($dn, $ds, $patientname, $patientsurname, $patientphone, $probeID, 0);
                $flag=1;
                //logRequest($probeID,2);
            }
            else if($row1['smsRequest']==1)
            {
                //Send SMS if allowed
                echo 'Sent SMS to '.$patientname.' '.$patientsurname.' on '.$patientphone;
                Send_Feedback_SMS($ds, $patientname.' '.$patientsurname, $patientphone, $probeID, 0, 1);
                $flag=1;
                //logRequest($probeID,3);
            }





            








        }
        else
        {
            //delete entry FROM pendingprobes because it no longer has permission from doctor AND/OR  patient
            $deletePendingProbeQuery = $con->prepare("delete FROM pendingprobes where probeID=?");
            $deletePendingProbeQuery->bindValue(1, $probeID, PDO::PARAM_INT);
            $deletePendingProbeQuery->execute();

            //mysql_query($deletePendingProbeQuery);
        }
	}
		
	

}

//////////////////////////PAYMENT/PROBE NOTIFICATION BY EMAIL//////////////////////////////////////////

$query = $con->prepare("SELECT * FROM payments WHERE notify_email = 0");
$result = $query->execute();

while($row = $query->fetch(PDO::FETCH_ASSOC))
{
	$query_clean = $con->prepare("UPDATE payments SET notify_email = 1 WHERE id = ?");
	$query_clean->bindValue(1, $row['id'], PDO::PARAM_INT);
	$result_clean = $query_clean->execute();
	
	require_once "Services/Twilio.php";	
	require_once 'lib/swift_required.php';	     
	$AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
	$AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
	
	$send_patient = 0;
	$send_doctor = 0;
	if($row['owner_type'] == 'doctor'){
		$doctor_id = $row['owner_id'];
		$send_doctor = 1;
		
		if($row['service_id'] != null){
			$patient_id = $row['service_id'];
			$send_patient = 1;
		}
	}else{
		if($row['payout_id'] != null){
			$doctor_id = $row['payout_id'];
			$send_doctor = 1;
		}
		
		$patient_id = $row['owner_id'];
		$send_patient = 1;
	}

	if($send_doctor){
		$query3 = $con->prepare("select * FROM doctors WHERE id = ?");
		$query3->bindValue(1, $doctor_id, PDO::PARAM_INT);
		$result3 = $query3->execute();
		$row3 = $query3->fetch(PDO::FETCH_ASSOC);
	
	//echo 'send doc';
		// Instantiate a new Twilio Rest Client
		//DOCTOR MESSAGE/////////////////////////////////////////////
		$client = new Services_Twilio($AccountSid, $AuthToken);
		
		$from = '+19034018888'; 
		$numbersOnly = preg_replace('/[^0-9,]|,[0-9]*$/','',$row3['phone']);
		$to= '+'.$numbersOnly; 
		$body = $row['service'];
		try{
		$client->account->sms_messages->create($from, $to, $body);
		}catch(Exception $e){
		echo "Twilio could not text message number.  Number is most likely incorrect.".$e;
		}
		
		//////////////////////DOCTOR EMAIL////////////////////////////
	
		 $Content = file_get_contents('templates/SendPaymentNotification.html');
		 
		 if($row['service_type'] == 'probe'){
			 
			 if($row['currency'] == 'tokens'){
				 $cost = 'Your account has been charged $'.($row['amount']/100).' from your account balance.';
			 }else{
				 $cost = 'This member\'s account has been charged $'.($row['amount']/100).' from the credit card on file.';
			 }
			 
			 $Var1 = $row['service'].'.<br/><br/>'.$cost;
		 }elseif($row['service_type'] == 'buy credits'){
			 $Var1 = 'You have purchased $'.($row['amount']/100).' credits.  These funds have been made available immediately.';
		 }
		 //$Var1 = 'Dr. '.$row3['Name'].' '.$row3['Surname'];
		 $Content = str_replace("**Var1**",$Var1,$Content);
		 //$Content = str_replace("**Var2**",$row['time_diff'],$Content);
		 $Content = str_replace("**Var10**",$hardcode,$Content);
		 
		 $body = $Content;

		 $domain = 'http://'.strval($dbhost);

		 $subject = 'A payment has been made.';//.$userName.' '.$userSurname;

		 $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
		  ->setUsername('dev.health2me@gmail.com')
		  ->setPassword('health2me');

		 $mailer = Swift_Mailer::newInstance($transporter);
		 
		 if($row3['IdMEDEmail'] == ''){
			 $doc_email = 'correct@format.com';
		 }else{
			 $doc_email = $row3['IdMEDEmail'];
		 }

		 $message = Swift_Message::newInstance()
		  ->setSubject($subject)
		  ->setFrom(array('hub@inmers.us' => 'Health2Me Payment Tracker'))
		  ->setTo(array($doc_email))
		  ->setBody($body, 'text/html')
		  ;

		 $result = $mailer->send($message);
	}
	
	if($send_patient){
		$query2 = $con->prepare("select * FROM usuarios WHERE Identif = ?");
		$query2->bindValue(1, $patient_id, PDO::PARAM_INT);
		$result2 = $query2->execute();
		$row2 = $query2->fetch(PDO::FETCH_ASSOC);
		
		//echo 'send pat';
	
		//MEMBER MESSAGE//////////////////////////////////////////////////
		$client2 = new Services_Twilio($AccountSid, $AuthToken);

		$from = '+19034018888'; 
		$numbersOnly = preg_replace('/[^0-9,]|,[0-9]*$/','',$row2['telefono']);
		$to= '+'.$numbersOnly; 
		$body = $row['service'];
		try{
		$client2->account->sms_messages->create($from, $to, $body);
		}catch(Exception $e){
		echo "Twilio could not text message number.  Number is most likely incorrect.".$e;
		}
		
		////////////////////////////MEMBER EMAIL/////////////////
		 $Content = file_get_contents('templates/SendPaymentNotification.html');
		 
		 if($row['service_type'] == 'probe'){
			 
			 if($row['currency'] == 'tokens'){
				 $cost = 'Your\'re doctor has charged his account $'.($row['amount']/100).' from their account balance.';
			 }else{
				 $cost = 'Your account has been charged $'.($row['amount']/100).' from the credit card we have on file for you.';
			 }
			 
			 $Var1 = $row['service'].'.<br/><br/>'.$cost;
		 
		 
		 
		 //$Var1 = $row2['Name'].' '.$row2['Surname'];
		 $Content = str_replace("**Var1**",$Var1,$Content);
		 //$Content = str_replace("**Var2**",$row['time_diff'],$Content);
		 $Content = str_replace("**Var10**",$hardcode,$Content);
		 
		 $body = $Content;

		 $subject = 'A payment has been made.';//.$userName.' '.$userSurname;

		 $transporter2 = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
		  ->setUsername('dev.health2me@gmail.com')
		  ->setPassword('health2me');

		 $mailer2 = Swift_Mailer::newInstance($transporter2);
		
		 if($row2['email'] == ''){
			 $mem_email = 'correct@format.com';
		 }else{
			 $mem_email = $row2['email'];
		 }

		 $message2 = Swift_Message::newInstance()
		  ->setSubject($subject)
		  ->setFrom(array('hub@inmers.us' => 'Health2Me Payment Tracker'))
		  ->setTo(array($row2['email']))
		  ->setBody($body, 'text/html')
		  ;

		 $result = $mailer2->send($message2);
		}
	} 
	 

}

/////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////Email and text notify of account creation/////////////////////////

$query = $con->prepare("SELECT * FROM usuarios WHERE email_notify = 0 && IdUsRESERV is null");
$result = $query->execute();

	require_once "Services/Twilio.php";	
	require_once 'lib/swift_required.php';	     
	$AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
	$AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";

while($row = $query->fetch(PDO::FETCH_ASSOC))
{
	//echo 'loop';
	$query_clean = $con->prepare("UPDATE usuarios SET email_notify = 1 WHERE Identif = ?");
	$query_clean->bindValue(1, $row['Identif'], PDO::PARAM_INT);
	$result_clean = $query_clean->execute();

	$query_temp = $con->prepare("SELECT * FROM key_chain WHERE type = 'register' && owner = ? ORDER BY id DESC LIMIT 1");
	$query_temp->bindValue(1, $row['Identif'], PDO::PARAM_INT);
	$result_temp = $query_temp->execute();
	$row_temp = $query_temp->fetch(PDO::FETCH_ASSOC);
	
	$password = $row_temp['short_hash'];
	//echo $password;
	//echo '3';
	//MEMBER MESSAGE//////////////////////////////////////////////////
	if($row['telefono'] != ''){
		$client3 = new Services_Twilio($AccountSid, $AuthToken);

		$from = '+19034018888'; 
		$numbersOnly = preg_replace('/[^0-9,]|,[0-9]*$/','',$row['telefono']);
		$to= '+'.$numbersOnly; 
		$body = 'Health2.me account created.  Login: '.$row['Name'].'.'.$row['Surname'].' and Password:' .$password.'.';
		try{
		$client3->account->sms_messages->create($from, $to, $body);
		}catch(Exception $e){
		echo "Twilio could not text message number.  Number is most likely incorrect.".$e;
		}
	}
	//echo '2';
	////////////////////////////MEMBER EMAIL/////////////////
	if($row['email'] != ''){
		 $Content = file_get_contents('templates/SendPaymentNotification.html');
			 
		 $Var1 = 'Health2Me account created.  <br/><br/>Login: <b>'.$row['Name'].'.'.$row['Surname'].'</b> <br/><br/>Password: <b>' .$password.'</b>.<br/><br/> Visit <a href="'.$domain.'" >Health2Me</a> to login now.';
		 
		 //echo $row['email'];
		 
		 //$Var1 = $row2['Name'].' '.$row2['Surname'];
		 $Content = str_replace("**Var1**",$Var1,$Content);
		 //$Content = str_replace("**Var2**",$row['time_diff'],$Content);
		 $Content = str_replace("**Var10**",$hardcode,$Content);
		 
		 $body = $Content;

		 $subject = 'Your account has been created.';//.$userName.' '.$userSurname;

		 $transporter3 = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
		  ->setUsername('dev.health2me@gmail.com')
		  ->setPassword('health2me');

		 $mailer3 = Swift_Mailer::newInstance($transporter3);

		 $message3 = Swift_Message::newInstance()
		  ->setSubject($subject)
		  ->setFrom(array('hub@inmers.us' => 'Health2Me Account Credentials'))
		  ->setTo(array($row['email']))
		  ->setBody($body, 'text/html')
		  ;

		 $result = $mailer3->send($message3);
		}
}
	

///////////////////////////////////////////////////////////////////////////////////////////////////////

function logRequest($probeID,$method)
{
//set_include_path('/var/www/vhost1/');
	require(".../var/www/vhost1/environment_detail.php");
	
 
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

	$query = $con->prepare("insert INTO sentprobelog(probeID,method,requestTime) values(?,?,now())");
	$query->bindValue(1, $probeID, PDO::PARAM_INT);
	$query->bindValue(2, $method, PDO::PARAM_INT);
	$query->execute();
	
	//mysql_query($query);
	
}	



?>

