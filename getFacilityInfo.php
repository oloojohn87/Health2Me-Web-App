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

  
 
 
/*$idpatient = $_SESSION['UserID'];
$iddoctor = $_SESSION['MEDID'];
if(!isset($iddoctor)){
$iddoctor = 0;
}
if ($iddoctor == $idpatient) $idauthor = -1; else $idauthor = $iddoctor;
*/

$doc_id = $_GET['docid'];


$query = $con->prepare("SELECT * FROM schedule_facility WHERE doctor_id=?");
$query->bindValue(1, $doc_id, PDO::PARAM_INT);
$result = $query->execute();

if(isset($_GET['grabstats'])){
echo "<table class='table table-bordered'>
<tr><th><center>Facility Stats</center></th></tr>";
}else{
echo "<table class='table table-bordered'>
<tr><th><center>Facilities</center></th></tr>";
}

if(isset($_GET['grabstats'])){
while($row = $query->fetch(PDO::FETCH_ASSOC)){

$query2 = $con->prepare("SELECT * FROM schedule_rooms WHERE facility_id=?");
$query2->bindValue(1, $row['id'], PDO::PARAM_INT);
$result = $query2->execute();
$count = $query2->rowCount();
$t_count = 0;

while($row2 = $query2->fetch(PDO::FETCH_ASSOC)){
$query3 = $con->prepare("SELECT * FROM schedule_appointment WHERE room_id=?");
$query3->bindValue(1, $row2['id'], PDO::PARAM_INT);
$result = $query3->execute();
$count2 = $query3->rowCount();
$t_count = $t_count + $count2;
}

echo "<tr onclick='getFacilityRooms(".$row['id'].");'><td><b>Rooms:</b>".$count."</td><td><b>Total Appointments:</b>".$t_count."</td></tr>";
}
}else{
while($row = $query->fetch(PDO::FETCH_ASSOC)){
echo "<tr onclick='getFacilityRooms(".$row['id'].");'><td>".$row['name']."</td></tr>";
}
}
echo "</table>";
?>