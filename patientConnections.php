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

if ($Acceso != '23432')
{
echo "Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Inmers HomePage</a></h2>";
die;
}
				
// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

//$result = mysql_query("SELECT * FROM usuarios where IdUsFIXEDNAME='$NombreEnt' and IdUsRESERV='$PasswordEnt'");
$result = $con->prepare("SELECT * FROM usuarios where Identif=?");
$result->bindValue(1, $IdUsu, PDO::PARAM_INT);
$result->execute();

$count=$result->rowCount();
$row = $result->fetch(PDO::FETCH_ASSOC);
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
echo "MEDICAL USER NOT VALID. Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Inmers HomePage</a></h2>";
die;
}

//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks");
$result = $con->prepare("SELECT * FROM lifepin");
$result->execute();
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



    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/icons/favicon.ico">
    
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
	<div id="content" style="padding-left:0px; background: #F9F9F9; height:1600px;">
   
	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     
   <ul class="menu-speedbar">
         <li><a href="UserAccount.php?Acceso=23432&USERID='<?php echo $USERID; ?>'&Nombre=<?php echo $IdUsFIXEDNAME;?>&Password=<?php echo $IdUsRESERV;?>">Dashboard</a></li>
    	 <li><a href="patientdetail.php?Acceso=23432&USERID='<?php echo $USERID; ?>'&Nombre=<?php echo $IdUsFIXEDNAME;?>&Password=<?php echo $IdUsRESERV;?>&IdUsu=<?php echo $USERID; ?>">Clinical Records</a></li>
         <li><a href="patientConnections.php?Acceso=23432&USERID='<?php echo $USERID; ?>'&Nombre=<?php echo $IdUsFIXEDNAME;?>&Password=<?php echo $IdUsRESERV;?>&IdUsu=<?php echo $USERID; ?>" class="act_link">Connections</a></li>
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
     
     $sql=$con->prepare("SELECT * FROM usuarios where Identif =?");
	 $sql->bindValue(1, $IdUsu, PDO::PARAM_INT);
	
     $q = $sql->execute();
     $row=$sql->fetch(PDO::FETCH_ASSOC);
     
     $Name = $row['Name'];
     $Surname = $row['Surname'];
     
     // Meter tipos en un Array
     $sql=$con->prepare("SELECT * FROM tipopin");
     $q = $sql->execute();
     
     $Tipo[0]='N/A';
     while($row=$sql->fetch(PDO::FETCH_ASSOC)){
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
   	  <button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>
   	  <div id="header-modal" class="modal hide" style="display: none;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <h3>Message</h3>
         </div>
         <div class="modal-body" id="ContenidoModal">

         </div>
         <input type="hidden" id="queId">
         <div class="modal-footer">
	         <!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">--->
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 


        <div class="grid" class="grid span4" style="width:700px; margin: 0 auto; margin-top:30px; height:1500px; padding-top:30px; padding-left:20px;">
	        	 <span class="label label-success" style="left:0px; margin-left:10px; margin-top:40px; font-size:30px;">Connections</span>
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
 
         <div class="clearfix" style="margin-bottom:20px;"></div>
        
        <span class="label label-success" style="left:0px; margin-left:0px; font-size:14px;">Messages</span>
       
        <!--Upload Box Start-->
        <div class="grid" style="width:97%;">
        	<div class="grid-content overflow">
               <table class="table table-striped table-mod" id="datatable_3" style="border: 1px solid #CACACA;">
                    <thead>
                      <tr>
                        <th class="chex-table"><input type="checkbox" id="maincheck" name="cc"/><label for="maincheck"><span></span></label></th>
                        <th class="min-width"><i class="icon-star"></i></th>
                        <th>Subject</th>
                        <th>Sender</th>
                        <th>Date</th>
                        <th>Size</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
<?php                    
$sql=$con->prepare("SELECT * FROM messages where ToId =?");
$sql->bindValue(1, $IdUsu, PDO::PARAM_INT);

$q = $sql->execute();
$Orden = 0;
while($row=$sql->fetch(PDO::FETCH_ASSOC)){
     	$MId=$row['id'];
     	$Visto=$row['Leido'];
     	$Importante=$row['Importante'];
     	$Subject=$row['Subject'];
     	$Sender=$row['FromEmail'];
     	$Date=$row['Fecha'];
     	$Content=$row['Content'];
     	$Orden++;

?>     

                      <tr class="CFILA" id="<?php echo $MId; ?>" Orden="<?php echo $Orden; ?>">
                        <td class="chex-table"><input type="checkbox" name="numbers[]" class="mc" value="<?php echo $Visto; ?>" id="1" /><label for="1"><span></span></label></td>
                        <td class="min-width"><a href="#"><i class="icon-star"></i></a></td>
                        <td><a href="#"><?php echo $Subject; ?></a></td>
                        <td><?php echo $Sender; ?></td>
                        <td><?php echo $Date; ?></td>
                        <td style="width:0px;">File: 34 KB</td>
                        <td style="width:0px;"><div style="overflow:hidden; white-space:nowrap; width:1px !important; height:1px !important;"><?php echo $Content; ?></div></td>
                      </tr>
<?php
}
?>
                    </tbody>
                    </table>
              
              
              
        	</div>
        </div>
        <!--Upload Box END-->
        <div class="clearfix" style="margin-bottom:20px;"></div>
        

        
                      
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
    <script type="text/javascript" >
    
    $(document).ready(function() {
    
    $(".CFILA").live('click',function() {
     	var myClass = $(this).attr("id");
     	var NombreEnt = $('#NombreEnt').val();
     	var PasswordEnt = $('#PasswordEnt').val();

     	// Método para seleccionar con JQuery una columna específica dentro de la selección de una FILA, conservando el ID creado.
     	var queSelId = ".CFILA#"+myClass;
		var queOrden = $(queSelId).attr('orden');
		var queSel = ".CFILA:nth-child("+queOrden+") td:nth-child(4)";
     	var queFrom = $(queSel).html();
     	var queSel = ".CFILA:nth-child("+queOrden+") td:nth-child(3)";
     	var queSubj = $(queSel).html();
     	var queSel = ".CFILA:nth-child("+queOrden+") td:nth-child(7)";
     	var queConten = $(queSel).html();
     	
     	var ContenidoVM = '';
     	ContenidoVM = ContenidoVM +  ' </br>';
      	ContenidoVM = ContenidoVM + '<p><span style="font-weight:bold;">From: </span><span style="font-weight: normal; color:grey;">'+queFrom+'</span></p>';
     	ContenidoVM = ContenidoVM + '<p><span style="font-weight:bold;">Subject: </span><span style="font-weight: normal; color:blue; margin-top:5px;">'+queSubj+'</span></p>';
     	ContenidoVM = ContenidoVM +  '<hr>';
     	ContenidoVM = ContenidoVM + queConten.substr(95, (queConten.length-95-6));
      	
     	/*
      	ContenidoVM = ContenidoVM +  ' </br>';
      	ContenidoVM = ContenidoVM +  'Dr. Name Surname (name@mail.com) is requesting to establish connection with you. Please click the button: ';
     	ContenidoVM = ContenidoVM +  ' </br>';
      	ContenidoVM = ContenidoVM +  '<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink" style="margin-top:10px; margin-bottom:10px;">';
     	ContenidoVM = ContenidoVM +  ' </br>';
     	ContenidoVM = ContenidoVM +  ' to confirm, or just close this message to reject.';
     	*/

     	$('#ContenidoModal').html(ContenidoVM);
     	$('#BotonModal').trigger('click');

    });


 
 
	}); 		
	</script>

  </body>
</html>

