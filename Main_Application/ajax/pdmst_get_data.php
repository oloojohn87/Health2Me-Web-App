<?php
require("environment_detail.php");

$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];
//KYLE$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

$period = $_POST['period'];

$result = array();

if($period == 1)
{
    // today
    $res = mysql_query("SELECT * FROM appointments WHERE date = CURDATE() AND time_called is not null");
    $row_arr = null;
    while($row = mysql_fetch_assoc($res))
    {
        $res2 = mysql_query("SELECT Name,Surname FROM usuarios WHERE Identif=".$row['pat_id']);
        $row2 = mysql_fetch_assoc($res2);
        $name = $row2['Name'].' '.$row2['Surname'];
        $row_arr = array("date" => $row['date'], "time" => $row['time_called'], "pat_id" => $row['pat_id'], "type" => $row['video'], "name" => $name);
        array_push($result, $row_arr);
    }
    
    echo json_encode($result);
}
else if($period == 2)
{
    // week
    $res = mysql_query("SELECT * FROM appointments WHERE date <= CURDATE() AND date >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY AND time_called is not null");
    $row_arr = null;
    while($row = mysql_fetch_assoc($res))
    {
        $res2 = mysql_query("SELECT Name,Surname FROM usuarios WHERE Identif=".$row['pat_id']);
        $row2 = mysql_fetch_assoc($res2);
        $name = $row2['Name'].' '.$row2['Surname'];
        $date = new DateTime($row['date']);
        $row_arr = array("date" => $row['date'], 'DOW' => $date->format('w'), "pat_id" => $row['pat_id'], "type" => $row['video'], "name" => $name);
        array_push($result, $row_arr);
    }
    
    echo json_encode($result);
}
else if($period == 3)
{
    // month
    $res = mysql_query("SELECT * FROM appointments WHERE date <= CURDATE() AND date > DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND time_called is not null");
    $row_arr = null;
    while($row = mysql_fetch_assoc($res))
    {
        $res2 = mysql_query("SELECT Name,Surname FROM usuarios WHERE Identif=".$row['pat_id']);
        $row2 = mysql_fetch_assoc($res2);
        $name = $row2['Name'].' '.$row2['Surname'];
        $date = new DateTime($row_arr['date']);
        $info = explode("-", $row['date']);
        $row_arr = array("date" => $row['date'], 'day' => $info[2], "pat_id" => $row['pat_id'], "type" => $row['video'], "name" => $name);
        array_push($result, $row_arr);
    }
    
    echo json_encode($result);
}
else if($period == 4)
{
    // year
    $res = mysql_query("SELECT * FROM appointments WHERE date <= CURDATE() AND date > DATE_SUB(CURDATE(), INTERVAL 1 YEAR) AND time_called is not null");
    $row_arr = null;
    while($row = mysql_fetch_assoc($res))
    {
        $res2 = mysql_query("SELECT Name,Surname FROM usuarios WHERE Identif=".$row['pat_id']);
        $row2 = mysql_fetch_assoc($res2);
        $name = $row2['Name'].' '.$row2['Surname'];
        $date = new DateTime($row_arr['date']);
        $info = explode("-", $row['date']);
        $row_arr = array("date" => $row['date'], 'month' => $info[1], "pat_id" => $row['pat_id'], "type" => $row['video'], "name" => $name);
        array_push($result, $row_arr);
    }
    
    echo json_encode($result);
}
?>