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
echo "<h2><a href='".$domain"'>Return to Inmers HomePage</a></h2>";
die;
}
				
					// Connect to server and select databse.
//KYLE$link = mysql_connect("$host", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

/*
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
	
}
else
{
echo "MEDICAL USER NOT VALID. Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='<?php $domain?>'>Return to Inmers HomePage</a></h2>";
die;
}
*/


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

  <body>


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
              <span class="name-user"><strong>Welcome</strong>, Dr. Michael rl</span> 
              <span class="avatar"><img src="images/doctors/michaelrlICON.jpg" alt="" ></span> 
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
	<div id="sidebar">
    
      <ul class="menu-sidebar">
       <li><a href=""><i class="icon-home"></i> <span>Home</span></a></li>
       <li><a href="MedicalAccount.php?Acceso=23432&Nombre=<?php echo $NombreEnt;?>&Password=<?php echo $PasswordEnt;?>&MEDID=<?php echo $MedID;?>"><i class="icon-group"></i> <span>Config</span></a></li>
       <!--
       <li><a href="forms.html"><i class="icon-edit"></i> <span>Forms</span></a></li>
       <li><a href="widgets.html" class="active"><i class="icon-briefcase"></i> <span>Others</span></a></li>
       <li><a href="error_404.html"><i class="icon-warning-sign"></i> <span>Errors</span></a></li>
       <li><a href="messages.html"><i class="icon-gift"></i> <span>Bonus</span></a></li>
       -->
      </ul>
     
    
    </div>
	<!--    Sidebar END-->

    <!--Content Start-->
	<div id="content">
    
 	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     
     <!--
     <ul class="menu-drop">
     			<li><a href="#"><i class="icon-chevron-down"></i></a>
               		<ul>
                        <li><a href="dashboard.html">Dashboard</a></li>
                        <li><a href="charts.html">Charts</a></li>
                        <li><a href="gallery.html">Image Gallery</a></li>
                        <li><a href="calendar.html">Calendar</a></li>
                        <li><a href="ui.html">UI elements</a></li>
                        <li><a href="icons.html">Icons</a></li>
                        <li><a href="buttons.html">Buttons</a></li>
                        <li><a href="grids.html">Grids</a></li>
                        <li><a href="forms.html">Inputs & elements</a></li>
                        <li><a href="form_validation.html">Validation</a></li>
                        <li><a href="wizard.html">Wizard</a></li>
                        <li><a href="file_manager.html">File Manager</a></li>
                        <li><a href="conversation.html">Conversation</a></li>
                        <li><a href="widgets.html">Widgets</a></li>
                        <li><a href="typography.html">Typography</a></li>
                        <li><a href="tables.html">Tables</a></li>
                        <li><a href="datatables.html">DataTables</a></li>
                        <li><a href="error_404.html">Error 404</a></li>
                        <li><a href="error_500.html">Error 500</a></li>
                        <li><a href="error_503.html">Error 503</a></li>
                        <li><a href="error_offline.html">Error Offline</a></li>
                        <li><a href="messages.html">Messages</a></li>
                        <li><a href="search.html">Search</a></li>
                        <li><a href="profile.html">Profile</a></li>
                        <li><a href="stream.html">Stream</a></li>
                        <li><a href="invoice.html">Invoice</a></li>
                    </ul>  
                </li>
     </ul>
     -->
     <ul class="menu-speedbar">
         <li><a href="dashboard.php?Nombre=<?php echo $NombreEnt;?>&Password=<?php echo $PasswordEnt;?>">Dashboard</a></li>
    	 <li><a href="patients.php?Nombre=<?php echo $NombreEnt;?>&Password=<?php echo $PasswordEnt;?>"  class="act_link">Patients</a></li>
         <li><a href="">Doctors</a></li>
         <li><a href="">Centers</a></li>
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
     // Meter clases en un Array
     $Clase[0]='Episode';
     $Clase[1]='Check or Preventive';
     $Clase[2]='Isolated Report';
     $Clase[3]='Drug Data';
     
//BLOCKSLIFEPIN     $sql="SELECT * FROM blocks where IdUsu ='$IdUsu'ORDER by Fecha DESC";
     $sql="SELECT * FROM lifepin where IdUsu ='$IdUsu' ORDER by Fecha DESC";
     $q = mysql_query($sql);
     
     ?>
     
     <!--CONTENT MAIN START-->
     <div class="content">
     	<h1><?php echo $Name.' '.$Surname; ?></h1>
        <!--NOTES Start-->
        <h3>Health History Timeline</h3>
        <div class="scroll-pane horizontal-only notes" style="height: 420px;">
        <div style="width: 2420px; height: 300px;">
<?php
while($row=mysql_fetch_assoc($q)){
?>
          <div class="note" id="<?php echo $row['IdPin']; ?>">
          
            <div class="THUMB" id="<?php echo $row['RawImage']; ?>" style="width:280px; height:215px; overflow: hidden"><img src="http://www.monimed.com/eMapLife/PinImageSetTH/<?php echo $row['RawImage']; ?>" alt=""></div>
            <div class="note-category cat-green" id="<?php echo $Tipo[$row['Tipo']];?>"><?php echo $Tipo[$row['Tipo']];?></div>
            <div class="note-title" id="<?php echo $Clase[$row['EvRuPunt']];?>"><?php echo $Clase[$row['EvRuPunt']];?></div>
            <div class="note-more" id="<?php 
            
            $FechaNum = strtotime($row['Fecha']);
            $FechaBien = date ("D M j Y",$FechaNum);
            echo $FechaBien;
            
            
            ?>"><a href="#"><?php 
            
            $FechaNum = strtotime($row['Fecha']);
            $FechaBien = date ("D M j Y",$FechaNum);
            echo $FechaBien;
            
            
            ?></a></div>
            <div class="note-info"><span><?php echo substr($row['RawImage'],1,15);?></span></div>
          </div>
<?php
};
?>
        </div>
        </div>  
        <!--NOTES END-->


     <div class="grid span3" style="width:50%;">
          <div class="grid-title a" style="height:60px;">
           <div class="pull-left a" style="font-size:24px;"></div>
           <div class="pull-right">
               <div class="grid-title-label"><span class="label label-warning"></span></div>
           </div>
          <div class="clear"></div>  
           <div>
           <span class="ClClas" style="font-size:18px; color:grey;"></span>
           </div>
           <div class="clear"></div>   
          </div>
          
          <div class="grid-content">
<?php
//BLOCKSLIFEPIN $sql="SELECT * FROM blocks where IdUsu ='$IdUsu'";
$sql="SELECT * FROM lifepin where IdUsu ='$IdUsu'";
$q = mysql_query($sql);
$row=mysql_fetch_assoc($q);
?>
 
             <img id="ImagenAmp" src="">
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
    
    $('.note').click( function () {
	    
	    var queId = $(this).attr("id");
	    var queImg = $(".THUMB", this).attr("id");
	    var queTip = $(".cat-green", this).attr("id");
	    var queClas = $(".note-title", this).attr("id");
	    var queFecha = $(".note-more", this).attr("id");
	    
	    var conten = '<img src="http://www.monimed.com/eMapLife/PinImageSet/'+queImg+'" alt="">';
	    //alert (queClas);
	    $('div.grid-content').html(conten);
	    $('div.pull-left.a').html(queTip);
	    $('.ClClas').html(queClas);
	    $('div.grid-title-label').html('<span class="label label-warning">'+queFecha+'</span>');
	    });
   
 
	}); 		
	</script>

  </body>
</html>
