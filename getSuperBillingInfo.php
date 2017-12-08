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


if($_GET['sort'] == 1){
$sort = "id";
}elseif($_GET['sort'] == 2){
$sort = "sdate";
}

if($_GET['order'] == 1){
$order = 'DESC';
$order_holder = 2;
}else{
$order = 'ASC';
$order_holder = 1;
}


$query = $con->prepare("select * from bill_super where patient_id=? ORDER BY ".$sort." ".$order."");
$query->bindValue(1, $UserID, PDO::PARAM_INT);
$result = $query->execute();


echo "<table class='table table-bordered'>";

echo "<tr><th><a onclick='getSuperBillingInfo(1, ".$order_holder.");'>ID</a></th><th>Label</th><th><a onclick='getSuperBillingInfo(2, ".$order_holder.");'>From</a></th><th>To</th><th>Service Location</th><th>Sum Invoices</th><th>Sum Taxes</th><th>Sum Fees</th><th>Sum Total</th><th>Sum Paid</th><th>Sum Rem. Balance</th></tr>";



while($row = $query->fetch(PDO::FETCH_ASSOC)){

$query2 = $con->prepare("select SUM(invoice),SUM(taxes),SUM(fees),SUM(paid) from bill where member_id=? && super_id = ? ORDER BY ".$sort." ".$order."");
$query2->bindValue(1, $UserID, PDO::PARAM_INT);
$query2->bindValue(2, $row['id'], PDO::PARAM_INT);
$result2 = $query2->execute();

$row2 = $query2->fetch(PDO::FETCH_ASSOC);

$sum_invoice = (sprintf('%0.2f',($row2['SUM(invoice)'] / 100)));
$total_sum_invoice = $total_sum_invoice + $sum_invoice;
$sum_taxes = (sprintf('%0.2f',($row2['SUM(taxes)'] / 100)));
$total_sum_taxes = $total_sum_taxes + $sum_taxes;
$sum_fees = (sprintf('%0.2f',($row2['SUM(fees)'] / 100)));
$total_sum_fees = $total_sum_fees + $sum_fees;
$sum_total = (sprintf('%0.2f',(($row2['SUM(invoice)'] + $row2['SUM(taxes)'] + $row2['SUM(fees)']) / 100)));
$total_sum_total = $total_sum_total + $sum_total;
$sum_paid = (sprintf('%0.2f',($row2['SUM(paid)'] / 100)));
$total_sum_paid = $total_sum_paid + $sum_paid;
$sum_balance = (sprintf('%0.2f',((($row2['SUM(invoice)'] + $row2['SUM(taxes)'] + $row2['SUM(fees)']) - $row2['SUM(paid)']) / 100)));
$total_sum_balance = $total_sum_balance + $sum_balance;

echo "<tr onclick='getSuperBillingItem(".$row['id'].");'><td>".$row['id']."</td><td>".$row['name']."</td><td>".$row['sdate']."</td><td>".$row['edate']."</td><td>".$row['service_location']."</td><td>$".$sum_invoice."</td><td>$".$sum_taxes."</td><td>$".$sum_fees."</td><td>$".$sum_total."</td><td>$".$sum_paid."</td><td>$".$sum_balance."</td></tr>";
}
echo "<tr>
<td>X</td>
<td>X</td>
<td>X</td>
<td>X</td>
<td>X</td>
<td>$".$total_sum_invoice."</td>
<td>$".$total_sum_taxes."</td>
<td>$".$total_sum_fees."</td>
<td>$".$total_sum_total."</td>
<td>$".$total_sum_paid."</td>
<td>$".$total_sum_balance."</td>
</tr>";
echo "</table>";

?>