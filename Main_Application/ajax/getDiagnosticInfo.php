<?php
ini_set("display_errors", 0);
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

if(isset($_GET['IdUsu'])){
$UserID = $_GET['IdUsu'];
}else{
$UserID = $_SESSION['UserID'];
}
//$UserID = 1313;

/*
$dates=array("May 2004","Jul 2000","Mar 1998","Mar 1998","Mar 1998","Dec 1972");
$icd=array("S62.608D","K36","V42","S72.00","S36.0","B26.9");
$surgery=array("Fracture distal finger Left Hand","Appendicectomy","Car Accident","Femoral Fracture","Splenectomy","Parotiditis");
$count=6;
*/


$Query= $con->prepare("select id,DATE_FORMAT(dxstart,'%b %Y') as sdate,dxstart,dxname,dxcode,notes,doctor_signed,latest_update from p_diagnostics where idpatient=$UserID  and deleted=0 order by dxstart desc");
$Query->bindValue(1, $UserID, PDO::PARAM_INT);
$result=$Query->execute();

$count = $Query->rowCount();

if($count==0)
{
	echo '<div  style="width:100%; height:20px; border:0px solid; text-align:center; background-color: #2c3e50; color: white;line-height: 20px;">';
	if($_COOKIE["lang"] == 'th'){
        echo 'Historia Personal';
	}else{
        echo 'Personal History';
	}
	echo '</div><center>';
	if($_COOKIE["lang"] == 'th'){
        echo 'No Hay Entradas';
	}else{
        echo 'No Entries';
	}
	echo '</center></span><p><center><img width="75px" src="../../images/icons/general_user_error_icon.png" alt="No Data Icon"></center>';
	return;
}

$count=0;
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;

while($row = $Query->fetch(PDO::FETCH_ASSOC))
{
	$dates[$count] = $row['sdate'];
	$datesource[$count] = $row['dxstart'];
	$icd[$count] = (htmlspecialchars($row['dxcode']));
	$surgery[$count] = (htmlspecialchars($row['dxname']));
	$ids[$count] = $row['id'];
	$notes[$count] = (htmlspecialchars($row['notes']));
	
	$doctor_signedP = $row['doctor_signed'];
	$latest_updateP = $row['latest_update'];
	if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}

	$count++;
}

$HeightContainer = 230;//220; //254

echo '<style>';
echo 'div.DataSlot{';
echo '  float:left; height:30px; width:276px; border:1px solid #cacaca; border-radius:5px; margin:3px; margin-left:0px; color:#22aeff; ';
echo '  } ';
echo 'div.DataSlot:hover{';
echo '  color:#54bc00; ';
echo '  } ';
echo 'div.DataSlotMin{';
echo '  float:left; height:10px; width:5px; margin-top:14px; border:0px solid #cacaca; border-radius:10px; border-top-right-radius:0px; border-bottom-right-radius:0px;  border-right:0px; color:#22aeff; background-color:#2c3e50;';
echo '  } ';
echo 'div.Section1{';
echo '  float:left; width: 95px; height:100%; border:0px solid #cacaca; margin-top: 2px;';
echo '  } ';
echo 'div.LSection1{';
echo '  float:left; width: 35px; font-size: 30px; font-weight:bold; margin-top:3px; margin-left:2px;';
echo '  } ';
echo 'div.RSection1{';
echo '  float:left; width: 50px; font-size:20px; font-weight:bold; color:black; margin-top:3px; margin-left:5px; margin: 0px;padding: 0px;padding-top: 2px;';
echo '  } ';
echo 'div.ICDCode{';
echo '  float:left; width:100%; height; 15px; font-weight:bold; font-size: 12px; margin:0px; margin-left:2px; margin-top:1px; padding: 0px;line-height: 12px;-webkit-font-smoothing: antialiased;';
echo '  } ';
echo 'div.DateLab{';
echo '  float:left; width:100%; height; 15px; color:grey; font-weight: normal; font-size: 10px; margin:0px; margin-left:2px; margin-top:-2px;  padding: 0px;line-height: 12px;-webkit-font-smoothing: antialiased;';
echo '  } ';
echo 'div.Section2{';
echo '  float:left; width: 160px; height: 90%; border:0px solid #cacaca; margin-top: 2px;display: table;';
echo '  } ';
echo 'p.DisText{';
echo '  line-height: 12px; color: grey;display: table-cell;vertical-align: middle;';
echo '  } ';
echo '</style>';


echo '<div  style="width:490px; height:20px; border:0px solid; text-align:center; background-color: #2c3e50; color: white;line-height: 20px;">';
	if($_COOKIE["lang"] == 'th'){
        echo 'Historia Personal';
	}else{
        echo 'Personal History';
	}
	echo '</div>';
//echo '<div  style="width:490px; height:16px; border:0px solid; text-align:center; background-color: #22aeff;color: white;line-height: 16px;">Personal History</div>';
echo '<div id="TimelineContainer" 		style="width:480px; height:'.$HeightContainer.'px; margin-top:18px; border:0px solid #cacaca;">';
echo '	<div id="ColDataLabels"     	style="float:left; width:100px; height:100%; border:0px solid #cacaca; position:relative;">';

$pixelLength = $HeightContainer-5;
$SToday = date("M Y");
if($_COOKIE["lang"] == 'th'){
if(substr($SToday, 0, 3) == 'Jan'){
$new_month = 'Ene '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Feb'){
$new_month = 'Feb '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Mar'){
$new_month = 'Mar '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Apr'){
$new_month = 'Abr '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'May'){
$new_month = 'May '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Jun'){
$new_month = 'Jun '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Jul'){
$new_month = 'Jul '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Aug'){
$new_month = 'Ago '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Sep'){
$new_month = 'Sep '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Oct'){
$new_month = 'Oct '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Nov'){
$new_month = 'Nov '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Dec'){
$new_month = 'Dic '.substr($SToday, 4, 4);
}
}else{
if(substr($SToday, 0, 3) == 'Jan'){
$new_month = 'Jan '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Feb'){
$new_month = 'Feb '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Mar'){
$new_month = 'Mar '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Apr'){
$new_month = 'Apr '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'May'){
$new_month = 'May '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Jun'){
$new_month = 'Jun '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Jul'){
$new_month = 'Jul '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Aug'){
$new_month = 'Aug '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Sep'){
$new_month = 'Sep '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Oct'){
$new_month = 'Oct '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Nov'){
$new_month = 'Nov '.substr($SToday, 4, 4);
}
if(substr($SToday, 0, 3) == 'Dec'){
$new_month = 'Dec '.substr($SToday, 4, 4);
}
}
echo '<div class="DataLab" style="float:right; margin-right:5px; width: 70px; position:absolute; top:-10px; font-size:12px; color:#22aeff;text-align: right;left: 25px;">'.$new_month.'</div>';	

if($_COOKIE["lang"] == 'th'){
if(substr($dates[$count-1], 0, 3) == 'Jan'){
$new_month = 'Ene '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Feb'){
$new_month = 'Feb '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Mar'){
$new_month = 'Mar '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Apr'){
$new_month = 'Abr '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'May'){
$new_month = 'May '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Jun'){
$new_month = 'Jun '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Jul'){
$new_month = 'Jul '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Aug'){
$new_month = 'Ago '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Sep'){
$new_month = 'Sep '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Oct'){
$new_month = 'Oct '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Nov'){
$new_month = 'Nov '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Dec'){
$new_month = 'Dic '.substr($dates[$count-1], 4, 4);
}
}else{
if(substr($dates[$count-1], 0, 3) == 'Jan'){
$new_month = 'Jan '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Feb'){
$new_month = 'Feb '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Mar'){
$new_month = 'Mar '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Apr'){
$new_month = 'Apr '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'May'){
$new_month = 'May '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Jun'){
$new_month = 'Jun '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Jul'){
$new_month = 'Jul '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Aug'){
$new_month = 'Aug '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Sep'){
$new_month = 'Sep '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Oct'){
$new_month = 'Oct '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Nov'){
$new_month = 'Nov '.substr($dates[$count-1], 4, 4);
}
if(substr($dates[$count-1], 0, 3) == 'Dec'){
$new_month = 'Dec '.substr($dates[$count-1], 4, 4);
}
}

echo '<div class="DataLab" style="float:right; margin-right:5px; width: 70px; position:absolute; top:'.($pixelLength-10).'px; font-size:12px; color:#22aeff;text-align: right;left: 25px;">'.$new_month.'</div>';	

$n = 0;
$daysLength = daysOld($datesource[$count-1]);
$pixelLength = $HeightContainer-5;
$displayCount[0] = 0;
$displayCount[1] = $pixelLength;
$dcount = 2;
while ($n < $count)
{	
	$days = daysOld($datesource[$n]);
	$dateHeight = ($pixelLength * $days)/$daysLength;
	
	$rn = 0;
	$display = 1;
	while ($rn < $dcount)
	{
		if (abs($dateHeight - $displayCount[$rn]) < 20) $display = 0;  // don't display
		$rn++;
	}

	if ($display == 1) 
	{
	
	if($_COOKIE["lang"] == 'th'){
if(substr($dates[$n], 0, 3) == 'Jan'){
$new_month = 'Ene '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Feb'){
$new_month = 'Feb '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Mar'){
$new_month = 'Mar '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Apr'){
$new_month = 'Abr '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'May'){
$new_month = 'May '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Jun'){
$new_month = 'Jun '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Jul'){
$new_month = 'Jul '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Aug'){
$new_month = 'Ago '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Sep'){
$new_month = 'Sep '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Oct'){
$new_month = 'Oct '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Nov'){
$new_month = 'Nov '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Dec'){
$new_month = 'Dic '.substr($dates[$n], 4, 4);
}
}else{
if(substr($dates[$n], 0, 3) == 'Jan'){
$new_month = 'Jan '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Feb'){
$new_month = 'Feb '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Mar'){
$new_month = 'Mar '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Apr'){
$new_month = 'Apr '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'May'){
$new_month = 'May '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Jun'){
$new_month = 'Jun '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Jul'){
$new_month = 'Jul '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Aug'){
$new_month = 'Aug '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Sep'){
$new_month = 'Sep '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Oct'){
$new_month = 'Oct '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Nov'){
$new_month = 'Nov '.substr($dates[$n], 4, 4);
}
if(substr($dates[$n], 0, 3) == 'Dec'){
$new_month = 'Dec '.substr($dates[$n], 4, 4);
}
}
		echo '<div class="DataLab" style="float:right; margin-right:5px; width: 70px; position:absolute; top:'.($dateHeight-10).'px; font-size:12px; color:#22aeff;text-align: right;left: 25px;">'.$new_month.'</div>';	
		$displayCount[$dcount] = $dateHeight;
		$dcount++;
	}

	$n++;
}


echo '	</div>';// Closing of ColDataLabels

echo '	<div id="ColTimelineGraph"     	style="float:left; width:30px; height:230px; border:1px solid #cacaca; border-radius:3px; position:relative;">';
$HeightLabel = 32 + 3+ 3;
$n = 0;
$daysLength = daysOld($datesource[$count-1]);
$pixelLength = $HeightContainer-5;
while ($n < $count)
{	
	$days = daysOld($datesource[$n]);
	$dateHeight = ($pixelLength * $days)/$daysLength;
	echo '<div class="ELine" style="height: 0px; width: 30px; border: 1px solid grey; position:absolute; left: -1px; top:'.$dateHeight.'px;"></div>';	     $n++;
}


echo '	</div>';// Closing of ColTimelineGraph
echo '	<div id="ColConnectingLines"   	style="float:left; width:50px; height:230px; border:0px solid #cacaca; position:relative;">';

$n = 0;
$daysLength = daysOld($datesource[$count-1]);
$pixelLength = $HeightContainer-5;
while ($n < $count)
{	
	$days = daysOld($datesource[$n]);
	$dateHeight = ($pixelLength * $days)/$daysLength;
	$x1 = 0;
	$y1 = $dateHeight+1;
	$x2 = 50;
	$y2 = ($HeightLabel * ($n+1))-($HeightLabel/2);
	$returned =  degL($x1,$y1,$x2,$y2);
	$Len = $returned[0];
	$Alf = $returned[1];
	//echo '<div class="ELine" style="height: 0px; width:50px; border: 1px solid grey; position:absolute; left: 1px; top:'.($HeightLabel * ($n+1)).'px; border-color:red;"></div>';	
	echo '<div class="ELine" style="height: 0px; width:'.$Len.'px; border-top: 1px solid lightgrey; position:absolute; left: 0px; top:'.$y1.'px; transform:rotate('.$Alf.'deg); -ms-transform:rotate('.$Alf.'deg); -webkit-transform:rotate('.$Alf.'deg); -webkit-transform-origin: 0% 0%;"></div>';	    
$n++;
}


echo '	</div>'; // Closing of ColConnectingLines
echo '	<div id="ColDataSlots"   		style="float:left; width:290px; height:230px; border:0px solid #cacaca;">';

$n = 0;
while ($n < $count)
{
	$fIndex = sprintf("%02d", $n);
	//$date = date_create('2000-01-01');
	//echo date_format($date, 'Y-m-d H:i:s');
	$date = date_create($dates[$n]);
	$dateA = date_format($date, 'M Y');
	
	if($_COOKIE["lang"] == 'th'){
if(substr($dateA, 0, 3) == 'Jan'){
$new_month = 'Ene '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Feb'){
$new_month = 'Feb '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Mar'){
$new_month = 'Mar '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Apr'){
$new_month = 'Abr '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'May'){
$new_month = 'May '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Jun'){
$new_month = 'Jun '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Jul'){
$new_month = 'Jul '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Aug'){
$new_month = 'Ago '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Sep'){
$new_month = 'Sep '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Oct'){
$new_month = 'Oct '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Nov'){
$new_month = 'Nov '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Dec'){
$new_month = 'Dic '.substr($dateA, 4, 4);
}
}else{
if(substr($dateA, 0, 3) == 'Jan'){
$new_month = 'Jan '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Feb'){
$new_month = 'Feb '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Mar'){
$new_month = 'Mar '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Apr'){
$new_month = 'Apr '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'May'){
$new_month = 'May '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Jun'){
$new_month = 'Jun '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Jul'){
$new_month = 'Jul '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Aug'){
$new_month = 'Aug '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Sep'){
$new_month = 'Sep '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Oct'){
$new_month = 'Oct '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Nov'){
$new_month = 'Nov '.substr($dateA, 4, 4);
}
if(substr($dateA, 0, 3) == 'Dec'){
$new_month = 'Dec '.substr($dateA, 4, 4);
}
}

 // echo '<style>div.SemiBall {width:0px; }; </style>';
    if (strlen($icd[$n]) > 1 ) $ballcolor = '#2c3e50'; else $ballcolor = '#22aeff';
    echo '<div class="DataSlotMin" style="background-color:'.$ballcolor.';"></div>';		
    echo '		<div class="DataSlot" style="float:left;">';
    echo '			<div class="Section1">';
	echo '          	<div class="LSection1"><font size="5">'.$fIndex.'</font></div>';
	echo '          	<div class="RSection1">';
	echo '					<div class="ICDCode">'.substr($icd[$n],0,7).'</div>';
	echo '					<div class="DateLab">'.$new_month.'</div>';
	echo '				</div>';
	echo '			</div>';
	echo '			<div class="Section2">';
	echo '				<p class="DisText">'.substr($surgery[$n],0,30).'</p>';
	echo '			</div>';
	echo '		</div>';	
	
	$n++;
}

echo '	</div>'; // Closing of ColDataSlots
echo '</div>';  // Closing of TimelineContainer

echo '<input id="Ddoctor_signed" value="'.$doctor_signed.'" style="width:20px; float:left; display:none;">';	
echo '<input id="Dlatest_update" value="'.$latest_update.'" style="width:120px; float:left; display:none;">';	


function checkOvlp($coordY)
{


}

function degL($x1,$y1,$x2,$y2)
{
	$a = $x2 - $x1;
	$b = $y2 - $y1;
	$L = sqrt(pow($a,2) + pow($b,2));
	
	$alfa = asin($b/$L);
	
	$solved[0] = $L;
	$solved[1] = rad2deg($alfa);
	
	return $solved;
}

function daysOld($entryDate)
{
	$ts2 = strtotime($entryDate);
	$today = strtotime(date("Y-m-d H:i:s"));	

	$seconds_diff = $today - $ts2 ;	
	$days = floor($seconds_diff/3600/24);	
	$years = floor($days/365);	
	
	return $days;
}


function addNewBlankLine($number)
{
	for($i=0;$i<$number;$i++)
	{
		echo '<tr>';
		echo '<td style="width:30px;text-align:center"><img src="../../images/line.jpg" style="height:20px;"></img></td>';
		//echo '<td style="border-left:thick solid black"></td>'; 
		 
		echo '<td></td>';
		echo '<td></td>';
		echo '<td></td>';
		echo '</tr>';

	}

}


function addNewBallLine($id,$dt,$icd,$surgery)
{
	$rowid = $id;
	$notesid = "Note".$id;
	echo '<tr>';
	echo '<td style="width:30px;text-align:center"><i class="icon-circle"></i></td>';
	echo '<td style="width:70px;text-align:left">'.$dt.'</td>';
	echo '<td style="width:50px;text-align:left">'.$icd.'</td>';
	echo '<td style="cursor:pointer;width:100px;text-align:left;color:red" class="Surgery" id='.$rowid.'>'.$surgery.'</td>';
	echo '</tr>';
	
	
}


function addNewLine($id,$dt,$icd,$surgery)
{
	$rowid = $id;
	$notesid = "Note".$id;
	echo '<tr>';
	echo '<td style="width:30px;text-align:center"><img src="../../images/line.jpg" style="height:20px;"></img></td>';
	echo '<td style="width:70px;text-align:left">'.$dt.'</td>';
	echo '<td style="width:50px;text-align:left">'.$icd.'</td>';
	echo '<td style="cursor:pointer;width:100px;text-align:left;color:red" class="Surgery" id='.$rowid.'>'.$surgery.'</td>';
	echo '</tr>';
}


?>
