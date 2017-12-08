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


$idusu = $_GET['idusu'];
$userid=$_SESSION['MEDID'];
echo "IDUSU=".$idusu."<p>";
echo "MEDID=".$userid."<p>";
//$userid=28;
//$idusu=1491;


$query = $con->prepare("select * from evolutions where userid=? order by message_date");
$query->bindValue(1, $idusu, PDO::PARAM_INT);
$result = $query->execute();
 
//echo $query;
$row = $query->fetch(PDO::FETCH_ASSOC);



//Generating HTML 
$i=1;
$table1 = '<table cellspacing="0" cellpadding="1" style="border:1px solid #cacaca" width="100%"><tr><th width="10%" align="center" style="border:1px solid #cacaca"><b>Sr.No</b></th><th width="30%" align="center" style="border:1px solid #cacaca"><b>Date</b></th><th width="60%" align="center" style="border:1px solid #cacaca"><b>Comments</b></th></tr>';



do
{
	$current_encoding = mb_detect_encoding($row["message"], 'auto');
	$encoded_message = iconv($current_encoding, 'ISO-8859-1', $row["message"]);

	$date=$row["message_date"];

	$yy = strtok($date, "-");
	$mm = strtok("-");
	$dd = strtok("-");

	$date = $mm.'-'.$dd.'-'.$yy;


	$col1 = '<td width="10%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$i.'</td>';
	$col2 = '<td width="30%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$date.'</td>';
	$col3 = '<td width="60%" align="center" style="font-size:16px;color:#3D93E0;border:1px solid #cacaca">'.$row["message"].'</td>';
	
	
	$table1 = $table1 .'<tr>'.$col1.$col2.$col3.'</tr>';
	$i++;
}while($row = $query->fetch(PDO::FETCH_ASSOC));



$table1 = $table1 . '</table>';

echo $table1."<p>";



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


$pdf->AddPage();
//$tbl = $table1;
$pdf->SetFont('helvetica', 'B', 18);
$pdf->writeHTML('<br><br><span style="color:#3D93E0; font-size:20px;"><b>Patient Evolution : </span></b>', true, false, false, false, 'L');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->writeHTML($table1, true, false, false, false, '');


//Get the Encryption Password
$enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$enc_result->execute();

$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
$enc_pass=$row_enc['pass'];

echo $enc_pass."<p>";
$usuariosquery = $con->prepare("select sexo,name,surname,idusfixed,idusfixedname from usuarios where identif=?");
$usuariosquery->bindValue(1, $idusu, PDO::PARAM_INT);
$result1 = $usuariosquery->execute();

$row1 = $usuariosquery->fetch(PDO::FETCH_ASSOC);


/*
$extension='pdf';
$confcode = $row1['idusfixed'].md5(date('Ymdgisu'));
$new_image_name = $idusu.'.'.$extension;			


//$locFile = "Evolution";  
$locFile="Packages_Encrypted";

$ds = DIRECTORY_SEPARATOR;  
$checksum=md5_file($locFile.$ds.$new_image_name);


//Generate the Report
$pdf->Output($locFile.$ds.$new_image_name,'F');
*/

$extension='pdf';
$new_image_name = $idusu.'.'.$extension;						
$new_image_nameTH = $idusu.'.png';	

$locFile = "Packages_Encrypted";  
$locFileTH = "PackagesTH_Encrypted";    
$ds = DIRECTORY_SEPARATOR;  
$checksum=md5_file($locFile.$ds.$new_image_name);
$path="C:\\PROGRA~2\\ImageMagick-6.8.1-Q16\\";
echo '<b>CHECKSUM</b>'.$checksum."<p>";

//Generate the Report
$check = $pdf->Output($locFile.$ds.$new_image_name,'F');
echo '<b>CHECK</b>'.$check."<p>";
//shell_exec('chmod 777 '.$locFile.$ds.$new_image_name);

//Generate Thumbnails
$cadenaConvert = 'convert "'.$locFile.$ds.$new_image_name.'[0]" -colorspace RGB -geometry 200 "'.$locFileTH.$ds.$new_image_nameTH.'" 2>&1';
$output = shell_exec($cadenaConvert);  
//shell_exec('chmod 777 '.$locFileTH.$ds.$new_image_nameTH);
//echo '<b>OUTPUT</b>'.$output."<p>";

$initial_file_dump = $locFile.$ds.$new_image_name;
$temp_dir = 'temp'.$ds.$userid.$ds.'Packages_Encrypted'.$ds.$new_image_name;

//echo "Initial File Dump : ".$initial_file_dump."<p>";sdfsdf
//echo "Temp Dir : ".$temp_dir."<p>";

copy($initial_file_dump, $temp_dir);
//shell_exec('chmod 777 '.$temp_dir);
copy($locFileTH.$ds.$new_image_nameTH, 'temp'.$ds.$userid.$ds.'PackagesTH_Encrypted'.$ds.$new_image_nameTH);
//shell_exec('chmod 777 temp'.$ds.$userid.$ds.'PackagesTH_Encrypted'.$ds.$new_image_nameTH);



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
	$query->bindValue(1, $idusu, PDO::PARAM_INT);
	$query->bindValue(2, $new_image_name, PDO::PARAM_STR);
	$query->bindValue(3, $tipo, PDO::PARAM_INT);
	$query->bindValue(4, $row1['idusfixed'], PDO::PARAM_INT);
	$query->bindValue(5, $row1['idusfixedname'], PDO::PARAM_STR);
	$query->bindValue(6, $userid, PDO::PARAM_INT);
	$query->bindValue(7, $userid, PDO::PARAM_INT);
	$query->bindValue(8, $checksum, PDO::PARAM_STR);
	$query->bindValue(9, ($row1['name']."_".$row1['surname']."_EVOLUTION.pdf"), PDO::PARAM_STR);
	$query->execute();

	$query = $con->prepare("select max(IdPin) as idpin from lifepin");
	$result = $query->execute();
	$row = $query->fetch(PDO::FETCH_ASSOC);
		
	//Log that report has been uploaded/created
	$IdPin=$row['IdPin'];
	$content = "Evolution Report Created";
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
	
}
else
{

	$row = $check->fetch(PDO::FETCH_ASSOC);
	//Log that report has been uploaded/created
	$IdPin=$row['idpin'];
	$content = "Evolution Report Modified";
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



}

//============================================================+
// END OF FILE
//============================================================+*/
