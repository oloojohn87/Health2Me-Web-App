<?php
require_once("environment_detail.php");
require_once("Services/Twilio.php"); // Loads the library

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

$API_VERSION = '2010-04-01';
$AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
$AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";

$client = new Services_Twilio($AccountSid, $AuthToken);

$call = $client->account->calls->get($_GET['sid']);
$call_status = $call->status;
$call_status = str_replace("-", " ", $call_status);
$call_status = ucwords($call_status);

if($call_status == ''){
echo "Doctor did not answer";

if(isset($_GET['docid'])){
$query = $con->prepare("UPDATE consults SET status = 'Not answered by doctor.' WHERE Status = 'In Progress' && Doctor = ?");
$query->bindValue(1, $_GET['docid'], PDO::PARAM_INT);
$query->execute();
}
}else{
echo $call_status;
}

?>
