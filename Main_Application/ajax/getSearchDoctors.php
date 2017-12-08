<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$queUsu = $_GET['Doctor'];
$UserDOB = $_GET['DrEmail'];
$NReports = $_GET['NReports'];
//$queUsu = 32;


$result = $con->prepare("SELECT * FROM doctors WHERE IdMEDFIXEDNAME like ? AND IdMEDEmail like ? LIMIT 15");
$result->bindValue(1, '%'.$queUsu.'%', PDO::PARAM_STR);
$result->bindValue(2, '%'.$UserDOB.'%', PDO::PARAM_STR);
$result->execute();


if ($NReports == '3')
{
	echo  '<table class="table table-mod" id="TablaPacMODAL" style="height:100px; width:600px; overflow-y:hidden; table-layout: fixed; border: 1px SOLID #CACACA;">';
	echo '<thead><tr id="FILA" class="CFILADoctor"><th style="width:80px;">First Name</th><th style="width:80px;">Last Name</th><th>Fixed Id</th></tr></thead>';
}
else
{
	echo  '<table class="table table-mod" id="TablaPacMODAL" style="height:100px; width:600px; overflow-y:hidden; table-layout: fixed; border: 1px SOLID #CACACA;">';
	echo '<thead><tr id="FILA" class="CFILADoctor"><th style="width:80px;">First Name</th><th style="width:80px;">Last Name</th><th>Fixed Id</th><th>User Id</th></tr></thead>';
}
echo '<tbody>';

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

echo '<tr class="CFILADoctor" id="'.$row['id'].'" id2="'.$row['phone'].'" style="height:10px; line-height:0;">';
echo '<td>'.$row['Name'].'</td>';
echo '<td>'.$row['Surname'].'</td>';
echo '<td>'.$row['IdMEDEmail'].'</td>';
if ($NReports !='3') echo '<td>'.$row['IdUsFIXEDNAME'].'</td>';

//Borrar esto para acelerar el rendimiento
$UsuFila = $row['id'];
//////$result22 = mysql_query("SELECT * FROM lifepin WHERE IdUsu='$UsuFila' ");
//////$count=mysql_num_rows($result22);
$count = 0;
//Borrar esto para acelerar el rendimiento

//////echo '<td style="overflow:hidden; white-space:nowrap; width:20px; text-align: center;">'.$count.'</td></tr>';

}

echo '</tbody></table>';    
    

?>