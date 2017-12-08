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

$UserID = $_SESSION['UserID'];
$type_holder = $_GET['type'];
$superid = $_GET['superid'];


if($_GET['sort'] == 1){
$sort = "id";
}elseif($_GET['sort'] == 2){
$sort = "bill_date";
}elseif($_GET['sort'] == 3){
$sort = "bill_type";
}elseif($_GET['sort'] == 4){
$sort = "invoice";
}elseif($_GET['sort'] == 5){
$sort = "taxes";
}elseif($_GET['sort'] == 6){
$sort = "fees";
}elseif($_GET['sort'] == 7){
$sort = "created_date";
}elseif($_GET['sort'] == 8){
$sort = "paid";
}

if($_GET['order'] == 1){
$order = 'DESC';
$order_holder = 2;
}else{
$order = 'ASC';
$order_holder = 1;
}

if(isset($_GET['type'])){
$query = $con->prepare("select * from bill where member_id=? && bill_type = ? && super_id = ? ORDER BY ".$sort." ".$order."");
$query->bindValue(1, $UserID, PDO::PARAM_INT);
$query->bindValue(2, $type_holder, PDO::PARAM_INT);
$query->bindValue(3, $superid, PDO::PARAM_INT);
$result = $query->execute();
}else{
$query = $con->prepare("select * from bill where member_id=? && super_id = ? ORDER BY ".$sort." ".$order."");
$query->bindValue(1, $UserID, PDO::PARAM_INT);
$query->bindValue(2, $superid, PDO::PARAM_INT);
$result = $query->execute();
}

echo "<table class='table table-bordered'>
<tr><th><a onclick='getBillingInfo(".($type_holder+1).", 1, ".$order_holder.");'>ID</a></th><th>EMG<th>CPT/HCPCS</th><th>Mod1</th><th>Mod2</th><th>Mod3</th><th>Mod4</th></th><th><a onclick='getBillingInfo(".($type_holder+1).", 3, ".$order_holder.");'>Type</a></th><th><a onclick='getBillingInfo(".($type_holder+1).", 4, ".$order_holder.");'>Invoice</a></th><th><a onclick='getBillingInfo(".($type_holder+1).", 5, ".$order_holder.");'>Taxes</a></th><th><a onclick='getBillingInfo(".($type_holder+1).", 6, ".$order_holder.");'>Fees</a></th><th>Total</th><th><a onclick='getBillingInfo(".($type_holder+1).", 8, ".$order_holder.");'>Paid</a></th><th>Remaining</th><th>Images</th><th><a onclick='getBillingInfo(".($type_holder+1).", 7, ".$order_holder.");'>Created</a></th></tr>";

while($row = $query->fetch(PDO::FETCH_ASSOC)){
if($row['bill_type'] == 1){
$type = 'Image';
}elseif($row['bill_type'] == 2){
$type = 'Lab';
}elseif($row['bill_type'] == 3){
$type = 'App.';
}elseif($row['bill_type'] == 4){
$type = 'Immu.';
}else{
$type = 'Unknown';
}

$query2 = $con->prepare("select * from lifepin where IdUsu=? && bill_id = ?");
$query2->bindValue(1, $UserID, PDO::PARAM_INT);
$query2->bindValue(2, $row['id'], PDO::PARAM_INT);
$result = $query2->execute();

$count = $query2->rowCount();

echo "<tr onclick='getBillingItem(".$row['id'].");billIdHolder(".$row['id'].");'><td>".$row['id']."</td><td></td><td>".$row['cpt']."</td><td>".$row['mod1']."</td><td>".$row['mod2']."</td><td>".$row['mod3']."</td><td>".$row['mod4']."</td><td>".$type."</td><td>$".(sprintf('%0.2f',($row['invoice'] / 100)))."</td><td>$".(sprintf('%0.2f',($row['taxes'] / 100)))."</td><td>$".(sprintf('%0.2f',($row['fees'] / 100)))."</td><td>$".(sprintf('%0.2f',(($row['invoice'] + $row['taxes'] + $row['fees']) / 100)))."</td><td>$".(sprintf('%0.2f',($row['paid'] / 100)))."</td><td>$".(sprintf('%0.2f',((($row['invoice'] + $row['taxes'] + $row['fees']) - $row['paid']) / 100)))."</td><td>".$count."</td><td><font size='1'>".$row['created_date']."</font></td></tr>";
}


echo "</table>";


?>