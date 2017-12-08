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

$type = $_POST['type'];
$user = $_POST['user'];
$is_receiver_doctor = 0;
if($type == 'DOC')
    $is_receiver_doctor = 1;

$upd = $con->prepare("UPDATE notifications SET viewed = NOW() WHERE receiver = ? AND is_receiver_doctor = ? AND dismissed = 0 AND viewed IS NULL");
$upd->bindValue(1, $user, PDO::PARAM_INT);
$upd->bindValue(2, $is_receiver_doctor, PDO::PARAM_INT);
$upd->execute();

if($type == 'DOC'){
	$query = $con->prepare( "SELECT * FROM doctors WHERE id = ?" );
}else{
	$query = $con->prepare( "SELECT * FROM usuarios WHERE Identif = ?" );
}
$query->bindValue(1, $user, PDO::PARAM_INT);
$query->execute();

$timezone_row = $query->fetch(PDO::FETCH_ASSOC);

$timezone = $timezone_row['timezone'];

$sel = $con->prepare("SELECT N.*,   
                            CONCAT(D1.name, ' ', D1.surname) AS doctor_receiver, 
                            CONCAT(U1.name, ' ', U1.surname) AS user_receiver,
                            CONCAT(D2.name, ' ', D2.surname) AS doctor_sender, 
                            CONCAT(U2.name, ' ', U2.surname) AS user_sender,
                            ADDTIME(`created`, '".$timezone."') AS updated_timezone 
                        FROM 
                            (
                                SELECT * FROM notifications WHERE receiver = ? AND is_receiver_doctor = ? AND dismissed = 0 ORDER BY created DESC
                            ) N
                        LEFT JOIN
                            doctors D1 ON D1.id = N.receiver
                        LEFT JOIN
                            usuarios U1 ON U1.identif = N.receiver
                        LEFT JOIN
                            doctors D2 ON D2.id = N.sender
                        LEFT JOIN
                            usuarios U2 ON U2.identif = N.sender");
$sel->bindValue(1, $user, PDO::PARAM_INT);
$sel->bindValue(2, $is_receiver_doctor, PDO::PARAM_INT);
$sel->execute();
$result = array();
while($sel_row = $sel->fetch(PDO::FETCH_ASSOC))
{
    array_push($result, $sel_row);
}

$group = 0;
if($is_receiver_doctor)
{
    $doc = $con->prepare("SELECT notification_group FROM doctors WHERE id = ?");
    $doc->bindValue(1, $user, PDO::PARAM_INT);
    $doc->execute();
    $doc_row = $doc->fetch(PDO::FETCH_ASSOC);
    $group = $doc_row['notification_group'];
}
else
{
    $pat = $con->prepare("SELECT notification_group FROM usuarios WHERE identif = ?");
    $pat->bindValue(1, $user, PDO::PARAM_INT);
    $pat->execute();
    $pat_row = $pat->fetch(PDO::FETCH_ASSOC);
    $group = $pat_row['notification_group'];
}

echo json_encode(array("notifications" => $result, "group" => $group));

?>
