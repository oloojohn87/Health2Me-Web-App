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



function ConvertLocalTimezoneToGMT($gmttime,$timezoneRequired)
{
    $system_timezone = date_default_timezone_get();
 
    $local_timezone = $timezoneRequired;
    date_default_timezone_set($local_timezone);
    $local = date("Y-m-d h:i:s A");
 
    date_default_timezone_set("GMT");
    $gmt = date("Y-m-d h:i:s A");
 
    date_default_timezone_set($system_timezone);
    $diff = (strtotime($gmt) - strtotime($local));
 
    $date = new DateTime($gmttime);
    $date->modify("+$diff seconds");
    $timestamp = $date->format("m-d-Y H:i:s");
    return $timestamp;
}


$update_flag = $_GET['update_flag'];
$title = $_GET['title'];
$start = $_GET['start'];
$event_id = $_GET['event_id'];
//echo 'start: '.$start;


/*$start = $_GET['start'];
$date =date_create(substr($start,0,10)." 00:00:00");
$start_in_24_hour_format  = date_format($date,"Y-m-d")." ".date("H:i:s", strtotime($start));
*/


/*$tok = strtok($start, " ");
$sdate =  $tok;
$tok = strtok(" ");
$stime = $tok;

//echo 'date : '.$sdate;
//echo 'time : '.$stime;




echo 'converted '.$start_in_24_hour_format;



$tok = strtok($end, " ");
$edate =  $tok;
$tok = strtok(" ");
$etime = $tok;

*/
$start = $_GET['start'];
$date1 =date_create(substr($start,0,10)." 00:00:00");
$start_in_24_hour_format  = date_format($date1,"Y-m-d")." ".date("H:i:s", strtotime($start));

$end = $_GET['end'];
$date =date_create(substr($end,0,10)." 00:00:00");
$end_in_24_hour_format  = date_format($date,"Y-m-d")." ".date("H:i:s", strtotime($end));

//$userid=28;
$userid = $_GET['userid'];
$patient=$_GET['patient'];
$added_by= $_SESSION['MEDID'];


//$added_by= 28;


/*$start_in_24_hour_format= $sdate.' '.$start_in_24_hour_format;
echo 'start 24hr format '.$start_in_24_hour_format;

$end_in_24_hour_format=$edate.' '.$end_in_24_hour_format; */
//echo 'end 24hr format '.$end_in_24_hour_format;

//echo 'Flag = '. $update_flag;

if($update_flag=='false')
{

/*$query = "select * from events where userid=".$userid;
$cond1 =  "((timestampdiff(MINUTE,end,'".$start_in_24_hour_format."')<0 and timestampdiff(MINUTE,'".$start_in_24_hour_format."',start)<0) ";
$cond2 = "(timestampdiff(MINUTE,'".$end_in_24_hour_format."',start)<0 and timestampdiff(MINUTE,end,'".$end_in_24_hour_format."')<0)";
$cond3 = "(timestampdiff(MINUTE,end,'".$end_in_24_hour_format."')<0 and timestampdiff(MINUTE,'".$start_in_24_hour_format."',start)<0)";
$cond4 = "(timestampdiff(MINUTE,end,'".$start_in_24_hour_format."')<0 and timestampdiff(MINUTE,'".$end_in_24_hour_format."',start)<0)) ";

$query = $query.' and '.$cond1.' OR '.$cond2.' OR '.$cond3.' OR '.$cond4;
*/
$query = $con->prepare("select * from events where userid=? 
AND ((timestampdiff(MINUTE,end,'".$start_in_24_hour_format."')<0 and timestampdiff(MINUTE,'".$start_in_24_hour_format."',start)<0) 
OR (timestampdiff(MINUTE,'".$end_in_24_hour_format."',start)<0 and timestampdiff(MINUTE,end,'".$end_in_24_hour_format."')<0) 
OR (timestampdiff(MINUTE,end,'".$end_in_24_hour_format."')<0 and timestampdiff(MINUTE,'".$start_in_24_hour_format."',start)<0)
OR (timestampdiff(MINUTE,end,'".$start_in_24_hour_format."')<0 and timestampdiff(MINUTE,'".$end_in_24_hour_format."',start)<0))
");
$query->bindValue(1, $userid, PDO::PARAM_INT);

}
else
{
/*$query = "select * from events where userid=".$userid;
$cond1 =  "((timestampdiff(MINUTE,end,'".$start_in_24_hour_format."')<0 and timestampdiff(MINUTE,'".$start_in_24_hour_format."',start)<0) ";
$cond2 = "(timestampdiff(MINUTE,'".$end_in_24_hour_format."',start)<0 and timestampdiff(MINUTE,end,'".$end_in_24_hour_format."')<0)";
$cond3 = "(timestampdiff(MINUTE,end,'".$end_in_24_hour_format."')<0 and timestampdiff(MINUTE,'".$start_in_24_hour_format."',start)<0)";
$cond4 = "(timestampdiff(MINUTE,end,'".$start_in_24_hour_format."')<0 and timestampdiff(MINUTE,'".$end_in_24_hour_format."',start)<0)) ";

$query = $query.' and '.$cond1.' OR '.$cond2.' OR '.$cond3.' OR '.$cond4 . 'and id!='.$event_id;
*/
$query = $con->prepare("select * from events where userid=?
AND ((timestampdiff(MINUTE,end,'".$start_in_24_hour_format."')<0 and timestampdiff(MINUTE,'".$start_in_24_hour_format."',start)<0)
OR (timestampdiff(MINUTE,'".$end_in_24_hour_format."',start)<0 and timestampdiff(MINUTE,end,'".$end_in_24_hour_format."')<0)
OR (timestampdiff(MINUTE,end,'".$end_in_24_hour_format."')<0 and timestampdiff(MINUTE,'".$start_in_24_hour_format."',start)<0)
OR (timestampdiff(MINUTE,end,'".$start_in_24_hour_format."')<0 and timestampdiff(MINUTE,'".$end_in_24_hour_format."',start)<0))
");
$query->bindValue(1, $userid, PDO::PARAM_INT);

}
//echo $query;


$result = $query->execute();

$num_rows = $query->rowCount();
//echo $num_rows;

$i=1;
if($num_rows > 0)
{	
	//echo "The event you want to schedule coincides with the following events : \n";
	while($row = $query->fetch(PDO::FETCH_ASSOC))
	{
		$q = $con->prepare("select idusfixedname from usuarios where identif=?");
		$q->bindValue(1, $row['patient'], PDO::PARAM_INT);
		$res = $q->execute();
		
		$r = $q->fetch(PDO::FETCH_ASSOC);
		
		echo $i.'. '.$row['title']. ' with  ' .$r['idusfixedname'] .'$';  
		$i=$i+1;
	}
	
	return;
}


//}

$timezone_query = $con->prepare("select name from timezones,user_timezone_config where id=timez and userid=?");
$timezone_query->bindValue(1, $userid, PDO::PARAM_INT);
$timezone_result = $timezone_query->execute();

$timezone = $timezone_query->fetch(PDO::FETCH_ASSOC);

//echo '<br>'.$timezone['name'];

$gmt_time = ConvertLocalTimezoneToGMT($start_in_24_hour_format,$timezone['name']);

//echo $gmt_time;
$dt = substr($gmt_time,0,10);
//echo 'substr : '.$dt;
$mon = strtok($dt, "-");
$day = strtok("-");
$year = strtok("-");

$gmt_time = $year.'-'.$mon.'-'.$day . ' ' . substr($gmt_time,10);


$query = $con->prepare("insert into events(title,start,end,userid,parentid,patient,added_by,gmt_start) values(?,?,?,?,0,?,?,?)");
$query->bindValue(1, $title, PDO::PARAM_STR);
$query->bindValue(2, $start_in_24_hour_format, PDO::PARAM_STR);
$query->bindValue(3, $end_in_24_hour_format, PDO::PARAM_STR);
$query->bindValue(4, $userid, PDO::PARAM_INT);
$query->bindValue(5, $patient, PDO::PARAM_INT);
$query->bindValue(6, $added_by, PDO::PARAM_INT);
$query->bindValue(7, $gmt_time, PDO::PARAM_STR);
//echo '<br>'.$query;

$result = $query->execute();
//echo $result;
if($result==1)
{
	echo "success";
}
else
{
	echo "failure";
}



?>