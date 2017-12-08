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


$userid = $_SESSION['MEDID'];
//$userid=28;

//Step 0: Delete type configuration
$query = $con->prepare("delete from  user_event_config where userid=?");
$query->bindValue(1, $userid, PDO::PARAM_INT);
$query->execute();


//Step 1: Get parent ID's
$query = $con->prepare("select parent_id from parent_event where userid=?");
$query->bindValue(1, $userid, PDO::PARAM_INT);
$query->execute();

//Step 2: Delete all recurring events after today
while($row = $query->fetch(PDO::FETCH_ASSOC))
{
	
	$query2 = $con->prepare("delete from events where userid=? and parentid=? and start >= now()");
	$query2->bindValue(1, $userid, PDO::PARAM_INT);
	$query2->bindValue(2, $row['parent_id'], PDO::PARAM_INT);
	$query2->execute();

}

$query2 = $con->prepare("delete from user_time_config where userid=?");
$query2->bindValue(1, $userid, PDO::PARAM_INT);
$query2->execute();




/*



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
	echo "The event you want to schedule coincides with the following events : \n";
	while($row = mysql_fetch_array($result))
	{
		echo $i.'. '.$row['title'].' :  '.$row['start'].' to '.$row['end'].'\n';
		$i=$i+1;
	}
	
	return;
}


$query = "insert into events(title,start,end,userid,parentid) values('".$title."','".$start."','".$end."',".$userid.",0)";
//echo $query;
$result = mysql_query($query);
if($result==1)
{
	echo "success";
}
else
{
	echo "failure";
}


*/
?>