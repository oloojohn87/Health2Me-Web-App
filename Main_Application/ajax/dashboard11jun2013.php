<?php
//$host="54.225.226.163"; // Host name
/*require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$NombreEnt = 'x';
$PasswordEnt = 'x';


$NombreEnt = $_GET['Nombre'];
$PasswordEnt = $_GET['Password'];

$anchoDisp = 700;
$escala = .6;
$anchoDisp = 1000;
$escala = 1;

/*if ($PasswordEnt != 'a' && $PasswordEnt != 'Houston333')
{
echo "Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='<?php $domain?>'>Return to Inmers HomePage</a></h2>";
die;
}
*/
					
// Connect to server and select databse.
/*$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");


$result = mysql_query("SELECT * FROM doctors where IdMEDEmail='$NombreEnt' and IdMEDRESERV='$PasswordEnt'");
$count=mysql_num_rows($result);
$row = mysql_fetch_array($result);
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
$result2 = mysql_query("SELECT * FROM doctors where IdMEDFIXEDNAME='$NombreEnt' and IdMEDRESERV='$PasswordEnt'");
$count2=mysql_num_rows($result2);
$row2 = mysql_fetch_array($result2);
$success ='NO';
if($count2==1){
	$success ='SI';
	$MedID = $row2['id'];
	$MedUserEmail= $row2['IdMEDEmail'];
	$MedUserName = $row2['Name'];
	$MedUserSurname = $row2['Surname'];
	$MedUserLogo = $row2['ImageLogo'];
	$IdMedFIXED = $row['IdMEDFIXED'];
	$IdMedFIXEDNAME = $row['IdMEDFIXEDNAME'];
	
}
else
{
echo "MEDICAL USER NOT VALID. Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Inmers HomePage</a></h2>";
die;
}
}


//BLOCKLIFEPIN $result = mysql_query("SELECT * FROM blocks");
$result = mysql_query("SELECT * FROM lifepin");

?>
<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>Inmers - Center Management Console</title>
    <meta name="viewport" content="width=device-width, initial-scale=<?php echo $escala ?>"> 
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
    <link rel="stylesheet" href="css/jvInmers.css">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lte IE 8]><script type="text/javascript" src="/js/excanvas.min.js"></script><![endif]-->
 
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="/images/icons/favicon.ico">
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
		    	window.open('http://localhost/CheckDropBoxAccess.php?uniqueID=<?php echo $MedID ?>&EmailID=<?php echo $MedUserEmail ?>','','left=200,width=1000,height=600,resizable=0,scrollbars=1');
				myWindow.focus();
				//document.getElementById(#DropBoxID).style.display="block";
			}  
</script>
  
  </head>
  <!-- <body onload="$('#BotonBusquedaPac').trigger('click');" style="background: #F9F9F9;"> -->
	<body style="background: #F9F9F9;">
	<!--Header Start-->
	<div class="header" >
     	<input type="hidden" id="USERDID" Value="<?php echo $USERID; ?>">	
    	<input type="hidden" id="MEDID" Value="<?php echo $MedID; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">	
  		
           <a href="index.html" class="logo"><h1>health2.me</h1></a>
           
           <div class="pull-right">
           
           <!--Notifications Start-->  
           <div class="notifications-head">
           
           <!--Messages Start-->
           <div class="btn-group m_left hide-mobile" >
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
    	     	<span class="notification">531</span><span class="triangle-1"></span><i class="icon-comments"></i><span class="caret"></span>
            </a>
            <div class="dropdown-menu">
            
              <span class="triangle-2"></span>
              <div class="ichat">
               <div class="ichat-messages">
               	<div class="ichat-title">
                  <div class="pull-left">New Messages</div>
                  <div class="pull-right"><span>Update 4*</span></div>
                  <div class="clear"></div>
                </div>
                
                <div class="imessage">
                  <div class="iavatar"><img src="images/doctors/michaelrlICON.jpg" alt=""></div>
                  <div class="imes">
                  	<div class="iauthor">Dr. Michael rl</div>
                    <div class="itext">All right. Thank you.</div>
                  </div>
                  <div class="idelete"><a href="#"><span><i class="icon-remove"></i></span></a></div>
                  <div class="clear"></div>
                </div>
                <div class="imessage">
                  <div class="iavatar"><img src="images/users/6.jpg" alt=""></div>
                  <div class="imes">
                  	<div class="iauthor">Emma Clark</div>
                    <div class="itext">Can I help you?</div>
                  </div>
                  <div class="idelete"><a href="#"><span><i class="icon-remove"></i></span></a></div>
                  <div class="clear"></div>
                </div>
                <div class="imessage">
                  <div class="iavatar"><img src="images/users/2.jpg" alt=""></div>
                  <div class="imes">
                  	<div class="iauthor">Blake Washington</div>
                    <div class="itext">Can I help you?</div>
                  </div>
                  <div class="idelete"><a href="#"><span><i class="icon-remove"></i></span></a></div>
                  <div class="clear"></div>
                </div>
                
                <div class="ichat-link"><a href="#">InBox</a> <a href="#">OutBox</a> <a href="#">Spam</a> <a href="#">Trash</a>
                <div class="clear"></div>
                </div>
                
                </div>
                <a href="#" class="iview">View all</a><a href="#" class="imark">Mark all read</a>
               
              </div>
            
            </div>
          </div>
          <!--Messages END--> 
          
          <!--Recent Activity Start-->
           <div class="btn-group pull-left hide-mobile" >
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
    	     	<span class="notification">77</span><span class="triangle-1"></span><i class="icon-bell"></i><span class="caret"></span>
            </a>
            <div class="dropdown-menu">
            
              <span class="triangle-2"></span>
              <div class="ichat">
               <div class="ichat-messages">
               	<div class="ichat-title">
                  <div class="pull-left">Recent Activity</div>
                  <div class="pull-right"><span>Update 14*</span></div>
                  <div class="clear"></div>
                </div>
                
                <div class="r_activity">
                <div class="imessage">
                  <div class="r_icon"><a href="#"><i class="icon-camera"></i></a></div>
                  <div class="r_info">
                  	<div class="r_name"><strong>Alan Cook</strong> a new photo</div>
                    <div class="r_text"><i class="icon-time"></i> 3 hours ago</div>
                    <div class="r_link"><a href="#">View...</a></div>
                  </div>
                  <div class="clear"></div>
                </div>
                <div class="imessage">
                  <div class="r_icon"><a href="#"><i class="icon-thumbs-up"></i></a></div>
                  <div class="r_info">
                  	<div class="r_name"><strong>Isaac Donaldson</strong> like: BMW E36</div>
                    <div class="r_text"><i class="icon-time"></i> 5 hours ago</div>
                    <div class="r_link"><a href="#">View...</a></div>
                  </div>
                  <div class="clear"></div>
                </div>
                <div class="imessage">
                  <div class="r_icon"><a href="#"><i class="icon-user"></i></a></div>
                  <div class="r_info">
                  	<div class="r_name">Registered new user</div>
                    <div class="r_text"><i class="icon-time"></i> 15th of May - 06:21</div>
                    <div class="r_link"><a href="#">View...</a></div>
                  </div>
                  <div class="clear"></div>
                </div>
                </div>
               
                
                </div>
                <a href="#" class="iview">View all</a><a href="#" class="imark">Mark all read</a>
               
              </div>
            
            </div>
          </div>
          <!--Recent Activity END--> 
            
          </div><!--Notifications END-->
            
           <!--Button User Start-->
		   <div class="btn-group pull-right" >
           
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
              <span class="name-user"><strong>Welcome</strong> Dr, <?php echo $MedUserName.' '.$MedUserSurname; ?></span> 
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
              <li><a href="#"><i class="icon-globe"></i> Home</a></li>
              <li><a href="#"><i class="icon-comments"></i> Messages</a></li>
              <li><a href="#"><i class="icon-cog"></i> Settings</a></li>
              <li><a href="javascript:opendropboxWin()" id="DropBoxID"><i class="icon-cloud"></i>Add DropBox</a></li>
              <li><a href="index.html"><i class="icon-off"></i> Sign Out</a></li>
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
   	  <button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>
   	  <div id="header-modal" class="modal hide" style="display: none; height:470px; width:800px; margin-left:-400px;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <div id="InfB" >
	                 	<h4>Package Identification & Classification</h4>
                 </div>
                 <div id="InfoIDPacienteB" style="float:left; margin-top:-30px; margin-left:70%;">
    
                 </div>
         </div>
         
         <div class="modal-body" id="ContenidoModal" style="height:320px;">
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
              <button id="BotonAddClase"  class="btn btn-small" style=""><i class="icon-plus-sign"></i>Add New Episode (Class)</button>
 
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
             <a href="#" class="btn btn-success" data-dismiss="modal" id="GrabaDatos">Update Data</a>
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 
    
    	    
	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     <ul class="menu-speedbar">
         <li><a href="dashboard.php?Acceso=23432&Nombre=<?php echo $NombreEnt;?>&Password=<?php echo $PasswordEnt;?>&MEDID=<?php echo $MedID;?>" class="act_link">Dashboard</a></li>
    	 <li><a href="patients.php?Acceso=23432&Nombre=<?php echo $NombreEnt;?>&Password=<?php echo $PasswordEnt;?>&MEDID=<?php echo $MedID;?>">Patients</a></li>
         <li><a href="medicalConnections.php?Acceso=23432&Nombre=<?php echo $NombreEnt;?>&Password=<?php echo $PasswordEnt;?>&MEDID=<?php echo $MedID;?>">Connections</a></li>
         <li><a href="medicalConfiguration.php?Acceso=23432&Nombre=<?php echo $NombreEnt;?>&Password=<?php echo $PasswordEnt;?>&MEDID=<?php echo $MedID;?>">Configuration</a></li>
         <li><a href="">Doctors</a></li>
         <li><a href="">Centers</a></li>
         <li><a href="index.html" style="color:yellow;">Sign Out</a></li>

     </ul>

     
     </div>
     </div>
     <!--SpeedBar END-->
     
     <!--Search Start-->   
     <div class="search">
     <form class="search-form">
     	<input type="text" name="" value="" placeholder="Enter keywords">
     </form>
	 <div class="clear"></div>	
     </div>
     <!--Search END-->
     
     <div id="Loading"></div>
     
     <!--CONTENT MAIN START-->
     <div class="content" style="height:3500px;">
     
	   <div class="grid" class="grid span4" style="width:1000px; margin: 0 auto; margin-top:30px;">

		<div class="row-fluid" style="height:400px; width:1000px; margin:0 auto;">	            
		 
		  <div style="margin:15px; padding-top:20px;">
			     <span class="label label-info" style="left:0px; margin-left:10px; margin-top:40px; font-size:30px;">Clinic-Port&copy</span>
			     <div class="clearfix" style="margin-bottom:20px;"></div>
		  <?php
		  // Sección para construir la información estadística del Médico (Dashboard: relativo a "packets")  
		  //$resultDOC = mysql_query("SELECT * FROM lifepin");
		 $resultDOC = mysql_query("SELECT * FROM lifepin WHERE IdMed='$MedID'");
		  	
		  $countDOC=mysql_num_rows($resultDOC);
		  $r=0;
		  $EstadCanal = array(0,0,0,0,0,0,0);
		  $EstadCanalValid = array(0,0,0,0,0,0,0);
		  $EstadCanalNOValid = array(0,0,0,0,0,0,0);
		  $ValidationStatus = array(0,0,0,0,0,0,0,0,0,0);
		  while ($rowDOC = mysql_fetch_array($resultDOC))
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
				  	    		<span id="NombreComp" style="font: bold 24px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; ">Dr. <?php echo $MedUserName;?> <?php echo $MedUserSurname;?></span>
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
	*/	  /*				
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
		  	    		?>
		  	    		
		  	    		<div style="width:230px; float:right; margin:0px; padding:0px;"><!-- WRAPPER DE ESTA AREA --->
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<div style="width:100px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px;">
		  	    		
		  	    		<div style="height:80px; width:100px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
			  	    		<p style=" font-size:<?php echo queFuente($r);?>px; font-weight:bold; color: <?php echo $C5.'0.99)' ?>; padding-top:27px;"><?php echo $r ?></p>
		  	    		</div>
		  	    		
		  	    		<div style="width:100px;  text-align:center; margin:0px; background-color: <?php echo $C5.'0.99)' ?>; border:1px solid <?php echo $C5.'0.99)' ?>; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; ">Packets</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<div style="width:100px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px; margin-bottom:20px;">
		  	    		
		  	    		<div style="height:80px; width:100px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
			  	    		<p style=" font-size:<?php echo queFuente($ValidationStatus[0]);?>px; font-weight:bold; color: <?php echo $C0.'0.99)' ?>; padding-top:27px;"><?php echo $ValidationStatus[0] ?></p>
		  	    		</div>
		  	    		
		  	    		<div style="width:100px;  text-align:center; margin:0px; background-color: <?php echo $C0.'0.99)' ?>; border:1px solid <?php echo $C0.'0.99)' ?>; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; ">NEW</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		</div>
  	  	                <div class="clear"></div>   
		  	    		
		  	    		
		  	    		<div style="height:120px; width:100px;float:left;  text-align:center; margin-left:48px; ">
		  	    		<div style="position:relative; top: <?php echo 60-($G0/2) ?>px; height:<?php echo $G0 ?>px; width:<?php echo $G0 ?>px;    -webkit-border-radius: <?php echo $G0/2 ?>px; -moz-border-radius: <?php echo $G0/2 ?>px; border-radius: <?php echo $G0/2 ?>px; margin:0 auto; background-color: <?php echo $C0.'0.45)' ?>; ">
		  	    			<div style="height:<?php echo $P0 ?>px; width:<?php echo $P0 ?>px; position:relative; left:<?php echo ($G0/2)-($P0/2);?>px; top:<?php echo ($G0/2)-($P0/2);?>px;  -webkit-border-radius: <?php echo $P0/2 ?>px; -moz-border-radius: <?php echo $P0/2 ?>px; border-radius: <?php echo $P0/2 ?>px; background-color: <?php echo $C0.'0.99)' ?>; "></div>
		  	    		</div>
		  	    		</div>
		  	    		
		  	    		<div style="height:120px; width:100px; float:left;  text-align:center; margin-left:48px;">
		  	    		<div style="position:relative; top: <?php echo 60-($G1/2) ?>px;  height:<?php echo $G1 ?>px; width:<?php echo $G1 ?>px;    -webkit-border-radius: <?php echo $G1/2 ?>px; -moz-border-radius: <?php echo $G1/2 ?>px; border-radius: <?php echo $G1/2 ?>px; margin:0 auto; background-color: <?php echo $C1.'0.45)' ?>;">
		  	    			<div style="height:<?php echo $P1 ?>px; width:<?php echo $P1 ?>px; position:relative; left:<?php echo ($G1/2)-($P1/2);?>px; top:<?php echo ($G1/2)-($P1/2);?>px;  -webkit-border-radius: <?php echo $P1/2 ?>px; -moz-border-radius: <?php echo $P1/2 ?>px; border-radius: <?php echo $P1/2 ?>px; background-color: <?php echo $C1.'0.99)' ?>; "></div>
		  	    		</div>
		  	    		</div>	
		  	    			  	    		
		  	    		<div style="height:120px; width:100px;  float:left;  text-align:center; margin-left:48px;">
		  	    		<div style="position:relative; top: <?php echo 60-($G2/2) ?>px;  height:<?php echo $G2 ?>px; width:<?php echo $G2 ?>px;    -webkit-border-radius: <?php echo $G2/2 ?>px; -moz-border-radius: <?php echo $G2/2 ?>px; border-radius: <?php echo $G2/2 ?>px; margin:0 auto; background-color: <?php echo $C2.'0.45)' ?>; ">
		  	    			<div style="height:<?php echo $P2 ?>px; width:<?php echo $P2 ?>px; position:relative; left:<?php echo ($G2/2)-($P2/2);?>px; top:<?php echo ($G2/2)-($P2/2);?>px;  -webkit-border-radius: <?php echo $P2/2 ?>px; -moz-border-radius: <?php echo $P2/2 ?>px; border-radius: <?php echo $P2/2 ?>px; background-color: <?php echo $C2.'0.99)' ?>; "></div>
		  	    		</div>
		  	    		</div>	
		  	    				  	    		
		  	    		<div style="height:120px; width:100px;  float:left;  text-align:center; margin-left:48px;">
		  	    		<div style="position:relative; top: <?php echo 60-($G3/2) ?>px; height:<?php echo $G3 ?>px; width:<?php echo $G3 ?>px;    -webkit-border-radius: <?php echo $G3/2 ?>px; -moz-border-radius: <?php echo $G3/2 ?>px; border-radius: <?php echo $G3/2 ?>px; margin:0 auto; background-color: <?php echo $C3.'0.45)' ?>;">
		  	    			<div style="height:<?php echo $P3 ?>px; width:<?php echo $P3 ?>px; position:relative; left:<?php echo ($G3/2)-($P3/2);?>px; top:<?php echo ($G3/2)-($P3/2);?>px;  -webkit-border-radius: <?php echo $P3/2 ?>px; -moz-border-radius: <?php echo $P3/2 ?>px; border-radius: <?php echo $P3/2 ?>px; background-color: <?php echo $C3.'0.99)' ?>; "></div>
		  	    		</div>
		  	    		</div>	
		  	    				  	    		
		  	    		<div style="height:120px; width:100px; float:left;  text-align:center; margin-left:48px;">
		  	    		<div style="position:relative; top: <?php echo 60-($G4/2) ?>px;  height:<?php echo $G4 ?>px; width:<?php echo $G4 ?>px;    -webkit-border-radius: <?php echo $G4/2 ?>px; -moz-border-radius: <?php echo $G4/2 ?>px; border-radius: <?php echo $G4/2 ?>px; margin:0 auto; background-color: <?php echo $C4.'0.45)' ?>;">
		  	    			<div style="height:<?php echo $P4 ?>px; width:<?php echo $P4 ?>px; position:relative; left:<?php echo ($G4/2)-($P4/2);?>px; top:<?php echo ($G4/2)-($P4/2);?>px;  -webkit-border-radius: <?php echo $P4/2 ?>px; -moz-border-radius: <?php echo $P4/2 ?>px; border-radius: <?php echo $P4/2 ?>px; background-color: <?php echo $C4.'0.99)' ?>; "></div>
		  	    		</div>
		  	    		</div>	
		  	    				  	    		
		  	    		<div style="height:120px; width:100px; float:left;  text-align:center; margin-left:48px; ">
		  	    		<div style="position:relative; top: <?php echo 60-($G5/2) ?>px;  height:<?php echo $G5 ?>px; width:<?php echo $G5 ?>px;    -webkit-border-radius: <?php echo $G5/2 ?>px; -moz-border-radius: <?php echo $G5/2 ?>px; border-radius: <?php echo $G5/2 ?>px; margin:0 auto; background-color: <?php echo $C5.'0.45)' ?>; ">
		  	    			<div style="height:<?php echo $P5 ?>px; width:<?php echo $P5 ?>px; position:relative; left:<?php echo ($G5/2)-($P5/2);?>px; top:<?php echo ($G5/2)-($P5/2);?>px;  -webkit-border-radius: <?php echo $P5/2 ?>px; -moz-border-radius: <?php echo $P5/2 ?>px; border-radius: <?php echo $P5/2 ?>px; background-color: <?php echo $C5.'0.99)' ?>;  "></div>
		  	    		</div>
		  	    		</div>	
  	  	                
  	  	                <div class="clear"></div>   


		  	    		<div style="height:40px; width:100px;float:left;  text-align:center; margin-left:48px;">
		  	    		<p style="font-family: arial; font-size:18px; color:<?php echo $C0.'0.99)' ?>; ">Mobile</p>
			  	    	<p style="font-family: arial; font-size:14px; padding-top:0px; margin-top:-15px; color:<?php echo $C0.'0.60)' ?>; "><?php echo $V0.'/'.$VV0 ?></p>
			  	    	</div>
		  	    		
		  	    		<div style="height:40px; width:100px; float:left;  text-align:center; margin-left:48px; ">
		  	    		<p style="font-family: arial; font-size:18px; color:<?php echo $C1.'0.99)' ?>; ">Mobile+</p>
			  	    	<p style="font-family: arial; font-size:14px; padding-top:0px; margin-top:-15px; color:<?php echo $C1.'0.60)' ?>; "><?php echo $V1.'/'.$VV1 ?></p>
		  	    		</div>	
		  	    			  	    		
		  	    		<div style="height:40px; width:100px;  float:left;  text-align:center; margin-left:48px;">
		  	    		<p style="font-family: arial; font-size:18px; color:<?php echo $C2.'0.99)' ?>; ">eMail</p>
			  	    	<p style="font-family: arial; font-size:14px; padding-top:0px; margin-top:-15px; color:<?php echo $C2.'0.60)' ?>; "><?php echo $V2.'/'.$VV2 ?></p>
		  	    		</div>	
		  	    				  	    		
		  	    		<div style="height:40px; width:100px;  float:left;  text-align:center; margin-left:48px;">
		  	    		<p style="font-family: arial; font-size:18px; color:<?php echo $C3.'0.99)' ?>; ">Printer</p>
			  	    	<p style="font-family: arial; font-size:14px; padding-top:0px; margin-top:-15px; color:<?php echo $C3.'0.60)' ?>; "><?php echo $V3.'/'.$VV3 ?></p>
		  	    		</div>	
		  	    				  	    		
		  	    		<div style="height:40px; width:100px; float:left;  text-align:center; margin-left:48px;">
		  	    		<p style="font-family: arial; font-size:18px; color:<?php echo $C4.'0.99)' ?>; ">XDS</p>
			  	    	<p style="font-family: arial; font-size:14px; padding-top:0px; margin-top:-15px; color:<?php echo $C4.'0.60)' ?>; "><?php echo $V4.'/'.$VV4 ?></p>
		  	    		</div>	
		  	    				  	    		
		  	    		<div style="height:40px; width:100px; float:left;  text-align:center; margin-left:48px;">
		  	    		<p style="font-family: arial; font-size:18px; color:<?php echo $C5.'0.99)' ?>; ">Web</p>
			  	    	<p style="font-family: arial; font-size:14px; padding-top:0px; margin-top:-15px; color:<?php echo $C5.'0.60)' ?>; "><?php echo $V5.'/'.$VV5 ?></p>
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


       <div class="row-fluid" style="width:1000px; margin: 0 auto;">	            
        <!--Tabla NORMAL (NO-Dinámica) -->
        <div class="grid" style="width:96%; margin: 0 auto; margin-top:30px;">
          <div class="grid-title">
           <div class="pull-left"><div class="fam-heart-delete" style="margin-right:10px;"></div>health2.me Remote Assets and Channel Status</div>
           <div class="pull-right"></div>
           <div class="clear"></div>   
          </div>
        
        <div id="Vitalidad" style="height:100px;"> 
        	
        
        </div>            
        
        </div>
        <!--Tabla NORMAL (NO-Dinámica) -->
   
        <!--Tabla NORMAL (NO-Dinámica) -->
        <div class="grid" style="width:98%; margin: 0 auto; margin-top:30px; margin-bottom:30px;">
          <div class="grid-title">
           <div class="pull-left"><div class="fam-email-open-image" style="margin-right:10px;"></div>Clinical Information Packages</div>
           <div class="pull-right"></div>
           <div class="clear"></div>   
          </div>
          
          <div class="search-bar">
          
          	<input type="text" class="span" name="" placeholder="Search pattern" style="width:200px;" id="SearchUser"> 
          	<input type="button" class="btn btn-primary" value="Filter" style="margin-left:50px;" id="BotonBusquedaPac">
         	<img src="images/load/8.gif" alt="" style="margin-left:50px; display: none;" id="Wait1">
          
          </div>
          
          <div class="grid">
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
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script type="text/javascript" >
    /*
var timeoutTime = 300000;
var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
function ShowTimeOutWarning()
{
	alert ('Session expired');
	var a=0;
	window.location = 'timeout.html';
}




    $(document).ready(function() {

    $('body').bind('mousedown keydown', function(event) {
        clearTimeout(timeoutTimer);
        timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
    });
	
	setInterval(function() {
       $('#BotonBusquedaPac').trigger('click');
       
       $('#Vitalidad').html('       Checking     ');   
       var serviceURL = '<?php echo $domain;?>/CheckVitality.php'; 
       $('#Vitalidad').load(serviceURL);    
      }, 30000);


	
    
    $("#BotonBusquedaPac").click(function(event) {
    	    $('#Wait1').show(); 
    	    var IdUs =156;
    	    var UserInput = $('#SearchUser').val();
            var IdMed = $('#MEDID').val();
                      
    	    var serviceURL = '<?php echo $domain;?>/getBlocksD.php' + '?Usuario=' + UserInput ; 
    	    //getBlocks(serviceURL);
    	    //var longit = Object.keys(blocks).length;	
    	    //alert (longit);
    	    var queUrl ='getFullBlocks.php?Usuario='+UserInput+'&IdMed='+ IdMed;
    	    $('#TablaPac').load(queUrl);
    	    //$('#TablaPac').trigger('click');
    	    $('#TablaPac').trigger('update');
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
     
        

    $(".CFILA").live('click',function() {
     	$('#Wait1').show(); 
     	$('#BotonModal').trigger('click');
     	
     	//e.stopPropagation();
     	
     	var myClass = $(this).attr("id");
     	var NombreEnt = $('#NombreEnt').val();
     	var PasswordEnt = $('#PasswordEnt').val();
     	
     	//$('#BotonModal').trigger('click');
     	LoadPinData ($(this),this);

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
	     	queUrl ='UpdatePinUser.php?quePin='+pines[0].IdPin+'&queUsu='+myClass+'&queIdUsFIXED='+user[0].IdUsFIXED+'&queIdUsFIXEDNAME='+user[0].IdUsFIXEDNAME+'&queEmail='+user[0].email+'&queName='+user[0].Name+'&queSurname='+user[0].Surname+'&queNameDoctor='+NameDoctor+'&queSurnameDoctor='+SurnameDoctor;
	     	var sal2 = LanzaAjax (queUrl);
	     	//alert (sal2);
	     	}
	     	else
	    {
		    x="You pressed Cancel!";
		}
  
     	var NombreEnt = $('#NombreEnt').val();
     	
     	$('#CloseModal').trigger('click');
     	
    });
       
    $('#Wait1')
    .hide()  // hide it initially
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
*/ /*
 
 
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
	 	    var queUsu = $("#queUsu").val();
	 	    var queBlock = $("#queBlock").val();
	 	    var UltimoEvento = $("#UltimoEvento").val();
	 	    var cadena = '<?php echo $domain; ?>/EliminaClase.php?queBlock='+queBlock+'&queUser='+queUsu+'&UltimoEvento='+UltimoEvento+'&Nombre='+name;
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
 	  var r=confirm('Confirm updating information for this block ?');
 	  	if (r==true)
 	  	{
	 	    var queUsu = $("#queUsu").val();
	 	    var queBlock = $("#queBlock").val();
	 	    var UltimoEvento = $("#UltimoEvento").val();
	 	    
	 	    var queERU = $("#SelecERU").val();
	 	    var queEvento = $("#SelecEvento").val();
	 	    var queTipo = $("#SelecTipo").val();
	 	    
	 	    var cadena = '<?php echo $domain;?>/GrabaClasif.php?queBlock='+queBlock+'&queUser='+queUsu+'&queERU='+queERU+'&queEvento='+queEvento+'&queTipo='+queTipo;
	 	  	//alert (cadena);
	 	  	var RecTipo = LanzaAjax (cadena);
		    //alert (RecTipo);
		    $('#Episodios option:selected').remove();
         	$('#CloseModal').trigger('click');
	 	}

   	});

    $("#ImagenTH").live('click',function() {
	    $('.ContenDinamico').toggle();
      	$('#ATIA').toggle();
        
    	});    
 	
 	$("#BotonBusquedaPacCOMP").live('click',function() {
	     var UserInput = $('#SearchUserUSERFIXED').val();
	     var UserDOB = $('#SearchUserDOB').val();
	     var queUrl ='getSearchUsers.php?Usuario='+UserInput+'&UserDOB='+UserDOB+'&NReports=2';
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
		 			var RecTipo = LanzaAjax (queUrl);
		 			var mensSal =  RecTipo.substr(0,4);
		 			if (mensSal=='USER') alert (RecTipo);
		 			$('#CloseModal').trigger('click');
		 		}
 		 }
 	});	 
 	  
 	  function LoadPinData (selector,quethis){
	    
	    var queId = selector.attr("id");
	    
	    var serviceURL = '<?php echo $domain;?>/getpinData.php' + '?IdPin=' + queId; 
    	  
    	getpinData(serviceURL);
    	var longit = Object.keys(pines).length;	
	   
	    var serviceURL = '<?php echo $domain; ?>/gettipoData.php'; 
    	gettipoData(serviceURL);
    	var longit = Object.keys(tipos).length;	
	   
	    var serviceURL = '<?php echo $domain; ?>/getclaseData.php' + '?IdUsu=' + pines[0].IdUsu; 
    	getclaseData(serviceURL);
    	var longit = Object.keys(clases).length;	
	    //if (longit==0) alert ('User NOT ACTIVE IN DB');
	    
	    var queImg = pines[0].RawImage;
	    
	     
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
	    if (extensionR=='pdf')
	    {
		   	var contenTHURL = $domain.'/PackagesTH/'+ImagenRaiz+'.png';  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
		   	var contenURL =   $domain.'/Packages/'+ImagenRaiz+'.'+extensionR;  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
	    	//var conten = '<img id="ImagenN" src="'+contenURL+'" alt="">';
	    	var conten =  '<iframe id="ImagenN" style="border:1px solid #666CCC" title="PDF" src="'+contenURL+'" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
	    	var contenTH = '<img id="ImagenTH" src="'+contenTHURL+'" alt="">';
	    }
	    else
	    {
	    	if (pines[0].Location==1)  // ES JPG, pero se ha subido con la nueva APP eMapLifePROF directamente a AWS VIRGINIA
	    	{
		    	var contenTHURL = $domain.'/PackagesTH/'+queImg;  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
		    	var conten = '<img id="ImagenN" src="$domain.'/Packages/'+queImg+'" alt="">';
		    	var contenTH = '<img id="ImagenTH" src="'+contenTHURL+'" alt="">';
	    	}

	    }
	    
	    //alert (queClas);
			    	
	    //$('div.grid-content').html(conten);
	    $('#AreaConten').html(conten);
	    $('#RepoThumb').html(contenTH);
	    
	    // LOG DEL VISIONADO
	    var queMED = $("#MEDID").val();

	    var IDPIN = queId;
	    var Content = 'Infomation (ORIGINAL and THUMBNAIL) displayed to Doctor';
	    var VIEWIdUser = 0;
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
		    $('#SeccionBusqueda').hide();
		    $('#BotonAnadirPaciente').show();

	    }else
	    {
		    IDEt ='';
		    contenEt='ASSIGN USER';
		    $('#SeccionBusqueda').show();
         	$('#BotonAnadirPaciente').show();
	    }
	    
	    $('#SearchUserUSERFIXED').val('');
		$('#SearchUserDOB').val('');

	     $('#InfoIDPacienteB').html('<span id="ETUSER" class="label label-info" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">'+contenEt+'</span><span class="label label-info" style="background-color:'+quecolor+'; font-size:14px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">'+extensionR+'</span>');

	    $('#InfoIDPaciente').html(IDEt+'<span class="label label-success" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">'+pines[0].IdUsFIXED+'</span><span class="label label-success" style="font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; margin-left:20px;">'+pines[0].IdUsFIXEDNAME+'</span>');
	    
	    
	    var queUrl ='getSearchUsers.php?Usuario='+pines[0].IdUsFIXEDNAME+'&UserDOB='+pines[0].IdUsFIXED+'&NReports='+longit;
    	$('#TablaPacMODAL').load(queUrl);
    	$('#TablaPacMODAL').trigger('update');
	    
	    queUrl ='getTipoClase.php?BlockId='+queId;
      	 
      	$('.ContenDinamico').hide();
      	$('#ATIA').hide();
          
      	$('.ContenDinamico').load(queUrl);
    	    //$('#TablaPac').trigger('click');
    	$('.ContenDinamico').trigger('update');
	    
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

  function getUserData(UserId) {
 	var cadenaGUD = '<?php echo $domain;?>/GetUserData.php?UserId='+UserId;
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
?>
 

  </body>
</html>*/

