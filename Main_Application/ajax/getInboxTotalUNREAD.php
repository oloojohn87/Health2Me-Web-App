<?php
 /*KYLE
 require("environment_detail.php");
 
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

$IdMED= $_GET['IdMED'];
$patientid=$_GET['patient'];
$sendingdoc=empty($_GET['sendingdoc']) ? 0: $_GET['sendingdoc'];
$unread=empty($_GET['unread']) ? 0: $_GET['unread'];
$scenario=empty($_GET['scenario']) ? 0: $_GET['scenario'];

$cutByDays = '';
if ($unread == 0) $cutByDays = 'AND DATEDIFF(NOW(), fecha) <=  60';
if ($scenario == 'patient') 
{ 
	$WhatDB = 'message_infrastructureuser'; 
	$SwitchDr = 0;
} else 
{ 
	$WhatDB = 'message_infrasture'; 
	$SwitchDr = 1;
}

//$setQuery = 'SELECT * FROM message_infrastructure WHERE receiver_id=' . $IdMED;

if($sendingdoc==0)
{
	if($patientid==-1)
    {
		$setQuery = 'SELECT * FROM '.$WhatDB.' WHERE receiver_id='.$IdMED.' '.$cutByDays;
	}
    else
	{
		$setQuery = 'SELECT * FROM '.$WhatDB.'  WHERE receiver_id='.$IdMED.' and patient_id='.$patientid.'  '.$cutByDays;
	}
}
else{
	if($patientid==-1)
    {
		$setQuery = 'SELECT * FROM '.$WhatDB.'  WHERE receiver_id='.$IdMED.'  and sender_id='.$sendingdoc.'  '.$cutByDays;
	}
    else
	{
		$setQuery = 'SELECT * FROM '.$WhatDB.'  WHERE receiver_id='.$IdMED.'  and patient_id='.$patientid.' and sender_id='.$sendingdoc.' '.$cutByDays;
	}
}

$count = mysql_num_rows(mysql_query($setQuery));

echo $count;
?>