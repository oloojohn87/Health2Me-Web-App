<?php
session_start();
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


$notification_id = $_POST['notification_id'];
$user_id = $_SESSION['BOTHID'];

$result = $con->prepare("SELECT pass FROM encryption_pass WHERE id = (SELECT MAX(id) FROM encryption_pass)");
$result->execute();
$row = $result->fetch(PDO::FETCH_ASSOC);
$pass = $row['pass'];

$items = array();

// add entries to the $items array
dismissNotification($items, $notification_id, $con);

echo ($items);



/*
 *
 *  FUNCTIONS
 *
 *  Example:
 *  
 *      $items['38'] = array("Type" => "3", ...);
 *
 */

function dismissNotification(&$items, $notification_id, &$con)
{
    // update dismission fields for this probe id
    $notifications = array();
    $notif_ = $con->prepare("UPDATE notification_phs SET dismissed_timestamp = NOW(), dismissed_via = 'web'  WHERE id = ? ");
    //$notif_ = $con->prepare("UPDATE notification_phs SET dismissed_via = 'web'  WHERE id = ? ");
    $notif_->bindValue(1, $notification_id, PDO::PARAM_INT);
    $count = $notif_->execute();
    
    // echo a message to say the UPDATE succeeded
    $items =  $notif_->rowCount() . " records UPDATED successfully.  Rows affected:  ".$count;
  
}



?>
