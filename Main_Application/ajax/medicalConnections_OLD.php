<?php
/*KYLE
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$NombreEnt = 'x';
$PasswordEnt = 'x';


$NombreEnt = $_GET['Nombre'];
$PasswordEnt = $_GET['Password'];
$Acceso = $_GET['Acceso'];
$MEDID = $_GET['MEDID'];

if ($Acceso != '23432')
{
echo "Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Inmers HomePage</a></h2>";
die;
}

					
// Connect to server and select databse.
$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

$result = mysql_query("SELECT * FROM doctors where id='$MEDID'");
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
	
}
else
{
echo "MEDICAL USER NOT VALID. Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Inmers HomePage</a></h2>";
die;
}

//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks");
$result = mysql_query("SELECT * FROM lifepin");

?>
<!DOCTYPE html>
<html lang="en" style="background: #F9F9F9;"><head>
    <meta charset="utf-8">
    <title>Inmers - Center Management Console</title>
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
  </head>
  <body style="background: #F9F9F9;">

     	<input type="hidden" id="MEDID" Value="<?php echo $MedID; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">	
     	<input type="hidden" id="USERDID" Value="<?php echo $USERID; ?>">	

	<!--Header Start-->
	<div class="header" >
    	
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
   	  <div id="header-modal" class="modal hide" style="display: none;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  Establish Connection with User
         </div>
         <div class="modal-body">
             <p>Please confirm that you want to link with this user.</p>
         </div>
         <input type="hidden" id="queId">
         <div class="modal-footer">
	         <input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 

	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     <ul class="menu-speedbar">
         <li><a href="dashboard.php?Acceso=23432&Nombre=<?php echo $NombreEnt;?>&Password=<?php echo $PasswordEnt;?>&MEDID=<?php echo $MedID;?>">Dashboard</a></li>
    	 <li><a href="patients.php?Acceso=23432&Nombre=<?php echo $NombreEnt;?>&Password=<?php echo $PasswordEnt;?>&MEDID=<?php echo $MedID;?>" >Patients</a></li>
         <li><a href="medicalConnections.php?Acceso=23432&Nombre=<?php echo $NombreEnt;?>&Password=<?php echo $PasswordEnt;?>" class="act_link">Connections</a></li>
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
     
     <!--CONTENT MAIN START-->
	 <div class="content">
	      
	     <div class="grid" class="grid span4" style="width:1000px; margin: 0 auto; margin-top:30px; padding-top:30px;">

	 
		 <span class="label label-info" style="left:0px; margin-left:30px; font-size:30px;">Connect to Users</span>
       
       
       
       
       
       		  <div style="margin:15px; margin-top:5px;">
		  <?php
		  // Sección para construir la información estadística del Médico (Dashboard: relativo a "packets")  
		  $r=0;
		  $EstadCanal = array(0,0,0,0,0,0,0);
		  $EstadCanalValid = array(0,0,0,0,0,0,0);
		  $EstadCanalNOValid = array(0,0,0,0,0,0,0);
		  $ValidationStatus = array(0,0,0,0,0,0,0,0,0,0);
		 	
		  $r=0;
		  while ($r<6)
		  {
		  $EstadCanal[$r]= 1;
		  $EstadCanalValid[$r]= 1;
		  $EstadCanalNOValid[$r]= 1;
		  $ValidationStatus[$r]= 1;
		  $r++;
		  }	
		  	  
		  $resultDOC = mysql_query("SELECT * FROM lifepin WHERE IdMed='$MedID' AND IdCreator='$MedID'");
		  $countDOC2=mysql_num_rows($resultDOC);
		  
		  $PacketsVistos = 0;
		  
		  //$UsersAccess = array();
		  $UsersAccess = new SplFixedArray(1000);
//		  $UsersAccess->setSize(1000);
		  $MedsAccess = new SplFixedArray(1000);
//		  $MedsAccess->setSize(1000);
 		  $indice=0;
 		  $indiceM=0;
 		  
		  while ($rowDOC = mysql_fetch_array($resultDOC))
		  {
		  	 $quePIN = $rowDOC['IdPin'];
		  	 $resultVIEW = mysql_query("SELECT * FROM bpinview WHERE IDPIN='$quePIN' AND VIEWIdMed!='$MedID' ");
		  	 $countVIEW=mysql_num_rows($resultVIEW);
		  	
		  	 if ($countVIEW>0) $PacketsVistos++;
 		   
		  	 while ($rowPIN = mysql_fetch_array($resultVIEW))
		  	 {
			  	 $UserView = $rowPIN['VIEWIdUser'];
			  	 $MedView = $rowPIN['VIEWIdMed'];
			  	 $nn=0;
			  	 $encontrado =0;
			  	 $encontradoM =0;
			  	 while ($nn<=$indice) {
			  	 	if ($UsersAccess[$nn]==$UserView) $encontrado=1;  
			  	 	if ($UsersAccess[$nn]==$MedView) $encontradoM=1;  
				  	$nn++; }
			  	 if ($encontrado==0 && $UserView!=0) {$UsersAccess[$indice] = $UserView;    $indice++;}
			  	 if ($encontradoM==0 && $MedView!=0) {$MedsAccess[$indice] = $MedView;    $indiceM++;}
		  	 }
		  	 /*
		  	 echo "<br>\n"; 
		  	 echo "PIN = ".$quePIN." --- Usuarios= ".$indice;
		  	 echo "<br>\n"; 
		  	 echo "<br>\n"; 
		  	 echo "PIN = ".$quePIN." --- Medicos= ".$indiceM;
		  	 echo "<br>\n"; 
		  	 */
		  }
		  /*
		  echo "<br>\n"; 
		  echo "PINES = ".$countDOC2." --- VIEWS= ".$PacketsVistos;
		  echo "<br>\n"; 
		  */
		  		  
		  // Variante para calcular de forma más ajustada el porcentaje de packetes sobre el total de los paquetes de los pacientes que han sido vistos por otros (EXPORTADOS) ((FORMA LARGA)
 
		  // Sección para construir la información estadística del Médico (Dashboard: relativo a "packets")  
		  /*KYLE
		  $hash = md5( strtolower( trim( $MedUserEmail ) ) );
		  $avat = 'identicon.php?size=75&hash='.$hash;
			?>	

	  	    		<div class="row-fluid"  style="">	            
		  	    		<input type="hidden" id ="quePorcentaje" value="<?php echo $porcentajeCreados ?>" /> 
		  	    		<div class="grid" style="padding:10px; height:240px;">
		  	    		
		  	    		<?php
		  				
		  				$maximo = max($EstadCanal);
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
		  	    		<input type="hidden" id="PaquetesSI" value="<?php echo $PacketsVistos?>" />
		  	    		<input type="hidden" id="PaquetesNO" value="<?php echo ($countDOC2-$PacketsVistos)?>" />
			  	    	<div style="width:180px; height:226px; float:left; margin:0px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px; ">
			  	    	<div id="MiDonut" style="width:180px; height:226px; margin-top:-10px;"></div>
			  	    	
			  	    	<div style="width:180px;  text-align:center; margin:0px; margin-top:-12px; background-color: <?php echo $C5.'0.99)' ?>; border:1px solid <?php echo $C5.'0.99)' ?>; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; ">Packet Scope</p>
		  	    		</div>	
			  	    	
			  	    	</div>
			  	    	<!--Pie END-->
			  	    		
		  	    		<div style="width:440px; float:right; margin:0px; padding:0px;"><!-- WRAPPER DE ESTA AREA --->
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS (MAS GRANDE) ---->
		  	    		<!--
		  	    		<div style="width:120px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px;">
		  	    		
		  	    		<div style="height:100px; width:120px;  text-align:center; margin:0px;">  
			  	    		<p style=" font-size:<?php echo queFuente3($indice);?>px; font-weight:bold; color: <?php echo $C5.'0.99)' ?>; padding-top:37px;"><?php echo $indice ?></p>
		  	    		</div>
		  	    		
		  	    		<div style="width:120px;  text-align:center; margin:0px; background-color: <?php echo $C5.'0.99)' ?>; border:1px solid <?php echo $C5.'0.99)' ?>; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:20px; color:white; padding:5px; margin:0px; ">Patients+</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		-->
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS (MAS GRANDE) ---->

		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<div style="width:100px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:30px;" title="This figure indicates the number of individual patients that have accessed information created by this user">
		  	    		
		  	    		<div style="height:80px; width:100px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
			  	    		<p style=" font-size:<?php echo queFuente($indice);?>px; font-weight:bold; color: <?php echo $C5.'0.99)' ?>; padding-top:27px;"><?php echo $indice ?></p>
		  	    		</div>
		  	    		
		  	    		<div style="width:100px;  text-align:center; margin:0px; background-color: <?php echo $C5.'0.99)' ?>; border:1px solid <?php echo $C5.'0.99)' ?>; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; ">Patients+</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<div style="width:100px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px; " title="This figure indicates the number of individual doctors that have accessed information created by this user">
		  	    		
		  	    		<div style="height:80px; width:100px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
			  	    		<p style=" font-size:<?php echo queFuente($indiceM);?>px; font-weight:bold; color: <?php echo $C0.'0.99)' ?>; padding-top:27px;"><?php echo $indiceM ?></p>
		  	    		</div>
		  	    		
		  	    		<div style="width:100px;  text-align:center; margin:0px; background-color: <?php echo $C0.'0.99)' ?>; border:1px solid <?php echo $C0.'0.99)' ?>; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; ">Doctors+</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<div style="width:180px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px;" title="This figure indicates the number of individual pieces of information (packets) created by this user that have benn accessed in total">
		  	    		
		  	    		<div style="height:80px; width:180px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
			  	    		<p style=" font-size:<?php echo queFuente($PacketsVistos);?>px; font-weight:bold; color: rgba(255,66,66,0.99); padding-top:27px;"><?php echo $PacketsVistos ?></p>
		  	    		</div>
		  	    		
		  	    		<div style="width:180px;  text-align:center; margin:0px; background-color: rgba(255,66,66,0.99); border:1px solid rgba(255,66,66,0.99); margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; ">Shared Packets</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<!---
			  	    	<div style="width:200px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:30px; margin-top:10px;">
		  	    		
		  	    		<div style="height:80px; width:200px;  text-align:center; margin:0px;">  
			  	    		<p style=" font-size:50px; font-weight:bold; color: rgba(115,187,59,0.99); padding-top:27px;"><?php echo number_format(($PacketsVistos*100/($countDOC2)), 0, ',', ' ').' % ' ?></p>
		  	    		</div>
		  	    		
		  	    		<div style="width:200px;  text-align:center; margin:0px; background-color: rgba(115,187,59,0.99); border:1px solid rgba(115,187,59,0.99); margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; ">New Contacts</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		-->
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<!---- BOLA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<!--
		  	    		<div style="width:100px; height:100px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 50px; -moz-border-radius: 50px; border-radius: 50px; margin-left:10px;">
		  	    		
		  	    		<div style="height:60px; width:100px;  text-align:center; margin:0px;">  
			  	    		<p style=" font-size:<?php echo queFuente2($accesibles);?>px; font-weight:bold; color: <?php echo $C0.'0.99)' ?>; padding-top:27px;"><?php echo $accesibles ?></p>
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
/*KYLE

		  	    		?>
		  	    		
		  	    		
		  	    		
		  	    				  	    		
		  	    		</div>
		  	    	</div>	


    	  </div>

       
       
       
       
       
       
       
     
       
       
            <!--Tabs Start-->
        <div style="margin-top:20px;" >
          <div id="tab-container" >
    		<ul>
    		  <li class="active"><a href="#userscloud" class="t_item1"  data-toggle="tab">Users</a></li>
              <li style=""><a href="#connections" class="t_item2" data-toggle="tab">Connections</a></li>
    		</ul>
    	</div>
        
        <div id="main-container"  class="tab-content shadow" style="height:600px;">
              <div class="tab-pane fade in active tab-overflow-main" id="userscloud">
              <h4>Users available:</h4>
                      <!--Search bar Start-->
             <div class="grid">
          <div class="grid-title">
           <div class="pull-left"><div class="fam-database-lightning" style="margin-right:10px;"></div>Request Access to Patients</div>
           <div class="pull-right"></div>
           <div class="clear"></div>   
          </div>
          
          <div class="search-bar">
          
          	<input type="text" class="span" name="" placeholder="Surname" style="width:120px;" id="SearchUser"> 
          	<input type="text" class="span" name="" placeholder="e-Mail" style="width:150px; margin-left:10px;" id="SearchEmail"> 
          	<input type="text" class="span" name="" placeholder="Fixed Id" style="width:120px;  margin-left:10px;" id="SearchIdUsFIXED"> 
          	<input type="button" class="btn btn-primary" value="Search" style="margin-left:50px;" id="BotonBusquedaPac">
         	<img src="images/load/8.gif" alt="" style="margin-left:50px; display: none;" id="Wait1">
         	
          </div>
          
          <div class="grid">
          <table class="table table-mod" id="TablaPac">
          </table> 
  
          </div>
        </div>
                      <!--Search bar END-->

               <!-- <div class="news">
                
                 <div class="post">
                 <div class="post-title"><a href="#">Creativ photo on Photoshop CS6</a></div>
                 <div class="post-date">20, September 2012</div>
                 <div class="post-desc">
                   <img src="images/strat_mini.jpg" alt=""> This is Photoshop's version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt 
                 </div>
                 </div>
                 
                 <div class="post">
                 <div class="post-title"><a href="#">Creativ photo on Photoshop CS6</a></div>
                 <div class="post-date">20, September 2012</div>
                 <div class="post-desc">
                   <img src="images/strat_mini.jpg" alt=""> This is Photoshop's version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt 
                 </div>
                 </div>
                 
                 <div class="post">
                 <div class="post-title"><a href="#">Creativ photo on Photoshop CS6</a></div>
                 <div class="post-date">20, September 2012</div>
                 <div class="post-desc">
                   <img src="images/strat_mini.jpg" alt=""> This is Photoshop's version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt ...
                 </div>
                 </div>
                 
                 <div class="post">
                 <div class="post-title"><a href="#">Creativ photo on Photoshop CS6</a></div>
                 <div class="post-date">20, September 2012</div>
                 <div class="post-desc">
                   <img src="images/strat_mini.jpg" alt=""> This is Photoshop's version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt ...
                 </div>
                 </div>
                 
                </div> -->
              </div>
              
              <div class="tab-pane tab-overflow-main" id="connections">
  
  
                <h4>Doctors available:</h4>
                      <!--Search bar Start-->
             <div class="grid">
          <div class="grid-title">
           <div class="pull-left"><div class="fam-database-lightning" style="margin-right:10px;"></div>Request Access to Doctors</div>
           <div class="pull-right"></div>
           <div class="clear"></div>   
          </div>
          
          <div class="search-bar">
          
          	<input type="text" class="span" name="" placeholder="Name" style="width:200px;" id="SearchUser"> 
          	<input type="button" class="btn btn-primary" value="Search" style="margin-left:50px;" id="BotonBusquedaPac">
         	<img src="images/load/8.gif" alt="" style="margin-left:50px; display: none;" id="Wait1">
         	
          </div>
          
          <div class="grid">
          <table class="table table-mod" id="TablaPac">
          </table> 
  
          </div>
        </div>
                      <!--Search bar END-->


  
  
                      
              </div>
          
            
          </div>
        </div>
        <!--Tabs END-->  
	     
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
    <script type="text/javascript" >
    
    $(document).ready(function() {
  
  	var user;
    var doctor;
    
    $("#BotonBusquedaPac").click(function(event) {
    	    var IdUs =156;
    	    var UserInput = $('#SearchUser').val();
    	    var UserEmail = $('#SearchEmail').val();
    	    var IdUsFIXED = $('#SearchIdUsFIXED').val();
    	    var MEDID = $('#MEDID').val();
            var queUrl ='getFullUsersLINK.php?Usuario='+UserInput+'&NReports=10&MEDID='+MEDID+'&Email='+UserEmail+'&IdUsFIXED='+IdUsFIXED;
      	    
      	    $('#TablaPac').load(queUrl);
    	    //$('#TablaPac').trigger('click');
    	    $('#TablaPac').trigger('update');
  	    
    });
    
    $("#BotonBusquedaMen").click(function(event) {
    	    var IdUs =156;
    	    var UserInput = $('#SearchUser').val();
    	    var MEDID = $('#MEDID').val();
            var queUrl ='getMessages.php?Usuario='+UserInput+'&NReports=10&MEDID='+MEDID;
      	    
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
     /*	var myClass = $(this).attr("id");
     	var NombreEnt = $('#NombreEnt').val();
     	var PasswordEnt = $('#PasswordEnt').val();
     	//window.location.replace('patientdetail.php?Nombre='+NombreEnt+'&Password='+PasswordEnt+'&IdUsu='+myClass);
     	//alert (myClass);
        $('#BotonModal').trigger('click');
      */
	  /*KYLE
    });
    
    $(".view-button").live('click',function() {
     	var myClass = $(this).attr("id");
     	$('#queId').attr("value",myClass);
     	var NameMed = $('#IdMEDName').val();
     	var SurnameMed = $('#IdMEDSurname').val();
     	var PasswordEnt = $('#PasswordEnt').val();
        var MEDID = $('#MEDID').val();
        var MEDEmail = $('#IdMEDEmail').val();
    
        $('#BotonModal').trigger('click');
    });
  
    $("#ConfirmaLink").live('click',function() {
     	var To = $('#queId').val();
    	getUserData(To);
    
    	if (user[0].email==''){
        	var IdCreador = user[0].IdCreator;
	    	
	    	alert ('orphan user . Creator= '+IdCreador);
	    	
	    	getMedCreator(IdCreador);

	    	var NameMed = $('#IdMEDName').val();
	    	var SurnameMed = $('#IdMEDSurname').val();
	    	var From = $('#MEDID').val();
	    	var FromEmail = $('#IdMEDEmail').val();
	    	var Subject = 'Request conection from Dr. '+NameMed+' '+SurnameMed;
        
	    	var Content = 'Dr. '+NameMed+' '+SurnameMed+' is requesting to establish connection with your patient named: '+user[0].Name+' '+user[0].Surname+' (UserId:  '+To+'). Please confirm, or just close this message to reject.';
    	
	    	//alert (Content);
	    	var destino = "Dr. "+doctor[0].Name+" "+doctor[0].Surname; 
	    	var cadena = '<?php echo $domain;?>/MsgInterno.php?Tipo=1&IdPac='+user[0].Identif+'&To='+doctor[0].id+'&ToEmail='+doctor[0].IdMEDEmail+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1';
	    	
	    	//alert (cadena);
	    	var RecTipo = LanzaAjax (cadena);
	    	
	    	//alert (RecTipo);
    	}
    	else
    	{
      	var NameMed = $('#IdMEDName').val();
     	var SurnameMed = $('#IdMEDSurname').val();
    	var From = $('#MEDID').val();
        var FromEmail = $('#IdMEDEmail').val();
        var Subject = 'Request conection ';
        
        var Content = 'Dr. '+NameMed+' '+SurnameMed+' is requesting to establish connection with you (UserId:  '+To+'). Please confirm, or just close this message to reject.';
    	
    	var cadena = '<?php echo $domain;?>/MsgInterno.php?Tipo=0&IdPac=0&To='+To+'&ToEmail='+user[0].email+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1';
				
		//alert (cadena);
		var RecTipo = 'Temporal';
	                     $.ajax(
                                {
                                url: cadena,
                                dataType: "html",
                                async: false,
                                complete: function(){ //alert('Completed');
                                
                                },
                                success: function(data)
                                {
                                if (typeof data == "string") {
                                RecTipo = data;
                                }
                                }
                                });
                         
	   //alert (RecTipo);	    
	   //var Content = 'Dr. '+NameMed+' '+SurnameMed+' is requesting to establish connection with you (UserId:  '+To+'). Please click the button: </br><input type="button" href="www.inmers.com/ConfirmaLink?User='+To+'&Doctor='+From+'&Confirm='+RecTipo+'" class="btn btn-success" value="Confirm" id="ConfirmaLink" style="margin-top:10px; margin-bottom:10px;"> </br> to confirm, or just close this message to reject.';
	   
	   //EnMail(user[0].email, 'MediBANK Link Request', Content);  // NO SE USA AQUÍ, PERO SI FUNCIONA PERFECTAMENTE PARA ENVIAR MENSAJES DE EMAIL DESDE JAVASCRIPT
	   }
	   
	   $('#CloseModal').trigger('click');
	   $('#BotonBusquedaPac').trigger('click');

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
  	$("#datatable_1 tbody").click(function(event) {
  		alert ('click');
		$(oTable.fnSettings().aoData).each(function (){
			$(this.nTr).removeClass('row_selected');
		});
		$(event.target.parentNode).addClass('row_selected');
	});
  */
    //alert ('ok');
    
   
/*
    setInterval(function() {
   
  
	 //  alert ('Redraw now');
	 // "bDestroy": true,
	 // "bRetrieve": true,
	

	$('#datatable_1').dataTable( {
		"bProcessing": true,
		"bDestroy": true,
		"sAjaxSource": "getBLOCKS.php"
	} );
						//location.reload();
   				 		//$('#loaddiv').fadeOut('slow').load('reload.php').fadeIn("slow");
   				 		
   				 		}, 10000);  
  				 		
  */  
  /*KYLE
  
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

  function getMedCreator(UserId) {
 	var cadenaGUD = '<?php echo $domain;?>/GetMedCreator.php?UserId='+UserId;
    $.ajax(
           {
           url: cadenaGUD,
           dataType: "json",
           async: false,
           success: function(data)
           {
           //alert ('success');
           doctor = data.items;
           }
           });
    }

  
  function EnMail (aQuien, Tema, Contenido)
  {
	  var cadena = '<?php echo $domain;?>/EnMail.php?aQuien='+aQuien+'&Tema='+Tema+'&Contenido='+Contenido;
	  var RecTipo = 'Temporal';
	  $.ajax(
      {
          url: cadena,
          dataType: "html",
          async: false,
          complete: function(){ //alert('Completed');
               },
          success: function(data)
               {
               if (typeof data == "string") {
               RecTipo = data;
                      }
               }
      });
	  //alert (RecTipo);	    
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

  
  
/* VERDES
#16ff00
#12cb00
#0e9a00
*/
/* VARIOS COL
#54bc00
#ffdb14
#6cb1ff
*/
/*KYLE
 	window.onload = function(){		
	 	
	 	var PaquetesSI = parseInt($('#PaquetesSI').val());
	 	var PaquetesNO = parseInt($('#PaquetesNO').val());
	 	var PTotal = PaquetesSI + PaquetesNO;
	 	var porcenSI = Math.floor((PaquetesSI*100)/PTotal);
	 	var porcenNO = Math.floor((PaquetesNO*100)/PTotal);
	 	Morris.Donut({
			element: 'MiDonut',
			colors: ['#0fa200','#ff5d5d'],
			formatter: function (y) { return  y +' %' },
			data: [
				{label: "Hit", value: porcenSI},
				{label: "Not used", value: porcenNO}
				]
			});
		};
  
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
    <script src="js/morris.js"></script>
    
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

function queFuente3 ($numero)
{
$queF=10;
switch ($numero)
{
	case ($numero>999 && $numero<9999):	$queF = 40;
										break;
	case ($numero>99 && $numero<1000):	$queF = 60;
										break;
	case ($numero>0 && $numero<100):	$queF = 80;
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