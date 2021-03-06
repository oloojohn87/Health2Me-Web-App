<?php
session_start();
 require("environment_detail.php");
 require("PasswordHash.php");
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
echo "Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Health2me Dashboard</a></h2>";
die;
}

					// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

	//$result = mysql_query("SELECT * FROM doctors where IdMEDEmail='$name' and IdMEDRESERV='$pass'");
	$result = $con->prepare("SELECT * FROM doctors where id=?"); 
	$result->bindValue(1, $MEDID, PDO::PARAM_INT);
	$result->execute();
	
	$count=$result->rowCount();
	$row = $result->fetch(PDO::FETCH_ASSOC);
	$success ='NO';
	if($count==1)
	{
		
		$IdMedRESERV=$row['IdMEDRESERV'];
		$addstring=$row['salt'];
		$correcthash=PBKDF2_HASH_ALGORITHM .":". PBKDF2_ITERATIONS. ":" . $addstring . ":" .$IdMedRESERV;
		$success ='SI';
		$MedID = $row['id'];
		$MedUserEmail= $row['IdMEDEmail'];
		$MedUserName = $row['Name'];
		$MedUserSurname = $row['Surname'];
		$MedUserLogo = $row['ImageLogo'];
		$IdMedFIXED = $row['IdMEDFIXED'];
		$IdMedFIXEDNAME = $row['IdMEDFIXEDNAME'];
		$MedUserRole = $row['Role'];
		if ($MedUserRole=='1') $MedUserTitle ='Dr. '; else $MedUserTitle =' '; 
				
	}

$privilege=$_SESSION['Previlege'];

$anchoDisp = 700;
$escala = .6;
$anchoDisp = 1000;
$escala = 1;



//BLOCKLIFEPIN $result = mysql_query("SELECT * FROM blocks");
$result = $con->prepare("SELECT * FROM lifepin");
$result->execute();
?>
<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>Inmers - Center Management Console</title>
    <link rel="icon" type="image/ico" href="images/favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=<?php echo $escala ?>"> 
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/style.css" rel="stylesheet">

    <link href="css/bootstrap.css" rel="stylesheet">
    
    <link rel="stylesheet" href="css/ui-1.10.3/jquery-ui.css" media="screen"  />
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
    
<!--	<link rel="stylesheet" href="css/icon/font-awesome.css">-->
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
    <link rel="stylesheet" href="css/jvInmers.css">

	<link rel="stylesheet" href="css/toggle-switch.css">
    <?php
    if ($_SESSION['CustomLook']=="COL") { 
    ?>
        <link href="css/styleCol.css" rel="stylesheet">
    <?php } ?>
    
    
	<link rel="shortcut icon" href="/images/icons/favicon.ico">
	
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

function pageRefresh(){
location.reload();
}

//alert($.cookie('lang'));

var langType = $.cookie('lang');


if(langType == 'th'){
        window.lang.change('th', function(){
		$(".loader_spinner").fadeOut("slow");
		});
		lang.change('th');
}

if(langType == 'en'){
	window.lang.change('en', function(){
		$(".loader_spinner").fadeOut("slow");
		});
		lang.change('en');
}

</script>
    
    

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lte IE 8]><script type="text/javascript" src="/js/excanvas.min.js"></script><![endif]-->
 
    <!-- Le fav and touch icons -->
	  <!--<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
     
	  <script src="js/jquery-1.10.3/jquery-1.10.1.min.js"></script>
   <script src="js/jquery-1.10.3/jquery-migrate-1.2.1.js"></script>
       <script src="js/jquery-1.10.3/jquery-ui.js"></script>
	 <style>
	  .photo {
		width: 300px;
		text-align: center;
	  }
	  .photo .ui-widget-header {
		margin: 1em 0;
	  }
	  .map {
		width: 350px;
		height: 400px;
	  }
	  .ui-tooltip {
		max-width: 500px;
	  }
   </style> 
   <script type="text/javascript">
   $(function(){
    
  
   
   });
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

    <script "text/javascript">
		
    	function opendropboxWin()
			{
				//var uniqueID="";

		    	window.open('<?php echo $domain;?>/CheckDropBoxAccess.php?uniqueID=<?php echo $MedID ?>&EmailID=<?php echo $MedUserEmail ?>','','left=200,width=1000,height=600,resizable=0,scrollbars=1');

				myWindow.focus();
				//document.getElementById(#DropBoxID).style.display="block";
			}  
</script>
  
  </head>
  
  <body onload="$('#BotonBusquedaPac').trigger('click');" style="background: #F9F9F9;">
	<div class="loader_spinner"></div>
	<!--Header Start-->
	<div class="header" >
     	<input type="hidden" id="USERDID" Value="<?php echo $USERID; ?>">	
    	<input type="hidden" id="MEDID" Value="<?php echo $MedID; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">	
  		
           <a href="index.html" class="logo"><h1>health2.me</h1></a>
           
		   <div style="float:left;">
		   <a href="#en" onclick="setCookie('lang', 'en', 30); return false;"><img src="icons/english.png"></a>
		   </br>
			<a href="#sp" onclick="setCookie('lang', 'th', 30); return false;"><img src="icons/spain.png"></a>
			</div>
		   
           <div class="pull-right">
           
            
           <!--Button User Start-->
		   <div class="btn-group pull-right" >
           
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
              <span class="name-user"><strong lang="en">Welcome</strong> Dr, <?php echo $MedUserName.' '.$MedUserSurname; ?></span> 
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
              <li><a href="dashboard.php" lang="en"><i class="icon-globe"></i> Home</a></li>
              
              <li><a href="medicalConfiguration.php" lang="en"><i class="icon-cog"></i> Settings</a></li>
              <li><a href="javascript:opendropboxWin()" id="DropBoxID" lang="en"><i class="icon-cloud"></i>Add DropBox</a></li>
              <li><a href="logout.php"><i class="icon-off" lang="en"></i> Sign Out</a></li>
            </ul>
            </div>
          </div>
          <!--Button User END-->  
          
          </div>
    </div>
    <!--Header END-->
    
    <!--Content Start-->
	<div id="content" style="background: #F9F9F9; padding-left:0px;">

     	  <!--- VENTANA MODAL  ---> 
   	  <button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;" lang="en">Modal with Header</button>
   	  <div id="header-modal" class="modal hide" style="display: none; height:470px; width:800px; margin-left:-400px;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <div id="InfB" >
	                 	<h4 lang="en">Package Identification & Classification</h4>
                 </div>
        
				<div id="InfoIDPacienteB" style="float:left; margin-top:-30px; margin-left:70%;">
		
                 </div>
         </div>
         
         <div class="modal-body" id="ContenidoModal" style="height:320px;">
			 <div style="margin:20px;" lang="en">Date of the Report: <input type="text" name="repdate" id="datepicker1" style="font-size:24px;"></div>

			<div  id="RepoThumb" class="seleccionable" style="width:100px; float:right;"></div>
			
         <div id="InfoIDPaciente">
         
         </div>
         
         <div id="SeccionBusqueda"> <!--- SECCIÓN DE BÚSQUEDA ---->
         <div class="search-bar" style="margin-top:10px; border: 1px SOLID #CACACA; width:80%;">
          
          	<input type="text" name="ssn3" id="SearchUserDOB" class="validate[required,minSize[4]] span" maxlength="10" placeholder="Birthday" title="Birthday (Year Month Day: YYYYMMDD)"  style="width:130px; " > 
          	<input type="text" name="ssn3" id="SearchUserUSERFIXED" class="input-small"  placeholder="name.surname" style="width:180px; margin-left:10px;" > 
 
          	<input type="button" class="btn btn-primary" value="Search" style="margin-left:20px; margin-top:-10px;" id="BotonBusquedaPacCOMP">
         	<input type="button" class="btn btn-success" value="Add USER" style="margin-left:70px; margin-top:-10px; display: none;" id="BotonAnadirPaciente">
         	<img src="images/load/8.gif" alt="" style="margin-left:50px; display: none;" id="Wait2">
         	
         </div>
         
         <div id="VacioAUN" style=" width:82.5%; margin-top:10px; border: 1px SOLID #CACACA; ">
          <table class="table table-mod" id="TablaPacMODAL">
          </table> 
         </div>
         </div>						<!--- SECCIÓN DE BÚSQUEDA ---->
         
         <div class="clear"></div>   

           <div class="ContenDinamico">
	         <p><H4>Class:  </H3>
	               <div class="formRight" stytle="width:50px;">
		               <select name="Clases" id="Clases" data-placeholder="Select Class (reason for this data ?)" class="chzn-select chosen_select" multiple tabindex="5" >
                            <option value=""></option>
                            <optgroup label="Episodes (user folder)">
                              <option>Epi 1</option>
                              <option>Epi 2</option>
                            <optgroup>
                            <optgroup label="Routine / Checks">
                              <option>Routine / Checks</option>
                            </optgroup>
                            <optgroup label="Isolated Data">
                              <option>Isolated Data</option>
                            </optgroup>
                            <optgroup label="Drug Related Data">
                              <option>Drug Related Data</option>
                           </optgroup>
                          </select>
                       </div>   
              <button id="BotonAddClase"  class="btn btn-small" style="" lang="en"><i class="icon-plus-sign"></i>Add New Episode (Class)</button>
 
	         </p>
	         <p><H4>Type:  </H3>
	         	    <div class="formRight">
		               <select name="Tipos" id="Tipos" data-placeholder="Select Type (is it a report, an image, etc, ?)" class="chzn-select chosen_select" multiple tabindex="5">
                            <option value=""></option>
                            <optgroup label="Imaging Tests">
                              <option>Epi 1</option>
                              <option>Epi 2</option>
                            <optgroup>
                            <optgroup label="Lab Tests">
                              <option>Routine / Checks</option>
                            </optgroup>
                            <optgroup label="Physician Reports">
                              <option>Isolated Data</option>
                            </optgroup>
                         </select>
                    </div>   

	         </p>
	         <p><H5>Clinical Area:  </H3></p>
         
         
         </div>  <!---Fin del CONTENIDO DINÁMICO  ---->
             
             <!--   AREA DE TRABAJO DE LA IMAGEN AMPLIADA  -->
             <div id="ATIA" class="grid span3" style="width:90%;">
	            <div class="grid-title a" style="height:60px;">
		            <div class="pull-left a" id="AreaTipo" style="font-size:24px;"></div>
		            <div class="pull-right">
			            	<div class="grid-title-label" id="AreaFecha" ><span class="label label-warning" ></span></div>
			        </div>
			        <div class="clear"></div>  
			        <div>
				        <span class="ClClas" id="AreaClas" style="font-size:18px; color:grey;"></span>
				    </div>
				    <div class="clear"></div>   
                </div>
          
                <div class="grid-content" id="AreaConten">
             		<img id="ImagenAmp" src="">
                </div>
             </div>
             <!--   AREA DE TRABAJO DE LA IMAGEN AMPLIADA  -->
         
         </div>
         <input type="hidden" id="queId">
         
         <div class="modal-footer">
	         <!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">--->
             <a href="#" class="btn btn-success" data-dismiss="modal" id="GrabaDatos" lang="en">Update Data</a>
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal" lang="en">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 
    
    	    
	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     <ul class="menu-speedbar">
		
    	 <li><a href="MainDashboard.php" lang="en">Home</a></li>
		 <li><a href="dashboard.php" class="act_link" lang="en" >Dashboard</a></li>
    	 <li><a href="patients.php"  lang="en">Members</a></li>
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
     
     
     <div id="Loading"></div>
     
     <!--CONTENT MAIN START-->
     <div class="content" style="height:3500px;">
     
	   <div class="grid" class="grid span4" style="width:1100px; margin: 0 auto; margin-top:30px;">

		<div class="row-fluid" style="height:400px; width:1100px; margin:0 auto;">	            
		 
		  <div style="margin:15px; padding-top:20px;">
			     <span class="label label-info" style="left:0px; margin-left:10px; margin-top:40px; font-size:30px;" lang="en">Clinic-Port&copy </span>
		<!--		 <a href="billing_test.php" class="btn" title="Billing" style="color:black; margin-right:20px;  float:right;"><i class="icon-plus"></i>Billing</a> -->
			     <a href="dropzone.php" class="btn" title="Member Creation" style="color:black; margin-right:20px;  float:right;" lang="en"><i class="icon-plus"></i> Member Creation</a> 
		<!--		 <a href="scheduler-n.php?curr_source=<?php echo $_SESSION['MEDID'];?>" class="btn" title="Schedule" style="color:black; margin-right:20px;  float:right;"><i class="icon-calendar"></i> Scheduler</a>  -->
				 <div class="clearfix" style="margin-bottom:20px;"></div>
				 
		

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
		  	if (is_numeric($Valid)) {$ValidationStatus[$Valid] ++; $esvalido=1;}

		  	$Canal = $rowDOC['CANAL'];
		  	if (is_numeric($Canal)){
		  		$EstadCanal[$Canal] ++;
		  		if ($Valid==0 && $esvalido==1) {$EstadCanalValid[$Canal] ++;} else {$EstadCanalNOValid[$Canal] ++;}
		  		}

		  	$r++;  
		  	
		  }
		//print("result:"+$r);
		  // Sección para construir la información estadística del Médico (Dashboard: relativo a "packets")  
		  $hash = md5( strtolower( trim( $MedUserEmail ) ) );
		  $avat = 'identicon.php?size=75&hash='.$hash;
			?>	

	  	    		<div class="row-fluid" style="">	            

		  	    		<div class="grid" style="padding:10px; height:280px;">
			  
			  	    		<!--- AREA DE INFORMACIÓN BÁSICA DEL USUARIO ---->
			  	    		<div style="width:400px;  float:left;"><!-- WRAPPER DE ESTA AREA --->
				  	    		<img src="<?php echo $avat; ?>" style="width:55px; float:right; font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; box-shadow: 3px 3px 15px #CACACA;"/>
				  	    		<span id="NombreComp" style="font: bold 24px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; "> <?php echo $MedUserTitle.' '.$MedUserName;?> <?php echo $MedUserSurname;?></span>
				  	    		<span id="IdUsFIXED" style="font-size: 12px; color: #3D93E0; font-weight: normal; font-family: Arial, Helvetica, sans-serif; display: block; margin-left:20px;"><?php echo $IdMedFIXED;?></span>
				  	    		<span id="IdUsFIXEDNAME" style="font-size: 14px; color: GREY; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:20px;"><?php echo $IdMedFIXEDNAME;?></span>
				  	    		<span id="email" style="font-size: 14px; color: #5EB529; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:20px;"><?php echo $MedUserEmail;?></span>
				  	    	</div>
			  	    		<!--- AREA DE INFORMACIÓN BÁSICA DEL USUARIO ---->

		  	    		
		  	    		<?php
		  				
		  				$maximo = max($EstadCanal);
		  				$maximoR = 100;
		  				
		  				if ($maximo==0) $maximo=0.00001;
	/*
		  				$G0=80;
		  				$P0=20;
		  				$C0='rgba(255,200,49,';
		  				$G1=60;
		  				$P1=50;
		  				$C1='rgba(235,220,0,';
		  				$G2=90;
		  				$P2=50;
		  				$C2='rgba(215,240,100,';
		  				$G3=30;
		  				$P3=4;
		  				$C3='rgba(185,200,150,';
		  				$G4=40;
		  				$P4=30;
		  				$C4='rgba(145,100,200,';
		  				$G5=80;
		  				$P5=10;
		  				$C5='rgba(105,120,250,';
	*/	  				
		  				$G0=($EstadCanal[0] * $maximoR) / $maximo;
		  				$P0=($maximoR/10)+(($EstadCanalValid[0] * $maximoR) / $maximo);
		  				$C0='rgba(255,200,49,';
		  				$V0=$EstadCanal[0];
		  				$VV0=$EstadCanalValid[0];
		  				
		  				$G1=($EstadCanal[6] * $maximoR) / $maximo;
		  				$P1=($maximoR/10)+(($EstadCanalValid[6] * $maximoR) / $maximo);
		  				$C1='rgba(235,220,0,';
		  				$V1=$EstadCanal[6];
		  				$VV1=$EstadCanalValid[6];

		  				$G2=($EstadCanal[1] * $maximoR) / $maximo;
		  				$P2=($maximoR/10)+(($EstadCanalValid[1] * $maximoR) / $maximo);
		  				$C2='rgba(215,240,100,';
		  				$V2=$EstadCanal[1];
		  				$VV2=$EstadCanalValid[1];

		  				$G3=($EstadCanal[2] * $maximoR) / $maximo;
		  				$P3=($maximoR/10)+(($EstadCanalValid[2] * $maximoR) / $maximo);
		  				$C3='rgba(185,200,150,';
		  				$V3=$EstadCanal[2];
		  				$VV3=$EstadCanalValid[2];

		  				$G4=($EstadCanal[4] * $maximoR) / $maximo;
		  				$P4=($maximoR/10)+(($EstadCanalValid[4] * $maximoR) / $maximo);
		  				$C4='rgba(145,100,200,';
		  				$V4=$EstadCanal[4];
		  				$VV4=$EstadCanalValid[4];

		  				$G5=($EstadCanal[5] * $maximoR) / $maximo;
		  				$P5=($maximoR/10)+(($EstadCanalValid[5] * $maximoR) / $maximo);
		  				$C5='rgba(105,120,250,';
 		  				$V5=$EstadCanal[5];
		  				$VV5=$EstadCanalValid[5];
						
						$G6=($EstadCanal[7] * $maximoR) / $maximo;
		  				$P6=($maximoR/10)+(($EstadCanalValid[7] * $maximoR) / $maximo);
		  				$C6='rgba(105,120,300,';
 		  				$V6=$EstadCanal[7];
		  				$VV6=$EstadCanalValid[7];
		  	    		?>
		  	    		
		  	    		<div style="width:230px; float:right; margin:0px; padding:0px;"><!-- WRAPPER DE ESTA AREA --->
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<div style="width:100px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px;">
		  	    		
		  	    		<div style="height:80px; width:100px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
			  	    		<p style=" font-size:<?php echo queFuente($r); ?>px; font-weight:bold; color: <?php echo $C5.'0.99)' ?>; padding-top:27px;"><?php echo $r ?></p>
		  	    		</div>
		  	    		
		  	    		<div style="width:100px;  text-align:center; margin:0px; background-color: <?php echo $C5.'0.99)' ?>; border:1px solid <?php echo $C5.'0.99)' ?>; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; " lang="en">Packets</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<div style="width:100px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px; margin-bottom:20px;">
		  	    		
		  	    		<div style="height:80px; width:100px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
			  	    		<p style=" font-size:<?php echo queFuente($ValidationStatus[0]);?>px; font-weight:bold; color: <?php echo $C0.'0.99)' ?>; padding-top:27px;"><?php echo $ValidationStatus[0] ?></p>
		  	    		</div>
		  	    		
		  	    		<div style="width:100px;  text-align:center; margin:0px; background-color: <?php echo $C0.'0.99)' ?>; border:1px solid <?php echo $C0.'0.99)' ?>; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; " lang="en">NEW</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		</div>
  	  	                <div class="clear"></div>   
		  	    		
		  	    		
		  	    		<div style="height:120px; width:80px;float:left;  text-align:center; margin-left:48px; ">
		  	    		<div style="position:relative; top: <?php echo 60-($G0/2) ?>px; height:<?php echo $G0 ?>px; width:<?php echo $G0 ?>px;    -webkit-border-radius: <?php echo $G0/2 ?>px; -moz-border-radius: <?php echo $G0/2 ?>px; border-radius: <?php echo $G0/2 ?>px; margin:0 auto; background-color: <?php echo $C0.'0.45)' ?>; ">
		  	    			<div style="height:<?php echo $P0 ?>px; width:<?php echo $P0 ?>px; position:relative; left:<?php echo ($G0/2)-($P0/2);?>px; top:<?php echo ($G0/2)-($P0/2);?>px;  -webkit-border-radius: <?php echo $P0/2 ?>px; -moz-border-radius: <?php echo $P0/2 ?>px; border-radius: <?php echo $P0/2 ?>px; background-color: <?php echo $C0.'0.99)' ?>; "></div>
		  	    		</div>
		  	    		</div>
		  	    		
		  	    		<div style="height:120px; width:80px; float:left;  text-align:center; margin-left:48px;">
		  	    		<div style="position:relative; top: <?php echo 60-($G1/2) ?>px;  height:<?php echo $G1 ?>px; width:<?php echo $G1 ?>px;    -webkit-border-radius: <?php echo $G1/2 ?>px; -moz-border-radius: <?php echo $G1/2 ?>px; border-radius: <?php echo $G1/2 ?>px; margin:0 auto; background-color: <?php echo $C1.'0.45)' ?>;">
		  	    			<div style="height:<?php echo $P1 ?>px; width:<?php echo $P1 ?>px; position:relative; left:<?php echo ($G1/2)-($P1/2);?>px; top:<?php echo ($G1/2)-($P1/2);?>px;  -webkit-border-radius: <?php echo $P1/2 ?>px; -moz-border-radius: <?php echo $P1/2 ?>px; border-radius: <?php echo $P1/2 ?>px; background-color: <?php echo $C1.'0.99)' ?>; "></div>
		  	    		</div>
		  	    		</div>	
		  	    			  	    		
		  	    		<div style="height:120px; width:80px;  float:left;  text-align:center; margin-left:48px;">
		  	    		<div style="position:relative; top: <?php echo 60-($G2/2) ?>px;  height:<?php echo $G2 ?>px; width:<?php echo $G2 ?>px;    -webkit-border-radius: <?php echo $G2/2 ?>px; -moz-border-radius: <?php echo $G2/2 ?>px; border-radius: <?php echo $G2/2 ?>px; margin:0 auto; background-color: <?php echo $C2.'0.45)' ?>; ">
		  	    			<div style="height:<?php echo $P2 ?>px; width:<?php echo $P2 ?>px; position:relative; left:<?php echo ($G2/2)-($P2/2);?>px; top:<?php echo ($G2/2)-($P2/2);?>px;  -webkit-border-radius: <?php echo $P2/2 ?>px; -moz-border-radius: <?php echo $P2/2 ?>px; border-radius: <?php echo $P2/2 ?>px; background-color: <?php echo $C2.'0.99)' ?>; "></div>
		  	    		</div>
		  	    		</div>	
		  	    				  	    		
		  	    		<div style="height:120px; width:80px;  float:left;  text-align:center; margin-left:48px;">
		  	    		<div style="position:relative; top: <?php echo 60-($G3/2) ?>px; height:<?php echo $G3 ?>px; width:<?php echo $G3 ?>px;    -webkit-border-radius: <?php echo $G3/2 ?>px; -moz-border-radius: <?php echo $G3/2 ?>px; border-radius: <?php echo $G3/2 ?>px; margin:0 auto; background-color: <?php echo $C3.'0.45)' ?>;">
		  	    			<div style="height:<?php echo $P3 ?>px; width:<?php echo $P3 ?>px; position:relative; left:<?php echo ($G3/2)-($P3/2);?>px; top:<?php echo ($G3/2)-($P3/2);?>px;  -webkit-border-radius: <?php echo $P3/2 ?>px; -moz-border-radius: <?php echo $P3/2 ?>px; border-radius: <?php echo $P3/2 ?>px; background-color: <?php echo $C3.'0.99)' ?>; "></div>
		  	    		</div>
		  	    		</div>	
		  	    				  	    		
		  	    		<div style="height:120px; width:80px; float:left;  text-align:center; margin-left:48px;">
		  	    		<div style="position:relative; top: <?php echo 60-($G4/2) ?>px;  height:<?php echo $G4 ?>px; width:<?php echo $G4 ?>px;    -webkit-border-radius: <?php echo $G4/2 ?>px; -moz-border-radius: <?php echo $G4/2 ?>px; border-radius: <?php echo $G4/2 ?>px; margin:0 auto; background-color: <?php echo $C4.'0.45)' ?>;">
		  	    			<div style="height:<?php echo $P4 ?>px; width:<?php echo $P4 ?>px; position:relative; left:<?php echo ($G4/2)-($P4/2);?>px; top:<?php echo ($G4/2)-($P4/2);?>px;  -webkit-border-radius: <?php echo $P4/2 ?>px; -moz-border-radius: <?php echo $P4/2 ?>px; border-radius: <?php echo $P4/2 ?>px; background-color: <?php echo $C4.'0.99)' ?>; "></div>
		  	    		</div>
		  	    		</div>	
		  	    				  	    		
		  	    		<div style="height:120px; width:80px; float:left;  text-align:center; margin-left:48px; ">
		  	    		<div style="position:relative; top: <?php echo 60-($G5/2) ?>px;  height:<?php echo $G5 ?>px; width:<?php echo $G5 ?>px;    -webkit-border-radius: <?php echo $G5/2 ?>px; -moz-border-radius: <?php echo $G5/2 ?>px; border-radius: <?php echo $G5/2 ?>px; margin:0 auto; background-color: <?php echo $C5.'0.45)' ?>; ">
		  	    			<div style="height:<?php echo $P5 ?>px; width:<?php echo $P5 ?>px; position:relative; left:<?php echo ($G5/2)-($P5/2);?>px; top:<?php echo ($G5/2)-($P5/2);?>px;  -webkit-border-radius: <?php echo $P5/2 ?>px; -moz-border-radius: <?php echo $P5/2 ?>px; border-radius: <?php echo $P5/2 ?>px; background-color: <?php echo $C5.'0.99)' ?>;  "></div>
		  	    		</div>
		  	    		</div>	
  	  	                
  	  	                <div style="height:120px; width:80px; float:left;  text-align:center; margin-left:48px; ">
		  	    		<div style="position:relative; top: <?php echo 60-($G6/2) ?>px;  height:<?php echo $G6 ?>px; width:<?php echo $G6 ?>px;    -webkit-border-radius: <?php echo $G6/2 ?>px; -moz-border-radius: <?php echo $G6/2 ?>px; border-radius: <?php echo $G6/2 ?>px; margin:0 auto; background-color: <?php echo $C6.'0.45)' ?>; ">
		  	    			<div style="height:<?php echo $P6 ?>px; width:<?php echo $P6 ?>px; position:relative; left:<?php echo ($G6/2)-($P6/2);?>px; top:<?php echo ($G6/2)-($P6/2);?>px;  -webkit-border-radius: <?php echo $P6/2 ?>px; -moz-border-radius: <?php echo $P6/2 ?>px; border-radius: <?php echo $P6/2 ?>px; background-color: <?php echo $C6.'0.99)' ?>;  "></div>
		  	    		</div>

		  	    		</div>	
  	  	                <div class="clear"></div>   

						

		  	    		<div style="height:40px; width:80px;float:left;  text-align:center; margin-left:48px;">
		  	    		<p style="font-family: arial; font-size:18px; color:<?php echo $C0.'0.99)' ?>; " lang="en">Mobile</p>
			  	    	<p style="font-family: arial; font-size:14px; padding-top:0px; margin-top:-15px; color:<?php echo $C0.'0.60)' ?>; "><?php echo $V0.'/'.$VV0 ?></p>
			  	    	</div>
		  	    		
		  	    		<div style="height:40px; width:80px; float:left;  text-align:center; margin-left:48px; ">
		  	    		<p style="font-family: arial; font-size:18px; color:<?php echo $C1.'0.99)' ?>; " lang="en">Mobile+</p>
			  	    	<p style="font-family: arial; font-size:14px; padding-top:0px; margin-top:-15px; color:<?php echo $C1.'0.60)' ?>; "><?php echo $V1.'/'.$VV1 ?></p>
		  	    		</div>	
		  	    			  	    		
		  	    		<div style="height:40px; width:80px;  float:left;  text-align:center; margin-left:48px;">
		  	    		<p style="font-family: arial; font-size:18px; color:<?php echo $C2.'0.99)' ?>; " lang="en">eMail</p>
			  	    	<p style="font-family: arial; font-size:14px; padding-top:0px; margin-top:-15px; color:<?php echo $C2.'0.60)' ?>; "><?php echo $V2.'/'.$VV2 ?></p>
		  	    		</div>	
		  	    				  	    		
		  	    		<div style="height:40px; width:80px;  float:left;  text-align:center; margin-left:48px;">
		  	    		<p style="font-family: arial; font-size:18px; color:<?php echo $C3.'0.99)' ?>; " lang="en">Printer</p>
			  	    	<p style="font-family: arial; font-size:14px; padding-top:0px; margin-top:-15px; color:<?php echo $C3.'0.60)' ?>; "><?php echo $V3.'/'.$VV3 ?></p>
		  	    		</div>	
		  	    				  	    		
		  	    		<div style="height:40px; width:80px; float:left;  text-align:center; margin-left:48px;">
		  	    		<p style="font-family: arial; font-size:18px; color:<?php echo $C4.'0.99)' ?>; ">XDS</p>
			  	    	<p style="font-family: arial; font-size:14px; padding-top:0px; margin-top:-15px; color:<?php echo $C4.'0.60)' ?>; "><?php echo $V4.'/'.$VV4 ?></p>
		  	    		</div>	
		  	    				  	    		
		  	    		<div style="height:40px; width:80px; float:left;  text-align:center; margin-left:48px;">
		  	    		<p style="font-family: arial; font-size:18px; color:<?php echo $C5.'0.99)' ?>; " lang="en">Web</p>
			  	    	<p style="font-family: arial; font-size:14px; padding-top:0px; margin-top:-15px; color:<?php echo $C5.'0.60)' ?>; "><?php echo $V5.'/'.$VV5 ?></p>
		  	    		</div>	
		  	    		
		  	    		<div style="height:40px; width:80px; float:left;  text-align:center; margin-left:48px;">
		  	    		<p style="font-family: arial; font-size:18px; color:<?php echo $C6.'0.99)' ?>; " lang="en">Cloud</p>
			  	    	<p style="font-family: arial; font-size:14px; padding-top:0px; margin-top:-15px; color:<?php echo $C6.'0.60)' ?>; "><?php echo $V6.'/'.$VV6 ?></p>
		  	    		</div>	


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

		<div class="row-fluid" style="width:1100px; margin: 0 auto;">	            
        <!--Tabla NORMAL (NO-Dinámica) -->
        <div class="grid" style="width:96%; margin: 0 auto; margin-top:30px;">
          <div class="grid-title">
           <div class="pull-left" lang="en"><div class="fam-heart-delete" style="margin-right:10px;"></div>health2.me Remote Assets and Channel Status</div>
           <div class="pull-right"></div>
           <div class="clear"></div>   
          </div>
        
        <div id="Vitalidad" style="height:100px;"> 
        	
        
        </div>   
        
        </div>
        <!--Tabla NORMAL (NO-Dinámica) -->
		<!---hover testing
		<div class="ui-widget photo">
			<div class="ui-widget-header ui-corner-all">
			<h2>St. Stephen's Cathedral</h2>
			
			<h3><a href="http://maps.google.com/maps?q=vienna,+austria&amp;z=11" data-geo="data-geo">Vienna, Austria</a></h3>
			</div>
			<a href="http://en.wikipedia.org/wiki/File:Wien_Stefansdom_DSC02656.JPG">
			<img src="images/st-stephens.jpg" alt="St. Stephen's Cathedral" class="ui-corner-all" />
			</a>
			<p>But as it's not a native tooltip, it can be styled. Any themes built with
			<a href="http://themeroller.com" title="ThemeRoller: jQuery UI's theme builder application">ThemeRoller</a>
			 will also style tooltips accordingly.</p>
			 <a title="" data-placement="top" data-toggle="tooltip" href="#" data-original-title="Tooltip on top">
             Tooltip on top
			</a>
		</div>
		hover testing--->
        <!--Tabla NORMAL (NO-Dinámica) -->
        <div class="grid" style="width:98%; margin: 0 auto; margin-top:30px; margin-bottom:30px;">
          <div class="grid-title">
           <div class="pull-left" lang="en"><div class="fam-email-open-image" style="margin-right:10px;"></div>Clinical Information Packages</div>
           
           <img src="images/load/8.gif" alt="" style="margin-left:50px; margin-top:10px; display: none;" id="Wait1">

           <div class="pull-right"></div>
           <div class="clear"></div>   
          </div>
          
          <div class="search-bar">
          
          	<input type="text" class="span" name="" placeholder="Search pattern" style="width:200px;" id="SearchUser"> 
          	<input type="button" class="btn btn-primary" value="Filter" style="margin-left:50px;" id="BotonBusquedaPac">
                   
   			<div style="float:right; margin-right:0px;">
				<label class="checkbox toggle candy yellow" onclick="" style="width:100px;">
					<input type="checkbox" id="CPending" name="CPending" />
					<p>
						<span lang="en">All</span>
						<span lang="en">Pend</span>
					</p>
					
					<a class="slide-button"></a>
				</label>
   			</div>
			<div style="float:right; margin-right:0px;">
				<label class="checkbox toggle candy" onclick="" style="width:100px;">
					<input type="checkbox" id="COthers" name="COthers" />
					<p>
						<span lang="en">All</span>
						<span lang="en">Me</span>
					</p>
					
					<a class="slide-button"></a>
				</label>
   			</div>
   			<div style="float:right; margin-right:0px;">
				<label class="checkbox toggle candy blue" onclick="" style="width:100px; ">
					<input type="checkbox" id="CRows" name="CRows"/>
					<p>
						<span>30</span>
						<span>15</span>
					</p>
					
					<a class="slide-button"></a>
				</label>
   			</div>
          </div>
          
          <div class="search-bar" style="height:50px;background-color:AliceBlue;" id="numbar">
          	
          	<input type="button" class="btn btn-success" value="delete" style="margin-left:0px;" id="divdelbutton">
			<input type='button' class='btn btn-success' value='verify' style='margin-left:30px;' id='reportvalidate'>
			<!--<label style="float:right;" id="CurrentPage"></label>-->
			<div style="float:right;" class="the-icons">
			<i class="icon-chevron-right" style="padding:10px 10px;float:right;margin-right:0px;" id="next"></i>
			<label style="padding:10px;float:right;margin-right:0px;" id="CurrentPage"></label>
			<i class="icon-chevron-left" style="padding:10px ;float:right;margin-right:0px;" id="prev"></i>
			</div>
          </div>
          
          <div class="grid" style="margin-top:0px;">
          <table class="table table-mod" id="TablaPac" style="width:100%; table-layout: fixed; ">
          </table> 
  
          </div>
          
        </div>
        <!--Tabla NORMAL (NO-Dinámica) -->

   

    <!--Zero configuration END-->
  

            <!--Statistics Box Start-->
  <!--
        <div class="grid">
          <div class="grid-title">
           <div class="pull-left">Statistics Box</div>
           <div class="pull-right">
            <div class="stat-input-date">
               <input type="text" name="regular" class="input-date-min" value="Jan 1, 2012">
               <div class="fieldIcon"><i class="icon-calendar"></i></div>
             </div>
           </div>
           <div class="clear"></div>   
          </div>
        
       
           <div class="information-data">
            <div class="data data-last">
                <p class="date-figures">2362</p>
                <p class="date-title">Info Blocks</p>
            </div>
        	<div class="data">
                <p class="date-figures">935</p>
                <p class="date-title">Patients</p>
            </div>
            <div class="data">
                <p class="date-figures">24</p>
                <p class="date-title">Doctors</p>
            </div>
            <div class="data">
                <p class="date-figures">8</p>
                <p class="date-title">Centers</p>
            </div>
            <div class="data">
                <p class="date-figures"></p>
                <p class="date-title"></p>
            </div>
        	</div>
            <div class="clear"></div>
            
            <div class="grid-content overflow">
            <table class="chart2">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Views</th>
                                                <th>Money ($)</th>
                                                <th>Sales</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>1</th>
                                                <td>250</td>
                                                <td>580</td>
                                                <td>686</td>
                                            </tr>
                                            <tr>
                                                <th>2</th>
                                                <td>280</td>
                                                <td>450</td>
                                                <td>546</td>
                                            </tr>
                                            <tr>
                                                <th>3</th>
                                                <td>400</td>
                                                <td>500</td>
                                                <td>600</td>
                                            </tr>
                                            <tr>
                                                <th>4</th>
                                                <td>350</td>
                                                <td>600</td>
                                                <td>703</td>
                                            </tr>
                                            <tr>
                                                <th>5</th>
                                                <td>410</td>
                                                <td>560</td>
                                                <td>800</td>
                                            </tr>
                                            <tr>
                                                <th>6</th>
                                                <td>210</td>
                                                <td>620</td>
                                                <td>750</td>
                                            </tr>
                                            <tr>
                                                <th>7</th>
                                                <td>265</td>
                                                <td>500</td>
                                                <td>820</td>
                                            </tr>
                                            <tr>
                                                <th>8</th>
                                                <td>310</td>
                                                <td>650</td>
                                                <td>801</td>
                                            </tr>
                                            <tr>
                                                <th>9</th>
                                                <td>450</td>
                                                <td>660</td>
                                                <td>756</td>
                                            </tr>
                                            <tr>
                                                <th>10</th>
                                                <td>433</td>
                                                <td>600</td>
                                                <td>900</td>
                                            </tr>
                                            <tr>
                                                <th>11</th>
                                                <td>400</td>
                                                <td>650</td>
                                                <td>800</td>
                                            </tr>
                                            <tr>
                                                <th>12</th>
                                                <td>360</td>
                                                <td>800</td>
                                                <td>930</td>
                                            </tr>
                                            <tr>
                                                <th>13</th>
                                                <td>410</td>
                                                <td>750</td>
                                                <td>800</td>
                                            </tr>
                                            <tr>
                                                <th>14</th>
                                                <td>440</td>
                                                <td>650</td>
                                                <td>860</td>
                                            </tr>

                                        </tbody>
              </table>
              
              <div class="filter-statistics">
              <div class="pull-left">
               <div class="filter"><input type="text" name="" value="14"> <span>Show</span></div>
               <div class="filter"><input type="text" name="" value="200" class="input_size_2"> <span>Step</span></div>
              </div>
              
              <div class="pull-right"><a href="#" class="btn">View All</a></div>
              <div class="clear"></div>
              </div>
              
        	  </div>
        </div>
 -->
        <!--Statistics Box END-->
        

   
        </div>
  
        </div>
       
     </div>
     <!--CONTENT MAIN END-->

    </div>
    <!--Content END-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!--<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />-->
    <!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>-->
    <!--<script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>-->
	
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
var temp_idpin;
var temp_idusu;
var temp_idusfixed;
var temp_idusfixedname;
var temp_date;
var temp_extension;  
var ordering=0;  
var sorted="desc";
var lastChecked=new Array();
var oldnumpages=0;
var currpage=1;
var decrypt=true;
var last_num = 0;

//var timeoutTime = 18000000;
var timeoutTime = 1800000;  //30minutes
var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);


var active_session_timer = 60000; //1minute
var sessionTimer = setTimeout(inform_about_session, active_session_timer);

//This function is called at regular intervals and it updates ongoing_sessions lastseen time
function inform_about_session()
{
	$.ajax({
		url: '<?php echo $domain?>/ongoing_sessions.php?userid='+$('#MEDID').val(),
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

/*
$('#datepicker1').datepicker({
showOn: "button",
buttonImage: "images/calendar.gif",
buttonImageOnly: true
});
*/
//Functionality for typewatch for automated searching in patient tab.
    $("#SearchUserUSERFIXED,#SearchUserDOB").typeWatch({
				captureLength: 1,
				callback: function(value) {
					$("#BotonBusquedaPacCOMP").trigger('click');
					$("#BotonBusquedaPacCOMP").trigger('update');
					//alert('searching');
				}
	});
	
	$("#SearchUser").typeWatch({
				captureLength: 1,
				callback: function(value) {
					$("#BotonBusquedaPac").trigger('click');
					//alert('searching');
				}
	});
$('#datepicker1').datepicker({
				inline: true,
				nextText: '&rarr;',
				prevText: '&larr;',
				showOtherMonths: true,
				dateFormat: 'mm-dd-yy',
				dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
				showOn: "button",
				buttonImage: "images/calendar-blue.png",
				buttonImageOnly: true,
			});



    $(document).ready(function() {

    $('body').bind('mousedown keydown', function(event) {
        clearTimeout(timeoutTimer);
        timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
    });
	
	 $(document).tooltip({
      items:"[report-image], [title]",
      content: function() {
        var element = $(this);
		if ( element.is( "[report-image]" ) ) {
          var text = $(this).attr('id');
		  var idpin=$(this).parents("tr.CFILA").attr('id');
		 // alert("idpin"+id);
		 //alert(text);
		  return "<div><iframe class='map' style='border:1px solid #666CCC; margin:0 auto; display:block;' src='"+text+"' frameborder='1' scrolling='auto'></iframe><div class='clear'></div><input type='hidden' id='reportID' Value='"+idpin+"'><input type='button' class='btn btn-primary' value='verify' style='margin-left:260px;margin-top:8px' id='reportvalidate'></div>";
        }
		
		/*<div id='SeccionBusqueda'><div class='search-bar' style='margin-top:10px; border: 1px SOLID #CACACA; width:80%;'><input type='text' name='ssn3' id='SearchUserDOB' class='validate[required,minSize[4]] span' maxlength='10' placeholder='Birthday' title='Birthday (Year Month Day: YYYYMMDD)'  style='width:130px;'><input type='text' name='ssn3' id='SearchUserUSERFIXED' class='input-small'  placeholder='name.surname' style='width:180px; margin-left:10px;' ><input type='button' class='btn btn-primary' value='Search' style='margin-left:20px; margin-top:-10px;' id='BotonBusquedaPacCOMP'></div><div id='VacioAUN' style=' width:82.5%; margin-top:10px; border: 1px SOLID #CACACA; '><table class='table table-mod' id='TablaPacMODAL'></table></div></div>*/
		
		if ( element.is( "[title]" ) ) {
          return element.attr( "title" );
        }
      },
	  show: null,
	   hide: { effect: "", },
	   open: function( event, ui ) { ui.tooltip.animate({ top: ui.tooltip.position().center}, "fast" ); },
	    close: function( event, ui ) {ui.tooltip.hover(function () {$(this).stop(true).fadeTo(400, 1); 
            //.fadeIn("slow"); // doesn't work because of stop()
        },
        function () {$(this).fadeOut("400", function(){ $(this).remove(); }) }
       );
      }
	 });

    $("#reportvalidate").live('click',function(){
		var obj=$(this).parents("tr.CFILA");
		var id=$(this).parents("tr.CFILA").attr('id');
		var idpin=$('#reportID').val();
		//alert("This is a test alert for report validation!"+idpin);
		var cadena = '<?php echo $domain;?>/setInstantValidation.php?IdPin='+idpin;
        var status = LanzaAjax (cadena);
		if(status)
			alert("Report has been verified");
		 $('#TablaPac').load(queUrl);
    	 $('#TablaPac').trigger('update');
	});   
 
		
	$("#divdelbutton").hide();
	$("#reportvalidate").hide();
	$('#Wait1').hide();
	setInterval(function() {
       $('#BotonBusquedaPac').trigger('click');
              
       $('#Vitalidad').html('       Checking     ');   

      // var serviceURL = '$domain/CheckVitality.php'; 
       var serviceURL = '<?php echo $domain;?>/CheckVitality.php'; 

      // var serviceURL = '$domaindev'/CheckVitality.php'; 
  

       $('#Vitalidad').load(serviceURL);    
      }, 30000);

   $("#CRows").click(function(event) {
		 decrypt=true;
   		 $('#BotonBusquedaPac').trigger('click');
   		});
	
   $("#CPending").click(function(event) {
		 decrypt=true;
   		 $('#BotonBusquedaPac').trigger('click');
   		});
		
	$("#COthers").click(function(event) {
		 decrypt=true;
   		 $('#BotonBusquedaPac').trigger('click');
   		});
    
    $("#BotonBusquedaPac").click(function(event) {
    	    $('#Wait1').show(); 
    	    //add(5);
			//getnumpages();
			var searchcriteria = $('#SearchUser').val()
			var NumFilas = 0;
    	    if ($('#CRows').is(":checked"))	NumFilas = 1; 
			var GetPending = 1;
    	    if ($('#CPending').is(":checked")) GetPending = 0;
			var GetOthers = 1;
    	    if ($('#COthers').is(":checked")) GetOthers = 0;
			//alert ('Rows =  '+NumFilas+' Others = '+GetOthers+'Pending = '+GetPending  );
			
    	    var IdUs =156;
    	    var UserInput = $('#SearchUser').val();
            var IdMed = $('#MEDID').val();
                      
			var max;		  
			if(NumFilas==0)
			{
				max=15;
			}
			else
			{
				max=30;
			}
					  
    	    var serviceURL = '<?php echo $domain;?>/getnumreports.php?Usuario='+UserInput+'&IdMed='+ IdMed+'&NumF='+ NumFilas+'&GetP='+ GetPending+'&GetO='+ GetOthers+'&GetOrder='+ ordering+'&GetSort='+ sorted+'&GetSearch='+ searchcriteria; 
    	    //alert(serviceURL);
            getnumrows(serviceURL);
            //alert(numrow);
			var longit = Object.keys(numrow).length;	
    	    var totalpage=Math.ceil(numrow[0].num/max);
			//alert(numrow[0].num + '  ** '+last_num);
			if(last_num != numrow[0].num)
			{
				decrypt = true;
			}
			last_num = numrow[0].num;
			
			
			if(currpage<1)
			{
				currpage=totalpage;
			}
			
			if(currpage>totalpage)
			{
				currpage=1;
			}
			
			//$('#CurrentPage').text("Page "+currpage+" of "+totalpage);
			$('#CurrentPage').text(currpage+" of "+totalpage);
    	    //var queUrl ='<?php echo $domain;?>/getFullBlocks.php?Usuario='+UserInput+'&IdMed='+ IdMed+'&NumF='+ NumFilas+'&GetP='+ GetPending+'&GetO='+ GetOthers+'&GetOrder='+ ordering+'&GetSort='+ sorted+'&GetSearch='+ searchcriteria;
			 allTicks = $('#TablaPac').find('input:checked').map(function() { 
			//  alert(this.id);
			  return this.id; });
			
			var queUrl ='<?php echo $domain;?>/getFullBlocks.php?Usuario='+UserInput+'&IdMed='+ IdMed+'&NumF='+ NumFilas+'&GetP='+ GetPending+'&GetO='+ GetOthers+'&GetOrder='+ ordering+'&GetSort='+ sorted+'&GetSearch='+ searchcriteria+'&Getcurrpage='+ currpage+'&decrypt='+ decrypt;
    	    //alert(queUrl);
			decrypt=false;
			$('#TablaPac').load(queUrl);
    	    //$('#TablaPac').trigger('click');
    	    $('#TablaPac').trigger('update');
			
			  if(allTicks.length!=0)
			  // alert("You have checked some columns!");
			   //$(document).ready(function (){
			  setTimeout(function(){ for (i = 0 ; i < allTicks.length; i++ ){
					//alert("array"+document.getElementById(allTicks[i]));
					document.getElementById(allTicks[i]).checked = true;
					// $('#' + allTicks[i]).attr('checked', 'checked');
			   }},600);	 
     });

	 function getnumpages()
	 {
		var Pend;
		if ($('#CPending').is(":checked"))
			Pend=0;
		else
			Pend=1;
			
		var Others;	
		if ($('#COthers').is(":checked"))
			Others=0;
		else
			Others=1;
			
		var cadena = '<?php echo $domain?>/getnumresults.php?Usuario='+UserInput+'&IdMed='+ IdMed+'&NumF='+ NumFilas+'&GetP='+ GetPending+'&GetO='+ GetOthers+'&GetOrder='+ ordering+'&GetSort='+ sorted+'&GetSearch='+ searchcriteria;
	 	var RecTipo = LanzaAjax (cadena);
			
		
	 }
	 
	 $("#prev").live('click',function() {
		currpage=currpage-1;
		decrypt=true;
		$('#BotonBusquedaPac').trigger('click');
	 });
	 
	$("#next").live('click',function() {
		currpage=currpage+1;
		decrypt=true;
		$('#BotonBusquedaPac').trigger('click');
	 });
	
	 
	function add(numpages)
	{
		var i = document.getElementById( 'numbar' );
		//alert(i);
		for (var j=1;j<=oldnumpages;j++)
		{
			var obj = document.getElementByName("num"+j);
			alert("Removing num"+j);
			//document.body.removeChild(obj);
			i.removeChild(obj);
		}
		for (var j=1;j<=numpages;j++)
		{
			var element = document.createElement("input");
			//Assign different attributes to the element.
			element.setAttribute("type", "button");
			element.setAttribute("value", j);
			element.setAttribute("name", "num"+j);
			i.appendChild(element);
			
		}
		oldnumpages=numpages;
		alert(oldnumpages);
 

	
	}
	$("#col0").live('click',function() {
		if(ordering == 0)
		{
			if(sorted=="desc")
				sorted="";
			else
				sorted="desc";
		}
		else
		{
			sorted="desc";
		}
		ordering=0;
		$("#BotonBusquedaPac").trigger('click');
		
	});

	//This was the column for Report Upload...It is replaced by Origin...We no longer sort by origin..Hence commented this case
	/*$("#col1").live('click',function() {
		if(ordering == 1)
		{
			if(sorted=="desc")
				sorted="";
			else
				sorted="desc";
		}
		else
		{
			sorted="desc";
		}
		ordering=1;
		$("#BotonBusquedaPac").trigger('click');
	});*/
	
	$("#col2").live('click',function() {
		if(ordering == 2)
		{
			if(sorted=="desc")
				sorted="";
			else
				sorted="desc";
		}
		else
		{
			sorted="desc";
		}
		ordering=2;
		$("#BotonBusquedaPac").trigger('click');
	});
	
	
	
	
	$("#col3").live('click',function() {
		if(ordering == 3)
		{
			if(sorted=="desc")
				sorted="";
			else
				sorted="desc";
		}
		else
		{
			sorted="desc";
		}
		ordering=3;
		$("#BotonBusquedaPac").trigger('click');
	});
	
	$("#col4").live('click',function() {
		if(ordering == 4)
		{
			if(sorted=="desc")
				sorted="";
			else
				sorted="desc";
		}
		else
		{
			sorted="desc";
		}
		ordering=4;
		$("#BotonBusquedaPac").trigger('click');
	});
	
	$("#col5").live('click',function() {
		if(ordering == 5)
		{
			if(sorted=="desc")
				sorted="";
			else
				sorted="desc";
		}
		else
		{
			sorted="desc";
		}
		ordering=5;
		$("#BotonBusquedaPac").trigger('click');
	});
	
	
	
	$("#col7").live('click',function() {
		if(ordering == 7)
		{
			if(sorted=="desc")
				sorted="";
			else
				sorted="desc";
		}
		else
		{
			sorted="desc";
		}
		ordering=7;
		$("#BotonBusquedaPac").trigger('click');
	});
	
	
    
    function getBlocks(serviceURL) {
    	$.ajax(
           {
           url: serviceURL,
           dataType: "json",
           async: false,
           success: function(data)
           {
           	blocks = data.items;
           	//$('#Wait1').hide(); 
           	//alert ("PASA");
           	//alert (employees);
           }
         });
     }   
     
       
   $("#nxtaction").live("click",function(event) {
     	
     	$('#Wait1').show(); 
     	
     	lastChecked=0;
			//alert("reached here");
     		//$("#divdelbutton").hide();
	     	var myClass = $(this).parents("tr.CFILA").attr("id");
			//alert("myClass:"+myClass);
			$(this).focus();
	        var parent=$(this).parents("tr.CFILA");
			//alert("myClass:"+parent);
	  	if(myClass!=null&&myClass!='FILA'){
	     		//$('#Wait1').show(); 
	     	
	     	var NombreEnt = $('#NombreEnt').val();
	     	var PasswordEnt = $('#PasswordEnt').val();
	     	
	     	//$('#BotonModal').trigger('click');
			
	     	ParseStatus = LoadPinData ($(parent),parent);
		 	//alert("Herealso");
		 	//alert ('ParseStatus1(vs): '+ParseStatus.substr(0,1)+'  ParseStatus2(fs): '+ParseStatus.substr(1,1)+'  ParseStatus3(a_s): '+ParseStatus.substr(2,1));
		 	
		 	if (ParseStatus.substr(2,1) == 0)
		 	{
     	
			 	alert ('Please wait until Health2Me gets classification and identification information from this report.');
		 	}
		 		 	
		 	if (ParseStatus.substr(0,1) == 1)
		 	{
			 	alert ('Caution: This report has already been classified by user.');
		 	}
		 	
		 	$('#BotonModal').trigger('click');
		 	$('#Wait1').hide(); 
		 }
	      	
    });
    
    $('input[type=checkbox][id^="checkcol"]').live('click',function() {
    // this represents the checkbox that was checked
	
       if($(this).is(':checked')){
       		
       		$("#divdelbutton").show();
			$("#reportvalidate").attr('value','verifyAll');
			$("#reportvalidate").show();
       }else{
       		
       		$("#divdelbutton").hide();
			$("#reportvalidate").hide();
       }
      
	});
	
	
    $("tr.CFILA td.checktab,#nxtaction,#prev,#next").live("mouseenter",function () {
			$(this).css("background","LightSteelBlue");
			$(this).css("cursor","pointer");
		});
		
	$("tr.CFILA td.checktab,#nxtaction,#prev,#next").live("mouseleave",function () {
			$(this).css("background","");
	});
		
	
	    
     $("#divdelbutton,#reportvalidate").live('click',function() {
		//var 
		var IdList='';
		 var del=false;
		 var ver=false;
		 var element = $(this);
		//allTicks=[];
		//alert("delete clicked");
		 setTimeout(function(){ for (i = 0 ; i < allTicks.length; i++ ){
					//alert("array"+document.getElementById(allTicks[i]));
					document.getElementById(allTicks[i]).checked = false;
					// $('#' + allTicks[i]).attr('checked', 'checked');
	     }},250);	
		$('input[type=checkbox][id^="checkcol"]').each(function () {
				var sThisVal = (this.checked ? "1" : "0");
				
				//sList += (sList=="" ? sThisVal : "," + sThisVal);
				if(sThisVal==1){
				 var idpin=$(this).parents("tr.CFILA").attr("id");
				// alert(idpin);
				if ( element.is( "#divdelbutton")) {
				 var cadena = '<?php echo $domain;?>/getPacketStatus.php?IdPin='+idpin;
				 var packetstatus = LanzaAjax (cadena);
				 if(packetstatus==2)
				 {
					alert('This report contains Members Basic EMR Data. It cannot be deleted !');
					return;
				 }
				 if(packetstatus==0){
				 del=confirm("This packetID "+idpin+" would be parmenantly lost! Click OK to confirm");
				 if(!del)
					//alert("permanently deleted");
					return;
				 }
				  
				  var cadena = '<?php echo $domain;?>/deleteReports.php?IdPin='+idpin+'&state='+packetstatus;
				  //alert(cadena);
				  var RecTipo = LanzaAjax (cadena);
				  //alert(RecTipo);
				  var Content='report marked deleted';
				  var VIEWIdUser = 0;
				  var VIEWIdMed = $("#MEDID").val();
				  var MEDIO = 0;
				  var cadena = '<?php echo $domain ;?>/LogEvent.php?IDPIN='+idpin+'&Content='+Content+'&VIEWIdUser='+VIEWIdUser+'&VIEWIdMed='+VIEWIdMed+'&MEDIO='+MEDIO;
				  var RecTipo = LanzaAjax (cadena);
				 }else if (element.is( "#reportvalidate")) {
				  //alert("Verify detected");
				  //var idpin=$('#reportID').val();
				  //alert("This is a test alert for report validation!"+idpin);
				  var cadena = '<?php echo $domain;?>/setInstantValidation.php?IdPin='+idpin;
				  var status = LanzaAjax (cadena);
				  ver=true;
				 }
				 
				 IdList=IdList+idpin+", ";   //collect all idpin values
				}
				//alert('Checked = ' + sThisVal);
		});
		if(!del&&!ver)
		alert("Report(s) with Id "+IdList+"removed!");
		
			//alert('Total Checked = ' + counter);
		if(ver)
		alert("Report(s) with Id "+IdList+"verified!");
		
		$("#BotonBusquedaPac").trigger('click');
		
		$("#divdelbutton").hide();
		$("#reportvalidate").hide();
	 
	 });
	 
	  $("#reportvalidatehover").live('click',function(){
		var obj=$(this).parents("tr.CFILA");
		var id=$(this).parents("tr.CFILA").attr('id');
		var idpin=$('#reportID').val();
		//alert("This is a test alert for report validation!"+idpin);
		 setTimeout(function(){ for (i = 0 ; i < allTicks.length; i++ ){
					//alert("array"+document.getElementById(allTicks[i]));
					document.getElementById(allTicks[i]).checked = false;
					// $('#' + allTicks[i]).attr('checked', 'checked');
	     }},250);
		var cadena = '<?php echo $domain;?>/setInstantValidation.php?IdPin='+idpin;
        var status = LanzaAjax (cadena);
		if(status)
			alert("ReportID "+idpin+" has been verified");
		 $("#BotonBusquedaPac").trigger('click');
		
	});  
    
     	 
     $("#checkall").live('click',function(event){
     	$('#Wait1').hide();
     	if($(this).is(':checked')){
     		$("#divdelbutton").show();
			$("#reportvalidate").show();
			$('input[type=checkbox]').prop('checked', true);
     	}else{
     		$("#divdelbutton").hide();
			$("#reportvalidate").hide();
			$('input[type=checkbox]').prop('checked', false);
     	}
     	//$("#divdelbutton").toggle('show'); 
     	event.stopPropagation();
     	
     }); 
	 

 
    $(".CFILAMODAL").live('click',function() {
     	var myClass = $(this).attr("id");
     	
     	var NameDoctor = $('#IdMEDName').val();
     	var SurnameDoctor = $('#IdMEDSurname').val();
     	
     	getUserData(myClass);
    
     	var r=confirm('Please confirm that you want to attach this report ('+pines[0].IdPin+') to user: '+user[0].IdUsFIXEDNAME+' ('+user[0].IdUsFIXED+')');
		
		
     	if (r==true)
     	{
	     	x="You pressed OK!";
			temp_idpin = pines[0].IdPin;
			temp_idusfixedname = user[0].IdUsFIXEDNAME;
			temp_idusfixed = user[0].IdUsFIXED;
			temp_idusu = myClass;
			
			//Changing the display on the popup
			switch (temp_extension)
			{
				case 'jpg': quecolor='BLUE';
		    			break;
				case 'pdf': quecolor='RED';
		    			break;
			}	
			IDEt ='<span class="label label-info" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace;">ID: </span>';
	        
	        $('#InfoIDPacienteB').html('<span id="ETUSER" class="label label-info" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">'+myClass+'</span><span class="label label-info" style="background-color:'+quecolor+'; font-size:14px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">'+temp_extension+'</span>');
			$('#InfoIDPaciente').html(IDEt+'<span class="label label-success" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">'+user[0].IdUsFIXED+'</span><span class="label label-success" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">'+user[0].IdUsFIXEDNAME+'</span>');
			
			
		
	    }
	    else
	    {
		    x="You pressed Cancel!";
		}
  
     	var NombreEnt = $('#NombreEnt').val();
     	
     	//$('#CloseModal').trigger('click');
     	
    });
       
    $('#Wait1').hide()  // hide it initially
    .ajaxStart(function() {
        //alert ('ajax start');
        $(this).show();
    })
    .ajaxStop(function() {
        $(this).hide();
    }); 
      
 /*
 $('#Loading').ajaxStart(function () {
    $(this).dialog({
        title: "Loading data...",
        modal: true,
        width: 150,
        height: 100,
        closeOnEscape: false,
        resizable: false,
        open: function () {
            $(".ui-dialog-titlebar-close", $(this).parent()).hide(); //hides the little 'x' button
        }
    });
}).ajaxStop(function () {
    $(this).dialog('close');
});
*/ 
 
 
     $("#Clases").live('change',function() {
	    var doble = $(this).val();
	    var newVal = String(doble).substr(0,1);
	    var newVal2 = String(doble).substr(1,2);
		$("#SelecERU").val(newVal);		   
		$("#SelecEvento").val(newVal2);		   
		    //do something
	});
	
    $("#Tipos").live('change',function() {
	    var newVal = $(this).val();
		$("#SelecTipo").val(newVal);		   
		//alert (newVal);
		    //do something
	});   

	$('#Tipos').live('keypress', function(e) {
		alert (e.keyCode);
		if(e.keyCode==13){
			// Enter pressed... do anything here...
			}
	});

	
    $("#BotonAddClase").live('click',function() {
 	   var name=prompt("Please enter new Episode (class)","");
 	   if (name!=null && name!="")
 	   	{
	 	   var queUsu = $("#queUsu").val();
	 	   var queBlock = $("#queBlock").val();
	 	   var UltimoEvento = $("#UltimoEvento").val();
	 	   var cadena = '<?php echo $domain;?>/AnadeClase.php?queBlock='+queBlock+'&queUser='+queUsu+'&UltimoEvento='+UltimoEvento+'&Nombre='+name;
		   var RecTipo = LanzaAjax (cadena);
		   $("#Episodios").append('<option value='+(UltimoEvento+1)+' selected="selected">'+name+'</option>');
		   $("#UltimoEvento").val(1+parseInt(UltimoEvento));		   
		}
   	});

    $("#BotonElimClase").live('click',function() {
 	  var name = $('#Episodios').find(":selected").text();
 	  var r=confirm('Confirm removal of episode ('+name+') ?');
 	  	if (r==true)
 	  	{
	 	    //var queUsu = $("#queUsu").val();
	 	    var queUser = $("#ETUSER").text();
	 	    var queBlock = $("#queBlock").val();
	 	    var UltimoEvento = $("#UltimoEvento").val();
	 	    var cadena = '<?php echo $domain?>/EliminaClase.php?queBlock='+queBlock+'&queUser='+queUser+'&UltimoEvento='+UltimoEvento+'&Nombre='+name;
	 	  	var RecTipo = LanzaAjax (cadena);
		    $('#Episodios option:selected').remove();
         	$('#CloseModal').trigger('click');
	 	}
	 	else
	 	{
		 	//x="You pressed Cancel!";
		}
   	});

    $("#GrabaDatos").live('click',function() {
 	 
	  var name = $('#Episodios').find(":selected").text();
 	  //alert('Confirm '+temp_idpin + '  ' + temp_idusu + '  ' + temp_idusfixed + '  ' + temp_idusfixedname);
	  //var r =confirm('Please approve that this report will be attached to :\nIDUsu='+temp_idusu + '\nIDUsfixed='+temp_idusfixed+'\nidusfixedname='+temp_idusfixedname + '\nReport Date = Work in Progress');
	  var r=confirm('Confirm updating information for this block ?');
 	  	
		if (r==true)
 	  	{
	 	    //var queUser = $("#queUsu").val();
	 	    var queUser = $("#ETUSER").text();
	 	    var queBlock = $("#queBlock").val();
	 	    var UltimoEvento = $("#UltimoEvento").val();
			var queFecha = $("#datepicker1").val();	
	 	    var queERU = $("#SelecERU").val();
	 	    var queEvento = $("#SelecEvento").val();
	 	    var queTipo = $("#SelecTipo").val();
	 	    var Idmed=$("#MEDID").val();
			//alert(queFecha);
			//alert('Name='+name+'  queusu='+queUsu+ '   queblock=' +queBlock+ '    ultimoevento=' +UltimoEvento+'    quetiop='+queTipo);
	 	    //var cadena = 'manual_parse.php?idpin='+temp_idpin+'&idusu='+temp_idusu+'&date=2013-06-11&idusfixed='+temp_idusfixed+'&idusfixedname='+temp_idusfixedname;
	 	    //Changes for the Blind report functionality
	 	    var cadena = '<?php echo $domain;?>/GrabaClasif.php?Idmed='+Idmed+'&queBlock='+queBlock+'&queUser='+queUser+'&queERU='+queERU+'&queEvento='+queEvento+'&queTipo='+queTipo+'&fecha='+queFecha+'&idusfixed='+temp_idusfixed+'&idusfixedname='+temp_idusfixedname;
	 	  	//alert(cadena);
	 	  	var RecTipo = LanzaAjax (cadena);
			//alert(RecTipo);
		    $('#Episodios option:selected').remove();
			$("#BotonBusquedaPac").trigger('click');
			$('#CloseModal').trigger('click');
			
			
			
			//Writing to Log
			var queMED = $("#MEDID").val();
			var IDPIN = temp_idpin;
			var Content = 'Report Verified';
			var VIEWIdUser = queUser;
			var VIEWIdMed = queMED;
			var MEDIO = 0;
			var cadena1 = '<?php echo $domain;?>/LogEvent.php?IDPIN='+IDPIN+'&Content='+Content+'&VIEWIdUser='+VIEWIdUser+'&VIEWIdMed='+VIEWIdMed+'&MEDIO='+MEDIO;
			//alert(cadena1);
			var RecTipo1 = LanzaAjax (cadena1);
			
			
	 	}
      
	 	//$('#BotonModal').trigger('click');
   	});

    $("#ImagenTH").live('click',function() {

	    $('.ContenDinamico').toggle();
		$('#ATIA').toggle();
        
    	});    
 	
 	$("#BotonBusquedaPacCOMP").live('click',function() {
	     var UserInput = $('#SearchUserUSERFIXED').val();
	     var UserDOB = $('#SearchUserDOB').val();
	     var queMED = $("#MEDID").val();
	     var queUrl ='getSearchUsers.php?Usuario='+UserInput+'&UserDOB='+UserDOB+'&IdDoc='+queMED+'&NReports=2';
    	 $('#TablaPacMODAL').load(queUrl);
    	 $('#TablaPacMODAL').trigger('update');
    });     

 	$("#ETUSER").live('click',function() {
 	  	 $('#SeccionBusqueda').toggle();

 	});
 
  	$("#BotonAnadirPaciente").live('click',function() {
 		 var UserInput = $('#SearchUserUSERFIXED').val();
	     var UserDOB = $('#SearchUserDOB').val();
 		 var queMED = $("#MEDID").val();

 		 var mensaje = '';
	    // var Year =  UserDOB.substr(queImg.length-3,3);;
 		 var Year =  UserDOB.substr(0,4);
 		 if (Year<1) Year=0;
 		 if (Year<1900 || Year>2100){ mensaje = Year+' is not a valid year.'};
  		 var Month =  UserDOB.substr(4,2);
  		 if (Month<1) Month=0;
 		 if (Month<1 || Month>12){ mensaje = mensaje+' '+Month+' is not a valid month.'};
 		 var Day =  UserDOB.substr(6,2);
 		 if (Day<1) Day=0;
 		 if (Day<1 || Day>31){ mensaje = mensaje+' '+Day+' is not a valid day.'};
 		 var Gender =  UserDOB.substr(8,1);
 		 if (Gender<0 || Gender>1){ mensaje = mensaje+' '+Gender+' is not a valid gender (use only 0=female or 1=male)'};
		 var OrderOB =  UserDOB.substr(9,1);
 		 if (OrderOB<0 || Gender>5){ mensaje = mensaje+' '+OrderOB+' is not a valid order of birth (use only 0=unique borth or 1=first in twins,triplets,etc; n=n inb twins, triplets, etc)'};
 
 		 if (mensaje!='') alert (mensaje);
 
 		 var mensaje2='';
 		 if (UserInput=='') mensaje2 = 'You must indicate a valid name.surname.';
 		 var encontI = UserInput.indexOf('.');
 		 var encontF = UserInput.lastIndexOf('.');
 		 if (encontI<0) mensaje2 = mensaje2+' '+UserInput+' "name.surname" is not correctly formatted, must contain a dot (.) between name and surname.';
 		 if (encontF>0 && (encontI!=encontF)) mensaje2 = UserInput+' is not correctly formatted, "name.username" must contain ONLY one dot (.)';
 
 		 CarInvalid = new Array('/', '&', '!', '-', '_', ';', ':', '¨', 'ç', '?', '¡', '!', '"' ,'´', '`', '=', '¿', '{', '}', '+', 'º' , '@' , '<' , '>' , '(', ')' , '$' );
 		 var nn=0;
 		 while (nn< CarInvalid.length)
 		 {
	 		 var mm=0;
	 		 while (mm<UserInput.length)
	 		 {
	 		 	if (UserInput.substr(mm,1) == CarInvalid[nn])  mensaje2 = mensaje2 + ' use of character '+CarInvalid[nn]+' is not allowed.';
		 		mm++;
	 		 }
	 		 nn++;
 		 }
 		 if (mensaje2!='') alert (mensaje2);

 		 if (mensaje=='' && mensaje2=='')
 		 {
	 		if (Gender==0) gendstring = 'FEMALE'; else gendstring = 'MALE';
	 		var r=confirm('Confirm creation of NEW USER ('+UserInput+' , '+gendstring+' )?');
	 		if (r==true)
	 			{	 	    
		 			var queUrl ='GrabaNuevoUser.php?IdUsFIXED='+UserDOB+'&IdUsFIXEDNAME='+UserInput+'&IdPin='+pines[0].IdPin+'&IdMed='+queMED;
		 			//alert (queUrl);
		 			var RecTipo = LanzaAjax (queUrl);
		 			var mensSal =  RecTipo.substr(0,4);
		 			if (mensSal=='USER') alert (RecTipo);
		 			$('#CloseModal').trigger('click');
		 		}
 		 }
 	});	 
 	  
 	  function LoadPinData (selector,quethis){
	    //alert("Here");
	    var queId = selector.attr("id");
	    temp_idpin = queId;
	    var serviceURL = '<?php echo $domain;?>/getpinData.php' + '?IdPin=' + queId; 
    	  
    	getpinData(serviceURL);
    	
		var longit = Object.keys(pines).length;	
	   
	    var serviceURL = '<?php echo $domain;?>/gettipoData.php'; 
    	
		gettipoData(serviceURL);
    	var longit = Object.keys(tipos).length;	
	   
	    var serviceURL = '<?php echo $domain;?>/getclaseData.php' + '?IdUsu=' + pines[0].IdUsu; 
    	getclaseData(serviceURL);
    	var longit = Object.keys(clases).length;	
	    //if (longit==0) alert ('User NOT ACTIVE IN DB');
	    
	    var queImg = pines[0].RawImage;
	    
	    var ParseStatus = pines[0].vs;
	    var ParseStatus2 = pines[0].fs;
	    var ParseStatus3 = pines[0].a_s;
	    
	    //alert ('Parsed = '+ParseStatus);
	     
	    var queTip = NombreTipo(pines[0].Tipo);
	    var queERU = pines[0].EvRuPunt;
	    switch (queERU)
	    {
		    case '0': var queClas = NombreClase(pines[0].Evento);
		    		break;
		    case '1': var queClas = "Check & Routine Data";
		    		break;
		    case '2': var queClas = "Individual Symptom";
		    		break;
		    case '3': var queClas = "Drug Related Data";
		    		break;
	    }
	   
    	getUserData(pines[0].IdUsu);
    	
	    var queFecha = pines[0].Fecha;
	    
	    var extensionR = queImg.substr(queImg.length-3,3);
	    var ImagenRaiz = queImg.substr(0,queImg.length-4);
	   
	    //$ImageRaiz = substr($row['RawImage'],0,strlen($row['RawImage'])-4);
        if(queImg.indexOf(".") == -1)
        {
            var contenTHURL = '/temp/pdf_thumbnail.png';  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
		   	var contenURL =   '/temp/pdf_thumbnail.png'; 
            
            var conten =  '<iframe id="ImagenN" style="border:1px solid #666CCC" title="PDF" src="'+contenURL+'" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
	    	var contenTH = '<img id="ImagenTH" src="'+contenTHURL+'" alt="">';
        }
	    else if (extensionR=='pdf' || extensionR=='MOV')
	    {
			var cadena = '<?php echo $domain;?>/DecryptFile.php?rawimage='+queImg+'&queMed='+<?php echo $MedID;?>;
			//alert(cadena);
			var RecTipo = LanzaAjax (cadena);
		   	
			var contenTHURL = '<?php echo $domain ;?>/temp/<?php echo $MedID ;?>/PackagesTH_Encrypted/'+ImagenRaiz+'.png';  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
		   	var contenURL =   '<?php echo $domain ;?>/temp/<?php echo $MedID ;?>/Packages_Encrypted/'+ImagenRaiz+'.'+extensionR;  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
	    	//alert(contenURL);
			//var conten = '<img id="ImagenN" src="'+contenURL+'" alt="">';
	    	var conten =  '<iframe id="ImagenN" style="border:1px solid #666CCC" title="PDF" src="'+contenURL+'" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
	    	var contenTH = '<img id="ImagenTH" src="'+contenTHURL+'" alt="">';
	    }
	    else
	    {
	    	/*if (pines[0].Location==1) */ // ES JPG, pero se ha subido con la nueva APP eMapLifePROF directamente a AWS VIRGINIA
	    	//{
				var cadena = '<?php echo $domain;?>/DecryptFile.php?rawimage='+queImg+'&queMed='+<?php echo $MedID;?>;
				//alert(cadena);
				var RecTipo = LanzaAjax (cadena);
		   	
			
		    	var contenTHURL = '<?php echo $domain ;?>/temp/<?php echo $MedID ;?>/PackagesTH_Encrypted/'+queImg;  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
		    	var conten = '<img id="ImagenN" src="<?php echo $domain ;?>/temp/<?php echo $MedID ;?>/Packages_Encrypted/'+queImg+'" alt="">';
				//alert(conten);
		    	var contenTH = '<img id="ImagenTH" src="'+contenTHURL+'" alt="">';

	    }
	    
	    //alert (queClas);
			    	
	    //$('div.grid-content').html(conten);
	    $('#AreaConten').html(conten);
	    $('#RepoThumb').html(contenTH);
		
		//Fill the datepicker
		
	    
	    Year=queFecha.substr(0,4);
		if(Year=="0000")
		{
			$('#datepicker1').val('01-01-2013');
		}
		else
		{
			
			var last=queFecha.lastIndexOf("-");
			var first = queFecha.indexOf("-");
			
			Month = queFecha.substr(first+1,2);
			Day = queFecha.substr(last+1,2);
			$('#datepicker1').val(Month + '-' + Day + '-' + Year);
			
		}
		
		
		// LOG DEL VISIONADO
	    var queMED = $("#MEDID").val();

	    var IDPIN = queId;
	    var Content = 'Report Viewed';
	    var VIEWIdUser = pines[0].IdUsu;
	    var VIEWIdMed = queMED;
	    var MEDIO = 0;
	    var cadena = '<?php echo $domain;?>/LogEvent.php?IDPIN='+IDPIN+'&Content='+Content+'&VIEWIdUser='+VIEWIdUser+'&VIEWIdMed='+VIEWIdMed+'&MEDIO='+MEDIO;
	 	var RecTipo = LanzaAjax (cadena);
	 	//alert (RecTipo);

	    //$('div.pull-left.a').html(queTip);
	    $('#AreaTipo').html(queTip);
	    
	    //$('.ClClas').html(queClas);
	    $('#AreaClas').html(queClas);
	    
	    //$('div.grid-title-label').html('<span class="label label-warning" style="font-size:16px;">'+queFecha+'</span>');
	    $('#AreaFecha').html('<span class="label label-warning" style="font-size:16px;">'+queFecha+'</span>');
	    //$('#datepicker1').val(queFecha);
	    switch (extensionR)
	    {
		    case 'jpg': quecolor='BLUE';
		    			break;
		    case 'pdf': quecolor='RED';
		    			break;
	    }
	    
	    if (pines[0].IdUsu >0){
		    IDEt ='<span class="label label-info" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace;">ID: </span>';
		    contenEt=pines[0].IdUsu;
			idfix=pines[0].IdUsFIXED;
			idfixname=pines[0].IdUsFIXEDNAME;
		    $('#SeccionBusqueda').hide();
		    $('#BotonAnadirPaciente').show();

	    }else
	    {
		    IDEt ='';
		    contenEt='ASSIGN USER';
			idfix=0;
			idfixname="Not Found";
		    $('#SeccionBusqueda').show();
         	$('#BotonAnadirPaciente').show();
	    }
	    
		temp_idusu = pines[0].IdUsu;
		temp_idusfixedname = pines[0].IdUsFIXEDNAME;
		temp_idusfixed = pines[0].IdUsFIXED;
		
	    $('#SearchUserUSERFIXED').val('');
		$('#SearchUserDOB').val('');
		temp_extension = extensionR;
	     $('#InfoIDPacienteB').html('<span id="ETUSER" class="label label-info" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">'+contenEt+'</span><span class="label label-info" style="background-color:'+quecolor+'; font-size:14px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">'+extensionR+'</span>');

	    $('#InfoIDPaciente').html(IDEt+'<span class="label label-success" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">'+idfix+'</span><span class="label label-success" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">'+idfixname+'</span>');
	    
	    var queMED = $("#MEDID").val();
	    var queUrl ='getSearchUsers.php?Usuario='+pines[0].IdUsFIXEDNAME+'&UserDOB='+pines[0].IdUsFIXED+'&IdDoc='+queMED+'&NReports='+longit;
    	$('#TablaPacMODAL').load(queUrl);
    	$('#TablaPacMODAL').trigger('update');
	    
	    queUrl ='getTipoClase.php?BlockId='+queId;
      	 
      	$('.ContenDinamico').hide();
      	$('#ATIA').hide();
          
      	$('.ContenDinamico').load(queUrl);
    	    //$('#TablaPac').trigger('click');
    	$('.ContenDinamico').trigger('update');
	    return ParseStatus+ParseStatus2+ParseStatus3;
	    
	    };
   
function urlExists(url, callback){
  $.ajax({
    type: 'HEAD',
    url: url,
    success: function(){
      callback(true);
    },
    error: function() {
      callback(false);
    }
  });
}
 
	function LanzaAjax (DirURL)
		{
		var RecTipo = 'SIN MODIFICACIÓN';
	    $.ajax(
           {
           url: DirURL,
           dataType: "html",
           async: false,
           complete: function(){ //alert('Completed');
                    },
           success: function(data) {
                    if (typeof data == "string") {
                                RecTipo = data;
                                }
                     }
            });
		return RecTipo;
		}    
	

    function getpinData(serviceURL) {
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
 
    function gettipoData(serviceURL) {
    $.ajax(
           {
           url: serviceURL,
           dataType: "json",
           async: false,
           success: function(data)
           {
           tipos = data.items;
           }
           });
     }   

    function getclaseData(serviceURL) {
    $.ajax(
           {
           url: serviceURL,
           dataType: "json",
           async: false,
           success: function(data)
           {
           clases = data.items;
           }
           });
     }  

	function getnumrows(serviceURL) {
    $.ajax(
           {
           url: serviceURL,
           dataType: "json",
           async: false,
           success: function(data)
           {
           numrow = data.items;
           alert(numrow);
           }
           });
     }   


	 function getUserData(UserId) {
 	var cadenaGUD = 'getUserData.php?UserId='+UserId;   //Modified for testing#dev
    $.ajax(
           {
           url: cadenaGUD,
           dataType: "json",
           async: false,
           success: function(data)
           {
           //alert ('success');
           user = data.items;
           }
           });
    }


    function NombreTipo(tipo) {
	    var longit = Object.keys(tipos).length;	
	    var n = 0;
	    var salida = 0;
	    while (n<longit)
	    {
	    	if (tipos[n].Id==tipo) salida= tipos[n].NombreEng;
		    n++;
	    }
	    return salida;
     }  
    function NombreClase(clase) {
	    var longit = Object.keys(clases).length;	
	    var n = 0;
	    var salida = 0;
	    while (n<longit)
	    {
	    	if (clases[n].IdEvento==clase) salida= clases[n].Nombre;
		    n++;
	    }
	    return salida;
     } 
     
     
    });        
            

	</script>
	<!--<script src="js/bootstrap.min.js"></script>
    <!-- <script src="/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script> -->
	<script src="js/bootstrap-datepicker.js"></script>
    <script src="js/bootstrap-colorpicker.js"></script>
	<script src="js/bootstrap-modal.js"></script>
	<script src="js/bootstrap-dropdown.js"></script>
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
?>
 

  </body>
</html>
