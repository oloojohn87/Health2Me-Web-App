<?php
 
 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 $userid = $_GET['idusu'];
 
 
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$result = $con->prepare("SELECT idpatient,idmedication,name,drugcode,dossage,numberday,DATE_FORMAT(date(datestart),'%m-%d-%Y') as datestart,DATE_FORMAT(date(datestop),'%m-%d-%Y') as datestop , del from medications where idpatient=?"); 
$result->bindValue(1, $userid, PDO::PARAM_INT);
$result->execute();

 
echo  '<table class="table table-mod" id="Medication" style="height:100px; width:600px; overflow-y:hidden; table-layout: fixed; border: 1px SOLID #CACACA;">';
echo '<thead><tr id="FILA" class="CFILAMODAL"><th style="text-align: center;">Drug Name</th><th style="text-align: center;">Drug Code</th><th style="text-align: center;">Dossage</th><th style="text-align: center;">Number per Day</th><th style="text-align: center;">Day Start</th><th style="text-align: center;">Day Stop</th></tr></thead>'; 
echo '<tbody>'; 
 
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

	echo '<tr class="CFILA_medications" id="'.$row['idmedication'].'"> ';

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
	if(strpos($row['datestart'],'0000') != false)
	{
		$datestart='';
	}
	else
	{
		$datestart=$row['datestart'];
	}
	
	
	$datestop='';
	if(strpos($row['datestop'],'0000') != false)
	{
		$datestop='';
	}
	else
	{
		$datestop=$row['datestop'];
	}
	
	
	
	
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; ">'.$stk_start.$row['name'].$stk_end.'</td>';
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; ">'.$stk_start.$row['drugcode'].$stk_end.'</td>';
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; ">'.$stk_start.$row['dossage'].$stk_end.'</td>';
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; ">'.$stk_start.$row['numberday'].$stk_end.'</td>';
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; ">'.$stk_start.$datestart.$stk_end.'</td>';
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; ">'.$stk_start.$datestop.$stk_end.'</td>';
	echo '</tr>';
 
	
 
}
 
 
 

echo '</tbody></table>';    
     
 ?>