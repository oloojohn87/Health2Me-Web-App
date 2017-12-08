<?php
// Get the PHP helper library from twilio.com/docs/php/install
require_once("Services/Twilio.php"); // Loads the library
require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$API_VERSION = '2010-04-01';
$AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
$AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}		

// Your Account Sid and Auth Token from twilio.com/user/account
$client = new Services_Twilio($AccountSid, $AuthToken);

$res = array();

$three_days_ago = date('Y-m-d', strtotime('-10 days', strtotime(date('Y-m-d'))));
 
// Loop over the list of records and echo a property for each one
foreach ($client->account->calls->getIterator(0, 50, array("StartTime>" => $three_days_ago)) as $call) 
{
    $to = $call->to;
    $from = $call->from;
    $to = str_replace("+", "", $to);
    $from = str_replace("+", "", $from);
    
    $direction = "User called Health2Me";
    if($call->direction[0] == 'o')
    {
        $direction = "Health2Me called User";
    }
    
    $to_name = "Undefined";
    $result = $con->prepare("SELECT Name,Surname FROM usuarios where telefono = ?");
    $result->bindValue(1, $to, PDO::PARAM_STR);
    $result->execute();
    
    $pat_count = $result->rowCount();
    $pat_row = $result->fetch(PDO::FETCH_ASSOC);
    
    $result = $con->prepare("SELECT Name,Surname FROM doctors where phone = ?");
    $result->bindValue(1, $to, PDO::PARAM_STR);
    $result->execute();
    
    $doc_count = $result->rowCount();
    $doc_row = $result->fetch(PDO::FETCH_ASSOC);
    if($to == '19034018888')
    {
        $to_name = "Health2Me";
    }
    else if(($doc_count + $pat_count) > 1)
    {
        $to_name = "Multiple Users";
    }
    else if($doc_count == 1)
    {
        $to_name = $doc_row['Name'].' '.$doc_row['Surname'];
        if(strlen($to_name) < 2)
        {
            $to_name = "Undefined";
        }
    }
    else if($pat_count == 1)
    {
        $to_name = $pat_row['Name'].' '.$pat_row['Surname'];
        if(strlen($to_name) < 2)
        {
            $to_name = "Undefined";
        }
    }
    else
    {
        $to_name = "Undefined";
    }
    
    $from_name = "Undefined";
    $result = $con->prepare("SELECT Name,Surname FROM usuarios where telefono = ?");
    $result->bindValue(1, $from, PDO::PARAM_STR);
    $result->execute();
    
    $pat_count = $result->rowCount();
    $pat_row = $result->fetch(PDO::FETCH_ASSOC);
    
    $result = $con->prepare("SELECT Name,Surname FROM doctors where phone = ?");
    $result->bindValue(1, $from, PDO::PARAM_STR);
    $result->execute();
    
    $doc_count = $result->rowCount();
    $doc_row = $result->fetch(PDO::FETCH_ASSOC);
    if($from == '19034018888')
    {
        $from_name = "Health2Me";
    }
    else if(($doc_count + $pat_count) > 1)
    {
        $from_name = "Multiple Users";
    }
    else if($doc_count == 1)
    {
        $from_name = $doc_row['Name'].' '.$doc_row['Surname'];
        if(strlen($from_name) < 2)
        {
            $from_name = "Undefined";
        }
    }
    else if($pat_count == 1)
    {
        $from_name = $pat_row['Name'].' '.$pat_row['Surname'];
        if(strlen($from_name) < 2)
        {
            $from_name = "Undefined";
        }
    }
    else
    {
        $from_name = "Undefined";
    }
    
    
    
    $res[$call->date_created] = array("to" => $to, "from" => $from, "to_name" => $to_name, "from_name" => $from_name, "status" => $call->status, "start_time" => $call->start_time, "end_time" => $call->end_time, "direction" => $direction, "sid" => $call->sid);
}

echo json_encode($res);

?>