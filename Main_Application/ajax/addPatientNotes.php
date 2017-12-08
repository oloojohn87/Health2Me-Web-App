<?php
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

$doctorId = $_POST['doctorId'];
$userId = $_POST['userId'];
$findings = $_POST['findings'];
$recommendations = $_POST['recommendations'];
//$date = $_POST['date'];


echo $doctorId.' '.$userId.' '.$findings.$recommendations;
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'notes_leaf.png';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, 'Remote Medical Interview', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

}

//find the consultation id
$query = $con->prepare("select max(consultationId) as consultationId,max(datetime) as datetime from consults where patient = ? and doctor = ?");
$query->bindValue(1, $userId, PDO::PARAM_INT);
$query->bindValue(2, $doctorId, PDO::PARAM_INT);
$query->execute();

$result = $query->fetch(PDO::FETCH_ASSOC);
$consultationId = $result['consultationId'];
$consultationDate = $result['datetime'];

//find the doctor name
$query = $con->prepare("select name,surname from doctors where id = ?");
$query->bindValue(1, $doctorId, PDO::PARAM_INT);
$query->execute();

$result = $query->fetch(PDO::FETCH_ASSOC);
$doctorName = "Dr. ".$result['name']." ".$result['surname'];

//find the patient name
$query = $con->prepare("select Name,Surname from usuarios where Identif = ?");
$query->bindValue(1, $userId, PDO::PARAM_INT);
$query->execute();

$result = $query->fetch(PDO::FETCH_ASSOC);
$patientName = $result['Name']." ".$result['Surname'];


//ADD ABILITY TO USE PATIENT IMAGE
$patientImage='"PatientImage/'.$userId.'.jpg"';
if (file_exists($patientImage)) {
    $patientImage = '<img src='.$patientImage.' alt="patient image">';
} else {
    $patientImage = '<img src="PatientImage/defaultDP.jpg" alt="patient image">';
} 

$findings = str_replace("\n", "<br/>", $findings);
$recommendations = str_replace("\n", "<br/>", $recommendations);
$findings = str_replace('"', "", $findings);
$recommendations = str_replace('"', "", $recommendations);
$findings = str_replace("'", "", $findings);
$recommendations = str_replace("'", "", $recommendations);

$content = file_get_contents("templates/notes_template_embedded.html");
$content = str_replace("**consultation_id**",$consultationId,$content);
$content = str_replace("**ConsultationDate**",$consultationDate,$content);
$content = str_replace("**patient_name**",$patientName,$content);
$content = str_replace("**findings_content**",trim($findings,'"\'"'),$content);
$content = str_replace("**recomm_content**",trim($recommendations,'"\'"'),$content);
$content = str_replace("**dr_name**",$doctorName,$content);
$content = str_replace("**patient_image**",$patientImage,$content);


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
$pdf->setPrintHeader(false);
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


$pdf->AddPage();
//$tbl = $table1;
$pdf->SetFont('helvetica', 'B', 18);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->writeHTML($content, true, false, true, false, '');


//Get the Encryption Password
$enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$enc_result->execute();

$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
$enc_pass=$row_enc['pass'];

$usuariosquery = $con->prepare("select sexo,name,surname,idusfixed,idusfixedname from usuarios where identif= ?");
$usuariosquery->bindValue(1, $userId, PDO::PARAM_INT);
$usuariosquery->execute();

$row1 = $usuariosquery->fetch(PDO::FETCH_ASSOC);

$extension='pdf';
$confCode = $row1['idusfixed'].md5(date('Ymdgisu'));
$new_image_name = 'eML'.$confCode.'.'.$extension;						
$new_image_nameTH = 'eML'.$confCode.'.png';	

$locFile = "Packages_Encrypted";  
$locFileTH = "PackagesTH_Encrypted";    
$ds = DIRECTORY_SEPARATOR;  
$checksum=md5_file($locFile.$ds.$new_image_name);
$path="C:\\PROGRA~2\\ImageMagick-6.8.1-Q16\\";


// use the font
//$pdf->SetFont($fontname, '', 14, '', false);

//Generate the Report
$pdf->Output($locFile.$ds.$new_image_name,'F');

//Generate Thumbnail
$cadenaConvert = 'convert "'.$locFile.$ds.$new_image_name.'[0]" -colorspace RGB -geometry 200 "'.$locFileTH.$ds.$new_image_nameTH.'" 2>&1';
$output = shell_exec($cadenaConvert);  

copy($locFile.$ds.$new_image_name, 'temp'.$ds.$userid.$ds.'Packages_Encrypted'.$ds.$new_image_name);
copy($locFileTH.$ds.$new_image_nameTH, 'temp'.$ds.$userid.$ds.'PackagesTH_Encrypted'.$ds.$new_image_nameTH);



//Encrypt the thumbnail
shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in PackagesTH_Encrypted/".$new_image_nameTH." -out temp/".$new_image_nameTH);
    shell_exec("rm PackagesTH_Encrypted/".$new_image_nameTH);
    shell_exec("cp temp/".$new_image_nameTH." PackagesTH_Encrypted/");
    shell_exec("rm temp/".$new_image_nameTH);
    
    shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in Packages_Encrypted/".$new_image_name." -out temp/".$new_image_name);
    shell_exec("rm Packages_Encrypted/".$new_image_name);
    shell_exec("cp temp/".$new_image_name." Packages_Encrypted/");
    shell_exec("rm temp/".$new_image_name);        					



$check = $con->prepare("select * from lifepin where rawimage=?");
$check->bindValue(1, $new_image_name, PDO::PARAM_STR);
$check->execute();

$num_rows = $check->rowCount();

if($num_rows==0)
{
	$tipo=60;  //for demographics
	$query = $con->prepare("insert into lifepin(idusu,rawimage,fechainput,tipo,canal,needaction,idusfixed,idusfixedname,idmed,idcreator,creatortype,fs,a_s,vs,checksum,orig_filename,emr_report,fecha) 
	values(?,?,now(),?,9,0,?,?,?,?,1,0,0,1,?,?,1,now())");
	$query->bindValue(1, $userId, PDO::PARAM_INT);
	$query->bindValue(2, $new_image_name, PDO::PARAM_STR);
	$query->bindValue(3, $tipo, PDO::PARAM_INT);
	$query->bindValue(4, $row1['idusfixed'], PDO::PARAM_INT);
	$query->bindValue(5, $row1['idusfixedname'], PDO::PARAM_STR);
	$query->bindValue(6, $doctorId, PDO::PARAM_INT);
	$query->bindValue(7, $doctorId, PDO::PARAM_INT);
	$query->bindValue(8, $checksum, PDO::PARAM_STR);
	$query->bindValue(9, $row1['name']."_".$row1['surname']."_EVOLUTION.pdf", PDO::PARAM_STR);
	$query->execute();


	$query = $con->prepare("select max(idpin) as idpin from lifepin");
	$query->execute();
	$row = $query->fetch(PDO::FETCH_ASSOC);
		
	//Log that report has been uploaded/created
	$IdPin=$row['idpin'];
	$content = "Note created by doctor:".$doctorId." for patient:".$userId;
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
    
    //Finding the exact consultation row in consults table where we have enter the notes name
    $query1 = $con->prepare("select in_consultation from doctors where id = ?");
	$query1->bindValue(1, $doctorId, PDO::PARAM_INT);
	$query1->execute();
	
    $result1 = $query1->fetch(PDO::FETCH_ASSOC);
    $in_consultation = $result1['in_consultation'];
    
    $query2 = $con->prepare("select status,max(consultationId) as consultation from consults where doctor=? and patient=?");
	$query2->bindValue(1, $doctorId, PDO::PARAM_INT);
	$query2->bindValue(2, $userId, PDO::PARAM_INT);
	$query2->execute();
	
    $result2 = $query2->fetch(PDO::FETCH_ASSOC);
    $consultationId = $result2['consultation'];
    $status = "";
    $status = $result2['status'];
    
    //if(/*($in_consultation == 1) && */$status == 'completed' ) //commented out $in_consultation aand making the consultation generic 
    {
     $query3 = $con->prepare("update consults set data_file =? where consultationId= ?");
	 $query3->bindValue(1, $new_image_name, PDO::PARAM_STR);
	 $query3->bindValue(2, $consultationId, PDO::PARAM_INT);
	 $query3->execute();
    }
       
    
	
}

?>