<?php
 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$groupid=$_GET['groupid'];


$cadena = '';
// First create an array with all Patient Ids that have activity (report created, messages, stages)
// This is "MyPatients" array: A) Patients created by me, plus B) Patients referred to me, plus C) Patients created by other members of my group
$result = $con->prepare("select * from groups where id = ?");
$result->bindValue(1, $groupid, PDO::PARAM_INT);
$result->execute();

//$row2 = mysql_fetch_array($result);
$counter1=0;
while ($row2 = $result->fetch(PDO::FETCH_ASSOC)) {
	$grpIds[$counter1] = $row2['id'];
	$grpName[$counter1] = $row2['Name'];
	$grpAddress[$counter1] = $row2['Address'];
	$grpZIP[$counter1] = $row2['ZIP'];
	$grpCity[$counter1]= $row2['City'];
	$grpState[$counter1]= $row2['State'];
	$grpCountry[$counter1]= $row2['Country'];
	$grpimagename[$counter1]=$row2['image'];
	
	
	
	if ($counter1>0) $cadena.=',';    
	$cadena.='
    {
        "id":"'.$grpIds[$counter1].'",
        "Name":"'.$grpName[$counter1].'",
        "Address":"'.$grpAddress[$counter1].'",
        "ZIP":"'.$grpZIP[$counter1].'",
        "City":"'.$grpCity[$counter1].'",
		"State":"'.$grpState[$counter1].'",
        "Country":"'.$grpCountry[$counter1].'",
		 "Image":"'.$grpimagename[$counter1].'"
        }'; 

	 $counter1++;

	
}

$encode = json_encode($cadena);
echo '{"items":['.($cadena).']}'; 

?>