<?php

 require("environment_detail.php");
 set_time_limit(180);
 
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 
$MedID=28;
$EntryTypegroup=0;


	$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
	mysql_select_db("$dbname")or die("cannot select DB");
	
	
	/* $sql="SELECT * FROM Usuarios where Identif ='$queUsu'";
     $q = mysql_query($sql);
     $row=mysql_fetch_assoc($q);*/
     
     /*$Name = $row['Name'];
     $Surname = $row['Surname'];*/
	 
	 $result = mysql_query("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
	$row = mysql_fetch_array($result);
	$pass=$row['pass'];
     
     // Meter tipos en un Array
     $sql="SELECT * FROM TipoPin";
     $q = mysql_query($sql);
     
     $Tipo[0]='N/A';
     while($row=mysql_fetch_assoc($q)){
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

	 //$email = $row['email'];
     //$hash = md5( strtolower( trim( $email ) ) );
	 //$avat = 'identicon.php?size=50&hash='.$hash;
	 
	 
	 
//$IdUsu=$_GET['USERID'];

//$report= '(1226,1227)';
/*$reports=explode(" ", $Reportids);
$count=count($reports);*/
$i=0;
//$inyectHTML = '<div style="width: 500px; height: 500px; margin-top:10px; margin-left:10px;">';
$inyectHTML = '';
/*while($count>0)
{
//

*/

$query='SELECT * FROM LifePin limit 200';
$result = mysql_query($query);

$numero=mysql_num_rows($result) ;
$n=0;
while($row = mysql_fetch_array($result))
{
   
if ($EntryTypegroup == 0 )
{
	$extensionR = substr($row['RawImage'],strlen($row['RawImage'])-3,3);
	$ImageRaiz = substr($row['RawImage'],0,strlen($row['RawImage'])-4);
	$type=$row['Tipo'];
    if ($extensionR!='jpg')
			{
				
				decrypt_files($row['RawImage'],$MedID,$pass);
				$selecURL ='temp/'.$MedID.'/PackagesTH_Encrypted/'.$ImageRaiz.'.png';
				$selecURLAMP ='temp/'.$MedID.'/Packages_Encrypted/'.$ImageRaiz.$extensionR;
				
			}
			else {
			if	($row['CANAL']==7){
				decrypt_files($row['RawImage'],$MedID,$pass);
				$selecURL ='temp/'.$MedID.'/PackagesTH_Encrypted/'.$row['RawImage'];
				$selecURLAMP ='temp/'.$MedID.'/Packages_Encrypted/'.$row['RawImage'];
				
				
			} else {
				$subtipo = substr($row['RawImage'], 3 , 2);
				if ($subtipo=='XX')  { decrypt_files($row['RawImage'],$MedID,$pass);$selecURL ='temp/'.$MedID.'/PackagesTH_Encrypted/'.$row['RawImage']; }
				else { $selecURL ='eMapLife/PinImageSetTH/'.$row['RawImage']; }
				// COMPROBACIÓN DE EXISTENCIA DEL ARCHIVO (PARA LOS CASOS DE EMAPLIFE iOS o ANDROID QUE TODAVIA NO GENERAN THUMBNAILS Y NO REFERENCIAN AL DIRECTORIO -TH
				$file = $selecURL;
				$file_headers = @get_headers($file);
				if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
			  	  	$exists = false;
			  	  	$selecURL ='eMapLife/PinImageSet/'.$row['RawImage'];
			  	  }
			  	  else {
				  	  $exists = true;
				  	  }
				}
		}
	if(!file_exists($selecURL))
				 continue;
if (!$row['Tipo']){$indi = 999;}else{$indi = $row['Tipo'];};
//echo $Tipo[$indi];
//echo $Tipo[$indi];

//if (!$row['EvRuPunt']){$indi2 = 999;}else{$indi2 = $row['EvRuPunt'];}; 

     /*$Evento = $row['Evento'];
     $sqlE="SELECT * FROM UsuEventos where IdUsu ='$queUsu' and IdEvento ='$Evento' ";
     $qE = mysql_query($sqlE);
     $rowE=mysql_fetch_assoc($qE);
     $EventoALFA = $rowE['Nombre'];
     
     if (!$row['EvRuPunt']){
    	 $indi2 = 999; 
    	 $salida=$EventoALFA; 
     }else{
     	$indi2 = $row['EvRuPunt']; 
     	$salida=$Clase[$indi2]; 
     }; */

// ***** CODE VARIATION FROM CREATIMELINE *****
//if ($n>0) $cadena=$cadena.',';
// ***** CODE VARIATION FROM CREATIMELINE *****
$n++;

if (!$row['Tipo']){$indi = 999;}else{$indi = $row['Tipo'];};

$FechaNum = strtotime($row['Fecha']);
$FechaBien = date ("M j, Y",$FechaNum);


$inyectHTML .=' <li>';


          
						$inyectHTML .= '<div class="attachments" id="'.$row['IdPin'].'" style="width:150px; height:250px; float:left; margin-right:10px; margin-bottom:20px; -webkit-box-shadow: 3px 3px 14px rgba(25, 25, 25, 0.5); -moz-box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5); box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5);">
						<div style="border: 1px solid blue; background-color: RGB(200, 200, 200); height:50px; width:auto; border: 1px solid RGB(223,223,223);">
						
			    		 <p style="float:left; height:30px; width:20px; padding:0px; margin:0px; margin-top:10px; margin-left:10px;"><i class="'.$TipoIcon[$indi].'" icon-large" style="color:RGB(111,111,111); font-size: 1.0em;"></i></p>
						<p class="queTIP" id="'.substr($Tipo[$indi],0,12).'" style="float:left; height:20px; width:90px; padding:0px; margin:0px; margin-top:0px; margin-left:10px; color:RGB(65,65,65); font-size:12px; font-family: "Myriad Pro", Myriad, "Liberation Sans", "Nimbus Sans L", "Helvetica Neue", Helvetica, Arial, sans-serif; overflow: hidden;" >'.substr($Tipo[$indi],0,12).'</p>
						<p class="'.$FechaBien.'" style="float:left; margin:0px; margin-top:0px;  margin-left:10px; margin-top:-5px; color:RGB(65,65,65); font-size:9px; font-italic:true; font-family: "Myriad Pro", Myriad, "Liberation Sans", "Nimbus Sans L", "Helvetica Neue", Helvetica, Arial, sans-serif;">'.$FechaBien.'</p>		
             		
						<span class="label label-info" style="float:right; margin-top:0px; margin-right:10px; font-size:10px; text-shadow:none; text-decoration:none; font-weight:normal; background-color:'.$TipoColor[$indi].'">'.$TipoAB[$indi].'</span>				
						</div>';
						$inyectHTML .= '<div class="Channel" id="'.$row['CANAL'].'"></div>';
						$inyectHTML .= '<div class="queIMG" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
						$inyectHTML .= '<img src="'.$selecURL.'">'; 
						$inyectHTML .= '</div>';
						$inyectHTML .= '</div>';
						 $inyectHTML .= '</li>';


		}
		
	//$count=$count-1;
	$i++;
	}
	//$inyectHTML .= ' </div>';

echo $inyectHTML;

// ***** CODE VARIATION FROM CREATIMELINE *****

function decrypt_files($rawimage,$queMed,$pass )
{
	$ImageRaiz = substr($rawimage,0,strlen($rawimage)-4);
	$extensionR = substr($rawimage,strlen($rawimage)-3,3);
	
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
	}*/
	
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

?>