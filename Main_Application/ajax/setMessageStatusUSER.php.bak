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

$msg_id= $_GET['msgid'];
$delete=empty($_GET['delete']) ? 0 : 1;

if($delete){
$result = $con->prepare("delete from message_infrastructureUSER where message_id=?");
$result->bindValue(1, $msg_id, PDO::PARAM_INT);
$result->execute();

}else{
$result = $con->prepare("Update message_infrastructureUSER set status='read' where message_id=?");
$result->bindValue(1, $msg_id, PDO::PARAM_INT);
$result->execute();

}
/*
while ($row = mysql_fetch_array($result)) {

$id=$row['message_id'];
$subject=$row['Subject'];
$content=$row['content'];
$senderid=$row['sender_id'];
$getname = mysql_query("select IdMEDFIXEDNAME from doctors where id='$senderid'");
$row11 = mysql_fetch_array($getname);
$senderid=$row11['IdMEDFIXEDNAME'];
//$date=$row['fecha'];
$rep_date = substr($row['fecha'],0,10);
$year=strtok($rep_date,"-");
$month=strtok("-");
$day = strtok("-");
$rep_date = $month."-".$day."-".$year;

echo '<tr class="CFILA" id="'.$id.'">';
echo '<td class="chex-table"><input type="checkbox" name="numbers[]" class="mc" value="0" id="checkcol'.$id.'"/><label for="checkcol'.$id.'"><span></span></label></td>';
//echo '<td class="min-width"><a href="javascript:void(0)"><i class="icon-star"></i></a></td>';
echo '<td id="'.$id.'"><span class="label label-success" style="margin-right:20px ;font-size:10px;">new</span><a href="javascript:void(0)"> <b>'.$subject.'</b></a><span id="'.$id.'" style="display:none">'.$content.'</span></td>';
echo '<td>'.$senderid.'</td>';
echo '<td>'.$rep_date.'</td>';
echo '</tr>';

}

echo '</tbody>';   */ 
    

?>


