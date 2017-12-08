<?php

session_start();

// Include the main TCPDF library 
require_once('tcpdf/tcpdf.php');
require("environment_detailForLogin.php");
error_reporting(E_ERROR | E_PARSE);
set_time_limit(60);

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			

$probe = $_GET['probe'];

$table1 = '';

$prb = $con->prepare("SELECT PRB.probeID, PRB.protocolID, PRB.doctorID, PRB.patientID, PRT.question1, PRT.question2, PRT.question3, PRT.question4, PRT.question5  FROM probe AS PRB LEFT JOIN probe_protocols AS PRT ON PRB.protocolID = PRT.protocolID WHERE PRB.probeID = ?");
$prb->bindValue(1, $probe, PDO::PARAM_INT);
$prb->execute();
$probe_row = $prb->fetch(PDO::FETCH_ASSOC);

$pat = $probe_row['patientID'];
if(isset($_GET['pat']))
{
    $pat = $_GET['pat'];
}
$doc = $probe_row['doctorID'];
if(isset($_GET['doc']))
{
    $doc = $_GET['doc'];
}

$question1 = 0;
$question2 = 0;
$question3 = 0;
$question4 = 0;
$question5 = 0;
$questionID = array();
if($probe_row['question1'] != NULL)
{
    $question1 = $probe_row['question1'];
    $questionID[$question1] = 0;
}
if($probe_row['question2'] != NULL)
{
    $question2 = $probe_row['question2'];
    $questionID[$question2] = 1;
}
if($probe_row['question3'] != NULL)
{
    $question3 = $probe_row['question3'];
    $questionID[$question3] = 2;
}
if($probe_row['question4'] != NULL)
{
    $question4 = $probe_row['question4'];
    $questionID[$question4] = 3;
}
if($probe_row['question5'] != NULL)
{
    $question5 = $probe_row['question5'];
    $questionID[$question5] = 4;
}

$questions = array('', '' ,'' ,'', '');
$question_unit = array('', '', '', '', '');
$question_units = array(array(), array(), array(), array(), array());

$units = $con->prepare("SELECT * FROM probe_units WHERE probe_question IN (?, ?, ?, ?, ?) ORDER BY probe_question, value");
$units->bindValue(1, $question1, PDO::PARAM_INT);
$units->bindValue(2, $question2, PDO::PARAM_INT);
$units->bindValue(3, $question3, PDO::PARAM_INT);
$units->bindValue(4, $question4, PDO::PARAM_INT);
$units->bindValue(5, $question5, PDO::PARAM_INT);
$units->execute();
while($row = $units->fetch(PDO::FETCH_ASSOC))
{
    array_push($question_units[$questionID[$row['probe_question']]], array("label" => $row['label'], "value" => $row['value']));
}

$unit = $con->prepare("SELECT id, unit, question_text FROM probe_questions WHERE id IN (?, ?, ?, ?, ?) ORDER BY id");
$unit->bindValue(1, $question1, PDO::PARAM_INT);
$unit->bindValue(2, $question2, PDO::PARAM_INT);
$unit->bindValue(3, $question3, PDO::PARAM_INT);
$unit->bindValue(4, $question4, PDO::PARAM_INT);
$unit->bindValue(5, $question5, PDO::PARAM_INT);
$unit->execute();
while($row = $unit->fetch(PDO::FETCH_ASSOC))
{
    if($row['unit'] != NULL && strlen($row['unit']) > 0)
        $question_unit[$questionID[$row['id']]] = ' '.$row['unit'];
    if($row['question_text'] != NULL && strlen($row['question_text']) > 0)
        $questions[$questionID[$row['id']]] = $row['question_text'];
}

$get = $con->prepare("SELECT * FROM proberesponse WHERE probeID = ? ORDER BY question ASC, responseTime DESC");
$get->bindValue(1, $probe, PDO::PARAM_INT);
$get->execute();

$last_question = -1;
while($row = $get->fetch(PDO::FETCH_ASSOC))
{
    $date = date('M j, Y g:i A', strtotime($row['responseTime']));
    $response = $row['response'];
    $question = 0;
    if($row['question'] != NULL)
    {
        $question = $row['question'] - 1;
    }
    if($last_question != $question)
    {
        if(strlen($table1) > 0)
        {
            $table1 .= '</table>';
        }
        $table1 .= '<h3>Question '.strval($question + 1).': '.$questions[$question].'</h3>';
        $table1 .= '<table cellspacing="0" cellpadding="1" style="border:1px solid #cacaca" width="100%"><tr><th width="50%" align="center" style="border:1px solid #cacaca"><b>Date</b></th><th width="50%" align="center" style="border:1px solid #cacaca"><b>Response</b></th></tr>';
        $last_question = $question;
    }
    $count = count($question_units[$question]);
    if($count > 0)
    {
        for($i = 0; $i < $count; $i++)
        {
            if($question_units[$question][$i]['value'] >= $response)
            {
                $response .= ' ('.$question_units[$question][$i]['label'].')';
                break;
            }
        }
    }
    else
    {
        $response .= $question_unit[$question];
    }
    $col1 = '<td width="50%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$date.'</td>';
	$col2 = '<td width="50%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$response.'</td>';
    $table1 .= '<tr>'.$col1.$col2.'</tr>';
}

$table1 .= '</table>';

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Health2Me');
$pdf->SetTitle('Probe');
$pdf->SetSubject('Probe');
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


$pdf->AddPage();
//$tbl = $table1;
$pdf->SetFont('helvetica', 'B', 18);
$pdf->writeHTML('<br><br><span style="color:#3D93E0; font-size:20px;"><b>Probe Responses : </span></b>', true, false, false, false, 'L');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->writeHTML($table1, true, false, false, false, '');

//Get the Encryption Password
$enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$enc_result->execute();

$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
$enc_pass=$row_enc['pass'];

$usuariosquery = $con->prepare("select sexo,name,surname,idusfixed,idusfixedname from usuarios where identif=?");
$usuariosquery->bindValue(1, $pac, PDO::PARAM_INT);
$result1 = $usuariosquery->execute();

$row1 = $usuariosquery->fetch(PDO::FETCH_ASSOC);

$extension='pdf';
$new_image_name = 'Probe_'.$probe.'.'.$extension;						
$new_image_nameTH = 'Probe_'.$probe.'.png';	

$locFile = "Packages_Encrypted";  
$locFileTH = "PackagesTH_Encrypted";    
$ds = DIRECTORY_SEPARATOR;  

//Generate the Report
$check = $pdf->Output($locFile.$ds.$new_image_name,'F');
$checksum=md5_file($locFile.$ds.$new_image_name);
$path="C:\\PROGRA~2\\ImageMagick-6.8.1-Q16\\";

//Generate Thumbnails
$cadenaConvert = 'convert "'.$locFile.$ds.$new_image_name.'[0]" -colorspace RGB -geometry 200 "'.$locFileTH.$ds.$new_image_nameTH.'" 2>&1';
$output = shell_exec($cadenaConvert);

$initial_file_dump = $locFile.$ds.$new_image_name;
$temp_dir = 'temp'.$ds.'Packages_Encrypted'.$ds.$new_image_name;

if(file_exists('temp'.$ds.$pat.$ds.'Packages_Encrypted'.$ds.$new_image_name))
{
    shell_exec("rm temp".$ds.$pat.$ds."Packages_Encrypted".$ds.$new_image_name);
    copy($initial_file_dump, 'temp'.$ds.$pat.$ds.'Packages_Encrypted'.$ds.$new_image_name);
}
else if(file_exists('temp'.$ds.$pat.$ds.'Packages_Encrypted'))
{
    copy($initial_file_dump, 'temp'.$ds.$pat.$ds.'Packages_Encrypted'.$ds.$new_image_name);
}
if(file_exists('temp'.$ds.$doc.$ds.'Packages_Encrypted'.$ds.$new_image_name))
{
    shell_exec("rm temp".$ds.$doc.$ds."Packages_Encrypted".$ds.$new_image_name);
    copy($initial_file_dump, 'temp'.$ds.$doc.$ds.'Packages_Encrypted'.$ds.$new_image_name);
}
else if(file_exists('temp'.$ds.$doc.$ds.'Packages_Encrypted'))
{
    copy($initial_file_dump, 'temp'.$ds.$doc.$ds.'Packages_Encrypted'.$ds.$new_image_name);
}
if(file_exists('temp'.$ds.$pat.$ds.'PackagesTH_Encrypted'.$ds.$new_image_nameTH))
{
    shell_exec("rm temp".$ds.$pat.$ds."PackagesTH_Encrypted".$ds.$new_image_nameTH);
    copy($locFileTH.$ds.$new_image_nameTH, 'temp'.$ds.$pat.$ds.'PackagesTH_Encrypted'.$ds.$new_image_nameTH);
}
else if(file_exists('temp'.$ds.$pat.$ds.'PackagesTH_Encrypted'))
{
    copy($locFileTH.$ds.$new_image_nameTH, 'temp'.$ds.$pat.$ds.'PackagesTH_Encrypted'.$ds.$new_image_nameTH);
}
if(file_exists('temp'.$ds.$doc.$ds.'PackagesTH_Encrypted'.$ds.$new_image_nameTH))
{
    shell_exec("rm temp".$ds.$doc.$ds."PackagesTH_Encrypted".$ds.$new_image_nameTH);
    copy($locFileTH.$ds.$new_image_nameTH, 'temp'.$ds.$doc.$ds.'PackagesTH_Encrypted'.$ds.$new_image_nameTH);
}
else if(file_exists('temp'.$ds.$doc.$ds.'PackagesTH_Encrypted'))
{
    copy($locFileTH.$ds.$new_image_nameTH, 'temp'.$ds.$doc.$ds.'PackagesTH_Encrypted'.$ds.$new_image_nameTH);
}

//Encrypt the thumbnail
shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in PackagesTH_Encrypted/".$new_image_nameTH." -out temp/".$new_image_nameTH);
shell_exec("rm PackagesTH_Encrypted/".$new_image_nameTH);
shell_exec("cp temp/".$new_image_nameTH." PackagesTH_Encrypted/");
shell_exec("rm temp/".$new_image_nameTH);

shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in Packages_Encrypted/".$new_image_name." -out temp/".$new_image_name);
shell_exec("rm Packages_Encrypted/".$new_image_name);
shell_exec("cp temp/".$new_image_name." Packages_Encrypted/");
shell_exec("chmod 777 Packages_Encrypted/".$new_image_name);
shell_exec("rm temp/".$new_image_name);


$check = $con->prepare("select * from lifepin where rawimage=?");
$check->bindValue(1, $new_image_name, PDO::PARAM_STR);
$check_result=$check->execute();

$num_rows = $check->rowCount();

if($num_rows==0)
{
	$tipo=60;  //for demographics
	$query = $con->prepare("insert into lifepin(idusu,rawimage,fechainput,tipo,canal,needaction,idusfixed,idusfixedname,idmed,idcreator,creatortype,fs,a_s,vs,checksum,orig_filename,emr_report,fecha)
	values(?,?,now(),?,9,0,?,?,?,?,1,0,0,1,?,?,1,now())");
	$query->bindValue(1, $pat, PDO::PARAM_INT);
	$query->bindValue(2, $new_image_name, PDO::PARAM_STR);
	$query->bindValue(3, $tipo, PDO::PARAM_INT);
	$query->bindValue(4, $row1['idusfixed'], PDO::PARAM_INT);
	$query->bindValue(5, $row1['idusfixedname'], PDO::PARAM_STR);
	$query->bindValue(6, $doc, PDO::PARAM_INT);
	$query->bindValue(7, $pat, PDO::PARAM_INT);
	$query->bindValue(8, $checksum, PDO::PARAM_STR);
	$query->bindValue(9, "Probe_".$probe.".pdf", PDO::PARAM_STR);
	$query->execute();

    
	//$query = $con->prepare("select max(IdPin) as idpin from lifepin");
	//$result = $query->execute();
	//$row = $query->fetch(PDO::FETCH_ASSOC);
		
	//Log that report has been uploaded/created
	$IdPin = $con->lastInsertId();
	$content = "Evolution Report Created";
	$VIEWIdUser = $pat;
	$VIEWIdMed = doc;
	$ip = $_SERVER['REMOTE_ADDR'];
	$MEDIO = 0;
	$q = $con->prepare("INSERT INTO bpinview  SET  IDPIN =?, Content=?, DateTimeSTAMP = NOW(), VIEWIdUser = ?, VIEWIdMed = ?, VIEWIP = ?, MEDIO = ? ");
	$q->bindValue(1, $IdPin, PDO::PARAM_INT);
	$q->bindValue(2, $content, PDO::PARAM_STR);
	$q->bindValue(3, $VIEWIdUser, PDO::PARAM_INT);
	$q->bindValue(4, $VIEWIdMed, PDO::PARAM_INT);
	$q->bindValue(5, $ip, PDO::PARAM_STR);
	$q->bindValue(6, $MEDIO, PDO::PARAM_INT);
	$q->execute();
	
}
else
{
	$row = $check->fetch(PDO::FETCH_ASSOC);
	//Log that report has been uploaded/created
    
    $update_checksum = $con->prepare("UPDATE lifepin SET checksum = ? WHERE IdPin = ?");
    $update_checksum->bindValue(1, $checksum, PDO::PARAM_STR);
    $update_checksum->bindValue(2, $row['IdPin'], PDO::PARAM_INT);
    $update_checksum->execute();
    
	$IdPin = $row['IdPin'];
	$content = "Evolution Report Modified";
	$VIEWIdUser = $pat;
	$VIEWIdMed = $doc;
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



}
