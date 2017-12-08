<?php
session_start();
require("environment_detail.php");

$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];
$hardcode = $env_var_db['hardcode'];
$local = $env_var_db['local'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

$user = $_POST['user'];

if(isset($_POST['id']))
{
    $id = $_POST['id'];
    $get = $con->prepare("SELECT * FROM medication_reminders WHERE user = ? AND id = ?");
    $get->bindValue(1, $user, PDO::PARAM_INT);
    $get->bindValue(2, $id, PDO::PARAM_INT);
    $get->execute();
    $result = $get->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode($result);
}
else
{
    $get = $con->prepare("SELECT * FROM medication_reminders WHERE user = ?");
    $get->bindValue(1, $user, PDO::PARAM_INT);
    $get->execute();
    $result = array();
    while($row = $get->fetch(PDO::FETCH_ASSOC))
    {
        if($row['start'] != NULL && $row['timezone'] != NULL)
        {
            $date = DateTime::createFromFormat("Y-m-d H:i:s", $row['start'], new DateTimezone($row['timezone']));
            $date->setTimezone(new DateTimezone('UTC'));
            $row['start_utc'] = $date->format("Y-m-d H:i:s");
        }
        $get_res = $con->prepare("SELECT date FROM medication_reminders_response WHERE reminder_id = ? ORDER BY date DESC LIMIT 30");
        $get_res->bindValue(1, $row['id'], PDO::PARAM_INT);
        $get_res->execute();
        $responses = array();
        while($response_row = $get_res->fetch(PDO::FETCH_ASSOC))
        {
            array_push($responses, $response_row['date']);
        }
        $row['responses'] = $responses;
        array_push($result, $row);
    }

    echo json_encode($result);
}

?>