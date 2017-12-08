<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 
 $userid = $_GET['idusu'];
 
 
// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			


$query = $con->prepare("SELECT IdDX,Name,ICDCode,DATE_FORMAT(date(DateStart),'%m-%d-%Y') as DateStart , DATE_FORMAT(date(DateStop),'%m-%d-%Y') as DateStop,del FROM pastdx WHERE idpatient=?");
$query->bindValue(1, $userid, PDO::PARAM_INT);


$result = $query->execute();
 
echo  '<table class="table table-mod" id="PastDX" style="height:100px; width:600px; overflow-y:hidden; table-layout: fixed; border: 1px SOLID #CACACA;">';
echo '<thead><tr id="FILA" class="CFILAMODAL"><th style="text-align: center;">DX Name</th><th style="text-align: center;">ICD Code</th><th style="text-align: center;">Date Start</th><th style="text-align: center;">Date Stop</th></tr></thead>'; 
echo '<tbody>'; 
 
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

	
	
	echo '<tr class="CFILA_pastdx" id="'.$row['IdDX'].'"> ';
	
	
	if($row['del'])
	{
		$stk_start = '<strike>';
		$stk_end = '</strike>';
	}
	else
	{
		$stk_start = '';
		$stk_end = '';
	}
	
	$datestart='';
	if(strpos($row['DateStart'],'0000') != false)
	{
		$datestart='';
	}
	else
	{
		$datestart=$row['DateStart'];
	}
	
	
	$datestop='';
	if(strpos($row['DateStop'],'0000') != false)
	{
		$datestop='';
	}
	else
	{
		$datestop=$row['DateStop'];
	}
	

	
	
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; ">'.$stk_start.(htmlspecialchars($row['Name'])).$stk_end.'</td>';
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; ">'.$stk_start.(htmlspecialchars($row['ICDCode'])).$stk_end.'</td>';
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; ">'.$stk_start.$datestart.$stk_end.'</td>';
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; ">'.$stk_start.$datestop.$stk_end.'</td>';
	echo '</tr>';
 
}
 
 
 

echo '</tbody></table>';    
     
 ?>