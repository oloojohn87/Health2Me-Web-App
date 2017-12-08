<?php
//session_start();
require("environment_detail.php");
//require("h2pdf.php");

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 
$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$doc_id = $_POST['doc_id'];

if(isset($_POST['status']))
{
    $status = $_POST['status'];

    $query = "UPDATE doctors SET in_consultation=".$status;
    if($status == '0' || $status == 0)
    {
        $query .= ",telemed_type=0";
    }
    $query .= " WHERE id=".$doc_id;
    mysql_query($query);
}
else if(isset($_POST['update_lastseen']))
{
    $result = $con->prepare("SELECT consultationId FROM consults WHERE Doctor = ? ORDER BY DateTime DESC");
	$result->bindValue(1, $doc_id, PDO::PARAM_INT);
	$result->execute();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $con_id = $row['consultationId'];
    $result = $con->prepare("UPDATE consults SET lastActive=NOW() WHERE consultationId = ?");
    $result->bindValue(1, $con_id, PDO::PARAM_INT);
	$result->execute();
}
else if(isset($_POST['add_to_recent_doctors']) && $_POST['add_to_recent_doctors'] == 1)
{
    $pat_id = $_POST['pat_id'];
    //$result = mysql_query("UPDATE doctors SET in_consultation=3 WHERE id=".$doc_id);
    $result = $con->prepare("SELECT most_recent_doc FROM usuarios WHERE Identif=?");
	$result->bindValue(1, $pat_id, PDO::PARAM_INT);
	$result->execute();
	
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $str = $row['most_recent_doc'];
    $res = "";
    if(strlen($str) > 0)
    {
        $str = str_replace(array("[", "]"), "", $str);
        $ids = explode(",", $str);
        $found = false;
        for($i = 0; $i < count($ids); $i++)
        {
            if($ids[$i] == $doc_id)
            {
                $found = true;
            }
        }
        if(!$found)
        {
            array_unshift($ids , $doc_id);
            while(count($ids) > 5)
            {
                $doc = array_pop($ids);
            }
        }
        $res = implode(",", $ids);
    }
    else
    {
        $res = $doc_id;
    }
    $new_ids = "[".$res."]";
    $result = $con->prepare("UPDATE usuarios SET most_recent_doc=? WHERE Identif=?");
	$result->bindValue(1, $new_ids, PDO::PARAM_INT);
	$result->bindValue(2, $pat_id, PDO::PARAM_INT);
	$result->execute();
}

?>