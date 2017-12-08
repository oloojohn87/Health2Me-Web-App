<?php
require("environment_detailForLogin.php");
require("NotificationClass.php");

$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];
$hardcode = $env_var_db['hardcode'];
$local = $env_var_db['local'];

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con){
	die('Could not connect: ' . mysql_error());
	}


//GRAB STRINGIFIED JSON FROM POST
$json = $_POST['json'];

//DECODE STRINGIFIED JSON INTO JSON OBJECT
$decoded = json_decode($_POST['json'], true);

foreach($decoded as $document){
	$mem_id = $document['document']['member_id'];
	$encoded_doc = $document['document']['64encoded'];
	$extension = $document['document']['filetype'];
	$filename = $document['document']['original_file_name'];
    
    echo $mem_id.':'.$extension.':'.$filename.':';

	$decode = base64_decode($encoded_doc);
	$hide_from_member = 0;
	$queId = $mem_id;

	$result = $con->prepare("SELECT * FROM usuarios where Identif=?");
	$result->bindValue(1, $queId, PDO::PARAM_INT);
	$result->execute();
		
	$row = $result->fetch(PDO::FETCH_ASSOC);

	$reportdate=date("Y-m-d");

	$reporttype=1;

	$notifications = new Notifications();

	$enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
	$enc_result->execute();

	$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);

	$enc_pass = $row_enc['pass'];

	if($extension == 'jpeg')
    $extension = 'jpg';

	$IdUsFIXED=0;
	$confcode = $IdUsFIXED.md5(date('Ymdgisu'));
	$new_image_name = 'eML'.$confcode.'.'.$extension;
	$filepath="Packages_Encrypted/".$new_image_name;

    $extensions_list = array('png', 'jpg', 'tiff', 'gif', 'mov', 'pdf');
    
    try { if(!in_array(strtolower($extension), $extensions_list)) throw new RuntimeException("ExtError"); }
    catch (RuntimeException $e) { 
        echo 'ExtError Incorrect file extension only png, jpg, tiff, gif, mov, and pdf are allowed.';
        die();  
    }

    finally { $resultado = file_put_contents($filepath, $decode); }

    $checksum=md5_file($filepath);

    $Isql="INSERT INTO lifepin SET NeedACTION = 1, IdEmail='1', IdUsu=$mem_id, FechaInput=NOW(), Fecha='$reportdate' , FechaEmail = Now() , IdUsFIXED='$IdUsFIXED', IdUsFIXEDNAME='".$row['IdUsFIXEDNAME']."', IdMedEmail = NULL, CANAL=5, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=$reporttype, fs=1, checksum='$checksum', hide_from_member='$hide_from_member', idcreator = 1";
    $q = $con->query($Isql);
    
    $IdPin = $con->lastInsertId();

    if($q){
        //$newlog_q = "INSERT INTO bpinview SET IdPIN=".$IdPin.", Content='Report Uploaded', DateTimeSTAMP=NOW(), VIEWIdUser=".$med_id.", VIEWIdMed=0, VIEWIP='".$ip_new."', MEDIO=0";
        $notifications->add('REPUPL', $mem_id, false, $row['personal_doctor'], true, $IdPin);
	}

	$locFile = "Packages_Encrypted";
	$ds = DIRECTORY_SEPARATOR; 

	$locFileTH = "PackagesTH_Encrypted";
	$path="/usr/lib/x86_64-linux-gnu/ImageMagick-6.7.7";
	
	//Changes for handling jpg files
	if($extension=="jpg") {
        $new_image_nameTH = 'eML'.$confcode.'.jpg';	
	}elseif($extension=="gif") {
        $new_image_nameTH = 'eML'.$confcode.'.gif';	
	}else {
        $new_image_nameTH = 'eML'.$confcode.'.png';
	}

	if($extension=="MOV") //for video use this command
	{
		$path = 'ffmpeg';  //path of ffmpeg
		$cadenaConvert = 'ffmpeg -i '.$locFile.$ds.$new_image_name.' -f image2 -t 0.001 -ss 3 '. $locFileTH.$ds.$new_image_nameTH;
		
	}
	else   //use this command for other file types
	{
		$path="/usr/lib/x86_64-linux-gnu/ImageMagick-6.7.7";  //path of ImageMagick
		$cadenaConvert = 'convert "'.$locFile.$ds.$new_image_name.'[0]" -colorspace RGB -geometry 200 "'.$locFileTH.$ds.$new_image_nameTH.'" 2>&1';
		//error_log($cadenaConvert, 3, "/var/log/apache2/error.log");
	}
	$output = shell_exec($cadenaConvert);  

	shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in PackagesTH_Encrypted/".$new_image_nameTH." -out temp/".$new_image_nameTH);
    shell_exec("rm PackagesTH_Encrypted/".$new_image_nameTH);
    shell_exec("cp temp/".$new_image_nameTH." PackagesTH_Encrypted/");
    shell_exec("rm temp/".$new_image_nameTH);

    shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in Packages_Encrypted/".$new_image_name." -out temp/".$new_image_name);
    shell_exec("rm Packages_Encrypted/".$new_image_name);
    shell_exec("cp temp/".$new_image_name." Packages_Encrypted/");
    shell_exec("rm temp/".$new_image_name);        //Encrypt the report (ankit)
	//file_put_contents("upload_test.txt",'Encrypt.bat Packages_Encrypted '.$new_image_name.' '.$enc_pass, FILE_APPEND);
	
	//reación de un thumbnail desde el PDF  
  

	$Isql="UPDATE lifepin SET IdEmail='1', RawImage='$new_image_name' , FechaInput=NOW(), ValidationStatus=8 , orig_filename ='$filename',fs=1,idusu=".$queId.",idcreator=NULL, CreatorType = 0, IdMed = 0 WHERE IdPin='$IdPin'";
	$q =$con->query($Isql);
}
?>