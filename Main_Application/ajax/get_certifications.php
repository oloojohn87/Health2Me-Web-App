<?php

require("environment_detail.php");
require_once('realtime-notifications/pusherlib/lib/Pusher.php');
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

if(isset($_POST['id']))
{
    $id = $_POST['id'];
    $ret_array = array();
	
    $res = $con->prepare("SELECT * FROM certifications WHERE doc_id=?");
	$res->bindValue(1, $id, PDO::PARAM_INT);
	$res->execute();
	
    while($row = $res->fetch(PDO::FETCH_ASSOC))
    {
        array_push($ret_array, $row);
    }
    echo json_encode($ret_array);
}
else
{
    $res = $con->prepare("SELECT * FROM certifications");
	$res->execute();
    $check_array = array();
    $count_array = array();
    $result = array();
    while($row = $res->fetch(PDO::FETCH_ASSOC))
    {
        if(!isset($check_array[$row['name'].'SEPARATOR'.$row['image']]))
        {
            $check_array[$row['name'].'SEPARATOR'.$row['image']] = 1;
        }
        else
        {
            $check_array[$row['name'].'SEPARATOR'.$row['image']] += 1;
        }
        if(!isset($result[($row['name'])]))
        {
            $result[($row['name'])] = $row['image'];
            $count_array[($row['name'])] = 0;
        }
    }
    foreach($check_array as $key => $value)
    {
        $info = explode("SEPARATOR", $key);
        $name = $info[0];
        $image = $info[1];
        if($count_array[$name] < $value)
        {
            $result[$name] = $image;
            $count_array[$name] = $value;
        }
    }
    
    echo json_encode($result);
}

?>