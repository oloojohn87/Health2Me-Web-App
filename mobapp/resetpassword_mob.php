<?php
require("../environment_detail.php");
require("../PasswordHash.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="DOCTORS"; // Table name

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	


$IdUserEmail = $_GET['email'];


$confirm_code=md5(uniqid(rand()));

$result = mysql_query("Select * from Usuarios where email = '$IdUserEmail'"); 
$count=mysql_num_rows($result);
$row = mysql_fetch_array($result);

/*if($count==0){
echo "Email ID doesn't exist in the System. Please contact Health2me support!";
}else if($count>=2){
echo "Multiple ID for this same email exist in the System. Please contact Health2me Support!";
}else if($count==1){*/

$IdUsFIXED=$row['IdUsFIXED'];
$IdUsFIXEDNAME=$row['IdUsFIXEDNAME'];

// LLAMADA PARA VERIFICAR EL TELEFONO DEL PACIENTE: ****************************************************

require_once '../MBCaller.php';
//$a = SendCallVERIF (34608754342);

// ENVÍA EL EMAIL AL PACIENTE: ****************************************************
require_once '../lib/swift_required.php';

$aQuien = $IdUserEmail;
$Tema = 'Inmers Account Reset';

$adicional ='<p>Your email: <span><h3>'.$IdUserEmail.'</h3></span><p><p>Your Number id: <span><h3>'.$IdUsFIXED.'</h3></span><p><p>Your Name id: <span><h3>'.$IdUsFIXEDNAME.'</h3></span></p><p>Please use your Name id for sign in purposes.</p>';

$Sobre = $Tema;
$Body = '<p>Please click <a href="'.$domain.'/mobapp/resetPasswordConfirm_mob.php?token='.$confirm_code.'">here</a> to verify and reset your password.</p>'.$adicional;

/*$transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
  ->setUsername('newmedibank@gmail.com')
  ->setPassword('ardiLLA98');*/

    
	 $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
	  ->setUsername('dev.health2me@gmail.com')
	  ->setPassword('health2me');

$mailer = Swift_Mailer::newInstance($transporter);


// Create the message
$message = Swift_Message::newInstance()
  
  // Give the message a subject
  ->setSubject($Sobre)

  // Set the From address with an associative array
  ->setFrom(array('no-reply@health2.me' => 'health2.me'))

  // Set the To addresses with an associative array
  ->setTo(array($aQuien))

  ->setBody($Body, 'text/html')

  ;


$result = $mailer->send($message);

//echo "An email with reset password link has been send. Please check your email and follow the instructions!"
/*$salto="location:SignIn.html";
header($salto);*/
?>
<!DOCTYPE html> 
<html> 
<head> 
	<title>Health2me Account Creation</title> 
	
	<meta name="viewport" content="width=device-width, initial-scale=1"> 

	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
	<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
    <style type="text/css" media="screen">
        .ui-page { -webkit-backface-visibility: hidden; }
    </style>
        <script type="text/javascript">
            
        $(document).bind("pageinit", function(){
            $("#id").click(function(event) {
                alert ('click');
            });    
            //apply overrides here
        });
    </script>

</head> 
<body> 

<div data-role="page" data-theme="d">

	<div data-role="header" data-theme="a">
		<h1><img src="Icono_H2M.png" alt="" ></h1>
	</div><!-- /header -->

	<div data-role="content" data-theme="d" >	
		
    <p>An email with reset password link has been send to your registered emailID <?php echo $IdMEDEmail ?></p>
	
	<p> It is important to remember that in our endeavour to provide best security,Health2me use one way password hashing. That means password cannot be retrieved by anyone.</p>
	<p> If user forgets the password, it could only be reset using one-step verification process</p>
	
	</div>
	
	<div data-role="footer" data-id="foo1" data-position="fixed">
	<div data-role="navbar">
		<ul>
			<li><a href="signIn.html" data-ajax="false">Login</a></li>								
		</ul> 
			</div><!-- /navbar -->
		</div><!-- /footer -->
	</div><!-- /page -->
</body>
</html>