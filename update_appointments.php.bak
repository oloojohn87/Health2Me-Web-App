<?php

require "environment_detail.php";
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$appointment_id = $_POST['appointment_id'];
$type = $_POST['type'];
$obj = $_POST['obj'];
$reset = $_POST['reset'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

if($reset == 1)
{
    // reset descriptions
    $query = $con->prepare("UPDATE appointments SET med_desc='0',pat_desc='0',med_candidates='0',pat_candidates='0' WHERE id=?");
	$query->bindValue(1, $appointment_id, PDO::PARAM_INT);
	$res = $query->execute();
	
    if($res)
    {
        echo 'reset successful';
    }
    else
    {
        echo 'error reseting';
    }
}
else
{
    if($type == 1)
    {
        // set doctor's webrtc description object
        $query = $con->prepare("UPDATE appointments SET med_desc=? WHERE id=?");
		$query->bindValue(1, $obj, PDO::PARAM_INT);
		$query->bindValue(2, $appointment_id, PDO::PARAM_INT);
		$res = $query->execute();
		
        if($res)
        {
            echo 'update successful';
        }
        else
        {
            echo 'error updating';
        }
    }
    else if($type == 2)
    {
        // set patients's webrtc description object
        $query = $con->prepare("UPDATE appointments SET pat_desc=? WHERE id=?");
		$query->bindValue(1, $obj, PDO::PARAM_INT);
		$query->bindValue(2, $appointment_id, PDO::PARAM_INT);
		$res = $query->execute();
		
        if($res)
        {
            echo 'update successful';
        }
        else
        {
            echo 'error updating';
        }
    }
    else if($type == 3)
    {
        // set patient's webrtc candidates
        $query = $con->prepare("UPDATE appointments SET pat_candidates=? WHERE id=?");
		$query->bindValue(1, $obj, PDO::PARAM_INT);
		$query->bindValue(2, $appointment_id, PDO::PARAM_INT);
		$res = $query->execute();
		
        if($res)
        {
            echo 'update successful';
        }
        else
        {
            echo 'error updating';
        }
    }
    else if($type == 4)
    {
        // set doctor's webrtc candidates
        $query = $con->prepare("UPDATE appointments SET med_candidates=? WHERE id=?");
		$query->bindValue(1, $obj, PDO::PARAM_INT);
		$query->bindValue(2, $appointment_id, PDO::PARAM_INT);
		$res = $query->execute();
		
        if($res)
        {
            echo 'update successful';
        }
        else
        {
            echo 'error updating';
        }
    }
}

?>