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

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

$resource = $_GET['resource_name'];
$type = $_GET['type'];
$id_project = $_GET['id_project'];

$cadena = '';

$notifications = array();

$notif_ = $con->prepare("SELECT * FROM worklogs_dec23_2014 WHERE ID_Number = ?");
$notif_->bindValue(1, $id_project, PDO::PARAM_INT);
$notif_->execute();
$n = 0;
$dependencies = array();
 
while($row = $notif_->fetch(PDO::FETCH_ASSOC))
{
    
    if ($n > 0) $cadena .=  ',';
    $cadena .=  '{
                        "user_id": "'.$row['User_ID'].'",
                        "date": "'.$row['Date'].'",
                        "logged":"'.$row['Work_Done_Minutes'].'"
                    }';  
    $n++;
}

$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 

/*
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
*/



?>
