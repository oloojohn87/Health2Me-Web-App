<?php
session_start();
set_time_limit(600);
error_reporting(E_ERROR | E_PARSE);
//require "logger.php";
require "environment_detail.php";
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];
$local = $env_var_db['local'];

/*require("NotificationClass.php");
$notifications = new Notifications();	*/
		
$path_for_ffmpeg = "ffmpeg\\bin\\";
		
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}
	
	
$userid = $_SESSION['MEDID'];
//$userid = 28;	
//$fecha = $_POST["repdate"];
$id = $_POST["id"];
$idusu = $_POST["idus"];
$tipo = $_POST["tipo"];
//file_put_contents("ip.txt", 'Received from '.$tipo, FILE_APPEND);
$ds = DIRECTORY_SEPARATOR;  //1

if (!file_exists('dropzone_uploads'.$ds.$userid)) 
{
    mkdir('dropzone_uploads'.$ds.$userid, 0777, true);
	mkdir('dropzone_uploads'.$ds.$userid.$ds.'Thumbnail', 0777, true);
	
}

$result = $con->prepare("SELECT * FROM usuarios where Identif=?");
$result->bindValue(1, $idusu, PDO::PARAM_INT);
$result->execute();
	
$row = $result->fetch(PDO::FETCH_ASSOC);

$hide_from_member = 0;

if($row['GrantAccess'] != 'HTI-SPA' && $row['GrantAccess'] != 'HTI-COL' && $row['GrantAccess'] != 'HTI-RIVA' && $row['GrantAccess'] != 'HTI-24X7'){
	if($_SESSION['BOTHID'] == $idusu){
		$hide_from_member = 0;
	}else{
		$hide_from_member = 1;
	}
}

//Commented by Pallab below sql queries

/*$enc_result = mysql_query("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
			$row_enc = mysql_fetch_array($enc_result);
			$enc_pass=$row_enc['pass']; */

//Start of new code added by Pallab
$enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$enc_result->execute();
$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
$enc_pass = $row_enc['pass'];
//End of new code added by Pallab


 
//$month = strtok($fecha,"/");
//$day = strtok("/");
//$year = strtok("/");

//$fecha = $year.'-'.$month.'-'.$day;

$storeFolder = 'dropzone_uploads'.$ds.$userid.$ds;


if (!empty($_FILES)) {
     
    $tempFile = $_FILES['file']['tmp_name'];          //3
	
      
    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder ;  //4
     
    $targetFile =  $targetPath. $_FILES['file']['name'];  //5
 
    move_uploaded_file($tempFile,$targetFile); //6
	
	$checksum=md5_file($filepath);
	//1. Find patients idusfixed
	$query = "select idusfixed,idusfixedname from usuarios where identif = ".$idusu;
	
    //Below lines commented by Pallab
    /*$result=mysql_query($query);
	$row = mysql_fetch_array($result);
    */

    //Start of new code added by Pallab
    $result = $con->prepare("select idusfixed,idusfixedname from usuarios where identif = ?");
    $result->bindValue(1,$idusu,PDO::PARAM_INT);
    $result->execute();
    $row = $result->fetch(PDO::FETCH_ASSOC);
    //End of new code added by Pallab
    
    $IdUsFIXED=$row['idusfixed'];
	$idusfixedname=$row['idusfixedname'];
	
	//2.Get file Extension
	//Commented by Pallab $extension = substr($_FILES['file']['name'],strlen($_FILES['file']['name'])-3,3);
    
    // New code added by Pallab for getting the file extension
    $extension = strtolower(end(explode('.', $_FILES['file']['name'])));
	
    //Start of new code added by Pallab for handling .jpeg files extension -- Comparison is done for peg or PEG because .jpeg/.JPEG files extension value come as         peg/PEG
    if($extension == 'jpeg')
        $extension = 'jpg';
    //End of new code added by Pallab for handling .jpeg files extension
    
    //$path_info = pathinfo($_FILES['file']['name']);
	//$extension = $path_info['extension'];
/*	
	if($userid==28 && $extension!="pdf")
	{
		header("error message",replace,404);return;
	}
*/
	
	
	//3.Create a new file name
	$confcode = $IdUsFIXED.md5(date('Ymdgisu'));
	$new_image_name = 'eML'.$confcode.'.'.$extension;			
	
	//shell_exec('mkdir '.$new_image_name);
	
	$filepath = $targetFile;
	
	$locFile = $local."Packages_Encrypted";  //Changed from PAckages to PAckages_Encrypted by Ankit
	copy($filepath, $locFile.$ds.$new_image_name);
	copy($filepath, $local.'temp'.$ds.$userid.$ds.'Packages_Encrypted'.$ds.$new_image_name);
	
	$checksum=md5_file($locFile.$ds.$new_image_name);
	
	$locFileTH = $local."PackagesTH_Encrypted";    //Changed from PackagesTH to PackagesTH_Encrypted
	//$path="C:\\PROGRA~2\\ImageMagick-6.8.1-Q16\\";
						
	//Changes for handling jpg files
	if($extension=="jpg") $new_image_nameTH = 'eML'.$confcode.'.jpg';
    else if($extension=="gif") $new_image_nameTH = 'eML'.$confcode.'.gif';
    else $new_image_nameTH = 'eML'.$confcode.'.png';	

	
/*	
	//echo $extension;
	$office_file_types = array("doc", "docx", "rtf", "xls","xlsx","ppt","pptx");
	if (in_array(strtolower($extension), $office_file_types))
	{
		echo "Office File";
		$PDFcommand='Doc2PDF.bat '.$locFile.$ds.$new_image_name.' '.$locFile.$ds.'eML'.$confcode.'.pdf';
		echo "<br>".$PDFcommand."<br>";
		$output=shell_exec($PDFcommand);
		if($output!="success")
		{
		
			echo "failed to create PDF : ".$output;
		}
		else
		{
			echo "PDF created";
			$new_image_name='eML'.$confcode.'.pdf';
		}
		return;
	}
*/
	
	
	
				
	if($extension=="MOV") //for video use this command
	{
		$path = $path_for_ffmpeg;  //path of ffmpeg
		$cadenaConvert = $path.'ffmpeg.exe -i '.$locFile.$ds.$new_image_name.' -f image2 -t 0.001 -ss 3 '. $locFileTH.$ds.$new_image_nameTH;
	}
	else   //use this command for other file types
	{
		$path="/usr/lib/x86_64-linux-gnu/ImageMagick-6.7.7";  //path of ImageMagick
	    $cadenaConvert = 'convert "'.$locFile.$ds.$new_image_name.'[0]" -colorspace RGB -geometry 200 "'.$locFileTH.$ds.$new_image_nameTH.'" 2>&1';
	}													
	file_put_contents("ip.txt", $cadenaConvert, FILE_APPEND);													
														
														
	//$cadenaConvert = $path.'convert "'.$locFile.$ds.$new_image_name.'[0]" -colorspace RGB -geometry 200 "'.$locFileTH.$ds.$new_image_nameTH.'" 2>&1';
																																	
	$output = shell_exec($cadenaConvert);  
	
	copy($locFileTH.$ds.$new_image_nameTH, $local.'temp'.$ds.$userid.$ds.'PackagesTH_Encrypted'.$ds.$new_image_nameTH);
	
	shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in ".$local."PackagesTH_Encrypted/".$new_image_nameTH." -out ".$local."temp/".$new_image_nameTH);
    shell_exec("rm ".$local."PackagesTH_Encrypted/".$new_image_nameTH);
    shell_exec("cp ".$local."temp/".$new_image_nameTH." ".$local."PackagesTH_Encrypted/");
    shell_exec("rm temp/".$new_image_nameTH);
    
    shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in ".$local."Packages_Encrypted/".$new_image_name." -out ".$local."temp/".$new_image_name);
    shell_exec("rm ".$local."Packages_Encrypted/".$new_image_name);
    shell_exec("cp ".$local."temp/".$new_image_name." ".$local."Packages_Encrypted/");
    shell_exec("rm ".$local."temp/".$new_image_name);          //Encrypt the report (ankit)
	

	//$query = "insert into lifepin(idusu,rawimage,fecha,fechainput,tipo,canal,needaction,idusfixed,idusfixedname,idmed,idcreator,creatortype,vs,checksum,orig_filename) values(".$idusu.",'".$new_image_name."','".$fecha."',now(),".$tipo.",8,0,".$IdUsFIXED.",'".$idusfixedname."',".$userid.','.$userid.",1,1,'".$checksum."','".$_FILES['file']['name']."')";
	
    //Below sql queries commented by Pallab
    /*
    $query = "insert into lifepin(idusu,rawimage,fechainput,tipo,canal,needaction,idusfixed,idusfixedname,idmed,idcreator,creatortype,fs,a_s,vs,checksum,orig_filename) values(".$idusu.",'".$new_image_name."',now(),".$tipo.",8,0,".$IdUsFIXED.",'".$idusfixedname."',".$userid.','.$userid.",1,1,1,0,'".$checksum."','".$_FILES['file']['name']."')";
	//file_put_contents("ip.txt", $query, FILE_APPEND);
	mysql_query($query);
*/
	
	//Start of new code added by Pallab
    $query1 = $con->prepare("insert into lifepin(idusu,rawimage,fechainput,tipo,canal,needaction,idusfixed,idusfixedname,idmed,idcreator,creatortype,fs,a_s,vs,checksum,orig_filename,hide_from_member) values(?,?,now(),?,8,0,?,?,?,?,1,1,1,0,?,?,?)");
    $query1->bindValue(1,$idusu,PDO::PARAM_INT);
    $query1->bindValue(2,$new_image_name,PDO::PARAM_STR);
    $query1->bindValue(3,$tipo,PDO::PARAM_INT);
    $query1->bindValue(4,$IdUsFIXED,PDO::PARAM_INT);
    $query1->bindValue(5,$idusfixedname,PDO::PARAM_STR);
    $query1->bindValue(6,0,PDO::PARAM_INT);
    $query1->bindValue(7,$userid,PDO::PARAM_INT);
    $query1->bindValue(8,$checksum,PDO::PARAM_STR);
    $query1->bindValue(9,$_FILES['file']['name'],PDO::PARAM_STR);
    $query1->bindValue(10,$hide_from_member,PDO::PARAM_INT);
    $query1->execute();
    
    //End of new code added by Pallab
	
	
	
	$query = $con->prepare("select max(idpin) as idpin from lifepin");
	$result = $query->execute();
	$row =  $query->fetch(PDO::FETCH_ASSOC);
	
	//Log that report has been uploaded
	$IdPin=$row['idpin'];
	$content = "Report Uploaded";
	$VIEWIdUser=$idusu;
	$VIEWIdMed=$userid;
	$ip=$_SERVER['REMOTE_ADDR'];
	$MEDIO=0;
	
    //Below SQL query commented by Pallab
   //Below SQL query commented by Pallab
   // $q = mysql_query("INSERT INTO bpinview  SET  IDPIN ='$IdPin', Content='$content', DateTimeSTAMP = NOW(), VIEWIdUser = '$VIEWIdUser', VIEWIdMed = '$VIEWIdMed', VIEWIP = '$ip', MEDIO = '$MEDIO' ");
	//LogBLOCKAMP ($IdPin, $content, $VIEWIdUser, $VIEWIdMed, $ip, $MEDIO);
    
    //Start of new code added by Pallab
    $q = $con->prepare("INSERT INTO bpinview  SET  IDPIN =?, Content=?, DateTimeSTAMP = NOW(), VIEWIdUser = ?, VIEWIdMed = ?, VIEWIP = ?, MEDIO = ? ");
    $q->bindValue(1,$IdPin,PDO::PARAM_INT);
    $q->bindValue(2,$content,PDO::PARAM_STR);
    $q->bindValue(3,$VIEWIdUser,PDO::PARAM_INT);
    $q->bindValue(4,$VIEWIdMed,PDO::PARAM_INT);
    $q->bindValue(5,$ip,PDO::PARAM_STR);
    $q->bindValue(6,$MEDIO,PDO::PARAM_INT);
    $q->execute();
    //End of new code added by Pallab
    
    
    
    /*NOTIFICATION FOR UPLOADING REPORT BY MARS KICKS IN
	$foundDocs = array();
                    
    //IF THE PATIENT HAS BEEN REFERRED, NOTIFY THIS OCCURRENCE TO THE DOCTORS WHO REFERRED THIS PATIENT
    $findRefDocs = $con->prepare('SELECT IdMED, IdMED2 FROM doctorslinkdoctors WHERE IdMED = ? OR IdMED2 = ? AND IdPac = ? UNION SELECT IdMED, null AS IdMED2 FROM doctorslinkusers WHERE IdUs = ?');
    $findRefDocs->bindValue(1, $_SESSION['MEDID'], PDO::PARAM_INT);
    $findRefDocs->bindValue(2, $_SESSION['MEDID'], PDO::PARAM_INT);
    $findRefDocs->bindValue(3, $idusu, PDO::PARAM_INT);
    $findRefDocs->bindValue(4, $idusu, PDO::PARAM_INT);
    $findRefDocs->execute();

    while($RefDocRow = $findRefDocs->fetch(PDO::FETCH_ASSOC)) {
            if(!in_array($RefDocRow['IdMED'], $foundDocs)) array_push($foundDocs, $RefDocRow['IdMED']);
            else if($RefDocRow['IdMED2'] !== null && !in_array($RefDocRow['IdMED2'], $foundDocs)) array_push($foundDocs, $RefDocRow['IdMED2']);
    }

    // doctor
    if($_SESSION['BOTHID'] == $_SESSION['MEDID'])
    {
        //to the patient
        $notifications->add('REPUPL', $_SESSION['MEDID'], true, $idusu, false, null);
        //to the related doctors
        foreach($foundDocs as $foundDoc) 
            $notifications->add('REPUPL', $_SESSION['MEDID'], true, $foundDoc, true, $idusu);
    }
    //below means patient himself uploading
    elseif($hide_from_member == 0) 
    {
        //to the related doctors
        foreach($foundDocs as $foundDoc)
            $notifications->add('REPUPL', $idusu, false, $foundDoc, true, null);
    }
	//KICKS OUT*/
	
	echo $id.';'.$row['idpin'].';'.$new_image_name.';'.$tipo;
	

}
?>    
