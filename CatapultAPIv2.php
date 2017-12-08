<?php
require("environment_detail.php");
require("PasswordHash.php");

$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];
$hardcode = $env_var_db['hardcode'];
$local = $env_var_db['local'];

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con){
	die('Could not connect: ' . mysql_error());
	}


//GRAB STRINGIFIED JSON FROM POST
$json = $_POST['json'];

//DECODE STRINGIFIED JSON INTO JSON OBJECT
$decoded = json_decode($_POST['json'], true);

//COUNTER FOR HANDLING MULTIPLE PATIENTS AT ONCE IN A LARGER JSON OBJECT
$p = 0;

//DECLARE ARRAY TO BE RETURNED TO THE API CALLER
$returned_json = array();

//RUN FOREACH LOOP TO LOOP THROUGH EACH PATIENT WITHIN THE JSON OBJECT
foreach($decoded as $patient){
	//DECLARE SLAVE JSON TO BE PASSED TO MASTER JSON THAT IS 'RETURNED'
	$patient_json = array();
	
	$PatientId = $patient['patients']['PatientId'];//0
	$patient_json['PatientId'] = $PatientId;

	$CompanyName = $patient['patients']['CompanyName'];//1
	$patient_json['CompanyName'] = $CompanyName;

	$FirstName = $patient['patients']['FirstName'];//2
	$patient_json['FirstName'] = $FirstName;

	$LastName = $patient['patients']['LastName'];//3
	$patient_json['LastName'] = $LastName;

	$DOB = $patient['patients']['DOB'];//4
	$patient_json['DOB'] = $DOB;

	$SSN = $patient['patients']['SSN'];//5
	$patient_json['DOB'] = $DOB;

	$EmployeeId = $patient['patients']['EmployeeId'];//6
	$patient_json['EmployeeId'] = $EmployeeId;

	$Gender = $patient['patients']['Gender'];//7
	$patient_json['Gender'] = $Gender;

	$LanguageId = $patient['patients']['LanguageId'];//8
	$patient_json['LanguageId'] = $LanguageId;

	$MiddleInitial = $patient['patients']['MiddleInitial'];//9
	$patient_json['MiddleInitial'] = $MiddleInitial;

	$Address1 = $patient['patients']['Address1'];//10
	$patient_json['Address1'] = $Address1;

	$Address2 = $patient['patients']['Address2'];//11
	$patient_json['Address2'] = $Address2;

	$City = $patient['patients']['City'];//12
	$patient_json['City'] = $City;

	$State = $patient['patients']['State'];//13
	$patient_json['State'] = $State;

	$PostalCode = $patient['patients']['PostalCode'];//14
	$patient_json['PostalCode'] = $PostalCode;

	$HomePhone = $patient['patients']['HomePhone'];//15
	$patient_json['HomePhone'] = $HomePhone;

	$CellPhone = $patient['patients']['CellPhone'];//16
	$patient_json['CellPhone'] = $CellPhone;

	$EmailAddress = $patient['patients']['EmailAddress'];//17
	$patient_json['EmailAddress'] = $EmailAddress;

	$InsuranceGroupId = $patient['patients']['InsuranceGroupId'];//18
	$patient_json['InsuranceGroupId'] = $InsuranceGroupId;

	$InsuranceMemberId = $patient['patients']['InsuranceMemberId'];//19
	$patient_json['InsuranceMemberId'] = $InsuranceMemberId;

	$InsurancePlanId = $patient['patients']['InsurancePlanId'];//20
	$patient_json['InsurancePlanId'] = $InsurancePlanId;

	$HispanicLatino = $patient['patients']['HispanicLatino'];//21
	$patient_json['HispanicLatino'] = $HispanicLatino;

	$EthnicityId = $patient['patients']['EthnicityId'];//22
	$patient_json['EthnicityId'] = $EthnicityId;

	$IsActive = $patient['patients']['IsActive'];//23
	$patient_json['IsActive'] = $IsActive;
	
	//VALIDATION
	$valid_email = 0;
	$valid_home = 0;
	$valid_cell = 0;
	
	//FLAGS
	$gender_flag = 0;
	
	//KEYS
	$prim_key = 0;
	
	if($Gender == 'M'){
		$gender_flag = 1;
	}
			
	//STRIP HOME PHONE OF ADDITIONAL CHARACTERS
	$number_stripped = preg_replace('/[^0-9,]|,[0-9]*$/','',$HomePhone);
	
	//REGEX TO DETERMINE IF PHONE NUMBER CONTAINS VALID FORMAT
	$regex = '/(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]‌​)\s*)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)([2-9]1[02-9]‌​|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})/';
	$num_length = strlen($number_stripped);
	
	//CHECKS IF NUMBER HAS A '1' IN FRONT OF NUMBER TO MAKE SURE TWILIO CAN TEXT CORRECTLY(USA NUMBERS ONLY!!!)
	if($num_length == 10){
		$number_stripped = '1'.$number_stripped;
		$num_length = 11;
	}
	
	//echo "HOME NUMBER STRIPPED: ".$number_stripped;
	$patient_json['StrippedHomeNumber'] = $number_stripped;
	
	if (preg_match($regex, $number_stripped) && $num_length == 11) {
		$valid_home = 1;
		//echo " (NUMBER VALID FORMAT)";
		$patient_json['ValidHomeNumber'] = 'NUMBER VALID FORMAT';
	}else{
		//echo " (NUMBER INVALID FORMAT)";
		$patient_json['ValidHomeNumber'] = 'NUMBER INVALID FORMAT';
	}
	
	$HomePhone = $number_stripped;

	$number_stripped = preg_replace('/[^0-9,]|,[0-9]*$/','',$CellPhone);
	
	$regex = '/(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]‌​)\s*)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)([2-9]1[02-9]‌​|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})/';
	$num_length = strlen($number_stripped);
	
	if($num_length == 10){
		$number_stripped = '1'.$number_stripped;
		$num_length = 11;
	}
	
	//echo "CELL NUMBER STRIPPED: ".$number_stripped;
	$patient_json['StrippedCellNumber'] = $number_stripped;
	
	if (preg_match($regex, $number_stripped) && $num_length == 11) {
		$valid_cell = 1;
		//echo " (NUMBER VALID FORMAT)";
		$patient_json['ValidCellNumber'] = 'NUMBER VALID FORMAT';
	}else{
		//echo " (NUMBER INVALID FORMAT)";
		$patient_json['ValidCellNumber'] = 'NUMBER INVALID FORMAT';
	}
	
	$CellPhone = $number_stripped;

	$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
	
	if (preg_match($regex, $EmailAddress)) {
		$valid_email = 1;
		//echo "EMAIL VALID, BUT NOT VERIFIED ACCURATE";
		$patient_json['ValidEmail'] = 'EMAIL VALID, BUT NOT VERIFIED ACCURATE';
	}else{
		//echo "EMAIL INVALID";
		$patient_json['ValidEmail'] = 'EMAIL INVALID';
	}
	
	$query = $con->prepare("SELECT * FROM usuarios WHERE email = ? && GrantAccess = 'CATA'");
	$query->bindValue(1, $EmailAddress, PDO::PARAM_STR);
	$query->execute();
	
	$count = $query->rowCount();
	
	if($count >= 1){
		//echo "</br><b><span style='font-size:125%;'>DUPLICATE EMAIL DETECTED!</span></b>";
		$patient_json['DuplicateEmail'] = 'DUPLICATE EMAIL DETECTED';
	}else{
		$patient_json['DuplicateEmail'] = 'NO DUPLICATE EMAIL DETECTED';
	}
	
	$IdUsFIXED = substr($DOB,6,4).substr($DOB,0,2).substr($DOB,3,2).$gender_flag.'0';
	$IdUsFIXEDNAME = $FirstName.'.'. $LastName;
	
	$query = $con->prepare("SELECT * FROM usuarios WHERE IdUsFIXED = ? && IdUsFIXEDNAME = ? && GrantAccess = 'CATA'");
	$query->bindValue(1, $IdUsFIXED, PDO::PARAM_STR);
	$query->bindValue(2, $IdUsFIXEDNAME, PDO::PARAM_STR);
	$query->execute();
	
	$count = $query->rowCount();
	
	if($count >= 1){
		//echo "<b><span style='font-size:150%;'>WARNING POSSIBLE DUPLICATE USER DETECTED!</span></b></br></br>";
		$patient_json['DuplicateUser'] = 'WARNING POSSIBLE DUPLICATE USER DETECTED';
	}else{
		$patient_json['DuplicateUser'] = 'NO DUPLICATE USER DETECTED';
	}

	//HOLDS ARRAY OF WORDS TO GENERATE PASSWORD AUTOMATICALLY
	$input = array("either", "eleven","else", "elsewhere", "empty", "enough", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "fifteen", "fify", "fill", "find", "fire", "first", "five",  "former", "formerly", "forty", "found", "four", "from", "front", "full", "further",  "give", "hasnt", "have");
	$input2 = array("Madrid","London","Paris","Rome","Tokio","Dallas","Portland","Chicago","Rabat","Jerusalem","Amman","Cairo","Athens","Oslo","Bogota","Ottawa","Beijing");

	//AUTOMATICALLY GENERATE RANDOM PIN
	$autopassword = substr($input[array_rand($input)],0,4).substr($input2[array_rand($input2)],0,4);
	$autopin = rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);
	$pin = $autopin;
	$pass = $autopassword;
	
	$hashresult = explode(":", create_hash($autopassword));
	$IdUsRESERV= $hashresult[3];
	$additional_string=$hashresult[2];
	$salt = $additional_string;
	$pin_hash = create_hash($autopin);
	
	$plan = 'PREMIUM';
	$signupdate = date("Y-m-d H:i:s");
	$dob_string = (substr($DOB,6,4).'-'.substr($DOB,0,2).'-'.substr($DOB,3,2));
	
	//CREATE USER IN DATABASE
	$q = $con->prepare("INSERT INTO usuarios SET Name = ?, Surname = ?, FNac = ?, Sexo = ?, email = ?, telefono = ?, IdUsFIXED = ?, IdUsFIXEDNAME = ?, Alias = ?, IdUsRESERV = ?, salt = ?, pin_hash=? , GrantAccess=?, Password=?, signUpDate=?, plan = ?, ownerAcc = 'Owner'"); //, Password='$pass'
	$q->bindValue(1, $FirstName, PDO::PARAM_STR);
	$q->bindValue(2, $LastName, PDO::PARAM_STR);
	$q->bindValue(3, $dob_string, PDO::PARAM_STR);
	$q->bindValue(4, $gender_flag, PDO::PARAM_INT);
	$q->bindValue(5, $EmailAddress, PDO::PARAM_STR);
	$q->bindValue(6, $CellPhone, PDO::PARAM_INT);
	$q->bindValue(7, $IdUsFIXED, PDO::PARAM_INT);
	$q->bindValue(8, $IdUsFIXEDNAME, PDO::PARAM_STR);
	$q->bindValue(9, $IdUsFIXEDNAME, PDO::PARAM_STR);
	$q->bindValue(10, $IdUsRESERV, PDO::PARAM_STR);
	$q->bindValue(11, $salt, PDO::PARAM_STR);
	$q->bindValue(12, $pin_hash, PDO::PARAM_STR);
	$q->bindValue(13, 'CATA', PDO::PARAM_STR);
	$q->bindValue(14, $pass, PDO::PARAM_STR);
	$q->bindValue(15, $signupdate, PDO::PARAM_STR);
	$q->bindValue(16, $plan, PDO::PARAM_STR);
	$result = $q->execute();
	
	$prim_key = $con->lastInsertId(); 
	
	//INSERTS BASIC EMR STUFF SO DOB AND SO FORTH ARE NOT ALL SCREWED UP
	$b = $con->prepare("INSERT INTO basicemrdata SET DOB = ?, IdPatient = ?");
	$b->bindValue(1, $dob_string, PDO::PARAM_STR);
	$b->bindValue(2, $prim_key, PDO::PARAM_INT);
	$result2 = $b->execute();
	
	if($result && $result2){
		
		//INSERT DATA INTO CATAPULT TABLE SO THAT WE CAN REFERENCE IT LATER IF NEED BE
		$c = $con->prepare("INSERT INTO catapult SET 
		our_id = ?, 
		PatientId = ?, 
		CompanyName = ?, 
		FirstName = ?, 
		LastName = ?, 
		DOB = ?, 
		SSN = ?, 
		EmployeeId = ?, 
		Gender = ?, 
		LanguageId = ?, 
		MiddleInitial = ?, 
		Address1 = ?, 
		Address2 = ?, 
		City = ?, 
		State = ?, 
		PostalCode = ?, 
		HomePhone = ?, 
		CellPhone = ?, 
		EmailAddress = ?,
		InsuranceGroupId = ?, 
		InsuranceMemberId = ?, 
		InsurancePlanId = ?, 
		HispanicLatino = ?, 
		EthnicityId = ?, 
		IsActive = ?");
		
		$c->bindValue(1, $prim_key, PDO::PARAM_INT);
		$c->bindValue(2, $PatientId, PDO::PARAM_INT);
		$c->bindValue(3, $CompanyName, PDO::PARAM_STR);
		$c->bindValue(4, $FirstName, PDO::PARAM_STR);
		$c->bindValue(5, $LastName, PDO::PARAM_STR);
		$c->bindValue(6, $DOB, PDO::PARAM_STR);
		$c->bindValue(7, $SSN, PDO::PARAM_STR);
		$c->bindValue(8, $EmployeeId, PDO::PARAM_STR);
		$c->bindValue(9, $Gender, PDO::PARAM_STR);
		$c->bindValue(10, $LanguageId, PDO::PARAM_INT);
		$c->bindValue(11, $MiddleInitial, PDO::PARAM_STR);
		$c->bindValue(12, $Address1, PDO::PARAM_STR);
		$c->bindValue(13, $Address2, PDO::PARAM_STR);
		$c->bindValue(14, $City, PDO::PARAM_STR);
		$c->bindValue(15, $State, PDO::PARAM_STR);
		$c->bindValue(16, $PostalCode, PDO::PARAM_STR);
		$c->bindValue(17, $HomePhone, PDO::PARAM_STR);
		$c->bindValue(18, $CellPhone, PDO::PARAM_STR);
		$c->bindValue(19, $EmailAddress, PDO::PARAM_STR);
		$c->bindValue(20, $InsuranceGroupId, PDO::PARAM_STR);
		$c->bindValue(21, $InsuranceMemberId, PDO::PARAM_STR);
		$c->bindValue(22, $InsurancePlanId, PDO::PARAM_INT);
		$c->bindValue(23, $HispanicLatino, PDO::PARAM_INT);
		$c->bindValue(24, $EthnicityId, PDO::PARAM_INT);
		$c->bindValue(25, $IsActive, PDO::PARAM_INT);
		$c->execute();
	}
	
	
	
	require_once "Services/Twilio.php";		     
	$AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
	$AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
	
	// Instantiate a new Twilio Rest Client
	$client = new Services_Twilio($AccountSid, $AuthToken);
	
	/* Your Twilio Number or Outgoing Caller ID */
	$from = '+19034018888'; 
	$to= '+'.$CellPhone; 
	
	//SMS BODY
	$body = 'Welcome to Catapult! '.$FirstName.', your login is: '.$EmailAddress.' (Password:'.$pass.' ). Your security PIN is: '.$pin;
	
	//TRY SENDING MESSAGE
	try{
	$client->account->sms_messages->create($from, $to, $body);
	}catch(Exception $e){
	echo "Twilio could not text message number.  Number is most likely incorrect.".$e;
	}

	// SEND EMAIL TO NEW USER:
	require_once 'lib/swift_required.php';
	
	$NamePatient = $FirstName.' '.$LastName;
	  
	$aQuien = $EmailAddress;
	$Sobre = 'Welcome to Catapult Health!';
	
	$FromText = 'Catapult Health';
	
	$Content = file_get_contents($hardcode.'templates/CatapultAccountCreation.html');
	
	$Var1 = $FirstName.' '.$LastName;
	$Var2 = 'Thank you for joining Catapult Health!<p>Please login to schedule your appointment with a nurse practitioner today!
	<p>Your login is : <b>'.$EmailAddress.'</b></p>
	<p>Your password is : <b>'.$pass.'</b></p>
	<p>Your PIN is : <b>'.$pin.'</b></p>
	<p>You may login <a href="'.$domain.'">here</a>.</p>';
	$Var3 = $domain;
	$Content = str_replace("**Var1**",$Var1,$Content);
	$Content = str_replace("**Var2**",$Var2,$Content);
	$Content = str_replace("**Var3**",$Var3,$Content);
	    
	$Body = $Content;

	 
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
	
	  ->setBody($Body, 'text/html');
	
	$result = $mailer->send($message);
	// SEND EMAIL TO NEW USER ******************************
	
	//echo "<b>HEALTH2ME KEYS</b></br>PRIMARY_KEY: ".$prim_key."</br>FIXED_NAME: ".$IdUsFIXEDNAME."</br>FIXED_ID: ".$IdUsFIXED;
	$keys_array = array();
	$keys_array['primary_key'] = $prim_key;
	$keys_array['fixed_name'] = $IdUsFIXEDNAME;
	$keys_array['fixed_id'] = $IdUsFIXED;

	$patient_json['Health2MeKeys'] = $keys_array;

	$returned_json[] = $patient_json;
	
	$p++; 
}
echo json_encode($returned_json);

?>