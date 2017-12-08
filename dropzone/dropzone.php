<?php

require('../environment_detail.php');
session_start();

$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];
$hardcode = $env_var_db['hardcode'];

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$IdUsFIXED = '1111111';
$IdUsFIXEDNAME = '';

$enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$enc_result->execute();

$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);

$enc_pass = $row_enc['pass'];

$array = array();

$user_id = -1;
$patient_id = -1;

$classification = $_POST['classification'];

if(isset($_SESSION['MEDID']) && $_SESSION['MEDID'] > 0)
{
    $user_id = $_SESSION['MEDID'];
}
else if(isset($_SESSION['UserID']) && $_SESSION['UserID'] > 0)
{
    $user_id = $_SESSION['UserID'];
}
else if(isset($_POST['user']))
{
    $user_id = $_POST['user'];
}
if(isset($_POST['patient']))
{
    $patient_id = $_POST['patient'];
    
    $patient_query = $con->prepare("SELECT * FROM usuarios WHERE Identif = ?");
	$patient_query->bindValue(1, $patient_id, PDO::PARAM_INT);
	$patient_query->execute();
    
    $patient_row = $patient_query->fetch(PDO::FETCH_ASSOC);
    
    $IdUsFIXED = $patient_row['IdUsFIXED'];
    $IdUsFIXEDNAME = $patient_row['IdUsFIXEDNAME'];
}

for($i = 0; $i < count($_FILES['files']['name']); $i++)
{

    $fileName = $_FILES['files']['name'][$i];
    $fileType = $_FILES['files']['type'][$i];
    
    $fileContent = file_get_contents($_FILES['files']['tmp_name'][$i]);
    
    $now = new DateTime('NOW');
    
    $confcode = $IdUsFIXED.md5($now->format('Ymdgisu').($i + 1));
    
    $extension = 'pdf';
    
    $dot = strrpos($_FILES['files']['name'][$i], '.');
    if($dot != -1)
    {
        $extension = substr($_FILES['files']['name'][$i], $dot + 1);
    }
    
    $new_file_name = 'eML'.$confcode.'.'.$extension;
    
    $new_file_nameTH = $new_image_nameTH = 'eML'.$confcode.'.png';
    
    if(strtolower($extension) == "jpg" || strtolower($extension) == "jpeg") 
    {
        $new_image_nameTH = 'eML'.$confcode.'.jpg';	
    }
    elseif(strtolower($extension) == "gif") 
    {
        $new_image_nameTH = 'eML'.$confcode.'.gif';	
    }
    
    if($user_id == -1 || $patient_id == -1)
    {

        file_put_contents("reports/".$new_file_name, $fileContent);

        $cadenaConvert = 'convert "reports/'.$new_file_name.'[0]" -colorspace RGB -geometry 200 "thumbnails/'.$new_file_nameTH.'" 2>&1';
        $output = shell_exec($cadenaConvert); 

        array_push($array, array("thumbnail" => "send_referral/thumbnails/".$new_file_nameTH, "classification" => $classification, "id" => $confcode, "report" => "send_referral/reports/".$new_file_name));
    }
    else
    {
        file_put_contents("../temp/".$user_id."/Packages_Encrypted/".$new_file_name, $fileContent);

        $cadenaConvert = 'convert "../temp/'.$user_id.'/Packages_Encrypted/'.$new_file_name.'[0]" -colorspace RGB -geometry 200 "../temp/'.$user_id.'/PackagesTH_Encrypted/'.$new_file_nameTH.'" 2>&1';
        $output = shell_exec($cadenaConvert); 
        
        // ENCRYPT
        
        shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in ../temp/".$user_id."/PackagesTH_Encrypted/".$new_file_nameTH." -out ../temp/".$new_file_nameTH);
        shell_exec("cp ../temp/".$new_file_nameTH." ../PackagesTH_Encrypted/");
        shell_exec("rm ../temp/".$new_file_nameTH);

        shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in ../temp/".$user_id."/Packages_Encrypted/".$new_file_name." -out ../temp/".$new_file_name);
        shell_exec("cp ../temp/".$new_file_name." ../Packages_Encrypted/");
        shell_exec("rm ../temp/".$new_file_name); 
        
        // ADD TO DATABASE
        
        $hide = ($user_id == $patient_id) ? 0 : 1;
        $checksum = md5_file("Packages_Encrypted/".$new_file_name);
        
        $insert = $con->prepare("INSERT INTO lifepin SET NeedACTION = 0, IdEmail = '1', IdUsu = ?, FechaInput=NOW(), Fecha = NOW() , FechaEmail = NOW() , IdUsFIXED =  ?, IdUsFIXEDNAME= ?, IdMedEmail = NULL, CANAL = 5, ValidationStatus = 99, EvRuPunt = 2, Evento = 99, Tipo = ?, fs = 1, checksum = ?, hide_from_member = ?, idcreator = ?, RawImage = ?, orig_filename = ?");
        $insert->bindValue(1, $patient_id, PDO::PARAM_INT);
        $insert->bindValue(2, $IdUsFIXED, PDO::PARAM_STR);
        $insert->bindValue(3, $IdUsFIXEDNAME, PDO::PARAM_STR);
        $insert->bindValue(4, $classification, PDO::PARAM_INT);
        $insert->bindValue(5, $checksum, PDO::PARAM_STR);
        $insert->bindValue(6, $hide, PDO::PARAM_INT);
        $insert->bindValue(7, $user_id, PDO::PARAM_INT);
        $insert->bindValue(8, $new_file_name, PDO::PARAM_STR);
        $insert->bindValue(9, $_FILES['files']['name'][$i], PDO::PARAM_STR);
        $insert->execute();
        
        $IdPin = $con->lastInsertId();
        
        // ADD TO RESULTS 
        array_push($array, array("thumbnail" => "temp/".$user_id."/PackagesTH_Encrypted/".$new_file_nameTH, "classification" => $classification, "id" => $IdPin, "report" => "temp/".$user_id."/Packages_Encrypted/".$new_file_name));
    }
}

$json = json_encode($array);

echo $json;

?>