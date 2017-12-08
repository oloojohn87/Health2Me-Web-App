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

$result = $con->prepare("SELECT * FROM notification_new WHERE channel_id=? and status='new' ORDER BY fecha DESC");
$result->bindValue(1, $IdMED, PDO::PARAM_INT);
$result->execute();


while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

$id=$row['id'];
$message=$row['message'];
$rep_date = substr($row['fecha'],0,10);
$year=strtok($rep_date,"-");
$month=strtok("-");
$day = strtok("-");
$rep_date = $month."-".$day."-".$year;

echo '<div id="notif_'.$id.'" class="imessage" style="display:block;">';
//echo '<div class="r_icon"><a href="#"><i class="icon-camera"></i></a></div>';
echo '<div class="r_info" style="display:block;">';
echo '<button class="close" type="button" data-dismiss="modal" id="notif_btn'.$id.'">×</button>';
echo '<div class="r_name" style="display:block;">'.$message.'</div>';
echo '<div class="r_text" style="display:block;"><i class="icon-time"></i>'.$rep_date.'</div>';
//echo '<div class="r_link"><a href="#">View...</a></div>';
echo '</div><div class="clear" style="display:block;"></div></div>';

}
   
    

?>


