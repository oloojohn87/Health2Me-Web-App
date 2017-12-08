<?php
require("environment_detail.php");
$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$res = $con->prepare("SELECT * FROM timeslots WHERE doc_id=?");
$res->bindValue(1, $_POST['id'], PDO::PARAM_INT);
$res->execute();

$arr = array('0' => array(), '1' => array(), '2' => array(), '3' => array(), '4' => array(), '5' => array(), '6' => array());
$zones = array('0' => array("00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00"),
               '1' => array("00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00"),
               '2' => array("00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00"),
               '3' => array("00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00"),
               '4' => array("00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00"),
               '5' => array("00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00"),
               '6' => array("00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00", "00:00:00"));
$today = new DateTime('now');
$today->setTime(0, 0, 0);
while($row = $res->fetch(PDO::FETCH_ASSOC))
{
    
    $date_interval = new DateInterval('P'.($row['week_day']+1).'D');
    $start = new DateTime($row['week'].' 00:00:00');
    $end = new DateTime($row['week'].' 00:00:00');
    $start->add($date_interval);
    $end->add($date_interval);
    $interval = $today->diff($start);
    if($start >= $today)
    {
        $dow = intval($start->format('N'));
        if($interval->d >= 0 && $interval->d < 7)
        {
            
            if(strcmp($row['end_time'], '08:00:00') >= 0 && strcmp($row['start_time'], '10:00:00') <= 0)
            {
                if(!in_array(0, $arr[$dow]))
                {
                    array_push($arr[$dow], 0);
                    $zones[$dow][0] = $row['timezone'];
                }
            }
            if(strcmp($row['end_time'], '10:00:00') >= 0 && strcmp($row['start_time'], '12:00:00') <= 0)
            {
                if(!in_array(1, $arr[$dow]))
                {
                    array_push($arr[$dow], 1);
                    $zones[$dow][1] = $row['timezone'];
                }
            }
            if(strcmp($row['end_time'], '12:00:00') >= 0 && strcmp($row['start_time'], '14:00:00') <= 0)
            {
                if(!in_array(2, $arr[$dow]))
                {
                    array_push($arr[$dow], 2);
                    $zones[$dow][2] = $row['timezone'];
                }
            }
            if(strcmp($row['end_time'], '14:00:00') >= 0 && strcmp($row['start_time'], '16:00:00') <= 0)
            {
                if(!in_array(3, $arr[$dow]))
                {
                    array_push($arr[$dow], 3);
                    $zones[$dow][3] = $row['timezone'];
                }
            }
            if(strcmp($row['end_time'], '16:00:00') >= 0 && strcmp($row['start_time'], '18:00:00') <= 0)
            {
                if(!in_array(4, $arr[$dow]))
                {
                    array_push($arr[$dow], 4);
                    $zones[$dow][4] = $row['timezone'];
                }
            }
            if(strcmp($row['end_time'], '18:00:00') >= 0 && strcmp($row['start_time'], '20:00:00') <= 0)
            {
                if(!in_array(5, $arr[$dow]))
                {
                    array_push($arr[$dow], 5);
                    $zones[$dow][5] = $row['timezone'];
                }
            }
            if(strcmp($row['end_time'], '20:00:00') >= 0 && strcmp($row['start_time'], '22:00:00') <= 0)
            {
                if(!in_array(6, $arr[$dow]))
                {
                    array_push($arr[$dow], 6);
                    $zones[$dow][6] = $row['timezone'];
                }
            }
            
        }
    }
}

echo json_encode(array("slots" => $arr, "zones" => $zones));

?>
