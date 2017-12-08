<?php
class addUserClass{
	//PDO...
	private $con;
	private $dbhost;
	private $dbname;
	private $dbuser;
	private $dbpass;
	
	public $domain;
	
	public $first_name;
	public $last_name;
	public $email;
	public $fixed_name;
	public $fixed_id;
	
	//SWIFT TRANSPORTER...
	public $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
		  ->setUsername('newmedibank@gmail.com')
		  ->setPassword('ardiLLA98');
	
	
	private $stripe = array(
		"secret_key"      => "sk_test_hJg0Ij3YDmTvpWMenFHf3MLn",
		"publishable_key" => "pk_test_YBtrxG7xwZU9RO1VY8SeaEe9"
	);
	
	public function __construct(){
		require("environment_detailForLogin.php");
		require("PasswordHash.php");
		require_once("displayExitClass.php");
		require_once('stripe/stripe/lib/Stripe.php');
		
		//SET DB CONNECTION/
		$this->dbhost = $env_var_db["dbhost"];
		$this->dbname = $env_var_db["dbname"];
		$this->dbuser = $env_var_db["dbuser"];
		$this->dbpass = $env_var_db["dbpass"];
		$this->domain = $domain;
		
		$this->con = new PDO('mysql:host='.$this->dbhost.';dbname='.$this->dbname.';charset=utf8', ''.$this->dbuser.'', ''.$this->dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		if (!$this->con)
		{
			die('Could not connect: ' . mysql_error());
		}
		
		Stripe::setApiKey($this->stripe['secret_key']);
	}
	
	public function make_user($fixed_id, $fixed_name, $gender, $birth_order, $first_name, $last_name, $email, $plan, $cc_token, $post_day, $post_month, $post_year, $post_phone, $post_password){
		$this->fixed_id = $fixed_id;
		$this->fixed_name = $fixed_name;
		$Sexo = $gender;
		$Orden = $birth_order;
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->email = $email;

		$creditCard = $cc_token;
		$plan = $plan;

		echo "PlanValue  ".$plan;

		$plan_str = 'FREE';

		if($plan == '1')
		{
			$plan_str = 'PREMIUM';
		}
		else if($plan == '2')
		{
			$plan_str = 'FAMILY';
		   
		}
		$stripe_id = '';

		if(strlen($Orden) == 0)
		{
			$Orden = 0;
		}

		$month = $post_month;
		$day = $post_day;
		if(strlen($month) == 1)
		{
			$month = '0'.$month;
		}
		if(strlen($day) == 1)
		{
			$day = '0'.$day;
		}
		$dob = $post_year.'-'.$month.'-'.$day;

		$new_user_id = 0;

		$telefono = str_replace('+','',$post_phone);

		$queModo = 1;

		$confirm_code=md5(uniqid(rand()));

		//CREATES PASSWORD HASH RESULT
		$hashresult = explode(":", create_hash($post_password));
		$IdUsRESERV= $hashresult[3];
		$additional_string=$hashresult[2];

			//CHECKS TO MAKE SURE PHONE NUMBER IS VALID FORMAT...
			$numbersOnly = preg_replace('/[^0-9,]|,[0-9]*$/','',$post_phone);
			$numberOfDigits = strlen($numbersOnly);
			if ($numberOfDigits == 7 or $numberOfDigits == 10 or $numberOfDigits == 11 or $numberOfDigits > 11) {
				$valid_phone = 1;
			} else {
				$valid_phone = 0;
			}

		//CHECKS FOR DUPLICATE EMAIL IN SYSTEM
		$result = $this->con->prepare("SELECT * FROM usuarios WHERE email = ?");
		$result->bindValue(1, $this->email, PDO::PARAM_STR);
		$result->execute();
		$num_rows = $result->rowCount();
		if ($num_rows > 0) {
			$proceed = 1;
			$logvalue_email = 1;
		}else{
		$logvalue_email = 0;
		}

		if($logvalue_email == 0)
		{
			$creditCardQuery = '';
			if(strlen($creditCard) > 0 && $plan != '0')
			{
				$customer = Stripe_Customer::create(array(
				  "description" => "Customer for ".$this->first_name." ".$this->last_name,
				  "email" => $this->email
				));
				$customer->cards->create(array("card" => $creditCard));
				if($plan == 1)
				{
					$customer->subscriptions->create(array("plan" => "g45lm34"));
				}
				else if($plan == 2)
				{
					$customer->subscriptions->create(array("plan" => "75h23m4"));
				}
				$creditCardQuery = ", stripe_id='".$customer->id."'";
				$stripe_id = $customer->id;
			}
			
			// adding new user into the usuarios table
			$query_str = "INSERT INTO usuarios SET IdUsFIXED = ?, IdUsFIXEDNAME = ?, Alias = ?, Sexo = ?, Orden = ?, Name = ?, Surname = ? , IdUsRESERV = ?,telefono = ?, email = ? , Verificado = '0' , confirmcode=?,salt=?, plan=?, subsType = 'Owner',signUpDate = NOW()"; //subsType= 'Owner' added by Pallab
			if($plan != '0')
			{
				$query_str .= ", stripe_id=?";
			}
			$result = $this->con->prepare($query_str);
			$result->bindValue(1, $this->fixed_id, PDO::PARAM_STR);
			$result->bindValue(2, $this->fixed_name, PDO::PARAM_STR);
			$result->bindValue(3, $this->fixed_name, PDO::PARAM_STR);
			$result->bindValue(4, $Sexo, PDO::PARAM_INT);
			$result->bindValue(5, $Orden, PDO::PARAM_INT);
			$result->bindValue(6, $this->first_name, PDO::PARAM_STR);
			$result->bindValue(7, $this->last_name, PDO::PARAM_STR);
			$result->bindValue(8, $IdUsRESERV, PDO::PARAM_STR);
			$result->bindValue(9, $telefono, PDO::PARAM_STR);
			$result->bindValue(10, $this->email, PDO::PARAM_STR);
			$result->bindValue(11, $confirm_code, PDO::PARAM_STR);
			$result->bindValue(12, $additional_string, PDO::PARAM_STR);
			$result->bindValue(13, $plan_str, PDO::PARAM_STR);
			if($plan != '0')
			{
				$result->bindValue(14, $stripe_id, PDO::PARAM_STR);
			}
			$result->execute();
			$last_id = $this->con->lastInsertId();
			$new_user_id = $last_id;
			
			// adding a new entry for the new user into the basicemrdata table
			$query_str = "INSERT INTO basicemrdata SET DOB = ?, phone = ?, IdPatient = ?";
			$result = $this->con->prepare($query_str);
			$result->bindValue(1, $dob, PDO::PARAM_STR);
			$result->bindValue(2, $telefono, PDO::PARAM_STR);
			$result->bindValue(3, $last_id, PDO::PARAM_INT);
			$result->execute();

		}
		else
		{
			$exit_display = new displayExitClass();

			$exit_display->displayFunction(7);
			die();
		}
		
		$this->send_confirmation_email();

		echo '<script src="js/jquery.min.js"></script>
		<script src="js/timezones.js" type="text/javascript"></script>  
		<script> 
			//set the timezone to the curent timezone
			var current_timezone = get_timezone_offset();
			var email = "'.$this->email.'";
			url= "get_timezone.php?timezone="+current_timezone+"&email="+email;
			var RecTipo=LanzaAjax(url);
			function LanzaAjax (DirURL)
				{
				var RecTipo = "SOMETHING WENT WRONG";
				$.ajax(
				   {
				   url: DirURL,
				   dataType: "html",
				   async: false,
				   complete: function(){
							},
				   success: function(data) {
							if (typeof data == "string") {
										RecTipo = data;
										}
							 }
					});
				return RecTipo;
				}   
			  
			window.location.replace("createUserConfirmation.php?number="+'.$this->fixed_id.'+"&name="+'.$this->first_name.'.'.$this->last_name.'+"&email="+'.$this->email.');
		</script>';

	}
	
	private function send_confirmation_email(){
		require_once 'MBCaller.php';

		require_once 'lib/swift_required.php';

		$subject = 'Inmers Account Confirmation';

		$info_block = '<ul style="display:block;margin:15px 20px;padding:0;list-style:none;border-top:1px solid #eee">
		<li style="display:block;margin:0;padding:5px 0;border-bottom:1px solid #eee"><strong>Your Email:</strong>   <a href="mailto:'.$this->email.'" target="_blank">'.$this->email.'</a></li>
		<li style="display:block;margin:0;padding:5px 0;border-bottom:1px solid #eee"><strong>Your Number Id:</strong> '.$this->fixed_id.' </li>
		<li style="display:block;margin:0;padding:5px 0;border-bottom:1px solid #eee"><strong>Your Name Id:</strong>       '.$this->fixed_name.'</li>
		</ul>';


		$additional ='<p>Please follow the link to verify your identity and complete the sign up process.</p><br><p>For your records here is a copy of the information you submitted to us...</p>'.$info_block;

		$confirm_button = '<a href='.$this->domain.'/ConfirmaUserPac.php?token='.$confirm_code.' style="cursor:auto; color:#ffffff;display:inline-block;font-family:\'Helvetica\',Arial,sans-serif;width:auto;white-space:nowrap;min-height:32px;margin:5px 5px 0 0;padding:0 22px;text-decoration:none;text-align:center;font-weight:bold;font-style:normal;font-size:15px;line-height:32px;border:0;border-radius:4px;vertical-align:top;background-color:#3498db" target="_blank"><span style="display:inline;font-family:\'Helvetica\',Arial,sans-serif;text-decoration:none;font-weight:bold;font-style:normal;font-size:15px;line-height:32px;border:none;background-color:#3498db;color:#ffffff">Yes, confirm my account.</span></a>';


		$Body = '<a href="#"><img src="'.$this->domain.'/images/health2me_horizontal.png"></a></p><p>Thank you for your interest in our services!</p><p><h1>Please confirm your account</h1></p><p>'.$confirm_button.'</p>'.$additional;

		$mailer = Swift_Mailer::newInstance($this->transporter);


		// Create the message
		$message = Swift_Message::newInstance()
		  
		  // Give the message a subject
		  ->setSubject($subject)

		  // Set the From address with an associative array
		  ->setFrom(array('no-reply@health2.me' => 'health2.me'))

		  // Set the To addresses with an associative array
		  ->setTo(array($this->email))

		  ->setBody($Body, 'text/html');


		$result = $mailer->send($message);
	}
	
}


?>
