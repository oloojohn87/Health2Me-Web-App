<?php
 session_start(); 
 require("environment_detail.php");
 
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$docID = $_SESSION['MEDID'];
//$docID = 68;
$week = $_GET['week'];

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			

$query4 = $con->prepare("SELECT * FROM timeslots WHERE doc_id=? and week=?");
$query4->bindValue(1, $docID, PDO::PARAM_INT);
$query4->bindValue(2, $week, PDO::PARAM_STR);
$result4 = $query4->execute();

$timeslots = array();
while ($row4 = $query4->fetch(PDO::FETCH_ASSOC)) 
{

	$str = $row4["id"].'_';
    $str .= $row4["week_day"];
    $str .= str_replace(":", "", substr($row4["start_time"], 0, 5));
    $str .= str_replace(":", "", substr($row4["end_time"], 0, 5));
    $tz = str_replace(":", "", substr($row4["timezone"], 0, strlen($row4["timezone"]) - 3));
	if($tz[0] != '-')
    {
                $tz = "+".$tz;
    }
	$str .=$tz;
    $str .= str_replace("-", "", $row4["week"]);
    array_push($timeslots, $str);
}
echo json_encode($timeslots);


    
    

?>


