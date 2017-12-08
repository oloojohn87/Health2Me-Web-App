<?php
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
require_once("../modules/push/push.php");

/*$app_key = '71864d35b684736ec717';
$app_secret = '82df6497e5a74c524ab8';
$app_id = '51355';*/



$msg=empty($_GET['message']) ? null: $_GET['message'];
$chn=empty($_GET['channel']) ? null: $_GET['channel'];
$NType=empty($_GET['NotifType']) ? null: $_GET['NotifType'];


if($msg!=null and $chn!=null)
{
Push_notification($chn,$msg,$NType);
Push_Email($chn,$msg,$NType);
} 

//Push_notification($chn,$msg);
//Push_Email($chn,$msg);

function Push_notification($channel,$message,$NotifType){
//$app_key = 'd869a07d8f17a76448ed';
//$app_secret = '92f67fb5b104260bbc02';
//$app_id = '51379';
//$pusher = new Pusher($app_key, $app_secret, $app_id);
$push = new Push();
$data = array('message' => $message);
$push->send($channel, 'notification', $data);

}

//This function is for sending the notification using email whenever user sends a new message from the inbox
function Push_Email($userId,$message,$NotifType){

require("environment_detail.php");

$FromDoctorId=empty($_GET['FromDoctorId']) ? null:$_GET['FromDoctorId'];
$FromDoctorName=empty($_GET['FromDoctorName']) ? null:$_GET['FromDoctorName'];
$FromDoctorSurname=empty($_GET['FromDoctorSurname']) ? null:$_GET['FromDoctorSurname'];
$Patientname=empty($_GET['Patientname']) ? null:$_GET['Patientname'];
$PatientSurname=empty($_GET['PatientSurname']) ? null:$_GET['PatientSurname'];
$IdUsu=empty($_GET['IdUsu']) ? null:$_GET['IdUsu'];
$Selector=empty($_GET['Selector']) ? null:$_GET['Selector'];
 
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

$q=$con->prepare("select Name,Surname,IdMEDFIXEDNAME,IdMedEmail,notify_email  from doctors where id=?");
$q->bindValue(1, $FromDoctorId, PDO::PARAM_INT);
$q->execute();

$row = $q->fetch(PDO::FETCH_ASSOC);
$FromDoctorName = $row['Name'];
$FromDoctorSurname = $row['Surname'];

$q=$con->prepare("select *  from usuarios where Identif=?");
$q->bindValue(1, $IdUsu, PDO::PARAM_INT);
$q->execute();

$row = $q->fetch(PDO::FETCH_ASSOC);
$Patientname = $row['Name'];
$PatientSurname = $row['Surname'];


$q=$con->prepare("select Name,Surname,IdMEDFIXEDNAME,IdMedEmail,notify_email  from doctors where id=?");
$q->bindValue(1, $userId, PDO::PARAM_INT);
$q->execute();

$row = $q->fetch(PDO::FETCH_ASSOC);

if($row['notify_email']) {
 
            $ToEmail= $row['IdMedEmail'];
		    $Todoctorname=$row['Name'];
			$TodoctorSurname=$row['Surname'];
			$loginname=$row['IdMEDFIXEDNAME'];
		 ###Send notification to the doctor who had requested for unlocking the report####

			require_once 'lib/swift_required.php';
			
			$Sobre="Health2me Notification";
			switch ($NotifType)
			{
				case '1':	$MsgColor='#22aeff'; 
							$ButColor='grey'; 
							$MsgText = 'New encrypted message from Dr. '.$FromDoctorName.' '.$FromDoctorSurname;
							$Sobre='New encrypted message';
							break;
				case '2': 	$MsgColor='DarkBlue'; 
							$ButColor='#22aeff'; 
							$MsgText = 'Dr. '.$FromDoctorName.' '.$FromDoctorSurname.' changed referral stage for patient '.$Patientname.' '.$PatientSurname;
							$Sobre='Changed referral stage for patient '.$Patientname.' '.$PatientSurname;
							break;
			}

			//$ToEmail=$_GET['email'];
			
			$aQuien = $ToEmail;

            if ($Selector == '2')
            {
                    $msg= '<p>Dear Dr. '.$Todoctorname.' '.$TodoctorSurname.',<p><p> You have a message from Patient: '.$FromDoctorName.' '.$FromDoctorSurname.'</p>
                    <p>Please click <b><u><a href="'.$domain.'/SignIn_Ref.php?userlogin='.$loginname.'&idp='.$IdUsu.'">here</a></u></b> to login and see the message in your Inbox!</p> <p>Thank You,</p>';
             }
            else
            {
                    $msg= '<p>Dear Dr. '.$Todoctorname.' '.$TodoctorSurname.',<p><p> You have a message from Dr. '.$FromDoctorName.' '.$FromDoctorSurname.' related to patient '.$Patientname.' '.$PatientSurname.'</p>
                    <p>Please click <b><u><a href="'.$domain.'/SignIn_Ref.php?userlogin='.$loginname.'&idp='.$IdUsu.'">here</a></u></b> to login and see the message in your Inbox!</p> <p>Thank You,</p>';
            }
    
			
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
                                    <p align="left" class="article-title"><singleline label="Title">'.$MsgText.'</singleline></p>
                                    <div align="left" class="article-content">
                                        <multiline label="Description">
                                        
                                        </multiline>
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
    
			
			$FromText='Dr. '.$FromDoctorName.' '.$FromDoctorSurname.' via Health2me';
			
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


		$result = $mailer->send($message);

	}

}

//This function is for sending email reminders to patients
function Push_Patient_Email($userId,$message){

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
 
$q=$con->prepare("select email  from usuarios where identif =?");
$q->bindValue(1, $userId, PDO::PARAM_INT);
$q->execute();

$row = $q->fetch(PDO::FETCH_ASSOC);
$num_rows = $q->rowCount();
if($num_rows) {
 
            $ToEmail= $row['email'];
		   
		

			require_once 'lib/swift_required.php';
			
			
			
			$Sobre="Health2me Appointment Notification";
			
			$aQuien = $ToEmail;

			/*$msg= '<p>Dear Doctor,<p><p> Your have a message from doctor. 
			Please login into <a href="'.$domain.'">'.$domain.'</a> to see the message in your Inbox in patient page!</p><p>Thank You,</p>';
			
			$message2= '***** HIPAA AND STANDARD COMPLIANCE PROCEDURES DISCLAIMER *****  :   ';
			$message2.= ' Text of the Legal, Disclaimer and technical explanations here ... Lore Ipsum';
			*/
			
			$Body='<h3>';
			$Body.= $message;
			$Body.='</h3>';
		   /*
			$Body.='<h3>';
			$Body.= $message2;
			$Body.='</h3>';
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
		  ->setFrom(array('hub@inmers.us' => 'Health2Me Health Social Network'))

		  // Set the To addresses with an associative array
		  ->setTo(array($aQuien))

		  ->setBody($Body, 'text/html')

		  ;


		$result = $mailer->send($message);

	}

}

//This function is for emailing reminders to doctors
function Push_Reminder_Email($userId,$message){

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
 
$q=$con->prepare("select IdMedEmail,notify_email  from doctors where id=?");
$q->bindValue(1, $userId, PDO::PARAM_INT);
$q->execute();

$row = $q->fetch(PDO::FETCH_ASSOC);

if($row['notify_email']) {
 
            $ToEmail= $row['IdMedEmail'];
		   
		 ###Send notification to the doctor who had requested for unlocking the report####

			require_once 'lib/swift_required.php';
			
			
			//$ToEmail=$_GET['email'];
			$Sobre="Health2me Appointment Notification";
			
			$aQuien = $ToEmail;

			/*$msg= '<p>Dear Doctor,<p><p> Your have a message from doctor. 
			Please login into <a href="'.$domain.'">'.$domain.'</a> to see the message in your Inbox in patient page!</p><p>Thank You,</p>';
			
			$message2= '***** HIPAA AND STANDARD COMPLIANCE PROCEDURES DISCLAIMER *****  :   ';
			$message2.= ' Text of the Legal, Disclaimer and technical explanations here ... Lore Ipsum';
			*/
			
			$Body='<h3>';
			$Body.= $message;
			$Body.='</h3>';
		   
			//$Body.='<h3>';
			//$Body.= $message2;
			//$Body.='</h3>';
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
		  ->setFrom(array('hub@inmers.us' => 'Health2Me Health Social Network'))

		  // Set the To addresses with an associative array
		  ->setTo(array($aQuien))

		  ->setBody($Body, 'text/html')

		  ;


		$result = $mailer->send($message);

	}

}


//For Sending Probe Requests via Email
function Push_Probe_Email($doctorName,$patientEmail,$probeID,$emergency){
    require_once 'lib/swift_required.php';
    require("environment_detailForLogin.php");
    
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
    
    $probe = $con->prepare("SELECT * FROM probe WHERE probeID = ?");
    $probe->bindValue(1, $probeID, PDO::PARAM_INT);
    $probe->execute();
    $probe_row = $probe->fetch(PDO::FETCH_ASSOC);
    
    $doc = $con->prepare("SELECT Surname,Gender FROM doctors WHERE id = ?");
    $doc->bindValue(1, $probe_row['doctorID'], PDO::PARAM_INT);
    $doc->execute();
    $doc_row = $doc->fetch(PDO::FETCH_ASSOC);

    $gender = 'he';
    if($doc_row['Gender'] == 0)
        $gender = 'she';

    $pat = $con->prepare("SELECT Name,Surname,telefono FROM usuarios WHERE Identif = ?");
    $pat->bindValue(1, $probe_row['patientID'], PDO::PARAM_INT);
    $pat->execute();
    $pat_row = $pat->fetch(PDO::FETCH_ASSOC);
    
    $protocol = $con->prepare("SELECT * FROM probe_protocols WHERE protocolID = ?");
    $protocol->bindValue(1, $probe_row['protocolID'], PDO::PARAM_INT);
    $protocol->execute();
    $protocol_row = $protocol->fetch(PDO::FETCH_ASSOC);
    
    $questions_index = array($protocol_row['question1'] => 1);
    $protocol_questions = $protocol_row['question1'];
    if($protocol_row['question2'] != NULL)
    {
        $protocol_questions .= ','.$protocol_row['question2'];
        $questions_index[ $protocol_row['question2'] ] = 2;
    }
    if($protocol_row['question3'] != NULL)
    {
        $protocol_questions .= ','.$protocol_row['question3'];
        $questions_index[ $protocol_row['question3'] ] = 3;
    }
    if($protocol_row['question4'] != NULL)
    {
        $protocol_questions .= ','.$protocol_row['question4'];
        $questions_index[ $protocol_row['question4'] ] = 4;
    }
    if($protocol_row['question5'] != NULL)
    {
        $protocol_questions .= ','.$protocol_row['question5'];
        $questions_index[ $protocol_row['question5'] ] = 5;
    }

    $question = $con->prepare("SELECT * FROM probe_questions WHERE id IN (".$protocol_questions.")");
    $question->execute();
    
    $questions = '';
    while($question_row = $question->fetch(PDO::FETCH_ASSOC))
    {
        $questions .= '<span style="color: #777;">'.$question_row['question_text'].'</span><br/>';
        if($question_row['answer_type'] == 3)
        {
            $questions .= 
                '<select style="width: 100%; height: 30px; border-radius: 5px; padding: 15px; color: #444; border: 1px solid #D6D6D6; outline: none; margin-bottom: 10px; background-color: #FFF;" name="question'.$questions_index[ $question_row['id'] ].'" id="question'.$questions_index[ $question_row['id'] ].'" />
                    <option value="1">Yes</option>
                    <option value="2">No</option>
                </select>';
        }
        else
        {
            $questions .= '<input type="number" style="border-radius: 5px; padding: 5px; padding-top: 8px; padding-bottom: 8px; color: #444; width: 98%; border: 1px solid #D6D6D6; outline: none; margin-bottom: 10px;" val="" name="question'.$questions_index[ $question_row['id'] ].'" id="question'.$questions_index[ $question_row['id'] ].'" />';
        }
    }
    
    $Content = file_get_contents('templates/probe_email.html');
    $Content = str_replace("**Var3**", $doctorName, $Content);
    $Content = str_replace("**Var4**", $gender, $Content);
    $Content = str_replace("**Var5**", $questions, $Content);
    $Content = str_replace("**Var6**", $domain, $Content);
    $Content = str_replace("**Var7**", $probeID, $Content);

    $FromText='Health2Me';


     $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
      ->setUsername('dev.health2me@gmail.com')
      ->setPassword('health2me');


    $mailer = Swift_Mailer::newInstance($transporter);


    // Create the message
    $message = Swift_Message::newInstance()

  // Give the message a subject
  ->setSubject("Information Request from Dr. ".$doctorName)


  // Set the From address with an associative array
  ->setFrom(array('hub@inmers.us' => $FromText))

  // Set the To addresses with an associative array
  ->setTo(array($patientEmail))

  ->setBody($Content, 'text/html')

  ;

    try{
        $result = $mailer->send($message);
        create_sent_probe_log($probeID,1,'',0,$emergency);
    }
    catch (Exception $e) 
    {
        //echo 'Error: ' . $e->getMessage();
        create_sent_probe_log($requestID,1,$uniqueToken,13,$emergency);
    }

}

	
//For Sending Probe Requests via Phone	
function Health_Feedback_Call($doctorname,$doctorsurname,$patientname,$patientsurname,$patientphone,$IdRef,$emergency)
{
    session_start();
    require "Services/Twilio.php";
    require("environment_detailForLogin.php");
    
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

    //$Numero = "4695797083";
    //$queNum = $Numero; 
    $queNum = $patientphone; 
    /* Set our AccountSid and AuthToken */
    $AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
    $AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";

    /* Your Twilio Number or an Outgoing Caller ID you have previously validated
        with Twilio */

    $from = '+19034018888';
    /* Number you wish to call */
    $to= $queNum;

    /* Directory location for callback.php file (for use in REST URL)*/
    $url = $domain;
    /* Instantiate a new Twilio Rest Client */
    $client = new Services_Twilio($AccountSid, $AuthToken);
    //$IdRef=1;
    //$CallString = $url . '/FeedbackCallback.php?IdRef='.$IdRef.'&NameDoctor='.$doctorname.'&SurnameDoctor='.$doctorsurname.'&NamePatient='.$patientname.'&SurnamePatient='.$patientsurname;
    //$CallString = str_replace(' ','',$CallString); //need this else it give an exception

    $CallString = $url."/probe_call.php?probeID=".$IdRef;

    try 
    {
        $call = $client->account->calls->create($from, '+'.$to, $CallString/*, array("StatusCallback"=>$url."/callstatus.php")*/);
        //$call = $client->account->calls->create($from, '+'.$to, $CallString);
        //$msg = urlencode("Connecting... ".$call->sid);
        //echo $call->sid;
        //create_sent_probe_log($IdRef,2,$call->sid,0,$emergency);
    }
    catch (Exception $e) 
    {
        $error = $e->getMessage();
        $results2 = $con->prepare("INSERT INTO twilio_errors SET consult_id = ?, patient_name = ?, error = ?, patient_number = ?, doc_name = ?, type = ?");
        $results2->bindValue(1, $IdRef, PDO::PARAM_INT);
        $results2->bindValue(2, ($patientname." ".$patientsurname), PDO::PARAM_STR);
        $results2->bindValue(3, $error, PDO::PARAM_STR);
        $results2->bindValue(4, $to, PDO::PARAM_STR);
        $results2->bindValue(5, ($doctorname." ".$doctorsurname), PDO::PARAM_STR);
        $results2->bindValue(6, 'phoneprobe', PDO::PARAM_STR);
        $results2->execute();
        //echo 'Error: ' . $e->getMessage();
        create_sent_probe_log($IdRef,2,'',13,$emergency);
    }





    return 'OK';

}

//Send Probe Request via SMS		
function Send_Feedback_SMS($doctorname,$patientname,$patientphone,$probeID,$emergency,$question = 0)
{
    require "Services/Twilio.php";
    require("environment_detailForLogin.php");
     
    $AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
    $AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
    
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

 
 
    // Instantiate a new Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
 
	$from='+19034018888';
    // make an associative array of server admins. Feel free to change/add your 
    // own phone number and name here.
 
    // Iterate over all admins in the $people array. $to is the phone number, 
    // $name is the user's name
    $language = 'en';
    
    $body = ""; 
    
    if($question == 0)
    {
        $body = "Dr.$doctorname Health Status Request, Reply VERY BAD, BAD, NORMAL, GOOD or VERY GOOD to update your data.Reply STOP to Cancel";
    }
    else
    {
        $probe = $con->prepare("SELECT * FROM probe WHERE probeID = ?");
        $probe->bindValue(1, $probeID, PDO::PARAM_INT);
        $probe->execute();
        $probe_row = $probe->fetch(PDO::FETCH_ASSOC);
        
        $protocol = $con->prepare("SELECT * FROM probe_protocols WHERE protocolID = ?");
        $protocol->bindValue(1, $probe_row['protocolID'], PDO::PARAM_INT);
        $protocol->execute();
        $protocol_row = $protocol->fetch(PDO::FETCH_ASSOC);

        $doc = $con->prepare("SELECT Surname,Gender FROM doctors WHERE id = ?");
        $doc->bindValue(1, $probe_row['doctorID'], PDO::PARAM_INT);
        $doc->execute();
        $doc_row = $doc->fetch(PDO::FETCH_ASSOC);
        
        $gender = 'he';
        if($doc_row['Gender'] == 0)
            $gender = 'she';
        
        if($language == 'es')
        {
            $gender = 'él';
            if($doc_row['Gender'] == 0)
                $gender = 'ella';
        }

        $pat = $con->prepare("SELECT Name,Surname,telefono FROM usuarios WHERE Identif = ?");
        $pat->bindValue(1, $probe_row['patientID'], PDO::PARAM_INT);
        $pat->execute();
        $pat_row = $pat->fetch(PDO::FETCH_ASSOC);
        
        $questions = $con->prepare("SELECT * FROM probe_questions WHERE id = ?");
        $questions->bindValue(1, $protocol_row['question'.$question], PDO::PARAM_INT);
        $questions->execute();
        $questions_row = $questions->fetch(PDO::FETCH_ASSOC);
        if($probe_row['currentQuestion'] == 1)
        {
            
            $pre_body = '';
            if($language == 'en')
                $pre_body = 'Doctor '.$doc_row['Surname'].' is requesting information so that '.$gender.' can treat you better. Please answer the following question(s). ';
            else if($language == 'es')
                $pre_body = 'El doctor '.$doc_row['Surname'].' esta solicitando informacion para te tratar mejor. Por favor, responde las siguientes preguntas.';
            $message = $client->account->sms_messages->create($from, '+'.$patientphone, $pre_body);
            sleep(2);
        }
        
        if($language == 'en')
            $body = $questions_row['question_text'];
        else if($language == 'es')
            $body = $questions_row['question_textSPA'];
        if($questions_row['answer_type'] == 3)
        {
            $body .= '(y/n)';
        }
    }
    
	try
    {
		$message = $client->account->sms_messages->create($from, '+'.$patientphone, $body);
		//echo $message->sid;
		create_sent_probe_log($probeID,3,$message->sid,0,$emergency);
	}
	catch (Exception $e) 
	{
        //echo 'Error: ' . $e->getMessage();
        create_sent_probe_log($probeID,3,'',13,$emergency);

        $error = $e->getMessage();
        $results2 = $con->prepare("INSERT INTO twilio_errors SET consult_id = ?, patient_name = ?, error = ?, patient_number = ?, doc_name = ?, type = ?");
        $results2->bindValue(1, $probeID, PDO::PARAM_INT);
        $results2->bindValue(2, ($patientname), PDO::PARAM_STR);
        $results2->bindValue(3, $error, PDO::PARAM_STR);
        $results2->bindValue(4, $patientphone, PDO::PARAM_STR);
        $results2->bindValue(5, ($doctorname), PDO::PARAM_STR);
        $results2->bindValue(6, 'phoneprobe', PDO::PARAM_STR);
        $results2->execute();
	}
    //echo "Sent message to $patientname";
	return 'OK';
}




function sanitize($data) {
  return htmlspecialchars($data);
}

function create_sent_probe_log($probeID,$method,$sid,$res,$emergency)
{
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

	
	
	//calculate probe time in patient timezone
	$query = $con->prepare("select name from timezones where id=(select timezone from probe where probeID=?)");
	$query->bindValue(1, $probeID, PDO::PARAM_INT);
	$result=$query->execute();
	
	$row = $query->fetch(PDO::FETCH_ASSOC);
	$timezone=$row['name'];

	$query = $con->prepare("select now() as currentDateTime");
	$result=$query->execute();
	$row = $query->fetch(PDO::FETCH_ASSOC);
	$date_str=$row['currentDateTime'];	
				
	//Convert current GMT to Patient Timezone bacause we are storing the patient response time in the database
	$PatientDateTime = convertFromGMT($timezone,$date_str);
	
	
	$query = $con->prepare("insert INTO sentprobelog(probeID,method,requestTime,sid,result,emergency) values(?,?,?,?,?,?)");
	$query->bindValue(1, $probeID, PDO::PARAM_INT);
	$query->bindValue(2, $method, PDO::PARAM_INT);
	$query->bindValue(3, $PatientDateTime, PDO::PARAM_STR);
	$query->bindValue(4, $sid, PDO::PARAM_STR);
	$query->bindValue(5, $res, PDO::PARAM_INT);
	$query->bindValue(6, $emergency, PDO::PARAM_INT);
	$query->execute();

}

function convertFromGMT($timezone,$date_str)
{
	$default_tz = date_default_timezone_get();
	date_default_timezone_set('GMT'); // Set this to user's timezone (obtain user's timezone through js)

	$datetime = new DateTime($date_str);
	$cst = new DateTimeZone($timezone);
	$datetime->setTimezone($cst);

	date_default_timezone_set($default_tz);

	$GMTTime = $datetime->format('Y-m-d H:i:s');
	return $GMTTime;
}

function randString($length, $charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
{
    $str = '';
    $count = strlen($charset);
    while ($length--) {
        $str .= $charset[mt_rand(0, $count-1)];
    }
    return $str;
}


 ?>