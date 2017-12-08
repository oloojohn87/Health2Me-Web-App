<?php

require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$tbl_name="usuarios"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

if(isset($_POST['id']))
{
    $id = $_POST['id'];

    $dismiss = $con->prepare("UPDATE notifications SET dismissed = 1 WHERE id = ?");
    $dismiss->bindValue(1, $id, PDO::PARAM_INT);
    $dismiss->execute();
}
else if(isset($_POST['sender']) && isset($_POST['receiver']) && isset($_POST['is_sender_doctor']) && isset($_POST['is_receiver_doctor']))
{
    $type = '';
    if(isset($_POST['type']))
        $type = " AND type = ?";
    
    $dismiss = $con->prepare("UPDATE notifications SET dismissed = 1 WHERE sender = ? AND receiver = ? AND is_sender_doctor = ? AND is_receiver_doctor = ?".$type);
    $dismiss->bindValue(1, $_POST['sender'], PDO::PARAM_INT);
    $dismiss->bindValue(2, $_POST['receiver'], PDO::PARAM_INT);
    $dismiss->bindValue(3, $_POST['is_sender_doctor'], PDO::PARAM_INT);
    $dismiss->bindValue(4, $_POST['is_receiver_doctor'], PDO::PARAM_INT);
    if(isset($_POST['type']))
        $dismiss->bindValue(5, $_POST['type'], PDO::PARAM_STR);
    $dismiss->execute();
}
echo 1;

?>