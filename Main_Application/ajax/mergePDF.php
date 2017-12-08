<?php
session_start();
error_reporting(E_ALL ^ E_DEPRECATED);

include 'PDFMerger/PDFMerger.php';

require("environment_detail.php");
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
  
  
$ds = DIRECTORY_SEPARATOR;

/*
unlink('temp/28/merger.pdf');

$pdf = new PDFMerger;

$pdf->addPDF('temp/28/Packages_Encrypted/eML047a3dbec38dc547d76ae80bb60c098ac.pdf', 'all')
	->addPDF('temp/28/Packages_Encrypted/eML049e86cff401c421001a1ac0d6372d3c3.pdf', 'all')
	->addPDF('temp/28/Packages_Encrypted/eML1965031910a6da6efa729b2e38be85be3825b3d35f.pdf', 'all')
	->merge('file', 'temp/28/merger.pdf');
	
	//REPLACE 'file' WITH 'browser', 'download', 'string', or 'file' for output options
	//You do not need to give a file path for browser, string, or download - just the name.
*/

$referralID = $_GET['referralID'];
$pass = $_SESSION['decrypt'];
$queMed = $_SESSION['MEDID'];

$query = $con->prepare("select attachments from doctorslinkdoctors where id=?");
$query->bindValue(1, $referralID, PDO::PARAM_INT);
$result = $query->execute();

$row = $query->fetch(PDO::FETCH_ASSOC);
$idpins = explode(" ", $row['attachments']);


$pdf = new PDFMerger;
$flag=0;
$feedback = "Failed to print files";
//echo count($idpins);
for($i=0;$i<count($idpins);$i++)
{
	$query = $con->prepare("select rawimage from lifepin where idpin=?");
	$query->bindValue(1, $idpins[$i], PDO::PARAM_INT);
	$res = $query->execute();
	
	$filename = $query->fetch(PDO::FETCH_ASSOC);
	
	$rawimage = $filename['rawimage'];
	$path = 'Packages_Encrypted'.$ds.$rawimage;
	$ext = pathinfo($path, PATHINFO_EXTENSION);
	
	//echo $idpins[$i].'  '.$filename['rawimage'].'    '.$ext.'<br>';
	
	if(strtolower($ext) == 'pdf')
	{
		decrypt($rawimage,$queMed,$pass);
		$filepath = 'temp/'.$queMed.'/Packages_Encrypted/'.$rawimage;
		if(file_exists($filepath))
		{
			$pdf->addPDF($filepath, 'all');
			$flag=1;
		}
		else
		{
			$feedback = "Some files were not found.Contact Administrator";
		}
	}
	else if(strtolower($ext) == 'jpg' || strtolower($ext) == 'png')
	{
		decrypt($rawimage,$queMed,$pass);
		$filepath = 'temp/'.$queMed.'/Packages_Encrypted/'.$rawimage;
		$newfilepath = preg_replace('"\.(png|jpg|jpeg)$"', '.pdf',$filepath);
		$command = 'convert '.$filepath.' '.$newfilepath;
		//echo $command;
		shell_exec($command);
		if(file_exists($filepath))
		{
			$pdf->addPDF($newfilepath, 'all');
			$flag=1;
		}
		else
		{
			$feedback = "Some files were not found.Contact Administrator";
		}
	}
	else
	{
		$feedback = "Not all files are printable";
	}
	
	
}

if($flag==1)
{
	$mergedPDFPath = 'temp'.$ds.$queMed.$ds.$referralID.'.pdf';
	$pdf->merge('file', $mergedPDFPath);
	echo 'success';
}
else
{
	echo $feedback;
}



function decrypt($rawimage,$queMed,$pass)
{
	$ImageRaiz = substr($rawimage,0,strlen($rawimage)-4);
	$extensionR = substr($rawimage,strlen($rawimage)-3,3);
	
	$filename = 'temp/'.$queMed.'/Packages_Encrypted/'.$rawimage;
	if (file_exists($filename)) 
	{
		//do nothing
		//echo "The file $filename exists";
	}
	else 
	{
		shell_exec('Decrypt.bat Packages_Encrypted '.$rawimage.' '.$queMed .' '.$pass.' 2>&1');
		//echo 'Decrypt.bat Packages_Encrypted '.$rawimage.' '.$queMed .' '.$pass.' 2>&1';	
	}
	
	if($extensionR=='jpg')
	{
		//die("Found JPG Extension");
		$extension='jpg';
		//return;
	}
	else
	{
		$extension='png';
	}
	$filename = 'temp/'.$queMed.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extension;	
	//echo $filename;
	if (file_exists($filename)) 
	{
		//do nothing
		//echo "The file $filename exists";	
	}
	else 
	{
		shell_exec('Decrypt.bat PackagesTH_Encrypted '.$ImageRaiz.'.'.$extension.' '.$queMed.' '.$pass);
		//echo "Thumbnail Generated";
	}

}









?>