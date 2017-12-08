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



if(isset($_GET['id']))
{
    $get = $con->prepare("SELECT * FROM appointments WHERE id = ?");
    $get->bindValue(1, $_GET['id'], PDO::PARAM_INT);
    $get->execute();
    $row = $get->fetch(PDO::FETCH_ASSOC);
    
    $specific_time = null;
    if(isset($row['specific_time']))
    {
        $time_info = explode(':', $row['specific_time']);
        $hour = intval($time_info[0]);
        $meridian = 'am';
        $minutes = $time_info[1];
        if($hour > 11)
        {
            $meridian = 'pm';
            if($hour > 12)
            {
                $hour -= 12;
            }
        }
        $specific_time = $hour.':'.$minutes.$meridian;
    }

    $orig_date = new DateTime($row['date']);
    $formatted_date = $orig_date->format('M jS, Y');

    $orig_date = new DateTime($row['start_time']);
    $formatted_start_time = $orig_date->format('g:i A');

    $orig_date = new DateTime($row['end_time']);
    $formatted_end_time = $orig_date->format('g:i A');

    $formatted_specific_time = '';
    if(isset($row['specific_time']))
    {
        $orig_date = new DateTime($row['specific_time']);
        $formatted_specific_time = $orig_date->format('g:i A');
    }
    
    $result = array("start_time" => (htmlspecialchars($row['start_time'])), "end_time" => (htmlspecialchars($row['end_time'])), "date" => (htmlspecialchars($row['date'])), "formatted_date" => $formatted_date, "formatted_start_time" => $formatted_start_time, "formatted_end_time" => $formatted_end_time, "formatted_specific_time" => $formatted_specific_time, "id" => (htmlspecialchars($row['id'])), "specific_time" => (htmlspecialchars($row['specific_time'])), "doc_ack" => $row['doctor_ack']);
    echo json_encode($result);
}
else
{
    $doc_id = $_GET['doc_id'];


    $res_arr = array();

    $res = NULL;
    if(isset($_GET['pat_id']))
    {
        $pat_id = $_GET['pat_id'];
        $res = $con->prepare("SELECT * FROM appointments WHERE med_id = ? AND pat_id = ? AND processed = 0 AND date > NOW() - INTERVAL 1 DAY ORDER BY date, start_time, id");
        $res->bindValue(1, $doc_id, PDO::PARAM_INT);
        $res->bindValue(2, $pat_id, PDO::PARAM_INT);
    }
    else
    {
        $res = $con->prepare("SELECT * FROM appointments WHERE med_id = ? AND processed = 0 AND date > NOW() - INTERVAL 1 DAY ORDER BY date, start_time, id");
        $res->bindValue(1, $doc_id, PDO::PARAM_INT);
    }
    $res->execute();

    while($row = $res->fetch(PDO::FETCH_ASSOC))
    {
        $specific_time = null;
        if(isset($row['specific_time']))
        {
            $time_info = explode(':', $row['specific_time']);
            $hour = intval($time_info[0]);
            $meridian = 'am';
            $minutes = $time_info[1];
            if($hour > 11)
            {
                $meridian = 'pm';
                if($hour > 12)
                {
                    $hour -= 12;
                }
            }
            $specific_time = $hour.':'.$minutes.$meridian;
        }

        $orig_date = new DateTime($row['date']);
        $formatted_date = $orig_date->format('M jS, Y');

        $orig_date = new DateTime($row['start_time']);
        $formatted_start_time = $orig_date->format('g:i A');

        $orig_date = new DateTime($row['end_time']);
        $formatted_end_time = $orig_date->format('g:i A');

        $formatted_specific_time = '';
        if(isset($row['specific_time']))
        {
            $orig_date = new DateTime($row['specific_time']);
            $formatted_specific_time = $orig_date->format('g:i A');
        }

        $res2 = $con->prepare("SELECT Name,Surname FROM usuarios WHERE Identif=?");
        $res2->bindValue(1, $row['pat_id'], PDO::PARAM_INT);
        $res2->execute();

        $pat_row = $res2->fetch(PDO::FETCH_ASSOC);
        array_push($res_arr, array("pat_id" => (htmlspecialchars($row['pat_id'])), "start_time" => (htmlspecialchars($row['start_time'])), "end_time" => (htmlspecialchars($row['end_time'])), "date" => (htmlspecialchars($row['date'])), "date_created" => $row['date_created'], "formatted_date" => $formatted_date, "formatted_start_time" => $formatted_start_time, "formatted_end_time" => $formatted_end_time, "formatted_specific_time" => $formatted_specific_time, "pat_name" => (htmlspecialchars($pat_row['Name'])).' '.(htmlspecialchars($pat_row['Surname'])), "id" =>(htmlspecialchars($row['id'])), "specific_time" => (htmlspecialchars($row['specific_time'])), "doc_ack" => $row['doctor_ack']));
    }

    echo json_encode($res_arr);
}

?>