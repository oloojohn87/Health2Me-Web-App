<?php
class twilioPhoneClass{
	//PDO...
	private $con;
	private $dbhost;
	private $dbname;
	private $dbuser;
	private $dbpass;
	private $hardcode;

	//PUBLIC VARIABLES...
	public $patient_phone;
	public $doctor_phone;
	public $patient_id;
	public $doctor_id;
	public $patient_name;
	public $doctor_name;
	public $description;
	public $contract;
	public $recent_id;
	public $info;
	public $language;
	public $recording_url;
	
	public $override = false;
	public $override_doc_validation = false;
	
	//TWILIO CREDENTIALS...
	private $api_version = '2010-04-01';
    	private $account_sid = "AC109c7554cf28cdfe596e4811c03495bd";
    	private $auth_token = "26b187fb3258d199a6d6edeb7256ecc1";
	
	//DATABASE DATA/
	public $patient_row;
	public $doctor_row;
	
	//PUSHER/
	//private $pusher_app_key = 'd869a07d8f17a76448ed';
	//private $pusher_app_secret = '92f67fb5b104260bbc02';
	//private $pusher_app_id = '51379';
	
	private $push;
	
	public $stripe = array(
		"secret_key"      => "sk_test_hJg0Ij3YDmTvpWMenFHf3MLn",
		"publishable_key" => "pk_test_YBtrxG7xwZU9RO1VY8SeaEe9"
	);

	//BEGIN CONSTRUCT/
	public function __construct(){
		ini_set('max_execution_time', 300);
		require("Services/Twilio.php");
		require("environment_detailForLogin.php");
		require_once('stripe/stripe/lib/Stripe.php');
		require("push_server.php");
        require_once('push/push.php');
		
		//MAKE PUSHER CONNECTION/
		//$this->pusher = new Pusher($this->pusher_app_key, $this->pusher_app_secret, $this->pusher_app_id);
        $this->push = new Push();
		
		//SET DB CONNECTION/
		$this->dbhost = $env_var_db["dbhost"];
		$this->dbname = $env_var_db["dbname"];
		$this->dbuser = $env_var_db["dbuser"];
		$this->dbpass = $env_var_db["dbpass"];
		$this->hardcode = $env_var_db['hardcode'];
		
		$this->con = new PDO('mysql:host='.$this->dbhost.';dbname='.$this->dbname.';charset=utf8', ''.$this->dbuser.'', ''.$this->dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		if (!$this->con)
		{
			die('Could not connect: ' . mysql_error());
		}
  
		//SET STRIPE CREDENTIALS
		Stripe::setApiKey($this->stripe['secret_key']);
	//END CONSTRUCT/
	}
	
	//BEGIN INIT CALL/
	public function initiate_call($patient_phone, $doctor_phone, $patient_id, $doctor_id, $patient_name, $doctor_name, $description, $override, $override_doc_validation, $grant_access, $language_var){		
		$desc = '?';
		
		if($patient_phone[0] == '+'){
			$this->patient_phone = $patient_phone;
		}else{
			$this->patient_phone = "+".$patient_phone;
		}
		$this->doctor_phone = "+".$doctor_phone;
		$this->patient_id = $patient_id;
		$this->doctor_id = $doctor_id;
		$this->patient_name = str_replace('_', ' ', $patient_name);
		$this->doctor_name = str_replace('_', ' ', $doctor_name);
		$this->description = $description;
		$this->override = $override;
		$this->override_doc_validation = $override_doc_validation;
		$this->language = $language_var;
		
		//GRABBING DATABASE DATA/
		$result = $this->con->prepare("SELECT id,in_consultation,stripe_id FROM doctors WHERE id=?");
		$result->bindValue(1, $this->doctor_id, PDO::PARAM_INT);
		$result->execute();

		$this->doctor_row = $result->fetch(PDO::FETCH_ASSOC);

		$result = $this->con->prepare("SELECT stripe_id,plan,ownerAcc,subsType,GrantAccess FROM usuarios WHERE Identif=?");
		$result->bindValue(1, $this->patient_id, PDO::PARAM_INT);
		$result->execute();

		$this->patient_row = $result->fetch(PDO::FETCH_ASSOC);
		
		if($grant_access != ''){
			$this->patient_row['GrantAccess'] = $grant_access;
		}
		
		switch(true){
				case ($this->patient_row['GrantAccess'] == 'HTI-RIVA'):
					$this->doctor_phone = "+18779669066";
					$this->doctor_name = "Riva Call Center";
					$this->doctor_id = 1;
					$this->override_doc_validation = true;
					$this->override = true;
					$this->contract = 'HTI-RIVA';
					
					break;
				case ($this->patient_row['GrantAccess'] == 'HTI-COL'):
					$this->doctor_phone = "+18779669066";
					$this->doctor_name = "HTI Call Center";
					$this->doctor_id = 1;
					$this->override_doc_validation = true;
					$this->override = true;
					$this->contract = 'HTI-COL';
					break;
				case ($this->patient_row['GrantAccess'] == 'HTI-24X7'):
					$this->doctor_phone = "+18779669066";
					$this->doctor_name = "24X7 Call Center";
					$this->doctor_id = 1;
					$this->override_doc_validation = true;
					$this->override = true;
					$this->contract = 'HTI-24X7';
					break;
				case ($this->patient_row['GrantAccess'] == 'Manually-Launched'):
					$this->contract = str_replace(" ", "%20", 'Manual Launch');
					$this->override_doc_validation = true;
					$this->override = true;
					break;
				default:
					$this->contract = 'H2M';
					//$this->override_doc_validation = true;
					break;
			}
			
		// check if the doctor is in a consultation if not proceed... override doc validation = true will execute call even in doc is in consultation...
		if($this->doctor_row['in_consultation'] != 1 || $this->override_doc_validation)
		{
			$client = new Services_Twilio($this->account_sid, $this->auth_token);
			
		
			$docs_in_consultation = array();
			foreach ($client->account->conferences->getIterator(0, 50, array("Status" => "in-progress")) as $conference)
			{
				$conference_name = explode("_", $conference->friendly_name);
				$doc_id = intval($conference_name[0]);
				if(!in_array($tis->doctor_id, $docs_in_consultation))
				{
					array_push($docs_in_consultation, $this->doctor_id);
				}
			}
			
			$consults = $this->con->prepare("SELECT consultationId FROM consults WHERE Status = 'In Progress' AND Doctor = ?");
			$consults->bindValue(1, $this->doctor_id, PDO::PARAM_INT);
			$consults->execute();
			$in_consults = $consults->rowCount();
			
			//Second doctor validation check, sees if doctor is currently on the phone with Twilio and not database.  Database is verified through first check if statement.
			if((!in_array(intval($this->doctor_row['id']), $docs_in_consultation) && $in_consults == 0) || $this->override_doc_validation)
			{
			
				// check if the user has a credit card in their account
				$has_credit_card = false;
				if(isset($this->patient_row['stripe_id']) && $this->patient_row['stripe_id'] != null && strlen($this->patient_row['stripe_id']) > 0)
				{
					$customer = Stripe_Customer::retrieve($this->patient_row['stripe_id']);
					$cards = Stripe_Customer::retrieve((htmlspecialchars($this->patient_row['stripe_id'])))->cards->all(array('limit' => 10));
					
					// check if the user has a credit card in their account
					if(count($cards["data"]) > 0)
					{
						$has_credit_card = true;
					}
				//END IF	
				}
				
				if(!$has_credit_card && $this->patient_row['plan'] == 'FAMILY' && $this->patient_row['subsType'] != 'Owner')
				{
					$result = $this->con->prepare("SELECT stripe_id FROM usuarios WHERE Identif IN (SELECT ownerAcc FROM usuarios WHERE Identif = ?)");
					$result->bindValue(1, $this->patient_id, PDO::PARAM_INT);
					$result->execute();

					$owner_row = $result->fetch(PDO::FETCH_ASSOC);
					
					if(isset($owner_row['stripe_id']) && $owner_row['stripe_id'] != null && strlen($owner_row['stripe_id']) > 0)
					{
						$customer = Stripe_Customer::retrieve($owner_row['stripe_id']);
						$cards = Stripe_Customer::retrieve((htmlspecialchars($owner_row['stripe_id'])))->cards->all(array('limit' => 10));

						// check if the user has a credit card in their account
						if(count($cards["data"]) > 0)
						{
							$has_credit_card = true;
						}
					}
				//END IF	
				}
				
				if($has_credit_card || $this->override)
				{
					$date = date_create();
					$timestamp = date_timestamp_get($date);
					$result = $this->con->prepare("UPDATE doctors SET cons_req_time=?, consultation_pat=? WHERE id=?");
					$result->bindValue(1, $timestamp, PDO::PARAM_STR);
					$result->bindValue(2, $this->patient_id, PDO::PARAM_INT);
					$result->bindValue(3, $this->doctor_id, PDO::PARAM_INT);
					$result->execute();

					$initial = substr($this->patient_phone,3);
					
				
					$doc_names = explode(" ", $this->doctor_name);
					$pat_names = explode(" ", $this->patient_name);
					$doc_firstname = $doc_names[0];
					$pat_firstname = $pat_names[0];
					$doc_lastname = $doc_names[1];
					for($i = 2; $i < count($doc_names); $i++)
					{
						$doc_lastname .= " ".$doc_names[$i];
					}
					$pat_lastname = $pat_names[1];
					for($i = 2; $i < count($pat_names); $i++)
					{
						$pat_lastname .= " ".$pat_names[$i];
					}
					
					$desc = '?';
					if(strlen($this->description) == 0)
					{
						$desc = 'NULL';
					}
				//END IF	
				}else{
					//NC is passed back if there is no credit card.
					echo 'NC';
				}
				
				if($this->doctor_id == 1){
					$results = $this->con->prepare("INSERT INTO consults(contract,DateTime,status,type,Patient,Doctor,doctorName,doctorSurname,patientName,patientSurname,lastActive,description, Doctor_Status) values(?,NOW(),'In Progress','phone',?,?,?,?,?,?,NOW(),".$desc.",1)");
					$results->bindValue(1, $this->contract, PDO::PARAM_STR);
					$results->bindValue(2, $this->patient_id, PDO::PARAM_INT);
					$results->bindValue(3, $this->doctor_id, PDO::PARAM_INT);
					$results->bindValue(4, $doc_firstname, PDO::PARAM_STR);
					$results->bindValue(5, $doc_lastname, PDO::PARAM_STR);
					$results->bindValue(6, $pat_firstname, PDO::PARAM_STR);
					$results->bindValue(7, $pat_lastname, PDO::PARAM_STR);
					if(strlen($this->description) > 0)
					{
						$results->bindValue(8, $this->description, PDO::PARAM_STR);
					}
					$results->execute();

				}else{
				$results = $this->con->prepare("INSERT INTO consults(contract,DateTime,status,type,Patient,Doctor,doctorName,doctorSurname,patientName,patientSurname,lastActive,description) values(?,NOW(),'In Progress','phone',?,?,?,?,?,?,NOW(),".$desc.")");
					$results->bindValue(1, $this->contract, PDO::PARAM_STR);
					$results->bindValue(2, $this->patient_id, PDO::PARAM_INT);
					$results->bindValue(3, $this->doctor_id, PDO::PARAM_INT);
					$results->bindValue(4, $doc_firstname, PDO::PARAM_STR);
					$results->bindValue(5, $doc_lastname, PDO::PARAM_STR);
					$results->bindValue(6, $pat_firstname, PDO::PARAM_STR);
					$results->bindValue(7, $pat_lastname, PDO::PARAM_STR);
					if(strlen($this->description) > 0)
					{
						$results->bindValue(8, $this->description, PDO::PARAM_STR);
					}
					$results->execute();
				}

				$recent_id = $this->con->lastInsertId(); 

				//THIS GENERATES CONFERENCE FILE THAT WILL CONNECT THE TWO CALLERS...
				$conference_id = $this->doctor_id."_".$this->patient_id;
				$xml = '<?php echo \'<?xml version="1.0" encoding="UTF-8" ?>\'; ?><Response>
				<?php if(isset($_REQUEST["Digits"]) || $_GET["grant_access"] == "HTI-RIVA" || $_GET["grant_access"] == "HTI-COL" || $_GET["grant_access"] == "HTI-24X7"){
					require("../environment_detailForLogin.php");
					$dbhost = $env_var_db["dbhost"];
					$dbname = $env_var_db["dbname"];
					$dbuser = $env_var_db["dbuser"];
					$dbpass = $env_var_db["dbpass"];
					$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");mysql_select_db("$dbname")or die("cannot select DB");

					$result = mysql_query("SELECT in_consultation FROM doctors where id='.$this->doctor_id.'");
					$row = mysql_fetch_array($result);
					$count = $row["in_consultation"];
					
					$result = mysql_query("SELECT most_recent_doc FROM usuarios WHERE Identif='.$this->patient_id.'");
					$row = mysql_fetch_assoc($result);
					$str = $row["most_recent_doc"];
					$res = "";

					if(strlen($str) > 0){
						$str = str_replace(array("[", "]"), "", $str);
						$ids = explode(",", $str);
						$found = false;

						for($i = 0; $i < count($ids); $i++){
							if($ids[$i] == '.$this->doctor_id.'){
							$found = true;
							}
						}
						
						if(!$found){
							array_unshift($ids , '.$this->doctor_id.');
							while(count($ids) > 5){
								$doc = array_pop($ids);
							}
						}
						$res = implode(",", $ids);
					}else{
						$res = '.$this->doctor_id.';
					}
					
					$new_ids = "[".$res."]";
					$result = mysql_query("UPDATE usuarios SET most_recent_doc=".$new_ids." WHERE Identif='.$this->patient_id.'");
					
					echo \'<Dial action="../start_telemed_phonecall.php?stage=apt-leave&amp;id='.$this->doctor_id.'" method="GET">
					<Conference record="record-from-start" maxParticipants="2" endConferenceOnExit="true" eventCallbackUrl="../start_telemed_phonecall.php?stage=apt-callback&amp;info='.$this->doctor_id.'_'.$this->patient_id.'_'.$recent_id.'">'.$this->doctor_id.'_'.$this->patient_id.'</Conference>
					</Dial>\';
				} else {
					echo \'<Hangup/>\';
				} ?>
				</Response><?php unlink(__FILE__); 
						
				if(isset($_REQUEST["Digits"])){
					$result = mysql_query("UPDATE consults SET Doctor_Status = 1 WHERE consultationId = '.$recent_id.'");
				}
				?>';
				
				$file_make = file_put_contents('twiML_temp/'.$conference_id.'.php', $xml);
				
				function normalizeChars($s) {
					$replace = array(
						'ъ'=>'-', 'Ь'=>'-', 'Ъ'=>'-', 'ь'=>'-',
						'Ă'=>'A', 'Ą'=>'A', 'À'=>'A', 'Ã'=>'A', 'Á'=>'A', 'Æ'=>'A', 'Â'=>'A', 'Å'=>'A', 'Ä'=>'Ae',
						'Þ'=>'B',
						'Ć'=>'C', 'ץ'=>'C', 'Ç'=>'C',
						'È'=>'E', 'Ę'=>'E', 'É'=>'E', 'Ë'=>'E', 'Ê'=>'E',
						'Ğ'=>'G',
						'İ'=>'I', 'Ï'=>'I', 'Î'=>'I', 'Í'=>'I', 'Ì'=>'I',
						'Ł'=>'L',
						'Ñ'=>'N', 'Ń'=>'N',
						'Ø'=>'O', 'Ó'=>'O', 'Ò'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'Oe',
						'Ş'=>'S', 'Ś'=>'S', 'Ș'=>'S', 'Š'=>'S',
						'Ț'=>'T',
						'Ù'=>'U', 'Û'=>'U', 'Ú'=>'U', 'Ü'=>'Ue',
						'Ý'=>'Y',
						'Ź'=>'Z', 'Ž'=>'Z', 'Ż'=>'Z',
						'â'=>'a', 'ǎ'=>'a', 'ą'=>'a', 'á'=>'a', 'ă'=>'a', 'ã'=>'a', 'Ǎ'=>'a', 'а'=>'a', 'А'=>'a', 'å'=>'a', 'à'=>'a', 'א'=>'a', 'Ǻ'=>'a', 'Ā'=>'a', 'ǻ'=>'a', 'ā'=>'a', 'ä'=>'ae', 'æ'=>'ae', 'Ǽ'=>'ae', 'ǽ'=>'ae',
						'б'=>'b', 'ב'=>'b', 'Б'=>'b', 'þ'=>'b',
						'ĉ'=>'c', 'Ĉ'=>'c', 'Ċ'=>'c', 'ć'=>'c', 'ç'=>'c', 'ц'=>'c', 'צ'=>'c', 'ċ'=>'c', 'Ц'=>'c', 'Č'=>'c', 'č'=>'c', 'Ч'=>'ch', 'ч'=>'ch',
						'ד'=>'d', 'ď'=>'d', 'Đ'=>'d', 'Ď'=>'d', 'đ'=>'d', 'д'=>'d', 'Д'=>'d', 'ð'=>'d',
						'є'=>'e', 'ע'=>'e', 'е'=>'e', 'Е'=>'e', 'Ə'=>'e', 'ę'=>'e', 'ĕ'=>'e', 'ē'=>'e', 'Ē'=>'e', 'Ė'=>'e', 'ė'=>'e', 'ě'=>'e', 'Ě'=>'e', 'Є'=>'e', 'Ĕ'=>'e', 'ê'=>'e', 'ə'=>'e', 'è'=>'e', 'ë'=>'e', 'é'=>'e',
						'ф'=>'f', 'ƒ'=>'f', 'Ф'=>'f',
						'ġ'=>'g', 'Ģ'=>'g', 'Ġ'=>'g', 'Ĝ'=>'g', 'Г'=>'g', 'г'=>'g', 'ĝ'=>'g', 'ğ'=>'g', 'ג'=>'g', 'Ґ'=>'g', 'ґ'=>'g', 'ģ'=>'g',
						'ח'=>'h', 'ħ'=>'h', 'Х'=>'h', 'Ħ'=>'h', 'Ĥ'=>'h', 'ĥ'=>'h', 'х'=>'h', 'ה'=>'h',
						'î'=>'i', 'ï'=>'i', 'í'=>'i', 'ì'=>'i', 'į'=>'i', 'ĭ'=>'i', 'ı'=>'i', 'Ĭ'=>'i', 'И'=>'i', 'ĩ'=>'i', 'ǐ'=>'i', 'Ĩ'=>'i', 'Ǐ'=>'i', 'и'=>'i', 'Į'=>'i', 'י'=>'i', 'Ї'=>'i', 'Ī'=>'i', 'І'=>'i', 'ї'=>'i', 'і'=>'i', 'ī'=>'i', 'ĳ'=>'ij', 'Ĳ'=>'ij',
						'й'=>'j', 'Й'=>'j', 'Ĵ'=>'j', 'ĵ'=>'j', 'я'=>'ja', 'Я'=>'ja', 'Э'=>'je', 'э'=>'je', 'ё'=>'jo', 'Ё'=>'jo', 'ю'=>'ju', 'Ю'=>'ju',
						'ĸ'=>'k', 'כ'=>'k', 'Ķ'=>'k', 'К'=>'k', 'к'=>'k', 'ķ'=>'k', 'ך'=>'k',
						'Ŀ'=>'l', 'ŀ'=>'l', 'Л'=>'l', 'ł'=>'l', 'ļ'=>'l', 'ĺ'=>'l', 'Ĺ'=>'l', 'Ļ'=>'l', 'л'=>'l', 'Ľ'=>'l', 'ľ'=>'l', 'ל'=>'l',
						'מ'=>'m', 'М'=>'m', 'ם'=>'m', 'м'=>'m',
						'ñ'=>'n', 'н'=>'n', 'Ņ'=>'n', 'ן'=>'n', 'ŋ'=>'n', 'נ'=>'n', 'Н'=>'n', 'ń'=>'n', 'Ŋ'=>'n', 'ņ'=>'n', 'ŉ'=>'n', 'Ň'=>'n', 'ň'=>'n',
						'о'=>'o', 'О'=>'o', 'ő'=>'o', 'õ'=>'o', 'ô'=>'o', 'Ő'=>'o', 'ŏ'=>'o', 'Ŏ'=>'o', 'Ō'=>'o', 'ō'=>'o', 'ø'=>'o', 'ǿ'=>'o', 'ǒ'=>'o', 'ò'=>'o', 'Ǿ'=>'o', 'Ǒ'=>'o', 'ơ'=>'o', 'ó'=>'o', 'Ơ'=>'o', 'œ'=>'oe', 'Œ'=>'oe', 'ö'=>'oe',
						'פ'=>'p', 'ף'=>'p', 'п'=>'p', 'П'=>'p',
						'ק'=>'q',
						'ŕ'=>'r', 'ř'=>'r', 'Ř'=>'r', 'ŗ'=>'r', 'Ŗ'=>'r', 'ר'=>'r', 'Ŕ'=>'r', 'Р'=>'r', 'р'=>'r',
						'ș'=>'s', 'с'=>'s', 'Ŝ'=>'s', 'š'=>'s', 'ś'=>'s', 'ס'=>'s', 'ş'=>'s', 'С'=>'s', 'ŝ'=>'s', 'Щ'=>'sch', 'щ'=>'sch', 'ш'=>'sh', 'Ш'=>'sh', 'ß'=>'ss',
						'т'=>'t', 'ט'=>'t', 'ŧ'=>'t', 'ת'=>'t', 'ť'=>'t', 'ţ'=>'t', 'Ţ'=>'t', 'Т'=>'t', 'ț'=>'t', 'Ŧ'=>'t', 'Ť'=>'t', '™'=>'tm',
						'ū'=>'u', 'у'=>'u', 'Ũ'=>'u', 'ũ'=>'u', 'Ư'=>'u', 'ư'=>'u', 'Ū'=>'u', 'Ǔ'=>'u', 'ų'=>'u', 'Ų'=>'u', 'ŭ'=>'u', 'Ŭ'=>'u', 'Ů'=>'u', 'ů'=>'u', 'ű'=>'u', 'Ű'=>'u', 'Ǖ'=>'u', 'ǔ'=>'u', 'Ǜ'=>'u', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'У'=>'u', 'ǚ'=>'u', 'ǜ'=>'u', 'Ǚ'=>'u', 'Ǘ'=>'u', 'ǖ'=>'u', 'ǘ'=>'u', 'ü'=>'ue',
						'в'=>'v', 'ו'=>'v', 'В'=>'v',
						'ש'=>'w', 'ŵ'=>'w', 'Ŵ'=>'w',
						'ы'=>'y', 'ŷ'=>'y', 'ý'=>'y', 'ÿ'=>'y', 'Ÿ'=>'y', 'Ŷ'=>'y',
						'Ы'=>'y', 'ž'=>'z', 'З'=>'z', 'з'=>'z', 'ź'=>'z', 'ז'=>'z', 'ż'=>'z', 'ſ'=>'z', 'Ж'=>'zh', 'ж'=>'zh'
					);
					return strtr($s, $replace);
				}
				
				//SET CALL CONNECTION PARAMETERS THAT WILL BE PASSED TO TWILIO
				$doc_url = $this->hardcode.'start_telemed_phonecall.php?stage=req-doc&pat_name='.str_replace(" ", "%20", $this->patient_name).'&grant_access='.$this->contract.'&conference_id='.$conference_id.'&language='.$this->language;
				$pat_url = $this->hardcode.'start_telemed_phonecall.php?stage=req-pat&info='.$this->doctor_id.'-'.str_replace(" ", "%20",normalizeChars($this->doctor_name)).'-'.$conference_id.'-'.$this->patient_id.'-'.$recent_id.'&grant_access='.$this->contract.'&language='.$this->language;
				str_replace("-", "", $this->doctor_phone);
				str_replace("-", "", $this->patient_phone);
				
				//THIS CATCHED TWILIO ERRORS AND ADDS THEM TO TWILIO_ERRORS TABLE
				try
				{
//echo $this->doctor_phone;
					//CALLS DOC OR CALL CENTER DEPENDING ON PATH
					$client->account->calls->create('+19034018888', $this->doctor_phone, $doc_url);
				} 
				catch (Exception $e)
				{

					//CATCHING TWILIO ERRORS
					$error = $e->getMessage();
					$results2 = $this->con->prepare("INSERT INTO twilio_errors SET consult_id = ?, doc_id = ?, error = ?, patient_number = ?, doc_number = ?, type = ?");
					$results2->bindValue(1, $recent_id, PDO::PARAM_INT);
					$results2->bindValue(2, $this->doctor_id, PDO::PARAM_INT);
					$results2->bindValue(3, $error, PDO::PARAM_STR);
					$results2->bindValue(4, $this->patient_phone, PDO::PARAM_STR);
					$results2->bindValue(5, $this->doctor_phone, PDO::PARAM_STR);
					$results2->bindValue(6, 'phone', PDO::PARAM_STR);
					$results2->execute();
				}
				
				//CHECKS FOR DLU CONNECTION
				$results = $this->con->prepare("SELECT id FROM doctorslinkusers WHERE IdMED = ? AND IdUs = ? AND IdPIN is null");
				$results->bindValue(1, $this->doctor_id, PDO::PARAM_INT);
				$results->bindValue(2, $this->patient_id, PDO::PARAM_INT);
				$results->execute();
				$rowCount = $results->rowCount();
				if($rowCount == 0)
				{
					//CREATES DLU CONNECTION IF NONE FOUND
					$results = $this->con->prepare("insert into doctorslinkusers (IdMED,IdUs,Fecha,IdPIN,estado) values(?,?,NOW(),null,2)");
					$results->bindValue(1, $this->doctor_id, PDO::PARAM_INT);
					$results->bindValue(2, $this->patient_id, PDO::PARAM_INT);
					$results->execute();
				}
				
				//CREATES PUSHER NOTIFICATION FOR CONSULTATION
				$arr = array("id" => $this->patient_id, "name" =>$this->patient_name);
				//$this->pusher->trigger($this->doctor_id, 'telemed_phone_call', json_encode($arr));
				$this->push->send($this->doctor_id, 'telemed_phone_call', json_encode($arr));
                
				//if($this->contract != 'HTI-RIVA' && $this->contract != 'HTI-COL'){
				//	sleep(45);
				//}

				sleep(45);
				//CHECKS TO SEE IF CALL IS IN PROGRESS AND IF DOCTOR/CALL CENTER ARE ON THE CALL
				/*$check_accepted = $this->con->prepare("SELECT * FROM consults WHERE consultationId = ?");
				$check_accepted->bindValue(1, $recent_id, PDO::PARAM_INT);
				$check_accepted->execute();
				
				$accepted = $check_accepted->fetch(PDO::FETCH_ASSOC);
				
				if($accepted['Status'] == 'In Progress'  && $accepted['Doctor_Status'] != 1){
					$client->account->calls->create('+19034018888', $this->doctor_phone, $doc_url);
					sleep(45);
				}*/

				
				//CHECKS TO SEE IF CALL IS IN PROGRESS AND IF DOCTOR/CALL CENTER ARE ON THE CALL
				$check_accepted = $this->con->prepare("SELECT * FROM consults WHERE consultationId = ?");
				$check_accepted->bindValue(1, $recent_id, PDO::PARAM_INT);
				$check_accepted->execute();
				
				$accepted = $check_accepted->fetch(PDO::FETCH_ASSOC);
				
				if($accepted['Status'] == 'In Progress'  && $accepted['Doctor_Status'] == 1){
				
					try
					{
						//THIS CALLS PATIENT IF DOC/CALL CENTER ARE ON THE PHONE
						$call = $client->account->calls->create('+19034018888', $this->patient_phone, $pat_url);
					} catch (Exception $e){
						$error = $e->getMessage();
						$results2 = $this->con->prepare("INSERT INTO twilio_errors SET consult_id = ?, patient_id = ?, error = ?, patient_number = ?, doc_number = ?, type = ?");
						$results2->bindValue(1, $recent_id, PDO::PARAM_INT);
						$results2->bindValue(2, $this->patient_id, PDO::PARAM_INT);
						$results2->bindValue(3, $error, PDO::PARAM_STR);
						$results2->bindValue(4, $this->patient_phone, PDO::PARAM_STR);
						$results2->bindValue(5, $this->doctor_phone, PDO::PARAM_STR);
						$results2->bindValue(6, 'phone', PDO::PARAM_STR);
						$results2->execute();
					}
					//SLEEP 45 SECONDS AND CALL MEMBER AGAIN IF MEMBER DID NOT PICKUP
					sleep(45);
					$check_accepted = $this->con->prepare("SELECT * FROM consults WHERE consultationId = ?");
					$check_accepted->bindValue(1, $recent_id, PDO::PARAM_INT);
					$check_accepted->execute();

					$accepted = $check_accepted->fetch(PDO::FETCH_ASSOC);

					if($accepted['Status'] == 'In Progress'  && $accepted['Doctor_Status'] == 1 && $accepted['Patient_Status'] != 1){
					
						try{
							$call = $client->account->calls->create('+19034018888', $this->patient_phone, $pat_url);
						} catch (Exception $e){
							$error = $e->getMessage();
							$results2 = $this->con->prepare("INSERT INTO twilio_errors SET consult_id = ?, patient_id = ?, error = ?, patient_number = ?, doc_number = ?, type = ?");
							$results2->bindValue(1, $recent_id, PDO::PARAM_INT);
							$results2->bindValue(2, $this->patient_id, PDO::PARAM_INT);
							$results2->bindValue(3, $error, PDO::PARAM_STR);
							$results2->bindValue(4, $this->patient_phone, PDO::PARAM_STR);
							$results2->bindValue(5, $this->doctor_phone, PDO::PARAM_STR);
							$results2->bindValue(6, 'phone', PDO::PARAM_STR);
							$results2->execute();
						}
						
					}
					
						$sid = $call->sid;
						$this->recording_url = $call->uri;
						echo $sid;
						$recording_query = $this->con->prepare("UPDATE consults SET recorded_file_source = ? WHERE consultationId = ?");
						$recording_query->bindValue(1, $this->recording_url, PDO::PARAM_STR);
						$recording_query->bindValue(2, $recent_id, PDO::PARAM_INT);
						$recording_query->execute();
					
				//END IF	
				}

				
			
			//END SECOND IF STATEMENT WHICH CHECKS TWILIO IF DOCTOR IS IN CONSULTATION(SECOND CHECK FOR ROBUSTNESS)	
			}else{
				//IC is passed back if doctor is already in consultation...
				echo 'IC';
			}
			
		//END FIRST IF STATEMENT CHECK IF DOCS IN CONSULTAION IF NOT PROCEED...
		}else{
			//IC is passed back if doctor is already in consultation...
			echo 'IC';
		}

	//END INITIATE CALL/
	}
	
	public function request_appointment_from_doc($conference_id, $patient_name, $grant_access, $language){
		echo '<?xml version="1.0" encoding="UTF-8" ?>';
		
		switch(true){
				case ($grant_access == 'HTI-RIVA'):
				//Causes automatic redirect to call center.
					echo "<Response>
							  <Redirect>twiML_temp/".$conference_id.".php?grant_access=HTI-RIVA</Redirect>
						  </Response>";
					break;
				case ($grant_access == 'HTI-COL'):
				//Causes automatic redirect to call center.
					echo "<Response>
							  <Redirect>twiML_temp/".$conference_id.".php?grant_access=HTI-COL</Redirect>
						  </Response>";
					break;
				case ($grant_access == 'HTI-24X7'):
				//Causes automatic redirect to call center.
					echo "<Response>
							  <Redirect>twiML_temp/".$conference_id.".php?grant_access=HTI-24X7</Redirect>
						  </Response>";
					break;
				case ($language == 'sp'):
				//Causes automatic redirect to call center.
					echo "<Response>
							<Gather action='twiML_temp/".$conference_id.".php' timeout='6' numDigits='1'>
								<Say voice='alice' language='es-ES'>".urldecode($patient_name)." le gustaría iniciar una orientación telefónica con usted. ¿Le gustaría a aceptar ? Por favor, pulse cualquier dígito para aceptar la orientación</Say>
							</Gather>
							<Say>Consultation denied. Good bye!</Say>
							<Redirect>twiML_temp/".$conference_id.".php</Redirect>
						</Response>";
					break;
				default:
				//Redirects to doctor for H2M
					echo "<Response>
							<Gather action='twiML_temp/".$conference_id.".php' timeout='10' numDigits='1'>
								<Say>".urldecode($patient_name)." would like to start a phone consultation with you. Please note, you must log into health two me and close the consultation to receive payment.  Would you like to accept? Please press any digit to accept the consultation.</Say>
							</Gather>
							<Say>Consultation denied. Good bye!</Say>
							<Redirect>twiML_temp/".$conference_id.".php</Redirect>
						</Response>";
					break;
			}
	//END REQUEST_APPOINTMENT_FROM_DOC FUNCTION		
	}
	
	public function request_appointment_from_pat($doctor_id, $patient_id, $recent_id, $grant_access, $info, $language){
		echo '<?xml version="1.0" encoding="UTF-8" ?>';
		
		//SET PUBLIC VARIABLES...
		$this->doctor_id = $doctor_id;
		$this->patient_id = $patient_id;
		$this->recent_id = $recent_id;
		$this->contract = $grant_access;
		$this->info = $info;
		$info_data = explode("-", $info);
		
		$result = $this->con->prepare("SELECT in_consultation FROM doctors where id = ?");
		$result->bindValue(1, $this->doctor_id, PDO::PARAM_INT);
		$result->execute();
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$count = $row["in_consultation"];
		
		$result = $this->con->prepare("SELECT most_recent_doc,GrantAccess FROM usuarios WHERE Identif = ?");
		$result->bindValue(1, $this->patient_id, PDO::PARAM_INT);
		$result->execute();
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$str = $row['most_recent_doc'];
		$res = "";
		if(strlen($str) > 0)
		{
			$str = str_replace(array("[", "]"), "", $str);
			$ids = explode(",", $str);
			$found = false;
			for($i = 0; $i < count($ids); $i++)
			{
				if($ids[$i] == $this->doctor_id)
				{
					$found = true;
				}
			}
			if(!$found)
			{
				array_unshift($ids , $this->doctor_id);
				while(count($ids) > 5)
				{
					$doc = array_pop($ids);
				}
			}
			$res = implode(",", $ids);
		}
		else
		{
			$res = $this->doctor_id;
		}
		$new_ids = "[".$res."]";
		$result = $this->con->prepare("UPDATE usuarios SET most_recent_doc = ? WHERE Identif = ?");
		$result->bindValue(1, $new_ids, PDO::PARAM_STR);
		$result->bindValue(2, $this->patient_id, PDO::PARAM_INT);
		$result->execute();
		
		switch(true){
			case ($this->contract == 'HTI-RIVA'):
			//Causes automatic redirect to call center.
				echo '<Response>';
				echo '<Gather timeout="50" action="'.$this->hardcode.'start_telemed_phonecall.php?stage=req-pat-stage2&amp;grant_access='.$this->contract.'&amp;info='.str_replace(" ", "%20", $this->info).'" method="GET" numDigits="1"><Say>We are redirecting you to the Riva call center.  Press any digit to confirm.</Say></Gather>';//Changing to digit 5 to identify as patient
				echo '<Redirect/>';
				echo '</Response>';
				break;
			case ($this->contract == 'HTI-COL'):
			//Causes automatic redirect to call center.
				echo '<Response>';
				echo '<Gather timeout="50" action="'.$this->hardcode.'start_telemed_phonecall.php?stage=req-pat-stage2&amp;grant_access='.$this->contract.'&amp;info='.str_replace(" ", "%20", $this->info).'" method="GET" numDigits="1"><Say voice="alice" language="es-ES">Usted esta a punto de comenzar su orientación con '.$info_data[1].'. Por favor marque cualquier digito para aceptar.</Say></Gather>';//Changing to digit 5 to identify as patient
				echo '<Redirect/>';
				echo '</Response>';
				break;
			case ($this->contract == 'HTI-24X7'):
			//Causes automatic redirect to call center.
				echo '<Response>';
				echo '<Gather timeout="50" action="'.$this->hardcode.'start_telemed_phonecall.php?stage=req-pat-stage2&amp;grant_access='.$this->contract.'&amp;info='.str_replace(" ", "%20", $this->info).'" method="GET" numDigits="1"><Say>We are redirecting you to the 24X7 call center.  Press any digit to confirm.</Say></Gather>';//Changing to digit 5 to identify as patient
				echo '<Redirect/>';
				echo '</Response>';
				break;
			case ($language == 'sp'):
			//Causes automatic redirect to call center.
				echo '<Response>';
				echo '<Gather timeout="50" action="'.$this->hardcode.'start_telemed_phonecall.php?stage=req-pat-stage2&amp;grant_access=H2M&amp;info='.str_replace(" ", "%20", $this->info).'" method="GET" numDigits="1"><Say voice="alice" language="es-ES">Usted esta a punto de comenzar su orientación con '.$info_data[1].'. Por favor marque cualquier digito para aceptar.</Say></Gather>';//Changing to digit 5 to identify as patient
				echo '<Redirect/>';
				echo "<Say>We didn't receive any input. Goodbye!</Say>";
				echo '</Response>';
				break;
			default:
			//Redirects to doctor for H2M
				echo '<Response>';
				echo '<Gather timeout="50" action="'.$this->hardcode.'start_telemed_phonecall.php?stage=req-pat-stage2&amp;grant_access=H2M&amp;info='.str_replace(" ", "%20", $this->info).'" method="GET" numDigits="1"><Say>You are about to enter a consultation with '.$info_data[1].'. Please press any digit to confirm.</Say></Gather>';//Changing to digit 5 to identify as patient
				echo '<Redirect/>';
				echo "<Say>We didn't receive any input. Goodbye!</Say>";
				echo '</Response>';
				break;
			
			
		//END IF ELSE STATEMENT
		}
		
	//END REQUEST_APPOINTMENT_FROM_PAT FUNCTION	
	}
	
	public function request_appointment_from_pat_stage2($doctor_id, $patient_id, $recent_id, $info){
		echo '<?xml version="1.0" encoding="UTF-8" ?>';
		$this->doctor_id = $doctor_id;
		$this->patient_id = $patient_id;
		$this->recent_id = $recent_id;
		
		$info_data = explode("-", $info);
		
		echo '<Response>';
		echo '<Dial action="start_telemed_phonecall.php?stage=apt-leave&amp;id='.$this->doctor_id.'" method="GET">';
		echo '<Conference record="record-from-start" maxParticipants="2" endConferenceOnExit="true" eventCallbackUrl="start_telemed_phonecall.php?stage=apt-callback&amp;info='.$this->doctor_id.'_'.$this->patient_id.'_'.$this->recent_id.'">'.$info_data[2].'</Conference>';
		echo '</Dial>';
		echo '</Response>';
			
		$result = $this->con->prepare("UPDATE consults SET Patient_Status = ? WHERE consultationId = ?");
		$result->bindValue(1, 1, PDO::PARAM_INT);
		$result->bindValue(2, $this->recent_id, PDO::PARAM_INT);
		$result->execute();
	}
	
	public function appointment_callback($recording_url, $doctor_id, $patient_id, $info){
		//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
		require_once('push/push.php');
		$this->doctor_id = $doctor_id;
		$this->patient_id = $patient_id;
		$this->info = $info;
		$info_data = explode("-", $info);
		
		//CREATE PUSHER INSTANCE FOR NOTIFICATIONS
		//$this->pusher = new Pusher($this->pusher_app_key, $this->pusher_app_secret, $this->pusher_app_id);
		$this->push = new Push();
        
		//PULL URL LOCATION FOR AUDIO FILE
		$rec_url = $recording_url;
		$file = "recordings/".basename($rec_url);
		
		if(count($info_data) > 2)
		{
			$callSID = $info_data[3];
			
			// Set Twilio AccountSid and AuthToken
			$sid = "AC109c7554cf28cdfe596e4811c03495bd";
			$token = "26b187fb3258d199a6d6edeb7256ecc1";
			$client = new Services_Twilio($sid, $token);
			$call = $client->account->calls->get($callSID);
			$callStatus = $call->status;
		}
		
		
		$result = $this->con->prepare("SELECT * FROM consults WHERE Doctor = ? AND Patient = ? ORDER BY DateTime DESC");
		$result->bindValue(1, $this->doctor_id, PDO::PARAM_INT);
		$result->bindValue(2, $this->patient_id, PDO::PARAM_INT);
		$result->execute();
		$row = $result->fetch(PDO::FETCH_ASSOC);
		
		$recent_id = $row['consultationId'];
		
		

		$consultation_start_time = explode(" ", $row['DateTime']);
		$startTime = strtotime($consultation_start_time[1]);
		$result = $this->con->prepare("SELECT CURTIME() as time");
		$result->execute();
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$endTime = strtotime($row['time']);
		$timeDiff = $endTime - $startTime;

		//updating the consults table

		$result = $this->con->prepare("UPDATE consults SET Status ='Completed', Recorded_File = ? WHERE consultationId = ? && Doctor_Status = ? && Patient_Status = ?");
		$result->bindValue(1, $file, PDO::PARAM_STR);
		$result->bindValue(2, $recent_id, PDO::PARAM_INT);
		$result->bindValue(3, 1, PDO::PARAM_INT);
		$result->bindValue(4, 1, PDO::PARAM_INT);
		$result->execute();
		
		$result = $this->con->prepare("UPDATE consults SET Status ='Not answered by doctor.', Recorded_File = ? WHERE consultationId = ? && Doctor_Status is null && Patient_Status = ?");
		$result->bindValue(1, $file, PDO::PARAM_STR);
		$result->bindValue(2, $recent_id, PDO::PARAM_INT);
		$result->bindValue(3, 1, PDO::PARAM_INT);
		$result->execute();


		//finding whether any new report created by the doctor
		$query = $this->con->prepare("SELECT count(*) AS count FROM lifepin WHERE idcreator = ?");
		$query->bindValue(1, $this->doctor_id, PDO::PARAM_INT);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
		
		$countOfReportsInLifePin = $result['count'];
		
		$query = $this->con->prepare("SELECT reportsCreated FROM doctors_calls WHERE id = ?");
		$query->bindValue(1, $this->doctor_id, PDO::PARAM_INT);
		$query->execute();
		$result = $query->fetch(PDO::FETCH_ASSOC);
		
		$currentCountOfReports = $result['reportsCreated'];
		$diff = $countOfReportsInLifePin - $currentCountOfReports;
		if($diff > 0)
		{
		  $currentCountOfReports = $countOfReportsInLifePin; 
		}

		//Updating the doctors_calls table
		$query = $this->con->prepare("SELECT * FROM doctors_calls WHERE id = ?");
		$query->bindValue(1, $this->doctor_id, PDO::PARAM_INT);
		$query->execute();
		$result = $query->rowCount();
		if($result > 0)
		{  
			$query = $this->con->prepare("UPDATE doctors_calls SET reportsCreated  = ?,numberOfConsultations = numberOfConsultations + 1 where id = ?");
			$query->bindValue(1, $currentCountOfReports, PDO::PARAM_INT);
			$query->bindValue(2, $this->doctor_id, PDO::PARAM_INT);
			$query->execute();
		}else{        
			$query = $this->con->prepare("SELECT name,surname FROM doctors WHERE id = ?");
			$query->bindValue(1, $this->doctor_id, PDO::PARAM_INT);
			$query->execute();
			$result = $query->fetch(PDO::FETCH_ASSOC);
			
			$doctorName = "'".$result['name']."'";
			$doctorSurname = "'".$result['surname']."'";
			$numberOfPatientsConsulted = 1;
			$numberOfConsultations = 1;
			
			$query = $this->con->prepare("INSERT INTO doctors_calls (id,name,surname,reportsCreated,numberOfConsultedPatients,numberOfConsultations) VALUES(?,?,?,?,?,?)");
			$query->bindValue(1, $this->doctor_id, PDO::PARAM_INT);
			$query->bindValue(2, $doctorName, PDO::PARAM_STR);
			$query->bindValue(3, $doctorName, PDO::PARAM_STR);
			$query->bindValue(4, $countOfReportsInLifePin, PDO::PARAM_INT);
			$query->bindValue(5, $numberOfPatientsConsulted, PDO::PARAM_INT);
			$query->bindValue(6, $numberOfConsultations, PDO::PARAM_INT);
			$query->execute();
		}

		//updating the usuarios table
		$query = $this->con->prepare("UPDATE usuarios SET numberOfPhoneCalls = numberOfPhoneCalls + 1");
		$query->execute();

			
		$query = $this->con->prepare("SELECT Name,Surname FROM doctors WHERE id = ?");
		$query->bindValue(1, $this->doctor_id, PDO::PARAM_INT);
		$query->execute();
		$row = $query->fetch(PDO::FETCH_ASSOC);

		$doc_name = $row['Name'].' '.$row['Surname'];
		
		$query = $this->con->prepare("SELECT Name,Surname FROM usuarios WHERE Identif = ?");
		$query->bindValue(1, $this->patient_id, PDO::PARAM_INT);
		$query->execute();
		$row = $query->fetch(PDO::FETCH_ASSOC);

		$pat_name = $row['Name'].' '.$row['Surname'];
		//$this->pusher->trigger('Telemedicine', 'ameridoc', 'Consultation Between Doctor '.$doc_name.' and patient '.$pat_name.' ended.');
		$this->push->send('Telemedicine', 'ameridoc', 'Consultation Between Doctor '.$doc_name.' and patient '.$pat_name.' ended.');
        
		$query = $this->con->prepare("UPDATE doctors SET in_consultation=0,telemed_type=0 WHERE id = ?");
		$query->bindValue(1, $this->doctor_id, PDO::PARAM_INT);
		$query->execute();

		$src = fopen($rec_url, 'r');
		$dest = fopen($file, 'w');
		stream_copy_to_stream($src, $dest);

		$enc_pass= $this->con->prepare("SELECT pass FROM encryption_pass WHERE id = (SELECT max(id) FROM encryption_pass)");
		$enc_pass->execute();
		$row_enc = $enc_pass->fetch(PDO::FETCH_ASSOC);
		$enc_pass=$row_enc['pass'];

		shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in recordings/".basename($rec_url)." -out recordings/recordings/".basename($rec_url)."");

	//END APPOINTMENT_CALLBACK FUNCTION	
	}
	
	public function appointment_leave($doc_id){
		echo '<?xml version="1.0" encoding="UTF-8" ?>';
		
		$this->doctor_id = $doc_id;
		
		$query = $this->con->prepare("UPDATE doctors SET in_consultation=0,telemed_type=0 WHERE id = ?");
		$query->bindValue(1, $this->doctor_id, PDO::PARAM_INT);
		$query->execute();
		
		$query = $this->con->prepare("SELECT * FROM consults WHERE Doctor = ? AND Status = 'In Progress' ORDER BY DateTime DESC LIMIT 1");
		$query->bindValue(1, $this->doctor_id, PDO::PARAM_INT);
		$query->execute();
		$row = $query->fetch(PDO::FETCH_ASSOC);

		$recent_id = $row['consultationId'];
		
		if($row['Doctor_Status'] == null && $row['Patient_Status'] == 1){
		$result55 = $this->con->prepare("UPDATE consults SET Status ='Not Answered By Doctor' WHERE consultationId = ?");
		$result55->bindValue(1, $recent_id, PDO::PARAM_INT);
		$result55->execute();
		}
		
		if($row['Patient_Status'] == null && $row['Doctor_Status'] == 1){
		$result55 = $this->con->prepare("UPDATE consults SET Status ='Not Answered By Patient' WHERE consultationId = ?");
		$result55->bindValue(1, $recent_id, PDO::PARAM_INT);
		$result55->execute();
		}
		
		if($row['Patient_Status'] != 1 && $row['Doctor_Status'] != 1){
		$result55 = $this->con->prepare("UPDATE consults SET Status ='Not Answered By Patient or Doctor' WHERE consultationId = ?");
		$result55->bindValue(1, $recent_id, PDO::PARAM_INT);
		$result55->execute();
		}
		
		$consultation_start_time = explode(" ", $row['DateTime']);
		$startTime = strtotime($consultation_start_time[1]);//strtotime($result['startTime']);
		$result = $this->con->prepare("SELECT CURTIME() as time");
		$result->execute();
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$endTime = strtotime($row['time']);//strtotime($result['endTime']);
		$timeDiff = $endTime - $startTime;
		
		$query = $this->con->prepare("UPDATE consults SET Status='Completed', Length = ? WHERE Doctor = ? && Status='In Progress' && Doctor_Status = 1 && Patient_Status = 1");
		$query->bindValue(1, $timeDiff, PDO::PARAM_INT);
		$query->bindValue(2, $this->doctor_id, PDO::PARAM_INT);
		$query->execute();
		

		echo '<Response><Hangup/></Response>';
	}
}
?>
