<?php
 require("../environment_detail.php");
 require("../PasswordHash.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 
 $link = mysqli_connect("$dbhost", "$dbuser", "$dbpass","$dbname");

 if (!$link) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
 }
 
 $name = $_POST['name'];
 $surname = $_POST['surname'];
 $email = $_POST['email'];
 $dob = $_POST['dob'];
 $password = $_POST['password'];
 $gender=$_POST['gender'];
 
 //Check if email already exists
 $stmt = mysqli_prepare($link, "SELECT * FROM DOCTORS where IdMEDEmail=?");
 mysqli_stmt_bind_param($stmt, "s", $email);
	
 mysqli_stmt_execute($stmt);
	
 mysqli_stmt_store_result($stmt);
	
 $count = mysqli_stmt_num_rows($stmt);

list($yy, $mm, $dd) = explode('-', $dob);
//get age from date or birthdate
$age = (date("md", date("U", mktime(0, 0, 0, $mm, $dd, $yy))) > date("md")
? ((date("Y") - $yy) - 1)
: (date("Y") - $yy));

 $success=0;
 if($count==0 && $age >= 18)
 {
 
	//If does not exist, create account
	$confirm_code=md5(uniqid(rand()));
	list($yy, $mm, $dd) = explode('-', $dob);
	$OrderOB='0';
	$verificado='0';
	$IdMEDFIXED = $yy.$mm.$dd.$gender.$OrderOB;
	$IdMEDFIXEDNAME = strtolower($name).'.'.strtolower($surname);
	
	$hashresult = explode(":", create_hash($password));
	$IdMEDRESERV= $hashresult[3];
	$additional_string=$hashresult[2];
	$previlege = 0;
	
	$query = "INSERT INTO DOCTORS(IdMEDFIXED,IdMEDFIXEDNAME,Gender,OrderOB,Name,Surname,IdMEDRESERV,IdMEDEmail,Verificado,token,salt,previlege) values(?,?,?,?,?,?,?,?,?,?,?,?)";
	$insertstmt = mysqli_prepare($link, $query);
	
	
	mysqli_stmt_bind_param($insertstmt,"ssiisssssiss",$IdMEDFIXED,$IdMEDFIXEDNAME,$gender,$OrderOB,$name,$surname,$IdMEDRESERV,$email,$verificado,$confirm_code,$additional_string,$previlege);
	mysqli_stmt_execute($insertstmt);
	sendEmail($email,$IdMEDFIXED,$IdMEDFIXEDNAME,$confirm_code);
	$success=1;
	
	
 }
 else
 {
	//Return error
	$success=0;
 }
 
 $post_data = array('success' => $success);
 $post_data = json_encode($post_data);
	
 echo $post_data;			
 
 
 
 
function sendEmail($IdMEDEmail,$IdMEDFIXED,$IdMEDFIXEDNAME,$confirm_code)
{
	require_once '../MBCaller.php';

	require_once '../lib/swift_required.php';

	$aQuien = $IdMEDEmail;
	$Tema = 'Inmers Account Confirmation';

	$adicional ='<p>Please follow the link to verify your identity and complete the sign up process.</p><p>Your email: <span><h3>'.$IdMEDEmail.'</h3></span><p><p>Your Number id: <span><h3>'.$IdMEDFIXED.'</h3></span><p><p>Your Name id: <span><h3>'.$IdMEDFIXEDNAME.'</h3></span></p><p>Please use your Name id for sign in purposes.</p>';

	$Sobre = $Tema;
	$Body = '<p>Thanks for your interest in our services!</p><p>Please click on the following link to confirm your new Inmers account.</p><p><?php $domain?>/ConfirmaUser.php?token='.$confirm_code.'</p>'.$adicional;


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


} 

function getMonth($str)
{
    if(strcmp($str, "Jan") == 0)
    {
        return "1";
    }
    else if(strcmp($str, "Feb") == 0)
    {
        return "2";
    }
    else if(strcmp($str, "Mar") == 0)
    {
        return "3";
    }
    else if(strcmp($str, "Apr") == 0)
    {
        return "4";
    }
    else if(strcmp($str, "May") == 0)
    {
        return "5";
    }
    else if(strcmp($str, "Jun") == 0)
    {
        return "6";
    }
    else if(strcmp($str, "Jul") == 0)
    {
        return "7";
    }
    else if(strcmp($str, "Aug") == 0)
    {
        return "8";
    }
    else if(strcmp($str, "Sep") == 0)
    {
        return "9";
    }
    else if(strcmp($str, "Oct") == 0)
    {
        return "10";
    }
    else if(strcmp($str, "Nov") == 0)
    {
        return "11";
    }
    else if(strcmp($str, "Dec") == 0)
    {
        return "12";
    }
}



?>