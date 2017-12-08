<?php
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
require_once('push/push.php');
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

$q=con->prepare("select *  from usuarios where Identif=?");
$q->bindValue(1, $IdUsu, PDO::PARAM_INT);
$q->execute();

$row = $q->fetch(PDO::FETCH_ASSOC);
$Patientname = $row['Name'];
$PatientSurname = $row['Surname'];

//echo '  ** POINT 2  (Selector = '.$Selector.' , DoctorId = '.$FromDoctorId.')';

if ($Selector == '2')
{
	$q=$con->prepare("select * from doctors where id=?");
	$q->bindValue(1, $FromDoctorId, PDO::PARAM_INT);
	$q->execute();

	$row = $q->fetch(PDO::FETCH_ASSOC);
	$Notify = $row['notify_email'];
}
else
{
	$q=$con->prepare("select * from usuarios where Identif=?");
	$q->bindValue(1, $userId, PDO::PARAM_INT);
	$q->execute();
	
	$row = $q->fetch(PDO::FETCH_ASSOC);
	$Notify = 1;
}


//echo '  ** POINT 3 (Notify = '.$Notify.')';


if($Notify == '1') {

            if ($Selector == '2')
			{
				$ToEmail= $row['IdMEDEmail'];
				$loginname=$row['IdMEDFIXEDNAME'];
				$MsgText = '
							        <p align="left" class="article-title"><singleline label="Title">Dear Dr. '.$FromDoctorName.' '.$FromDoctorSurname.',</singleline></p>
                                    <p align="left" class="article-title"><singleline label="Title">New encrypted message from '.$Patientname.' '.$PatientSurname.'.</singleline></p>
							';

			}
			else
			{
				$ToEmail= $row['email'];
				$loginname=$row['IdUsFIXEDNAME'];
				$MsgText = '
							        <p align="left" class="article-title"><singleline label="Title">Dear '.$Patientname.' '.$PatientSurname.',</singleline></p>
                                    <p align="left" class="article-title"><singleline label="Title">New encrypted message from Dr. '.$FromDoctorName.' '.$FromDoctorSurname.'.</singleline></p>
							';
			}
		    $Todoctorname=$row['Name'];
			$TodoctorSurname=$row['Surname'];
		 
		 
			require_once 'lib/swift_required.php';
			
			switch ($NotifType)
			{
				case '1':	$MsgColor='#22aeff'; 
							$ButColor='grey'; 
							$Sobre='Contact Center Health2me';
							break;
				case '2': 	$MsgColor='orange'; 
							$ButColor='#22aeff'; 
							$MsgText = 'Dr. '.$FromDoctorName.' '.$FromDoctorSurname.' changed referral stage for patient '.$Patientname.' '.$PatientSurname;
							$Sobre='Changed referral stage for patient '.$Patientname.' '.$PatientSurname;
				break;
			}
			
			//$ToEmail=$_GET['email'];
			//$Sobre="Health2me Notification";
			
			$aQuien = $ToEmail;
            $Body = '';
    
			$msg= '<p>Dear '.$Patientname.' '.$PatientSurname.',<p><p> You have a message from Dr. '.$FromDoctorName.' '.$FromDoctorSurname.'</p>'.'
			<p>Please click <b><u><a href="'.$domain.'/SignInUSER.html?userlogin='.$loginname.'&idp='.$IdUsu.'">here</a></u></b> to login and see the message in your Inbox!</p> <p>Thank You,</p>';
			
			$message2= '***** HIPAA AND STANDARD COMPLIANCE PROCEDURES DISCLAIMER *****  :   ';
			$message2.= ' Text of the Legal, Disclaimer and technical explanations here ... Lore Ipsum';
			
            $message2='';

			
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
                                <td class="w580" width="580">'.$MsgText.'
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
                       <p style="margin:0 auto; font-size:16px; background-color:'.$ButColor.';">Click here to access your message</p>
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

		  ->setBody($MsgHtml, 'text/html')

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
			$Body='<h3>';
			$Body.= $message;
			$Body.='</h3>';
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
#top-bar { border-radius:6px 6px 0px 0px; -moz-border-radius: 6px 6px 0px 0px; -webkit-border-radius:6px 6px 0px 0px; -webkit-font-smoothing: antialiased; background-color: grey; color: #ededed; }
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
#footer { background-color: grey; color: #ededed; }
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
                                    <p align="left" class="article-title"><singleline label="Title">New encrypted message from Dr. '.$FromDoctorName.' '.$FromDoctorSurname.'.</singleline></p>
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
                       <p style="margin:0 auto; font-size:16px;">Click here to access your message</p>
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

		  ->setBody($MsgHtml, 'text/html')

		  ;


		$result = $mailer->send($message);

	}

}



function sanitize($data) {
  return htmlspecialchars($data);
}

 ?>