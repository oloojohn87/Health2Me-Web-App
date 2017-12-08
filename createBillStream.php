<?php
session_start();
// This is for creating a report attachment view of all the available reports for doctor referrals area. It outputs all the available reports with checkbox on each report to enable the user for selecting the reports.
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

 
$ElementDOM = $_GET['ElementDOM'];
$EntryTypegroup = $_GET['EntryTypegroup'];
$Usuario = $_GET['Usuario'];
$pass = '';
$MedID = $_SESSION['MEDID'];
$bill_id = $_GET['billid'];
if(isset($_SESSION['decrypt']))
{
    $pass = $_SESSION['decrypt'];
}
else
{
    $query = $con->prepare("SELECT * FROM encryption_pass ORDER BY changed_on DESC");
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $pass = $row['pass'];
}



$ReportsDisplayed = 0;


	 $tbl_name="usuarios"; // Table name
		
	 
		
		//$queUsu = $_GET['Usuario'];
		//$queMed = $_GET['IdMed'];
	 $queUsu = $Usuario;
	 $queMed = 0;

$sql = $con->prepare("SELECT * FROM tipopin");
$q = $sql->execute();

$Tipo[0]='N/A';
while($row = $sql->fetch(PDO::FETCH_ASSOC)){
    $Tipo[$row['Id']]=$row['NombreEng'];
    $TipoAB[$row['Id']]=$row['NombreCorto'];
    $TipoColor[$row['Id']]=$row['Color'];
    $TipoIcon[$row['Id']]=$row['Icon'];
    $TipoAgrup[$row['Id']]=$row['Agrup'];
}

$Tipo[999]='N/A';
// Meter clases en un Array
$Clase[999]='Episode';
$Clase[0]='Episode';
$Clase[1]='Check or Preventive';
$Clase[2]='Isolated Report';
$Clase[3]='Drug Data';

	
$result = $con->prepare("SELECT * FROM lifepin WHERE IdUsu=? AND (markfordelete IS NULL or markfordelete=0) and Tipo NOT IN (select Id from tipopin where Agrup=9) ORDER BY Fecha DESC ");

$result->bindValue(1, $queUsu, PDO::PARAM_INT);
$result->execute();


$numero=$result->rowCount();
$n=0;

//$inyectHTML = '<div id="ReportStream" style="width:2000px; height:355px; border:solid red;">';
$inyectHTML = '';

while($row = $result->fetch(PDO::FETCH_ASSOC))
{

    $indi = 999;
    if ($row['Tipo'] != null)
    {
        $indi = $row['Tipo'];
    }
    $FechaNum = strtotime($row['Fecha']);
    $FechaBien = date ("M j, Y",$FechaNum);
    
    $extensionR = end(explode('.', $row['RawImage']));
    if($extensionR == 'jpe')
    {
        $extensionR = 'jpeg';
    }
	$ImageRaiz = substr($row['RawImage'],0,strlen($row['RawImage'])-4);
    
    // if(!in_array($row['IdPin'], $deletedreports)){
		//echo "*********************************".$extensionR;
		  if($extensionR=='wav'){
				
					$selecURL =$domain.'/Packages/'.$ImageRaiz.'.'.$extensionR;
					$selecURLAMP =$domain.'/Packages/'.$ImageRaiz.'.'.$extensionR;
				
			}elseif ($extensionR=='pdf')
				{
				$isPDF=1;
				decrypt_files($row['RawImage'],$MedID,$pass);
				///THERE IS A PROBLEM WITH IMAGERAIZ VARIABLE
				$selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				
				$selecURLAMP =$domain.'/temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
				}
             elseif ($extensionR=='png')
             {
                 decrypt_files($row['RawImage'],$MedID,$pass);
				///THERE IS A PROBLEM WITH IMAGERAIZ VARIABLE
				$selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				
				$selecURLAMP =$domain.'/temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
             }
             else if($extensionR=='jpg'|| $extensionR=='JPG' || $extensionR=='jpeg' || $extensionR=='JPEG')
			{
				decrypt_files($row['RawImage'],$MedID,$pass);
				$selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extensionR;
				//echo $selecURL;
				//Adding changes to handle jpg files. It seems in some channel the jpg file are getting converted as png
				if(file_exists('temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extensionR)){
					//echo $selecURL;
					$selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				}else{
					$selecURL=$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				}					
				//echo $selecURL;	
				$selecURLAMP =$domain.'/temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
			}else {
			if	( ($row['CANAL']==7||$row['CANAL']==5||$row['CANAL']== null)){
					if($isPDF==0){
						decrypt_files($row['RawImage'],$MedID,$pass);
						$selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$row['RawImage'];
						$selecURLAMP =$domain.'/temp/'.$MedID.'/Packages_Encrypted/'.$row['RawImage'];
					}
				
			} else {
				if($isPDF==0){
					$subtipo = substr($row['RawImage'], 3 , 2);
					if ($subtipo=='XX')  { decrypt_files($row['RawImage'],$MedID,$pass);$selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$row['RawImage']; }
					else { $selecURL =$domain.'/eMapLife/PinImageSetTH/'.$row['RawImage']; }
					// COMPROBACIÓN DE EXISTENCIA DEL ARCHIVO (PARA LOS CASOS DE EMAPLIFE iOS o ANDROID QUE TODAVIA NO GENERAN THUMBNAILS Y NO REFERENCIAN AL DIRECTORIO -TH
					$file = $selecURL;
					$file_headers = @get_headers($file);
					if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
						$exists = false;
						$selecURL =$domain.'/eMapLife/PinImageSet/'.$row['RawImage'];
					  }
					  else {
						  $exists = true;
						  }
					}
				}
			}
		//}
    /*
    if($extensionR == 'jpe')
    {
        $extensionR = 'jpeg';
    }
	$type=$row['Tipo'];
    
    if($extensionR=='wav')
    {
				
        $selecURL =$domain.'/Packages/'.$ImageRaiz.'.'.$extensionR;
        $selecURLAMP =$domain.'/Packages/'.$ImageRaiz.'.'.$extensionR;
				
    }
    elseif ($extensionR=='pdf')
    {
        $isPDF=1;
        decrypt_files($row['RawImage'],$MedID,$pass);
        $selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
        $selecURLAMP =$domain.'/temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
    }
    elseif ($extensionR=='png')
    {
        decrypt_files($row['RawImage'],$MedID,$pass);
        $selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
        $selecURLAMP =$domain.'/temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
    }
				
			
    else if($extensionR=='jpg'|| $extensionR=='JPG' || $extensionR=='jpeg' || $extensionR=='JPEG')
    {
        decrypt_files($row['RawImage'],$MedID,$pass);
        $selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extensionR;
        //echo $selecURL;
        //Adding changes to handle jpg files. It seems in some channel the jpg file are getting converted as png
        if(file_exists('temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extensionR))
        {
            //echo $selecURL;
            //$selecURL =$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
        }
        else
        {
			$selecURL=$domain.'/temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
        }					
				//echo $selecURL;	
        $selecURLAMP =$domain.'/temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
    }
    else 
    {
        if	($row['CANAL']==7)
        {
            decrypt_files($row['RawImage'],$queUsu,$pass);
            $selecURL =$domain.'/temp/'.$queUsu.'/PackagesTH_Encrypted/'.$row['RawImage'];
            $selecURLAMP =$domain.'/temp/'.$queUsu.'/Packages_Encrypted/'.$row['RawImage'];
				
        } 
        else 
        {
            $subtipo = substr($row['RawImage'], 3 , 2);
            if ($subtipo=='XX')  
            {
                decrypt_files($row['RawImage'],$queUsu,$pass);
                $selecURL =$domain.'/temp/'.$queUsu.'/PackagesTH_Encrypted/'.$row['RawImage']; 
            }
            else 
            { 
                $selecURL =$domain.'/eMapLife/PinImageSetTH/'.$row['RawImage']; 
            }
				// COMPROBACIÓN DE EXISTENCIA DEL ARCHIVO (PARA LOS CASOS DE EMAPLIFE iOS o ANDROID QUE TODAVIA NO GENERAN THUMBNAILS Y NO REFERENCIAN AL DIRECTORIO -TH
            $file = $selecURL;
            $file_headers = @get_headers($file);
            if($file_headers[0] == 'HTTP/1.1 404 Not Found') 
            {
                $exists = false;
                $selecURL =$domain.'/temp/'.$queUsu.'/PackagesTH_Encrypted/'.$row['RawImage'];
            }
            else 
            {
                $exists = true;
            }
        }
    }*/

    $inyectHTML .=' 
        
         <div class="attachments" id="'.$row['IdPin'].'" style="width:150px; height:250px; float:left; margin-right:10px; margin-bottom:20px; -webkit-box-shadow: 3px 3px 14px rgba(25, 25, 25, 0.5); -moz-box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5); box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5);">
           	<div style="border: 1px solid blue; background-color: RGB(200, 200, 200); height:50px; width:148px; border: 1px solid RGB(223,223,223);">
			    <p class="CheckContainer" id="'.$row['IdPin'].'" style="float:left; height:30px; width:20px; padding:0px; margin:0px; margin-top:5px; margin-left:10px;"><span><input type="checkbox" name="numbers[]" class="mc" value="0" id="reportcol'.$row['IdPin'].'" ';
    if($bill_id == $row['bill_id'])
    {
        $inyectHTML .= 'checked disabled';
    }
    $inyectHTML .= '/><label for="reportcol'.$row['IdPin'].'"><span></span></label></span><i class="'.$TipoIcon[$indi].'" icon-large" style="color:RGB(111,111,111); font-size: 1.0em;"></i></p>
		  		<p class="queTIP" id="'.substr($Tipo[$indi],0,12).'" style="float:left; height:20px; width:90px; padding:0px; margin:0px; margin-top:0px; margin-left:10px; color:RGB(65,65,65); font-size:12px; font-family: "Myriad Pro", Myriad, "Liberation Sans", "Nimbus Sans L", "Helvetica Neue", Helvetica, Arial, sans-serif; overflow: hidden;" >'.substr($Tipo[$indi],0,12).'</p>
		  		<p class="'.$FechaBien.'" style="float:left; margin:0px; margin-top:0px;  margin-left:10px; margin-top:-5px; color:RGB(65,65,65); font-size:9px; font-italic:true; font-family: "Myriad Pro", Myriad, "Liberation Sans", "Nimbus Sans L", "Helvetica Neue", Helvetica, Arial, sans-serif;">'.$FechaBien.'</p>		
             		
		  		<span class="label label-info" style="float:right; margin-top:0px; margin-right:10px; font-size:10px; text-shadow:none; text-decoration:none; font-weight:normal; background-color:'.$TipoColor[$indi].'">'.$TipoAB[$indi].'</span>
		 				
		   	</div>';
          
	 
    $inyectHTML .= '<div class="queIMG" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
    $inyectHTML .= '<img src="'.$selecURL.'" style="margin-top:0px;">'; 
    $inyectHTML .= '</div>';
    $inyectHTML .= '</div>';


}

$WidthStream = $numero * 165;
$PARTIALinyectHTML = $inyectHTML;
$PREinyectHTML = '<div style="width: '.$WidthStream.'px; height: 270px; margin-top:10px; margin-left:10px;">';

$inyectHTML = '';
$inyectHTML = $PREinyectHTML.$PARTIALinyectHTML.'</div>';

echo $inyectHTML;

function decrypt_files($rawimage,$queMed,$pass )
{
	
    $ImageRaiz = substr($rawimage,0,strlen($rawimage)-4);
	$extensionR = substr($rawimage,strlen($rawimage)-3,3);
    
    //$ImageRaiz = basename($rawimage);
	//$extensionR = substr($rawimage,strlen($rawimage)-3,3);
	
	/*$filename = 'temp/'.$queMed.'/Packages_Encrypted/'.$rawimage;
	if (file_exists($filename)) 
	{
		//do nothing
		//echo "The file $filename exists";
	}
	else 
	{
		shell_exec('Decrypt.bat Packages_Encrypted '.$rawimage.' '.$queMed .' 2>&1');
		//echo "PDF Generated";	
	}
	*/
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
		$out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in PackagesTH_Encrypted/".$ImageRaiz.".".$extension." -out temp/".$queMed."/PackagesTH_Encrypted/".$ImageRaiz.".".$extension);
		//die($out.' '.$ImageRaiz);
	}




}
