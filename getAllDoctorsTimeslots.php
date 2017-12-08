<?php
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

$tempDate = date('Y-m-d');
$period = $_POST['period'];
$scope = $_POST['scope'];
$dow = intval(date('N', strtotime($tempDate)));

$num_slots = 96;
if($period == 2)
{
    $num_slots = 84;
}
else if($period == 3)
{
    $num_slots = 124;
}

if($dow == 7)
{
    $dow = 0;
}

$date = new DateTime($tempDate);
if($dow > 0)
{
    $date->sub(new DateInterval('P'.$dow.'D'));
}
$week = $date->format('Y-m-d');
$result = '';
if($period == 1)
{
    $result = $con->prepare("SELECT * FROM timeslots WHERE week = ? AND week_day = ?");
    $result->bindValue(1, $week, PDO::PARAM_STR);
    $result->bindValue(2, $dow, PDO::PARAM_INT);
}
else if($period == 2)
{
    $result = $con->prepare("SELECT * FROM timeslots WHERE week = ?");
    $result->bindValue(1, $week, PDO::PARAM_STR);
}
else
{
    $month = date("Y-m");
    $result = $con->prepare("SELECT * FROM timeslots WHERE DATE_ADD(week, INTERVAL week_day DAY) LIKE ?");
    $result->bindValue(1, $month."%", PDO::PARAM_STR);
}
$result->execute();

$result_array = array();

$scope_search = false;
if($scope != 'Global');
{
    $val = 0;
    if($scope == 'NULL')
    {
        $val = 1;
    }
    else if($scope == 'HTI-COL')
    {
        $val = 3;
    }
    $scope_result = $con->prepare("SELECT id FROM doctors WHERE previlege = ?");
    $scope_result->bindValue(1, $val, PDO::PARAM_INT);
    $scope_result->execute();
    $scope_search = array();
    if($scope_result->rowCount() > 0)
    {
        while($scope_row = $scope_result->fetch(PDO::FETCH_ASSOC))
        {
            array_push($scope_search, $scope_row['id']);
        }
    }
    
    
}

while($row = $result->fetch(PDO::FETCH_ASSOC))
{
    if($scope == 'Global' || in_array($row['doc_id'], $scope_search))
    {
        if(array_key_exists($row['doc_id'], $result_array))
        {   
            $start_slot = 0;
            $end_slot = 0;
            $start = explode(":", $row['start_time']);
            $end = explode(":", $row['end_time']);
            if($period == 1)
            {
                $start_slot = (intval($start[0]) * 4) + floor(intval($start[1]) / 15);
                $end_slot = (intval($end[0]) * 4) + floor(intval($end[1]) / 15);
            }
            else if($period == 2)
            {
                $start_slot = (intval($row['week_day']) * 12) + floor(intval($start[0]) / 2);
                $end_slot = (intval($row['week_day']) * 12) + floor(intval($end[0]) / 2);
            }
            else
            {
                $month_day = new DateTime($row['week']);
                $month_day->add(new DateInterval('P'.$row['week_day'].'D'));
                $day = intval($month_day->format('d'));
                $start_slot = (($day - 1) * 4) + floor(intval($start[0]) / 6);
                $end_slot = (($day - 1) * 4) + floor(intval($end[0]) / 6);
            }


            for($x = $start_slot; $x <= $end_slot; $x++)
            {
                $result_array[ $row['doc_id'] ][$x] = 1;
            }
        }
        else
        {
            $slots = array();
            for($i = 0; $i < $num_slots; $i++)
            {
                array_push($slots, 0);
            }
            $start_slot = 0;
            $end_slot = 0;
            $start = explode(":", $row['start_time']);
            $end = explode(":", $row['end_time']);
            if($period == 1)
            {
                $start_slot = (intval($start[0]) * 4) + floor(intval($start[1]) / 15);
                $end_slot = (intval($end[0]) * 4) + floor(intval($end[1]) / 15);
            }
            else if($period == 2)
            {
                $start_slot = (intval($row['week_day']) * 12) + floor(intval($start[0]) / 2);
                $end_slot = (intval($row['week_day']) * 12) + floor(intval($end[0]) / 2);
            }
            else
            {
                $month_day = new DateTime($row['week']);
                $month_day->add(new DateInterval('P'.$row['week_day'].'D'));
                $day = intval($month_day->format('d'));
                $start_slot = (($day - 1) * 4) + floor(intval($start[0]) / 6);
                $end_slot = (($day - 1) * 4) + floor(intval($end[0]) / 6);
            }
            for($x = $start_slot; $x <= $end_slot; $x++)
            {
                $slots[$x] = 1;
            }

            $result_array[ $row['doc_id'] ] = $slots;
        }
    }
}



$keys = array_keys($result_array);

if(count($keys) > 0)
{

    $query = $con->prepare("SELECT id,Name,Surname FROM doctors WHERE id IN (".implode(",", $keys).")");
    $query->execute();
    while($key = $query->fetch(PDO::FETCH_ASSOC))
    {
        if(array_key_exists($key['id'], $result_array))
        {
            $result_array[ $key['Name'].' '.$key['Surname'] ] = $result_array[ $key['id'] ];
            unset($result_array[ $key['id'] ]);
        }
    }
}

echo json_encode($result_array);

?>