<?php
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$QueUsu= $_GET['USERID'];
$Acceso = $_GET['Acceso'];

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

$QueQuery = $con->prepare("SELECT * FROM usuarios where Identif=?");
$QueQuery->bindValue(1, $QueUsu, PDO::PARAM_INT);


$result = $QueQuery->execute();
//$result = mysql_query("SELECT * FROM usuarios where IdUsFIXEDNAME='$NombreEnt' and IdUsRESERV='$PasswordEnt'");

$count=$QueQuery->rowCount();
$row = $QueQuery->fetch(PDO::FETCH_ASSOC);
$success ='NO';
if($count==1){
	$success ='SI';
	$USERID = $row['Identif'];
//	$MedUserEmail= $row['IdMEDEmail'];

	$QueDOB = $row['FNac'];
	$QueGender = $row['Sexo'];
	$QueOrden = $row['Orden'];
	$IdUsFIXED = $row['IdUsFIXED'];
	
	$UserName = $row['Name'];
	$UserSurname = $row['Surname'];
	$UserPassword = $row['IdUsRESERV'];
	$IdUsRESERV = $row['IdUsRESERV'];
	$IdUsFIXEDNAME = $row['IdUsFIXEDNAME'];
	
	$email = $row['email'];
	$telefono = $row['telefono'];
	
	$MedUserName = $row['Name'];
	$MedUserSurname = $row['Surname'];
		
//	$MedUserLogo = $row['ImageLogo'];
$QueEntrada = 1;  // 1= EDICIÓN , 2= CREACIÓN.
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
    <title>Inmers</title>
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

<!--  <body onload="CargaDatos(<?php echo $QueEntrada;?>);">-->
<script type="text/javascript" >
 	function CargaDatos(queEntrada)
	{
		if (queEntrada ==1)
		{
			//retf = $('#IdDoB').val();
			//alert (retf);
			$('#dp32').val($('#IdDoB').val());
			$('#gender').val($('#IdGender').val());
			$('#orderOB').val($('#IdOrden').val());
			
			$('#VIdUsFIXED').html($('#IdUsFIXED').val());

			$('#Vname').val($('#IdUserName').val());
			$('#Vsurname').val($('#IdUserSurname').val());
			$('#Vpassword').val($('#IdUserPassword').val());
			$('#password2').val($('#IdUserPassword').val());
			$('#VIdUsFIXEDNAME').html($('#IdUsFIXEDNAME').val());

			$('#Vemail').val($('#IdEmail').val());
			$('#Vphone').val($('#IdTelefono').val());

			$('#VIdUsFIXEDINSERT').html($('#IdUsFIXED').val());
			$('#VIdUsFIXEDNAMEINSERT').html($('#IdUsFIXEDNAME').val());

		}
	}  
</script>

 
  <body onload="CargaDatos(<?php echo $QueEntrada; ?>)">


	<!--Header Start-->
	<div class="header" >
     	<input type="hidden" id="USERDID" Value="<?php echo $USERID; ?>">	
    	
    	<input type="hidden" id="IdDoB" Value="<?php echo $QueDOB; ?>">	
    	<input type="hidden" id="IdGender" Value="<?php echo $QueGender; ?>">	
    	<input type="hidden" id="IdOrden" Value="<?php echo $QueOrden; ?>">	
    	<input type="hidden" id="IdUsFIXED" Value="<?php echo $IdUsFIXED; ?>">	
    	
    	<input type="hidden" id="IdUserName" Value="<?php echo $UserName; ?>">	
    	<input type="hidden" id="IdUserSurname" Value="<?php echo $UserSurname; ?>">	
    	<input type="hidden" id="IdUserPassword" Value="<?php echo $UserPassword; ?>">	
    	<input type="hidden" id="IdUsFIXEDNAME" Value="<?php echo $IdUsFIXEDNAME; ?>">	
  
    	<input type="hidden" id="IdEmail" Value="<?php echo $email; ?>">	
    	<input type="hidden" id="IdTelefono" Value="<?php echo $telefono; ?>">	

    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">	
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

    
    <!--Sidebar Start --->
<!--	<div id="sidebar">  -->
    <!--
      <ul class="menu-sidebar">
       <li><a href=""><i class="icon-home"></i> <span>Home</span></a></li>
       <li><a href="UserAccount.php?Acceso=23432&USERID='<?php echo $USERID; ?>'&Nombre=<?php echo $IdUsFIXEDNAME;?>&Password=<?php echo $IdUsRESERV;?>"><i class="icon-group"></i> <span>User</span></a></li>
       <li><a href="form_validation.php?Acceso=23432&USERID='<?php echo $USERID; ?>'"><i class="icon-edit"></i> <span>Setup</span></a></li>
      </ul>
    --> 
    
<!--    </div> --->
	<!--    Sidebar END-->

    
    <!--Content Start-->
	<div id="content" style="padding-left:0px; background: #F9F9F9; height:1200px;">
    
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
         <li><a href="UserAccount.php?Acceso=23432&USERID='<?php echo $USERID; ?>'&Nombre=<?php echo $IdUsFIXEDNAME;?>&Password=<?php echo $IdUsRESERV;?>">Dashboard</a></li>
    	 <li><a href="patientdetail.php?Acceso=23432&USERID='<?php echo $USERID; ?>'&Nombre=<?php echo $IdUsFIXEDNAME;?>&Password=<?php echo $IdUsRESERV;?>&IdUsu=<?php echo $USERID; ?>">Clinical Records</a></li>
         <li><a href="patientConnections.php?Acceso=23432&USERID='<?php echo $USERID; ?>'&Nombre=<?php echo $IdUsFIXEDNAME;?>&Password=<?php echo $IdUsRESERV;?>&IdUsu=<?php echo $USERID; ?>">Connections</a></li>
         <li><a href="form_validation.php?Acceso=23432&USERID='<?php echo $USERID; ?>'" class="act_link">Configuration</a></li>
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
	<div id="content" style="padding-left:0px; background: #F9F9F9; height:1200px;">
        
        <!--Form Validation Start-->
        <div class="grid" class="grid span4" style="width:700px; margin: 0 auto; margin-top:30px;">

          <div class="grid-content" style="padding-top:30px;">
           	<span class="label label-success" style="left:0px; margin-left:10px; margin-top:40px; font-size:30px;">Account Configuration</span>
		    <div class="clearfix" style="margin-bottom:20px;"></div>

            <form id="formID" class="formular" method="post" action="UpdateUser.php">
                 
                 <input type="hidden" id="EnviaUserid" name="EnviaUserid" Value="<?php echo $USERID; ?>">	
                 <input type="hidden" id="EnviaModo" name="EnviaModo" Value="<?php echo $QueEntrada; ?>">	
                 <input type="hidden" id="EnviaTipoUsuario" name="EnviaTipoUsuario" Value="2">	
                 <input type="hidden" id="VIdUsFIXEDINSERT" name="VIdUsFIXED" Value="<?php echo $IdUsFIXED; ?>">	
                 <input type="hidden" id="VIdUsFIXEDNAMEINSERT" name="VIdUsFIXEDNAME" Value="<?php echo $IdUsFIXEDNAME; ?>">	

                 <div class="clearfix"></div>
                 </br>
                 <span style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; ">User Identification .- Fixed Part</span>
                 <span id ="VIdUsFIXED" class="label label-success" style="float:right; font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace;">00000000</span>

                 <div class="clearfix"></div>
                
            
                <div id="CLICK">
                 <div class="formRow">
	                 <label>Date of Birth: </label>
	                 <div class="formRight">
	                 <div class="validate[custom[date],past[2013/01/01]] input-append date" id="dp3" data-date="12-02-2012" data-date-format="dd-mm-yyyy">
		                 <input class="span2" size="16" type="text" value="12-02-2012" id="dp32" name="dp32" readonly="">
		                 <span class="add-on" onclick='return false;'><i class="icon-th"></i></span>
		             </div>
	                 </div>
                 </div>
                </div>
                 
                 <div class="formRow">
                        <label>Gender: </label>
                        <div class="formRight">
                            <select name="gender" id="gender" class="validate[required] span_select" style="width:200px;">
                                <option value="">Select Gender:</option>
                                <option value="0">Female</option> 
                                <option value="1">Male</option>
                            </select>
                        </div>
                 </div>
 
                  <div class="formRow">
                     
	                     <div style="margin-left:0px;">
		                     <p>
			                     <input type="checkbox" id="c2" name="cc" >
			                     <label for="c2"><span></span> Part of a multiple birth ? (twins, triplets, etc.)</label>
			                 </p>
	                     </div>
	                         <div class="clearfix"></div>
             
                        <div id="MULTIPLE" style="margin-left:0px; display:none;">
                   
                        <label>Order of Birth: </label>
                        <div class="formRight">
                            <select name="orderOB" id="orderOB" style="width:200px;">
                                <option value="">Select Order:</option>
                                <option value="1">First</option> 
                                <option value="2">Second</option>
                                <option value="3">Third</option>
                                <option value="4">Fourth</option>
                            </select>
                        </div>
                        </div>
                 </div>
 
                 <div class="clearfix"></div>
                 <hr>

                 <span style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; ">User Identification .- Flexible Part</span>
                 <span id ="VIdUsFIXEDNAME" class="label label-success" style="float:right; font-size:18px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace;">name.surname</span>

                 
                 <div class="formRow">
                        <label>Name: </label>
                        <div class="formRight">
                           <input value="" class="validate[required] span" type="text" name="Vname" id="Vname" style="width:300px;" placeholder="Name">
                        </div>
                 </div>
                 
                 <div class="formRow">
                        <label>Surname: </label>
                        <div class="formRight">
                           <input value="" class="validate[required] span" type="text" name="Vsurname" id="Vsurname" style="width:300px;" placeholder="Surname">
                        </div>
                 </div>
                 
                 </br></br></br>
                        
                  <div class="formRow">
                        <label>Password: </label>
                        <div class="formRight">
                           <!--<input value="" class="validate[required,minSize[6],maxSize[6]] span" type="password" name="minsize" id="minsize">-->
                           <input value="" class="validate[required,minSize[6],maxSize[6]] span" type="password" name="Vpassword" id="Vpassword" style="width:200px;" placeholder="Password">
                 </div>
                 <div class="formRow">
                        <label>Retype Password: </label>
                        <div class="formRight">
                        	<input value="" class="validate[required,equals[Vpassword]] span" type="password" name="password2" id="password2" style="width:200px;" placeholder="Same Password">
                        </div>
                 
                 <div class="clearfix"></div>
                 <hr>
 
                 <span style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; ">Connections</span>
                
                  <div class="formRow">
                        <label>Email: </label>
                        <div class="formRight">
                           <input value="" class="validate[required, custom[email]] span" type="email" name="Vemail" id="Vemail" style="width:300px;" placeholder="email">
                        </div>
                 </div>
                  <div class="formRow">
                        <label>Phone: </label>
                        <div class="formRight">
                           <input value="" type="text" name="Vphone" id="Vphone" style="width:300px;" placeholder="phone">
                        </div>
                 </div>

                 </br></br> </br>
                 <input type="submit" class="btn btn-large btn-primary" value="SAVE" style="width:200px;">
            
                </form>
                 <div class="clear"></div>
          </div>    
        </div>
        <!--Form Validation END-->
 
  
     </div>
     <!--CONTENT MAIN END-->

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
   
   		
   	$('#dp32').live("change", function(){
	     getIdUsFIXED();
	     alert ('Change');
   		});	
 	
   		
    $('#gender').bind("focusout change", function(){
	     getIdUsFIXED();  		
   		});
 
     $('#orderOB').bind("focusout  change", function(){
	     getIdUsFIXED();
   		});
   	     
   	$('#Vname').bind("focusout  change", function(){
	     getIdUsFIXEDNAME();
   		});	

   	$('#Vsurname').bind("focusout  change", function(){
	     getIdUsFIXEDNAME();
   		});	
   		  		  
    $("#c2").click(function(event) {
	    	$("#MULTIPLE").css("display","inherit");
	    	var cosa=chkb($("#c2").is(':checked'));
	    	if (cosa==1){
		    	$("#MULTIPLE").css("display","inherit");
	    	}else{
		    	$("#MULTIPLE").css("display","none");
		    	$("#orderOB").val('0');
	    	}
  	  	     getIdUsFIXED();
  
    });
 
    function getIdUsFIXED(){
    	var fnac = $("#dp32").val();
   		var fnacnum = fnac.substr(6,4)+fnac.substr(3,2)+fnac.substr(0,2);
   		var gender = chkb($("#c2").is(':checked'));
   		
   		var gender = $("#gender").val();
   		var orderOB = $("#orderOB").val();
   		if (gender==0){ gender='0';}
   		if (orderOB==0){ orderOB='0';}
   		
   		var VIdUsFIXED = fnacnum+gender+orderOB;
   		$('#VIdUsFIXED').html(VIdUsFIXED);
   		$('#VIdUsFIXEDINSERT').val(VIdUsFIXED);
   		
    	}
 
     function getIdUsFIXEDNAME(){
    	var vname = $("#Vname").val().toLowerCase().replace(".","").replace(" ","");
   		var vsurname = $("#Vsurname").val().toLowerCase().replace(".","").replace(" ","");
    		
   		var VIdUsFIXEDNAME = vname+'.'+vsurname;
   		$('#VIdUsFIXEDNAME').html(VIdUsFIXEDNAME);
   		$('#VIdUsFIXEDNAMEINSERT').val(VIdUsFIXEDNAME);
    	}

    function chkb(bool){
	    if(bool)
	    	return 1;
	    	return 0;
	   }
	   
 
 
    });
    </script>

  </body>
</html>

