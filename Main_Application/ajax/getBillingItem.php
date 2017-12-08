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
$id = $_GET['id'];


$query = $con->prepare("select * from bill where member_id=? && id = ?");
$query->bindValue(1, $UserID, PDO::PARAM_INT);
$query->bindValue(2, $id, PDO::PARAM_INT);
$result = $query->execute();

$query2 = $con->prepare("select * from lifepin where IdUsu=? && bill_id = ?");
$query2->bindValue(1, $UserID, PDO::PARAM_INT);
$query2->bindValue(2, $id, PDO::PARAM_INT);
$result = $query2->execute();

$count = $query2->rowCount();

echo "<table class='table table-bordered'>
<tr><th>ID</th><th>Report Date</th><th>Type</th><th>Invoice</th><th>Taxes</th><th>Fees</th><th>Total</th><th>Paid</th><th>Remaining</th><th>Images</th><th>Created</th></tr>";

$row = $query->fetch(PDO::FETCH_ASSOC);

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

echo "<tr><td>".$row['id']."</td><td>".$row['bill_date']."</td><td>".$type."</td><td>$".(sprintf('%0.2f',($row['invoice'] / 100)))."</td><td>$".(sprintf('%0.2f',($row['taxes'] / 100)))."</td><td>$".(sprintf('%0.2f',($row['fees'] / 100)))."</td><td>$".(sprintf('%0.2f',(($row['invoice'] + $row['taxes'] + $row['fees']) / 100)))."</td><td>$".(sprintf('%0.2f',($row['paid'] / 100)))."</td><td>$".(sprintf('%0.2f',((($row['invoice'] + $row['taxes'] + $row['fees']) - $row['paid']) / 100)))."</td><td>".$count."</td><td><font size='1'>".$row['created_date']."</font></td></tr>";

echo "</table>";
echo '<div style=" color: #777;"> 
                <span style="float: left; margin-top: 6px;padding:5px;" lang="en">Label Bill :</span>
                <input type="text" id="bill_name" value="'.$row['name'].'" style="float: left;width:10%;padding:5px;" >
			</div>
			
			<div style="color: #777;"> 
                <span style="float: left; margin-top: 6px;padding:5px;" lang="en">Bill Date :</span>
                <input type="date" id="bill_date"  style="float: left;width:15%;padding:5px;" >
			</div>
			
			<div style=" color: #777;"> 
                <span style="float: left; margin-top: 6px;padding:5px;" lang="en">Bill Type :</span>
				<select id="select_type" style="width:20%;padding:5px;">
				<option value="1">Imaging</option>
				<option value="2">Lab</option>
				<option value="3">Appointment</option>
				<option value="4">Immunization</option>
				</select>
				</div>
				
				<div style="color: #777;margin-right:80%;"> 
                <span style="float: left; margin-top: 6px;padding:5px;" lang="en">Invoice :</span>
                <input type="number" step="0.01" value="'.(sprintf('%0.2f',($row['invoice'] / 100))).'" id="invoice_amount" style="float: left;width:40%;padding:5px;" >
			</div>
			<div style="color: #777;"> 
                <span style="float: left; margin-top: 6px;padding:5px;" lang="en">Taxes(if any) :</span>
                <input type="number" step="0.01" id="taxes" value="'.(sprintf('%0.2f',($row['taxes'] / 100))).'" style="float: left;width:8%;padding:5px;" >
			</div>
			<div style="color: #777;"> 
                <span style="float: left; margin-top: 6px;padding:5px;" lang="en">Fees(if any) :</span>
                <input type="number" step="0.01" id="fees" value="'.(sprintf('%0.2f',($row['fees'] / 100))).'" style="float: left;width:8%;padding:5px;" >
			</div>
			<div style="color: #777;"> 
                <span style="float: left; margin-top: 6px;padding:5px;" lang="en">Amount Paid :</span>
                <input type="number" step="0.01" id="paid" value="'.(sprintf('%0.2f',($row['paid'] / 100))).'" style="float: left;width:8%;padding:5px;" >
			</div>
			</br>
			<center><div style=" margin-top:3%;">
                 <button id="create_bill_button" style=" background-color: #22AEFF; color: #FFF; outline: 0px; border: 0px solid #FFF; border-radius: 7px; margin-top: 15px;" lang="en">
                    Update Bill
                 </button>
             </div></center>
			 <div >
			 <a onclick="showImage();"><button id="show_image_button" style="background-color: #grey; color: black; outline: 0px; border: 0px solid #FFF; border-radius: 7px; float:right;margin-top:-1%;" lang="en">
                    Show Images
                 </button></a>
                 <a onclick="addImage();"><button id="images_button" style="background-color: #grey; color: black; outline: 0px; border: 0px solid #FFF; border-radius: 7px; float:right;margin-top:-1%;" lang="en">
                    Add Image
                 </button></a>
             </div>
			 <hr>';


?>