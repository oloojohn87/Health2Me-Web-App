<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("environment_detail.php");
 
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="messages"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$IdMED= $_GET['IdMED'];

$result = $con->prepare("SELECT * FROM message_infrastructureuser WHERE sender_id=? AND tofrom='from' ORDER BY fecha DESC");
$result->bindValue(1, $IdMED, PDO::PARAM_INT);
$result->execute();

echo '<thead>';
echo '<tr>';
echo '<th class="chex-table"><input type="checkbox" id="maincheck" name="cc"/><label for="maincheck"><span></span></label></th>';
//echo '<th class="min-width"><i class="icon-star"></i></th>';
echo '<th lang="en">Subject</th>';
echo '<th lang="en">Send To</th>';
echo '<th lang="en">Date</th>';
echo '</tr>';
echo '</thead><tbody>';

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
$hasAttachments=false;
$id=$row['message_id'];
$subject=$row['Subject'];
$content=$row['content'];
$receiverid=$row['receiver_id'];
$status=$row['status'];
$reportids=$row['attachments'];
$patient_id=$row['patient_id'];

if($reportids!=null and $reportids!=0){
$hasAttachments=true;
}
$getname = $con->prepare("select IdUsFIXEDNAME FROM usuarios where Identif=?");
$getname->bindValue(1, $patient_id, PDO::PARAM_INT);
$getname->execute();

$row11 = $getname->fetch(PDO::FETCH_ASSOC);
$receiverid=$row11['IdUsFIXEDNAME'];

//$date=$row['fecha'];
$rep_date = substr($row['fecha'],0,10);
$year=strtok($rep_date,"-");
$month=strtok("-");
$day = strtok("-");
$rep_date = $month."-".$day."-".$year;

echo '<tr class="CFILA_P_out" id="'.$id.'">';
echo '<td class="chex-table"><input type="checkbox" name="numbers[]" class="mc" value="0" id="checkcol'.$id.'"/><label for="checkcol'.$id.'"><span></span></label></td>';
//echo '<td class="min-width"><a href="javascript:void(0)"><i class="icon-star"></i></a></td>';
/*if($status=='new'){
echo '<td class="CFILA_out" id="'.$id.'"><span class="label label-success" style="margin-right:20px ;font-size:10px;">new</span>';
echo '<a href="javascript:void(0)"> <b>'.$subject.'</b></a><span id="'.$id.'" style="display:none">'.$content.'</span>';
if($hasAttachments){echo'<i class="icon-paper-clip" style="float:right;font-size:18px"></i><ul id="'.$id.'" style="display:none">'.$reportids.'</ul></td>';}

}else{*/
echo '<td class="CFILA_out" id="'.$id.'">';
echo '<a href="javascript:void(0)">'.$subject.'</a><span id="'.$id.'" style="display:none">'.$content.'</span>';
if($hasAttachments){echo'<i class="icon-paper-clip" style="float:right;font-size:18px"></i><ul id="'.$id.'" style="display:none">'.$reportids.'</ul></td>';}

//}

echo '<td>'.$receiverid.'</td>';
echo '<td>'.$rep_date.'</td>';
echo '</tr>';

}

echo '</tbody>';    
    

?>


