<?php
//error_reporting(E_ALL);
ini_set('display_errors', '1');
require("environment_detailForLogin.php");
require("PasswordHash.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];
$hardcode = $env_var_db['hardcode'];

//$allow = array('107.21.7.32', '54.86.146.59');

//if(!in_array($_SERVER['REMOTE_ADDR'], $allow)){
//die("This is a not a dedicated AARP IP Address!");
//}



// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		


$name = $_POST['name'];
$surname = $_POST['surname'];
$dob =  $_POST['dob'];
$gender = $_POST['gender'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$security = $_POST['security'];
$facility = $_POST['facility'];
$agent = $_POST['agent'];
$plan = $_POST['plan'];



if($facility == 0){
$fac_id = "HTI-COL";
}elseif($facility == 1){
$fac_id = "HTI-SPA";
}elseif($facility == 2){
$fac_id = "HTI-RIVA";
}elseif($facility == 3){
$fac_id = "HTI-24X7";
}elseif($facility == 4){
$fac_id = "HTI-CR";
}elseif($facility == 5){
$fac_id = "HTI-USA-ENG";
}elseif($facility == 6){
$fac_id = "HTI-USA-SPAN";
}

$result = $con->prepare("SELECT salt, password FROM agents WHERE username = ?");
$result->bindValue(1, $agent, PDO::PARAM_STR);
$result->execute();

  $row = $result->fetch(PDO::FETCH_ASSOC);
  
  $blowfish_pre = '$2a$12$';
  $blowfish_end = '$';
  
  $hashed_pass = crypt($security, $blowfish_pre . $row['salt'] . $blowfish_end);

if($hashed_pass != $row['password']){
die("You have not entered the correct security code to add patients. Please contact Health2.me support.");
}

$counter1 = 0;

    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
        
		if (preg_match($regex, $email)) {
//     echo $email . " is a valid email. We can accept it.</br>";
	 $valid_email = 1;
} else { 
//     echo $email . " is an invalid email. Please try again.</br>";
	 $valid_email = 0;
} 


    $numbersOnly = preg_replace('/[^0-9,]|,[0-9]*$/','',$phone);
    $numberOfDigits = strlen($numbersOnly);
    if ($numberOfDigits == 7 or $numberOfDigits == 10 or $numberOfDigits == 11 or $numberOfDigits > 11) {
//        echo $numbersOnly."</br>";
		$valid_phone = 1;
    } else {
 //       echo 'Invalid Phone Number</br>';
		$valid_phone = 0;
    }




/////////////////////////////////////////////////////////////////////////////////////////////

if ($_POST['gender'] == 1) $gender=1; else $gender=0;

$newnombre = strtolower (str_replace(' ', '', $phone));
$newapellidos = strtolower (str_replace(' ', '', $_POST['surname']));


$IdUsFIXED = substr($_POST['dob'],0,4).substr($_POST['dob'],5,2).substr($_POST['dob'],8,2).$gender.'0';
$IdUsFIXEDNAME =$name.'.'. $surname;


$Users['FIXEDNAME'][$counter1]= $IdUsFIXEDNAME;         
$Users['FIXED'][$counter1]= $IdUsFIXED;         
$Users['NAME'][$counter1]= $name;         
$Users['SURNAME'][$counter1]= $surname;         
$Users['PHONE'][$counter1]= $phone;         
$Users['EMAIL'][$counter1]= $email;         
$Users['DOB'][$counter1]= $dob;         
$Users['GENDER'][$counter1]= $gender;         

$counter1++;



// Check IDs in H2M User Database
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];


$counter2=0;
$cadena = '';

$n=0;
while ($n < $counter1)
{
	$maxHit[$n] = 0;
	$minHit[$n] = 100;
	//$result = mysql_query("SELECT Identif,Name,Surname,email,telefono,IdUsFIXED,IdUsFIXEDNAME,IdUsRESERV,salt,pin_hash FROM usuarios"); // WHERE IdPatient = '$UserID'");
	$found[$n] = 0;
	$compfixed[$n] = 0;
	$compstring[$n]='';
	$result = $con->prepare("SELECT * FROM usuarios"); // WHERE IdPatient = '$UserID'");
	$result->execute();
	
	while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
		similar_text($Users['FIXEDNAME'][$n], $row['IdUsFIXEDNAME'], $percent); 
		if ($percent > $maxHit[$n])  { 
			$maxHit[$n] = $percent; 
			$hitname = $row['IdUsFIXEDNAME'];
		}	
		$percent2 = 0;	
		if ($maxHit[$n]>95)
		{
			// Check numeric IdUsFIXED
			similar_text($Users['FIXED'][$n], $row['IdUsFIXED'], $percent2); 
			if ($percent2 > 90) 
			{
				//echo ' MATCH  --'.$percent2.'--'.$Users['FIXED'][$n].' **  '.$row['IdUsFIXED'].'     -//    ';
				$found[$n] = 1;
				$compfixed[$n]=$percent2;
				$compstring[$n]=$row['IdUsFIXED'];
			}
		}
	}

	//$lev = levenshtein($Users['FIXEDNAME'][$n], $row['IdUsFIXEDNAME']);
	if ($found[$n] == 0)
	{
		/*
		echo ' WILL ADD '.$Users['FIXEDNAME'][$n].'                       Max Hit of '.$Users['FIXEDNAME'][$n].' IS : '.number_format($maxHit[$n], 2).' (Found in:'.$hitname.')';
		echo '</br>';
		$emailADD = '';
		*/		
	}		
	else
	{
		/*
		echo ' ***** ALREADY EXISTS ********** '.$Users['FIXEDNAME'][$n].'                       Max Hit of '.$Users['FIXEDNAME'][$n].' IS : '.number_format($maxHit[$n], 2).' (Found in:'.$hitname.')';
		echo '</br>';
		echo ' ***** ************** ********** FIXED CODE SIMILARITY = '.$compfixed[$n].'    SIMILAR FIXED FOUND = '.$compstring[$n];
		echo '</br>';
		*/
	}

	if ($n>0) $cadena.=',';    

	$confirm_code=md5(uniqid(rand()));
	
	$input = array("either", "eleven","else", "elsewhere", "empty", "enough", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "fifteen", "fify", "fill", "find", "fire", "first", "five",  "former", "formerly", "forty", "found", "four", "from", "front", "full", "further",  "give", "hasnt", "have");
	$input2 = array("Madrid","London","Paris","Rome","Tokio","Dallas","Portland","Chicago","Rabat","Jerusalem","Amman","Cairo","Athens","Oslo","Bogota","Ottawa","Beijing");

	$autopassword = substr($input[array_rand($input)],0,4).substr($input2[array_rand($input2)],0,4);
	$autopin = rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);
	$pin = $autopin;
	$pass = $autopassword;
	
	//$hashresult = explode(":", create_hash($_POST['Password']));
	$hashresult = explode(":", create_hash($autopassword));
	$IdUsRESERV= $hashresult[3];
	$additional_string=$hashresult[2];
	$salt = $additional_string;
	$pin_hash = create_hash($autopin);

	$cadena.='
	{
        "ADD":"'.$found[$n].'",
        "SimName":"'.$maxHit[$n].'",
        "SimDOB":"'.$compfixed[$n].'",
        "name":"'.$Users['NAME'][$n].'",
		"surname":"'.$Users['SURNAME'][$n].'",
		"dob":"'.$Users['DOB'][$n].'",
		"gender":"'.$Users['GENDER'][$n].'",
        "IdUSFIXED":"'.$Users['FIXED'][$n].'",
        "IdUSFIXEDNAME":"'.$Users['FIXEDNAME'][$n].'",
        "PASS":"'.$autopassword.'",
        "PIN":"'.$autopin.'",
        "IdUSRESERV":"'.$IdUsRESERV.'",
        "salt":"'.$additional_string.'",
        "pin_hash":"'.$pin_hash.'",
        "email":"'.$Users['EMAIL'][$n].'",
		"phone":"'.$Users['PHONE'][$n].'"
    }';  


	$n++;
}



$qA = $con->prepare("SELECT * FROM usuarios WHERE telefono=?");
$qA->bindValue(1, $phone, PDO::PARAM_INT);
$qA->execute();

$num_rows = $qA->rowCount();
if ($num_rows > 0) {
	$proceed = 1;
	$logvalue_phone = 1;
}else{
$logvalue_phone = 0;
}

$qB = $con->prepare("SELECT * FROM usuarios WHERE email='$email'");
$qB->bindValue(1, $email, PDO::PARAM_STR);
$qB->execute();

$num_rows = $qB->rowCount();
if ($num_rows > 0) {
	$proceed = 1;
	$logvalue_email = 1;
}else{
$logvalue_email = 0;
}

if($plan == 1){
$plan = 'FAMILY';
}else{
$plan = 'PREMIUM';
}

$encode = json_encode($cadena);
//echo '{"items":['.($cadena).']}'; 
//var_dump($cadena);

$signupdate = date("Y-m-d H:i:s");

if($valid_email == 1 && $valid_phone == 1 && $logvalue_phone == 0 && $logvalue_email == 0 && $_POST['name'] != "" && $_POST['surname'] != "" && $_POST['dob'] != "" && $_POST['gender'] != "" && $_POST['phone'] != "" && $_POST['security'] != "" && $_POST['plan'] != "" && $_POST['facility'] != ""){
	$q = $con->prepare("INSERT INTO usuarios SET Name = ?, Surname = ?, FNac = ?, Sexo = ?, email = ?, telefono = ?, IdUsFIXED = ?, IdUsFIXEDNAME = ?, Alias = ?, IdUsRESERV = ?, salt = ?, pin_hash=? , GrantAccess=?, Password=?, signUpDate=?, plan = ?, ownerAcc = 'Owner'"); //, Password='$pass'
	$q->bindValue(1, $name, PDO::PARAM_STR);
	$q->bindValue(2, $surname, PDO::PARAM_STR);
	$q->bindValue(3, $dob, PDO::PARAM_STR);
	$q->bindValue(4, $gender, PDO::PARAM_INT);
	$q->bindValue(5, $email, PDO::PARAM_STR);
	$q->bindValue(6, $numbersOnly, PDO::PARAM_INT);
	$q->bindValue(7, $IdUsFIXED, PDO::PARAM_INT);
	$q->bindValue(8, $IdUsFIXEDNAME, PDO::PARAM_STR);
	$q->bindValue(9, $IdUsFIXEDNAME, PDO::PARAM_STR);
	$q->bindValue(10, $IdUsRESERV, PDO::PARAM_STR);
	$q->bindValue(11, $salt, PDO::PARAM_STR);
	$q->bindValue(12, $pin_hash, PDO::PARAM_STR);
	$q->bindValue(13, $fac_id, PDO::PARAM_STR);
	$q->bindValue(14, $pass, PDO::PARAM_STR);
	$q->bindValue(15, $signupdate, PDO::PARAM_STR);
	$q->bindValue(16, $plan, PDO::PARAM_STR);
	$q->execute();
	
	$data_success = 1;
$id = $con->lastInsertId(); 
$account_owner = $id;

	$hold_year = substr($IdUsFIXED, 0 , 4);
	$hold_month = substr($IdUsFIXED, 4 , 2);
	$hold_day = substr($IdUsFIXED, 6 , 2);
	
	$date_string = $hold_year."-".$hold_month."-".$hold_day;
	
	$b = $con->prepare("INSERT INTO basicemrdata SET DOB = ?, IdPatient = ?");
	$b->bindValue(1, $date_string, PDO::PARAM_STR);
	$b->bindValue(2, $id, PDO::PARAM_INT);
	$b->execute();
	


if ($q){
	$check_num = $con->prepare("SELECT * FROM agents WHERE username=?");
	$check_num->bindValue(1, $agent, PDO::PARAM_STR);
	$check_num->execute();
	
	$rownum = $check_num->fetch(PDO::FETCH_ASSOC);
	
	$total_registered = $rownum['t_registered'] + 1;
	$upd_agents = $con->prepare("UPDATE agents SET t_registered=? WHERE idagents=?");
	$upd_agents->bindValue(1, $total_registered, PDO::PARAM_INT);
	$upd_agents->bindValue(2, $rownum['idagents'], PDO::PARAM_INT);
	$upd_agents->execute();
	
	$ins_agents = $con->prepare("INSERT INTO agentslinkusers SET agent=?, user=?");
	$ins_agents->bindValue(1, $rownum['idagents'], PDO::PARAM_INT);
	$ins_agents->bindValue(2, $id, PDO::PARAM_INT);
	$ins_agents->execute();
	
		$value = 'OK'; 	
		
		$logvalue='User added correctly'.'  P='.$pass.'  PIN='.$pin.' Reserv:'.$IdUsRESERV;
		//SEND SMS TEXT MESSAGE
		require_once "Services/Twilio.php";		     
		$AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
		$AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
		// Instantiate a new Twilio Rest Client
		$client = new Services_Twilio($AccountSid, $AuthToken);
		/* Your Twilio Number or Outgoing Caller ID */
		$from = '+19034018888'; 
		$to= '+'.$numbersOnly; 
		$body = 'Bienvenido a Llama al Doctor, '.$name.'. Su usuario es: '.$email.' (Password:'.$pass.' ). Su PIN de seguridad es: '.$pin;
		if($fac_id == "HTI-USA-ENG"){
			$body = 'Welcome to Health2Me, '.$name.'. Your user is: '.$email.' (Password:'.$pass.' ). Your security PIN is: '.$pin;
		}
		if($fac_id != 'HTI-RIVA'){		
			try{
			$client->account->sms_messages->create($from, $to, $body);
			}catch(Exception $e){
			echo "Twilio could not text message number.  Number is most likely incorrect.".$e;
			}
		}
		//echo "Sent message to $name";
		//SEND SMS TEXT MESSAGE

		// SEND EMAIL TO NEW USER:
		require_once 'lib/swift_required.php';
		
		$NamePatient = $name.' '.$surname;
		  
		$aQuien = $email;
		$Sobre = 'Bienvenido a LLama Al Doctor';
		
		$FromText = 'Llama Al Doctor';

		$ContenidoAdic='';
		
		if($fac_id == "HTI-RIVA"){
		$Sobre = 'Welcome to RivaCare!';
		$FromText = 'RivaCare';
		$Content = file_get_contents($hardcode.'templates/ExternalAddRiva.html');
		}elseif($fac_id == "HTI-USA-ENG"){
		$Sobre = 'Welcome to Health2Me!';
		$FromText = 'Health2Me';
		$Content = file_get_contents($hardcode.'templates/ExternalAdd-en.html');
		}else{
		$Content = file_get_contents($hardcode.'templates/ExternalAdd.html');
		}
		
		$Var1 = $name.' '.$surname;
		$Var2 = '';
		$Var3 = $pin;
		$Var4 = $pass;
		$Var5 = $email;
		$Var6 = $domain;
		$Var7 = '';
		$Var8 = '';
		$Var9 = '';
		$Var10 = '';
		$Var11 = '';
		$Var12 = '';
		$Var13 = '';
		$Var14 = '';
		$Var15 = '';
		$Content = str_replace("**Var1**",$Var1,$Content);
		$Content = str_replace("**Var2**",$Var2,$Content);
		$Content = str_replace("**Var3**",$Var3,$Content);
		$Content = str_replace("**Var4**",$Var4,$Content);
		$Content = str_replace("**Var5**",$Var5,$Content);
		$Content = str_replace("**Var6**",$Var6,$Content);
		$Content = str_replace("**Var7**",$Var7,$Content);
		$Content = str_replace("**Var8**",$Var8,$Content);
		$Content = str_replace("**Var9**",$Var9,$Content);
		$Content = str_replace("**Var10**",$Var10,$Content);
		$Content = str_replace("**Var11**",$Var11,$Content);
		$Content = str_replace("**Var12**",$Var12,$Content);
		$Content = str_replace("**Var13**",$Var13,$Content);
		$Content = str_replace("**Var14**",$Var14,$Content);
		$Content = str_replace("**Var15**",$Var15,$Content);
		    
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
		// SEND EMAIL TO NEW USER ******************************


		
	} else{
		$value = 'ERROR';
		$logvalue='MySQL insertion error';
	}

$q2 = $con->prepare("INSERT INTO ext_signup_log SET userid = ?,code = ?, date = NOW(), logvalue=?, username=?,salt=?, reserv=?,pin_hash=?  ");   
$q2->bindValue(1, $id, PDO::PARAM_INT);
$q2->bindValue(2, $value, PDO::PARAM_STR);
$q2->bindValue(3, $logvalue, PDO::PARAM_STR);
$q2->bindValue(4, $IdUsFIXEDNAME, PDO::PARAM_STR);
$q2->bindValue(5, $salt, PDO::PARAM_STR);
$q2->bindValue(6, $IdUsRESERV, PDO::PARAM_STR);
$q2->bindValue(7, $pin_hash, PDO::PARAM_STR);
$q2->execute();

}else{
$data_success = 0;
}
if($valid_email == 0){
$json_return = '';
$json_return.='
	{
        "CODE":"101",
        "ERROR":"Email is not valid."
    }';  
	echo $json_return;
	}
	
	if($valid_phone == 0){
$json_return = '';
$json_return.='
	{
        "CODE":"102",
        "ERROR":"Phone number is not valid."
    }';  
	echo $json_return;
	}
	
	if($logvalue_phone == 1){
$json_return = '';
$json_return.='
	{
        "CODE":"201",
        "ERROR":"Candidate phone is already known."
    }';  
	echo $json_return;
	}
	
	if($logvalue_email == 1){
$json_return = '';
$json_return.='
	{
        "CODE":"202",
        "ERROR":"Candidate email is already known."
    }';  
	echo $json_return;
	}
	
	if($data_success == 1){
$json_return = '';
$json_return.='
	{
        "CODE":"1",
        "SUCCESS":"Candidate has been successfully added to the system."
    }';  
	echo $json_return;
	}
	
	if($_POST['name'] == ""){
$json_return = '';
$json_return.='
	{
        "CODE":"301",
        "MISSING":"First name is required."
    }';  
	echo $json_return;
	}
	
	if($_POST['surname'] == ""){
$json_return = '';
$json_return.='
	{
        "CODE":"302",
        "MISSING":"Surname is required."
    }';  
	echo $json_return;
	}
	
	if($_POST['dob'] == ""){
$json_return = '';
$json_return.='
	{
        "CODE":"303",
        "MISSING":"Date of birth is required."
    }';  
	echo $json_return;
	}
	
	if($_POST['gender'] == ""){
$json_return = '';
$json_return.='
	{
        "CODE":"304",
        "MISSING":"Gender is required."
    }';  
	echo $json_return;
	}
	
	if($_POST['phone'] == ""){
$json_return = '';
$json_return.='
	{
        "CODE":"305",
        "MISSING":"Phone number is required."
    }';  
	echo $json_return;
	}
	
	if($_POST['security'] == ""){
$json_return = '';
$json_return.='
	{
        "CODE":"306",
        "MISSING":"Security code is required."
    }';  
	echo $json_return;
	}
	
	if($_POST['agent'] == ""){
$json_return = '';
$json_return.='
	{
        "CODE":"307",
        "MISSING":"Agent ID is required."
    }';  
	echo $json_return;
	}
	
	if($_POST['facility'] == ""){
$json_return = '';
$json_return.='
	{
        "CODE":"308",
        "MISSING":"Facility location is required."
    }';  
	echo $json_return;
	}
	
	if($_POST['plan'] == ""){
$json_return = '';
$json_return.='
	{
        "CODE":"309",
        "MISSING":"Payment plan is required."
    }';  
	echo $json_return;
	}
	
		////////////////////////////////////////////////////////////////////THIS CREATES FAMILY PLAN////////////////////////////////////////////////////////////////////////////////////////////////////////
	if(isset($_POST['family_count']) && isset($account_owner)){
$number_infamily = $_POST['family_count'];

for($z = 0; $z<= $number_infamily; $z++){
echo '---==='.$z.':COUNT===---';
${'name'.$z} = $_POST['name'.$z];
${'surname'.$z} = $_POST['surname'.$z];
${'dob'.$z} = $_POST['dob'.$z];
${'gender'.$z} = $_POST['gender'.$z];
${'phone'.$z} = $_POST['phone'.$z];
${'email'.$z} = $_POST['email'.$z];
${'relationship'.$z} = $_POST['relate'.$z];

if($facility == 0){
$fac_id = "HTI-COL";
}elseif($facility == 1){
$fac_id = "HTI-SPA";
}

$result = $con->prepare("SELECT salt, password FROM agents WHERE username = ?");
$result->bindValue(1, $agent, PDO::PARAM_STR);
$result->execute();

  $row = $result->fetch(PDO::FETCH_ASSOC);
  
  $blowfish_pre = '$2a$12$';
  $blowfish_end = '$';
  
  $hashed_pass = crypt($security, $blowfish_pre . $row['salt'] . $blowfish_end);

if($hashed_pass != $row['password']){
die("You have not entered the correct security code to add patients. Please contact Health2.me support.");
}

$counter1 = 0;

    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
        
		if (preg_match($regex, ${'email'.$z})) {
//     echo $email . " is a valid email. We can accept it.</br>";
	 $valid_email = 1;
} else { 
//     echo $email . " is an invalid email. Please try again.</br>";
	 $valid_email = 0;
} 


    $numbersOnly = preg_replace('/[^0-9,]|,[0-9]*$/','',${'phone'.$z});
    $numberOfDigits = strlen($numbersOnly);
    if ($numberOfDigits == 7 or $numberOfDigits == 10 or $numberOfDigits == 11 or $numberOfDigits > 11) {
//        echo $numbersOnly."</br>";
		$valid_phone = 1;
    } else {
 //       echo 'Invalid Phone Number</br>';
		$valid_phone = 0;
    }




/////////////////////////////////////////////////////////////////////////////////////////////

if ($_POST['gender'.$z] == 1) $gender=1; else $gender=0;

$newnombre = strtolower (str_replace(' ', '', ${'phone'.$z}));
$newapellidos = strtolower (str_replace(' ', '', $_POST['surname'.$z]));


$IdUsFIXED = substr($_POST['dob'.$z],0,4).substr($_POST['dob'.$z],5,2).substr($_POST['dob'.$z],8,2).$gender.'0';
$IdUsFIXEDNAME =${'name'.$z}.'.'. ${'surname'.$z};


$Users['FIXEDNAME'][$counter1]= $IdUsFIXEDNAME;         
$Users['FIXED'][$counter1]= $IdUsFIXED;         
$Users['NAME'][$counter1]= ${'name'.$z};         
$Users['SURNAME'][$counter1]= ${'surname'.$z};         
$Users['PHONE'][$counter1]= ${'phone'.$z};         
$Users['EMAIL'][$counter1]= ${'email'.$z};         
$Users['DOB'][$counter1]= ${'dob'.$z};         
$Users['GENDER'][$counter1]= ${'gender'.$z};         

$counter1++;



// Check IDs in H2M User Database
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];


$counter2=0;
$cadena = '';

$n=0;
while ($n < $counter1)
{
	$maxHit[$n] = 0;
	$minHit[$n] = 100;
	//$result = mysql_query("SELECT Identif,Name,Surname,email,telefono,IdUsFIXED,IdUsFIXEDNAME,IdUsRESERV,salt,pin_hash FROM usuarios"); // WHERE IdPatient = '$UserID'");
	$found[$n] = 0;
	$compfixed[$n] = 0;
	$compstring[$n]='';
	$result = $con->prepare("SELECT * FROM usuarios"); // WHERE IdPatient = '$UserID'");
	$result->execute();
	
	while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
		similar_text($Users['FIXEDNAME'][$n], $row['IdUsFIXEDNAME'], $percent); 
		if ($percent > $maxHit[$n])  { 
			$maxHit[$n] = $percent; 
			$hitname = $row['IdUsFIXEDNAME'];
		}	
		$percent2 = 0;	
		if ($maxHit[$n]>95)
		{
			// Check numeric IdUsFIXED
			similar_text($Users['FIXED'][$n], $row['IdUsFIXED'], $percent2); 
			if ($percent2 > 90) 
			{
				//echo ' MATCH  --'.$percent2.'--'.$Users['FIXED'][$n].' **  '.$row['IdUsFIXED'].'     -//    ';
				$found[$n] = 1;
				$compfixed[$n]=$percent2;
				$compstring[$n]=$row['IdUsFIXED'];
			}
		}
	}

	//$lev = levenshtein($Users['FIXEDNAME'][$n], $row['IdUsFIXEDNAME']);
	if ($found[$n] == 0)
	{
		/*
		echo ' WILL ADD '.$Users['FIXEDNAME'][$n].'                       Max Hit of '.$Users['FIXEDNAME'][$n].' IS : '.number_format($maxHit[$n], 2).' (Found in:'.$hitname.')';
		echo '</br>';
		$emailADD = '';
		*/		
	}		
	else
	{
		/*
		echo ' ***** ALREADY EXISTS ********** '.$Users['FIXEDNAME'][$n].'                       Max Hit of '.$Users['FIXEDNAME'][$n].' IS : '.number_format($maxHit[$n], 2).' (Found in:'.$hitname.')';
		echo '</br>';
		echo ' ***** ************** ********** FIXED CODE SIMILARITY = '.$compfixed[$n].'    SIMILAR FIXED FOUND = '.$compstring[$n];
		echo '</br>';
		*/
	}

	if ($n>0) $cadena.=',';    

	$confirm_code=md5(uniqid(rand()));
	
	$input = array("either", "eleven","else", "elsewhere", "empty", "enough", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "fifteen", "fify", "fill", "find", "fire", "first", "five",  "former", "formerly", "forty", "found", "four", "from", "front", "full", "further",  "give", "hasnt", "have");
	$input2 = array("Madrid","London","Paris","Rome","Tokio","Dallas","Portland","Chicago","Rabat","Jerusalem","Amman","Cairo","Athens","Oslo","Bogota","Ottawa","Beijing");

	$autopassword = substr($input[array_rand($input)],0,4).substr($input2[array_rand($input2)],0,4);
	$autopin = rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);
	$pin = $autopin;
	$pass = $autopassword;
	
	//$hashresult = explode(":", create_hash($_POST['Password']));
	$hashresult = explode(":", create_hash($autopassword));
	$IdUsRESERV= $hashresult[3];
	$additional_string=$hashresult[2];
	$salt = $additional_string;
	$pin_hash = create_hash($autopin);

	$cadena.='
	{
        "ADD":"'.$found[$n].'",
        "SimName":"'.$maxHit[$n].'",
        "SimDOB":"'.$compfixed[$n].'",
        "name":"'.$Users['NAME'][$n].'",
		"surname":"'.$Users['SURNAME'][$n].'",
		"dob":"'.$Users['DOB'][$n].'",
		"gender":"'.$Users['GENDER'][$n].'",
        "IdUSFIXED":"'.$Users['FIXED'][$n].'",
        "IdUSFIXEDNAME":"'.$Users['FIXEDNAME'][$n].'",
        "PASS":"'.$autopassword.'",
        "PIN":"'.$autopin.'",
        "IdUSRESERV":"'.$IdUsRESERV.'",
        "salt":"'.$additional_string.'",
        "pin_hash":"'.$pin_hash.'",
        "email":"'.$Users['EMAIL'][$n].'",
		"phone":"'.$Users['PHONE'][$n].'"
    }';  


	$n++;
}



$qA = $con->prepare("SELECT * FROM usuarios WHERE telefono=?");
$qA->bindValue(1, ${'phone'.$z}, PDO::PARAM_INT);
$qA->execute();

$num_rows = $qA->rowCount();
if ($num_rows > 0) {
	$proceed = 1;
	$logvalue_phone = 1;
}else{
$logvalue_phone = 0;
}

$qB = $con->prepare("SELECT * FROM usuarios WHERE email=?");
$qB->bindValue(1, ${'email'.$z}, PDO::PARAM_STR);
$qB->execute();

$num_rows = $qB->rowCount();
if ($num_rows > 0) {
	$proceed = 1;
	$logvalue_email = 1;
}else{
$logvalue_email = 0;
}

if($plan == 1){
$plan = 'FAMILY';
}else{
$plan = 'PREMIUM';
}

$encode = json_encode($cadena);
//echo '{"items":['.($cadena).']}'; 
//var_dump($cadena);

$signupdate = date("Y-m-d H:i:s");

if($valid_email == 1 && $valid_phone == 1 && $logvalue_phone == 0 && $logvalue_email == 0 && $_POST['name'] != "" && $_POST['surname'] != "" && $_POST['dob'] != "" && $_POST['gender'] != "" && $_POST['phone'] != "" && $_POST['security'] != "" && $_POST['plan'] != "" && $_POST['facility'] != ""){
	$q = $con->prepare("INSERT INTO usuarios SET Name = ?, Surname = ?, FNac = ?, Sexo = ?, email = ?, telefono = ?, IdUsFIXED = ?, IdUsFIXEDNAME = ?, Alias = ?, IdUsRESERV = ?, salt = ?, pin_hash=? , GrantAccess=?, Password=?, signUpDate=?, plan = ?, ownerAcc = ?, relationship = ?, subsType='Delegate'"); //, Password='$pass'
	$q->bindValue(1, ${'name'.$z}, PDO::PARAM_STR);
	$q->bindValue(2, ${'surname'.$z}, PDO::PARAM_STR);
	$q->bindValue(3, ${'dob'.$z}, PDO::PARAM_STR);
	$q->bindValue(4, $gender, PDO::PARAM_INT);
	$q->bindValue(5, ${'email'.$z}, PDO::PARAM_STR);
	$q->bindValue(6, $numbersOnly, PDO::PARAM_INT);
	$q->bindValue(7, $IdUsFIXED, PDO::PARAM_INT);
	$q->bindValue(8, $IdUsFIXEDNAME, PDO::PARAM_STR);
	$q->bindValue(9, $IdUsFIXEDNAME, PDO::PARAM_STR);
	$q->bindValue(10, $IdUsRESERV, PDO::PARAM_STR);
	$q->bindValue(11, $salt, PDO::PARAM_STR);
	$q->bindValue(12, $pin_hash, PDO::PARAM_STR);
	$q->bindValue(13, $fac_id, PDO::PARAM_STR);
	$q->bindValue(14, $pass, PDO::PARAM_STR);
	$q->bindValue(15, $signupdate, PDO::PARAM_STR);
	$q->bindValue(16, 'FAMILY', PDO::PARAM_STR);
	$q->bindValue(17, $account_owner, PDO::PARAM_INT);
	$q->bindValue(18, ${'relationship'.$z}, PDO::PARAM_STR);
	$q->execute();
	
	$data_success = 1;
$id = $con->lastInsertId(); 

	$hold_year = substr($IdUsFIXED, 0 , 4);
	$hold_month = substr($IdUsFIXED, 4 , 2);
	$hold_day = substr($IdUsFIXED, 6 , 2);
	
	$date_string = $hold_year."-".$hold_month."-".$hold_day;
	
	$b = $con->prepare("INSERT INTO basicemrdata SET DOB = ?, IdPatient = ?");
	$b->bindValue(1, $date_string, PDO::PARAM_STR);
	$b->bindValue(2, $id, PDO::PARAM_INT);
	$b->execute();
	
	
	if ($q){
	$check_num = $con->prepare("SELECT * FROM agents WHERE username=?");
	$check_num->bindValue(1, $agent, PDO::PARAM_STR);
	$check_num->execute();
	
	$rownum = $check_num->fetch(PDO::FETCH_ASSOC);
	
	$total_registered = $rownum['t_registered'] + 1;
	$upd_agents = $con->prepare("UPDATE agents SET t_registered=? WHERE idagents=?");
	$upd_agents->bindValue(1, $total_registered, PDO::PARAM_INT);
	$upd_agents->bindValue(2, $rownum['idagents'], PDO::PARAM_INT);
	$upd_agents->execute();
	
	$ins_agents = $con->prepare("INSERT INTO agentslinkusers SET agent=?, user=?");
	$ins_agents->bindValue(1, $rownum['idagents'], PDO::PARAM_INT);
	$ins_agents->bindValue(2, $id, PDO::PARAM_INT);
	$ins_agents->execute();
	
		$value = 'OK'; 	
		
		$logvalue='User added correctly'.'  P='.$pass.'  PIN='.$pin.' Reserv:'.$IdUsRESERV;
		//SEND SMS TEXT MESSAGE
		require_once "Services/Twilio.php";		     
		$AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
		$AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
		// Instantiate a new Twilio Rest Client
		$client = new Services_Twilio($AccountSid, $AuthToken);
		/* Your Twilio Number or Outgoing Caller ID */
		$from = '+19034018888'; 
		$to= '+'.$numbersOnly; 
		$body = 'Bienvenido a Llama al Doctor, '.${'name'.$z}.'. Su usuario es: '.${'email'.$z}.' (Password:'.$pass.' ). Su PIN de seguridad es: '.$pin;
		try{
		$client->account->sms_messages->create($from, $to, $body);
		}catch(Exception $e){
		echo "Twilio could not text message number.  Number is most likely incorrect.".$e;
		}
		//echo "Sent message to $name";
		//SEND SMS TEXT MESSAGE

		// SEND EMAIL TO NEW USER:
		require_once 'lib/swift_required.php';
		
		$NamePatient = ${'name'.$z}.' '.${'surname'.$z};
		  
		$aQuien = ${'email'.$z};
		$Sobre = 'Bienvenido a LLama Al Doctor';
		
		$FromText = 'Llama Al Doctor';

		$ContenidoAdic='';
		
		if($fac_id == "HTI-RIVA"){
		$Sobre = 'Welcome to RivaCare!';
		$FromText = 'RivaCare';
		$Content = file_get_contents($hardcode.'templates/ExternalAddRiva.html');
		}else{
		$Content = file_get_contents($hardcode.'templates/ExternalAdd.html');
		}
		
		$Var1 = ${'name'.$z}.' '.${'surname'.$z};
		$Var2 = '';
		$Var3 = $pin;
		$Var4 = $pass;
		$Var5 = ${'email'.$z};
		$Var6 = $domain;
		$Var7 = '';
		$Var8 = '';
		$Var9 = '';
		$Var10 = '';
		$Var11 = '';
		$Var12 = '';
		$Var13 = '';
		$Var14 = '';
		$Var15 = '';
		$Content = str_replace("**Var1**",$Var1,$Content);
		$Content = str_replace("**Var2**",$Var2,$Content);
		$Content = str_replace("**Var3**",$Var3,$Content);
		$Content = str_replace("**Var4**",$Var4,$Content);
		$Content = str_replace("**Var5**",$Var5,$Content);
		$Content = str_replace("**Var6**",$Var6,$Content);
		$Content = str_replace("**Var7**",$Var7,$Content);
		$Content = str_replace("**Var8**",$Var8,$Content);
		$Content = str_replace("**Var9**",$Var9,$Content);
		$Content = str_replace("**Var10**",$Var10,$Content);
		$Content = str_replace("**Var11**",$Var11,$Content);
		$Content = str_replace("**Var12**",$Var12,$Content);
		$Content = str_replace("**Var13**",$Var13,$Content);
		$Content = str_replace("**Var14**",$Var14,$Content);
		$Content = str_replace("**Var15**",$Var15,$Content);
		    
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
		// SEND EMAIL TO NEW USER ******************************


		
	} else{
		$value = 'ERROR';
		$logvalue='MySQL insertion error';
	}

$q2 = $con->prepare("INSERT INTO ext_signup_log SET userid = ?,code = ?, date = NOW(), logvalue=?, username=?,salt=?, reserv=?,pin_hash=?  ");   
$q2->bindValue(1, $id, PDO::PARAM_INT);
$q2->bindValue(2, $value, PDO::PARAM_STR);
$q2->bindValue(3, $logvalue, PDO::PARAM_STR);
$q2->bindValue(4, $IdUsFIXEDNAME, PDO::PARAM_STR);
$q2->bindValue(5, $salt, PDO::PARAM_STR);
$q2->bindValue(6, $IdUsRESERV, PDO::PARAM_STR);
$q2->bindValue(7, $pin_hash, PDO::PARAM_STR);
$q2->execute();

}else{
		$value = 'ERROR';
		$logvalue='MySQL insertion error';
	}



}
if($valid_email == 0){
$json_return = '';
$json_return.='
	{
        "CODE":"101",
        "ERROR":"Email is not valid."
    }';  
	echo $json_return;
	}
	
	if($valid_phone == 0){
$json_return = '';
$json_return.='
	{
        "CODE":"102",
        "ERROR":"Phone number is not valid."
    }';  
	echo $json_return;
	}
	
	if($logvalue_phone == 1){
$json_return = '';
$json_return.='
	{
        "CODE":"201",
        "ERROR":"Candidate phone is already known."
    }';  
	echo $json_return;
	}
	
	if($logvalue_email == 1){
$json_return = '';
$json_return.='
	{
        "CODE":"202",
        "ERROR":"Candidate email is already known."
    }';  
	echo $json_return;
	}
	
	if($data_success == 1){
$json_return = '';
$json_return.='
	{
        "CODE":"1",
        "SUCCESS":"Candidate has been successfully added to the system."
    }';  
	echo $json_return;
	}
	
	if($_POST['name'] == ""){
$json_return = '';
$json_return.='
	{
        "CODE":"301",
        "MISSING":"First name is required."
    }';  
	echo $json_return;
	}
	
	if($_POST['surname'] == ""){
$json_return = '';
$json_return.='
	{
        "CODE":"302",
        "MISSING":"Surname is required."
    }';  
	echo $json_return;
	}
	
	if($_POST['dob'] == ""){
$json_return = '';
$json_return.='
	{
        "CODE":"303",
        "MISSING":"Date of birth is required."
    }';  
	echo $json_return;
	}
	
	if($_POST['gender'] == ""){
$json_return = '';
$json_return.='
	{
        "CODE":"304",
        "MISSING":"Gender is required."
    }';  
	echo $json_return;
	}
	
	if($_POST['phone'] == ""){
$json_return = '';
$json_return.='
	{
        "CODE":"305",
        "MISSING":"Phone number is required."
    }';  
	echo $json_return;
	}
	
	if($_POST['security'] == ""){
$json_return = '';
$json_return.='
	{
        "CODE":"306",
        "MISSING":"Security code is required."
    }';  
	echo $json_return;
	}
	
	if($_POST['agent'] == ""){
$json_return = '';
$json_return.='
	{
        "CODE":"307",
        "MISSING":"Agent ID is required."
    }';  
	echo $json_return;
	}
	
	if($_POST['facility'] == ""){
$json_return = '';
$json_return.='
	{
        "CODE":"308",
        "MISSING":"Facility location is required."
    }';  
	echo $json_return;
	}
	
	if($_POST['plan'] == ""){
$json_return = '';
$json_return.='
	{
        "CODE":"309",
        "MISSING":"Payment plan is required."
    }';  
	echo $json_return;
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
?>
