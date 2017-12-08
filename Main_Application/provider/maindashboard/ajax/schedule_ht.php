<?php

require("../../../environment_detail.php");
//require("NotificationClass.php");
//require_once('realtime-notifications/pusherlib/lib/Pusher.php');
//require_once('push/push.php');
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

$pat = $_POST['pat'];
$date = $_POST['date'];
$time = $_POST['time'];
$language = $_POST['language'];

$date_begin = $date.' 00:00:00';
$date_end = $date.' 23:59:59';
$reservation_date = $date.' '.$time;


$get_np = $con->prepare("SELECT D.id, IFNULL(R1.day_load, 0) AS day_load FROM doctors D 
                            LEFT JOIN 
                        (SELECT provider_id, COUNT(*) AS day_load FROM reservation WHERE date BETWEEN ? AND ? AND status = 'ACTIVE' GROUP BY provider_id) R1 
                                ON R1.provider_id = D.id 
                            LEFT JOIN (SELECT provider_id, COUNT(*) AS count FROM reservation WHERE date = ? AND status = 'ACTIVE' GROUP BY provider_id) R2 
                                ON R2.provider_id = D.id 
                        WHERE D.type = 'CATA-NP' AND (R2.count = 0 OR R2.count IS NULL) 
                            ORDER BY R1.day_load ASC LIMIT 1");

$get_np->bindValue(1, $date_begin, PDO::PARAM_STR);
$get_np->bindValue(2, $date_end, PDO::PARAM_STR);
$get_np->bindValue(3, $reservation_date, PDO::PARAM_STR);
$get_np->execute();

if($get_np->rowCount() >= 1)
{
    $np = $get_np->fetch(PDO::FETCH_ASSOC);
    
    $insert = $con->prepare('INSERT INTO reservation SET provider_id = ?, patient_id = ?, date = ?, language = ?');
    $insert->bindValue(1, $np['id'], PDO::PARAM_INT);
    $insert->bindValue(2, $pat, PDO::PARAM_INT);
    $insert->bindValue(3, $reservation_date, PDO::PARAM_STR);
    $insert->bindValue(4, $language, PDO::PARAM_STR);
    $insert->execute();
    
    echo $np['id'];
}
else
{
    echo '0';
}


?>