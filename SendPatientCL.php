<?php
define('INCLUDE_CHECK',1);
require "logger.php";
require("environment_detail.php");

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


$Tipo = $_GET['Tipo'];
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
$PhoneNumber = $_GET['PhoneNumber'];
$TempoPass = $_GET['TempoPass'];
$Idpin=0;

$Numero = $PhoneNumber;


$tbl_name="doctorslinkdoctors"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$confirm_code=md5(uniqid(rand()));

if ($Tipo=='1')  // Basic Access
{
	$q = $con->prepare("INSERT INTO doctorslinkusers SET IdMED = ?, IdUs = ?, Fecha = NOW(), estado = 1, Confirm =?, Tipo=1, TemporaryEmail=? , TemporaryPhone=?, TempoPass='' ");
	$q->bindValue(1, $IdDocOrigin, PDO::PARAM_INT);
	$q->bindValue(2, $IdPac, PDO::PARAM_INT);
	$q->bindValue(3, $confirm_code, PDO::PARAM_STR);
	$q->bindValue(4, $ToEmail, PDO::PARAM_STR);
	$q->bindValue(5, $PhoneNumber, PDO::PARAM_STR);
	$q->execute();
	
	$IdRef = $con->lastInsertId(); 
}
else            
{
/*
	$IdDLU = -1;
	$result = mysql_query("SELECT * FROM doctorslinkusers WHERE IdMED = '$IdDoc' AND IdUs = '$IdPac'");
	while ($row = mysql_fetch_array($result)) {
		$IdDLU = $row['id'];
	}

	$q = mysql_query("UPDATE doctorslinkusers SET TempoPass='$TempoPass' WHERE id='$IdDLU' ");   
	$IsDLUEntry = 0;
	$resultPRE = mysql_query("SELECT * FROM doctorslinkusers WHERE IdMED = '$IdDoc' AND IdUs = '$IdPac'");
	while ($rowPRE = mysql_fetch_array($resultPRE)) {
			$TempoPass = $rowPRE['TempoPass'];
			$IsDLUEntry = 1;
			$IdRef = $rowPRE['IdUs'];
		}
*/
	$q = $con->prepare("UPDATE usuarios SET IdInvite=?, TempoPass=?,email=?,telefono=?,notify_email=1 WHERE Identif=?");
	$q->bindValue(1, $IdDocOrigin, PDO::PARAM_INT);
	$q->bindValue(2, $TempoPass, PDO::PARAM_STR);
	$q->bindValue(3, $ToEmail, PDO::PARAM_STR);
	$q->bindValue(4, $PhoneNumber, PDO::PARAM_STR);
	$q->bindValue(5, $IdPac, PDO::PARAM_INT);
	$q->execute();
	
	$IdRef = $IdPac;
//    echo 'Query: '.$q.' ******';
//    echo "UPDATE usuarios SET TempoPass='$TempoPass',email='$ToEmail',telefono='$PhoneNumber',notify_email=1 WHERE Identif='$IdPac'";
}

$result = $con->prepare("SELECT * FROM doctors WHERE id = ? LIMIT 15");
$result->bindValue(1, $IdDoc, PDO::PARAM_INT);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	$Name = $row['Name'];
	$Surname = $row['Surname'];
	$Role = $row['Role'];
	$Treat = '';
	if ($Role=='1') $Treat = 'Dr.';
	
	$Email = $ToEmail;
	if ($row['IdMEDEmail']) $Email = $row['IdMEDEmail'];
	//$Numero = $PhoneNumber;
	//if ($row['phone']) $Numero = $row['phone'];
	}

$result = $con->prepare("SELECT * FROM usuarios WHERE Identif = ? LIMIT 15");
$result->bindValue(1, $IdPac, PDO::PARAM_INT);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	$NamePac = $row['Name'];
	$SurnamePac = $row['Surname'];
	$UserLogin = $row['IdUsFIXEDNAME'];
	$UserEmail = $row['email'];
	}

//echo 'Number: '.$Numero.' ***********************';
//echo 'PhoneNumber: '.$PhoneNumber.' ***********************';

// SEND EMAIL TO PATIENT:
require_once 'lib/swift_required.php';

$NameDoctor = $Treat.' '.$Name.' '.$Surname;
$NamePatient = $NamePac.' '.$SurnamePac;

$aQuien = $ToEmail;
$Sobre = 'Health2Me Patient Portal.- This is a request for connection. ';
if ($Tipo=='2') $Sobre = 'You are invited to access your clinical record. ';

$FromText=$NameDoctor.' via Health2Me';

$Content = '<p style="font-size:14px; color:blue;">Health2Me Patient Portal</p><p>Hello '.$NamePac.',</p>';
$ContenidoAdic = '<p>'.$NameDocOrigin.' '.$SurnameDocOrigin.' wants to connect with you on Health2Me Patient Portal. Please click this link to confirm: </p><p style="font-size:18px;">'.$domain.'/patientAckReq.php?Nombre=nametest.surnametest&Password=11111111&IdMed='.$IdDoc.'&IdUsu='.$IdPac.'&Acceso=23432&email='.$ToEmail.'&phone='.$PhoneNumber.'&Confirm='.$confirm_code.'</p>';

$ContenidoAdic='';
$Content ='
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
            <div class="header-content"><webversion>Health2Me Health Social Network</webversion><span class="hide">&nbsp;&nbsp;|&nbsp; <preferences lang="en">Patient Portal</preferences>&nbsp;&nbsp;|&nbsp; <unsubscribe></unsubscribe></span></div>
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
    
    <div align="center" style="text-align: center">
        <img id="customHeaderImage" label="Header Image" editable="true" width="150" src="images/health2melogo-min2.122104.png" class="w640" border="0" align="top" style="display: inline; margin-top:30px;">
        <div style="font-size: 20px; margin-top: 0px; font-size: 16px; color:#489de5; font-family: "Cuprum", Arial, sans-serif; text-align: center; display: block;">unlocking health</div>
    </div>
    
    
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
                                    <p align="left" class="article-title"><singleline label="Title">Hello '.$NamePac.',</singleline></p>
                                    <div align="left" class="article-content">
                                        <multiline label="Description"><span style="color:#54bc00; font-size:13px; font-weight:bold;">Dr. '.$NameDocOrigin.' '.$SurnameDocOrigin.' </span> is requesting to connect with you through Health2Me Health Social Network to give you access to your Medical Records and be able to exchange messages in a safe and private way. <span style="color:#22aeff; font-size:13px; font-weight:bold;"></span></multiline>
                                    </div>
                                </td>
                            </tr>
                            <tr><td class="w580" width="580" height="10"></td></tr>
                        </tbody></table>
                    </layout>
                                        
                    <a style="text-decoration:none;" href="'.$domain.'/patientAckReq.php?Confirm='.$confirm_code.'&IdMed='.'" >
                    <div style="width:280px; height:40px; line-height: 40px; background-color:#58a1fe; color:white; border: 1 #cacaca; margin:0 auto; text-align:center; margin-bottom:20px; text-decoration:none;">
                       <p style="margin:0 auto; font-size:16px;">Click here to accept request</p>
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
    <tr><td class="w640" width="640" height="2"></td></tr>     
</tbody></table><div style="width:100%; height:80px;"></div></body></html>

';




if ($Tipo=='2') 
{
$Content = '<p style="font-size:14px; color:blue;">Health2Me Patient Portal</p><p>Hello '.$NamePac.',</p>';
$ContenidoAdic = '<p>'.$NameDocOrigin.' '.$SurnameDocOrigin.' wants to invite you to Health2Me Patient Portal. </p><p>Use this name for login: <strong>'.$UserEmail.'</strong></p><p>Please use this temporary password: <p style="font-size:18px; color:green;">'.$TempoPass.'</p></p>';
$ContenidoAdic .= '<p>'.$NameDocOrigin.' '.$SurnameDocOrigin.'Please click this link to confirm invitation: </p><p style="font-size:18px;">'.$domain.'/patientAckReqInvit.php?Nombre=nametest.surnametest&Password=11111111&IdMed='.$IdDoc.'&IdUsu='.$IdPac.'&Acceso=23432&email='.$ToEmail.'&phone='.$PhoneNumber.'&Confirm='.$TempoPass.'</p>';

$ContenidoAdic .= '<p> Links iOS, Android </p>';



$ContenidoAdic='';
$Content ='
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
            <div class="header-content"><webversion>Health2Me Health Social Network</webversion><span class="hide">&nbsp;&nbsp;|&nbsp; <preferences lang="en">Patient Portal</preferences>&nbsp;&nbsp;|&nbsp; <unsubscribe></unsubscribe></span></div>
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
    
    <div align="center" style="text-align: center">
        <img id="customHeaderImage" label="Header Image" editable="true" width="150" src="images/health2melogo-min2.122104.png" class="w640" border="0" align="top" style="display: inline; margin-top:30px;">
        <div style="font-size: 20px; margin-top: 0px; font-size: 16px; color:#489de5; font-family: "Cuprum", Arial, sans-serif; text-align: center; display: block;">unlocking health</div>
    </div>
    
    
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
                                    <p align="left" class="article-title"><singleline label="Title">Hello '.$NamePac.',</singleline></p>
                                    <div align="left" class="article-content">
                                        <multiline label="Description">Dr. '.$NameDocOrigin.' '.$SurnameDocOrigin.' invites you to access your medical records and to exchange messages through Health2Me safe and private platform.</multiline><span><p>Once confirmed the link below, go to Health2Me website to access your medical records at: www.health2.me</p> <p>Use this name for login: <strong>'.$UserEmail.'</strong></p><p>Your temporary password is </span><span style="font-size:14px; color:#54bc00; ">'.$TempoPass.'</span></p>.
                                    </div>
                                </td>
                            </tr>
                            <tr><td class="w580" width="580" height="10"></td></tr>
                        </tbody></table>
                    </layout>
                                        
                    <a style="text-decoration:none;" href="'.$domain.'/patientAckReqInvit.php?Nombre=nametest.surnametest&Password=11111111&IdMed='.$IdDoc.'&IdUsu='.$IdPac.'&Acceso=23432&email='.$ToEmail.'&phone='.$PhoneNumber.'&Confirm='.$TempoPass.'" >
                    <div style="width:280px; height:40px; line-height: 40px; background-color:#58a1fe; color:white; border: 1 #cacaca; margin:0 auto; text-align:center; margin-bottom:20px; text-decoration:none;">
                       <p style="margin:0 auto; font-size:16px;">Click here to accept invitation</p>
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
    <tr><td class="w640" width="640" height="2"></td></tr>     
</tbody></table><div style="width:100%; height:80px;"></div></body></html>

';









}



$Body = $Content.$ContenidoAdic;
  
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
// SEND EMAIL TO PATIENT ******************************

//die;

if ($CallPhone == '1')
{

// CALL PATIENT
require "Services/Twilio.php";

$queNum = $Numero; 
/* Set our AccountSid and AuthToken */
//$AccountSid = "ACc8d7e18334aea996eee97355a50fe672";
//$AuthToken = "484f8b70a66fe46fdb49f44900f891ec";
$AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
$AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
 
/* Your Twilio Number or an Outgoing Caller ID you have previously validated
    with Twilio */
//$from = '34518880185';
$from = '+19034018888';
 

// Your Account Sid and Auth Token from twilio.com/user/account
/*
$sid = "AC109c7554cf28cdfe596e4811c03495bd"; 
$token = "26b187fb3258d199a6d6edeb7256ecc1";
$client = new Services_Twilio($sid, $token); 
$call = $client->account->calls->create("+34608754342", "+34608754342", "http://demo.twilio.com/docs/voice.xml", array());
echo $call->sid; 
*/ 
 
 
 
/* Number you wish to call */
//$to= '34608754342';
$to= $queNum;
/* Directory location for callback.php file (for use in REST URL)*/
$url = $domain;
/* Instantiate a new Twilio Rest Client */
$client = new Services_Twilio($AccountSid, $AuthToken);
//$CallString = $url . '/callback.php?IdRef=1&NameDoctor='.$NameDoctor.'&NameDoctorRequest='.$NameDocOrigin.'&NamePatient='.$NamePatient.'&number=' . $to;
$CallString = $url . '/callback.php?IdRef='.$IdRef.'&NameDoctor=Dr Javier Vinals&NameDoctorRequest=Dr dos dos&NamePatient=Nombre Paciente&number=23';

$NameDoctor = str_replace(' ', '', $Name);
$SurnameDoctor = str_replace(' ', '', $Surname);
$NameDoctorOrigin = str_replace(' ', '', $NameDocOrigin);
$SurnameDoctorOrigin = str_replace(' ', '',$SurnameDocOrigin);
$NamePatient = str_replace(' ', '', $NamePac );
$SurnamePatient = str_replace(' ', '', $SurnamePac);

if ($Tipo=='1')
{
	$CallString =  $url .'/callbackPAT.php?IdRef='.$IdRef.'&NameDoctor='.$NameDoctor.'&SurnameDoctor='.$SurnameDoctor.'&IdDocOrigin='.$IdDocOrigin.'&NameDoctorOrigin='.$NameDoctorOrigin.'&SurnameDoctorOrigin='.$SurnameDoctorOrigin.'&NamePatient='.$NamePatient.'&SurnamePatient='.$SurnamePatient.'&number=' .$to;  // ALERT:  THIS STRING DOES NOT ALLOW BLANK SPACES WITHIN IT ***************
//	echo $CallString;
} 
else
{
	$IdRef = $IdPac;
	$CallString =  $url .'/callbackPATInvit.php?IdRef='.$IdRef.'&NameDoctor='.$NameDoctor.'&SurnameDoctor='.$SurnameDoctor.'&IdDocOrigin='.$IdDocOrigin.'&NameDoctorOrigin='.$NameDoctorOrigin.'&SurnameDoctorOrigin='.$SurnameDoctorOrigin.'&NamePatient='.$NamePatient.'&SurnamePatient='.$SurnamePatient.'&number=' .$to.'&TempoPass='.$TempoPass;  // ALERT:  THIS STRING DOES NOT ALLOW BLANK SPACES WITHIN IT ***************

}

//echo ' ---- Calling number : '.$to;

$call = $client->account->calls->create($from, $to, $CallString);

/* redirect back to the main page with CallSid */
$msg = urlencode("Connecting... ".$call->sid);
//header("Location: index.php?msg=$msg");
return 'OK';

// CALL REFERRAL  *********************************
}

?>