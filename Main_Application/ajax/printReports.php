<?php
error_reporting(E_ALL);
ini_set("display_errors", 0);
require('getFullUsersMEDCLASS.php');
require_once("displayExitClass.php");
session_start();

echo "<style type='text/css'>

@media all {
	.page-break	{ display: none; }
}

@media print {
	.page-break	{ display: block; page-break-before: always; }
}

div { page-break-inside:avoid; page-break-after:auto  }
thead { display:table-header-group }
tfoot { display:table-footer-group }

</style>";

require("environment_detailForLogin.php");
//error_reporting(E_ERROR | E_PARSE);
set_time_limit(60);

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 
// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		
  
  $idUsu = $_GET['IdUsu'];

$doc_id = $_SESSION['MEDID'];
$mem_id = $idUsu;

$checker = new checkPatientsClass();



$checker->setters($mem_id, $doc_id);
$checker->checker();

if($checker->status1 != 'true' && $checker->status2 != 'true' && $checker->status3 != 'true' && $checker->status4 != 'true'){
    $exit_display = new displayExitClass();
    $exit_display->displayFunction(5);
    die();
}elseif($checker->status5 == 'false'){

   $exit_display = new displayExitClass();
   $exit_display->displayFunction(-1);
   die();

}


echo '<img width="150px"src="images/health2meLOGO.png" style="float:left;" /><form style="float:right;">';
if(!isset($_GET['show_pins'])){
	echo "<a style='margin-right:20px;' href='printReports.php?IdUsu=".$_GET['IdUsu']."&show_pins=yes' >Add Reports</a>";
}else{
	echo "<a style='margin-right:20px;' href='printReports.php?IdUsu=".$_GET['IdUsu']."' >Hide Reports</a>";
}

echo '<input type="button" value=" Print this page " onclick="window.print();return false;" /></form>';

//$userid = 151;

$usuariosquery = $con->prepare("select sexo,name,surname,idusfixed,idusfixedname,location from usuarios where Identif=?");
$usuariosquery->bindValue(1, $idUsu, PDO::PARAM_INT);

$result1 = $usuariosquery->execute();
$row1 = $usuariosquery->fetch(PDO::FETCH_ASSOC);

$emrquery = $con->prepare("select DATE_FORMAT(dob,'%m-%d-%Y') as dob,address,address2,city,state,country,notes,fractures,surgeries,otherknown,obstetric,othermed,fatheralive,fathercod,fatheraod,fatherrd,motheralive,mothercod,motheraod,motherrd,siblingsrd,phone,insurance,bloodType,weight,weightType,height1,height2,heightType,zip from basicemrdata where idpatient=?");
$emrquery->bindValue(1, $idUsu, PDO::PARAM_INT);

$result2 = $emrquery->execute();
$row2 = $emrquery->fetch(PDO::FETCH_ASSOC);

$usuarioquery = $con->prepare("SELECT email,telefono FROM usuarios WHERE Identif=?");
$usuarioquery->bindValue(1, $idUsu, PDO::PARAM_INT);

$result3 = $usuarioquery->execute();
$row3 = $usuarioquery->fetch(PDO::FETCH_ASSOC);


$id=$idUsu;
$name = $row1['name'].' '.$row1['surname'];
$location = $row1['location'];
  
//echo $name."<p></p>";
switch($row1['sexo'])
{
	case 0 : $gender = 'Female';
			 break;
	case 1 : $gender = 'Male';
			 break;
	default: $gender = 'Male';
}


$dob = $row2['dob'];
$address = $row2['address'];
$address2 = $row2['address2'];
$city = $row2['city'];
$state = $row2['state'];
$country = $row2['country'];
$notes = $row2['notes'];
$fractures = $row2['fractures'];
$surgeries = $row2['surgeries'];
$otherknown = $row2['otherknown'];
$obstetric = $row2['obstetric'];
$othermed = $row2['othermed'];

$phone=$row3['telefono'];
$insurance=$row2['insurance'];
$email = $row3['email'];
$zip = $row2['zip'];

if($zip == ''){
$zip = 'Unspecified';
}

if($dob == ''){
$dob = 'Unspecified';
}

if($address == ''){
$address = 'Unspecified';
}

if($address2 == ''){
$address2 = 'Unspecified';
}

if($city == ''){
$city = 'Unspecified';
}

//if the country is not found from the basic emr table check data from the users table
if ($country == '') {
    $location_array = explode(",",$location);
    if (count($location_array) >1) {
        $state = $location_array[0];
        $country = $location_array[1];
    }  else {
        $country = $location;   
    }     
}    
//echo "Country : ".$country."<br>";
//echo "State   : ".$state."<p></p>";

// format blood type
$blood_type = $row2['bloodType'];
if($blood_type == null || $blood_type == 'none')
{
    $blood_type = 'Unspecified';
}
$blood_type = str_replace("pos", "+", $blood_type);
$blood_type = str_replace("neg", "-", $blood_type);

// format weight
$weight = $row2['weight'];
if($weight == null || $weight == 0)
{
    $weight = 'Unspecified';
}
else
{
    if($row2['weightType'] == 1)
    {
        $weight .= ' lbs';
    }
    else
    {
        $weight .= ' kg';
    }
}

// format height
$height = $row2['height1'];
if($height == null || $height == 0)
{
    $height = 'Unspecified';
}
else
{
    if($row2['heightType'] == 1)
    {
        $height .= ' ft ';
    }
    else
    {
        $height .= ' m ';
    }
    
    if($row2['height2'] == null || $row2['height2'] == 0)
    {
        $height = 'Unspecified';
    }
    else
    {
        $height .= $row2['height2'];
        if($row2['heightType'] == 1)
        {
            $height .= ' in';
        }
        else
        {
            $height .= ' cm';
        }
    }
}

if($state == ''){
$state = 'Unspecified';
}

if($country == ''){
$country == 'Unspecified';
}

if($blood_type == ''){
$blood_type = 'Unknown';
}

$tbl = '

<div><table cellspacing="0" cellpadding="0"  width="100%" style="margin-top:10px;">
    
	<!--<tr>
		<th width="100%" height="30" style="margin-top:10px;margin:20px 0;-webkit-margin-before: 0.5em;-webkit-margin-after: 0.5em;-webkit-margin-start: auto;-webkit-margin-end: auto;">  <span style="color:#3D93E0; font-size:20px;"><b>Basic Demographics : </b></span> </th>
	</tr>-->
	
	
	<tr>
        <td >   Name : <span style="color:#3D93E0;">'.$name.'</span>  </td>

    
	
    
        <td>    Date of Birth : <span style="color:#3D93E0;">'.$dob.'</span> </td>
       
    </tr>
	<tr>
		<td>    Gender : <span style="color:#3D93E0;">'.$gender.'</span>  </td>
	</tr>
';


	$tbl=$tbl.'<tr><td></br></td></tr><tr>
        <td >   Address1 : <span style="color:#3D93E0;">'.$address.'</span> </td>
    ';




	$tbl = $tbl.'
        <td >   Address2 : <span style="color:#3D93E0;">'.$address2.'</span> </td>
    </tr>';



	$tbl = $tbl.'<tr>
        <td >   City : <span style="color:#3D93E0;">'.$city.'</span> </td>
    ';

	$tbl=$tbl.'
        <td >   State : <span style="color:#3D93E0;">'.$state.'</span> </td>
    ';

	$tbl=$tbl.'
        <td >   Zip : <span style="color:#3D93E0;">'.$zip.'</span> </td>
    </tr>';
	

	$tbl = $tbl.'<tr>
        <td >   Country : <span style="color:#3D93E0;">'.$country.'</span> </td>
    </tr>';



	$tbl = $tbl.'<tr><td><br></td></tr><tr style="margin-top:10px;">
        <td >   Email : <span style="color:#3D93E0;">'.$email.'</span> </td>
    ';



	$tbl = $tbl.'
        <td >   Phone : <span style="color:#3D93E0;">'.$phone.'</span> </td>
    </tr>';



	$tbl = $tbl.'<tr>
        <td >   Blood Type : <span style="color:#3D93E0;">'.$blood_type.'</span> </td>
    </tr>';



	$tbl = $tbl.'<tr>
        <td >   Weight : <span style="color:#3D93E0;">'.$weight.'</span> </td>
    ';



	$tbl = $tbl.'
        <td >   Height : <span style="color:#3D93E0;">'.$height.'</span> </td>
    </tr>';


/*if($row['insurance'])
{
	$tbl = $tbl.'<tr>
		<td width ="10%"> </td>
        <td >   Insurance : <span style="color:#3D93E0;">'.$insurance.'</span> </td>
    </tr>';
}


if($row['notes'])
{
	$tbl = $tbl.'<tr>
		<td width ="10%"> </td>
        <td >   Notes : <span style="color:#3D93E0;">'.$notes.'</span> </td>
    </tr>';
}*/

$tbl = $tbl.'<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>';
	


	
	
	
	
	
$tbl = $tbl.'</table></div></br>';	

echo $tbl;

//echo $phone.'   '.$insurance;
//return;


switch($row2['fatheralive'])
{
	case 0: $fatheralive = 'No';
			break;
	case 1: $fatheralive = 'Yes';
			break;
	default : $fatheralive = 'No';
			break;
}

$fcod = $row2['fathercod'];
$faod = $row2['fatheraod'];
$frd = $row2['fatherrd'];

switch($row2['motheralive'])
{
	case 0: $motheralive = 'No';
			break;
	case 1: $motheralive = 'Yes';
			break;
	default : $motheralive = 'No';
			break;
}

$mcod = $row2['mothercod'];
$maod = $row2['motheraod'];
$mrd = $row2['motherrd'];

$siblingsrd = $row2['siblingsrd'];


//Generating HTML for PastDX
$query = $con->prepare("select dxname,dxcode,DATE_FORMAT(dxstart,'%m-%d-%Y') as datestart,DATE_FORMAT(dxend,'%m-%d-%Y') as dateend,notes from p_diagnostics where idpatient=? and deleted = 0");
$query->bindValue(1, $idUsu, PDO::PARAM_INT);

$result = $query->execute();
$i=1;
$table1 = '<div><table width="100%">
<tr>
		<th width="100%" height="30" style="margin-top:10px;border:1px solid #cacaca;margin:20px 0;-webkit-margin-before: 0.5em;-webkit-margin-after: 0.5em;-webkit-margin-start: auto;-webkit-margin-end: auto;">  <span style="color:#3D93E0; font-size:20px;"><b>Diagnostic History : </b></span> </th>
	</tr>
</table>
<table cellspacing="0" cellpadding="1" style="border:1px solid #cacaca" width="100%"><tr><th width="10%" align="center" style="border:1px solid #cacaca"><b>Sr.No</b></th><th width="30%" align="center" style="border:1px solid #cacaca"><b>Name</b></th><th width="10%" align="center" style="border:1px solid #cacaca"><b>ICD Code</b></th><th width="15%" align="center" style="border:1px solid #cacaca"><b>Start Date</b></th><th width="15%" align="center" style="border:1px solid #cacaca"><b>Stop Date</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Notes</b></th></tr>';

//echo "In Past Diagnostics: Count is:".$query->rowCount()."<br>";
while($row = $query->fetch(PDO::FETCH_ASSOC))
{
	$col1 = '<td width="10%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$i.'</td>';
	$col2 = '<td width="30%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row['dxname'].'</td>';
	$col3 = '<td width="10%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row['dxcode'].'</td>';
	$col4 = '<td width="15%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["datestart"].'</td>';
	$col5 = '<td width="15%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["dateend"].'</td>';
	$col6 = '<td width="20%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["notes"].'</td>';
	
	$table1 = $table1 .'<tr>'.$col1.$col2.$col3.$col4.$col5.$col6.'</tr>';
	$i++;
    
    //echo $row['dxname']."<br>";
}


$table1 = $table1 . '</table></div></br>';


//Generating HTML for Medication. Show the data which has not been marked deleted
$query = $con->prepare("select drugname,drugcode,dossage,frequency from p_medication where idpatient=? and deleted = 0");
$query->bindValue(1, $idUsu, PDO::PARAM_INT);

$result = $query->execute();
$i=1;
$table2 = '<div><table width="100%">
<tr>
		<th width="100%" height="30" style="margin-top:10px;border:1px solid #cacaca;margin:20px 0;-webkit-margin-before: 0.5em;-webkit-margin-after: 0.5em;-webkit-margin-start: auto;-webkit-margin-end: auto;">  <span style="color:#3D93E0; font-size:20px;"><b>Medication History : </b></span> </th>
	</tr>
</table>
<table cellspacing="0" cellpadding="1" style="border:1px solid #cacaca" width="100%"><tr><th width="10%" align="center" style="border:1px solid #cacaca"><b>Sr.No</b></th><th width="30%" align="center" style="border:1px solid #cacaca"><b>Drug Name</b></th><th width="10%" align="center" style="border:1px solid #cacaca"><b>Drug Code</b></th><th width="30%" align="center" style="border:1px solid #cacaca"><b>Dossage</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Frequency</b></th></tr>';


//echo "In Medications: Count is:".$query->rowCount()."<br>";
while($row = $query->fetch(PDO::FETCH_ASSOC))
{
//check to see if it is a valid drug code    
$drugcode = $row["drugcode"];
    if ($drugcode == 'DB00000') {
        $drugcode = "N/A";   
    }    
	$col1 = '<td width="10%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$i.'</td>';
	$col2 = '<td width="30%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["drugname"].'</td>';
	$col3 = '<td width="10%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$drugcode.'</td>';
	$col4 = '<td width="30%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["dossage"].'</td>';
	$col5 = '<td width="20%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["frequency"].'</td>';
	
	
	$table2 = $table2 .'<tr>'.$col1.$col2.$col3.$col4.$col5.'</tr>';
	$i++;
    
    //echo $row['drugname']."<br>";
}

$table2 = $table2 . '</table></div></br>';

//Generating HTML for Immunizations
$query = $con->prepare("select Vaccname,ageevent,intensity,DATE_FORMAT(date,'%m-%d-%Y') as datestart from p_immuno where idpatient=? and Vaccname!='' and deleted=0");
$query->bindValue(1, $idUsu, PDO::PARAM_INT);

$result = $query->execute();
$i=1;
$table3 = '<div><table width="100%">
<tr>
		<th width="100%" height="30" style="margin-top:10px;border:1px solid #cacaca;margin:20px 0;-webkit-margin-before: 0.5em;-webkit-margin-after: 0.5em;-webkit-margin-start: auto;-webkit-margin-end: auto;">  <span style="color:#3D93E0; font-size:20px;"><b>Immunization History : </b></span> </th>
	</tr>
</table>
<table cellspacing="0" cellpadding="1" style="border:1px solid #cacaca" width="100%"><tr><th width="10%" align="center" style="border:1px solid #cacaca"><b>Sr.No</b></th><th width="30%" align="center" style="border:1px solid #cacaca"><b>Name</b></th><th width="30%" align="center" style="border:1px solid #cacaca"><b>Date Recorded</b></th><th width="30%" align="center" style="border:1px solid #cacaca"><b>Age At Event</b></th></tr>';

//echo "In Immunizations: Count is:".$query->rowCount()."<br>";
while($row = $query->fetch(PDO::FETCH_ASSOC))
{
	$col1 = '<td width="10%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$i.'</td>';
	$col2 = '<td width="30%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["Vaccname"].'</td>';
	$col3 = '<td width="30%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["datestart"].'</td>';
	$col4 = '<td width="30%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["ageevent"].'</td>';	
	
	$table3 = $table3 .'<tr>'.$col1.$col2.$col3.$col4.'</tr>';
	$i++;
    
    
}
$table3 = $table3 . '</table></div></br>';


//Generating HTML for Allergies
$query = $con->prepare("select allername,ageevent,intensity,DATE_FORMAT(date,'%m-%d-%Y') as datestart from p_immuno where idpatient=? and allername!='' and deleted = 0");
$query->bindValue(1, $idUsu, PDO::PARAM_INT);

$result = $query->execute();
$i=1;
$table4 = '<div><table width="100%">
<tr>
		<th width="100%" height="30" style="margin-top:10px;border:1px solid #cacaca;margin:20px 0;-webkit-margin-before: 0.5em;-webkit-margin-after: 0.5em;-webkit-margin-start: auto;-webkit-margin-end: auto;">  <span style="color:#3D93E0; font-size:20px;"><b>Allergy History : </b></span> </th>
	</tr>
</table>
<table cellspacing="0" cellpadding="1" style="border:1px solid #cacaca" width="100%"><tr><th width="10%" align="center" style="border:1px solid #cacaca"><b>Sr.No</b></th><th width="30%" align="center" style="border:1px solid #cacaca"><b>Allergy Name</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Date Recorded</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Age At Start</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Intensity</b></th></tr>';

//echo "In Allergies: Count is:".$query->rowCount()."<br>";
while($row = $query->fetch(PDO::FETCH_ASSOC))
{
	$col1 = '<td width="10%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$i.'</td>';
	$col2 = '<td width="30%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["allername"].'</td>';
	$col3 = '<td width="20%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["datestart"].'</td>';
	$col4 = '<td width="20%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["ageevent"].'</td>';
	$col5 = '<td width="20%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["intensity"].'</td>';
	
	
	$table4 = $table4 .'<tr>'.$col1.$col2.$col3.$col4.$col5.'</tr>';
	$i++;
}
$table4 = $table4 . '</table></div></br>';

//Generating table for habits
$query = $con->prepare("select cigarettes,alcohol,exercise,sleep from p_habits where idpatient=?");
$query->bindValue(1, $idUsu, PDO::PARAM_INT);

$result = $query->execute();
$i=1;
$table5 = '<div><table width="100%">
<tr>
		<th width="100%" height="30" style="margin-top:10px;border:1px solid #cacaca;margin:20px 0;-webkit-margin-before: 0.5em;-webkit-margin-after: 0.5em;-webkit-margin-start: auto;-webkit-margin-end: auto;">  <span style="color:#3D93E0; font-size:20px;"><b>Habits : </b></span> </th>
	</tr>
</table>
<table cellspacing="0" cellpadding="1" style="border:1px solid #cacaca" width="100%"><tr><th width="25%" align="center" style="border:1px solid #cacaca"><b>Cigarettes(per Day)</b></th><th width="25%" align="center" style="border:1px solid #cacaca"><b>Alcohol(gl/week)</b></th><th width="25%" align="center" style="border:1px solid #cacaca"><b>Exercise(hrs/week)</b></th><th width="25%" align="center" style="border:1px solid #cacaca"><b>Sleep(hrs/week)</b></th></tr>';

//echo "In habits: Count is:".$query->rowCount()."<br>";
while($row = $query->fetch(PDO::FETCH_ASSOC))
{
	
     //echo $row['cigarettes'].'  '.$row['alcohol'].'  '.$row['exercise'].'  '.$row['sleep']."<br>";
    $table5 = $table5.'<tr>'.'<td width="25%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["cigarettes"].'</td>'.'<td width="25%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["alcohol"].'</td>'.'<td width="25%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["exercise"].'</td>'.'<td width="25%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["sleep"].'</td>'.'</tr>' ;
    
	
}
$table5 = $table5 . '</table></div></br>';

echo $table1.$table2.$table3.$table4.$table5;

//Generating the family history
$query = $con->prepare("select * from p_family where idpatient = ? and deleted = 0");
$query->bindValue(1, $idUsu, PDO::PARAM_INT);
$query->execute();

//echo "In Family history: Count is :".$query->rowCount()."<br>";
$family_history_count = $query->rowCount();

$tbl2=
	'<div><table width="100%"><tr>
		<th width="100%" height="30" style="margin-top:10px;border:1px solid #cacaca;margin:20px 0;-webkit-margin-before: 0.5em;-webkit-margin-after: 0.5em;-webkit-margin-start: auto;-webkit-margin-end: auto;">  <span style="color:#3D93E0; font-size:20px;"><b>Family History : </b></span> </th>
	</tr></table><table width="100%" style="border: 1px solid #cacaca;">';	
$x = 0;
while($row = $query->fetch(PDO::FETCH_ASSOC))
{
	$tbl2=$tbl2.'<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>
	
	
	<tr>
        <td width="45%">        <span style="color:#3D93E0;font-size:18px;" >'.$row['relative'].'</span> </td>
		
    </tr>
	<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>
	<tr>
        <td width="45%">        Disease Name : <span style="color:#3D93E0;" >'.$row['disease'].'</span> </td>
		
    </tr>
	<tr>
        <td width="45%">        Age At Disease : <span style="color:#3D93E0;" >'.$row['atage'].'</span> </td>
		
    </tr>';
	
	if($x != $family_history_count - 1){
	$tbl2=$tbl2.'<tr >
		<td width="100%"><hr></td>
	</tr>';
	}
	

 $x++;
}

echo $tbl2."</table></div><div class='page-break'></div>";


//Get the Encryption Password
$enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$enc_result->execute();
$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
$enc_pass=$row_enc['pass'];


//Log that report has been uploaded

$content = "Report Uploaded (EMR)";
$VIEWIdUser=$idUsu;
$ip=$_SERVER['REMOTE_ADDR'];
$MEDIO=0;
$q;

if(isset($_GET['show_pins'])){
	
	if($_SESSION['MEDID'] == $_SESSION['BOTHID']){
		$extra = 'AND (hide_from_member = 0 OR hide_from_member = 1)';
	}else{
		$extra = 'AND hide_from_member = 0';
	}
	
$query = $con->prepare("select * from lifepin where IdUsu=? AND markfordelete is null ".$extra." ORDER BY Fecha DESC");
$query->bindValue(1, $idUsu, PDO::PARAM_INT);
$result = $query->execute();

echo '<table width="100%"><tr>
		<th width="100%" height="30" style="margin-top:10px;border:1px solid #cacaca;margin:20px 0;-webkit-margin-before: 0.5em;-webkit-margin-after: 0.5em;-webkit-margin-start: auto;-webkit-margin-end: auto;">  <span style="color:#3D93E0; font-size:20px;"><b>Reports : </b></span> </th>
	</tr></table>';
$pdf_counter = 1000;
while($row_pins = $query->fetch(PDO::FETCH_ASSOC)){
	$ext = explode(".", $row_pins['RawImage']);
	
	decrypt_files($row_pins['RawImage'],$idUsu,$enc_pass);
	
	if($ext[1] != 'pdf'){
		if(file_exists("temp/".$idUsu."/Packages_Encrypted/".$row_pins['RawImage'])){
			if($row_pins['IdCreator'] > 0){
				$query2 = $con->prepare("select Name,Surname from doctors where id=?");
				$query2->bindValue(1, $row_pins['IdCreator'], PDO::PARAM_INT);
				$result = $query2->execute();
				$row_doctor = $query2->fetch(PDO::FETCH_ASSOC);
				
				if($row_doctor['Name'] != '' && $row_doctor['Surname'] != ''){
				$uploaded_name = "Dr. ".$row_doctor['Name']." ".$row_doctor['Surname'];
				}else{
				$uploaded_name = $name;
				}
			}else{
				$uploaded_name = $name;
			}
			$uploaded_time = substr($row_pins['FechaInput'],0,-9);
			$make_time = substr($row_pins['FechaInput'],-6,9);
			$make_hour = substr($row_pins['FechaInput'],-8,2);
			if($make_hour > 12){
				$make_hour = $make_hour - 12;
				$am_pm = 'pm';
			}else{
				$am_pm = 'am';
			}
			$date_of_report = substr($row_pins['Fecha'],0,-9);
			echo '<div><table width="100%" style="background-color:#E6E6E6;"><tr><th width="100%" height="30" style="margin-top:10px;border:1px solid #cacaca;margin:20px 0;-webkit-margin-before: 0.5em;-webkit-margin-after: 0.5em;-webkit-margin-start: auto;-webkit-margin-end: auto;">  <span style="color:#3D93E0; font-size:20px;">
		<b>File : Uploaded by: '.$uploaded_name.'</br>On : '.$uploaded_time.' @ '.$make_hour.$make_time.' '.$am_pm.'</b></br>Report Date: '.$date_of_report.'</span> </th>
		
		</tr></table>';
			echo "<center><img src='temp/".$idUsu."/Packages_Encrypted/".$row_pins['RawImage']."'/></center></div><p></p>";
			echo '<div class="page-break"></div>';
		}elseif(file_exists("temp/".$row_pins['IdCreator']."/Packages_Encrypted/".$row_pins['RawImage'])){
			if($row_pins['IdCreator'] > 0){
				$query2 = $con->prepare("select Name,Surname from doctors where id=?");
				$query2->bindValue(1, $row_pins['IdCreator'], PDO::PARAM_INT);
				$result = $query2->execute();
				$row_doctor = $query2->fetch(PDO::FETCH_ASSOC);
				
				if($row_doctor['Name'] != '' && $row_doctor['Surname'] != ''){
				$uploaded_name = "Dr. ".$row_doctor['Name']." ".$row_doctor['Surname'];
				}else{
				$uploaded_name = $name;
				}
			}else{
				$uploaded_name = $name;
			}
			$uploaded_time = substr($row_pins['FechaInput'],0,-9);
			$make_time = substr($row_pins['FechaInput'],-6,9);
			$make_hour = substr($row_pins['FechaInput'],-8,2);
			if($make_hour > 12){
				$make_hour = $make_hour - 12;
				$am_pm = 'pm';
			}else{
				$am_pm = 'am';
			}
			$date_of_report = substr($row_pins['Fecha'],0,-9);
			echo '<div><table width="100%" style="background-color:#E6E6E6;"><tr><th width="100%" height="30" style="margin-top:10px;border:1px solid #cacaca;margin:20px 0;-webkit-margin-before: 0.5em;-webkit-margin-after: 0.5em;-webkit-margin-start: auto;-webkit-margin-end: auto;">  <span style="color:#3D93E0; font-size:20px;">
		<b>File : Uploaded by: '.$uploaded_name.'</br>On : '.$uploaded_time.' @ '.$make_hour.$make_time.' '.$am_pm.'</b></br>Report Date: '.$date_of_report.'</span> </th>
		
		</tr></table>';
			echo "<center><img src='temp/".$row_pins['IdCreator']."/Packages_Encrypted/".$row_pins['RawImage']."'/></center></div><p></p>";
			echo '<div class="page-break"></div>';
		}
	}else{
	$content_url =   'temp/'.$idUsu.'/Packages_Encrypted/'.$row_pins['RawImage'];
		if(file_exists('temp/'.$idUsu.'/Packages_Encrypted/'.$row_pins['RawImage'])){
			if($row_pins['IdCreator'] > 0){
				$query2 = $con->prepare("select Name,Surname from doctors where id=?");
				$query2->bindValue(1, $row_pins['IdCreator'], PDO::PARAM_INT);
				$result = $query2->execute();
				$row_doctor = $query2->fetch(PDO::FETCH_ASSOC);
				
				if($row_doctor['Name'] != '' && $row_doctor['Surname'] != ''){
				$uploaded_name = "Dr. ".$row_doctor['Name']." ".$row_doctor['Surname'];
				}else{
				$uploaded_name = $name;
				}
			}else{
				$uploaded_name = $name;
			}
			$uploaded_time = substr($row_pins['FechaInput'],0,-9);
			$make_time = substr($row_pins['FechaInput'],-6,9);
			$make_hour = substr($row_pins['FechaInput'],-8,2);
			if($make_hour > 12){
				$make_hour = $make_hour - 12;
				$am_pm = 'pm';
			}else{
				$am_pm = 'am';
			}
			$date_of_report = substr($row_pins['Fecha'],0,-9);
			echo '<div><table width="100%" style="background-color:#E6E6E6;"><tr><th width="100%" height="30" style="margin-top:10px;border:1px solid #cacaca;margin:20px 0;-webkit-margin-before: 0.5em;-webkit-margin-after: 0.5em;-webkit-margin-start: auto;-webkit-margin-end: auto;">  <span style="color:#3D93E0; font-size:20px;">
		<b>File : Uploaded by: '.$uploaded_name.'</br>On : '.$uploaded_time.' @ '.$make_hour.$make_time.' '.$am_pm.'</b></br>Report Date: '.$date_of_report.'</b></span> </th>
		
		</tr></table>';
		
		$convert = shell_exec("convert ".$content_url." temp/".$idUsu."/Packages_Encrypted/file".$row_pins['RawImage'].$pdf_counter.".png");
		if(!$convert){
			//echo "Failed";
		}
		if(file_exists("temp/".$idUsu."/Packages_Encrypted/file".$row_pins['RawImage'].$pdf_counter.".png")){
		echo "<center><img src='temp/".$idUsu."/Packages_Encrypted/file".$row_pins['RawImage'].$pdf_counter.".png'/></center>";
		
		}else{
			for($x=0;$x<100;$x++){
			if(file_exists("temp/".$idUsu."/Packages_Encrypted/file".$row_pins['RawImage'].$pdf_counter."-".$x.".png")){
				echo "<center><img src='temp/".$idUsu."/Packages_Encrypted/file".$row_pins['RawImage'].$pdf_counter."-".$x.".png'/></center>";
				
			}else{
				break;
			}
			}
		}
		//echo '<iframe id="ImagenN" style="border:1px solid #666CCC; margin:0 auto; display:block;overflow:hidden;height:100%;" title="PDF" src="'.$content_url.'" alt="'.$idUsu.'" frameborder="1" height="100%" width="600" ></iframe></div><p></p>';
		echo '<div class="page-break"></div>';
		$pdf_counter--;
	}elseif(file_exists('temp/'.$row_pins['IdCreator'].'/Packages_Encrypted/'.$row_pins['RawImage'])){
		$content_url =   'temp/'.$row_pins['IdCreator'].'/Packages_Encrypted/'.$row_pins['RawImage'];
		if($row_pins['IdCreator'] > 0){
				$query2 = $con->prepare("select Name,Surname from doctors where id=?");
				$query2->bindValue(1, $row_pins['IdCreator'], PDO::PARAM_INT);
				$result = $query2->execute();
				$row_doctor = $query2->fetch(PDO::FETCH_ASSOC);
				
				if($row_doctor['Name'] != '' && $row_doctor['Surname'] != ''){
				$uploaded_name = "Dr. ".$row_doctor['Name']." ".$row_doctor['Surname'];
				}else{
				$uploaded_name = $name;
				}
			}else{
				$uploaded_name = $name;
			}
			$uploaded_time = substr($row_pins['FechaInput'],0,-9);
			$make_time = substr($row_pins['FechaInput'],-6,9);
			$make_hour = substr($row_pins['FechaInput'],-8,2);
			if($make_hour > 12){
				$make_hour = $make_hour - 12;
				$am_pm = 'pm';
			}else{
				$am_pm = 'am';
			}
			$date_of_report = substr($row_pins['Fecha'],0,-9);
			echo '<div><table width="100%" style="background-color:#E6E6E6;"><tr><th width="100%" height="30" style="margin-top:10px;border:1px solid #cacaca;margin:20px 0;-webkit-margin-before: 0.5em;-webkit-margin-after: 0.5em;-webkit-margin-start: auto;-webkit-margin-end: auto;">  <span style="color:#3D93E0; font-size:20px;">
		<b>File : Uploaded by: '.$uploaded_name.'</br>On : '.$uploaded_time.' @ '.$make_hour.$make_time.' '.$am_pm.'</b></br>Report Date: '.$date_of_report.'</b></span> </th>
		
		</tr></table>';
		
		$convert = shell_exec("convert ".$content_url." temp/".$row_pins['IdCreator']."/Packages_Encrypted/file".$row_pins['RawImage'].$pdf_counter.".png");
		if(!$convert){
			//echo "Failed";
		}
		if(file_exists("temp/".$row_pins['IdCreator']."/Packages_Encrypted/file".$row_pins['RawImage'].$pdf_counter.".png")){
		echo "<center><img src='temp/".$row_pins['IdCreator']."/Packages_Encrypted/file".$row_pins['RawImage'].$pdf_counter.".png'/></center>";
		
		}else{
			for($x=0;$x<100;$x++){
			if(file_exists("temp/".$row_pins['IdCreator']."/Packages_Encrypted/file".$row_pins['RawImage'].$pdf_counter."-".$x.".png")){
				echo "<center><img src='temp/".$row_pins['IdCreator']."/Packages_Encrypted/file".$row_pins['RawImage'].$pdf_counter."-".$x.".png'/></center>";
				
			}else{
				break;
			}
			}
		}
		//echo '<iframe id="ImagenN" style="border:1px solid #666CCC; margin:0 auto; display:block;overflow:hidden;height:100%;" title="PDF" src="'.$content_url.'" alt="'.$idUsu.'" frameborder="1" height="100%" width="600" ></iframe></div><p></p>';
		echo '<div class="page-break"></div>';
		$pdf_counter--;
	}
	
	}
}
}

function decrypt_files($rawimage,$queMed,$pass )
{
	$ImageRaiz = substr($rawimage,0,strlen($rawimage)-4);
	$extensionR = substr($rawimage,strlen($rawimage)-3,3);
	

	if($extensionR=='jpg')
	{
		//die("Found JPG Extension");
		$extension='jpg';
		//return;
	}
	elseif($extensionR=='pdf'){
		$extension='pdf';
	}else{
		$extension='png';
	}
	$filename = 'temp/'.$queMed.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extension;	
	//echo $filename;
	if (file_exists($filename)) 
	{
		//do nothing
		//echo "The file $filename exists";	
	}
	else 
	{
		$out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in Packages_Encrypted/".$ImageRaiz.".".$extension." -out temp/".$queMed."/Packages_Encrypted/".$ImageRaiz.".".$extension);
		//die($out.' '.$ImageRaiz);
	}


}

//$pdf->Output('example_048.pdf', 'I');


//============================================================+
// END OF FILE
//============================================================+*/
?>
