<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbname = 'task_tracking';
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

$resource = $_GET['resource_name'];
$type = $_GET['type'];

$cadena = '';

$notifications = array();
if ($type != 'ALL') $notif_ = $con->prepare("SELECT * FROM buglist_dec23_2014 WHERE assigned_to = ? AND  (status = 'Open' OR status = 'In Progress')");
else  $notif_ = $con->prepare("SELECT * FROM buglist_dec23_2014 WHERE (status = 'Open' OR status = 'In Progress')");
$notif_->bindValue(1, $resource, PDO::PARAM_INT);
$notif_->execute();
$n = 0;
$dependencies = array();
 
while($row = $notif_->fetch(PDO::FETCH_ASSOC))
{
    
    $overcost = 0;
    if ($row['original_estimate_minutes'] > 0) $overcost = (($row['actual_duration_minutes'] + $row['remaining_estimate_minutes'] ) -  $row['original_estimate_minutes'])*100/$row['original_estimate_minutes'];
    $overcost = round($overcost,2);
    $excess = round((($row['actual_duration_minutes'] + $row['remaining_estimate_minutes'] ) - $row['original_estimate_minutes']) / 60, 1);

    $overcost_text = $overcost.' %'.' ('.$excess.' h.)';
    if ($overcost < 20) $overcost_text = '';
    
    //$dependencies = Check_dependencies ($row['id']);
    
    
    if ($n > 0) $cadena .=  ',';
    $cadena .=  '{
                        "id": "'.$row['id'].'",
                        "resource": "'.$row['assigned_to'].'",
                        "task_name": "'.substr(str_replace('"', '\"', $row['title']),0,50).'",
                        "original_estimate": "'.round(($row['original_estimate_minutes']/60),1).'",
                        "actual_duration": "'.round(($row['actual_duration_minutes']/60),1).'",
                        "remaining_estimate": "'.round(($row['remaining_estimate_minutes']/60),1).'",
                        "overcost": "'.$overcost_text .'",
                        "status": "'.$row['status'].'",
                        "log_array":"'.Work_past_month($row['id'],$dbhost,$dbname,$dbuser,$dbpass).'"
                    }';  
    $n++;
}

$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 


function Check_dependencies($task_id)
{
    $totals = array();
 
    $con2 = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    if (!$con2)
    {
        die('Could not connect: ' . mysql_error());
    }

    $dependecy = $con2->prepare("SELECT * FROM buglist_dec23_2014 WHERE dependency = ?");
    $dependecy ->bindValue(1, $task_id, PDO::PARAM_INT);
    $dependecy ->execute();
    while($row = $dependecy->fetch(PDO::FETCH_ASSOC))
    {
        $totals[0] = $totals[0] + $row['original_estimate_minutes'];
        $totals[1] = $totals[1] + $row['actual_duration_minutes'];
        $totals[2] = $totals[2] + $row['remaining_estimate_minutes'];
    }

    return $totals;
}

function Work_past_month($project_id,$dbhost,$dbname,$dbuser,$dbpass)
{

    $con3 = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)); 
    if (!$con3)
    {
        die('Could not connect: ' . mysql_error());
    }                                                                                             
    $tasks = $con3->prepare("SELECT * FROM worklogs_dec23_2014 WHERE ID_Number = ?");
    $tasks ->bindValue(1, $project_id, PDO::PARAM_INT);
    $tasks ->execute();
    $log_array = '';
    while($row = $tasks->fetch(PDO::FETCH_ASSOC))
    {   
        $log_array = $log_array.$row['Work_Done_Minutes'].',';
    }
    //$log_array = '   llllllll    '. $project_id . '     llllllllllll ';
    return $log_array;

}




?>
