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

/*
$IdMEDFIXED = $_GET['VIdUsFIXED'];
$IdMEDFIXEDNAME = $_GET['VIdUsFIXEDNAME'];
$Gender = $_GET['Gender'];
$OrderOB = $_GET['OrderOB'];
$Name = $_GET['Vname'];
$Surname = $_GET['Vsurname'];
//$IdMEDRESERV = $_GET['Password'];
$IdMEDEmail = $_GET['email'];
$phone = $_GET['phone'];*/


$IdUsFIXED = $_POST['VIdUSFIXED'];
$IdUsFIXEDNAME = $_POST['VIdUSFIXEDNAME'];
$Gender = $_POST['Gender'];
//$OrderOB = $_POST['OrderOB'];
$Name = $_POST['Vname'];
$Surname = $_POST['Vsurname'];
//$IdMEDRESERV = $_POST['Password'];
$IdUsEmail = $_POST['email'];
$phone = $_POST['tel'];

$queModo = 1;

$confirm_code=md5(uniqid(rand()));

//Changes for adding encryption
$hashresult = explode(":", create_hash($_POST['Password']));
$IdUsRESERV= $hashresult[3];
$additional_string=$hashresult[2];

//$IdMEDRESERV=create_hash($_POST['Password']);
/*
echo $hashresult;
echo "<br>";
echo $IdMEDRESERV;
echo "<br>";
echo $additional_string;*/

if ($queModo == '1')
{
	$q = mysql_query("INSERT INTO Usuarios SET IdUsFIXED = '$IdUsFIXED', IdUsFIXEDNAME = '$IdUsFIXEDNAME', Sexo = '$Gender', Name = '$Name', Surname = '$Surname' , IdUsRESERV = '$IdUsRESERV', telefono = '$phone', email = '$IdUsEmail' , Verificado = '1' , confirmcode='$confirm_code',salt='$additional_string'");   
}
else
{
	$q = mysql_query("INSERT INTO Usuarios SET IdUsFIXED = '$IdUsFIXED', IdUsFIXEDNAME = '$IdUsFIXEDNAME', Sexo = '$Gender', Name = '$Name', Surname = '$Surname' , IdUsRESERV = '$IdUsRESERV', telefono = '$phone', email = '$IdUsEmail' , Verificado = '1' , confirmcode='$confirm_code',salt='$additional_string'");   
}

// LLAMADA PARA VERIFICAR EL TELEFONO DEL PACIENTE: ****************************************************

require_once '../MBCaller.php';
//$a = SendCallVERIF (34608754342);

// ENVÍA EL EMAIL AL PACIENTE: ****************************************************
require_once '../lib/swift_required.php';

$aQuien = $IdUsEmail;
$Tema = 'Inmers Account Confirmation';

$adicional ='<p>Your email: <span><h3>'.$IdUsEmail.'</h3></span><p><p>Your Number id: <span><h3>'.$IdUsFIXED.'</h3></span><p><p>Your Name id: <span><h3>'.$IdUsFIXEDNAME.'</h3></span></p><p>Please use your Name id for sign in purposes.</p>';

$Sobre = $Tema;
$Body = '<p>Thanks for your interest in our services!</p><p>Please click <a href="'.$domain.'/mobapp/ConfirmMobileSignup.php?token='.$confirm_code.'">here</a> to verify your account.</p>'.$adicional;


$transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
  ->setUsername('newmedibank@gmail.com')
  ->setPassword('ardiLLA98');

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
		
    <p>Thank You for Signing up with Health2me. A verification email has been sent to your registered emailID <?php echo $IdUsEmail ?></p>
	
	<p> It is important to verify your account before you start using Health2me. Please check your email and verify the account</p>
	
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