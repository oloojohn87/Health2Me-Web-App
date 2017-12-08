<?php
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
        $image_file = K_PATH_IMAGES.'remote_medical_user.jpg';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        //$this->SetFont('Varela Round', 'B', 20);
        // Title
        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

}


$query = mysql_query("select max(consultationId) as consultationId,datetime from consults where patient = $userId and doctor = $doctorId");
$result = mysql_fetch_array($query);
$consultationId = $result['consultationId'];
$consultationDate = $result['datetime'];

$query = mysql_query("select name,surname from doctors where id =".$doctorId);
$result = mysql_fetch_array($query);
$doctorName = $result['name']." ".$result['surname'];


$patientImage='PatientImage/'.$userId.'.jpg';
if (file_exists($patientImage)) {
    $patientImage = '<img src='.$patientImage.' alt="patient image">';
} else {
    $patientImage = '<img src='.'PatientImage/defaultDP.jpg'.' alt="patient image">';
}   

$content = file_get_contents($domain."/templates/notes_template_embedded.html");
$content = str_replace("**consultation_id**",$consultationId,$content);
$content = str_replace("**ConsultationDate**",$consultationDate,$content);
$content = str_replace("**patient_name**",$patientName,$content);
$content = str_replace("**findings_content**",$findings,$content);
$content = str_replace("**recomm_content**",$recommendations,$content);
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
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
//$pdf->setPrintHeader(false);
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
$enc_result = mysql_query("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$row_enc = mysql_fetch_array($enc_result);
$enc_pass=$row_enc['pass'];

$usuariosquery = "select sexo,name,surname,idusfixed,idusfixedname from usuarios where identif=".$userId;
$result1 = mysql_query($usuariosquery);
$row1 = mysql_fetch_array($result1);

$extension='pdf';
$confCode = $row1['idusfixed'].md5(date('Ymdgisu'));
$new_image_name = 'eML'.$confCode.'.'.$extension;						
$new_image_nameTH = 'eML'.$confCode.'.png';	

$locFile = "Packages_Encrypted";  
$locFileTH = "PackagesTH_Encrypted";    
$ds = DIRECTORY_SEPARATOR;  
$checksum=md5_file($locFile.$ds.$new_image_name);
$path="C:\\PROGRA~2\\ImageMagick-6.8.1-Q16\\";

//Generate the Report
$pdf->Output($locFile.$ds.$new_image_name,'F');

//Generate Thumbnail
$cadenaConvert = $path.'convert "'.$locFile.$ds.$new_image_name.'[0]" -colorspace RGB -geometry 200 "'.$locFileTH.$ds.$new_image_nameTH.'" 2>&1';
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



$check = "select * from lifepin where rawimage='".$new_image_name."'";
$check_result=mysql_query($check);
$num_rows = mysql_num_rows($check_result);

if($num_rows==0)
{
	$tipo=60;  //for demographics
	$query = "insert into lifepin(idusu,rawimage,fechainput,tipo,canal,needaction,idusfixed,idusfixedname,idmed,idcreator,creatortype,fs,a_s,vs,checksum,orig_filename,emr_report,fecha) values(".$userId.",'".$new_image_name."',now(),".$tipo.",9,0,".$row1['idusfixed'].",'".$row1['idusfixedname']."',".$doctorId.','.$doctorId.",1,0,0,1,'".$checksum."','".$row1['name']."_".$row1['surname']."_EVOLUTION.pdf',1,now())";
	//echo $query;
	mysql_query($query);


	$query = "select max(idpin) as idpin from lifepin";
	$result = mysql_query($query);
	$row =  mysql_fetch_array($result);
		
	//Log that report has been uploaded/created
	$IdPin=$row['idpin'];
	$content = "Note created by doctor:".$doctorId." for patient:".$userId;
	$VIEWIdUser=$idusu;
	$VIEWIdMed=$userid;
	$ip=$_SERVER['REMOTE_ADDR'];
	$MEDIO=0;
	$q = mysql_query("INSERT INTO bpinview  SET  IDPIN ='$IdPin', Content='$content', DateTimeSTAMP = NOW(), VIEWIdUser = '$VIEWIdUser', VIEWIdMed = '$VIEWIdMed', VIEWIP = '$ip', MEDIO = '$MEDIO' ");
    
    //Finding the exact consultation row in consults table where we have enter the notes name
    $query1 = mysql_query("select in_consultation from doctors where id =".$doctorId);
    $result1 = mysql_fetch_array($query1);
    $in_consultation = $result1['in_consultation'];
    
    $query2 = mysql_query("select status,max(consultationId) as consultation from consults where doctor=".$doctorId." and patient=".$userId."");
    $result2 = mysql_fetch_array($query2);
    $consultationId = $result2['consultation'];
    $status = "";
    $status = $result2['status'];
    
    //if(/*($in_consultation == 1) && */$status == 'completed' ) //commented out $in_consultation aand making the consultation generic 
    {
     mysql_query("update consults set data_file ='".$new_image_name."' where consultationId=".$consultationId);
    }
       
    
	
}

?>