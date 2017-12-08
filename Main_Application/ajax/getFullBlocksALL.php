<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
 /*KYLE
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="usuarios"; // Table name

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

$queUsu = $_GET['Usuario'];
//$NReports = $_GET['NReports'];
//$queUsu = 32;


//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks ORDER BY FechaInput DESC");
$result = mysql_query("SELECT * FROM lifepin ORDER BY FechaInput DESC LIMIT 50");

echo  '<table style="height:200px; overflow-y:hidden; table-layout: fixed;"  class="table table-mod" id="TablaPac">';
echo '<thead><tr id="FILA" class="CFILA" ><th width="20" style="text-align: center;">Id</th><th style="text-align: center;">IdUsFIXED</th><th style="text-align: center;">IdUsFIXEDNAME</th><th style="text-align: center;">Date Rec</th><th style="text-align: center;">Channel</th><th style="text-align: center;">Origin Doc</th><th style="text-align: center;">Valid?</th><th style="text-align: center;">Next Action</th><th width="30" style="text-align: center;">Live</th></tr></thead>';
echo '<tbody>';

while ($row = mysql_fetch_array($result)) {

$ahora = strtotime("now");
$compara = strtotime($row['FechaInput']);
$compara = $compara + (8*60*60); //Corrección de diferencia horaria de 8 horas (8*60 minutos, 8*60*60 segundos)
$difer = $ahora-$compara; //Segundos
$difer = $difer/60; //Minutos
//$difer = $difer/60; //Horas

$difer = floor($difer);  // Se redondea al entero más cercano por debajo

$queBlink ='';
if ($difer<60)
{
$queBlink ='<img src="images/load/38c.gif" alt="" style="margin-left:10px;">';
}


echo '<tr class="CFILA" id="'.$row['IdPin'].'"><td width="20">'.$row['IdPin'].'</td>';
echo '<td>'.$row['IdUsFIXED'].'</td>';
echo '<td style="overflow:hidden;white-space:nowrap;">'.$row['IdUsFIXEDNAME'].'</td>';
echo '<td style="text-align: center; overflow:hidden;white-space:nowrap; width:120px;">'.substr($row['FechaInput'],0,10).$queBlink.'</td>';
switch ($row['CANAL'])
{
case null:	$contCanal = '<a href="#"><span class="label label-info" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">eMapLife&copy</span></a>';
			break;
case '0':	$contCanal = '<a href="#"><span class="label label-info" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">eMapLife&copy iOs</span></a>';
			break;
case '1':	$contCanal = '<a href="#"><span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">SecMedMail&copy</span></a>';
			break;
default:	$contCanal = '<a href="#"><span class="label label-info" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">N/A</span></a>';
			break;
			
}
echo '<td style="text-align: center;">'.$contCanal.'</td>';
echo '<td style="overflow:hidden;white-space:nowrap; width:120px;">'.$row['IdMEDEmail'].'</td>';
switch ($row['ValidationStatus'])
{
case null:	$contValid = '<span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">OK</span>';
			break;
case '0':	$contValid = '<span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">OK</span>';
			break;
case '1':	$contValid = '<span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">No user id</span>';
			break;
case '2':	$contValid = '<span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:red;">No Dr. id</span>';
			break;
case '3':	$contValid = '<span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:red;">need id valid.</span>';
			break;
case '4':	$contValid = '<span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:orange;">need user ack. </span>';
			break;
case '8':	$contValid = '<span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">SECURED</span>';
			break;
case '99':	$contValid = '<span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">Not parsed</span>';
			break;
default:	$contValid = '<span class="label label-info" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">N/A</span>';
			break;
}
echo '<td style="text-align: center;">'.$contValid.'</td>';
echo '<td style="overflow:hidden;white-space:nowrap; width:120px;">'.$row['NextAction'].'</td>';
echo '<td width="30">'.$difer.'</td>';

}

echo '</tbody></table>';    
    

?>