<?php
session_start();
set_time_limit(600);
error_reporting(E_ERROR | E_PARSE);
//require "logger.php";
require "environment_detailForLogin.php";
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];
$local = $env_var_db['local'];

require("NotificationClass.php");
$notifications = new Notifications();
		
		
$path_for_ffmpeg = "ffmpeg\\bin\\";
		
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}
	

//$id = $_POST["id"];
$idusu = $_POST["idus"];
$DoctorId = $_POST["doctorId"];
$userid = $DoctorId;
//$tipo = $_POST["tipo"];
//file_put_contents("ip.txt", 'Received from '.$tipo, FILE_APPEND);
$ds = DIRECTORY_SEPARATOR;  //1

if (!file_exists('dropzone_uploads'.$ds.$idusu)) 
{
    mkdir('dropzone_uploads'.$ds.$idusu, 0777, true);
	mkdir('dropzone_uploads'.$ds.$idusu.$ds.'Thumbnail', 0777, true);
	
}

//Below SQL query commented by Pallab
/*$query1 = mysql_query("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
			$row1 = mysql_fetch_array($query1);
			$enc_pass=$row1['pass'];*/

$query1 = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$query1->execute();
$row1 = $query1->fetch(PDO::FETCH_ASSOC);
$enc_pass = $row1['pass'];



mkdir('dropzone_uploads'.$ds.'externalH2MDoc', 0777, true);
mkdir('dropzone_uploads'.$ds.'externalH2MDoc'.$ds.'Thumbnail', 0777, true);



$storeFolder = 'dropzone_uploads'.$ds.'externalH2MDoc'.$ds;


//Code for writing file to a temp location which will be used for retrieving file for View Preview button
if(!file_exists('dropzone_uploads'.$ds.'temporaryForFilePreview'))
{
    mkdir('dropzone_uploads'.$ds.'temporaryForFilePreview', 0777, true);
}
if(!file_exists('dropzone_uploads'.$ds.'temporaryForFilePreview'.$ds.'Thumbnail'))
{
    mkdir('dropzone_uploads'.$ds.'temporaryForFilePreview'.$ds.'Thumbnail', 0777, true);
}
$tempFolder = 'dropzone_uploads'.$ds.'temporaryForFilePreview'.$ds;

$tempFileForPreview = $_FILES['file']['tmp_name'];
$targetPathForPreview = dirname( __FILE__ ) . $ds.$tempFolder ;
$targetFileForPreview =  $targetPathForPreview.$_FILES['file']['name'];
copy($tempFileForPreview,$targetFileForPreview);
//$filepathForPreview = $targetFileForPreview;
//$extensionForPreview = substr($_FILES['file']['name'],strlen($_FILES['file']['name'])-3,3);

//$filename =$FILES['file']['name']'.'.$extensionForPreview;
//$locFile ='dropzone_uploads/temporaryForFilePreview';
//copy($filepathForPreview,$tempFolder.$tempFileForPreview);




if (!empty($_FILES)) {
     
    $tempFile = $_FILES['file']['tmp_name'];          //3
	
      
    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder ;  //4
     
    $targetFile =  $targetPath. $_FILES['file']['name'];  //5
 
    move_uploaded_file($tempFile,$targetFile); //6
	
	$checksum=md5_file($filepath);
	//1. Find patients idusfixed
	
    //Below SQL queries commented by Pallab
    /*$query = "select idusfixed,idusfixedname from usuarios where identif = ".$idusu;
	$result=mysql_query($query);
	$row = mysql_fetch_array($result);
    */   
    
    //Start of new code added by Pallab
    $query = $con->prepare("select idusfixed,idusfixedname from usuarios where identif = ?");
    $query->bindValue(1,$idusu,PDO::PARAM_INT);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);
    //End of new code added by Pallab
	
    
    $IdUsFIXED=$row['idusfixed'];
	$idusfixedname=$row['idusfixedname'];
	
	//2.Get file Extension
	//$extension = substr($_FILES['file']['name'],strlen($_FILES['file']['name'])-3,3); // Commented by Pallab
    
    $extension;
    $extension = end(explode('.', $_FILES['file']['name']));
    if($extension == 'jpeg' || $extension == 'JPEG' || $extension == 'JPG')
         $extension = 'jpg';
	//Start of commenting by Pallab - $extension = substr($_FILES['file']['name'],strlen($_FILES['file']['name'])-4,4); End of commenting by Pallab

	
	
	//3.Create a new file name
	$confcode = $IdUsFIXED.md5(date('Ymdgisu'));
	$new_image_name = 'eML'.$confcode.'.'.$extension;			
	
	$filepath = $targetFile;
	
	$locFile = $local."Packages_Encrypted";  //Changed from PAckages to PAckages_Encrypted by Ankit
	copy($filepath, $locFile.$ds.$new_image_name);
	copy($filepath, 'temp'.$ds.$userid.$ds.'Packages_Encrypted'.$ds.$new_image_name);
	
	$checksum=md5_file($locFile.$ds.$new_image_name);
	
	$locFileTH = $local."PackagesTH_Encrypted";    //Changed from PackagesTH to PackagesTH_Encrypted
						
	//Changes for handling jpg files
	if($extension!="jpg" && $extension!="jpeg") 
	{
		$new_image_nameTH = 'eML'.$confcode.'.png';	
	}else 
	{
		$new_image_nameTH = 'eML'.$confcode.'.jpg';
	}
	
	
				
	if($extension==".MOV") //for video use this command
	{
		$path = $path_for_ffmpeg;  //path of ffmpeg
		$cadenaConvert = $path.'ffmpeg.exe -i '.$locFile.$ds.$new_image_name.' -f image2 -t 0.001 -ss 3 '. $locFileTH.$ds.$new_image_nameTH;
	}
	else   //use this command for other file types
	{
		//$path="C:\\PROGRA~2\\ImageMagick-6.8.1-Q16\\";  //path of ImageMagick
        $path="/usr/lib/x86_64-linux-gnu/ImageMagick-6.7.7";  //path of ImageMagick
        
	    $cadenaConvert = 'convert "'.$locFile.$ds.$new_image_name.'[0]" -colorspace RGB -geometry 200 "'.$locFileTH.$ds.$new_image_nameTH.'" 2>&1';
        
        error_log($cadenaConvert, 3, "/var/log/apache2/error.log");
	}													
	file_put_contents("ip.txt", $cadenaConvert, FILE_APPEND);													
												
																																	
	$output = shell_exec($cadenaConvert);  
	
	copy($locFileTH.$ds.$new_image_nameTH, 'temp'.$ds.$userid.$ds.'PackagesTH_Encrypted'.$ds.$new_image_nameTH);
	
	shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in ".$local."PackagesTH_Encrypted/".$new_image_nameTH." -out ".$local."temp/".$new_image_nameTH);
    shell_exec("rm ".$local."PackagesTH_Encrypted/".$new_image_nameTH);
    shell_exec("cp ".$local."temp/".$new_image_nameTH." PackagesTH_Encrypted/");
    shell_exec("rm ".$local."temp/".$new_image_nameTH);
    
    shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in ".$local."Packages_Encrypted/".$new_image_name." -out ".$local."temp/".$new_image_name);
    shell_exec("rm ".$local."Packages_Encrypted/".$new_image_name);
    shell_exec("cp ".$local."temp/".$new_image_name." ".$local."Packages_Encrypted/");
    shell_exec("rm ".$local."temp/".$new_image_name);          //Encrypt the report (ankit)
	
    //$query = mysql_query("select previlege from doctor where id=".$DoctorId);
    
	$query = $con->prepare("insert into lifepin(idusu,rawimage,fecha,fechainput,creatortype,IdCreator,orig_filename,tipo) values(?,?,now(),now(),2,?,?,70)");
	$query->bindValue(1,$idusu,PDO::PARAM_INT);
	$query->bindValue(2,$new_image_name,PDO::PARAM_STR);
	$query->bindValue(3,$DoctorId,PDO::PARAM_INT);
	$query->bindValue(4,$_FILES['file']['name'],PDO::PARAM_STR);
    $result = $query->execute();
	
	//file_put_contents("ip.txt", $query, FILE_APPEND);
	//mysql_query($query);

	//End of new code added by Pallab
	if($result){
        
        // doctor
        if(isset($DoctorId))
        {
            //to the patient
            $notifications->add('REPUPL', $DoctorId, true, $idusu, false, $idusu);
        }
	}
	
	$grabPinnner = $con->lastInsertId(); 

	//Below SQL queries commented by Pallab
   /* $grabDLU = mysql_query("SELECT * FROM doctorslinkusers WHERE IdMED = '".$DoctorId."' && IdUs = '".$idusu."' && IdPIN IS NULL");
	$gotDLU = mysql_fetch_array($grabDLU);
    */
    //Start of new code added by Pallab
    $grabDLU = $con->prepare("SELECT * FROM doctorslinkusers WHERE IdMED = ? && IdUs = ? && IdPIN IS NULL");
    $grabDLU->bindValue(1,$DoctorId,PDO::PARAM_INT);
    $grabDLU->bindValue(2,$idusu,PDO::PARAM_INT);
    $grabDLU->execute();
    $gotDLU = $grabDLU->fetch(PDO::FETCH_ASSOC);
    //End of new code added by Pallab
    
    
	//Below SQL queries are commented 
	/*$grabPin = mysql_query("SELECT * FROM doctorslinkusers WHERE IdPIN = '".$grabPinnner."'");
	$gotPin = mysql_fetch_array($grabPin);
    */
    
    //Start of new code added by Pallab
    $grabPin = $con->prepare("SELECT * FROM doctorslinkusers WHERE IdPIN = ?");
    $grabPin->bindValue(1,$grabPinner,PDO::PARAM_INT);
    $grabPin->execute();
    $gotPin = $grabPin->fetch(PDO::FETCH_ASSOC);
    //End of new code added by Pallab
	
	if($gotDLU){
	
	}elseif(!$gotPin){
	
    //Below SQL query commented by Pallab    
    //mysql_query("INSERT INTO doctorslinkusers SET IdMED = '".$DoctorId."', IdUs = '".$idusu."', IdPIN = '".$grabPinnner."', estado= 2, Fecha = NOW()");
        
    //Start of new code added by Pallab
    $query = $con->prepare("INSERT INTO doctorslinkusers SET IdMED = ?, IdUs = ?, IdPIN = ?, estado= 2, Fecha = NOW()");
    $query->bindValue(1,$DoctorId,PDO::PARAM_INT);
    $query->bindValue(2,$idusu,PDO::PARAM_INT);
    $query->bindValue(3,$grabPinner,PDO::PARAM_INT);
    $query->execute();
    //End of new code added by Pallab
        
        
	}
	
	
	
	/*$query = "select max(idpin) as idpin from lifepin";
	$result = mysql_query($query);
	$row =  mysql_fetch_array($result);
	
	//Log that report has been uploaded
	$IdPin=$row['idpin'];
	$content = "Report Uploaded";
	$VIEWIdUser=$idusu;
	$VIEWIdMed=$userid;
	$ip=$_SERVER['REMOTE_ADDR'];
	$MEDIO=0;
	$q = mysql_query("INSERT INTO bpinview  SET  IDPIN ='$IdPin', Content='$content', DateTimeSTAMP = NOW(), VIEWIdUser = '$VIEWIdUser', VIEWIdMed = '$VIEWIdMed', VIEWIP = '$ip', MEDIO = '$MEDIO' ");
	//LogBLOCKAMP ($IdPin, $content, $VIEWIdUser, $VIEWIdMed, $ip, $MEDIO);
	
	
	
	echo $id.';'.$row['idpin'].';'.$new_image_name.';'.$tipo; */
	

} 
?>    
