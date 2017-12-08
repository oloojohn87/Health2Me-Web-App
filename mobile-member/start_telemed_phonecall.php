<?php
header("Access-Control-Allow-Origin: *");
ini_set('max_execution_time', 300);

$pat_phone = '';
$doc_phone = '';
$pat_id = '';
$doc_id = '';
$pat_name = '';
$doc_name = '';

$pat_phone = "14697748178";
//$doc_phone = "214-734-9964";
$doc_phone ="19723756339";
$pat_id='2074';
//$doc_id = "288";
$doc_id= "2543";
$doc_name="Bruno Lima";
$pat_name="Lori Green";

echo $pat_phone;
echo $doc_phone;
echo $pat_id;
echo $doc_id;
echo $doc_name;
echo $pat_name;

require "../Services/Twilio.php";
require("../environment_detail.php");
require_once('../stripe/stripe/lib/Stripe.php');
require("../push_server.php");
$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];

$stripe = array(
  "secret_key"      => "sk_test_hJg0Ij3YDmTvpWMenFHf3MLn",
  "publishable_key" => "pk_test_YBtrxG7xwZU9RO1VY8SeaEe9"
);
Stripe::setApiKey($stripe['secret_key']);

if(isset($_POST['pat_phone']))
{
    $pat_phone = "+".$_POST["pat_phone"];
    $doc_phone = "+".$_POST["doc_phone"];
    
    $pat_id = $_POST['pat_id'];
    $doc_id = $_POST['doc_id'];
    
    $pat_name = $_POST['pat_name'];
    $doc_name = $_POST['doc_name'];
	

}
else
{

    
    $pat_id = $argv[1];
    $doc_id = $argv[2];
    
    $name = explode("_", $argv[3]);
    $pat_name = $name[0]." ".$name[1];
    $name = explode("_", $argv[4]);
    $doc_name = $name[0]." ".$name[1];
    
    $pat_phone = "+".$argv[5];
    $doc_phone = "+".$argv[6];
    
    $dbhost = "localhost";
}

$override = false;
if(isset($_POST['override']) && $_POST['override'] == true)
{
    $override = true;
}


$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	


$result = $con->prepare("SELECT id,in_consultation,stripe_id FROM doctors WHERE id=?");
$result->bindValue(1, $doc_id, PDO::PARAM_INT);
$result->execute();

$row = $result->fetch(PDO::FETCH_ASSOC);

$result = $con->prepare("SELECT stripe_id,plan,ownerAcc,subsType,GrantAccess FROM usuarios WHERE Identif=?");
$result->bindValue(1, $pat_id, PDO::PARAM_INT);
$result->execute();

$pat_row = $result->fetch(PDO::FETCH_ASSOC);

// check if the doctor is in a video consultation
if($row['in_consultation'] != 1)
{
    $API_VERSION = '2010-04-01';
    $AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
    $AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
    // Instantiate a new Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
    
    $docs_in_consultation = array();
    foreach ($client->account->conferences->getIterator(0, 50, array("Status" => "in-progress")) as $conference)
    {
        $conference_name = explode("_", $conference->friendly_name);
        $doc_id = intval($conference_name[0]);
        if(!in_array($doc_id, $docs_in_consultation))
        {
            array_push($docs_in_consultation, $doc_id);
        }
    }
    
    // check if the doctor is in a phone consultaiton
    if(!in_array(intval($row['id']), $docs_in_consultation))
    {
        
        // check if the user has a credit card in their account
        $has_credit_card = false;
        if(isset($pat_row['stripe_id']) && $pat_row['stripe_id'] != null && strlen($pat_row['stripe_id']) > 0)
        {
            $customer = Stripe_Customer::retrieve($pat_row['stripe_id']);
            $cards = Stripe_Customer::retrieve((htmlspecialchars($pat_row['stripe_id'])))->cards->all(array('limit' => 10));
            
            // check if the user has a credit card in their account
            if(count($cards["data"]) > 0)
            {
                $has_credit_card = true;
            }
        }
        
        if(!$has_credit_card && $pat_row['plan'] == 'FAMILY' && $pat_row['subsType'] != 'Owner')
        {
            $result = $con->prepare("SELECT stripe_id FROM usuarios WHERE Identif IN (SELECT ownerAcc FROM usuarios WHERE Identif = ?)");
            $result->bindValue(1, $pat_id, PDO::PARAM_INT);
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
        }
        
        
    
        if($has_credit_card || $pat_row['GrantAccess'] == 'HTI-COL' || $override)
        {
            $date = date_create();
            $timestamp = date_timestamp_get($date);
            $result = $con->prepare("UPDATE doctors SET cons_req_time=?, consultation_pat=? WHERE id=?");
            $result->bindValue(1, $timestamp, PDO::PARAM_STR);
            $result->bindValue(2, $pat_id, PDO::PARAM_INT);
            $result->bindValue(3, $doc_id, PDO::PARAM_INT);
            $result->execute();

            //$results = $con->prepare("insert into ameridoc_calls (patientId,doctorId,startDate,startTime) values(?,?,curdate(),curtime())");
            //$results->bindValue(1, $pat_id, PDO::PARAM_INT);
            //$results->bindValue(2, $doc_id, PDO::PARAM_INT);
            //$results->execute();
            $initial = substr($pat_phone,3);
            $contract = "";
            if($initial == '+57')
            {
                $contract = 'HTI-COL';
            }
            else
            {
                $contract = 'HTI-AAR';
            }
            $doc_names = explode(" ", $doc_name);
            $pat_names = explode(" ", $pat_name);
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
            $results = $con->prepare("INSERT INTO consults(contract,DateTime,status,type,Patient,Doctor,doctorName,doctorSurname,patientName,patientSurname,lastActive) values(?,NOW(),'In Progress','phone',?,?,?,?,?,?,NOW())");
            $results->bindValue(1, $contract, PDO::PARAM_STR);
            $results->bindValue(2, $pat_id, PDO::PARAM_INT);
            $results->bindValue(3, $doc_id, PDO::PARAM_INT);
            $results->bindValue(4, $doc_firstname, PDO::PARAM_STR);
            $results->bindValue(5, $doc_lastname, PDO::PARAM_STR);
            $results->bindValue(6, $pat_firstname, PDO::PARAM_STR);
            $results->bindValue(7, $pat_lastname, PDO::PARAM_STR);
            $results->execute();

            $recent_id = $con->lastInsertId(); 

            $conference_id = $doc_id."_".$pat_id;
            $xml = '<?php echo \'<?xml version="1.0" encoding="UTF-8" ?>\'; ?><Response><?php if(isset($_REQUEST["Digits"])){require("../environment_detail.php");$dbhost = $env_var_db["dbhost"];$dbname = $env_var_db["dbname"];$dbuser = $env_var_db["dbuser"];$dbpass = $env_var_db["dbpass"];$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");mysql_select_db("$dbname")or die("cannot select DB");$result = mysql_query("SELECT in_consultation FROM doctors where id='.$doc_id.'");$row = mysql_fetch_array($result);$count = $row["in_consultation"];/*if($count == "2"){$result = mysql_query("UPDATE doctors SET in_consultation=3 WHERE id='.$doc_id.'");*/$result = mysql_query("SELECT most_recent_doc FROM usuarios WHERE Identif='.$pat_id.'");$row = mysql_fetch_assoc($result);$str = $row["most_recent_doc"];$res = "";if(strlen($str) > 0){$str = str_replace(array("[", "]"), "", $str);$ids = explode(",", $str);$found = false;for($i = 0; $i < count($ids); $i++){if($ids[$i] == '.$doc_id.'){$found = true;}}if(!$found){array_unshift($ids , '.$doc_id.');while(count($ids) > 5){$doc = array_pop($ids);}}$res = implode(",", $ids);}else{$res = '.$doc_id.';}$new_ids = "[".$res."]";$result = mysql_query("UPDATE usuarios SET most_recent_doc=".$new_ids." WHERE Identif='.$pat_id.'");/*}else{$result = mysql_query("UPDATE doctors SET in_consultation=2 WHERE id='.$doc_id.'");}*/ echo \'<Dial action="../phone_appointment_handle_leave.php?id='.$doc_id.'" method="GET">
			<Conference record="record-from-start" maxParticipants="2" endConferenceOnExit="true" eventCallbackUrl="../phone_appointment_callback.php?info='.$doc_id.'_'.$pat_id.'_'.$recent_id.'">'.$doc_id.'_'.$pat_id.'</Conference>
			</Dial>\';} else {echo \'<Hangup/>\';} ?></Response><?php unlink(__FILE__); 
			
			if(isset($_REQUEST["Digits"])){
			$result = mysql_query("UPDATE consults SET Doctor_Status = 1 WHERE consultationId = '.$recent_id.'");
			}
			?>';
			
            file_put_contents('twiML_temp/'.$conference_id.'.php', $xml);
		

            $doc_url = 'http://'.$_SERVER['HTTP_HOST'].'/phone_appointment_ask.php?pat_name='.str_replace(" ", "%20", $pat_name).'&conference_id='.$conference_id;
            $pat_url = 'http://'.$_SERVER['HTTP_HOST'].'/phone_appointment_wait.php?info='.$doc_id.'-'.str_replace(" ", "%20",$doc_name).'-'.$conference_id.'-'.$pat_id.'-'.$recent_id;
            str_replace("-", "", $doc_phone);
            str_replace("-", "", $pat_phone);
			
			//THIS CATCHED TWILIO ERRORS AND ADDS THEM TO TWILIO_ERRORS TABLE
			try{
            $client->account->calls->create('+19034018888', $doc_phone, $doc_url);
			} catch (Exception $e){
			$error = $e->getMessage();
			$results2 = $con->prepare("INSERT INTO twilio_errors SET consult_id = ?, doc_id = ?, error = ?, patient_number = ?, doc_number = ?, type = ?");
            $results2->bindValue(1, $recent_id, PDO::PARAM_INT);
            $results2->bindValue(2, $doc_id, PDO::PARAM_INT);
			$results2->bindValue(3, $error, PDO::PARAM_STR);
			$results2->bindValue(4, $pat_phone, PDO::PARAM_STR);
			$results2->bindValue(5, $doc_phone, PDO::PARAM_STR);
			$results2->bindValue(6, 'phone', PDO::PARAM_STR);
            $results2->execute();
			}
			
			try{
            $call = $client->account->calls->create('+19034018888', $pat_phone, $pat_url);
			} catch (Exception $e){
			$error = $e->getMessage();
			$results2 = $con->prepare("INSERT INTO twilio_errors SET consult_id = ?, patient_id = ?, error = ?, patient_number = ?, doc_number = ?, type = ?");
            $results2->bindValue(1, $recent_id, PDO::PARAM_INT);
            $results2->bindValue(2, $pat_id, PDO::PARAM_INT);
			$results2->bindValue(3, $error, PDO::PARAM_STR);
			$results2->bindValue(4, $pat_phone, PDO::PARAM_STR);
			$results2->bindValue(5, $doc_phone, PDO::PARAM_STR);
			$results2->bindValue(6, 'phone', PDO::PARAM_STR);
            $results2->execute();
			}
            $sid = $call->sid;

            $results = $con->prepare("SELECT id FROM doctorslinkusers WHERE IdMED = ? AND IdUs = ? AND IdPIN is null");
            $results->bindValue(1, $doc_id, PDO::PARAM_INT);
            $results->bindValue(2, $pat_id, PDO::PARAM_INT);
            $results->execute();
            $rowCount = $results->rowCount();
            if($rowCount == 0)
            {
                $results = $con->prepare("insert into doctorslinkusers (IdMED,IdUs,Fecha,IdPIN,estado) values(?,?,NOW(),null,2)");
                $results->bindValue(1, $doc_id, PDO::PARAM_INT);
                $results->bindValue(2, $pat_id, PDO::PARAM_INT);
                $results->execute();
            }
            
            $app_key = 'd869a07d8f17a76448ed';
            $app_secret = '92f67fb5b104260bbc02';
            $app_id = '51379';
            $pusher = new Pusher($app_key, $app_secret, $app_id);
            $arr = array("id" => $pat_id, "name" =>$pat_name);
            $pusher->trigger($doc_id, 'telemed_phone_call', json_encode($arr));

            /*sleep(50);
            $result = $con->prepare("SELECT in_consultation FROM doctors WHERE id=?");
            $result->bindValue(1, $doc_id, PDO::PARAM_INT);
            $result->execute();

            $row = $result->fetch(PDO::FETCH_ASSOC);
            if($row['in_consultation'] != 3)
            {
                $result = $con->prepare("UPDATE doctors SET in_consultation=0,telemed_type=0 WHERE id=?");
                $result->bindValue(1, $doc_id, PDO::PARAM_INT);
                $result->execute();

            }*/
            echo $sid;
        }
        else
        {
            echo 'NC';
        }
    }
    else
    {
        echo 'IC';
    }
}
else
{
    echo 'IC';
}

?>


