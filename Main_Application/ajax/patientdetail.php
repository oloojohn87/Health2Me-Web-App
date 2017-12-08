<?php
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$NombreEnt = $_GET['Nombre'];
$PasswordEnt = $_GET['Password'];
//$MEDID = $_GET['MEDID'];
$Acceso = $_GET['Acceso'];
$IdUsu = $_GET['IdUsu'];

//include 'UsuTimeline.php?IdUsu='.$IdUsu;
CreaTimeline($IdUsu);

if ($Acceso != '23432')
{
echo "Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Inmers HomePage</a></h2>";
die;
}
				
					// Connect to server and select databse.
//KYLE$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

//$result = mysql_query("SELECT * FROM usuarios where IdUsFIXEDNAME='$NombreEnt' and IdUsRESERV='$PasswordEnt'");
$result = mysql_query("SELECT * FROM usuarios where Identif='$IdUsu'");

$count=mysql_num_rows($result);
$row = mysql_fetch_array($result);
$success ='NO';
if($count==1){
	$success ='SI';
	$USERID = $row['Identif'];
//	$MedUserEmail= $row['IdMEDEmail'];
	$MedUserName = $row['Name'];
	$MedUserSurname = $row['Surname'];
	$IdUsFIXED = $row['IdUsFIXED'];
	$IdUsFIXEDNAME = $row['IdUsFIXEDNAME'];
	$IdUsRESERV = $row['IdUsRESERV'];
	$email = $row['email'];

//	$MedUserLogo = $row['ImageLogo'];
	
}
else
{
echo "USER NOT VALID. Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Inmers HomePage</a></h2>";
die;
}

//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks");
$result = mysql_query("SELECT * FROM lifepin");

?>
<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>health2.me Patient Detail</title>
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
    <link rel="stylesheet" href="css/jvInmers.css">

    <link rel="stylesheet" type="text/css" href="css/tooltipster.css" />

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

  <body onload="$('.note').trigger('click');">


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
              <span class="name-user"><strong>Welcome</strong>, <?php echo $MedUserName.' '.$MedUserSurname; ?></span> 
             <?php 
             $hash = md5( strtolower( trim( $email ) ) );
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
	<div id="content" style="padding-left:0px; background: #F9F9F9; height:2100px;">
   
	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     
   <ul class="menu-speedbar">
         <li><a href="UserAccount.php?Acceso=23432&USERID='<?php echo $USERID; ?>'&Nombre=<?php echo $IdUsFIXEDNAME;?>&Password=<?php echo $IdUsRESERV;?>" >Dashboard</a></li>
    	 <li><a href="patientdetail.php?Acceso=23432&USERID='<?php echo $USERID; ?>'&Nombre=<?php echo $IdUsFIXEDNAME;?>&Password=<?php echo $IdUsRESERV;?>&IdUsu=<?php echo $USERID; ?>" class="act_link">Clinical Records</a></li>
         <li><a href="patientConnections.php?Acceso=23432&USERID='<?php echo $USERID; ?>'&Nombre=<?php echo $IdUsFIXEDNAME;?>&Password=<?php echo $IdUsRESERV;?>&IdUsu=<?php echo $USERID; ?>">Connections</a></li>
         <li><a href="form_validation.php?Acceso=23432&USERID='<?php echo $USERID; ?>'">Configuration</a></li>
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
     
     <?php             // AREA PRINCIPAL DE ASOCIACIÓN DE LA INFORMACIÓN DEL PACIENTE  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     //$IdUsu=131;
     
     $sql="SELECT * FROM usuarios where Identif ='$IdUsu'";
     $q = mysql_query($sql);
     $row=mysql_fetch_assoc($q);
     
     $Name = $row['Name'];
     $Surname = $row['Surname'];
     
     // Meter tipos en un Array
     $sql="SELECT * FROM tipopin";
     $q = mysql_query($sql);
     
     $Tipo[0]='N/A';
     while($row=mysql_fetch_assoc($q)){
     	$Tipo[$row['Id']]=$row['NombreEng'];
     }
     
     $Tipo[999]='N/A';
     // Meter clases en un Array
     $Clase[999]='Episode';
     $Clase[0]='Episode';
     $Clase[1]='Check or Preventive';
     $Clase[2]='Isolated Report';
     $Clase[3]='Drug Data';
     
     
     ?>

     <!--CONTENT MAIN START-->

     
     <!--CONTENT MAIN START-->
     <div class="content" >
     	  <!--- VENTANA MODAL  ---> 
   	  <!--<button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>-->
   	  <div id="header-modal" class="modal hide" style="display: none; height:470px;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <h4>Set Classification</h3>
         </div>
         
         <div class="modal-body" id="ContenidoModal" style="height:320px;">
             <div  id="RepoThumb" style="width:70px; float:right; -webkit-box-shadow: 3px 3px 14px rgba(25, 25, 25, 0.5); -moz-box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5); box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5);"></div>
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
         </div>
         </div>
         <input type="hidden" id="queId">
         <div class="modal-footer">
	         <!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">--->
             <a href="#" class="btn btn-success" data-dismiss="modal" id="GrabaDatos">Update Data</a>
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 
     
     
     
        <div class="grid" class="grid span4" style="width:1000px; margin: 0 auto; margin-top:30px; height:2000px; padding-top:30px; padding-left:20px;">
	        	 <span class="label label-success" style="left:0px; margin-left:10px; margin-top:40px; font-size:30px;">Clinical Records</span>
			     <div class="clearfix" style="margin-bottom:20px;"></div>

		  <?php
		  $hash = md5( strtolower( trim( $email ) ) );
		  $avat = 'identicon.php?size=50&hash='.$hash;
			?>	
			    <img src="<?php echo $avat; ?>" style="float:right; margin-right:20px; font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace;"/>
				<span id="NombreComp" style="font: bold 24px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; "><?php echo $MedUserName;?> <?php echo $MedUserSurname;?></span>
		  		<span id="IdUsFIXED" style="font-size: 12px; color: #3D93E0; font-weight: normal; font-family: Arial, Helvetica, sans-serif; display: block; margin-left:20px;"><?php echo $IdUsFIXED;?></span>
			  	<span id="IdUsFIXEDNAME" style="font-size: 14px; color: GREY; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:20px;"><?php echo $IdUsFIXEDNAME;?></span>
	  	    	<span id="email" style="font-size: 14px; color: #5EB529; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:20px;"><?php echo $email;?></span>

        <!--NOTES Start-->
        
         <!--Upload Box Start-->
        <div class="grid" style="width:97%;">
        <input type="hidden" id="IdUsuP" value="<?php echo $IdUsu ?>" />
        
        	<div class="grid-content overflow">
              <div id="BotonUpload"  class="pull-left"><a href="#" class="btn" title="Upload Report"><i class="icon-upload-alt"></i> Upload Report</a> </div>
              <div class="pull-left" style="margin-left:20px;"><a href="patientReportGallery.php?Acceso=23432&USERID='<?php echo $USERID; ?>'&Nombre=<?php echo $IdUsFIXEDNAME;?>&Password=<?php echo $IdUsRESERV;?>&IdUsu=<?php echo $USERID; ?>" class="btn"><i class="icon-folder-open"></i> Records Board</a> </div>
              
              <button id="BotonMod" data-target="#header-modal" data-toggle="modal" class="btn" style="float:right; margin-right:10px;"><i class="icon-indent-left"></i>Classification</button>
              
             
        	</div>
        </div>
        <!--Upload Box END-->
        <div class="clearfix" style="margin-bottom:20px;"></div>
        
        
        <span class="label label-success" id="EtiTML" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">Health History Timeline</span>
        <div class="grid span3" style="margin-left:0px; width:98%; height:400px; margin-bottom:20px;" >
         <!-- BEGIN Timeline Embed -->
         	<div id="timeline-embed"></div>
         		<script type="text/javascript">
	         		var timeline_config = {
		         		width: "100%",
		         		height: "100%",
		         		source: 'jsondata2.txt'
		         		//     source: 'example_json.json'
		         		}
		         </script>
		 <!-- END Timeline Embed-->
        </div>
        
                    <div class="clear"></div>   
                     
        <span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px;">Health Repository</span>
        <div class="scroll-pane horizontal-only notes" style="height: 290px; width:97%; border:1px solid #cacaca; margin-top:10px; background-color:white;" >
        <div style="width: 2420px; height: 250px; margin-top:10px; margin-left:10px;">
<?php

//BLOCKSLIFEPIN     $sql="SELECT * FROM blocks where IdUsu ='$IdUsu' ORDER by Fecha DESC";
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

$extensionR = substr($row['RawImage'],strlen($row['RawImage'])-3,3);
$ImageRaiz = substr($row['RawImage'],0,strlen($row['RawImage'])-4);

if ($extensionR!='jpg')
{
	$selecURL =$domain.'/PackagesTH/'.$ImageRaiz.'.png';
	$selecURLAMP =$domain.'/Packages/'.$ImageRaiz.$extensionR;
}
else
{
	$subtipo = substr($row['RawImage'], 3 , 2);
	if ($subtipo=='XX')  { $selecURL =$domain.'/Packages/'.$row['RawImage']; }
	else { $selecURL ='http://www.monimed.com/eMapLife/PinImageSetTH/'.$row['RawImage']; }
	// COMPROBACIÓN DE EXISTENCIA DEL ARCHIVO (PARA LOS CASOS DE EMAPLIFE iOS o ANDROID QUE TODAVIA NO GENERAN THUMBNAILS Y NO REFERENCIAN AL DIRECTORIO -TH
	$file = $selecURL;
	$file_headers = @get_headers($file);
	if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
  	  	$exists = false;
  	  	$selecURL ='http://www.monimed.com/eMapLife/PinImageSet/'.$row['RawImage'];
  	  }
  	  else {
	  	  $exists = true;
	  	  }
}


//echo "-----------------------------------------------------------";
?>
          <div class="note" id="<?php echo $row['IdPin']; ?>" style="-webkit-box-shadow: 3px 3px 14px rgba(25, 25, 25, 0.5); -moz-box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5); box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5);">
          
            <div class="THUMB" id="<?php echo $row['RawImage']; ?>" style="width:140px; height:180px; overflow: hidden"><img src="<?php echo $selecURL; ?>" alt=""></div>
            <div class="note-category cat-green" style="height:15px; margin-top:0px; margin-bottom:0px; font-size:10px;"  id="<?php if (!$row['Tipo']){$indi = 999;}else{$indi = $row['Tipo'];};echo $Tipo[$indi];?>"><?php echo $Tipo[$indi];?></div>
            <!--<div class="note-title" style="height:15px; margin-top:0px; margin-bottom:0px; font-size:12px;"  id="<?php echo $Clase[$row['EvRuPunt']];?>"><?php echo $Clase[$row['EvRuPunt']];?></div> -->
            <div class="note-title" style="height:15px; margin-top:0px; margin-bottom:0px; font-size:12px;"  id="<?php if (!$row['EvRuPunt']){$indi = 999;}else{$indi = $row['EvRuPunt'];}; echo $Clase[$indi];?>"><?php echo $Clase[$indi];?></div>
            <div class="note-more" style="height:15px; margin-top:0px; margin-bottom:0px;"  id="<?php 
            
            $FechaNum = strtotime($row['Fecha']);
            $FechaBien = date ("D M j Y",$FechaNum);
            echo $FechaBien;
            
            
            ?>"><a href="#"><?php 
            
            $FechaNum = strtotime($row['Fecha']);
            $FechaBien = date ("D M j Y",$FechaNum);
            echo $FechaBien;
            
            
            ?></a>
            </div>
            
            
            <div class="note-info"><span><?php echo substr($row['RawImage'],1,15);?></span></div>
            </div>
            <div style="height:250px; float:left; margin-left:-20px; padding-left:0px;"><p class="css-vertical-text" style="font-size:10px; color:grey;"><?php echo $FechaBien; ?></p></div>

<?php
};
?>
        </div>
        </div>  
        <!--NOTES END-->


     <div class="grid span3" style="width:90%;">
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
          
          <div class="grid-content" id="AreaConten" style="">
<?php
//BLOCKSLIFEPIN $sql="SELECT * FROM blocks where IdUsu ='$IdUsu'";
$sql="SELECT * FROM lifepin where IdUsu ='$IdUsu'";
$q = mysql_query($sql);
$row=mysql_fetch_assoc($q);
?>
 
             <img id="ImagenAmp" style="margin:0 auto;" src="">
          </div>
        </div>
        
                      
     </div>
     <!--CONTENT MAIN END-->

    </div>
	</div>
    <!--Content END-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

	<script type="text/javascript" src="js/storyjs-embed.js"></script>

    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
   
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
  
  <!--  <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.0.min.js"></script>--->
    <script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>

  
    <script type="text/javascript" >
    
    $(document).ready(function() {

	$("#EtiTML").live('click',function() {
		alert ('Timeline launched');
		var timeline_config = {
		         		width: "100%",
		         		height: "100%",
		         		source: 'jsondata2.txt'
		         		//     source: 'example_json.json'
		         		}

		});    

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
	 	    var cadena = '<?php echo $domain;?>/EliminaClase.php?queBlock='+queBlock+'&queUser='+queUsu+'&UltimoEvento='+UltimoEvento+'&Nombre='+name;
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

    $("#BotonUpload").live('click',function() {
	 
	  	var queUsu = $("#IdUsuP").val();
	 	var cadena = '<?php echo $domain;?>/UsuTimeline.php?Usuario='+queUsu+'&IdMed=0';
	 	var RecTipo = LanzaAjax (cadena);
	    alert (RecTipo);
	    //alert (RecTipo);
	    /*
	    var IDPIN = 0;
	    var Content = 0;
	    var VIEWIdUser = 0;
	    var VIEWIdMed = 0;
	    var VIEWIP = 0;
	    var MEDIO = 0;

	 	var RecTipo = LanzaAjax (cadena);
	 	//alert (RecTipo);
	 	*/
   	});


//     $('this option:selected').remove();
    
    $('.note').click( function () {
	    
	    var queId = $(this).attr("id");
	    var queImg = $(".THUMB", this).attr("id");
	    var queTip = $(".cat-green", this).attr("id");
	    var queClas = $(".note-title", this).attr("id");
	    var queFecha = $(".note-more", this).attr("id");
	    	    
	    var queUsu = $("#IdUsuP").val();
	 	
	 	var IDPIN = queId;
	    var Content = 'Information (ORIGINAL) displayed to user';
	    var VIEWIdUser = queUsu;
	    var VIEWIdMed = 0;
	    var MEDIO = 0;
	    var cadena = '<?php echo $domain;?>/LogEvent.php?IDPIN='+IDPIN+'&Content='+Content+'&VIEWIdUser='+VIEWIdUser+'&VIEWIdMed='+VIEWIdMed+'&MEDIO='+MEDIO;
	 	var RecTipo = LanzaAjax (cadena);
	 	//alert (RecTipo);
	    
	    var extensionR = queImg.substr(queImg.length-3,3);
	    var ImagenRaiz = queImg.substr(0,queImg.length-4);
	    var subtipo = queImg.substr(3,2);  // Para los casos en que eMapLife+ (PROF) ya sube las imagenes a AMAZON y no a GODADDY
	    //$ImageRaiz = substr($row['RawImage'],0,strlen($row['RawImage'])-4);
	    if (extensionR=='pdf')
	    {
		   	var contenTHURL = '<?php echo $domain;?>/PackagesTH/'+ImagenRaiz+'.png';  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
		   	var contenURL =   '<?php echo $domain;?>/Packages/'+ImagenRaiz+'.'+extensionR;  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
	    	//var conten = '<img id="ImagenN" src="'+contenURL+'" alt="">';
	    	var conten =  '<iframe id="ImagenN" style="border:1px solid #666CCC; margin:0 auto; display:block;" title="PDF" src="'+contenURL+'" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
	    	var contenTH = '<img id="ImagenTH"  src="'+contenTHURL+'" alt="">';
	    }
	    else
	    {
	    	if (subtipo=='XX') { 
	    		var contenTHURL = '<?php echo $domain;?>/Packages/'+queImg; 
	    		var conten = '<img id="ImagenN" src="<?php echo $domain;?>/Packages/'+queImg+'" alt="" style="border:1px solid #666CCC; margin:0 auto; display:block;">';
	    		}
	    	else{ 
	    		var contenTHURL = 'http://www.monimed.com/eMapLife/PinImageSetTH/'+queImg; 
	    		var conten = '<img id="ImagenN" src="http://www.monimed.com/eMapLife/PinImageSet/'+queImg+'" alt="" style="border:1px solid #666CCC; margin:0 auto; display:block;">';
	    		}  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
	    	var contenTH = '<img id="ImagenTH"  src="'+contenTHURL+'" alt="">';
	    //if(urlExists(contenTHURL)) {}else { contenTHURL = 'http://www.monimed.com/eMapLife/PinImageSet/'+queImg;}
	    }
	    
	    //alert (queClas);
			    	
	    //$('div.grid-content').html(conten);
	    $('#AreaConten').html(conten);
	    $('#RepoThumb').html(contenTH);
	    
	    //$('div.pull-left.a').html(queTip);
	    $('#AreaTipo').html(queTip);
	    
	    //$('.ClClas').html(queClas);
	    $('#AreaClas').html(queClas);
	    
	    //$('div.grid-title-label').html('<span class="label label-warning" style="font-size:16px;">'+queFecha+'</span>');
	    $('#AreaFecha').html('<span class="label label-warning" style="font-size:16px;">'+queFecha+'</span>');
	    
	    var queUrl ='getTipoClase.php?BlockId='+queId;
      	    
      	$('.ContenDinamico').load(queUrl);
    	    //$('#TablaPac').trigger('click');
    	$('.ContenDinamico').trigger('update');
	    
	    });
   
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
 
	}); 		
	</script>

  </body>
</html>
<?php

function url_exists($url) {
    if (!$fp = curl_init($url)) return false;
    return true;
}

?>
<?php
function CreaTimeline($Usuario)
{
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="usuarios"; // Table name

//KYLE$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

//$queUsu = $_GET['Usuario'];
//$queMed = $_GET['IdMed'];
$queUsu = $Usuario;
$queMed = 0;


     $sql="SELECT * FROM usuarios where Identif ='$queUsu'";
     $q = mysql_query($sql);
     $row=mysql_fetch_assoc($q);
     
     $Name = $row['Name'];
     $Surname = $row['Surname'];
     
     // Meter tipos en un Array
     $sql="SELECT * FROM tipopin";
     $q = mysql_query($sql);
     
     $Tipo[0]='N/A';
     while($row=mysql_fetch_assoc($q)){
     	$Tipo[$row['Id']]=$row['NombreEng'];
     }
     
     $Tipo[999]='N/A';
     // Meter clases en un Array
     $Clase[999]='Episode';
     $Clase[0]='Episode';
     $Clase[1]='Check or Preventive';
     $Clase[2]='Isolated Report';
     $Clase[3]='Drug Data';

	 $email = $row['email'];
     $hash = md5( strtolower( trim( $email ) ) );
	 $avat = 'identicon.php?size=50&hash='.$hash;





$cadena='{
    "timeline":
    {
        "headline":"Health Events",
        "type":"default",
        "text":"<p>User Id.: '.$queUsu.'</p>",
        "asset": {
            "media":"'.$domain.'/images/ReportsGeneric.png", 
            "credit":"(c) health2.me",
            "caption":"Use side arrows for browsing"
        },
        "date": [               
        ';


//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks ORDER BY FechaInput DESC");
$result = mysql_query("SELECT * FROM lifepin WHERE IdUsu='$queUsu' ORDER BY FechaInput DESC LIMIT 50");

$numero=mysql_num_rows($result) ;
$n=0;

while ($row = mysql_fetch_array($result))
{

$extensionR = substr($row['RawImage'],strlen($row['RawImage'])-3,3);
$ImageRaiz = substr($row['RawImage'],0,strlen($row['RawImage'])-4);

if ($extensionR!='jpg')
{
	$selecURL =$domain.'/PackagesTH/'.$ImageRaiz.'.png';
	$selecURLAMP =$domain.'/Packages/'.$ImageRaiz.$extensionR;
}
else
{
	$subtipo = substr($row['RawImage'], 3 , 2);
	if ($subtipo=='XX')  { $selecURL =$domain.'/Packages/'.$row['RawImage']; }
	else { $selecURL ='http://www.monimed.com/eMapLife/PinImageSetTH/'.$row['RawImage']; }
	// COMPROBACIÓN DE EXISTENCIA DEL ARCHIVO (PARA LOS CASOS DE EMAPLIFE iOS o ANDROID QUE TODAVIA NO GENERAN THUMBNAILS Y NO REFERENCIAN AL DIRECTORIO -TH
	$file = $selecURL;
	$file_headers = @get_headers($file);
	if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
  	  	$exists = false;
  	  	$selecURL ='http://www.monimed.com/eMapLife/PinImageSet/'.$row['RawImage'];
  	  }
  	  else {
	  	  $exists = true;
	  	  }
}

if (!$row['Tipo']){$indi = 999;}else{$indi = $row['Tipo'];};
//echo $Tipo[$indi];
//echo $Tipo[$indi];

if (!$row['EvRuPunt']){$indi2 = 999;}else{$indi2 = $row['EvRuPunt'];}; 

if ($n>0) $cadena=$cadena.',';
$n++;


//$FechaFor =  date('j/n/y H:i:s',strtotime($row['Fecha']));
$FechaFor =  date('n/j/Y H:i:s',strtotime($row['Fecha']));

$cadena = $cadena.'
            {
                "startDate":"'.$FechaFor.'",
                "endDate":"'.$FechaFor.'",
                "headline":"'.$Tipo[$indi].'",
                "text":"<p>'.$Clase[$indi2].'</p>",
                "tag":"'.$Clase[$indi2].'",
                "asset": {
                    "media":"'.$selecURL.'",
                    "thumbnail":"'.$selecURL.'",
                    "credit":"(r) Author: '.$email.' ('.$Name.' '.$Surname.')",
                    "caption":""
                    }
            }
';


}

$cadena = $cadena.'
       ],
        "era": [
            {
                "startDate":"2013,12,10",
                "endDate":"2013,12,11",
                "headline":"Inmers Clinical Timeline",
                "text":"<p>Powered by eMapLife</p>"
            }

        ]
    }
}';

$jsondata = json_encode($cadena);

//echo "***********************************************************************************";
//echo $cadena;
//echo "***********************************************************************************";

/*
                "startDate":"'.$row['Fecha'].'",
                "endDate":"'.$row['Fecha'].'",
                "headline":"'.$Tipo[$indi].'",
                "text":"<p>'.$Clase[$indi2].'</p>",
                "tag":"'.$Clase[$indi2].'",
                "asset": {
                    "media":"'.$selecURL.'",
                    "thumbnail":"'.$selecURL.'",
                    "credit":"Credit Name Goes Here",
                    "caption":"Caption text goes here"
                    }

*/

//$cadena = str_replace('\n','',$cadena);
//$cadena = str_replace('\r','',$cadena);
//$cadena = str_replace(' ','',$cadena);

$countfile="jsondata2.txt";
$fp = fopen($countfile, 'w');
fwrite($fp, $cadena);
fclose($fp);
//sleep(5);
}
?>
