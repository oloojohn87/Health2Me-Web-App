<?php

session_start();

// Include the main TCPDF library 
require_once('tcpdf/tcpdf.php');
require("environment_detail.php");
error_reporting(E_ERROR | E_PARSE);
set_time_limit(120);

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

$id=$_GET['entryid'];

$idusu = $_GET['idusu'];
$userid=$_SESSION['MEDID'];
//$userid=28;

//$id=19;


$usuariosquery = $con->prepare("select sexo,name,surname,idusfixed,idusfixedname from usuarios where identif=?");
$usuariosquery->bindValue(1, $idusu, PDO::PARAM_INT);
$result1 = $usuariosquery->execute();


$row1 = $usuariosquery->fetch(PDO::FETCH_ASSOC);

$result = $con->prepare("SELECT JSON_DATA from clear_allergy_templates_data where Id=?");
$result->bindValue(1, $id, PDO::PARAM_INT);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

$json=$row['JSON_DATA'];
//var_dump($json);

}

$obj = json_decode($json,true);

//var_dump($obj);


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 048');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
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

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', 'B', 20);

// add a page
$pdf->AddPage();

$pdf->Write(0, 'Patient Encounter Form', '', 0, 'L', true, 0, false, false, 0);

$pdf->SetFont('helvetica', '', 10);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *

IMPORTANT:
If you are printing user-generated content, tcpdf tag can be unsafe.
You can disable this tag by setting to false the K_TCPDF_CALLS_IN_HTML
constant on TCPDF configuration file.

For security reasons, the parameters for the 'params' attribute of TCPDF
tag must be prepared as an array and encoded with the
serializeTCPDFtagParameters() method (see the example below).

 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

//$PatEnclst1=$obj["PatEnclst1"];

$PatEncDate=$obj["PatEncDate"];
$Pre_PatEncTemp = $obj["Pre_PatEncTemp"];
$Pre_PatEncBldPressure = $obj["Pre_PatEncBldPressure"];
$Pre_PatEncBldPulse=$obj["Pre_PatEncBldPulse"];
$Pre_PatEncBldRes=$obj["Pre_PatEncBldRes"];
$PatEncTolPrcopt=$obj["PatEncTolPrcopt"];
			
$PatEnclst1=$obj["PatEnclst1"];
$PatEnclst2=$obj["PatEnclst2"];
$PatEnclst2= $obj["PatEnclst3"];
$PatEnclst4=$obj["PatEnclst4"];
$PatEnclst5=$obj["PatEnclst5"];
$PatEnclst6=$obj["PatEnclst6"];
			
$PatEncLoc1=$obj["PatEncLoc1"];
$PatEncLoc2=$obj["PatEncLoc2"];
$PatEncLoc3=$obj["PatEncLoc3"];
$PatEncLoc4=$obj["PatEncLoc4"];
$PatEncLoc5=$obj["PatEncLoc5"];
$PatEncLoc6=$obj["PatEncLoc6"];
			
$PatEncRea1=$obj["PatEncRea1"];
$PatEncRea2=$obj["PatEncRea2"];
$PatEncRea3=$obj["PatEncRea3"];
$PatEncRea4=$obj["PatEncRea4"];
$PatEncRea5=$obj["PatEncRea5"];
$PatEncRea6=$obj["PatEncRea6"];

$PatEncTolPrcopt=$obj["PatEncTolPrcopt"];
$PatEncTolPrc=$obj["PatEncTolPrc"];
			
$PatEncavdstr=$obj["PatEncavdstr"];
$PatEncvoiceopt=$obj["PatEncvoiceopt"];
$PatEncvoice=$obj["PatEncvoice"];
			
$Post_PatEncTemp=$obj["Post_PatEncTemp"];
$post_patencbp=$obj["post_patencbp"];
$post_patencpul=$obj["post_patencpul"];
$post_patencres=$obj["post_patencres"];

//echo $text;

//$text="erereressa";

$tbl = <<<EOD

<p style="font-size:13px; font-weight:bold;"> DATE:  $PatEncDate </p>

<table cellspacing="0" cellpadding="1" border="1" style="margin-top:30px">
	
    <tr height="300">
			<td>
				<p align="center" style="margin:10px;"> <b> Pre-Test VITAL SIGNS: </b></p>
				<p style="margin-left:10px;">Temperature: $Pre_PatEncTemp </p>
				<p style="margin-left:10px;">Blood Pressure: $Pre_PatEncBldPressure </p>
				<p style="margin-left:10px;">Pulse: $Pre_PatEncBldPulse </p>
				<p style="margin-left:10px;">Respirations: $Pre_PatEncBldRes </p>
			</td>
			
			 <td>
				<p align="center" style="margin:10px;"> <b> TYPE OF TESTING ORDERED: </b></p>
				$PatEnctypeOrd
			</td>
			
			<td>
				<p align="center" style="margin:10px;"> <b> LIST TESTING COMPLETED: </b></p>
				<p style="margin-left:10px;">1 $PatEnclst1 </p>
				<p style="margin-left:10px;">2 $PatEnclst2 </p>
				<p style="margin-left:10px;">3 $PatEnclst3 </p>
				<p style="margin-left:10px;">4 $PatEnclst4 </p>
				<p style="margin-left:10px;">5 $PatEnclst5 </p>
				<p style="margin-left:10px;">6 $PatEnclst6 </p>
				
			</td>
			
			
	</tr>
	 <tr height="300">
			<td>
											<p align="center" style="margin:10px;"> <b> BODY LOCATION OF TEST COMPLETED: </b></p>
											<p style="margin-left:10px;">1 $PatEncLoc1 </p>
											<p style="margin-left:10px;">2 $PatEncLoc2 </p>
											<p style="margin-left:10px;">3 $PatEncLoc3 </p>
											<p style="margin-left:10px;">4 $PatEncLoc4 </p>
											<p style="margin-left:10px;">5 $PatEncLoc5 </p>
											<p style="margin-left:10px;">6 $PatEncLoc6 </p>
			</td>
			
			 <td>
											<p align="center" style="margin:10px;"> <b> PATIENTS TEST REACTION: </b></p>
											<p style="margin-left:10px;">1 $PatEncRea1 </p>
											<p style="margin-left:10px;">2 $PatEncRea2 </p>
											<p style="margin-left:10px;">3 $PatEncRea3 </p>
											<p style="margin-left:10px;">4 $PatEncRea4 </p>
											<p style="margin-left:10px;">5 $PatEncRea5 </p>
											<p style="margin-left:10px;">6 $PatEncRea6 </p>
			</td>
			
			<td>
											<p align="center" style="margin:10px;"> <b> DID PATIENT TOLERATE PROCEDURE WELL: </b></p>
											<p style="margin-left:10px;">
												$PatEncTolPrcopt												 
											</p>
											<p style="margin-left:10px;">If NO, please explain
												$PatEncTolPrc
											</p>
				
			</td>
			
			
	</tr>
	 <tr height="300">
	 
									<td>
											<p align="center" style="margin:10px;"> <b> PATIENT INSTRUCTIONS GIVEN FOR AVOIDANCE STRATAGIES? </b></p>
											<p style="margin-left:10px;">
												$PatEncavdstr
											</p>
											
																		
									</td>
									<td>
											<p align="center" style="margin:10px;"> <b>DID PATIENT VOICE ANY QUIESTIONS OR CONCERNS? </b></p>
											<p style="margin-left:10px;">
												$PatEncvoiceopt
											</p>
											
											<p style="margin-left:10px;">EXPLAIN: 
												$PatEncvoice
											</p>
											
																		
									</td>
									<td>
											<p align="center" style="margin:10px;"> <b> Post-Test VITAL SIGNS: </b></p>
											
											
											<p style="margin-left:10px;">Temperature: $Post_PatEncTemp </p>
											<p style="margin-left:10px;">Blood Pressure: $post_patencbp </p>
											<p style="margin-left:10px;">Pulse:	$post_patencpul </p>
											<p style="margin-left:10px;">Respirations: $post_patencres </p>
																		
									</td>
			
	</tr>
  
 
</table>
EOD;


//$html .= '<tcpdf method="AddPage" /><h2>Graphic Functions</h2>';

/*$params = TCPDF_STATIC::serializeTCPDFtagParameters(array(0));
$html .= '<tcpdf method="SetDrawColor" params="'.$params.'" />';

$params = TCPDF_STATIC::serializeTCPDFtagParameters(array(50, 50, 40, 10, 'DF', array(), array(0,128,255)));
$html .= '<tcpdf method="Rect" params="'.$params.'" />';
*/

// output the HTML content
//$pdf->writeHTML($html, true, 0, true, 0);
$pdf->writeHTML($tbl, true, false, false, false, '');
// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// reset pointer to the last page
$pdf->lastPage();

/* Code for the PDF encryption and creating a lifepin entry for the patientID */
// ---------------------------------------------------------
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

//Encrypt the thumbnail
shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in PackagesTH_Encrypted/".$new_image_nameTH." -out temp/".$new_image_nameTH);
    shell_exec("rm PackagesTH_Encrypted/".$new_image_nameTH);
    shell_exec("cp temp/".$new_image_nameTH." PackagesTH_Encrypted/");
    shell_exec("rm temp/".$new_image_nameTH);
    
    shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in Packages_Encrypted/".$new_image_name." -out temp/".$new_image_name);
    shell_exec("rm Packages_Encrypted/".$new_image_name);
    shell_exec("cp temp/".$new_image_name." Packages_Encrypted/");
    shell_exec("rm temp/".$new_image_name);

$tipo=60;  //Assuming same number for clear Allergy as EMR reports. Need to confirm with Javier *******
$query = $con->prepare("insert into lifepin(idusu,rawimage,fechainput,tipo,canal,needaction,idusfixed,idusfixedname,idmed,idcreator,creatortype,fs,a_s,vs,checksum,orig_filename,emr_report,fecha) 
values(?,?,now(),?,9,0,?,?,?,?,1,0,0,1,?,?,1,now())");
$query->bindValue(1, $idusu, PDO::PARAM_INT);
$query->bindValue(2, $new_image_name, PDO::PARAM_STR);
$query->bindValue(3, $tipo, PDO::PARAM_INT);
$query->bindValue(4, $row1['idusfixed'], PDO::PARAM_INT);
$query->bindValue(5, $row1['idusfixedname'], PDO::PARAM_STR);
$query->bindValue(6, $userid, PDO::PARAM_INT);
$query->bindValue(7, $userid, PDO::PARAM_INT);
$query->bindValue(8, $checksum, PDO::PARAM_STR);
$query->bindValue(9, $row1['name']."_".$row1['surname']."_clearAllergy.pdf", PDO::PARAM_STR);
$query->execute();



$query = $con->prepare("select max(idpin) as idpin from lifepin");
$query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);
	
//Log that report has been uploaded
$IdPin=$row['idpin'];
$content = "Report Uploaded (clearAllergy)";
$VIEWIdUser=$idusu;
$VIEWIdMed=$userid;
$ip=$_SERVER['REMOTE_ADDR'];
$MEDIO=0;
$q = $con->prepare("INSERT INTO bpinview  SET  IDPIN =?, Content=?, DateTimeSTAMP = NOW(), VIEWIdUser = ?, VIEWIdMed = ?, VIEWIP = ?, MEDIO = ? ");
$q->bindValue(1, $IdPin, PDO::PARAM_INT);
$q->bindValue(2, $content, PDO::PARAM_STR);
$q->bindValue(3, $VIEWIdUser, PDO::PARAM_INT);
$q->bindValue(4, $VIEWIdMed, PDO::PARAM_INT);
$q->bindValue(5, $ip, PDO::PARAM_STR);
$q->bindValue(6, $MEDIO, PDO::PARAM_INT);
$q->execute();


//Close and output PDF document
//$pdf->Output('clearAllergy/PDF/clear_allergy.pdf', 'F');


//============================================================+
// END OF FILE
//============================================================+*/

?>