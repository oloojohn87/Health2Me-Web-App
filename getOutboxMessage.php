<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script type="text/javascript">
	var lang = new Lang('en');
	window.lang.dynamic('th', 'jquery-lang-js-master/js/langpack/th.json');


//alert($.cookie('lang'));

var langType = $.cookie('lang');

if(langType == 'th'){
window.lang.change('th');
//alert('th');
}

if(langType == 'en'){
window.lang.change('en');
//alert('en');
}
	

</script>-->
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
$patientid=$_GET['patient'];
$receivingdoc=empty($_GET['receivingdoc']) ? 0: $_GET['receivingdoc'];

if($receivingdoc==0){
$result = $con->prepare("SELECT * FROM message_infrasture WHERE sender_id=? and patient_id=? ORDER BY fecha DESC");
$result->bindValue(1, $IdMED, PDO::PARAM_INT);
$result->bindValue(2, $patientid, PDO::PARAM_INT);
$result->execute();
}else{
$result = $con->prepare("SELECT * FROM message_infrasture WHERE sender_id=? and patient_id=? and receiver_id=? ORDER BY fecha DESC");
$result->bindValue(1, $IdMED, PDO::PARAM_INT);
$result->bindValue(2, $patientid, PDO::PARAM_INT);
$result->bindValue(3, $receivingdoc, PDO::PARAM_INT);
$result->execute();
}



echo '<table width="100%"><thead>';
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
$subject=(htmlspecialchars($row['Subject']));
$content=(htmlspecialchars($row['content']));
$receiverid=$row['receiver_id'];
$status=$row['status'];
$reportids=$row['attachments'];

if($reportids!=null and $reportids!=0){
$hasAttachments=true;
}
$getname = $con->prepare("select IdMEDFIXEDNAME from doctors where id=?");
$getname->bindValue(1, $receiverid, PDO::PARAM_INT);
$getname->execute();

$row11 = $getname->fetch(PDO::FETCH_ASSOC);
$receiverid=$row11['IdMEDFIXEDNAME'];

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

echo '</tbody></table>';    
    

?>


