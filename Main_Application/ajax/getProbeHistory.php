<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
 session_start();
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 
$tbl_name="usuarios"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			

$PatientID = $_GET['patientID'];
$doctorID =  $_SESSION['MEDID'];


echo  '<table class="table table-mod" id="TablaProbeHistory" style="height:100px; width:600px; overflow-y:hidden; table-layout: fixed; border: 1px SOLID #CACACA;">';
echo '<thead>
<tr>
    <th style="width:20px;text-align:center">Sr.No</th>
    <th style="width:50px;text-align:center">Date</th>
	<th style="width:70px;text-align:center">Status</th>
	<th style="width:20px;text-align:center"></th>
</tr>
</thead>';
echo '<tbody>';




$query = $con->prepare("select * from probe where patientID=? and doctorID = ?");
$query->bindValue(1, $PatientID, PDO::PARAM_INT);
$query->bindValue(2, $doctorID, PDO::PARAM_INT);
$result = $query->execute();

$row = $query->fetch(PDO::FETCH_ASSOC);
$probeID=$row['probeID'];
//$query = "select date(responseTime) as dt,response FROM proberesponse where probeID=".$row['probeID']." order by responseTime desc";
$query = $con->prepare("select DATE_FORMAT(requestTime,'%M %d , %Y') as dt,DATE_FORMAT(requestTime,'%l:%i %p') as dttime,result as response,emergency FROM sentprobelog where probeID=? order by requestTime desc");
$query->bindValue(1, $row['probeID'], PDO::PARAM_INT);
$result = $query->execute();

$i=1;
while($row = $query->fetch(PDO::FETCH_ASSOC))
{
		if($row['emergency'] == 1)
		{
			$emergencyIcon='<i class="icon-exclamation icon-2x" style="color:red;margin-left:5px" title="Emergency Probe"></i>';;
		}
		else
		{
			$emergencyIcon='';
		}

		echo '<tr>  
				<td style="text-align:center;">'.$i.$emergencyIcon.'</td>
				
				<td style="text-align:center;"><span style="font-size:12px; color:#54bc00; font-weight:normal;">'.$row['dt'].'</span>
					<div style="width:100%; margin-top:-8px;"></div>
					<span style="font-size:10px; color:grey; font-weight:normal;">'.$row['dttime'].'</span>
				</td>
				
				<td style="text-align:center;">'.getResponseText($row['response']).'</td>
				<td style="text-align:center;">'.getResponseIcon($row['response']).'</td>
		
		
			</tr>';
		$i++;

}
 
	
echo '</tbody></table>';    

$query = $con->prepare("select count(*) as cnt from sentprobelog where probeID=? and result>0 and result<6");
$query->bindValue(1, $probeID, PDO::PARAM_INT);
$result = $query->execute();

$row = $query->fetch(PDO::FETCH_ASSOC);
$probesResponded = $row['cnt'];

$query = $con->prepare("select count(*) as cnt from sentprobelog where probeID=? and result>=0 and result<6 or result=12");
$query->bindValue(1, $probeID, PDO::PARAM_INT);
$result = $query->execute();

$row = $query->fetch(PDO::FETCH_ASSOC);
$probesSent = $row['cnt'];

$query = $con->prepare("select emailRequest,phoneRequest,smsRequest from probe where probeID=?");
$query->bindValue(1, $probeID, PDO::PARAM_INT);
$result = $query->execute();

$row = $query->fetch(PDO::FETCH_ASSOC);

if($row['emailRequest']==1)
{
	echo '<input type="hidden" id="ProbeMethod" value="1"></input>';
}
else if($row['phoneRequest']==1)
{
	echo '<input type="hidden" id="ProbeMethod" value="2"></input>';
}
else
{
	echo '<input type="hidden" id="ProbeMethod" value="3"></input>';
}

echo '<input type="hidden" id="ProbesResponded" value='.$probesResponded.'></input>';
echo '<input type="hidden" id="ProbesSent" value='.$probesSent.'></input>';

function getResponseText($response)
{
	
	switch($response)
	{
		case 0:$responseText = "No Response";
				break;
		case 1:$responseText = "Very Bad";
		       	break;
		case 2:$responseText = "Bad";
				break;
		case 3:$responseText = "Normal";
				break;
		case 4:$responseText = "Good";
				break;
		case 5:$responseText = "Very Good";
				break;
		case 12:$responseText = "User Busy on Another Call";
				break;		
		case 13:$responseText = "Wrong Phone Number";
				break;				
		case 14:$responseText = "Probe Failed";
				break;						
		case 15:$responseText = "Probe Cancelled";
				break;								
				
				
				
	}
	return $responseText;
}

function getResponseIcon($response)
{
	$path = 'images/ProbeIcons/';
	$responseText='';
	switch($response)
	{
		case 0:$responseText = '<i class="icon-question icon-2x" style="color:red"></i>';
		       	break;
		case 1:$responseText = "<img src='".$path."f1.png' title='Very Bad' style='height:30px'></img>";
		       	break;
		case 2:$responseText = "<img src='".$path."f2.png' title='Bad' style='height:30px'></img>";
				break;
		case 3:$responseText = "<img src='".$path."f3.png' title='Normal' style='height:30px'></img>";
				break;
		case 4:$responseText = "<img src='".$path."f4.png' title='Good' style='height:30px'></img>";
				break;
		case 5:$responseText = "<img src='".$path."f5.png' title='Very Good' style='height:30px'></img>";
				break;
		
		case 12://busy
		
				break;
		case 13://wrong number
		case 14://call failed
				$responseText = '<i class="icon-thumbs-down icon-2x" ></i>';
		       	break;				
		
		
		case 15://Probe cancelled
				$responseText = '<i class="icon-ban-circle icon-2x" style="color:red"></i>';
		       	break;		
		
		default:break;		
	}
	return $responseText;
}
?>
