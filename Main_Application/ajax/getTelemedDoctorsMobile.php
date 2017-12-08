<?php
header("Access-Control-Allow-Origin: *");
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("environment_detail.php");
 require "Services/Twilio.php";
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="messages"; // Table name

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

$starting_point = $_POST['starting_point'];
$order = $_POST['order'];
$asc = $_POST['asc'];
$timezone = $_POST['timezone'];
$online_only = 0;
$telemed_only = 0;
$search = '';
$speciality = '';
$location = '';
$hourly_rate = 0;
if(isset($_POST['online_only']))
{
    $online_only = $_POST['online_only'];
}
if(isset($_POST['telemed_only']))
{
    $telemed_only = $_POST['telemed_only'];
}
if(isset($_POST['search_term']))
{
    $search = $_POST['search_term'];
}
if(isset($_POST['speciality']))
{
    $speciality = $_POST['speciality'];
}
if(isset($_POST['location']))
{
    $location = $_POST['location'];
}
if(isset($_POST['hourly_rate']))
{
    $hourly_rate = $_POST['hourly_rate'];
}

/*
$query = "SELECT * FROM doctors WHERE IdMEDEmail is not null ";
if($telemed_only == 1)
{
    $query .= 'AND telemed=1 ';
}
if(strlen($speciality) > 0)
{
    $query .= 'AND speciality="'.$speciality.'" ';
}
if(strlen($location) > 0)
{
    $query .= 'AND location LIKE "%'.$location.'" ';
}
if($hourly_rate > 0)
{
    $query .= 'AND hourly_rate='.$hourly_rate.' ';
}
if(strlen($search) > 0)
{
    $query .= "AND LOWER(CONCAT(name, ' ', surname)) LIKE '%".strtolower($search)."%'";
}
if($order == 0) // name
{
    $query .= "ORDER BY surname";
    if($asc == 0)
    {
        $query .= " DESC";
    }
    $query .= ", name";
}
else if($order == 1) // rating
{
    $query .= "ORDER BY rating_score";
    if($asc == 1)
    {
        $query .= " DESC";
    }
}
else if($order == 2) // hourly rate
{
    $query .= "ORDER BY hourly_rate";
}
if($asc == 0 && $order != 1)
{
    $query .= " DESC";
}
$query .= " LIMIT ".$starting_point.", 18446744073709551615";
*/

$specialty_spot = 0;
$location_spot = 0;
$hourlyrate_spot = 0;
$search_spot = 0;
$next_spot = 1;
$query = "SELECT * FROM doctors WHERE IdMEDEmail is not null AND Name NOT LIKE '% %' AND Surname NOT LIKE '% %' ";
if($telemed_only == 1)
{
    $query .= 'AND telemed = 1 ';
}
if(strlen($speciality) > 0)
{
    $query .= 'AND speciality = ? ';
    $specialty_spot = $next_spot;
    $next_spot += 1;
}
if(strlen($location) > 0)
{
    $query .= 'AND location LIKE ? ';
    $location_spot = $next_spot;
    $next_spot += 1;
}
if($hourly_rate > 0)
{
    $query .= 'AND hourly_rate = ? ';
    $hourlyrate_spot = $next_spot;
    $next_spot += 1;
}
if(strlen($search) > 0)
{
    $query .= "AND LOWER(CONCAT(name, ' ', surname)) LIKE ? ";
    $search_spot = $next_spot;
    $next_spot += 1;
}
if($order == 0) // name
{
    $query .= "ORDER BY surname";
    if($asc == 0)
    {
        $query .= " DESC";
    }
    $query .= ", name";
}
else if($order == 1) // rating
{
    $query .= "ORDER BY rating_score";
    if($asc == 1)
    {
        $query .= " DESC";
    }
}
else if($order == 2) // hourly rate
{
    $query .= "ORDER BY hourly_rate";
}
if($asc == 0 && $order != 1)
{
    $query .= " DESC";
}
$query .= " LIMIT ?, 18446744073709551615";
//echo $query;
$result = $con->prepare($query);
if(strlen($speciality) > 0)
{
    //echo "Speciality: ".$specialty_spot." - ".$speciality;
    $result->bindValue($specialty_spot, $speciality, PDO::PARAM_STR);
}
if(strlen($location) > 0)
{
    //echo "Location: ".$location_spot." - ".$location;
    $result->bindValue($location_spot, $location, PDO::PARAM_STR);
}
if($hourly_rate > 0)
{
    //echo "Hourly: ".$hourly_spot." - ".$hourly_rate;
    $result->bindValue($hourlyrate_spot, $hourly_rate, PDO::PARAM_INT);
}
if(strlen($search) > 0)
{
    //echo "Search: ".$search_spot." - ".$search;
    $result->bindValue($search_spot, "%".$search."%", PDO::PARAM_STR);
}
//echo "LIMIT: ".$next_spot." - ".$starting_point;
$result->bindValue($next_spot, $starting_point, PDO::PARAM_INT);
$result->execute();


$data = array();
$symbols = array(":", "-", " ");
$count = 0;
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
while (($row = $result->fetch(PDO::FETCH_ASSOC)) && $count < 11) 
{
    /*
    $query2 = 'SELECT * FROM appointments WHERE med_id='.$row["id"];
    $result2 = mysql_query($query2);
    $appointments = array();
    while ($row2 = mysql_fetch_assoc($result2)) 
    {
        $str = str_replace($symbols, "", substr($row2["date"], 0, strlen($row2["date"]) - 3));
        $dur = $row2["duration"];
        if($dur < 100)
        {
            $str .= "0";
            if($dur < 10)
            {
                $str .= "0";
            }
        }
        $str .= $dur;
        $timezone = str_replace(":", "", substr($row2["timezone"], 0, strlen($row2["timezone"]) - 3));
        if($timezone[0] != '-')
        {
            $timezone = "+".$timezone;
        }
        $str .= $timezone;
        array_push($appointments, $str);
    }
    */
    //$query3 = 'SELECT userid FROM ongoing_sessions WHERE userid='.$row["id"];
    //$result3 = mysql_query($query3);
    //$num = mysql_num_rows($result3);
    $num_per_page = 3;
    $avail = $row['in_consultation'] != 1 && !in_array(intval($row["id"]), $docs_in_consultation) && isDoctorAvailable($row["id"], $con);
    if($online_only == 0 || ($online_only == 1 && $avail))
    {
        if($count < 3)
        {
            $image = md5(strtolower(trim($row['IdMEDEmail'])));
            if(file_exists('DoctorImage/'.$row['id'].'.gif'))
            {
                $image = 'DoctorImage/'.$row['id'].'.gif';
            }
            else if(file_exists('DoctorImage/'.$row['id'].'.jpg'))
            {
                $image = 'DoctorImage/'.$row['id'].'.jpg';
            }
            else if(file_exists('DoctorImage/'.$row['id'].'.png'))
            {
                $image = 'DoctorImage/'.$row['id'].'.png';
            }
            $cer_query = $con->prepare("SELECT * FROM certifications WHERE doc_id = ?");
            $cer_query->bindValue(1, $row['id'], PDO::PARAM_INT);
            $cer_query->execute();
            $certifications = array();
            while($cer_row = $cer_query->fetch(PDO::FETCH_ASSOC))
            {
                array_push($certifications, $cer_row);
            }
            $row['hospital_addr'] = str_replace("\n", "<br/>", $row['hospital_addr']);
            $cons_query = $con->prepare("SELECT consultationId FROM consults WHERE Doctor = ?");
            $cons_query->bindValue(1, $row['id'], PDO::PARAM_INT);
            $cons_query->execute();
            $num_cons = $cons_query->rowCount();
            $arr = array("name" => $row["Name"], "surname" => $row["Surname"], "rating" => unserialize($row["rating"]), "hourly_rate" => $row["hourly_rate"], "speciality" => $row["speciality"], "location" => $row["location"], "id" => $row["id"], /*"timeslots" => $timeslots, "appointments" => $appointments,*/ "telemed" => $row["telemed"], "available" => $avail, "phone" => $row["phone"], "image" => $image, "certifications" => $certifications, "consultations" => $num_cons, "hospital_name" => $row['hospital_name'], "hospital_addr" => $row['hospital_addr']);
            array_push($data, $arr);
        }
        $count++;
    }
    if($count < 10)
    {
        $starting_point++;
    }
}
$next = false;
if($count > 10)
{
    $next = true;
    $count -= 1;
}

echo json_encode(array("data" => $data, "next" => $next, "next_result" => $starting_point + 1));


function isDoctorAvailable($id, $con)
{
    $query4 = $con->prepare('SELECT * FROM timeslots WHERE doc_id = ? AND week >= DATE_SUB(curdate(), INTERVAL 1 WEEK)');
    $query4->bindValue(1, $id, PDO::PARAM_INT);
    $query4->execute();
    $num_rows = $query4->rowCount();
    $is_available = false;
    if($num_rows > 0)
    {
        while ($row4 = $query4->fetch(PDO::FETCH_ASSOC)) 
        {
            $start = new DateTime($row4['week'].' '.$row4['start_time']);
            $end = new DateTime($row4['week'].' '.$row4['end_time']);
            $date_interval = new DateInterval('P'.$row4['week_day'].'D');
            $time_interval = null;
            $start->add($date_interval);
            $end->add($date_interval);
            $timezone = $row4['timezone'];
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
            if($start <= $date && $end >= $date)
            {
                $is_available = true;
            }
            
        }
    }
    return $is_available;
}
    

?>


