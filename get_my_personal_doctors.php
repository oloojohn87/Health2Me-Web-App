<?php

/*
 *  This file gets information for the user's current personal doctor
 */

require("environment_detail.php");
require "Services/Twilio.php";
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

// get all of the doctors that are currently in a Twilio conference so we can see whether the personal doctor is currently available or not
$docs_in_consultation = array();
try
{
    foreach ($client->account->conferences->getIterator(0, 50, array("Status" => "in-progress")) as $conference)
    {
        $conference_name = explode("_", $conference->friendly_name);
        $doc_id = intval($conference_name[0]);
        if(!in_array($doc_id, $docs_in_consultation))
        {
            array_push($docs_in_consultation, $doc_id);
        }
    }
}catch(Exception $e)
{
    ;
}

if(isset($_POST['id']))
{
    // get user's personal doctor
    
    $res = $con->prepare("SELECT personal_doctor,personal_doctor_accepted FROM usuarios WHERE Identif = ?");
    $res->bindValue(1, $_POST['id'], PDO::PARAM_INT);
    $res->execute();
    $pat_row = $res->fetch(PDO::FETCH_ASSOC);
    
    if($pat_row['personal_doctor'] != NULL)
    {
        $res = $con->prepare("SELECT * FROM doctors WHERE id = ?");
        $res->bindValue(1, $pat_row['personal_doctor'], PDO::PARAM_INT);
        $res->execute();
        
        $med_row = $res->fetch(PDO::FETCH_ASSOC);
        
        // search for the doctor's image by checking various file types in the DoctorImage folder
        $med_row['image'] = md5( strtolower( trim( $med_row['IdMEDEmail'] ) ) );
        if(file_exists('DoctorImage/'.$med_row['id'].'.gif'))
        {
            $med_row['image'] = 'DoctorImage/'.$med_row['id'].'.gif';
        }
        else if(file_exists('DoctorImage/'.$med_row['id'].'.jpg'))
        {
            $med_row['image'] = 'DoctorImage/'.$med_row['id'].'.jpg';
        }
        else if(file_exists('DoctorImage/'.$med_row['id'].'.png'))
        {
            $med_row['image'] = 'DoctorImage/'.$med_row['id'].'.png';
        }
        
        $med_row['available'] = $med_row['in_consultation'] != 1 && !in_array(intval($med_row["id"]), $docs_in_consultation) && isDoctorAvailable($med_row["id"], $con);
        
        $med_row['rating'] = unserialize($med_row['rating']);
        
        $med_row['hospital_addr'] = str_replace("\n", "<br/>", $med_row['hospital_addr']);
        
        $cer_query = $con->prepare("SELECT * FROM certifications WHERE doc_id = ?");
        $cer_query->bindValue(1, $med_row['id'], PDO::PARAM_INT);
        $cer_query->execute();
        $certifications = array();
        while($cer_row = $cer_query->fetch(PDO::FETCH_ASSOC))
        {
            array_push($certifications, $cer_row);
        }
        
        $cons_query = $con->prepare("SELECT consultationId FROM consults WHERE Doctor = ?");
        $cons_query->bindValue(1, $med_row['id'], PDO::PARAM_INT);
        $cons_query->execute();
        $num_cons = $cons_query->rowCount();
        
        $med_row['consultations'] = $num_cons;
        
        $med_row['certifications'] = $certifications;
        
        $recs = array();
        $protected_recs = array();
        $max_records = 0;
        
        getRecords($med_row['id'], $_POST['id'], $recs, $protected_recs, $max_records, $con);
        
        $med_row['records'] = $recs;
        $med_row['protected_records'] = $protected_recs;
        $med_row['max_records'] = $max_records;

        
        $new_messages = $con->prepare("SELECT DISTINCT message_id FROM message_infrastructureuser WHERE (sender_id = ? OR receiver_id = ?) AND patient_id = ? AND tofrom = 'from' AND status = 'new'");
        $new_messages->bindValue(1, $med_row['id'], PDO::PARAM_INT);
        $new_messages->bindValue(2, $med_row['id'], PDO::PARAM_INT);
        $new_messages->bindValue(3, $_POST['id'], PDO::PARAM_INT);
        $new_messages->execute();
        
        $med_row['num_new_messages'] = $new_messages->rowCount();
        
        // if the doctor has been accepted by the user
        $med_row['accepted'] = $pat_row['personal_doctor_accepted'];
        
        $rep_query = $con->prepare("SELECT IdPin FROM lifepin WHERE IdUsu = ? AND hide_from_member != 1");
        $rep_query->bindValue(1, $_POST['id'], PDO::PARAM_INT);
        $rep_query->execute();
        
        $med_row['hidden_reps'] = $rep_query->rowCount();
        
        
        echo json_encode($med_row);
    }
    else
    {
        echo 'ND'; // No Doctor
    }
}

// this function finds out whether the doctor passed in is available or not based on the entries in the timeslots table
function isDoctorAvailable($id, $con)
{
    // get all of the timeslots for the doctor in this week
    $query4 = $con->prepare('SELECT * FROM timeslots WHERE doc_id = ? AND week >= DATE_SUB(curdate(), INTERVAL 1 WEEK)');
    $query4->bindValue(1, $id, PDO::PARAM_INT);
    $query4->execute();
    $num_rows = $query4->rowCount();
    $is_available = false;
    if($num_rows > 0)
    {
        while ($row4 = $query4->fetch(PDO::FETCH_ASSOC)) 
        {
            // obtain the start and end dates by adding the week day to the week (sunday)
            $start = new DateTime($row4['week'].' '.$row4['start_time']);
            $end = new DateTime($row4['week'].' '.$row4['end_time']);
            $date_interval = new DateInterval('P'.$row4['week_day'].'D');
            $time_interval = null;
            $start->add($date_interval);
            $end->add($date_interval);
            $timezone = $row4['timezone'];
            
            // adjust for user's timezone
            if(strlen($timezone) == 9)
            {
                $timezone = str_replace("-", "", $timezone);
                $elements = explode(":", $timezone);
                $time_interval = new DateInterval('PT'.intval($elements[0]).'H'.intval($elements[1]).'M');
            }
            else
            {
                $elements = explode(":", $timezone);
                $time_interval = new DateInterval('PT'.intval($elements[0]).'H'.intval($elements[1]).'M');
                $time_interval->invert = 1;
            }
            $start->add($time_interval);
            $end->add($time_interval);
            
            // adjust for the server's timezone
            $date = new DateTime('now');
            $current_timezone = $date->format("Z");
            if($current_timezone[0] == '-')
            {
                $current_timezone = str_replace("-", "", $current_timezone);
                $tmz_interval = new DateInterval('PT'.intval($current_timezone).'S');
                $date->add($tmz_interval);
            }
            else
            {
                $tmz_interval = new DateInterval('PT'.intval($current_timezone).'S');
                $tmz_interval->invert = 1;
                $date->add($tmz_interval);
            }
            
            // if the $date is between the beginning and end dates for the timeslot, the doctor is available
            if($start <= $date && $end >= $date)
            {
                $is_available = true;
            }
            
        }
    }
    return $is_available;
}

function getRecords($med_id, $pat_id, &$recs, &$protected_recs, &$max_recs, &$con)
{
    // get files that the doctor has access to granted by the patient
    $all_recs = false;
    $all_recs_protected = false;
    $records = $con->prepare("SELECT DISTINCT IdPIN FROM doctorslinkusers WHERE IdMED = ? AND IdUs = ?");
    $records->bindValue(1, $med_id, PDO::PARAM_INT);
    $records->bindValue(2, $pat_id, PDO::PARAM_INT);
    $records->execute();
    while($rec_row = $records->fetch(PDO::FETCH_ASSOC))
    {
        if($rec_row['IdPIN'] == NULL)
        {
            $all_recs = true;
        }
        else
        {
            array_push($recs, $rec_row['IdPIN']);
        }
    }
    
    
    // get all files that the doctor has access to that was not granted by the patient
    $q = $con->prepare("Select IdCreator from usuarios where Identif = ? and IdCreator = ?");

    $q->bindValue(1, $pat_id, PDO::PARAM_INT);
    $q->bindValue(2, $med_id, PDO::PARAM_INT);
    $result = $q->execute();

    if($q->rowCount() > 0)
    {
        $all_recs_protected = true;
    }


    if(!$all_recs)
    {

        $q=$con->prepare("Select IdCreator from usuarios USU INNER JOIN (select distinct(B.idDoctor) from ".$dbname.".doctorsgroups B INNER JOIN (select A.idGroup,A.idDoctor from ".$dbname.".doctorsgroups A where A.idDoctor=?) C where B.idGroup=C.idGroup) DG  where USU.Identif=? and DG.idDoctor=USU.IdCreator");

        $q->bindValue(1, $med_id, PDO::PARAM_INT);
        $q->bindValue(2, $pat_id, PDO::PARAM_INT);
        $result = $q->execute();

        if($q->rowCount() > 0)
        {
            $all_recs_protected = true;
        }
        
    }

    if(!$all_recs)
    {

        $q=$con->prepare("select IdMED2 from ".$dbname.".doctorslinkdoctors where IdMED2=? and IdPac=? and estado=2");

        $q->bindValue(1, $med_id, PDO::PARAM_INT);
        $q->bindValue(2, $pat_id, PDO::PARAM_INT);
        $result = $q->execute();

        if($q->rowCount() > 0)
        {
            $all_recs_protected = true;
        }
    }
    if(!$all_recs)
    {

        $q=$con->prepare("select IdPin from ".$dbname.".lifepin where IdMed=? and IdUsu=? LIMIT 1");

        $q->bindValue(1, $med_id, PDO::PARAM_INT);
        $q->bindValue(2, $pat_id, PDO::PARAM_INT);

        $result = $q->execute();

        if($q->rowCount() > 0)
        {
            while($row = $q->fetch(PDO::FETCH_ASSOC))
            {
                array_push($protected_recs, $row['IdPin']);
            }
        }
    }
    
    
    $num_records = $con->prepare("SELECT DISTINCT IdPin FROM lifepin WHERE IdUsu = ? AND IdPin IS NOT NULL AND hide_from_member = 0");
    $num_records->bindValue(1, $pat_id, PDO::PARAM_INT);
    $num_records->execute();
    
    $max_recs = $num_records->rowCount();
    
    if($all_recs)
    {
        $recs = array();
        while($rec_row = $num_records->fetch(PDO::FETCH_ASSOC))
        {
            array_push($recs, $rec_row['IdPin']);
        }
    }
    else if($all_recs_protected)
    {
        $protected_recs = array();
        while($rec_row = $num_records->fetch(PDO::FETCH_ASSOC))
        {
            array_push($protected_recs, $rec_row['IdPin']);
        }
    }
    
    //$recs = array_diff($recs, $protected_recs);
}

?>
