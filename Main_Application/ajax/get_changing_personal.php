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

$result = $con->prepare("SELECT idpatient,id,height,weight,DATE_FORMAT(date(date_rec),'%m-%d-%Y') as daterec,hbp,lbp,del from changing_personal_history where idpatient=?"); 
$result->bindValue(1, $userid, PDO::PARAM_INT);
$result->execute();
  
 
echo  '<table class="table table-mod" id="CP" style="height:100px; width:600px; overflow-y:hidden; table-layout: fixed; border: 1px SOLID #CACACA;">';
echo '<thead><tr id="FILA" class="CFILAMODAL"><th style="text-align: center;">Date Recorded</th><th style="text-align: center;">Height</th><th style="text-align: center;">Weight</th><th style="text-align: center;">High BP</th><th style="text-align: center;">Low BP</th></tr></thead>'; 
echo '<tbody>'; 
 
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	echo '<tr class="CFILA_CP" id="'.$row['id'].'"> ';

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
	
	
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; ">'.$stk_start.(htmlspecialchars($row['daterec'])).$stk_end.'</td>';
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; ">'.$stk_start.(htmlspecialchars($row['height'])).$stk_end.'</td>';
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; ">'.$stk_start.(htmlspecialchars($row['weight'])).$stk_end.'</td>';
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; ">'.$stk_start.(htmlspecialchars($row['hbp'])).$stk_end.'</td>';
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; ">'.$stk_start.(htmlspecialchars($row['lbp'])).$stk_end.'</td>';
	
	echo '</tr>';
 
 
}
 
 
 

echo '</tbody></table>';    
     
 ?>