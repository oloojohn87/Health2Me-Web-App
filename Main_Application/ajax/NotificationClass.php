<?php
error_reporting(-1);
//ini_set('display_errors', 'On');

class Notifications{

    private $con;
    
    function Notifications() {
        
        require("environment_detailForLogin.php");
        $dbhost = $env_var_db['dbhost'];
        $dbname = $env_var_db['dbname'];
        $dbuser = $env_var_db['dbuser'];
        $dbpass = $env_var_db['dbpass'];
        
        
        
        $this->con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                                 PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        if (!$this->con)
        {
            die('Could not connect: ' . mysql_error());
        }
        
        $this->dbhost = $dbhost;
        
    }
    
    
    
    public function add($type, $sender, $is_sender_doctor, $receiver, $is_receiver_doctor, $auxilary){
        
        if (!$this->con)
        {
            die('Could not connect: ' . mysql_error());
        }
        
        $sender_flag = 0;
        $receiver_flag = 0;
        
        if($is_sender_doctor)
            $sender_flag = 1;
        else
            $sender_flag = 0;

        if($is_receiver_doctor)
            $receiver_flag = 1;
        else
            $receiver_flag = 0;
        
        $extra = '';
        if($auxilary != NULL)
            $extra = ', auxilary = ?';

        
        $typeList = array('NEWREF' => 'ref', 'REFCNG' => 'ref', 'NEWMES' => 'msg', 'REPUPL' => 'rep', 'SUMEDT' => 'sum', 'NEWPRB' => 'prb', 'PRBALR' => 'prb', 'NEWAPP' => 'apt', 'APPCNL' => 'apt', 'REVREQ' => 'req', 'SNDREQ' => 'req', 'APPUPD' => 'upd');
        $catCompare = $typeList[$type];
        
        //GET AN ARRAY OF TASKS OF NOTIFICATION
        $notifMethods = $this->NotificationCenter($receiver, $catCompare);
        
        $message = '';
        if($type == 'NEWREF')
        {
            $message = $this->generateMessageForNewReferral($sender, $auxilary, $notifMethods);
        }
        else if($type == 'REFCNG')
        {
            $message = $this->generateMessageForReferralChange($sender, $auxilary, $notifMethods);
        }
        else if($type == 'NEWMES')
        {
            $message = $this->generateMessageForNewMessage($sender, $sender_flag, $auxilary, $notifMethods);
        }
        else if($type == 'REPUPL')
        {
            $message = $this->generateMessageForReportUpload($sender, $sender_flag, $auxilary, $notifMethods);
        }
        else if($type == 'SUMEDT')
        {
            $message = $this->generateMessageForSummaryEdit($sender, $sender_flag, $receiver_flag, $auxilary, $notifMethods);
        }
        else if($type == 'NEWPRB')
        {
            $message = $this->generateMessageForNewProbe($sender, $notifMethods);
        }
        else if($type == 'PRBALR')
        {
            $message = $this->generateMessageForProbeAlert($sender, 1, $notifMethods);
        }
        else if($type == 'NEWAPP')
        {
            $message = $this->generateMessageForNewAppointment($sender, $sender_flag, $notifMethods);
        }
        else if($type == 'APPCNL')
        {
            $message = $this->generateMessageForAppointmentCanceled($sender, $sender_flag, $notifMethods);
        }
        else if($type == 'REVREQ')
        {
            $message = $this->generateMessageForReviewRequest($sender, $sender_flag, $notifMethods);
        }
        else if($type == 'SNDREQ')
        {
            $message = $this->generateMessageForSendRequest($sender, $sender_flag, $notifMethods);
        }
        else if ($type == 'APPUPD')
        {
			$message = $this->generateMessageForNewAppointmentUpdate($sender, $sender_flag, $receiver, $auxilary, $notifMethods);
		}
        else if ($type == 'MEDALR')
        {
			$message = $this->generateMessageForMedicationAlert($receiver, $auxilary);
		}
        
        $add = $this->con->prepare("INSERT INTO notifications SET created = NOW(), type = ?, sender = ?, receiver = ?, is_sender_doctor = ?, is_receiver_doctor = ?, message = ?".$extra);
        $add->bindValue(1, $type, PDO::PARAM_INT);
        $add->bindValue(2, $sender, PDO::PARAM_INT);
        $add->bindValue(3, $receiver, PDO::PARAM_INT);
        $add->bindValue(4, $sender_flag, PDO::PARAM_INT);
        $add->bindValue(5, $receiver_flag, PDO::PARAM_INT);
        $add->bindValue(6, $message, PDO::PARAM_STR);
        if(strlen($extra) > 0)
        {
            $add->bindValue(7, $auxilary, PDO::PARAM_INT);
        }
        $add->execute();
    }
    
    //SEARCHES IF THE RECEIVER(DOCTOR)'S NOTIFICATION SETTING IS ON
    private function NotificationCenter($receiver, $catCompare) {    
        $search = $this->con->prepare("SELECT methods FROM notification_docSetting WHERE idMed = ? AND category = ?");
        $search->bindValue(1, $receiver, PDO::PARAM_INT);
        $search->bindValue(2, $catCompare, PDO::PARAM_STR);
        $search->execute();
        $result = array();
        
        while($row = $search->fetch(PDO::FETCH_ASSOC)) {
            if(strpos($row['methods'], 'Txt') !== false) $result['Text'] = true;  
            if(strpos($row['methods'], 'Email') !== false) $result['Email'] = true;
        }
        
        $doc = $this->con->prepare("SELECT id, Name, Surname, phone, IdMEDEmail, IdMEDFIXEDNAME FROM doctors WHERE id = ?");
        $doc->bindValue(1, $receiver, PDO::PARAM_INT);
        $doc->execute();
        $row = $doc->fetch(PDO::FETCH_ASSOC);
        
        $result['receiverInfo'] = $row;
         
        return $result;
    }      

    private function generateMessageForNewReferral($sender, $auxilary, $is_sender_doctor, $notifMethods)
    {
        $doc = $this->con->prepare("SELECT CONCAT(name, ' ', surname) fullname FROM doctors WHERE id = ?");
        $doc->bindValue(1, $sender, PDO::PARAM_INT);
        $doc->execute();
        $doc_row = $doc->fetch(PDO::FETCH_ASSOC);

        $pat = $this->con->prepare("SELECT CONCAT(Name, ' ', Surname) fullname FROM usuarios WHERE Identif = ?");
        $pat->bindValue(1, $auxilary, PDO::PARAM_INT);
        $pat->execute();
        $pat_row = $pat->fetch(PDO::FETCH_ASSOC);
        
        $senderFullName = $doc_row['fullname'];
        $patientFullName = $pat_row['fullname'];
        
        //SEND TEXT IF SET
        if(isset($notifMethods['Text'])) {
            $this->sendText($senderFullName, $patientFullName, $notifMethods['receiverInfo'], $is_sender_doctor, 'newRef');
        }
        //SEND EMAIL IF SET
        if(isset($notifMethods['Email'])) { 
            $this->sendEmail($senderFullName, $patientFullName, $notifMethods['receiverInfo'], $is_sender_doctor, 'newRef');
        }       

        return $pat_row['fullname'].' has been referred to you by Dr. '.$doc_row['fullname'];
    }
    
    private function generateMessageForReferralChange($sender, $auxilary, $is_sender_doctor, $notifMethods)
    {
        $doc = $this->con->prepare("SELECT CONCAT(name, ' ', surname) fullname FROM doctors WHERE id = ?");
        $doc->bindValue(1, $sender, PDO::PARAM_INT);
        $doc->execute();
        $doc_row = $doc->fetch(PDO::FETCH_ASSOC);

        $pat = $this->con->prepare("SELECT CONCAT(name, ' ', surname) fullname FROM usuarios WHERE Identif = ?");
        $pat->bindValue(1, $auxilary, PDO::PARAM_INT);
        $pat->execute();
        $pat_row = $pat->fetch(PDO::FETCH_ASSOC);
        
        $senderFullName = $doc_row['fullname'];
        $patientFullName = $pat_row['fullname'];
        
        //SEND TEXT IF SET
        if(isset($notifMethods['Text'])) {
            $this->sendText($senderFullName, $patientFullName, $notifMethods['receiverInfo'], $is_sender_doctor, 'chngRef');
        }
        //SEND EMAIL IF SET
        if(isset($notifMethods['Email'])) { 
            $this->sendEmail($senderFullName, $patientFullName, $notifMethods['receiverInfo'], $is_sender_doctor, 'chngRef');
        }

        return 'Your referral of patient '.$pat_row['fullname'].' to Dr. '.$doc_row['fullname'].' has changed';
    }
    
    private function generateMessageForNewMessage($sender, $is_sender_doctor, $auxilary, $notifMethods)
    {
        $user_row = null;
        if($is_sender_doctor == 1)
        {
            $doc = $this->con->prepare("SELECT CONCAT('Dr. ', name, ' ', surname) fullname FROM doctors WHERE id = ?");
            $doc->bindValue(1, $sender, PDO::PARAM_INT);
            $doc->execute();
            $user_row = $doc->fetch(PDO::FETCH_ASSOC);
            
            if($auxilary != null) {
                $pat = $this->con->prepare("SELECT CONCAT(name, ' ', surname) fullname, name, surname, email AS email FROM usuarios WHERE Identif = ?");
                $pat->bindValue(1, $auxilary, PDO::PARAM_INT);
                $pat->execute();
                $pat_row = $pat->fetch(PDO::FETCH_ASSOC);
                $patientFullName =  $pat_row['fullname'];
            }
        }
        else
        {
            $pat = $this->con->prepare("SELECT CONCAT(name, ' ', surname) fullname FROM usuarios WHERE Identif = ?");
            $pat->bindValue(1, $sender, PDO::PARAM_INT);
            $pat->execute();
            $user_row = $pat->fetch(PDO::FETCH_ASSOC);
        }
        
        $senderFullName = $user_row['fullname'];
        
        //SEND TEXT IF SET
        if(isset($notifMethods['Text'])) {
            $this->sendText($senderFullName, '', $notifMethods['receiverInfo'], $is_sender_doctor, 'msg');
        }
        //SEND EMAIL IF SET
        if(isset($notifMethods['Email'])) { 
            $this->sendEmail($senderFullName, '', $notifMethods['receiverInfo'], $is_sender_doctor, 'msg');
        }
            
        return 'New message from '.$senderFullName;
    }
    
    private function generateMessageForReportUpload($sender, $is_sender_doctor, $auxilary, $notifMethods)
    {
        $user_row = null;
        if($is_sender_doctor == 1)
        {
            $doc = $this->con->prepare("SELECT CONCAT('Dr. ', name, ' ', surname) fullname, name, surname, IdMEDEmail AS email FROM doctors WHERE id = ?");
            $doc->bindValue(1, $sender, PDO::PARAM_INT);
            $doc->execute();
            $user_row = $doc->fetch(PDO::FETCH_ASSOC);
            
            if($auxilary != null) {
                $pat = $this->con->prepare("SELECT CONCAT(name, ' ', surname) fullname, name, surname, email AS email FROM usuarios WHERE Identif = ?");
                $pat->bindValue(1, $auxilary, PDO::PARAM_INT);
                $pat->execute();
                $pat_row = $pat->fetch(PDO::FETCH_ASSOC);
                $patientFullName = $pat_row['fullname'];
            }
        }
        else
        {
            $pat = $this->con->prepare("SELECT CONCAT(name, ' ', surname) fullname, name, surname, email AS email FROM usuarios WHERE Identif = ?");
            $pat->bindValue(1, $sender, PDO::PARAM_INT);
            $pat->execute();
            $user_row = $pat->fetch(PDO::FETCH_ASSOC);
        }
        
        $senderFullName = $user_row['fullname'];
        if($user_row['name'] == null || $user_row['surname'] == null)
            $senderFullName = $user_row['email'];
        
        //SEND TEXT IF SET
        if(isset($notifMethods['Text'])) {
            $this->sendText($senderFullName, $patientFullName, $notifMethods['receiverInfo'], $is_sender_doctor, 'upRep');
        }
        //SEND EMAIL IF SET
        if(isset($notifMethods['Email'])) { 
            $this->sendEmail($senderFullName, $patientFullName, $notifMethods['receiverInfo'], $is_sender_doctor, 'upRep');
        }
        return $senderFullName.' has uploaded a new report';
    }
    
    private function generateMessageForSummaryEdit($sender, $is_sender_doctor, $is_receiver_doctor, $auxilary, $notifMethods)
    {
        $user_row = null;
        $text = '';
        if($is_sender_doctor == 1)
        {
            $doc = $this->con->prepare("SELECT CONCAT('Dr. ', name, ' ', surname) fullname FROM doctors WHERE id = ?");
            $doc->bindValue(1, $sender, PDO::PARAM_INT);
            $doc->execute();
            $user_row = $doc->fetch(PDO::FETCH_ASSOC);
            
            if ($is_receiver_doctor == 0) $text = 'your';
            else {
                $pat = $this->con->prepare("SELECT CONCAT(name, ' ', surname) fullname, name, surname, email AS email FROM usuarios WHERE Identif = ?");
                $pat->bindValue(1, $sender, PDO::PARAM_INT);
                $pat->execute();
                $pat_row = $pat->fetch(PDO::FETCH_ASSOC);
                $patientFullName = $pat_row['fullname'];
                $text = 'patient '.$patientFullName.'\'s';
            }
        }
        else
        {
            $pat = $this->con->prepare("SELECT CONCAT(name, ' ', surname) fullname,Sexo FROM usuarios WHERE Identif = ?");
            $pat->bindValue(1, $sender, PDO::PARAM_INT);
            $pat->execute();
            $user_row = $pat->fetch(PDO::FETCH_ASSOC);

            if($user_row['Sexo'] == 1)
                $text = 'his';
            else
                $text = 'her';
        }
        
        $senderFullName = $user_row['fullname'];
        
        //SEND TEXT IF SET
        if(isset($notifMethods['Text'])) {
            $this->sendText($senderFullName, $patientFullName, $notifMethods['receiverInfo'], $is_sender_doctor, 'chngSum');
        }
        //SEND EMAIL IF SET
        if(isset($notifMethods['Email'])) { 
            $this->sendEmail($senderFullName, $patientFullName, $notifMethods['receiverInfo'], $is_sender_doctor, 'chngSum');
        }
        
        return $user_row['fullname'].' has edited '.$text.' summary';
    }
    
    private function generateMessageForNewProbe($sender, $is_sender_doctor, $notifMethods)
    {
        $doc = $this->con->prepare("SELECT CONCAT('Dr. ', name, ' ', surname) fullname FROM doctors WHERE id = ?");
        $doc->bindValue(1, $sender, PDO::PARAM_INT);
        $doc->execute();
        $doc_row = $doc->fetch(PDO::FETCH_ASSOC);
        
        $senderFullName = $doc_row['fullname'];

        //SEND TEXT IF SET
        if(isset($notifMethods['Text'])) {
            $this->sendText($senderFullName, '', $notifMethods['receiverInfo'], $is_sender_doctor, 'newPrb');
        }
        //SEND EMAIL IF SET
        if(isset($notifMethods['Email'])) { 
            $this->sendEmail($senderFullName, '', $notifMethods['receiverInfo'], $is_sender_doctor, 'newPrb');
        }

        return $doc_row['fullname'].' has created a new probe for you';
    }
    
    private function generateMessageForProbeAlert($sender, $is_sender_doctor, $notifMethods)
    {
        $pat = $this->con->prepare("SELECT CONCAT(name, ' ', surname) fullname FROM usuarios WHERE Identif = ?");
        $pat->bindValue(1, $sender, PDO::PARAM_INT);
        $pat->execute();
        $pat_row = $pat->fetch(PDO::FETCH_ASSOC);
        
        $senderFullName = $pat_row['fullname'];

        //SEND TEXT IF SET
        if(isset($notifMethods['Text'])) {
            $this->sendText($senderFullName, '', $notifMethods['receiverInfo'], $is_sender_doctor, 'alrtPrb');
        }
        //SEND EMAIL IF SET
        if(isset($notifMethods['Email'])) { 
            $this->sendEmail($senderFullName, '', $notifMethods['receiverInfo'], $is_sender_doctor, 'alrtPrb');
        }

        return 'Probe alert for '.$pat_row['fullname'];
    }
    
    private function generateMessageForNewAppointment($sender, $is_sender_doctor, $notifMethods)
    {
        $user_row = null;
        if($is_sender_doctor == 1)
        {
            $doc = $this->con->prepare("SELECT CONCAT('Dr. ', name, ' ', surname) fullname FROM doctors WHERE id = ?");
            $doc->bindValue(1, $sender, PDO::PARAM_INT);
            $doc->execute();
            $user_row = $doc->fetch(PDO::FETCH_ASSOC);
        }
        else
        {
            $pat = $this->con->prepare("SELECT CONCAT(name, ' ', surname) fullname FROM usuarios WHERE Identif = ?");
            $pat->bindValue(1, $sender, PDO::PARAM_INT);
            $pat->execute();
            $user_row = $pat->fetch(PDO::FETCH_ASSOC);
        }
        
        $senderFullName = $user_row['fullname'];
        
        //SEND TEXT IF SET
        if(isset($notifMethods['Text'])) {
            $this->sendText($senderFullName, '', $notifMethods['receiverInfo'], $is_sender_doctor, 'newApt');
        }
        //SEND EMAIL IF SET
        if(isset($notifMethods['Email'])) { 
            $this->sendEmail($senderFullName, '', $notifMethods['receiverInfo'], $is_sender_doctor, 'newApt');
        }
        
        return $user_row['fullname'].' has set an appointment with you';
    }
    
    private function generateMessageForNewAppointmentUpdate($sender, $is_sender_doctor, $receiver, $auxilary, $notifMethods)
    {
        $user_row = null;
        if($is_sender_doctor == 1)
        {
            $doc = $this->con->prepare("SELECT CONCAT('Dr. ', name, ' ', surname) fullname, type FROM doctors WHERE id = ?");
            $doc->bindValue(1, $sender, PDO::PARAM_INT);
            $doc->execute();
            $user_row = $doc->fetch(PDO::FETCH_ASSOC);
            
            $pat = $this->con->prepare("SELECT CONCAT(name, ' ', surname) fullname, email, telefono FROM usuarios WHERE Identif = ?");
            $pat->bindValue(1, $receiver, PDO::PARAM_INT);
            $pat->execute();
            $pat_row = $pat->fetch(PDO::FETCH_ASSOC);
            
            $type = $user_row['type'];
        }
        else
        {
            $pat = $this->con->prepare("SELECT CONCAT(name, ' ', surname) fullname FROM usuarios WHERE Identif = ?");
            $pat->bindValue(1, $sender, PDO::PARAM_INT);
            $pat->execute();
            $user_row = $pat->fetch(PDO::FETCH_ASSOC);
        }
        if(strpos($type, 'CATA') !== false) {
            require_once '../../lib/swift_required.php';
            $to = $pat_row['email'];
            $Var4 = $user_row['fullname'];
            $dateTime = explode(" ", $auxilary);
            $specific_date = date('F j, Y', strtotime($dateTime[0]));
            $Var5 = $specific_date;
            $specific_timeArray = explode(':', $dateTime[1]);
            $specific_hour = $specific_timeArray[0];
            $specific_hour = ($specific_hour > 12 ? $specific_hour - 12 : $specific_hour);
            $specific_minute = $specific_timeArray[1];
            $specific_meridiem = ($specific_timeArray[0] >= 12 ? "PM" : "AM");
            $specific_time = $specific_hour.':'.$specific_minute.' '.$specific_meridiem;
            
            
            $Var10 = 'http://'.strval($this->dbhost);
            $Content = null;
            
            $from = 'Catapult Health';
            $Var6 = $specific_time;
            $Content = file_get_contents('../../templates/AppointmentMessageupdatedCatapult.html');
            $Content = str_replace("**Var4**",$Var4,$Content);
            $Content = str_replace("**Var5**",$Var5,$Content);
            $Content = str_replace("**Var6**",$Var6,$Content);
            $Content = str_replace("**Var10**",$Var10,$Content);
            
            if($pat_row['telefono'] != null)
            {
                require_once "../../Services/Twilio.php";		     
                $AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
                $AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";

                // Instantiate a new Twilio Rest Client
                $client = new Services_Twilio($AccountSid, $AuthToken);

                $text_from = '+19034018888'; 
                $text_to= '+'.$pat_row['telefono']; 

                //SMS BODY
                $text_body = 'An appointment has been updated for you with NP '.$Var4.' on '.$Var5.' at '.$Var6;

                //TRY SENDING MESSAGE
                try{
                    $client->account->sms_messages->create($text_from, $text_to, $text_body);
                }catch(Exception $e){
                    echo "Twilio could not text message number.  Number is most likely incorrect.".$e;
                }
            }
            
            if(strlen($to) > 0)
            {
                $body = $Content;


                $subject = 'Appointment Has Been Updated';

                $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
                ->setUsername('dev.health2me@gmail.com')
                ->setPassword('health2me');

                $mailer = Swift_Mailer::newInstance($transporter);


                $message = Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom(array('hub@inmers.us' => $from))
                ->setTo(array($to))
                ->setBody($body, 'text/html');

                $result = $mailer->send($message);
            }
        }
        $senderFullName = $user_row['fullname'];
        
        return $user_row['fullname'].' has set an exact appointment time for you: '.$auxilary;
    }
    
    private function generateMessageForAppointmentCanceled($sender, $is_sender_doctor, $notifMethods)
    {
        $user_row = null;
        if($is_sender_doctor == 1)
        {
            $doc = $this->con->prepare("SELECT CONCAT('Dr. ', name, ' ', surname) fullname FROM doctors WHERE id = ?");
            $doc->bindValue(1, $sender, PDO::PARAM_INT);
            $doc->execute();
            $user_row = $doc->fetch(PDO::FETCH_ASSOC);
        }
        else
        {
            $pat = $this->con->prepare("SELECT CONCAT(name, ' ', surname) fullname FROM usuarios WHERE Identif = ?");
            $pat->bindValue(1, $sender, PDO::PARAM_INT);
            $pat->execute();
            $user_row = $pat->fetch(PDO::FETCH_ASSOC);
        }
        
        $senderFullName = $user_row['fullname'];
        
        //SEND TEXT IF SET
        if(isset($notifMethods['Text'])) {
            $this->sendText($senderFullName, '', $notifMethods['receiverInfo'], $is_sender_doctor, 'cnlApt');
        }
        //SEND EMAIL IF SET
        if(isset($notifMethods['Email'])) { 
            $this->sendEmail($senderFullName, '', $notifMethods['receiverInfo'], $is_sender_doctor, 'cnlApt');
        }    

        return $user_row['fullname'].' has canceled an appointment with you';
    }
    
    private function generateMessageForReviewRequest($sender, $is_sender_doctor, $notifMethods)
    {
        $pat = $this->con->prepare("SELECT CONCAT(name, ' ', surname) fullname FROM usuarios WHERE Identif = ?");
        $pat->bindValue(1, $sender, PDO::PARAM_INT);
        $pat->execute();
        $pat_row = $pat->fetch(PDO::FETCH_ASSOC);

        $senderFullName = $pat_row['fullname'];
        
        //SEND TEXT IF SET
        if(isset($notifMethods['Text'])) {
            $this->sendText($senderFullName, '', $notifMethods['receiverInfo'], $is_sender_doctor, 'revReq');
        }
        //SEND EMAIL IF SET
        if(isset($notifMethods['Email'])) { 
            $this->sendEmail($senderFullName, '', $notifMethods['receiverInfo'], $is_sender_doctor, 'revReq');
        }

        return '<a href="patientdetailMED-new.php?IdUsu='.$sender.'">'.$pat_row['fullname'].' has sent you some records to review</a>';
    }
    
    private function generateMessageForSendRequest($sender, $is_sender_doctor, $notifMethods)
    {
        $pat = $this->con->prepare("SELECT CONCAT(name, ' ', surname) fullname FROM usuarios WHERE Identif = ?");
        $pat->bindValue(1, $sender, PDO::PARAM_INT);
        $pat->execute();
        $pat_row = $pat->fetch(PDO::FETCH_ASSOC);

        $senderFullName = $pat_row['fullname'];
        
         //SEND TEXT IF SET
        if(isset($notifMethods['Text'])) {
            $this->sendText($senderFullName, '', $notifMethods['receiverInfo'], $is_sender_doctor, 'sndReq');
        }
        //SEND EMAIL IF SET
        if(isset($notifMethods['Email'])) { 
            $this->sendEmail($senderFullName, '', $notifMethods['receiverInfo'], $is_sender_doctor, 'sndReq');
        }

        return $pat_row['fullname'].' is requesting some records from you';
    } 
    private function generateMessageForMedicationAlert($receiver, $auxilary)
    {
        $med = $this->con->prepare("SELECT name FROM medication_reminders WHERE id = ?");
        $med->bindValue(1, $auxilary, PDO::PARAM_INT);
        $med->execute();
        $med_row = $pat->fetch(PDO::FETCH_ASSOC);

        $med_name = $med_row['name'];

        return 'You have forgotten to take the medication '.$med_name;
    }
    
    //SENDING A TEXT
    private function sendText($senderFullName, $patientFullName, $receiverInfo, $is_sender_doctor, $NotifType) {
        require_once "../../Services/Twilio.php";

        $AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
        $AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
        // Instantiate a new Twilio Rest Client
        $client = new Services_Twilio($AccountSid, $AuthToken);
        /* Your Twilio Number or Outgoing Caller ID */
        $from = '+19034018888'; 
        $to= '+'.$receiverInfo['phone']; 
        $body = '';
        switch($NotifType) {
            case 'msg':
                if ($is_sender_doctor == 0) $body = 'Health2Me.- New Encrypted Message From Patient '.$senderFullName.'. Access your account here: https://www.health2.me';
                else $body = 'Health2Me.- New Encrypted Message From '.$senderFullName.'. Access your account here: https://www.health2.me';
            break;
            
            case 'newRef': $body = 'Health2Me.- '.$senderFullName.' has referred a patient '.$patientFullName.' to you. Access your account here https://www.health2.me';
            break;
            
            case 'chngRef': $body = 'Health2Me.- '.$senderFullName.' has changed referral stage for patient '.$patientFullName.'. Access your account here https://www.health2.me';
            break;
            
            case 'upRep': 
                if ($is_sender_doctor == 0) $body = 'Health2Me.- Patient '.$senderFullName.' has uploaded a report. Access your account here https://www.health2.me';
                else $body = 'Health2Me.- '.$senderFullName.' has uploaded a report for patient '.$patientFullName.'. Access your account here https://www.health2.me';
            break;
            
            case 'chngSum': 
                if ($is_sender_doctor == 0) $body = 'Health2Me.- Patient '.$senderFullName.' has updated his/her summary. Access your account here https://www.health2.me';
                else $body = 'Health2Me.- '.$senderFullName.' has uploaded a report for patient '.$patientFullName.'. Access your account here https://www.health2.me';
            break;
            
            case 'newPrb': $body = 'Health2Me.- A new probe has been generated for a patient. Access your account here https://www.health2.me';
            break;
            
            case 'alrtPrb': $body = 'Health2Me.- A probe alert has been issued for a patient: '.$senderFullName.'. Access your account here https://www.health2.me';
            break; 
        
            case 'newApt':
                if ($is_sender_doctor == 0) $body = 'Health2Me.- Patient '.$senderFullName.' has made a new appointment. Access your account here https://www.health2.me';
                else $body = 'Health2Me.- '.$senderFullName.' has made a new appointment. Access your account here https://www.health2.me ';
            break;
            
            case 'cnlApt':
                if ($is_sender_doctor == 0) $body = 'Health2Me.- Patient '.$senderFullName.' has canceled an appointment. Access your account here https://www.health2.me';
                else $body = 'Health2Me.- '.$senderFullName.' has canceled an appointment. Access your account here https://www.health2.me ';
            break;
        
            case 'sndReq': $body = 'Health2Me.- A send request has been issued from a patient: '.$senderFullName.' Access your account here https://www.health2.me';
            break;
            
            case 'revReq': $body = 'Health2Me.- A review request has been issued from a patient: '.$senderFullName.'. Access your account here https://www.health2.me';
            break;
        }
        $client->account->sms_messages->create($from, $to, $body); 
    }
    
    
    
    //SENDING AN EMAIL
    private function sendEmail($senderFullName, $patientFullName, $receiverInfo, $is_sender_doctor, $NotifType) {
        
        $row = $receiverInfo;
        
        $ToEmail= $row['IdMEDEmail'];
        $Todoctorname=$row['Name'];
        $TodoctorSurname=$row['Surname'];
        $loginname=$row['IdMEDFIXEDNAME'];
        $domain = 'http://'.strval($this->dbhost);
        $IdUsu = $row['id'];
     ###Send notification to the doctor who had requested for unlocking the report####

        require_once '../../lib/swift_required.php';

        $Sobre="Health2me Notification";
        switch ($NotifType)
        {
            case 'msg':	$MsgColor='#FFC23B'; 
                        //$ButColor='grey';                
                        $Sobre='New encrypted message';
                        if ($is_sender_doctor == 0)
                        {
                            $MsgText = 'New encrypted message from Patient '.$senderFullName;
                            $msg= '<p>Dear Dr. '.$Todoctorname.' '.$TodoctorSurname.',</p><p> You have a message from Patient: '.$senderFullName.'</p>
                            <p>Please click <b><u><a href="'.$domain.'/SignIn_Ref.php?userlogin='.$loginname.'&idp='.$IdUsu.'">here</a></u></b> to login and see the message in your Inbox!</p> <p>Thank You,</p>';
                         }
                        else
                        {
                            $MsgText = 'New encrypted message from '.$senderFullName;
                            $msg= '<p>Dear Dr. '.$Todoctorname.' '.$TodoctorSurname.',</p><p> You have a message from '.$senderFullName.'</p>
                            <p>Please click <b><u><a href="'.$domain.'/SignIn_Ref.php?userlogin='.$loginname.'&idp='.$IdUsu.'">here</a></u></b> to login and see the message in your Inbox!</p> <p>Thank You,</p>';
                        }
                        break;
            
            case 'newRef': $MsgColor='green'; 
                        //$ButColor='#22aeff'; 
                        $MsgText = $senderFullName.' referred a patient '.$patientFullName.' for you';
                        $Sobre='New Referral: '.$patientFullName;         

                        $msg= '<p>Dear Dr. '.$Todoctorname.' '.$TodoctorSurname.',</p><p> '.$senderFullName.' referred a patient: '.$patientFullName.'</p>
                            <p>Please click <b><u><a href="'.$domain.'/SignIn_Ref.php?userlogin='.$loginname.'&idp='.$IdUsu.'">here</a></u></b> to login and see the message in your Inbox!</p> <p>Thank You,</p>';

                        break;
            
            case 'chngRef': $MsgColor='#357800'; 
                        //$ButColor='#22aeff'; 
                        $MsgText = $senderFullName.' changed referral stage for patient '.$patientFullName;
                        $Sobre='Changed referral stage for patient '.$patientFullName;
                        $msg= '<p>Dear Dr. '.$Todoctorname.' '.$TodoctorSurname.',</p><p> '.$senderFullName.' changed referral stage for patient: '.$patientFullName.'</p>
                            <p>Please click <b><u><a href="'.$domain.'/SignIn_Ref.php?userlogin='.$loginname.'&idp='.$IdUsu.'">here</a></u></b> to login and see the message in your Inbox!</p> <p>Thank You,</p>';

                        break;
            
            case 'upRep': $MsgColor='#22AEFF'; 
                        //$ButColor='#22aeff';   
                        $Sobre=$senderFullName.' has uploaded a report';
                        if ($is_sender_doctor == 0)
                        {
                            $MsgText = 'Patient '.$senderFullName.' has uploaded a report';
                            $msg= '<p>Dear Dr. '.$Todoctorname.' '.$TodoctorSurname.',</p><p> '.$senderFullName.' has uploaded a report.</p>
                            <p>Please click <b><u><a href="'.$domain.'/SignIn_Ref.php?userlogin='.$loginname.'&idp='.$IdUsu.'">here</a></u></b> to login and see the message in your Inbox!</p> <p>Thank You,</p>';
                         }
                        else
                        {
                            $MsgText = $senderFullName.' has uploaded a report for patient '.$patientFullName.'.';
                            $msg= '<p>Dear Dr. '.$Todoctorname.' '.$TodoctorSurname.',</p><p>'.$senderFullName.' has uploaded a report for patient '.$patientFullName.'.</p>
                            <p>Please click <b><u><a href="'.$domain.'/SignIn_Ref.php?userlogin='.$loginname.'&idp='.$IdUsu.'">here</a></u></b> to login and see the message in your Inbox!</p> <p>Thank You,</p>';
                        }
                        break;
            
            case 'chngSum': $MsgColor='#6B02C0'; 
                        //$ButColor='#22aeff'; 
                        
                        $Sobre=$senderFullName.' has updated summary';
                        if ($is_sender_doctor == 0)
                        {
                            $MsgText = 'Patient '.$senderFullName.' has updated summary';
                            $msg= '<p>Dear Dr. '.$Todoctorname.' '.$TodoctorSurname.',</p><p> '.$senderFullName.' has updated a summary.</p>
                            <p>Please click <b><u><a href="'.$domain.'/SignIn_Ref.php?userlogin='.$loginname.'&idp='.$IdUsu.'">here</a></u></b> to login and see the message in your Inbox!</p> <p>Thank You,</p>';
                         }
                        else
                        {
                            $MsgText = $senderFullName.' has updated summary of '.$patientFullName;
                            $msg= '<p>Dear Dr. '.$Todoctorname.' '.$TodoctorSurname.',</p><p>'.$senderFullName.'has updated a summary for patient '.$patientFullName.'.</p>
                            <p>Please click <b><u><a href="'.$domain.'/SignIn_Ref.php?userlogin='.$loginname.'&idp='.$IdUsu.'">here</a></u></b> to login and see the message in your Inbox!</p> <p>Thank You,</p>';
                        }
                        break;
            
            case 'newPrb': $MsgColor='magenta'; 
                        //$ButColor='#22aeff'; 
                        $MsgText = 'A new probe has been generated for '.$senderFullName;
                        $Sobre='New Probe';         

                        $msg= '<p>Dear Dr. '.$Todoctorname.' '.$TodoctorSurname.',</p><p> A new probe has been generated for patient '.$senderFullName.'.</p>
                            <p>Please click <b><u><a href="'.$domain.'/SignIn_Ref.php?userlogin='.$loginname.'&idp='.$IdUsu.'">here</a></u></b> to login and see the message in your Inbox!</p> <p>Thank You,</p>';

                        break;
            
            case 'alrtPrb': $MsgColor='#89150B'; 
                        //$ButColor='#22aeff'; 
                        $MsgText = 'Probe alert issued for '.$senderFullName;
                        $Sobre='Probe alert has been issued for patient '.$senderFullName;
                        $msg= '<p>Dear Dr. '.$Todoctorname.' '.$TodoctorSurname.',</p><p> A probe alert has been issued for patient '.$senderFullName.'</p>
                            <p>Please click <b><u><a href="'.$domain.'/SignIn_Ref.php?userlogin='.$loginname.'&idp='.$IdUsu.'">here</a></u></b> to login and see the message in your Inbox!</p> <p>Thank You,</p>';

                        break;
            
             /*case 'newApt': $MsgColor='#00C9AB'; 
                        //$ButColor='#22aeff';   
                        $Sobre=$senderFullName.' has made a new appointment';
                        if ($is_sender_doctor == 0)
                        {
                            $MsgText = 'Patient '.$senderFullName.' has made a new appointment';
                            $msg= '<p>Dear Dr. '.$Todoctorname.' '.$TodoctorSurname.',</p><p> '.$senderFullName.' has made a new appointment with you.</p>
                            <p>Please click <b><u><a href="'.$domain.'/SignIn_Ref.php?userlogin='.$loginname.'&idp='.$IdUsu.'">here</a></u></b> to login and see the message in your Inbox!</p> <p>Thank You,</p>';
                         }
                        else
                        {
                            $MsgText = $senderFullName.' has made a new appointment';
                            $msg= '<p>Dear Dr. '.$Todoctorname.' '.$TodoctorSurname.',</p><p>'.$senderFullName.'has made a new appointment with you.</p>
                            <p>Please click <b><u><a href="'.$domain.'/SignIn_Ref.php?userlogin='.$loginname.'&idp='.$IdUsu.'">here</a></u></b> to login and see the message in your Inbox!</p> <p>Thank You,</p>';
                        }
                        break;*/
            
             case 'cnlApt': $MsgColor='#E12313'; 
                        //$ButColor='#22aeff';   
                        $Sobre=$senderFullName.' has canceled an appointment';
                        if ($is_sender_doctor == 0)
                        {
                            $MsgText = 'Patient '.$senderFullName.' has canceled an appointment';
                            $msg= '<p>Dear Dr. '.$Todoctorname.' '.$TodoctorSurname.',</p><p> '.$senderFullName.' has canceled an appointment.</p>
                            <p>Please click <b><u><a href="'.$domain.'/SignIn_Ref.php?userlogin='.$loginname.'&idp='.$IdUsu.'">here</a></u></b> to login and see the message in your Inbox!</p> <p>Thank You,</p>';
                         }
                        else
                        {
                            $MsgText = $senderFullName.' has uploaded a report';
                            $msg= '<p>Dear Dr. '.$Todoctorname.' '.$TodoctorSurname.',</p><p>'.$senderFullName.'has canceled an appointment.</p>
                            <p>Please click <b><u><a href="'.$domain.'/SignIn_Ref.php?userlogin='.$loginname.'&idp='.$IdUsu.'">here</a></u></b> to login and see the message in your Inbox!</p> <p>Thank You,</p>';
                        }
                        break;
            
            case 'revReq': $MsgColor='#555'; 
                        //$ButColor='#22aeff'; 
                        $MsgText = 'A review request has been issued from patient '.$senderFullName;
                        $Sobre='Review Request';         

                        $msg= '<p>Dear Dr. '.$Todoctorname.' '.$TodoctorSurname.',</p><p> A review request has been issued from a patient: '.$senderFullName.'</p>
                            <p>Please click <b><u><a href="'.$domain.'/SignIn_Ref.php?userlogin='.$loginname.'&idp='.$IdUsu.'">here</a></u></b> to login and see the message in your Inbox!</p> <p>Thank You,</p>';

                        break;
            
            case 'sndReq': $MsgColor='black'; 
                        //$ButColor='#22aeff'; 
                        $MsgText = 'A send request has been issued';
                        $Sobre='Send Request';         

                        $msg= '<p>Dear Dr. '.$Todoctorname.' '.$TodoctorSurname.',</p><p> A send request has been issued from a patient: '.$senderFullName.'</p>
                            <p>Please click <b><u><a href="'.$domain.'/SignIn_Ref.php?userlogin='.$loginname.'&idp='.$IdUsu.'">here</a></u></b> to login and see the message in your Inbox!</p> <p>Thank You,</p>';

                        break;
            
            default:
                $MsgColor='#22AEFF';
                $MsgText = 'A message';
                $Sobre = 'A message';
                $msg = 'A message';
                break;
        }
        $ButColor='grey'; 
        


        //$ToEmail=$_GET['email'];

        $aQuien = $ToEmail;

        


        $message2= '***** HIPAA AND STANDARD COMPLIANCE PROCEDURES DISCLAIMER *****  :   ';
        $message2.= ' Text of the Legal, Disclaimer and technical explanations here ... Lore Ipsum';

        $MsgHtml='
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head><title></title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="viewport" content="width=320, target-densitydpi=device-dpi">
<style type="text/css">
/* Mobile-specific Styles */
@media only screen and (max-width: 660px) { 
table[class=w0], td[class=w0] { width: 0 !important; }
table[class=w10], td[class=w10], img[class=w10] { width:10px !important; }
table[class=w15], td[class=w15], img[class=w15] { width:5px !important; }
table[class=w30], td[class=w30], img[class=w30] { width:10px !important; }
table[class=w60], td[class=w60], img[class=w60] { width:10px !important; }
table[class=w125], td[class=w125], img[class=w125] { width:80px !important; }
table[class=w130], td[class=w130], img[class=w130] { width:55px !important; }
table[class=w140], td[class=w140], img[class=w140] { width:90px !important; }
table[class=w160], td[class=w160], img[class=w160] { width:180px !important; }
table[class=w170], td[class=w170], img[class=w170] { width:100px !important; }
table[class=w180], td[class=w180], img[class=w180] { width:80px !important; }
table[class=w195], td[class=w195], img[class=w195] { width:80px !important; }
table[class=w220], td[class=w220], img[class=w220] { width:80px !important; }
table[class=w240], td[class=w240], img[class=w240] { width:180px !important; }
table[class=w255], td[class=w255], img[class=w255] { width:185px !important; }
table[class=w275], td[class=w275], img[class=w275] { width:135px !important; }
table[class=w280], td[class=w280], img[class=w280] { width:135px !important; }
table[class=w300], td[class=w300], img[class=w300] { width:140px !important; }
table[class=w325], td[class=w325], img[class=w325] { width:95px !important; }
table[class=w360], td[class=w360], img[class=w360] { width:140px !important; }
table[class=w410], td[class=w410], img[class=w410] { width:180px !important; }
table[class=w470], td[class=w470], img[class=w470] { width:200px !important; }
table[class=w580], td[class=w580], img[class=w580] { width:280px !important; }
table[class=w640], td[class=w640] { width:300px !important; }
table[class*=hide], td[class*=hide], img[class*=hide], p[class*=hide], span[class*=hide] { display:none !important; }
table[class=h0], td[class=h0] { height: 0 !important; }
p[class=footer-content-left] { text-align: center !important; }
#headline p { font-size: 30px !important; }
.article-content, #left-sidebar{ -webkit-text-size-adjust: 90% !important; -ms-text-size-adjust: 90% !important; }
.header-content, .footer-content-left {-webkit-text-size-adjust: 80% !important; -ms-text-size-adjust: 80% !important;}
img { height: auto; line-height: 100%;}
} 
/* Client-specific Styles */
#outlook a { padding: 0; }	/* Force Outlook to provide a "view in browser" button. */
body { width: 100% !important; }
.ReadMsgBody { width: 100%; }
.ExternalClass { width: 100%; display:block !important; } /* Force Hotmail to display emails at full width */
/* Reset Styles */
/* Add 100px so mobile switch bar doesnt cover street address. */
body { background-color: #dedede; margin: 0; padding: 0; }
img { outline: none; text-decoration: none; display: block;}
br, strong br, b br, em br, i br { line-height:100%; }
h1, h2, h3, h4, h5, h6 { line-height: 100% !important; -webkit-font-smoothing: antialiased; }
h1 a, h2 a, h3 a, h4 a, h5 a, h6 a { color: blue !important; }
h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {	color: red !important; }
/* Preferably not the same color as the normal header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited { color: purple !important; }
/* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */  
table td, table tr { border-collapse: collapse; }
.yshortcuts, .yshortcuts a, .yshortcuts a:link,.yshortcuts a:visited, .yshortcuts a:hover, .yshortcuts a span {
color: black; text-decoration: none !important; border-bottom: none !important; background: none !important;
}	/* Body text color for the New Yahoo.  This example sets the font of Yahoos Shortcuts to black. */
/* This most probably wont work in all email clients. Dont include code blocks in email. */
code {
white-space: normal;
word-break: break-all;
}
#background-table { background-color: #dedede; }
/* Webkit Elements */
#top-bar { border-radius:6px 6px 0px 0px; -moz-border-radius: 6px 6px 0px 0px; -webkit-border-radius:6px 6px 0px 0px; -webkit-font-smoothing: antialiased; background-color: '.$MsgColor.'; color: #ededed; }
#top-bar a { font-weight: bold; color: #ffffff; text-decoration: none;}
#footer { border-radius:0px 0px 6px 6px; -moz-border-radius: 0px 0px 6px 6px; -webkit-border-radius:0px 0px 6px 6px; -webkit-font-smoothing: antialiased; }
/* Fonts and Content */
body, td { font-family: "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif; }
.header-content, .footer-content-left, .footer-content-right { -webkit-text-size-adjust: none; -ms-text-size-adjust: none; }
/* Prevent Webkit and Windows Mobile platforms from changing default font sizes on header and footer. */
.header-content { font-size: 12px; color: #ededed; }
.header-content a { font-weight: bold; color: #ffffff; text-decoration: none; }
#headline p { color: #444444; font-family: "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif; font-size: 36px; text-align: center; margin-top:0px; margin-bottom:30px; }
#headline p a { color: #444444; text-decoration: none; }
.article-title { font-size: 18px; line-height:24px; color: #b0b0b0; font-weight:bold; margin-top:0px; margin-bottom:18px; font-family: "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif; }
.article-title a { color: #b0b0b0; text-decoration: none; }
.article-title.with-meta {margin-bottom: 0;}
.article-meta { font-size: 13px; line-height: 20px; color: #ccc; font-weight: bold; margin-top: 0;}
.article-content { font-size: 13px; line-height: 18px; color: #444444; margin-top: 0px; margin-bottom: 18px; font-family: "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif; }
.article-content a { color: #2f82de; font-weight:bold; text-decoration:none; }
.article-content img { max-width: 100% }
.article-content ol, .article-content ul { margin-top:0px; margin-bottom:18px; margin-left:19px; padding:0; }
.article-content li { font-size: 13px; line-height: 18px; color: #444444; }
.article-content li a { color: #2f82de; text-decoration:underline; }
.article-content p {margin-bottom: 15px;}
.footer-content-left { font-size: 12px; line-height: 15px; color: #ededed; margin-top: 0px; margin-bottom: 15px; }
.footer-content-left a { color: #ffffff; font-weight: bold; text-decoration: none; }
.footer-content-right { font-size: 11px; line-height: 16px; color: #ededed; margin-top: 0px; margin-bottom: 15px; }
.footer-content-right a { color: #ffffff; font-weight: bold; text-decoration: none; }
#footer { background-color: '.$MsgColor.'; color: #ededed; }
#footer a { color: #ffffff; text-decoration: none; font-weight: bold; }
#permission-reminder { white-space: normal; }
#street-address { color: #b0b0b0; white-space: normal; }
</style>
<!--[if gte mso 9]>
<style _tmplitem="120" >
.article-content ol, .article-content ul {
margin: 0 0 0 24px;
padding: 0;
list-style-position: inside;
}
</style>
<![endif]--></head><body><table width="100%" cellpadding="0" cellspacing="0" border="0" id="background-table">
<tbody><tr>
    <td align="center" bgcolor="#dedede">
        <table class="w640" style="margin:0 10px;" width="640" cellpadding="0" cellspacing="0" border="0">
            <tbody><tr><td class="w640" width="640" height="20"></td></tr>

            <tr>
                <td class="w640" width="640">
                    <table id="top-bar" class="w640" width="640" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff">
<tbody><tr>
    <td class="w15" width="15"></td>
    <td class="w325" width="350" valign="middle" align="left">
        <table class="w325" width="350" cellpadding="0" cellspacing="0" border="0">
            <tbody><tr><td class="w325" width="350" height="8"></td></tr>
        </tbody></table>
       <div class="header-content"><webversion>Health2Me Health Social Network</webversion><span class="hide">&nbsp;&nbsp;|&nbsp; <preferences lang="en">Referral Network</preferences>&nbsp;&nbsp;|&nbsp; <unsubscribe></unsubscribe></span></div>
       <table class="w325" width="350" cellpadding="0" cellspacing="0" border="0">
            <tbody><tr><td class="w325" width="350" height="8"></td></tr>
        </tbody></table>
    </td>
    <td class="w30" width="30"></td>
    <td class="w255" width="255" valign="middle" align="right">
        <table class="w255" width="255" cellpadding="0" cellspacing="0" border="0">
            <tbody><tr><td class="w255" width="255" height="8"></td></tr>
        </tbody></table>
        <table cellpadding="0" cellspacing="0" border="0">
<tbody><tr>

    <td valign="middle"><fblike><img src="https://img.createsend1.com/img/templatebuilder/like-glyph.png" border="0" width="8" height="14" alt="Facebook icon"=""></fblike></td>
    <td width="3"></td>
    <td valign="middle"><div class="header-content"><fblike>Like</fblike></div></td>


    <td class="w10" width="10"></td>
    <td valign="middle"><tweet><img src="https://img.createsend1.com/img/templatebuilder/tweet-glyph.png" border="0" width="17" height="13" alt="Twitter icon"=""></tweet></td>
    <td width="3"></td>
    <td valign="middle"><div class="header-content"><tweet>Tweet</tweet></div></td>


    <td class="w10" width="10"></td>
    <td valign="middle"><forwardtoafriend lang="en"><img src="https://img.createsend1.com/img/templatebuilder/forward-glyph.png" border="0" width="19" height="14" alt="Forward icon"=""></forwardtoafriend></td>
    <td width="3"></td>
    <td valign="middle"><div class="header-content"><forwardtoafriend lang="en">Forward</forwardtoafriend></div></td>

</tr>
</tbody></table>
        <table class="w255" width="255" cellpadding="0" cellspacing="0" border="0">
            <tbody><tr><td class="w255" width="255" height="8"></td></tr>
        </tbody></table>
    </td>
    <td class="w15" width="15"></td>
</tr>
</tbody></table>

                </td>
            </tr>
            <tr>
            <td id="header" class="w640" width="640" align="center" bgcolor="#ffffff">

    <img editable="true" width="150" src="'.$domain.'/images/H2Mlogolinear.png"  border="0"  style="display: inline; margin-top:30px;">


</td>
            </tr>

            <tr><td class="w640" width="640" height="30" bgcolor="#ffffff"></td></tr>
            
            <!--tr>
            <td class="w640" width="640" bgcolor="#ffffff">
            <div style="width: 280px; height: 140px; background-color: '.$MsgColor.'; color: #FFF; font-size: 22px; position: relative; text-align: center; margin-right: 1%; float: left;"><div style="width: 20px; height: 20px; padding-top: 8px; color: #FFF; text-align: center; margin: auto;"><i class="icon-calendar-empty" style="margin-top: 10px;"></i></div></div>
            
            </td>
            </tr>
            <tr><td class="w640" width="640" height="30" bgcolor="#ffffff"></td></tr-->
            <tr id="simple-content-row"><td class="w640" width="640" bgcolor="#ffffff">
<table class="w640" width="640" cellpadding="0" cellspacing="0" border="0">
    <tbody><tr>
        <td class="w30" width="30"></td>
        <td class="w580" width="580">
            <repeater>

                <layout label="Text only">
                    <table class="w580" width="580" cellpadding="0" cellspacing="0" border="0">
                        <tbody><tr>
                            <td class="w580" width="580">
                                <p align="center" class="article-title"><singleline label="Title">'.$MsgText.'</singleline></p>
                                <div align="left" class="article-content">
                                    <multiline label="Description"></multiline>
                                </div>
                            </td>
                        </tr>
                        <tr><td class="w580" width="580" height="10"></td></tr>
                    </tbody></table>
                </layout>
                <a href="'.$domain.'/SignIn.html" style="text-decoration: none; " >
                <div style="width:280px; height:40px; line-height: 40px; background-color:#58a1fe; color:white; border: 1 #cacaca; margin:0 auto; text-align:center; margin-bottom:20px;">
                   <p style="margin:0 auto; font-size:16px; background-color:'.$ButColor.'; ">Click here to access your message</p>
                </div>
                </a>
            </repeater>
        </td>
        <td class="w30" width="30"></td>
    </tr>
</tbody></table>
</td></tr>
            <tr><td class="w640" width="640" height="15" bgcolor="#ffffff"></td></tr>

            <tr>
            <td class="w640" width="640">
<table id="footer" class="w640" width="640" cellpadding="0" cellspacing="0" border="0" bgcolor="#c7c7c7">
    <tbody><tr><td class="w30" width="30"></td><td class="w580 h0" width="360" height="30"></td><td class="w0" width="60"></td><td class="w0" width="160"></td><td class="w30" width="30"></td></tr>
    <tr>
        <td class="w30" width="30"></td>
        <td class="w580" width="360" valign="top">
        <span class="hide"><p id="permission-reminder" align="left" class="footer-content-left"><span>You are receiving this because another doctor included your email to refer a patient to you.</span></p></span>
        <p align="left" class="footer-content-left"><preferences lang="en">Terms & Conditions</preferences> | <unsubscribe>Privacy, security & HIPAA compliance</unsubscribe></p>
        </td>
        <td class="hide w0" width="60"></td>
        <td class="hide w0" width="160" valign="top">
        <p id="street-address" align="right" class="footer-content-right">
            <span style="color:#ededed; font-size:14px;">Inmers LLC </span><br>
            <span style="color:#ededed;">411 N. Washington </span><br>
<span style="color:#ededed;">75246 Dallas, TX</span></p>
        </td>
        <td class="w30" width="30"></td>
    </tr>
    <tr><td class="w30" width="30"></td><td class="w580 h0" width="360" height="15"></td><td class="w0" width="60"></td><td class="w0" width="160"></td><td class="w30" width="30"></td></tr>
</tbody></table>
</td>
            </tr>
            <tr><td class="w640" width="640" height="200"></td></tr>
        </tbody></table>
    </td>
</tr>
<tr><td class="w640" width="640" height="20"></td></tr>     
</tbody></table><div style="width:100%; height:80px;"></div></body></html>

        ';


        $Body='<h3>';
        $Body.= $msg;
        //$Body.='</h3>';

        $Body.='<h3>';
        $Body.= $message2;
        $Body.='</h3>';
        $Body = $MsgHtml;

        $FromText = '';
        if ($is_sender_doctor == '1') $FromText = $senderFullName.' via Health2me';
        else $FromText = 'Member '.$senderFullName.' via Health2me';

    /*$transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
      ->setUsername('newmedibank@gmail.com')
      ->setPassword('ardiLLA98'); */
    

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


    if($NotifType != 'newApt') $result = $mailer->send($message);
    
    
    }
    
}

?>
