<?php
/*KYLE
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

 
$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	


$idusu = $_GET['id'];
$userid=$_SESSION['MEDID'];
//$userid=28;



$usuariosquery = "select sexo,name,surname,idusfixed,idusfixedname from usuarios where identif=".$idusu;
$result1 = mysql_query($usuariosquery);
$row1 = mysql_fetch_array($result1);

$emrquery = "select DATE_FORMAT(dob,'%m-%d-%Y') as dob,address,address2,city,state,country,notes,fractures,surgeries,otherknown,obstetric,othermed,fatheralive,fathercod,fatheraod,fatherrd,motheralive,mothercod,motheraod,motherrd,siblingsrd from basicemrdata where idpatient=".$idusu;
$result2 = mysql_query($emrquery);
$row2 = mysql_fetch_array($result2);



//echo $usuariosquery;
//echo '<br>'.$emrquery;

$id=$idusu;
$name = $row1['name'].' '.$row1['surname'];
//$surname = ;
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
$query = "select name,icdcode,DATE_FORMAT(datestart,'%m-%d-%Y') as datestart,DATE_FORMAT(datestop,'%m-%d-%Y') as datestop from pastdx where idpatient=$id and del=0";
$result = mysql_query($query);
$i=1;
$table1 = '<table cellspacing="0" cellpadding="1" style="border:1px solid #cacaca" width="100%"><tr><th width="10%" align="center" style="border:1px solid #cacaca"><b>Sr.No</b></th><th width="30%" align="center" style="border:1px solid #cacaca"><b>Name</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>ICD Code</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Start Date</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Stop Date</b></th></tr>';

while($row = mysql_fetch_array($result))
{
	$col1 = '<td width="10%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$i.'</td>';
	$col2 = '<td width="30%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["name"].'</td>';
	$col3 = '<td width="20%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["icdcode"].'</td>';
	$col4 = '<td width="20%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["datestart"].'</td>';
	$col5 = '<td width="20%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["datestop"].'</td>';
	
	
	$table1 = $table1 .'<tr>'.$col1.$col2.$col3.$col4.$col5.'</tr>';
	$i++;
}
$table1 = $table1 . '</table>';


//Generating HTML for Medication
$query = "select name,drugcode,DATE_FORMAT(datestart,'%m-%d-%Y') as datestart,DATE_FORMAT(datestop,'%m-%d-%Y') as datestop,dossage,numberday from medications where idpatient=$id and del=0";
$result = mysql_query($query);
$i=1;
$table2 = '<table cellspacing="0" cellpadding="1" style="border:1px solid #cacaca" width="100%"><tr><th width="8%" align="center" style="border:1px solid #cacaca"><b>Sr.No</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Drug Name</b></th><th width="10%" align="center" style="border:1px solid #cacaca"><b>Drug Code</b></th><th width="12%" align="center" style="border:1px solid #cacaca"><b>Dossage</b></th><th width="10%" align="center" style="border:1px solid #cacaca"><b>No.per Day</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Start Date</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Stop Date</b></th></tr>';

while($row = mysql_fetch_array($result))
{
	$col1 = '<td width="8%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$i.'</td>';
	$col2 = '<td width="20%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["name"].'</td>';
	$col3 = '<td width="10%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["drugcode"].'</td>';
	$col4 = '<td width="12%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["dossage"].'</td>';
	$col5 = '<td width="10%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["numberday"].'</td>';
	$col6 = '<td width="20%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["datestart"].'</td>';
	$col7 = '<td width="20%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["datestop"].'</td>';
	
	
	
	$table2 = $table2 .'<tr>'.$col1.$col2.$col3.$col4.$col5.$col6.$col7.'</tr>';
	$i++;
}
$table2 = $table2 . '</table>';

//Generating HTML for Immunizations
$query = "select name,age,reaction,DATE_FORMAT(date,'%m-%d-%Y') as datestart from immunizations where idpatient=$id and del=0";
$result = mysql_query($query);
$i=1;
$table3 = '<table cellspacing="0" cellpadding="1" style="border:1px solid #cacaca" width="100%"><tr><th width="10%" align="center" style="border:1px solid #cacaca"><b>Sr.No</b></th><th width="30%" align="center" style="border:1px solid #cacaca"><b>Name</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Date Recorded</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Age</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Reaction</b></th></tr>';

while($row = mysql_fetch_array($result))
{
	$col1 = '<td width="10%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$i.'</td>';
	$col2 = '<td width="30%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["name"].'</td>';
	$col3 = '<td width="20%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["datestart"].'</td>';
	$col4 = '<td width="20%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["age"].'</td>';
	$col5 = '<td width="20%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["reaction"].'</td>';
	
	
	$table3 = $table3 .'<tr>'.$col1.$col2.$col3.$col4.$col5.'</tr>';
	$i++;
}
$table3 = $table3 . '</table>';


//Generating HTML for Allergies
$query = "select name,type,description,DATE_FORMAT(daterec,'%m-%d-%Y') as datestart from allergies where idpatient=$id and del=0";
$result = mysql_query($query);
$i=1;
$table4 = '<table cellspacing="0" cellpadding="1" style="border:1px solid #cacaca" width="100%"><tr><th width="10%" align="center" style="border:1px solid #cacaca"><b>Sr.No</b></th><th width="30%" align="center" style="border:1px solid #cacaca"><b>Allergy Name</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Type</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Date Recorded</b></th><th width="20%" align="center" style="border:1px solid #cacaca"><b>Description</b></th></tr>';

while($row = mysql_fetch_array($result))
{
	$col1 = '<td width="10%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$i.'</td>';
	$col2 = '<td width="30%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["name"].'</td>';
	$col3 = '<td width="20%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["type"].'</td>';
	$col4 = '<td width="20%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["datestart"].'</td>';
	$col5 = '<td width="20%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["description"].'</td>';
	
	
	$table4 = $table4 .'<tr>'.$col1.$col2.$col3.$col4.$col5.'</tr>';
	$i++;
}
$table4 = $table4 . '</table>';

$query = "select * from emr_config where userid = ".$userid;
$result = mysql_query($query);
$row = mysql_fetch_array($result);	 



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
$pdf->writeHTML('<br><br><u><b><span style="color:#3D93E0;  font-weight:bold;">EMR Report</span></b></u>', true, false, false, false, 'C');
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
		<td >        Notes : <span style="color:#3D93E0;">$notes</span> </td>
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
        <td >        Name : <span style="color:#3D93E0;">'.$name.'</span>  </td>

    </tr>
	
    <tr>
		<td width ="10%"> </td>
        <td>        Date of Birth : <span style="color:#3D93E0;">'.$dob.'</span> </td>
       
    </tr>
	<tr>
		<td width ="10%"> </td>
		<td>        Gender : <span style="color:#3D93E0;">'.$gender.'</span>  </td>
	</tr>
';

if($row['address'])
{
	$tbl=$tbl.'<tr>
		<td width ="10%"> </td>
        <td >        Address : <span style="color:#3D93E0;">'.$address.'</span> </td>
    </tr>';
}


if($row['address2'])
{
	$tbl = $tbl.'<tr>
		<td width ="10%"> </td>
        <td >        Address2 : <span style="color:#3D93E0;">'.$address2.'</span> </td>
    </tr>';
}

if($row['city'])
{
	$tbl = $tbl.'<tr>
		<td width ="10%"> </td>
        <td >        City : <span style="color:#3D93E0;">'.$city.'</span> </td>
    </tr>';
}

if($row['state'])
{
	$tbl=$tbl.'<tr>
		<td width ="10%"> </td>
        <td >        State : <span style="color:#3D93E0;">'.$state.'</span> </td>
    </tr>';
}

if($row['country'])
{
	$tbl = $tbl.'<tr>
		<td width ="10%"> </td>
        <td >        Country : <span style="color:#3D93E0;">'.$country.'</span> </td>
    </tr>';
}

if($row['notes'])
{
	$tbl = $tbl.'<tr>
		<td width ="10%"> </td>
        <td >        Notes : <span style="color:#3D93E0;">'.$notes.'</span> </td>
    </tr>';
}

$tbl = $tbl.'<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>';
	
	
if($row['personal'])
{
	$tbl = $tbl.'

	<tr>
		<td width="100%" height="30" style="margin-top:10px;border:1px solid #cacaca;margin:20px 0;-webkit-margin-before: 0.5em;-webkit-margin-after: 0.5em;-webkit-margin-start: auto;-webkit-margin-end: auto;">  <span style="color:#3D93E0; font-size:20px;"><b>Personal History : </b></span> </td>
	</tr>
    <tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>';
	
	if($row['fractures'])
	{
	$tbl=$tbl.'
	<tr>
		<td width ="5%"> </td>
        <td >        Fractures : <span style="color:#3D93E0;">'.$fractures.'</span>  </td>

    </tr>';

	}
	
	if($row['surgeries'])
	{
	$tbl=$tbl.'
	<tr>
		<td width ="5%"> </td>
        <td >        Surgeries : <span style="color:#3D93E0;">'.$surgeries.'</span>  </td>

    </tr>';
	
	}
	
	if($row['otherknown'])
	{
	$tbl=$tbl.'
	<tr>
		<td width ="5%"> </td>
        <td >        Other Known Medical Events : <span style="color:#3D93E0;">'.$otherknown.'</span>  </td>

    </tr>';
	}
	
	if($row['obstetric'])
	{
	$tbl=$tbl.'
	<tr>
		<td width ="5%"> </td>
        <td >        Obstetric History : <span style="color:#3D93E0;">'.$obstetric.'</span>  </td>

    </tr>';
	}
	
	if($row['othermed'])
	{
	$tbl=$tbl.
	'<tr>
		<td width ="5%"> </td>
        <td >        Other Medical Data : <span style="color:#3D93E0;">'.$othermed.'</span>  </td>

    </tr>';
	}
	
	$tbl=$tbl.
	'<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>';

}	


if($row['family'])
{
	$tbl=$tbl.
	'<tr>
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
        <td width="45%">        Alive : <span style="color:#3D93E0;" >'.$fatheralive.'</span> </td>
		<td width ="5%"></td>
        <td width="45%">        Alive : <span style="color:#3D93E0;" >'.$motheralive.'</span>   </td>
    </tr>
	<tr>
		<td width ="5%"></td>
        <td width="45%">        Cause of Death : <span style="color:#3D93E0;" >'.$fcod.'</span> </td>
		<td width ="5%"></td>
        <td width="45%">        Cause of Death : <span style="color:#3D93E0;" >'.$mcod.'</span>   </td>
    </tr>
	<tr>
		<td width ="5%"></td>
        <td width="45%">        Age of Death : <span style="color:#3D93E0;" >'.$faod.'</span> </td>
		<td width ="5%"></td>
        <td width="45%">        Age of Death : <span style="color:#3D93E0;" >'.$maod.'</span>   </td>
    </tr>
	<tr>
		<td width ="5%"></td>
        <td width="45%">        Relevant Diseases : <span style="color:#3D93E0;" >'.$frd.'</span> </td>
		<td width ="5%"></td>
        <td width="45%">        Relevant Diseases : <span style="color:#3D93E0;" >'.$mrd.'</span>   </td>
    </tr>
	<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>';
	
	if($row['siblings'])
	{
	$tbl=$tbl.
	
	'<tr>
		<td width="5%"></td>
		<td> <span style="color:#3D93E0;font-size:18px;" ><b>Siblings :</b></span> </td>
	</tr>
	<tr >
		<td height="-1"></td>
		<td height="-1"></td>
	</tr>
	<tr>
		<td width="5%"></td>
		<td>        Relevant Diseases : <span style="color:#3D93E0;" >'.$siblingsrd.'</span> </td>
	</tr>';
	
	}
	
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
*//*KYLE
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


//Get the Encryption Password
$enc_result = mysql_query("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$row_enc = mysql_fetch_array($enc_result);
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

//Encrypt the thumbnail
shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in PackagesTH_Encrypted/".$new_image_nameTH." -out temp/".$new_image_nameTH);
    shell_exec("rm PackagesTH_Encrypted/".$new_image_nameTH);
    shell_exec("cp temp/".$new_image_nameTH." PackagesTH_Encrypted/");
    shell_exec("rm temp/".$new_image_nameTH);
    
    shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in Packages_Encrypted/".$new_image_name." -out temp/".$new_image_name);
    shell_exec("rm Packages_Encrypted/".$new_image_name);
    shell_exec("cp temp/".$new_image_name." Packages_Encrypted/");
    shell_exec("rm temp/".$new_image_name);     					
$tipo=60;  //for demographics
$query = "insert into lifepin(idusu,rawimage,fechainput,tipo,canal,needaction,idusfixed,idusfixedname,idmed,idcreator,creatortype,fs,a_s,vs,checksum,orig_filename,emr_report,fecha) values(".$idusu.",'".$new_image_name."',now(),".$tipo.",9,0,".$row1['idusfixed'].",'".$row1['idusfixedname']."',".$userid.','.$userid.",1,0,0,1,'".$checksum."','".$row1['name']."_".$row1['surname']."_EMR.pdf',1,now())";
//echo $query;
mysql_query($query);


$query = "select max(idpin) as idpin from lifepin";
$result = mysql_query($query);
$row =  mysql_fetch_array($result);
	
//Log that report has been uploaded
$IdPin=$row['idpin'];
$content = "Report Uploaded (EMR)";
$VIEWIdUser=$idusu;
$VIEWIdMed=$userid;
$ip=$_SERVER['REMOTE_ADDR'];
$MEDIO=0;
$q = mysql_query("INSERT INTO bpinview  SET  IDPIN ='$IdPin', Content='$content', DateTimeSTAMP = NOW(), VIEWIdUser = '$VIEWIdUser', VIEWIdMed = '$VIEWIdMed', VIEWIP = '$ip', MEDIO = '$MEDIO' ");
	
	



//$pdf->Output('example_048.pdf', 'I');


//============================================================+
// END OF FILE
//============================================================+*/