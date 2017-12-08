<?php

session_start();
require "Services/Twilio.php";
require("environment_detail.php");
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


$doc_id = '';
if(isset($_POST['id']))
{
    $doc_id = $_POST['id'];
}
$type = $_POST['type'];
$loc1 = '';
$loc2 = '';
$must_be_available = true;
if(isset($_POST['location_1']))
{
    $loc1 = $_POST['location_1'];
}
if(isset($_POST['location_2']))
{
    $loc2 = $_POST['location_2'];
}
if(isset($_POST['must_be_available']))
{
    $must_be_available = $_POST['must_be_available'];
}
if(strlen($doc_id) > 0)
{
    $result = $con->prepare("SELECT phone,id,Name,Surname FROM doctors WHERE id=?");
	$result->bindValue(1, $doc_id, PDO::PARAM_INT);
	$result->execute();
	
    $row = $result->fetch(PDO::FETCH_ASSOC);
    echo json_encode(array("name" => $row['Name'].' '.$row['Surname'], "id" => $row['id'], "phone" => $row['phone']));
}
else
{
    $arr = findNextAvailableDoctor($type, $loc1, $loc2, $must_be_available);
    if($arr["name"] == '')
    {
        echo 'none';
    }
    else
    {
        echo json_encode($arr);
    }
}


function findNextAvailableDoctor($sp, $l1, $l2, $mba)
{
    require("environment_detail.php");
    $dbhost = $env_var_db['dbhost'];
    $dbname = $env_var_db['dbname'];
    $dbuser = $env_var_db['dbuser'];
    $dbpass = $env_var_db['dbpass'];

    $link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
    mysql_select_db("$dbname")or die("cannot select DB");

    function cleanquery($string)
    {
      if (get_magic_quotes_gpc())
      {
      $string = stripslashes($string);
      }
      $string = mysql_real_escape_string($string);
      return $string;
    }

    $sp = cleanquery($sp);
    $l1 = cleanquery($l1);
    $l2 = cleanquery($l2);
    $mba = cleanquery($mba);


    $API_VERSION = '2010-04-01';
    $AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
    $AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
    $client = new Services_Twilio($AccountSid, $AuthToken);
    
    $found = false;
    $result_id = -1;
    $result_phone = '';
    $result_name = '';
    $result_loc = '';
    date_default_timezone_set('GMT'); 
    $date = new DateTime('now');
    $docs_in_consultation = array();
    
    $query = "SELECT phone,id,Name,Surname,location FROM doctors WHERE telemed=1 AND speciality='".$sp."'";
    if($mba)
    {
        $query .= " AND in_consultation!=1";
        foreach ($client->account->conferences->getIterator(0, 50, array("Status" => "in-progress")) as $conference)
        {
            $conference_name = explode("_", $conference->friendly_name);
            $doc_id = intval($conference_name[0]);
            if(!in_array($doc_id, $docs_in_consultation))
            {
                array_push($docs_in_consultation, $doc_id);
            }
        }

        //$query .= "AND in_consultation=0";
    }
    if(strlen($l1) > 0 && strlen($l2) > 0)
    {
        $query .= ' AND (location = "'.$l1.'" OR location = "'.$l2.'")';
    }
    else if(strlen($l1) > 0 && strlen($l2) == 0)
    {
        $query .= ' AND location="'.$l1.'"';
    }
    else if(strlen($l1) == 0 && strlen($l2) > 0)
    {
        $query .= ' AND location="'.$l2.'"';
    }
    $query .= " ORDER BY cons_req_time";

	//echo("<script>console.log('PHP: ".$query."');</script>");  // DELETE
    
    $result = mysql_query($query);
    while(($row = mysql_fetch_assoc($result)) && !$found)
    {
        if($mba == true)
        {
            $result2 = mysql_query("SELECT * FROM timeslots WHERE doc_id=".$row['id']);
			//$queryJV = "SELECT * FROM timeslots WHERE doc_id=".$row['id'];  // DELETE
			//echo("<script>console.log('query:  ".$queryJV."');</script>");  // DELETE
            while(($row2 = mysql_fetch_assoc($result2)) && !$found)
            {
                $start = new DateTime($row2['week'].' '.$row2['start_time']);
                $end = new DateTime($row2['week'].' '.$row2['end_time']);
                $date_interval = new DateInterval('P'.$row2['week_day'].'D');
                $time_interval = new DateInterval('PT'.intval(substr($row2['timezone'], strlen($row2['timezone']) - 8, 2)).'H'.intval(substr($row2['timezone'], strlen($row2['timezone']) - 5, 2)).'M');
                if(substr($row2['timezone'], 0 , 1) != '-')
                {
                    $time_interval->invert = 1;
                }
                $start->add($date_interval);
                $end->add($date_interval);
                $start->add($time_interval);
                $end->add($time_interval);
                if($start <= $date && $end >= $date && !in_array(intval($row['id']), $docs_in_consultation))
                {
                    // doctor is available
                    $result_id = $row['id'];
                    $result_phone = $row['phone'];
                    $result_name = $row['Name'].'_'.$row['Surname'];
                    $result_loc = $row['location'];
                    $found = true;
                }
                
            }
        }
        else
        {
            $result_id = $row['id'];
            $result_phone = $row['phone'];
            $result_name = $row['Name'].'_'.$row['Surname'];
            $result_loc = $row['location'];
            $found = true;
        }
    }
    return array("name" => $result_name, "id" => $result_id, "phone" => $result_phone, "location" => $result_loc);
}


?>