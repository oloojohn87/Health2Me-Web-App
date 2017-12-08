<?php
 session_start();
require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 $local = $env_var_db['local'];

$rawimage = $_GET['rawimage'];
$queMed = $_GET['queMed'];
$pass = '';
if(isset($_SESSION['decrypt']))
{
    $pass = $_SESSION['decrypt'];
}
else
{
    $con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    if (!$con)
    {
        die('Could not connect: ' . mysql_error());
    }
    
    $query = $con->prepare("SELECT * FROM encryption_pass ORDER BY changed_on DESC");
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $pass = $row['pass'];
}

$ImageRaiz = basename($rawimage);
//	$extensionR = substr($rawimage,strlen($rawimage)-4,4);
$extensionR = strtolower(end(explode('.', $rawimage)));

$filename = $local.'temp/'.$queMed.'/Packages_Encrypted/'.$rawimage;
if (file_exists($filename)) 
{
    //do nothing
    //echo "The file $filename exists";
}
else 
{
    shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in ".$local."Packages_Encrypted/".$rawimage." -out ".$local."temp/".$queMed."/Packages_Encrypted/".$rawimage);
    //echo 'Decrypt.bat Packages_Encrypted '.$rawimage.' '.$queMed .' '.$pass.' 2>&1';	
}

if($extensionR=='jpg' | $extensionR=='jpeg')
{
    //die("Found JPG Extension");
    $extension='jpg';
    //return;
}
else if($extensionR=='gif')
{
    $extension = 'gif';
}
else
{
    $extension='png';
}
$filename = $local.'temp/'.$queMed.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extension;	
//echo $filename;
if (file_exists($filename)) 
{
    //do nothing
    //echo "The file $filename exists";	
}
else 
{
    shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in ".$local."PackagesTH_Encrypted/".$ImageRaiz.".".$extension." -out ".$local."temp/".$queMed."/PackagesTH_Encrypted/".$ImageRaiz.".".$extension);
    //echo "Thumbnail Generated";
}

?>
