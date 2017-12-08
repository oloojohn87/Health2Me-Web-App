<?php

require("environment_detail.php");

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 
/* Values received via ajax */
$id = $_GET['id'];
$title = $_GET['title'];
$start = $_GET['start'];
$end = $_GET['end'];
$userid = $_GET['userid'];

//echo $id.'   '.$start.'   '.$end;


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

$timezone_query = $con->prepare("select name from timezones,user_timezone_config where id=timez and userid=?");
$timezone_query->bindValue(1, $userid, PDO::PARAM_INT);
$timezone_result = $timezone_query->execute();
$timezone = $timezone_query->fetch(PDO::FETCH_ASSOC);

//echo '<br>'.$timezone['name'];

$gmt_time = ConvertLocalTimezoneToGMT($start,$timezone['name']);

//echo $gmt_time;
$dt = substr($gmt_time,0,10);
//echo 'substr : '.$dt;
$mon = strtok($dt, "-");
$day = strtok("-");
$year = strtok("-");

$gmt_time = $year.'-'.$mon.'-'.$day . ' ' . substr($gmt_time,10);




$query = $con->prepare("update events set start=?,end=?,gmt_start=?,doctor_notify=null  where id=?");
$query->bindValue(1, $start, PDO::PARAM_STR);
$query->bindValue(2, $end, PDO::PARAM_STR);
$query->bindValue(3, $gmt_time, PDO::PARAM_STR);
$query->bindValue(4, $id, PDO::PARAM_INT);
$query->execute();


/*$userid=$id;

$query = "select * from events where userid=".$userid;
$cond1 =  "(timestampdiff(MINUTE,end,'".$start."')<0 and timestampdiff(MINUTE,'".$start."',start)<0) ";
$cond2 = "(timestampdiff(MINUTE,'".$end."',start)<0 and timestampdiff(MINUTE,end,'".$end."')<0)";
$cond3 = "(timestampdiff(MINUTE,end,'".$end."')<0 and timestampdiff(MINUTE,'".$start."',start)<0)";
$cond4 = "(timestampdiff(MINUTE,end,'".$start."')<0 and timestampdiff(MINUTE,'".$end."',start)<0) ";

$query = $query.' and '.$cond1.' OR '.$cond2.' OR '.$cond3.' OR '.$cond4;

$result = mysql_query($query);

$num_rows = mysql_num_rows($result);
//echo $num_rows;

$i=1;
if($num_rows > 0)
{	
	//echo "The event you want to schedule coincides with the following events : \n";
	while($row = mysql_fetch_array($result))
	{
		$q = "select idusfixedname from usuarios where identif=".$row['patient'];
		$res = mysql_query($q);
		$r = mysql_fetch_array($res);
		
		echo $i.'. '.$row['title']. ' with  ' .$r['idusfixedname'] .'$';  
		$i=$i+1;
	}
	
	return;
}
*/

// connection to the database
/*
try {
 $bdd = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
 } catch(Exception $e) {
exit('Unable to connect to database.');
}
 // update the records
$sql = "UPDATE events set start=?, end=? WHERE id=?";
$q = $bdd->prepare($sql);
$q->execute(array($start,$end,$id));

//echo 'success';*/
?>