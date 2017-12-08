<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

/* 
$dbhost="54.225.226.163"; // Host name
$dbname="monimed"; // Database name
$dbuser="monimed"; // Mysql username
$dbpass="ardiLLA98"; // Mysql password*/

$tbl_name="usuarios"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

//$flag="true";

$result = $con->prepare("SELECT * FROM vitalidad WHERE IdProg=1");
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

$ahora = strtotime("now");
$compara = strtotime($row['Fecha']);
$compara = $compara + (8*60*60); //Corrección de diferencia horaria de 8 horas (8*60 minutos, 8*60*60 segundos)
$difer = $ahora-$compara; //Segundos
$difer = $difer/60; //Minutos
//$difer = $difer/60; //Horas

$difer = floor($difer);  // Se redondea al entero más cercano por debajo

$queBlink ='';
if ($difer<2)
{
echo '<div style="margin-top:10px; position: relative; float:left;  width:320px; height:30px; " >';
$queBlink ='<img src="images/ledgreen.png" alt=""  style="height:40px; width:40px;"  id="Bip" >';
echo $queBlink.' SecMedMail&copy RECEIVER ';
echo '</div>';

}
else{

echo '<div style="margin-top:10px; position: relative; float:left;  width:320px; height:30px; " >';
$queBlink ='<img src="images/ledredANIM.gif" alt="" style="width:40px; height:40px;" id="Bip" >';
echo $queBlink.' SecMedMail&copy RECEIVER DOWN ';
echo '</div>';

}


}



echo '<div style="margin-top:10px; position: relative; float:left; width:320px; height:30px;" >';
$queBlink ='<img src="images/ledgreen.png" style="height:40px; width:40px;" alt=""  id="Bip" >';
echo $queBlink.' eMapLife&copy Android App ';echo '</br>';
echo '</div>';

echo '<div style="margin-top:10px; position: relative; float:left; width:320px; height:30px;" >';
$queBlink ='<img src="images/ledgreen.png"  style="height:40px; width:40px;"  alt="" id="Bip">';
echo $queBlink.' eMapLife&copy iOs App ';
echo '</div>';



$result = $con->prepare("SELECT * FROM vitalidad WHERE IdProg=2");
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

$ahora = strtotime("now");
$compara = strtotime($row['Fecha']);
$compara = $compara + (8*60*60); //Corrección de diferencia horaria de 8 horas (8*60 minutos, 8*60*60 segundos)
$difer = $ahora-$compara; //Segundos
$difer = $difer/60; //Minutos
//$difer = $difer/60; //Horas

$difer = floor($difer);  // Se redondea al entero más cercano por debajo

$queBlink ='';
if ($difer<2)
{

echo '<div style="margin-top:10px; position: relative; float:left;  width:320px; height:30px; " >';
$queBlink ='<img src="images/ledgreen.png" alt=""  style="height:40px; width:40px;"  id="Bip" >';
echo $queBlink.' SecMedMail&copy PARSER ';
echo '</div>';

}
else{
	
$flag= "false" ;
echo '<div style="margin-top:10px; position: relative; float:left;  width:320px; height:30px; " >';
$queBlink ='<img src="images/ledredANIM.gif" alt="" style="width:40px; height:40px;" id="Bip" >';
echo $queBlink.' SecMedMail&copy PARSER DOWN ';
echo '</div>';
}

}

$result = $con->prepare("SELECT * FROM vitalidad WHERE IdProg=3");
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

$ahora = strtotime("now");
$compara = strtotime($row['Fecha']);
$compara = $compara + (8*60*60); //Corrección de diferencia horaria de 8 horas (8*60 minutos, 8*60*60 segundos)
$difer = $ahora-$compara; //Segundos
$difer = $difer/60; //Minutos
//$difer = $difer/60; //Horas

$difer = floor($difer);  // Se redondea al entero más cercano por debajo

$queBlink ='';
if ($difer<2)
{
echo '<div style="margin-top:10px; position: relative; float:left; width:320px; height:30px;" >';
$queBlink ='<img src="images/ledgreen.png"  style="height:40px; width:40px;"  alt="" id="Bip">';
echo $queBlink.' CloudChannel&copyRECEIVER ';
echo '</div>';
	
}else {
	
echo '<div style="margin-top:10px; position: relative; float:left; width:320px; height:30px;" >';
$queBlink ='<img src="images/ledredANIM.gif" alt="" style="height:40px; width:40px;"  id="Bip">';
echo $queBlink.' CloudChannel&copyRECEIVER ';
echo '</div>';

}}

$result = $con->prepare("SELECT * FROM vitalidad WHERE IdProg=4");
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

$ahora = strtotime("now");
$compara = strtotime($row['Fecha']);
$compara = $compara + (8*60*60); //Corrección de diferencia horaria de 8 horas (8*60 minutos, 8*60*60 segundos)
$difer = $ahora-$compara; //Segundos
$difer = $difer/60; //Minutos
//$difer = $difer/60; //Horas

$difer = floor($difer);  // Se redondea al entero más cercano por debajo

$queBlink ='';
if ($difer<2)
{	
echo '<div style="margin-top:10px; position: relative; float:left;  width:320px; height:30px; " >';
$queBlink ='<img src="images/ledgreen.png" alt=""  style="height:40px; width:40px;"  id="Bip" >';
echo $queBlink.' CloudChannel&copyPARSER ';
echo '</div>';
	
}else{
	
echo '<div style="margin-top:10px; position: relative; float:left;  width:320px; height:30px; " >';
$queBlink ='<img src="images/ledredANIM.gif" alt=""  style="height:40px; width:40px;"  id="Bip" >';
echo $queBlink.' CloudChannel&copyPARSER ';
echo '</div>';
} }
	


echo '<div style="margin-top:10px; position: relative; float:left; width:620px;  height:30px;" >';
echo '</div>';

echo '<div class="clear"></div>';





?>
