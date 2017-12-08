<?php

session_start();

// Include the main TCPDF library 
require_once('tcpdf/tcpdf.php');
require("environment_detail.php");
error_reporting(E_ERROR | E_PARSE);
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


$idUsu = $_POST['IdUsu'];
$userid= $_SESSION['MEDID']; //  userid over here is referring to doctorid


//$userid = 151;

$usuariosquery = $con->prepare("select sexo,name,surname,idusfixed,idusfixedname,location from usuarios where identif=?");
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
echo "Country : ".$country."<br>";
echo "State   : ".$state."<br>";

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
    if($$row2['weightType'] == 1)
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
$table1 = '<table cellspacing="0" cellpadding="1" style="border:1px solid #cacaca" width="100%"><tr><th width="10%" align="center" style="border:1px solid #cacaca"><b>Sr.No</b></th><th width="30%" align="center" style="border:1px solid #cacaca"><b>Name</b></th><th width="10%" align="center" style="border:1px solid #cacaca"><b>ICD Code</b></th><th width="15%" align="center" style="border:1px solid #cacaca"><b>Start Date</b></th><th width="15%" align="center" style="border:1px solid #cacaca"><b>Stop Date</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Notes</b></th></tr>';

echo "In Past Diagnostics: Count is:".$query->rowCount()."<br>";
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
    
    echo $row['dxname']."<br>";
}


$table1 = $table1 . '</table>';


//Generating HTML for Medication. Show the data which has not been marked deleted
$query = $con->prepare("select drugname,drugcode,dossage,frequency from p_medication where idpatient=? and deleted = 0");
$query->bindValue(1, $idUsu, PDO::PARAM_INT);

$result = $query->execute();
$i=1;
$table2 = '<table cellspacing="0" cellpadding="1" style="border:1px solid #cacaca" width="100%"><tr><th width="10%" align="center" style="border:1px solid #cacaca"><b>Sr.No</b></th><th width="30%" align="center" style="border:1px solid #cacaca"><b>Drug Name</b></th><th width="10%" align="center" style="border:1px solid #cacaca"><b>Drug Code</b></th><th width="30%" align="center" style="border:1px solid #cacaca"><b>Dossage</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Frequency</b></th></tr>';


echo "In Medications: Count is:".$query->rowCount()."<br>";
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
    
    echo $row['drugname']."<br>";
}

$table2 = $table2 . '</table>';

//Generating HTML for Immunizations
$query = $con->prepare("select Vaccname,ageevent,intensity,DATE_FORMAT(date,'%m-%d-%Y') as datestart from p_immuno where idpatient=? and Vaccname!='' and deleted=0");
$query->bindValue(1, $idUsu, PDO::PARAM_INT);

$result = $query->execute();
$i=1;
$table3 = '<table cellspacing="0" cellpadding="1" style="border:1px solid #cacaca" width="100%"><tr><th width="10%" align="center" style="border:1px solid #cacaca"><b>Sr.No</b></th><th width="30%" align="center" style="border:1px solid #cacaca"><b>Name</b></th><th width="30%" align="center" style="border:1px solid #cacaca"><b>Date Recorded</b></th><th width="30%" align="center" style="border:1px solid #cacaca"><b>Age At Event</b></th></tr>';

echo "In Immunizations: Count is:".$query->rowCount()."<br>";
while($row = $query->fetch(PDO::FETCH_ASSOC))
{
	$col1 = '<td width="10%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$i.'</td>';
	$col2 = '<td width="30%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["Vaccname"].'</td>';
	$col3 = '<td width="30%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["datestart"].'</td>';
	$col4 = '<td width="30%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["ageevent"].'</td>';	
	
	$table3 = $table3 .'<tr>'.$col1.$col2.$col3.$col4.'</tr>';
	$i++;
    
    
}
$table3 = $table3 . '</table>';


//Generating HTML for Allergies
$query = $con->prepare("select allername,ageevent,intensity,DATE_FORMAT(date,'%m-%d-%Y') as datestart from p_immuno where idpatient=? and allername!='' and deleted = 0");
$query->bindValue(1, $idUsu, PDO::PARAM_INT);

$result = $query->execute();
$i=1;
$table4 = '<table cellspacing="0" cellpadding="1" style="border:1px solid #cacaca" width="100%"><tr><th width="10%" align="center" style="border:1px solid #cacaca"><b>Sr.No</b></th><th width="30%" align="center" style="border:1px solid #cacaca"><b>Allergy Name</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Date Recorded</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Age At Start</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Intensity</b></th></tr>';

echo "In Allergies: Count is:".$query->rowCount()."<br>";
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
$table4 = $table4 . '</table>';

//Generating table for habits
$query = $con->prepare("select cigarettes,alcohol,exercise,sleep from p_habits where idpatient=?");
$query->bindValue(1, $idUsu, PDO::PARAM_INT);

$result = $query->execute();
$i=1;
$table5 = '<table cellspacing="0" cellpadding="1" style="border:1px solid #cacaca" width="100%"><tr><th width="25%" align="center" style="border:1px solid #cacaca"><b>Cigarettes(per Day)</b></th><th width="25%" align="center" style="border:1px solid #cacaca"><b>Alcohol(gl/week)</b></th><th width="25%" align="center" style="border:1px solid #cacaca"><b>Exercise(hrs/week)</b></th><th width="25%" align="center" style="border:1px solid #cacaca"><b>Sleep(hrs/week)</b></th></tr>';

echo "In habits: Count is:".$query->rowCount()."<br>";
while($row = $query->fetch(PDO::FETCH_ASSOC))
{
	
     echo $row['cigarettes'].'  '.$row['alcohol'].'  '.$row['exercise'].'  '.$row['sleep']."<br>";
    $table5 = $table5.'<tr>'.'<td width="25%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["cigarettes"].'</td>'.'<td width="25%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["alcohol"].'</td>'.'<td width="25%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["exercise"].'</td>'.'<td width="25%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["sleep"].'</td>'.'</tr>' ;
    
	
}
$table5 = $table5 . '</table>';

$query = $con->prepare("select * from emr_config where userid = ?");
$query->bindValue(1, $userid, PDO::PARAM_INT);

$result = $query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC); 



// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Health2Me');
$pdf->SetTitle('User Demographics');
$pdf->SetSubject('User Demographics ');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}


//Demographics Section
// --------------------------------------------------------------------------

// set font
$pdf->SetFont('helvetica', 'B', 24);

//$pdf->SetPrintHeader(false);
//$pdf->SetPrintFooter(false);
// add a page
$pdf->AddPage();
//$pdf->Write(0, 'Demographics', '', 0, 'C', true, 0, false, false, 0);
$pdf->writeHTML('<br><br><u><b><span style="color:#3D93E0;  font-weight:bold;">Patient Summary</span></b></u>', true, false, false, false, 'C');
$pdf->SetFont('helvetica', '', 12);



$tbl = <<<EOD


<br><br>

<table cellspacing="0" cellpadding="0"  width="100%" style="margin-top:300px;border:1px solid #cacaca">
    
	<tr>
		<td width="100%" height="30" style="margin-top:10px;border:1px solid #cacaca;margin:20px 0;-webkit-margin-before: 0.5em;-webkit-margin-after: 0.5em;-webkit-margin-start: auto;-webkit-margin-end: auto;">  <span style="color:#3D93E0; font-size:20px;"><b>Basic Demographics : </b></span> </td>
	</tr>
	<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>
	
	<tr>
		<td width ="10%"> </td>
        <td >        Name : <span style="color:#3D93E0;">$name</span>  </td>

    </tr>
	
    <tr>
		<td width ="10%"> </td>
        <td>        Date of Birth : <span style="color:#3D93E0;">$dob</span> </td>
       
    </tr>
	<tr>
		<td width ="10%"> </td>
		<td>        Gender : <span style="color:#3D93E0;">$gender</span>  </td>
	</tr>
	
	
	<tr>
		<td width ="10%"> </td>
        <td >        Address : <span style="color:#3D93E0;">$address</span> </td>
    </tr>
	
	<tr>
		<td width ="10%"> </td>
        <td >        Address2 : <span style="color:#3D93E0;">$address2</span> </td>
    </tr>
	
	<tr>
		<td width ="10%"> </td>
        <td >        City : <span style="color:#3D93E0;">$city</span> </td>
    </tr>
	<tr>
		<td width ="10%"> </td>
        <td >        State : <span style="color:#3D93E0;">$state</span> </td>
    </tr>
	
	<tr>
		<td width ="10%"> </td>
        <td >        Country : <span style="color:#3D93E0;">$country</span> </td>
    </tr>
	<tr>
		<td width ="10%"> </td>
        <td >        Phone : <span style="color:#3D93E0;">$phone</span> </td>
    </tr>
    <tr>
		<td width ="10%"> </td>
        <td >        Email : <span style="color:#3D93E0;">$email</span> </td>
    </tr>

    <tr>
		<td width ="10%"> </td>
		<td >        Blood Type : <span style="color:#3D93E0;">$blood_type</span> </td>
    </tr>
    <tr>
		<td width ="10%"> </td>
		<td >        Weight : <span style="color:#3D93E0;">$weight</span> </td>
    </tr>
    <tr>
		<td width ="10%"> </td>
		<td >        Height : <span style="color:#3D93E0;">$height</span> </td>
    </tr>
	<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>
	<tr>
		<td width="100%" height="30" style="margin-top:10px;border:1px solid #cacaca;margin:20px 0;-webkit-margin-before: 0.5em;-webkit-margin-after: 0.5em;-webkit-margin-start: auto;-webkit-margin-end: auto;">  <span style="color:#3D93E0; font-size:20px;"><b>Personal History : </b></span> </td>
	</tr>
    <tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>
	<tr>
		<td width ="5%"> </td>
        <td >        Fractures : <span style="color:#3D93E0;">$fractures</span>  </td>

    </tr>
	<tr>
		<td width ="5%"> </td>
        <td >        Surgeries : <span style="color:#3D93E0;">$surgeries</span>  </td>

    </tr>
	<tr>
		<td width ="5%"> </td>
        <td >        Other Known Medical Events : <span style="color:#3D93E0;">$otherknown</span>  </td>

    </tr>
	<tr>
		<td width ="5%"> </td>
        <td >        Obstetric History : <span style="color:#3D93E0;">$obstetric</span>  </td>

    </tr>
	<tr>
		<td width ="5%"> </td>
        <td >        Other Medical Data : <span style="color:#3D93E0;">$othermed</span>  </td>

    </tr>
	<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>
	<tr>
		<td width="100%" height="30" style="margin-top:10px;border:1px solid #cacaca;margin:20px 0;-webkit-margin-before: 0.5em;-webkit-margin-after: 0.5em;-webkit-margin-start: auto;-webkit-margin-end: auto;">  <span style="color:#3D93E0; font-size:20px;"><b>Family History : </b></span> </td>
	</tr>
	<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>
	<tr>
		<td width ="5%"></td>
        <td width="45%">        <span style="color:#3D93E0;font-size:18px;" ><b>Father :</b></span> </td>
		<td width ="5%"></td>
        <td width="45%">        <span style="color:#3D93E0;font-size:18px;" ><b>Mother :</b></span>   </td>
    </tr>
	<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>
	<tr>
		<td width ="5%"></td>
        <td width="45%">        Alive : <span style="color:#3D93E0;" >$fatheralive</span> </td>
		<td width ="5%"></td>
        <td width="45%">        Alive : <span style="color:#3D93E0;" >$motheralive</span>   </td>
    </tr>
	<tr>
		<td width ="5%"></td>
        <td width="45%">        Cause of Death : <span style="color:#3D93E0;" >$fcod</span> </td>
		<td width ="5%"></td>
        <td width="45%">        Cause of Death : <span style="color:#3D93E0;" >$mcod</span>   </td>
    </tr>
	<tr>
		<td width ="5%"></td>
        <td width="45%">        Age of Death : <span style="color:#3D93E0;" >$faod</span> </td>
		<td width ="5%"></td>
        <td width="45%">        Age of Death : <span style="color:#3D93E0;" >$maod</span>   </td>
    </tr>
	<tr>
		<td width ="5%"></td>
        <td width="45%">        Relevant Diseases : <span style="color:#3D93E0;" >$frd</span> </td>
		<td width ="5%"></td>
        <td width="45%">        Relevant Diseases : <span style="color:#3D93E0;" >$mrd</span>   </td>
    </tr>
	<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>
	<tr>
		<td width="5%"></td>
		<td> <span style="color:#3D93E0;font-size:18px;" ><b>Siblings :</b></span> </td>
	</tr>
	<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>
	<tr>
		<td width="5%"></td>
		<td>        Relevant Diseases : <span style="color:#3D93E0;" >$siblingsrd</span> </td>
	</tr>
	<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>
</table>

EOD;



$tbl = '<br><br>

<table cellspacing="0" cellpadding="0"  width="100%" style="margin-top:300px;border:1px solid #cacaca">
    
	<tr>
		<td width="100%" height="30" style="margin-top:10px;border:1px solid #cacaca;margin:20px 0;-webkit-margin-before: 0.5em;-webkit-margin-after: 0.5em;-webkit-margin-start: auto;-webkit-margin-end: auto;">  <span style="color:#3D93E0; font-size:20px;"><b>Basic Demographics : </b></span> </td>
	</tr>
	<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>
	
	<tr>
		<td width ="10%"> </td>
        <td >   Name : <span style="color:#3D93E0;">'.$name.'</span>  </td>

    </tr>
	
    <tr>
		<td width ="10%"> </td>
        <td>    Date of Birth : <span style="color:#3D93E0;">'.$dob.'</span> </td>
       
    </tr>
	<tr>
		<td width ="10%"> </td>
		<td>    Gender : <span style="color:#3D93E0;">'.$gender.'</span>  </td>
	</tr>
';

if($row['address'])
{
	$tbl=$tbl.'<tr>
		<td width ="10%"> </td>
        <td >   Address1 : <span style="color:#3D93E0;">'.$address.'</span> </td>
    </tr>';
}


if($row['address2'])
{
	$tbl = $tbl.'<tr>
		<td width ="10%"> </td>
        <td >   Address2 : <span style="color:#3D93E0;">'.$address2.'</span> </td>
    </tr>';
}

if($row['city'])
{
	$tbl = $tbl.'<tr>
		<td width ="10%"> </td>
        <td >   City : <span style="color:#3D93E0;">'.$city.'</span> </td>
    </tr>';
}

if($zip)
{
	$tbl=$tbl.'<tr>
		<td width ="10%"> </td>
        <td >   Zip : <span style="color:#3D93E0;">'.$zip.'</span> </td>
    </tr>';
}

if($row['state'])
{
	$tbl=$tbl.'<tr>
		<td width ="10%"> </td>
        <td >   State : <span style="color:#3D93E0;">'.$state.'</span> </td>
    </tr>';
}

if($row['country'])
{
	$tbl = $tbl.'<tr>
		<td width ="10%"> </td>
        <td >   Country : <span style="color:#3D93E0;">'.$country.'</span> </td>
    </tr>';
}

if($email)
{
	$tbl = $tbl.'<tr>
		<td width ="10%"> </td>
        <td >   Email : <span style="color:#3D93E0;">'.$email.'</span> </td>
    </tr>';
}

if($phone)
{
	$tbl = $tbl.'<tr>
		<td width ="10%"> </td>
        <td >   Phone : <span style="color:#3D93E0;">'.$phone.'</span> </td>
    </tr>';
}

if($blood_type)
{
	$tbl = $tbl.'<tr>
		<td width ="10%"> </td>
        <td >   Blood Type : <span style="color:#3D93E0;">'.$blood_type.'</span> </td>
    </tr>';
}

if($weight)
{
	$tbl = $tbl.'<tr>
		<td width ="10%"> </td>
        <td >   Weight : <span style="color:#3D93E0;">'.$weight.'</span> </td>
    </tr>';
}

if($height)
{
	$tbl = $tbl.'<tr>
		<td width ="10%"> </td>
        <td >   Height : <span style="color:#3D93E0;">'.$height.'</span> </td>
    </tr>';
}

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
	
$tbl=$tbl.
	'<tr>
		<td width="100%" height="30" style="margin-top:10px;border:1px solid #cacaca;margin:20px 0;-webkit-margin-before: 0.5em;-webkit-margin-after: 0.5em;-webkit-margin-start: auto;-webkit-margin-end: auto;">  <span style="color:#3D93E0; font-size:20px;"><b>Family History : </b></span> </td>
	</tr>';	

//Generating the family history
$query = $con->prepare("select * from p_family where idpatient = ? and deleted = 0");
$query->bindValue(1, $idUsu, PDO::PARAM_INT);
$query->execute();

echo "In Family history: Count is :".$query->rowCount()."<br>";

while($row = $query->fetch(PDO::FETCH_ASSOC))
{
	$tbl=$tbl.'<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>
	
	
	<tr>
		<td width ="5%"></td>
        <td width="45%">        <span style="color:#3D93E0;font-size:18px;" >'.$row['relative'].'</span> </td>
		
    </tr>
	<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>
	<tr>
		<td width ="5%"></td>
        <td width="45%">        Disease Name : <span style="color:#3D93E0;" >'.$row['disease'].'</span> </td>
		
    </tr>
	<tr>
		<td width ="5%"></td>
        <td width="45%">        Age At Disease : <span style="color:#3D93E0;" >'.$row['atage'].'</span> </td>
		
    </tr>
	
	<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>';
	

	
	$tbl=$tbl.
	'<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>';

    


}	
	
	
	
	
$tbl = $tbl.'</table>';	


$pdf->writeHTML($tbl, true, false, false, false, '');

/*
$pdf->writeHTML('<br><br><br>', true, false, false, false, '');

//Personal History
//------------------------------------------------------------------------------
$pdf->AddPage();

// set font
$pdf->SetFont('helvetica', 'B', 24);
$pdf->writeHTML('<br><br><u><b><span style="color:#3D93E0;  font-weight:bold;">Personal History</span></b></u><br>', true, false, false, false, 'C');
$pdf->SetFont('helvetica', '', 16);


$fracturestext ='<li><b><u>Fractures and Other Traumas:</u></b><br><br>'.$fractures.'<br></li>';
$surgeriestext ='<li><b><u>Surgeries:</u></b><br><br>'.$surgeries.'<br></li>';
$otherknowntext ='<li><b><u>Other Known Medical Events:</u></b><br><br>'.$otherknown.'<br></li>';
$obstetrictext ='<li><b><u>Obstetric History:</u></b><br><br>'.$obstetric.'<br></li>';
$othermedtext ='<li><b><u>Other Medical Data:</u></b><br><br>'.$othermed.'<br></li>';


$personalhistory = '<ul>'.$fracturestext.$surgeriestext.$otherknowntext.$obstetrictext.$othermedtext.'</ul><br>';
$pdf->writeHTML($personalhistory, true, false, false, false, 'L');
//------------------------------------------------------------------------------------

//Family History
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 24);
$pdf->writeHTML('<br><br><u><b><span style="color:#3D93E0;  font-weight:bold;">Family History</span></b></u>', true, false, false, false, 'C');
$pdf->SetFont('helvetica', '', 16);

$head = '<ul>';
$tail = '</ul>';
$fatherdata = '<ul><li>Alive :   '.$fatheralive.'<br></li>
                   <li>Cause of Death : '.$fcod.'<br></li>
				   <li>Age of Death : '.$faod.'<br></li>
				   <li>Relevant Diseases : '.$frd.'<br></li>
               </ul>';
			   
$motherdata = '<ul><li>Alive :   '.$motheralive.'<br></li>
                   <li>Cause of Death : '.$mcod.'<br></li>
				   <li>Age of Death : '.$maod.'<br></li>
				   <li>Relevant Diseases : '.$mrd.'<br></li>
               </ul>';
$siblingsdata = '<br>'.$siblingsrd;


$familyhistory = '<ul><b><li><u>Father </u></li></b><br>'.$fatherdata.
			         '<b><li><u>Mother </u></li></b><br>'.$motherdata.
					 '<b><li><u>Siblings </u></li></b><br>'.$siblingsdata.'</ul>'
		;



		








$pdf->writeHTML($familyhistory, true, false, false, false, 'L');


$tbl = <<<EOD
<br><br>
<br><br>
<table cellspacing="0" cellpadding="1" border="0" width="100%" style="margin-top:300px;">
    <tr>
        <td width="30%">Fractures and Other Traumas : </td>
        <td width="70%">$fractures</td>
        
    </tr>
	<tr>
		<td></td>
		<td></td>
	</tr>
    <tr>
        <td width="30%">Surgeries : </td>
        <td width="70%">$surgeries</td>
    </tr>
	<tr>
		<td></td>
		<td></td>
	</tr>
	<tr>
        <td width="30%">Other Known Medical Events : </td>
        <td width="70%">$otherknown</td>
    </tr>
	<tr>
		<td></td>
		<td></td>
	</tr>
	<tr>
        <td width="30%">Obstetric History : </td>
        <td width="70%">$obstetric</td>
    </tr>
	<tr>
		<td></td>
		<td></td>
	</tr>
	<tr>
        <td width="30%" height="20px">Other Medical Data : </td>
        <td width="70%">$othermed</td>
    </tr>
	<tr>
		<td></td>
		<td></td>
	</tr>
	
</table>
EOD;
*/
$pdf->AddPage();
//$tbl = $table1;
$pdf->SetFont('helvetica', 'B', 18);
$pdf->writeHTML('<br><br><span style="color:#3D93E0; font-size:20px;"><b>Past Diagnostics : </span></b>', true, false, false, false, 'L');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->writeHTML($table1, true, false, false, false, '');

$pdf->SetFont('helvetica', 'B', 18);
$pdf->writeHTML('<br><span style="color:#3D93E0; font-size:20px;"><b>Medications : </span></b>', true, false, false, false, 'L');
$pdf->SetFont('helvetica', 'B', 16);
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTML($table2, true, false, false, false, '');

$pdf->SetFont('helvetica', 'B', 18);
$pdf->writeHTML('<br><span style="color:#3D93E0; font-size:20px;"><b>Immunizations : </span></b>', true, false, false, false, 'L');
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTML($table3, true, false, false, false, '');


$pdf->SetFont('helvetica', 'B', 18);
$pdf->writeHTML('<br><span style="color:#3D93E0; font-size:20px;"><b>Allergies : </span></b>', true, false, false, false, 'L');
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTML($table4, true, false, false, false, '');

$pdf->SetFont('helvetica', 'B', 18);
$pdf->writeHTML('<br><span style="color:#3D93E0; font-size:20px;"><b>Habits : </span></b>', true, false, false, false, 'L');
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTML($table5, true, false, false, false, '');

//Get the Encryption Password
$enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$enc_result->execute();
$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
$enc_pass=$row_enc['pass'];


$extension='pdf';
$confcode = $row1['idusfixed'].md5(date('Ymdgisu'));
$new_image_name = 'eML'.$confcode.'.'.$extension;			
$new_image_nameTH = 'eML'.$confcode.'.png';	

$locFile = "Packages_Encrypted";  
$locFileTH = "PackagesTH_Encrypted";    
$ds = DIRECTORY_SEPARATOR;  
$checksum=md5_file($locFile.$ds.$new_image_name);
$path="C:\\PROGRA~2\\ImageMagick-6.8.1-Q16\\";

//Generate the Report
$pdf->Output($locFile.$ds.$new_image_name,'F');

//Generate Thumbnail
$cadenaConvert = 'convert "'.$locFile.$ds.$new_image_name.'[0]" -colorspace RGB -geometry 200 "'.$locFileTH.$ds.$new_image_nameTH.'" 2>&1';
$output = shell_exec($cadenaConvert);  

copy($locFile.$ds.$new_image_name, 'temp'.$ds.$userid.$ds.'Packages_Encrypted'.$ds.$new_image_name);
copy($locFileTH.$ds.$new_image_nameTH, 'temp'.$ds.$userid.$ds.'PackagesTH_Encrypted'.$ds.$new_image_nameTH);

//Encrypt the thumbnail
exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in PackagesTH_Encrypted/".$new_image_nameTH." -out temp/".$new_image_nameTH);
exec("rm PackagesTH_Encrypted/".$new_image_nameTH);
exec("cp temp/".$new_image_nameTH." PackagesTH_Encrypted/");
exec("rm temp/".$new_image_nameTH);
    
exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in Packages_Encrypted/".$new_image_name." -out temp/".$new_image_name);
exec("rm Packages_Encrypted/".$new_image_name);
exec("cp temp/".$new_image_name." Packages_Encrypted/");
exec("rm temp/".$new_image_name);  					
$tipo=60;  //for demographics



$query = $con->prepare("select count(*) as count from lifepin where idusu =? and orig_filename like '%Summary.pdf'");
$query->bindValue(1, $idUsu, PDO::PARAM_INT);
$query->execute();

$result = $query->fetch(PDO::FETCH_ASSOC);

echo "Summary File is:".$new_image_name."<br>";
echo "Count is:".$result['count']."<br>";

if($result['count'] == 0)
{
    echo "summary_value 1 ".$new_image_name."<br>";
    $a = $con->prepare("update consults set Summary_PDF = ? where patient = ? and doctor = ?");
	$a->bindValue(1, $new_image_name, PDO::PARAM_STR);
	$a->bindValue(2, $idUsu, PDO::PARAM_INT);
	$a->bindValue(3, $userid, PDO::PARAM_INT);
	$a->execute();
	
    $b = $con->prepare("insert into lifepin(idusu,rawimage,fechainput,tipo,canal,needaction,idusfixed,idusfixedname,idmed,idcreator,creatortype,fs,a_s,vs,checksum,orig_filename,emr_report,fecha) 
	values(?,?,now(),?,9,0,?,?,?,?,1,0,0,1,?,?,1,now())");
	$b->bindValue(1, $idUsu, PDO::PARAM_INT);
	$b->bindValue(2, $new_image_name, PDO::PARAM_STR);
	$b->bindValue(3, $tipo, PDO::PARAM_INT);
	$b->bindValue(4, $row1['idusfixed'], PDO::PARAM_INT);
	$b->bindValue(5, $row1['idusfixedname'], PDO::PARAM_STR);
	$b->bindValue(6, 0, PDO::PARAM_INT); //Replaced $userid by numeric 0 as per requirement of making summary viewable publicly
	$b->bindValue(7, $userid, PDO::PARAM_INT);
	$b->bindValue(8, $checksum, PDO::PARAM_STR);
	$b->bindValue(9, $row1['name']."_".$row1['surname']."_Summary.pdf", PDO::PARAM_STR);
	$b->execute();
	
    
    //adding update consults summary code here summary code here    
   $query = $con->prepare("select max(consultationId) as consultationId from consults where patient=? and doctor=?");
   $query->bindValue(1, $idUsu, PDO::PARAM_INT);
   $query->bindValue(2, $userid, PDO::PARAM_INT);
   $query->execute();
   
    $result = $query->fetch(PDO::FETCH_ASSOC);
    
    echo "Consultation".$result['consultationId']."<br>";
    
    $z = $con->prepare("update consults set Summary_PDF =?, length=TIMESTAMPDIFF(SECOND,DateTime,now()) where patient = ? and doctor = ? and consultationId = ?");  
	$z->bindValue(1, $new_image_name, PDO::PARAM_STR);
	$z->bindValue(2, $idUsu, PDO::PARAM_INT);
	$z->bindValue(3, $userid, PDO::PARAM_INT);
	$z->bindValue(4, $result['consultationId'], PDO::PARAM_INT);
	$z->execute();	
}
else
{
    $query = $con->prepare("select max(consultationId) as consultationId from consults where patient=? and doctor=?");
	$query->bindValue(1, $idUsu, PDO::PARAM_INT);
	$query->bindValue(2, $userid, PDO::PARAM_INT);
	$query->execute();	
	
    $result = $query->fetch(PDO::FETCH_ASSOC);
    
    echo "Consultation".$result['consultationId'];
    
    $x = $con->prepare("update consults set Summary_PDF =?, length=TIMESTAMPDIFF(SECOND,DateTime,now()) where patient = ? and doctor = ? and consultationId = ?");
    $x->bindValue(1, $new_image_name, PDO::PARAM_STR);
	$x->bindValue(2, $idUsu, PDO::PARAM_INT);
	$x->bindValue(3, $userid, PDO::PARAM_INT);
	$x->bindValue(4, $result['consultationId'], PDO::PARAM_INT);
	$x->execute();	
	
    $y = $con->prepare("update lifepin set IdCreator = ?,rawimage =?,fecha = now(), fechainput = now() where idusu=? and orig_filename like '%Summary.pdf'");
	$y->bindValue(1, $userid, PDO::PARAM_STR); //Added a new line for updating the IdCreator to the id of the current doctor who updated/made changes to the summary and have kpet the IdMed as 0
    $y->bindValue(2, $new_image_name, PDO::PARAM_STR);
	$y->bindValue(3, $idUsu, PDO::PARAM_INT);
	$y->execute();	
	
}
//Log that report has been uploaded

$content = "Report Uploaded (EMR)";
$VIEWIdUser=$idUsu;
$VIEWIdMed=$userid;
$ip=$_SERVER['REMOTE_ADDR'];
$MEDIO=0;
$q;
$query = $con->prepare("select IdPin from lifepin where rawimage =?");
$query->bindValue(1, $new_image_name, PDO::PARAM_STR);
$query->execute();

$row = $query->fetch(PDO::FETCH_ASSOC);

$IdPin = $row['IdPin'];

$s = $con->prepare("INSERT INTO bpinview  SET  IDPIN =?, Content=?, DateTimeSTAMP = NOW(), VIEWIdUser = ?, VIEWIdMed = ?, VIEWIP = ?, MEDIO = ?");
$s->bindValue(1, $IdPin, PDO::PARAM_INT);
$s->bindValue(2, $content, PDO::PARAM_STR);
$s->bindValue(3, $VIEWIdUser, PDO::PARAM_INT);
$s->bindValue(4, $VIEWIdMed, PDO::PARAM_INT);
$s->bindValue(5, $ip, PDO::PARAM_STR);
$s->bindValue(6, $MEDIO, PDO::PARAM_INT);
$s->execute();	



//$pdf->Output('example_048.pdf', 'I');


//============================================================+
// END OF FILE
//============================================================+*/