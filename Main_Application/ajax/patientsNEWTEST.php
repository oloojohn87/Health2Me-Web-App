<?php
session_start();
 require("environment_detail.php");
 require_once("displayExitClass.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    echo "<META http-equiv='refresh' content='0;URL=index.html'>";
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

$NombreEnt = $_SESSION['Nombre'];
$PasswordEnt = $_SESSION['Password'];
$MEDID = $_SESSION['MEDID'];
$Acceso = $_SESSION['Acceso'];
$privilege=$_SESSION['Previlege'];
if ($Acceso != '23432')
{
$exit_display = new displayExitClass();

$exit_display->displayFunction(1);
die;
}

					// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			

$result = $con->prepare("SELECT * FROM doctors where id=?");
$result->bindValue(1, $MEDID, PDO::PARAM_INT);
$result->execute();

$count=$result->rowCount();
$row = $result->fetch(PDO::FETCH_ASSOC);
$success ='NO';
if($count==1){
	$success ='SI';
	$MedID = $row['id'];
	$MedUserEmail= $row['IdMEDEmail'];
	$MedUserName = $row['Name'];
	$MedUserSurname = $row['Surname'];
	$MedUserLogo = $row['ImageLogo'];
	$IdMedFIXED = $row['IdMEDFIXED'];
	$IdMedFIXEDNAME = $row['IdMEDFIXEDNAME'];
	
}
else
{
$exit_display = new displayExitClass();

$exit_display->displayFunction(2);
die;
}


//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks");
$result = $con->prepare("SELECT * FROM lifepin");
$result->execute();

?>
<!DOCTYPE html>
<html lang="en" style="background: #F9F9F9;"><head>
    <meta charset="utf-8">
    <title lang="en">Inmers - Center Management Console</title>
    <link rel="icon" type="image/ico" href="favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    
    <link rel="stylesheet" href="css/jquery-ui-1.8.16.custom.css" media="screen"  />
    <link rel="stylesheet" href="css/fullcalendar.css" media="screen"  />
    <link rel="stylesheet" href="css/chosen.css" media="screen"  />
    <link rel="stylesheet" href="css/datepicker.css" >
    <link rel="stylesheet" href="css/colorpicker.css">
    <link rel="stylesheet" href="css/glisse.css?1.css">
    <link rel="stylesheet" href="css/jquery.jgrowl.css">
    <link rel="stylesheet" href="js/elfinder/css/elfinder.css" media="screen" />
    <link rel="stylesheet" href="css/jquery.tagsinput.css" />
    <link rel="stylesheet" href="css/demo_table.css" >
    <link rel="stylesheet" href="css/jquery.jscrollpane.css" >
    <link rel="stylesheet" href="css/validationEngine.jquery.css">
    <link rel="stylesheet" href="css/jquery.stepy.css" />
    
	<link rel="stylesheet" href="css/icon/font-awesome.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" href="css/toggle-switch.css">
    
    <?php
    if ($_SESSION['CustomLook']=="COL") { 
    ?>
        <link href="css/styleCol.css" rel="stylesheet">
    <?php } ?>

 
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/icons/favicon.ico">
	
	<!-- Create language switcher instance and set default language to en-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script type="text/javascript">
	var lang = new Lang('en');
	window.lang.dynamic('th', 'jquery-lang-js-master/js/langpack/th.json');


function delete_cookie( name ) {
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function setCookie(name,value,days) {
confirm('Would you like to switch languages?');
delete_cookie('lang');
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
	
	 pageRefresh();
}

function setCookie2(name,value,days) {
//confirm('Would you like to switch languages?');
delete_cookie('lang');
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
	
	 pageRefresh();
}

function pageRefresh(){
location.reload();
}

//alert($.cookie('lang'));

var langType = $.cookie('lang');

if(langType == 'th'){
var language = 'th';
}else{
var language = 'en';
}

if(langType == 'th'){
setTimeout(function(){
window.lang.change('th');
lang.change('th');
//alert('th');
}, 5000);
}

if(langType == 'en'){
setTimeout(function(){
window.lang.change('en');
lang.change('en');
//alert('th');
}, 5000);
}
	

</script>
    
    <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-37863944-1']);
  _gaq.push(['_setDomainName', 'health2.me']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
  </head>

  <body style="background: #F9F9F9;">
<!--<div class="loader_spinner"></div>-->
<input type="hidden" id="NombreEnt" value="<?php echo $NombreEnt; ?>">
<input type="hidden" id="PasswordEnt" value="<?php echo $PasswordEnt; ?>">
<input type="hidden" id="UserHidden">

	<!--Header Start-->
	<div class="header" >
     	<input type="hidden" id="USERDID" Value="<?php if(isset($USERID)) echo $USERID; ?>">	
    	<input type="hidden" id="MEDID" Value="<?php echo $MedID; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">	
  		
        <?php
  		        if ($_SESSION['CustomLook'] == 'COL') {
                    echo '<a href="index-col.html" class="logo"><h1>health2.me</h1></a>';
                } else {
                    echo '<a href="index.html" class="logo"><h1>health2.me</h1></a>';
                }    
           
           ?>
           <div style="float:left;">
		   <a href="#en" onclick="setCookie('lang', 'en', 30); return false;"><img src="images/icons/english.png"></a>
		   </br>
			<a href="#sp" onclick="setCookie('lang', 'th', 30); return false;"><img src="images/icons/spain.png"></a>
			</div>
           <div class="pull-right">
           
            
           <!--Button User Start-->
		   <div class="btn-group pull-right" >
		   <?php
           $current_encoding = mb_detect_encoding($MedUserName, 'auto');
			$show_text = iconv($current_encoding, 'ISO-8859-1', $MedUserName);

			$current_encoding = mb_detect_encoding($MedUserSurname, 'auto');
			$show_text2 = iconv($current_encoding, 'ISO-8859-1', $MedUserSurname); 
			?>
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
              <span class="name-user"><strong lang="en">Welcome</strong> Dr, <?php echo $show_text.' '.$show_text2; ?></span> 
             <?php 
             $hash = md5( strtolower( trim( $MedUserEmail ) ) );
             $avat = 'identicon.php?size=29&hash='.$hash;
			?>	
              <span class="avatar" style="background-color:WHITE;"><img src="<?php echo $avat; ?>" alt="" ></span> 
              <span class="caret"></span>
            </a>
            <div class="dropdown-menu" id="prof_dropdown">
            <div class="item_m"><span class="caret"></span></div>
            <ul class="clear_ul" >
			<li>

            <a href="MainDashboard.php">    
			<i class="icon-globe" lang="en"></i> Home</a></li>
              
              <li><a href="medicalConfiguration.php" lang="en"><i class="icon-cog"></i> Settings</a></li>
              <li><a href="logout.php" lang="en"><i class="icon-off"></i> Sign Out</a></li>
            </ul>
            </div>
          </div>
          <!--Button User END-->  
          
          </div>
    </div>
    <!--Header END-->
    
    <!--Content Start-->
	<div id="content" style="background: #F9F9F9; padding-left:0px;">
    
    	    
	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     <ul class="menu-speedbar">
		
    	 <li><a href="MainDashboard.php" lang="en">Home</a></li>
         <?php if ($privilege!=3)
		 {
		      echo "<li><a href=\"dashboard.php\" lang=\"en\" >Dashboard</a></li>";
         }   
         ?>    
    	 <li><a href="patients.php" class="act_link"  lang="en" >Members</a></li>
		 <?php if ($privilege==1)
		 {
				 echo '<li><a href="medicalConnections.php"  lang="en">Doctor Connections</a></li>';
				 echo '<li><a href="PatientNetwork.php"  lang="en">Member Network</a></li>';
		 }
		 ?>
         <li><a href="medicalConfiguration.php" lang="en">Configuration</a></li>
         <li><a href="logout.php" style="color:yellow;" lang="en">Sign Out</a></li>
     </ul>

     
     </div>
     </div>
     <!--SpeedBar END-->
     
     
     
     <!--CONTENT MAIN START-->
     <div class="content">
     
         <div class="grid" class="grid span4" style="width:1000px; margin: 0 auto; margin-top:30px; padding-top:30px;">

	     	 <span class="label label-info" style="left:0px; margin-left:30px; font-size:30px;" lang="en">Members</span>     
     
     	  <div class="row-fluid" style="height:170px; width:1000px; margin:0 auto;">	            
		 
		  <div style="margin:15px; margin-top:5px;">
		  <?php
		  // Sección para construir la información estadística del Médico (Dashboard: relativo a "packets")  
		  //$resultDOC = mysql_query("SELECT * FROM lifepin");
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
		  
		  $resultPAC = $con->prepare("SELECT * FROM doctorslinkusers WHERE IdMED=? ");
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
		  
		  $CONACCESO = $numeral;
		  $CONACCESOF = $numeralF;
		  

		  if ($NPackets!=0) $porcentajeCreados = number_format((100*$NPacketsMIOS/$NPackets), 0, ',', ' '); else $porcentajeCreados=0;
		  
		  // Variante para calcular de forma más ajustada el porcentaje de packetes sobre el total de los paquetes de los pacientes que están vinculados a mi. (FORMA LARGA)
 
		  // Sección para construir la información estadística del Médico (Dashboard: relativo a "packets")  
		  $hash = md5( strtolower( trim( $MedUserEmail ) ) );
		  $avat = 'identicon.php?size=75&hash='.$hash;
			?>	

	  	    		<div class="row-fluid"  style="">	            
		  	    		<input type="hidden" id ="quePorcentaje" value="<?php echo (100-$porcentajeCreados) ?>" /> 
		  	    		<div class="grid" style="padding:10px; height:110px;">
		  	    		
		  	    		<?php
		  				
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
		  	    		?>
		  	    		
		  	    		<!--Pie Chart-->
		  	    		<?php $titulo="Percentage of the number of packets referred by others to this user, out of the total packets this user can reach ".($NPackets-$NPacketsMIOS)." in ".$NPackets; ?>
			  	    	<div style="width:200px; height:160px; float:left; margin-top:-35px; " title="<?php echo $titulo; ?>">
			  	    	
						<?php if($_SESSION['CustomLook'] != 'COL'){ ?>
						<div id="gauge" class="200x160px" style="padding:0px;">
			  	    	</div>
						<?php } ?>
			  	    	</div>
			  	    	<!--Pie END-->
			  	    		
		  	    		<div style="width:440px; float:right; margin:0px; padding:0px;"><!-- WRAPPER DE ESTA AREA --->
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<div style="width:100px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px;">
		  	    		
		  	    		<div style="height:80px; width:100px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
			  	    		<p  id="TotPats" style=" font-size:<?php echo queFuente($MIOS);?>px; font-weight:bold; color: <?php echo $C5.'0.99)' ?>; padding-top:27px;"><?php echo $MIOS ?></p>
		  	    		</div>
		  	    		
		  	    		<div style="width:100px;  text-align:center; margin:0px; background-color: <?php echo $C5.'0.99)' ?>; border:1px solid <?php echo $C5.'0.99)' ?>; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; " lang="en">Members</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<div style="width:100px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px;">
		  	    		
		  	    		<div style="height:80px; width:100px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
			  	    		<p style=" font-size:<?php echo queFuente($CONACCESO);?>px; font-weight:bold; color: <?php echo $C0.'0.99)' ?>; padding-top:27px;"><?php echo $CONACCESO ?></p>
		  	    		</div>
		  	    		
		  	    		<div style="width:100px;  text-align:center; margin:0px; background-color: <?php echo $C0.'0.99)' ?>; border:1px solid <?php echo $C0.'0.99)' ?>; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; " lang="en">Reach</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->

		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS (TIPO ANCHO PARA 2 VALORES) ---->
		  	    		<div style="width:200px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px;">
		  	    		
		  	    		<div style="height:80px; width:200px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
			  	    		<p style=" font-size:<?php $accesibles=$MIOSF; $accesibles2=$MIOS+.000001; echo 50/*echo queFuente2($accesibles,$accesibles2);*/?>px; font-weight:bold; color: <?php echo $C1.'0.99)' ?>; padding-top:20px;"><?php echo number_format(($accesibles*100/$accesibles2), 0, ',', ' ').' % ' ?></p>
			  	    		<p style="margin-top:8px;  padding-top:0px; font-size:<?php $accesibles=$CONACCESOF; $accesibles2=$CONACCESO+.000001; echo 16/*echo queFuente2($accesibles,$accesibles2);*/?>px; font-weight:bold; color: <?php echo $C1.'0.70)' ?>; "><?php echo '( '.number_format(($accesibles*100/$accesibles2), 0, ',', ' ').' % from reach)' ?></p>
		  	    		</div>
		  	    		
		  	    		<div style="width:200px;  text-align:center; margin:0px; background-color: <?php echo $C1.'0.99)' ?>; border:1px solid <?php echo $C1.'0.99)' ?>; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; " lang="en">New Information</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS (TIPO ANCHO PARA 2 VALORES) ---->	
		  	    		<!---- BOLA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<!--
		  	    		<div style="width:100px; height:100px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 50px; -moz-border-radius: 50px; border-radius: 50px; margin-left:10px;">
		  	    		
		  	    		<div style="height:60px; width:100px;  text-align:center; margin:0px;">  
			  	    		<p style=" font-size:<?php echo queFuente2($accesibles, $accesibles2);?>px; font-weight:bold; color: <?php echo $C0.'0.99)' ?>; padding-top:27px;"><?php echo $accesibles ?></p>
		  	    		</div>
		  	    		
		  	    		
		  	    		<div style="height:40px; width:98px; margin:0px; padding:0px; text-align:center; margin:0px; background-color: <?php echo $C0.'0.99)' ?>; border:1px solid <?php echo $C0.'0.99)' ?>;  -webkit-border-bottom-right-radius: 40px; -moz-border-bottom-right-radius: 40px; border-bottom-right-radius: 40px; -webkit-border-bottom-left-radius: 40px; -moz-border-bottom-left-radius: 40px; border-bottom-left-radius: 40px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; "></p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		-->
		  	    		<!---- BOLA DE PRESENTACIÓN DE NÚMEROS ---->

		  	    		</div>
  	  	                <div class="clear"></div>   
		  	    		
		  	    	


		  	    		<?php
/*
		  echo "<br>\n"; 
		  echo "<br>\n"; 
		  echo "<br>\n"; 
		  echo "<br>\n"; 

		  echo "TOTAL PACKETS: ";
		  echo $r;
		  echo "<br>\n"; 	
		  echo "EMap: ";
		  echo $EstadCanal[0].' ('.$EstadCanalValid[0].' - '.$EstadCanalNOValid[0].')';
		  echo "<br>\n"; 	
		  echo "EMap PROF: ";
		  echo $EstadCanal[6].' ('.$EstadCanalValid[6].' - '.$EstadCanalNOValid[6].')';
		  echo "<br>\n"; 		  
		  echo "SecureEMapMail: ";
		  echo $EstadCanal[1].' ('.$EstadCanalValid[1].' - '.$EstadCanalNOValid[1].')';
		  echo "<br>\n"; 	
		  echo "SecurePrinter: ";
		  echo $EstadCanal[2].' ('.$EstadCanalValid[2].' - '.$EstadCanalNOValid[2].')';
		  echo "<br>\n"; 	
		  echo "SecureCloud: ";
		  echo $EstadCanal[3].' ('.$EstadCanalValid[3].' - '.$EstadCanalNOValid[3].')';
		  echo "<br>\n"; 	
		  echo "XDS: ";
		  echo $EstadCanal[4].' ('.$EstadCanalValid[4].' - '.$EstadCanalNOValid[4].')';
		  echo "<br>\n"; 	
		  echo "Web Inmers: ";
		  echo $EstadCanal[5].' ('.$EstadCanalValid[5].' - '.$EstadCanalNOValid[5].')';
		  echo "<br>\n"; 	
		  echo "<br>\n"; 	
		  echo "Válidos: ";
		  echo $ValidationStatus[0];
		  echo "<br>\n"; 	
		  echo "No Válidos: ";
		  echo $r-$ValidationStatus[0];
		  echo "<br>\n"; 	
*/

		  	    		?>
		  	    		
		  	    		
		  	    		
		  	    				  	    		
		  	    		</div>
		  	    	</div>	


    	  </div>
	    
	   </div>

     
        <!--Search bar Start-->
        <div class="grid" style="width:96%; margin: 0 auto; margin-top:30px;">
          <div class="grid-title">
           <div class="pull-left" lang="en"><div class="fam-database-lightning" style="margin-right:10px;"></div>Members Database</div>
           <div class="pull-right"></div>
           <div class="clear"></div>   
          </div>
          
          <div class="search-bar">
          
          	<input lang="en" type="text" class="span" name="" placeholder="Name" style="width:200px;" id="SearchUser"> 
          	<input lang="en" type="button" class="btn btn-primary" value="Search" style="margin-left:50px;" id="BotonBusquedaPac">
         	<img src="images/load/8.gif" alt="" style="margin-left:50px; display: none;" id="Wait1">
         	
			<div style="float:right; margin-right:100px;">
				<label class="checkbox toggle candy blue" onclick="" style="width:100px;">
					<input type="checkbox" id="RetrievePatient" name="RetrievePatient" />
					<p>
						<span title="Search in only in group" lang="en">Group</span>
						<span title="Search all patients" lang="en">All</span>
					</p>
					
					<a class="slide-button"></a>
				</label>
   			</div>
          </div>
          
          <div class="grid">
          <table class="table table-mod" id="TablaPac">
          </table> 
  
          </div>
        </div>
        <!--Search bar END-->

       

 

     </div>
     <!--CONTENT MAIN END-->
     </div>
    </div>
    <!--Content END-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
	<script src="TypeWatch/jquery.typewatch.js"></script>
	<!--Added for real-time notifications start-->
	<script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
    <link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
	<script src="realtime-notifications/pusher.min.js"></script>
	<script src="realtime-notifications/PusherNotifier.js"></script>
	
	
	 <script>
		$(function() {
	    var pusher = new Pusher('d869a07d8f17a76448ed');
	    var channel_name=$('#MEDID').val();
		var channel = pusher.subscribe(channel_name);
		var notifier=new PusherNotifier(channel);
		
	  });
	  
	 </script>
	<!--Added for real-time notifications stop-->
    <script type="text/javascript" >
    
	var timeoutTime = 18000000;
	//var timeoutTime = 300000;  //5minutes
	var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);


	var active_session_timer = 60000; //1minute
	var sessionTimer = setTimeout(inform_about_session, active_session_timer);

	//This function is called at regular intervals and it updates ongoing_sessions lastseen time
	function inform_about_session()
	{
		$.ajax({
			url: '<?php echo $domain?>/ongoing_sessions.php?userid='+<?php echo $_SESSION['MEDID'] ?>,
			success: function(data){
			//alert('done');
			}
		});
		clearTimeout(sessionTimer);
		sessionTimer = setTimeout(inform_about_session, active_session_timer);
	}

	function ShowTimeOutWarning()
	{
		alert ('Session expired');
		var a=0;
		window.location = 'timeout.php';
	}
	
	
	
	
    $(document).ready(function() {
	
	$('body').bind('mousedown keydown', function(event) {
        clearTimeout(timeoutTimer);
        timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
    });
	
	
    $("#SearchUser").typeWatch({
				captureLength: 1,
				callback: function(value) {
					$("#BotonBusquedaPac").trigger('click');
					//alert('searching');
				}
	});
	$("#RetrievePatient").click(function(event) {
   		 $('#BotonBusquedaPac').trigger('click');
   	});
    $("#BotonBusquedaPac").click(function(event) {
     		var queMED = $("#MEDID").val();
    	    var UserInput = $('#SearchUser').val();
    	    //alert(UserInput);
    	    /*
    	    var IdUs =156;
    	    var UserInput = $('#SearchUser').val();
                                  
    	    var serviceURL = 'http://www.health2.me/getpines.php' + '?Usuario=' + UserInput; 
    	    getLifePines(serviceURL);
    	    var longit = Object.keys(pines).length;	
    	    */
    	    //alert (longit);
    	   
        //alert ('ajax start');
            $('#Wait1').show();
   

			var onlyGroup=0;
			if ($('#RetrievePatient').is(":checked")){
			onlyGroup=1;
			}else{
			onlyGroup=1; //Chnaged the value from 1 to 0
			}
		   
    	     if(UserInput===""){
			    UserInput=-111;
			 }
			// alert(UserInput);
    	    setTimeout(function(){
             var queUrl ='getFullUsersMEDNEW.php?Usuario='+UserInput+'&IdMed='+queMED+'&Group='+onlyGroup;
			//alert(queUrl);
    	    $('#TablaPac').load(queUrl);
    	    //$('#TablaPac').trigger('click');
    	    $('#TablaPac').trigger('update');
            $('#Wait1').hide();  
            }, 500);
           
			
  	    
    });

   /* $("#BotonBusquedaPacCOMP").click(function(event) {
    	    var IdUs =156;
    	    var UserInput = $('#SearchUserYCOMP').val();
                                  
    	    var serviceURL = '<?php echo $domain;?>/getpines.php' + '?Usuario=' + UserInput; 
    	    getLifePines(serviceURL);
    	    var longit = Object.keys(pines).length;	
    	    //alert (longit);
    	    var queUrl ='getFullUsers.php?Usuario='+UserInput+'&NReports='+longit;
    	    $('#TablaPacCOMP').load(queUrl);
    	    //$('#TablaPac').trigger('click');
    	    $('#TablaPacCOMP').trigger('update');
    });
*/     

     $(".CFILA").live('click',function() {
     	var myClass = $(this).attr("id");
		var queMED = $("#MEDID").val();
		document.getElementById('UserHidden').value=myClass;
		//alert(document.getElementById('UserHidden').value);
		window.location.replace('patientdetailMED-new.php?IdUsu='+myClass);
		//alert("Here");
     	//window.location.replace('patientdetailMED.php');
		}); 
  
     /*
     $('#TablaPac').bind('click', function() {
          	
          	var NombreEnt = $('#NombreEnt').val();
            var PasswordEnt = $('#PasswordEnt').val();
                                   
          	window.location.replace('patientdetail.php?Nombre='+NombreEnt+'&Password='+PasswordEnt);

//      alert($(this).text());
      });
*/

     $('#TablaPacCOMP').bind('click', function() {
      alert($(this).text());
      });

	    
 	$('#datatable_1').dataTable( {
		"bProcessing": true,
		"bDestroy": true,
		"bRetrieve": true,
		"sAjaxSource": "getBLOCKS.php"
	} );
    
    $('#Wait1')
    .hide();  // hide it initially
    $( document ).ajaxStart(function() {
        //alert ('ajax start');
       $('#Wait1').show();
    });
    $( document ).ajaxStop(function() {
        $('#Wait1').hide();
    }); 

    $('#datatable_1 tbody').click( function () {
    // Alert the contents of an element in a SPAN in the first TD    
    alert( $('td:eq(0) span', this).html() );
    } );
 
    });        
    
     
    function getLifePines(serviceURL) {
    $.ajax(
           {
           url: serviceURL,
           dataType: "json",
           async: false,
           success: function(data)
           {
           pines = data.items;
           }
           });
     }        
	 
	 

	window.onload = function(){	
		var translation = '';

		if(language == 'th'){
		translation = '% Referidos a Mi';
		}else if(language == 'en'){
		translation = '% Refered to me';
		}	
		
		var quePorcentaje = $('#quePorcentaje').val();
		var g = new JustGage({
			id: "gauge", 
			value: quePorcentaje, 
			min: 0,
			max: 100,
			title: " ",
			label: translation
		}); 
	};
	
	$(window).load(function() {
	//alert("started");
//	$(".loader_spinner").fadeOut("slow");
	
	var userSurname = $('#SearchUserUSERFIXED').val();
 	     var IdMed = $('#MEDID').val();
	     var UserDOB = '';
         var queUrl ='getPatientNetworkStats.php?Usuario='+userSurname+'&UserDOB='+UserDOB+'&IdDoc='+IdMed;
		 
	
		$.ajax({
			url: queUrl,
			success: function(data){
				//alert(data);
				var res = data.split("::");
				//alert(res[0] + '   ' + res[1]);
				var UConn = res[0];
				gconn=UConn;
				var UTotal = res[1];
				var UProbe = res[2];
				var TotMsg = res[3];
				var TotUpDr = res[4];
				var TotUpUs = res[5];
				
				$('#TotMsgV').html(TotMsg);
				$('#TotUpDrV').html(TotUpDr);
				$('#TotUpUsV').html(TotUpUs);
				if (TotMsg > 0) $('#TotMsgV').css('visibility','visible');
				if (TotUpDr > 0) $('#TotUpDrV').css('visibility','visible');
				if (TotUpUs > 0) $('#TotUpUsV').css('visibility','visible');
				
				//alert(UProbe);
				titulo = 'Percentage of the members that are connected to you ('+UConn+'), out of the total of members available ('+UTotal+') ';
				 $('#gaugetitulo').attr('Title',titulo);
				
				 $('#TotPats').html(UTotal);
				
/*				 var quePorcentaje = Math.floor(100*(parseInt(UConn)/parseInt(UTotal)));
				 var g = new JustGage({
						id: "gauge", 
						value: quePorcentaje, 
						min: 0,
						max: 100,
						title: " ",
						label: "% CONNECTED"
					 });
				 $(".gauge_spinner").hide();*/
				
						
				
				
				
			}
		});

		
	
	
	
	})
				 		
	</script>

    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/bootstrap-colorpicker.js"></script>
    <script src="js/google-code-prettify/prettify.js"></script>
   
    <script src="js/jquery.flot.min.js"></script>
    <script src="js/jquery.flot.pie.js"></script>
    <script src="js/jquery.flot.orderBars.js"></script>
    <script src="js/jquery.flot.resize.js"></script>
    <script src="js/graphtable.js"></script>
    <script src="js/fullcalendar.min.js"></script>
    <script src="js/chosen.jquery.min.js"></script>
    <script src="js/autoresize.jquery.min.js"></script>
    <script src="js/jquery.tagsinput.min.js"></script>
    <script src="js/jquery.autotab.js"></script>
    <script src="js/elfinder/js/elfinder.min.js" charset="utf-8"></script>
	<script src="js/tiny_mce/tiny_mce.js"></script>
    <script src="js/validation/languages/jquery.validationEngine-en.js" charset="utf-8"></script>
	<script src="js/validation/jquery.validationEngine.js" charset="utf-8"></script>
    <script src="js/jquery.jgrowl_minimized.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/jquery.mousewheel.js"></script>
    <script src="js/jquery.jscrollpane.min.js"></script>
    <script src="js/jquery.stepy.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/raphael.2.1.0.min.js"></script>
    <script src="js/justgage.1.0.1.min.js"></script>
	<script src="js/glisse.js"></script>

	<script src="js/application.js"></script>

 <?php

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
</body>
</html>

