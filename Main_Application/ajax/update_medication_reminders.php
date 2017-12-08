<?php
session_start();
require("environment_detail.php");

$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];
$hardcode = $env_var_db['hardcode'];
$local = $env_var_db['local'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

if(isset($_POST['edit']) && $_POST['edit'] == 1)
{
    $start = $_POST['start'];
    $frequency = $_POST['frequency'];
    $unit = $_POST['unit'];
    $timezone = $_POST['timezone'];
    if($unit == 'days')
        $frequency *= 24;
    $alert = $_POST['alert'];
    $on = $_POST['on'];
    $id = $_POST['id'];
    $date = DateTime::createFromFormat("Y-m-d H:i:s", $start, new DateTimezone($timezone));
    $timestamp = intval($date->format("U"));
    
    $update = $con->prepare("UPDATE medication_reminders SET start = ?, frequency = ?, alert = ?, active = ?, timezone = ? WHERE id = ?");
    $update->bindValue(1, $start, PDO::PARAM_STR);
    $update->bindValue(2, $frequency, PDO::PARAM_INT);
    $update->bindValue(3, $alert, PDO::PARAM_INT);
    $update->bindValue(4, $on, PDO::PARAM_INT);
    $update->bindValue(5, $timezone, PDO::PARAM_STR);
    $update->bindValue(6, $id, PDO::PARAM_INT);
    $update->execute();
    
    $get = $con->prepare("SELECT last_sent FROM medication_reminders WHERE id = ?");
    $get->bindValue(1, $id, PDO::PARAM_INT);
    $get->execute();
    $reminder = $get->fetch(PDO::FETCH_ASSOC);
    if($reminder['last_sent'] == NULL || $reminder['last_send'] < ($timestamp - (3600 * $frequency)))
    {
        $update = $con->prepare("UPDATE medication_reminders SET last_sent = ? WHERE id = ?");
        $update->bindValue(1, $timestamp, PDO::PARAM_INT);
        $update->bindValue(2, $id, PDO::PARAM_INT);
        $update->execute();
    }
    echo 1;
}
else if(isset($_POST['delete']) && $_POST['delete'] == 1)
{
    $id = $_POST['id'];
    $del = $con->prepare("DELETE FROM medication_reminders WHERE id = ?");
    $del->bindValue(1, $id, PDO::PARAM_INT);
    $del->execute();
    echo 1;
}
else
{
    $user = $_POST['user_id'];
    $name = $_POST['name'];
    $image = $_POST['image'];
    
    $ins = $con->prepare("INSERT INTO medication_reminders SET name = ?, image = ?, user = ?");
    $ins->bindValue(1, $name, PDO::PARAM_STR);
    $ins->bindValue(2, $image, PDO::PARAM_STR);
    $ins->bindValue(3, $user, PDO::PARAM_INT);
    $ins->execute();
    
    echo 1;
    
}

?>