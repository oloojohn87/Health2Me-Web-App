<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
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
	

</script>
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

$queUsu = $_GET['searchString'];
$All2=$_GET['All'];
$IdMed = $_GET['IdMed'];
$currPage = $_GET['currPage'];
$Limit = $_GET['Limit'];


$offset = ($currPage-1)*$Limit;

if($queUsu==null or $queUsu==" " or $queUsu=="" or $queUsu==-111){
	$queUsu="";
	
	$limitString = ' LIMIT '.$offset.','.$Limit;
	$all=true;
}
else
{
	$limitString = '';
	$all = false;
	

}

//echo $queUsu;

echo  '<table class="table table-mod" id="TablaProbe" style="height:100px; width:600px; overflow-y:hidden; table-layout: fixed; border: 1px SOLID #CACACA;">';
echo '<thead>
<tr>
    <th style="width:200px;text-align:center" lang="en">Users</th>
    <th style="width:450px;text-align:center" lang="en">Status</th>
	<th style="width:190px;text-align:center" lang="en">Tools</th>
</tr>
</thead>';
echo '<tbody>';





if($All2 == 1)
{
	$query = $con->prepare('select * from (SELECT distinct(IdUs) as idus FROM doctorslinkusers WHERE IdPin IS NULL and (IdMED=? or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= ?))) and Estado IN (2,null)
	UNION
	SELECT IdUs as idus FROM doctorslinkusers WHERE IdPin IS NOT NULL and (Idmed=? or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor=?))) and Estado IN (2,null) 
	UNION
	select IdPac as idus from doctorslinkdoctors where idmed2= ? and estado=2
	)AS idusu '.$limitString);
	
	
	//$query = $con->prepare('select DISTINCT(IdUs) FROM doctorslinkusers WHERE IdMED = ? AND IdPIN IS NULL');
	                       
	
$query->bindValue(1, $IdMed, PDO::PARAM_INT);
$query->bindValue(2, $IdMed, PDO::PARAM_INT);
$query->bindValue(3, $IdMed, PDO::PARAM_INT);
$query->bindValue(4, $IdMed, PDO::PARAM_INT);
$query->bindValue(5, $IdMed, PDO::PARAM_INT);
//$query->bindValue(6, $limitString, PDO::PARAM_STR);
}
else
{
	$query = $con->prepare('select patientid as idus from probe where doctorID=?');
	$query->bindValue(1, $IdMed, PDO::PARAM_INT);
}


$result = $query->execute();
$count=0;
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
	if($all==true)
	{
		$id = $row['idus'];
		$query2 = $con->prepare("SELECT * FROM usuarios WHERE Identif = ? and (Surname like ? or IdUsFIXEDNAME like ?)");
		$query2->bindValue(1, $id, PDO::PARAM_INT);
		$query2->bindValue(2, '%'.$queUsu.'%', PDO::PARAM_STR);
		$query2->bindValue(3, '%'.$queUsu.'%', PDO::PARAM_STR);
		
		//echo $id;
		$resultUSU = $query2->execute();
		$rowUSU = $query2->fetch(PDO::FETCH_ASSOC);
		if ($rowUSU['Surname']!='')
		{
		
					$current_encoding = mb_detect_encoding($rowUSU['Name'], 'auto');
					$show_text = iconv($current_encoding, 'ISO-8859-1', $rowUSU['Name']);

					$current_encoding = mb_detect_encoding($rowUSU['Surname'], 'auto');
					$show_text2 = iconv($current_encoding, 'ISO-8859-1', $rowUSU['Surname']);
					
			//echo 'here'; 
			$name = $show_text.' '.$show_text2;
			echo createRow($rowUSU['Identif'],$name,$rowUSU['email'],1);
		}
	}
	else
	{
		
			$id = $row['idus'];
			$query2 = $con->prepare("SELECT * FROM usuarios WHERE Identif = ? and (Surname like ? or IdUsFIXEDNAME like ?)");
			$query2->bindValue(1, $id, PDO::PARAM_INT);
			$query2->bindValue(2, '%'.$queUsu.'%', PDO::PARAM_STR);
			$query2->bindValue(3, '%'.$queUsu.'%', PDO::PARAM_STR);
			
			//echo $query;
			$resultUSU = $query2->execute();
			$rowUSU = $query2->fetch(PDO::FETCH_ASSOC);
			if ($rowUSU['Surname']!='')
			{
				if($count >= $offset && $count <$offset+$Limit)
				{	
					//echo 'here'; 
					$current_encoding = mb_detect_encoding($rowUSU['Name'], 'auto');
					$show_text = iconv($current_encoding, 'ISO-8859-1', $rowUSU['Name']);

					$current_encoding = mb_detect_encoding($rowUSU['Surname'], 'auto');
					$show_text2 = iconv($current_encoding, 'ISO-8859-1', $rowUSU['Surname']);
			
					$name = $show_text.' '.$show_text2;
					echo createRow($rowUSU['Identif'],$name,$rowUSU['email'],1);
				}		
				$count++;
				
			}
			
			
		
	
	}
	
}











//*********************COMMENTED CODE BELOW IS TAKEN FROM PATIENTS.PHP**********************************************
/*
//$ArrayPacientes = new SplFixedArray(99999);
$ArrayPacientes = array();
$numeral=0;
$sql_que="select Id from tipopin where Agrup=9";
$res=mysql_query($sql_que);
	
$privatetypes=array();
$num1=0;
while($rowpr=mysql_fetch_assoc($res)){
	$privatetypes[$num1]=$rowpr['Id'];
	$num1++;
  }

	
	
	$resultPRE = mysql_query("SELECT distinct(IdUs) FROM doctorslinkusers WHERE IdPin IS NULL and (Idmed='$IdMed' or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$IdMed'))) and Estado IN (2,null) LIMIT 1000");
	while ($rowPRE = mysql_fetch_array($resultPRE)) {

		$idEncontrado = $rowPRE['IdUs'];
		$resultUSU = mysql_query("SELECT * FROM usuarios WHERE Identif = '$idEncontrado' and (Surname like '%$queUsu%' or name like '%$queUsu%' or IdUsFIXEDNAME like '%$queUsu%')");
		$rowUSU = mysql_fetch_array($resultUSU);
		if ($rowUSU['Surname']!='')
		{	
			$name = $rowUSU['Name'].' '.$rowUSU['Surname'];
			echo createRow($rowUSU['Identif'],$name,$rowUSU['email'],1);

		 }
	}
  


//Retrive all patients from doctorslinkusers where Idpin is not null
	$resultPRE = mysql_query("SELECT IdUs, count(IdPin) FROM doctorslinkusers WHERE IdPin IS NOT NULL and (Idmed='$IdMed' or IdMED IN (Select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor='$IdMed'))) and Estado IN (2,null) GROUP BY IdUs LIMIT 10");
	while ($rowPRE = mysql_fetch_array($resultPRE)) {

		$idEncontrado = $rowPRE['IdUs'];
		$countactualPIN=$rowPRE['count(IdPin)'];
		$resultUSU = mysql_query("SELECT * FROM usuarios WHERE Identif = '$idEncontrado' and (Surname like '%$queUsu%' or name like '%$queUsu%' or IdUsFIXEDNAME like '%$queUsu%')");
		$rowUSU = mysql_fetch_array($resultUSU);
		if ($rowUSU['Surname']!='')
			{
				$name = $rowUSU['Name'].' '.$rowUSU['Surname'];
				echo createRow($rowUSU['Identif'],$name,$rowUSU['email'],2);
			}
	}
  

	$resultPRE=mysql_query(" select distinct(IdPac) from doctorslinkdoctors where idmed2= '$IdMed' and estado=2 LIMIT 10");
	while ($rowPRE = mysql_fetch_array($resultPRE))
	{
	
			$idEncontrado = $rowPRE['IdPac'];
			$resultUSU = mysql_query("SELECT * FROM usuarios WHERE Identif = '$idEncontrado' AND Surname like '%$queUsu%' ");
			$rowUSU = mysql_fetch_array($resultUSU);
			
			if($idEncontrado!=null){
				
				if (!in_array($idEncontrado, $ArrayPacientes))
				{
					$name = $rowUSU['Name'].' '.$rowUSU['Surname'];
					echo createRow($rowUSU['Identif'],$name,$rowUSU['email'],3);
					
				}
			}
	}

/*
//Ability to show all the patients. We have to filter out the rest of the patient
if($Group==0)
{

	$sql_query = mysql_query("SELECT * FROM usuarios where Surname like '%$queUsu%' or name like '%$queUsu%' or IdUsFIXEDNAME like '%$queUsu%' LIMIT 10");
	while ($rowUSU = mysql_fetch_array($sql_query))
	{
		$idEncontrado =$rowUSU['Identif'];
		if($idEncontrado!=null)
		{
			if (!in_array($idEncontrado, $ArrayPacientes))
			{
				$name = $rowUSU['Name'].' '.$rowUSU['Surname'];
				echo createRow($rowUSU['Identif'],$name,$rowUSU['email'],4);
						
			}
		}
	}
}

*/

echo '</tbody></table>';    
    

function createRow($PatientId,$name,$email,$querynum)
{
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
	if($PatientId){
	
	}
	else
	{
		echo $querynum;
		return;
	}
	
	$lastcolumn="";
	//Calculations for middle column
	$query = $con->prepare("select * from probe where patientID=? and doctorID = ?"); 
	$query->bindValue(1, $PatientId, PDO::PARAM_INT);
	$query->bindValue(2, $_SESSION['MEDID'], PDO::PARAM_INT);
	$result = $query->execute();
	
	$row = $query->fetch(PDO::FETCH_ASSOC);
	$num1 = $query->rowCount();
	
	if($num1==0)
	{
		$middlecolumn = '<span style="color:#BDBDBD;" lang="en">No Probes for this user</span>';
		$lastcolumn = '<div id="'.$PatientId.'"  class="CreateProbe" style="float:left; text-align:center; margin-left:10px; margin-top:0px; " class=""><a href="javascript:void(0)" class="btn" title="Schedule Probe" style="text-align:center;  width:100px; font-size:10px; font-weight:normal;" lang="en"><i class="icon-plus-sign icon-2x"></i>Create Probe</a></div>';
	}
	else if($num1==1)
	{
	
		if($row['doctorPermission']==0)
		{
			$lastcolumn = '<div id="'.$row['probeID'].'"  class="RestartProbe" style="float:left; text-align:center; margin-left:10px; margin-top:0px; " class=""><a href="javascript:void(0)" class="btn" title="Restart Probe" style="text-align:center;  width:100px; font-size:10px; font-weight:normal;" lang="en"><i class="icon-rotate-right icon-2x"></i>Restart Probe</a></div>';
		}
		else if($row['patientPermission']==0)
		{
			$lastcolumn = '<div id="'.$row['probeID'].'"  class="CannotProbe" style="float:left; text-align:center; margin-left:55px; margin-top:0px; " class=""><a href="javascript:void(0)" class="btn" title="Patient Does not want to receive Probes" style="text-align:center;color:red;  font-size:15px; font-weight:normal;width:7px"><i class="icon-ban-circle"></i></a></div>';
		}
		else
		{
		
			$lastcolumn = '<div id="'.$row['probeID'].'"  class="SendMessage" style="float:left; text-align:center; margin-left:10px; margin-top:0px; " class=""><a href="javascript:void(0)" class="btn" title="Send Probe immediately" style="text-align:center;  font-size:15px; font-weight:normal;width:7px"><i class="icon-bell"></i></a></div>';
			$lastcolumn .= '<div id="'.$row['probeID'].'"  class="EditProbe" style="float:left; text-align:center; margin-left:10px; margin-top:0px; " class=""><a href="javascript:void(0)" class="btn" title="Edit Probe" style="text-align:center;  font-size:15px; font-weight:normal;width:7px"><i class="icon-edit"></i></a></div>';
			$lastcolumn .= '<div id="'.$row['probeID'].'" class="RevokeProbe" style="float:left; text-align:center; margin-left:-6px; margin-top:0px; " class=""><a href="javascript:void(0)" class="btn" title="Revoke Probe" style="text-align:center;  color:red; font-size:15px;margin-left:15px; font-weight:normal;">x</a></div>';
	
		}
		
		$probeMethod="";
		//Probe Method Icon
		if($row['emailRequest']==1)
		{
			$probeMethod='<i class="icon-envelope icon-2x" style="color:#61a4f0" title="Probed via Email"></i>';
		}
		else if($row['phoneRequest']==1)
		{
			$probeMethod='<i class="icon-phone icon-2x" style="color:green" title="Probed via Phone Call"></i>';
		}
		else if($row['smsRequest']==1)
		{
			$probeMethod='<i class="icon-comment icon-2x" style="color:gray" title="Probed via SMS"></i>';
		}
		
		
		
		
		//Calculate number of days since probe started
		$query = $con->prepare("select datediff(now(),creationDate) as days from probe where probeID=?");
		$query->bindValue(1, $row['probeID'], PDO::PARAM_INT);
		$res = $query->execute();
		
		$days_diff = $query->fetch(PDO::FETCH_ASSOC);
		$days = $days_diff['days'];
		
		
		//Calculate size of bubbles
		//$query = "select * FROM proberesponse where probeID=".$row['probeID']." order by responseTime desc";
		$query = $con->prepare("select result as response from sentprobelog where probeID=? and result>0 and result<6 order by requestTime desc");
		$query->bindValue(1, $row['probeID'], PDO::PARAM_INT);
		$res = $query->execute();
		
		$num = $query->rowCount();
		
		//$bubbles = array("","","","","","");
		//$bubbles = array(-1,-1,-1,-1,-1,-1);
		$bubbles = array(0,0,0,0,0,0);
		
		if($num==1)
		{
//				mysql_data_seek($res,0);
				$userResponses = $query->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_ABS, 0);
				$latest = $userResponses['response'];
				
				$old = $latest;
				
				$bubbles[5] = $latest;
		
		}
		else if($num==2)
		{
//			mysql_data_seek($res,0);
			$userResponses = $query->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_ABS, 0);
			$latest = $userResponses['response'];
		
//			mysql_data_seek($res,1);
			$userResponses = $query->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_ABS, 1);
			$old = $userResponses['response'];
			
			$bubbles[5] = $latest;
			$bubbles[4] = $old;
		}
		else if($num<6)
		{
			
			$i=5;
			while($userResponses = $query->fetch(PDO::FETCH_ASSOC))
			{
				$bubbles[$i]=$userResponses['response'];
				$i--;
			}
			
			$latest = $bubbles[5] ;
			$old = $bubbles[4] ;
			
		}
		else
		{
		
			$numCount = array(0,0,0,0,0,0);
			$cnt=$num;
			$i=0;
			$n=5;
			while($cnt>0)
			{
				$numCount[$n-$i]++;
				$i=($i+1)%($n+1);
				$cnt--;
			}
			
			while($n>=0)
			{
				$sum = 0;
				for($j=0;$j<$numCount[$n];$j++)
				{
					$userResponses = $query->fetch(PDO::FETCH_ASSOC);
					$sum = $sum + $userResponses['response'];
				}
				$bubbles[$n] = intval($sum / $numCount[$n]);
				//$bubbles[$n] = $numCount[$n];
				$n--;
			}
			
			

				
			/*
			if($num%6==0)
			{
				$first_loop_interval = intval($num/6);
				$first_loop_limit = 6;
			
				$second_loop_interval = 0;
				$second_loop_limit = 0;
			}
			else if($num%7==0)
			{
				$first_loop_interval = intval($num/6);
				$first_loop_limit = 5;
			
				$second_loop_interval = $num - (5*$first_loop_interval);
				$second_loop_limit = 1;
			}
			else if($num%8==0)
			{
				$first_loop_interval = intval($num/6);
				$first_loop_limit = 5;
			
				$second_loop_interval = $num - (5*$first_loop_interval);
				$second_loop_limit = 1;
			}
			else if($num%9==0)
			{
				$first_loop_interval = intval(ceil($num/6));
				$first_loop_limit = 3;
			
				$second_loop_interval = intval($num/6);
				$second_loop_limit = 3;
			}
			else if($num%10==0)
			{
				$first_loop_interval = intval(ceil($num/6));
				$first_loop_limit = 4;
			
				$second_loop_interval = intval($num/6);
				$second_loop_limit = 2;
			}
			else if($num%11==0)
			{
				$first_loop_interval = intval(ceil($num/6));
				$first_loop_limit = 5;
			
				$second_loop_interval = intval($num/6);
				$second_loop_limit = 1;
			}
			
			
			
						
			
			//First Loop
			for($i=0;$i<$first_loop_limit;$i++)
			{
				$sum = 0;
				for($j=0;$j<$first_loop_interval;$j++)
				{
					$userResponses = mysql_fetch_array($res);
					$sum = $sum + $userResponses['response'];
				}
				$avg = intval($sum/$first_loop_interval);
				$index = 5-$i;
				$bubbles[$index] = $avg;
				
			}
			
			//Second Loop
			for($j=0;$j<$second_loop_limit;$j++)
			{
				$sum = 0;
				for($k=0;$k<$second_loop_interval;$k++)
				{
					$userResponses = mysql_fetch_array($res);
					$sum = $sum + $userResponses['response'];
				}
				$avg = intval($sum/$second_loop_interval);
				$index = 5-$i;
				$bubbles[$index] = $avg;
				
				$i++;
				
			
			}
			
			
			*/
			
//			mysql_data_seek($res,0);
			$userResponses = $query->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_ABS, 0);
			$latest = $userResponses['response'];
		
//			mysql_data_seek($res,1);
			$userResponses = $query->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_ABS, 1);
			$old = $userResponses['response'];
			
		}
		
		
		if($num>0)
		{
		
				$latest_response_text = "";
				
				$path = 'images/ProbeIcons/';
				switch($latest)
				{
					case 1://$latest_response_text = "Very Bad";
					       $latest_response_text=" <img src='".$path."f1.png' title='Very Bad' style='height:30px'></img>";
					
							break;
					case 2://$latest_response_text = "Bad";
						   $latest_response_text=" <img src='".$path."f2.png' title='Bad' style='height:30px'></img>"; 
							break;
					case 3://$latest_response_text = "Normal";
						   $latest_response_text=" <img src='".$path."f3.png' title='Normal' style='height:30px'></img>";
							break;
					case 4://$latest_response_text = "Good";
						   $latest_response_text=" <img src='".$path."f4.png' title='Good' style='height:30px'></img>";	
							break;
					case 5://$latest_response_text = "Very Good";
					       $latest_response_text=" <img src='".$path."f5.png' title='Very Good' style='height:30px'></img>";
							break;
				}
				
				$improvement="";
				if($latest > $old)
				{
					//there is improvement in health
					
					//$improvement = '<span style="color:#01DF01;" title="There is an improvement in patients health since the last probe"><i class="icon-arrow-up icon-2x"></i></span>';
					$improvement = '<span style="color:#01DF01;" title="There is an improvement in patients health since the last probe">'.getIcon(6).'</i></span>';
				}
				else if($latest == $old)
				{
					//no improvement
					
					$improvement = '<span style="color:#BDBDBD;" title="There is no improvement in patients health since the last probe">'.getIcon(8).'</span>';
				}
				else
				{
					
					$improvement = '<span style="color:#FF0000;" title="There is a decline in patients health since the last probe">'.getIcon(7).'</span>';
				}
				
				
				$daysText = '<span style="color:#BDBDBD;" ><a class="neutral" style="color:#BDBDBD"><i class="icon-envelope-alt icon-2x"></i></a>  '.$days.' days</span>';
				
				$middlecolumn = $probeMethod.' '.$latest_response_text."    " . $improvement. "     " .$daysText. "  " ;
				for($i=0;$i<6;$i++)
				{
					$middlecolumn = $middlecolumn." ".getIcon($bubbles[$i]);
					//$middlecolumn = $middlecolumn." ".$bubbles[$i];
				}
		}
		else
		{
			$middlecolumn = "<span lang='en'>No Data Recorded yet</span>";
		}
	}
	else
	{
		$middlecolumn =  '<span style="color:#BDBDBD;">Error.Contact Administrator</span>';
	}
	
	
	
	
	



	return '<tr  class="CFILAPROBEROW">
            <th id="'.$PatientId.'" class="CFILAPAT" style="height:20px;"><span style="font-size:16px; color:#54bc00; font-weight:normal;"><a  class="neutral" href="javascript:void(0)">'.SCaps($name).'</a>
                <div style="width:100%; margin-top:-8px;"></div>
                <a class="neutral" href="javascript:void(0)"><span style="font-size:10px; color:grey; font-weight:normal;">'.$email.'</span></a>
                <span style="font-size:10px; color:#22aeff; font-weight:normal; margin-left:10px;">('.$PatientId.')</span>
            </th>
            <th style="text-align:center;">
				<div id="'.$PatientId.'">'.
					$middlecolumn		
				.'</div>
            </th>
            <th style=" text-align:center;">
                <div style="float:left; margin-left:20px;">
                '.$lastcolumn.'    
                </div>
            </th>
          </tr>';


}	
	
function SCaps ($cadena)
{
return strtoupper(substr($cadena,0,1)).substr($cadena,1);
}    


function getIcon($num)
{
$path = 'images/ProbeIcons/';

	switch ($num)
	{
		case -1: //no data
				 return "";
				 break;
		case 0 : //health 0 icon
				return "<img class='probePopUp' src=".$path."s0.png style='height:30px;cursor:pointer'></img>";
				 break;
		case 1 : //health 1 icon
				return "<img class='probePopUp' src=".$path."s1.png style='height:30px;cursor:pointer'></img>";
				 break;
		case 2 : //health 2 icon
			    return "<img class='probePopUp' src=".$path."s2.png style='height:30px;cursor:pointer'></img>";
				 break;
		case 3 : //health 3 icon
				return "<img class='probePopUp' src=".$path."s3.png style='height:30px;cursor:pointer'></img>";
				 break;
		case 4 : //health 4 icon
				return "<img class='probePopUp' src=".$path."s4.png style='height:30px;cursor:pointer'></img>";
				 break;
		case 5 : //health 5 icon
				return "<img class='probePopUp' src=".$path."s5.png style='height:30px;cursor:pointer'></img>";
				 break;
		case 6 : //up arrow
				return "<img src=".$path."up.png style='height:30px'></img>";	
				break;
		case 7 : //down arrow
				 return "<img src=".$path."down.png style='height:30px'></img>";
				 break;
		case 8 : //equal sign
				//return "<img src=".$path."equal.jpg style='height:30px'></img>";
				return "<img src=".$path."equal.png style='height:30px'></img>";
				 break;
	
	}
	


}

	
?>