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

 


$doc_id = $_GET['docid'];
$practice_id = $_GET['id'];

if(isset($_GET['show'])){
$query = $con->prepare("SELECT * FROM script_practices WHERE doc_owner = ?");
$query->bindValue(1, $doc_id, PDO::PARAM_INT);
$result = $query->execute();

$count = $query->rowCount();

echo "<table class='table table-bordered' id='practice_table'>";

if($count == 0){
echo "<tr><td><center>No practices have been added.  Please add a practice to proceed.</center></td></tr>";
}else{
echo "<tr>
<th><center>Practice</center></th>
</tr>";
while($row = $query->fetch(PDO::FETCH_ASSOC)){

$pname = $row['name'];

echo "<tr onclick='setPractice(".$row['id'].");'>
<td><center>".$row['name']."</center></td>
</tr>";
}
}
echo "</table>";

echo "<button id='add_practice' onclick='createPracticesList();' style='width: 250px; height: 30px; background-color: #22AEFF; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 7px; margin-top: 15px;' lang='en'>
                    Add Practice For E-Prescribing
                 </button>";

}else{


$query = $con->prepare("SELECT * FROM script_locations WHERE doc_owner = ? && practice_id = ?");
$query->bindValue(1, $doc_id, PDO::PARAM_INT);
$query->bindValue(2, $practice_id, PDO::PARAM_INT);
$result = $query->execute();

$count = $query->rowCount();

echo "<table class='table table-bordered' id='location_table'>";

if($count == 0){
echo "<tr><td><center>No prescribing locations have been added.  Please add a prescribing location to proceed.</center></td></tr>";
}else{
echo "<tr>
<th>Clinic</th>
<th>Address1</th>
<th>Address2</th>
<th>City</th>
<th>State</th>
<th>Zip</th>
<th>phone</th>
<th>Fax</th>
</tr>";
while($row = $query->fetch(PDO::FETCH_ASSOC)){
$cname = $row['clinic_name'];
$pname = $row['practice_name'];
$address1 = $row['address1'];
$address2 = $row['address2'];
$city = $row['city'];
$state = $row['state'];
$zip = $row['zip'];
$phone = $row['phone'];
$fax = $row['fax'];

echo "<tr onclick='setLocation(".$row['id'].");'>
<td>".$row['clinic_name']."</td><td>".$row['address1']."</td><td>".$row['address2']."</td><td>".$row['city']."</td><td>".$row['state']."</td><td>".$row['zip']."</td><td>".$row['phone']."</td><td>".$row['fax']."</td>
</tr>";
}
}
echo "</table>";

echo "<button id='add_location' onclick='createLocationsList();' style='width: 250px; height: 30px; background-color: #22AEFF; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 7px; margin-top: 15px;' lang='en'>
                    Add Location For E-Prescribing
                 </button>";
}
?>