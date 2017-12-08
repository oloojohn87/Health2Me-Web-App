<script src="jquery-lang-js-master/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script type="text/javascript">
	var lang = new Lang('en');
	window.lang.dynamic('th', 'jquery-lang-js-master/js/langpack/th.json');


//alert($.cookie('lang'));

var langType = $.cookie('lang');

if(langType == 'th'){
window.lang.change('th');
//alert('th');
}

if(langType == 'en'){
window.lang.change('en');
//alert('en');
}
	

</script>
<?php

 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$MedID=$_GET['docid'];


         $resultDOC = $con->prepare("SELECT * FROM lifepin WHERE IdMed=?");
		  $resultDOC->bindValue(1, $MedID, PDO::PARAM_INT);
		  $resultDOC->execute();
		  
		  $countDOC=$resultDOC->rowCount();
		  $r=0;
		  $EstadCanal = array(0,0,0,0,0,0,0,0,0,0);
		  $EstadCanalValid = array(0,0,0,0,0,0,0,0,0,0);
		  $EstadCanalNOValid = array(0,0,0,0,0,0,0,0,0,0);
		  $ValidationStatus = array(0,0,0,0,0,0,0,0,0,0,0);
		  while ($rowDOC = $resultDOC->fetch(PDO::FETCH_ASSOC))
		  {
		  	$Valid = $rowDOC['ValidationStatus'];
		  	$esvalido=0;
			//Add changes for null exception
		  	if (is_numeric($Valid)) {$ValidationStatus[$Valid] ++; $esvalido=1;}

		  	$Canal = $rowDOC['CANAL'];
		  	if (is_numeric($Canal)){
		  		$EstadCanal[$Canal] ++;
		  		if ($Valid==0 && $esvalido==1) {$EstadCanalValid[$Canal] ++;} else {$EstadCanalNOValid[$Canal] ++;}
		  		}

		  	$r++;  
		  	
		  }	



		  $ArrayPacientes = array();
		  $numeral=0;
		  $numeralF=0;
		  $antiguo = 30;
		  $NPackets = 0;
		  
		  $resultPAC = $con->prepare("SELECT distinct IdUs FROM doctorslinkusers WHERE IdMED=? ");
		  $resultPAC->bindValue(1, $MedID, PDO::PARAM_INT);
		  $resultPAC->execute();
		  
		  //$countPAC=mysql_num_rows($resultPAC);
		  while ($rowP1 = $resultPAC->fetch(PDO::FETCH_ASSOC))
		  {
		  		$ArrayPacientes[$numeral]=$rowP1['IdUs'];
		  		$numeral++;
		  		$antig = time()-strtotime($rowP1['Fecha']);
		  		$days = floor($antig / (60*60*24));
		  		if ($days<$antiguo) $numeralF++;
		  		
		  		$idEncontrado = $rowP1['IdUs'];
		  		$resultPIN = $con->prepare("SELECT * FROM lifepin WHERE IdUsu = ? ");
				$resultPIN->bindValue(1, $idEncontrado, PDO::PARAM_INT);
				$resultPIN->execute();
				
				$countPIN=$resultPIN->rowCount();
				$NPackets=$NPackets+$countPIN;

		  }
		  
		  $NPacketsMIOS = $NPackets;
		  $MIOS = $numeral;
		  $MIOSF = $numeralF;
		  
		  //$sumatotalPAC = 0;

          $arr=array();

		  $resultCRU = $con->prepare("SELECT * FROM doctorslinkdoctors WHERE IdMED=?");
		  $resultCRU->bindValue(1, $MedID, PDO::PARAM_INT);
		  $resultCRU->execute();
		  
		  //$countCRU=mysql_num_rows($resultCRU);
		  while ($rowCRU = $resultCRU->fetch(PDO::FETCH_ASSOC))
		  {
		  	  $Autorizado = $rowCRU['IdMED2'];
			  $resultPAC2 = $con->prepare("SELECT * FROM doctorslinkusers WHERE IdMED=? ");
			  $resultPAC2->bindValue(1, $Autorizado, PDO::PARAM_INT);
			  $resultPAC2->execute();
			  
			  //$countPAC2=mysql_num_rows($resultPAC2);
			  while ($rowC2 = $resultPAC2->fetch(PDO::FETCH_ASSOC))
			  {
			  	$idEncontrado = $rowC2['IdUs'];
    		  	if (!in_array($idEncontrado, $ArrayPacientes)){
			  		 $ArrayPacientes[$numeral]=$idEncontrado;
			  		 $numeral++;
			  		 $antig = time()-strtotime($rowC2['Fecha']);
			  		 $days = floor($antig / (60*60*24));
			  		 if ($days<$antiguo) $numeralF++;

			  		 $resultPIN = $con->prepare("SELECT * FROM lifepin WHERE IdUsu = ? ");
					 $resultPIN->bindValue(1, $idEncontrado, PDO::PARAM_INT);
					 $resultPIN->execute();
					 
			  		 $countPIN=$resultPIN->rowCount();
			  		 $NPackets=$NPackets+$countPIN;

			  		 }
			  }		 
			  //$sumatotalPAC = $sumatotalPAC + $countPAC2;
		  }
		  
		 /* $arr['CONACCESO'] = $numeral;
		  $arr['CONACCESOF'] = $numeralF;*/
		       $CONACCESO= $numeral;
		      $CONACCESOF = $numeralF;
		  

		  if ($NPackets!=0) $porcentajeCreados = number_format((100*$NPacketsMIOS/$NPackets), 0, ',', ' '); else $porcentajeCreados=0;
          
         $arr['porcentajeCreados']=$porcentajeCreados;
        
         //$test='{"CONACCESO":'. $CONACCESO .',"CONACCESOF",'.$CONACCESOF.',"porcentajeCreados",'.$porcentajeCreados.'}';
        
         $test=json_encode ($arr);

         //echo $test;

         //var_dump(json_decode($test));

        echo '<div class="row-fluid"  style="">	            
		  	    		<input type="hidden" id ="quePorcentaje" value="';
        echo (100-$porcentajeCreados);
        echo'" /> <div class="grid" style="padding:10px; height:110px;">';
		  	    		
		  	    		
		  				
		  				$maximo = max($EstadCanal);
		  				if ($maximo == 0) $maximo=0.0001;
		  				$maximoR = 100;
			
		  				$G0=($EstadCanal[0] * $maximoR) / $maximo;
		  				$P0=($EstadCanalValid[0] * $maximoR) / $maximo;;
		  				$C0='rgba(255,200,49,';
		  				$V0=$EstadCanal[0];
		  				$VV0=$EstadCanalValid[0];
		  				
		  				$G1=($EstadCanal[6] * $maximoR) / $maximo;
		  				$P1=($EstadCanalValid[6] * $maximoR) / $maximo;;
		  				$C1='rgba(115,187,59,';
		  				$V1=$EstadCanal[6];
		  				$VV1=$EstadCanalValid[6];

		  				$G2=($EstadCanal[1] * $maximoR) / $maximo;
		  				$P2=($EstadCanalValid[1] * $maximoR) / $maximo;;
		  				$C2='rgba(215,240,100,';
		  				$V2=$EstadCanal[1];
		  				$VV2=$EstadCanalValid[1];

		  				$G3=($EstadCanal[2] * $maximoR) / $maximo;
		  				$P3=($EstadCanalValid[2] * $maximoR) / $maximo;;
		  				$C3='rgba(185,200,150,';
		  				$V3=$EstadCanal[2];
		  				$VV3=$EstadCanalValid[2];

		  				$G4=($EstadCanal[4] * $maximoR) / $maximo;
		  				$P4=($EstadCanalValid[4] * $maximoR) / $maximo;;
		  				$C4='rgba(145,100,200,';
		  				$V4=$EstadCanal[4];
		  				$VV4=$EstadCanalValid[4];

		  				$G5=($EstadCanal[5] * $maximoR) / $maximo;
		  				$P5=($EstadCanalValid[5] * $maximoR) / $maximo;;
		  				$C5='rgba(105,120,250,';
 		  				$V5=$EstadCanal[5];
		  				$VV5=$EstadCanalValid[5];
		  	    		
		  	    		
		  	    		
		  	    		$titulo="Percentage of the number of packets referred by others to this user, out of the total packets this user can reach ".($NPackets-$NPacketsMIOS)." in ".$NPackets;


			  	    	echo '<div style="width:200px; height:160px; float:left; margin-top:-35px; " title="';
                         $titulo;
                            echo '">';
			  	    	
				        if($_SESSION['CustomLook'] != 'COL'){
						  echo '<div id="gauge" class="200x160px" style="padding:0px;">
			  	    	</div>';
						}
			  	    	echo '</div>	
		  	    		<div style="width:440px; float:right; margin:0px; padding:0px;">
		  	    		<div style="width:100px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px;">
		  	    		
		  	    		<div style="height:80px; width:100px;  text-align:center; margin:0px;">
			  	    		<p  id="TotPats" style=" font-size:'. queFuente($MIOS).'px; font-weight:bold; color: '.$C5.'0.99);padding-top:27px;">'.$MIOS.'</p>
		  	    		</div>';
		  	    		
		  	    		echo '<div style="width:100px;  text-align:center; margin:0px; background-color:'.$C5.'0.99); border:1px solid'.$C5.'0.99); margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; " lang="en">Members</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		
		  	    		<div style="width:100px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px;">
		  	    		
		  	    		<div style="height:80px; width:100px;  text-align:center; margin:0px;"> 
			  	    		<p style=" font-size:'.queFuente($CONACCESO).'px; font-weight:bold; color:'.$C0.'0.99); padding-top:27px;">'.$CONACCESO.'</p>
		  	    		</div>';
		  	    		
		  	    		echo '<div style="width:100px;  text-align:center; margin:0px; background-color:'.$C0.'0.99); border:1px solid'.$C0.'0.99); margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; " lang="en">Reach</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		
		  	    		<div style="width:200px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px;">
		  	    		
		  	    		<div style="height:80px; width:200px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
			  	    		<p style=" font-size:';
                        $accesibles=$MIOSF; 
                        $accesibles2=$MIOS+.000001; 
                        echo '50px; font-weight:bold;'.$C1.'0.99); padding-top:20px;">';
                        echo number_format(($accesibles*100/$accesibles2), 0, ',', ' ').' % ';
                        echo'</p>
			  	    		<p style="margin-top:8px;  padding-top:0px; font-size:';
                        $accesibles=$CONACCESOF; 
                        $accesibles2=$CONACCESO+.000001; 
                        echo '16px; font-weight:bold; color:'.$C1.'0.70);">';
                        echo '( '.number_format(($accesibles*100/$accesibles2), 0, ',', ' ').' % from reach)';
                        echo '</p>
		  	    		</div>
		  	    		
		  	    		<div style="width:200px;  text-align:center; margin:0px; background-color:'.$C1.'0.99); border:1px solid'.$C1.'0.99); margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; " lang="en">New Information</p>
		  	    		</div>	
		  	    		
		  	    		</div>';   
		  	    		
		  	    	
function queFuente ($numero)
{
$queF=10;
switch ($numero)
{
	case ($numero>999 && $numero<9999):	$queF = 30;
										break;
	case ($numero>99 && $numero<1000):	$queF = 50;
										break;
	case ($numero>0 && $numero<100):	$queF = 70;
										break;
}

return ($queF);

}

function queFuente2 ($numero1, $numero2)
{
$queF=10;
$numero= digitos($numero1)+digitos($numero2);
switch ($numero)
{
	case 2:	$queF = 60;
			break;
	case 3:	$queF = 55;
			break;
	case 4:	$queF = 50;
			break;
	case 5:	$queF = 45;
			break;
	case 6:	$queF = 40;
			break;
	case 7:	$queF = 35;
			break;
	case 8:	$queF = 30;
			break;
}

return ($queF);

}

function digitos ($numero)
{
$queF=0;

switch ($numero)
{
	case ($numero>999 && $numero<9999):	$queF = 4;
										break;
	case ($numero>99 && $numero<1000):	$queF = 3;
										break;
	case ($numero>0 && $numero<100):	$queF = 2;
										break;
}

return ($queF);

}
		  	    		
		  	    		
		  	    				  	    		
		  	    			


?>