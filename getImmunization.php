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

$result=$con->prepare("select idpatient,idimmunization,name,DATE_FORMAT(date(date),'%m-%d-%Y') as startdate,age,reaction,del from immunizations where idpatient=?");
$result->bindValue(1, $userid, PDO::PARAM_INT);
$result->execute();

 
echo  '<table class="table table-mod" id="Immunization" style="height:100px; width:600px; overflow-y:hidden; table-layout: fixed; border: 1px SOLID #CACACA;">';
echo '<thead><tr id="FILA" class="CFILAMODAL"><th style="text-align: center;">Name</th><th style="text-align: center;">Age</th><th style="text-align: center;">Date </th><th style="text-align: center;">Reaction</th></tr></thead>'; 
echo '<tbody>'; 
 
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
	echo '<tr class="CFILA_immunizations" id="'.(htmlspecialchars($row['idimmunization'])).'"> ';

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
	
	
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; ">'.$stk_start.(htmlspecialchars($row['name'])).$stk_end.'</td>';
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; ">'.$stk_start.(htmlspecialchars($row['age'])).$stk_end.'</td>';
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; ">'.$stk_start.(htmlspecialchars($row['startdate'])).$stk_end.'</td>';
	echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; ">'.$stk_start.(htmlspecialchars($row['reaction'])).$stk_end.'</td>';
	
	echo '</tr>';
 
	
 
}
 
 
 

echo '</tbody></table>';    
     
 ?>