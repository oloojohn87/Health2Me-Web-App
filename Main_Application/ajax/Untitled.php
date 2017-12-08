<?php
		<div class="scroll-pane horizontal-only notes" style="height: 290px; width:97%; border:1px solid #cacaca; margin-top:10px; background-color:white;" >
        <div style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;">

	
	$sql1="SELECT Idpin FROM lifepin where IdUsu ='$IdUsu' and IdMed IS NOT NULL and (IdMed NOT IN ((select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$IdMed'))) and IdMed NOT IN (Select idmed2 from doctorslinkdoctors where idmed='$IdMed' and IdPac='$IdUsu'))";
	$q1=mysql_query($sql1);

	$size=0;
	$size1=0;
	$blindReportId=array();
	$PendingReportID=array();

	while($row1=mysql_fetch_assoc($q1)){
		
		$ReportId=$row1['Idpin'];
		$query="SELECT estado FROM doctorslinkusers where IdMed='$IdMed' and IdUs='$IdUsu' and Idpin='$ReportId' ";
		$q11=mysql_query($query);
		if($rowes=mysql_fetch_assoc($q11)){
			$estad=$rowes['estado'];
			if($estad==1){
				$PendingReportID[$size1]=$ReportId;
				$size1++;
				//echo '<input type="hidden" id="Idpin">'
			}
		}else{
			$blindReportId[$size]=$ReportId;
			$size++;
		}
		
	}
	//echo '</fieldset>';
	$sql="SELECT * FROM lifepin where IdUsu ='$IdUsu' ORDER by Fecha DESC";
	 
    $q = mysql_query($sql);
	
/*echo "*******************************************   ";
echo $sql;
echo " xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx ";
echo $q;
$row = mysql_fetch_assoc($q);
echo " RRR  ";
echo $row['id'];
echo " RRR  ";
*/
while($row=mysql_fetch_assoc($q)){

     $Evento = $row['Evento'];
     $sqlE="SELECT * FROM usueventos where IdUsu ='$IdUsu' and IdEvento ='$Evento' ";
     $qE = mysql_query($sqlE);
     $rowE=mysql_fetch_assoc($qE);
     $EventoALFA = $rowE['Nombre'];

     $IdCrea = $row['IdMed'];
     $sqlD="SELECT * FROM doctors where id ='$IdCrea'";
     $qD = mysql_query($sqlD);
     $rowD=mysql_fetch_assoc($qD);
     $nameDoctor = $rowD['Name'].' '.$rowD['Surname'];


$extensionR = substr($row['RawImage'],strlen($row['RawImage'])-3,3);
$ImageRaiz = substr($row['RawImage'],0,strlen($row['RawImage'])-4);

if ($extensionR!='jpg')
{
	$selecURL =$domain.'/PackagesTH/'.$ImageRaiz.'.png';
	$selecURLAMP =$domain.'/Packages/'.$ImageRaiz.$extensionR;
}
else {
	
if	($row['CANAL']==7){
	$selecURL =$domain.'/PackagesTH/'.$row['RawImage'];
	$selecURLAMP =$domain.'/Packages/'.$row['RawImage'];
}else	{
	$subtipo = substr($row['RawImage'], 3 , 2);
	if ($subtipo=='XX')  { $selecURL =$domain.'/Packages/'.$row['RawImage']; }
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

	
if (!$row['Tipo']){$indi = 999;}else{$indi = $row['Tipo'];};

$CountTip[$TipoGroup[$indi]] ++;

//echo "-----------------------------------------------------------";
//This is the area where we have to create blind reports.
?>
          <div class="note2" id="<?php echo $row['IdPin']; ?>" style="width:150px; height:250px; float:left; margin-right:10px; margin-bottom:20px; -webkit-box-shadow: 3px 3px 14px rgba(25, 25, 25, 0.5); -moz-box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5); box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5);">
          <!--<div  id="<?php echo $row['RawImage']; ?>" style="width:140px; height:180px; overflow: hidden"></div>-->
          	<div style="border: 1px solid blue; background-color: RGB(242,242,242); height:50px; width:148px; border: 1px solid RGB(223,223,223);">
		  		<p style="float:left; height:30px; width:20px; padding:0px; margin:0px; margin-top:10px; margin-left:10px;"><i class="<?php echo $TipoIcon[$indi];?> icon-large" style="color:RGB(111,111,111); font-size: 1.0em;"></i></p>
		  		<p class="queTIP" id="<?php if (!$row['Tipo']){$indi = 999;}else{$indi = $row['Tipo'];};echo $Tipo[$indi];?>" style='float:left; height:20px; width:90px; padding:0px; margin:0px; margin-top:0px; margin-left:10px; color:RGB(65,65,65); font-size:12px; font-family: "Myriad Pro", Myriad, "Liberation Sans", "Nimbus Sans L", "Helvetica Neue", Helvetica, Arial, sans-serif; overflow: hidden;' ><?php echo $Tipo[$indi];?></p>
		  		<!--<span class="label label-info" style="float:right; margin-top:10px; margin-right:10px; font-size:10px; text-shadow:none; text-decoration:none; font-weight:normal;">P R O</span>-->
		  		<!--<span class="inf_link" style="margin-left:5px; background-color:red; background:blue; color:white;"><?php echo $CountTip[$TipoGroup[$indi]];?></span>-->
		  		<p class="queFEC" id="<?php 
            $FechaNum = strtotime($row['Fecha']);
            $FechaBien = date ("M j, Y",$FechaNum);
            echo $FechaBien;
            ?>" style='float:left; margin:0px; margin-top:0px;  margin-left:10px; margin-top:-5px; color:RGB(165,165,165); font-size:10px; font-italic:true; font-family: "Myriad Pro", Myriad, "Liberation Sans", "Nimbus Sans L", "Helvetica Neue", Helvetica, Arial, sans-serif;'><?php  echo $FechaBien; ?></p>		
             		
		  		<span class="label label-info" style="float:right; margin-top:0px; margin-right:10px; font-size:10px; text-shadow:none; text-decoration:none; font-weight:normal; background-color:<?php echo $TipoColor[$indi];?>"><?php echo $TipoAB[$indi];?></span>
		 		<p><i class="icon-eye-open icon-large" style="float:left; margin-left:10px; margin-top:-5px; color:RGB(191,191,191); font-size: 0.8em;"></i></p>
		  		<p><i class="icon-share-alt icon-large" style="float:left; margin-left:10px;  margin-top:-5px; color:RGB(191,191,191); font-size: 0.8em;"></i></p>
 		
		   	</div>
          
			 <?php if(!in_array($row['IdPin'], $blindReportId)){
			 	if(!in_array($row['IdPin'], $PendingReportID)){
			    echo '<div class="queIMG" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
			 	echo '<img src="'.$selecURL.'" alt="" style="margin-top:0px;">'; 
			 	echo '</div>';
				}else{
					echo '<div class="quePEN" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
			 		echo '<img src="'.$domain.'/PackagesTH/lockedfile.png" alt="" style="margin-top:0px;opacity:0.7;">';
			 		echo '</div>';
				}
			 }else{
			 	
			 	echo '<div class="queBLD" id="'.$row['RawImage'].'" style="background-color: white; height:150px; width:150px;  padding:0px; margin:0px; overflow: hidden;">';
			 	echo '<img src="'.$domain.'/PackagesTH/lockedfile.png" alt="" style="margin-top:0px;opacity:0.7;">';
			 	echo '</div>';
				
				//echo '<p><i class="icon-lock" style="float:center; margin-left:auto; margin-top:auto; color:RGB(100,100,100); font-size: 1.0em;"></i></p>';
			 }?>
			 <!-- <img src="<?php echo $selecURL; ?>" alt="" style="margin-top:0px;"> -->
          
          <div style="border: 1px solid blue; background-color: <?php echo $TipoColor[$indi];?>; height:48px; width:148px; border: 1px solid #22aeff;">
			  <p class="queEVE" id="<?php if (!$row['EvRuPunt']){$indi = 999; $salida=$EventoALFA; }else{$indi = $row['EvRuPunt']; $salida=$Clase[$indi]; }; echo $salida;?>" style="text-align:center; margin-top:12px; color:white; font-size:10px;"><?php echo $salida;?></p>
			  <p class="queEVE" id="<?php if (!$row['EvRuPunt']){$indi = 999; $salida=$EventoALFA; }else{$indi = $row['EvRuPunt']; $salida=$Clase[$indi]; }; echo $salida;?>" style="text-align:center; margin-top:-15px; color:white; font-size:10px;"><?php echo $nameDoctor.' ('.$row['IdMed'].')';?></p>
          </div>
                   

};
?>
        </div>
        </div>  


?>