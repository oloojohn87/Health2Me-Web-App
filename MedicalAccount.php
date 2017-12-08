<?php

 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$NombreEnt = 'x';
$PasswordEnt = 'x';

$Acceso = $_GET['Acceso'];
$NombreEnt = $_GET['Nombre'];
$PasswordEnt = $_GET['Password'];
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

$result = $con->prepare("SELECT * FROM doctors where IdMEDEmail=? and IdMEDRESERV=?");
$result->bindValue(1, $NombreEnt, PDO::PARAM_STR);
$result->bindValue(2, $PasswordEnt, PDO::PARAM_STR);
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
  <body>

	<!--Header Start-->
	<div class="header" >
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
       <li><a href=""><i class="icon-group"></i> <span>Account</span></a></li>
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
    	 <li><a href="patients.php?Acceso=23432&Nombre=<?php echo $NombreEnt;?>&Password=<?php echo $PasswordEnt;?>&MEDID=<?php echo $MedID;?>">Patients</a></li>
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
     
     <!--CONTENT MAIN START-->
	</br>  </br>   
	 <span class="label label-info" style="left:0px; margin-left:30px; margin-top:40px; font-size:30px;">Connect to Users</span>

     <div class="content">
	            <!--Tabs Start-->
        <div class="span8 grid-transparent">
          <div id="tab-container">
    		<ul>
    		  <li class="active"><a href="#userscloud" class="t_item1"  data-toggle="tab">Users</a></li>
              <li style=""><a href="#connections" class="t_item2" data-toggle="tab">Connections</a></li>
    		</ul>
    	</div>
        
        <div id="main-container"  class="tab-content shadow" style="height:400px;">
              <div class="tab-pane fade in active tab-overflow-main" id="userscloud">
              <h4>Users available:</h4>
                      <!--Search bar Start-->
             <div class="grid">
          <div class="grid-title">
           <div class="pull-left"><div class="fam-database-lightning" style="margin-right:10px;"></div>Registered Patients Database</div>
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
              
              <div class="grid">
	              <table class="table table-mod" id="TablaMensajes">
                  </table> 
              </div>
              
              
                 <div class="comments">
                 
                 	<!-- #comments post 1-->
                 	<div class="comment">
                     <div class="comment-post">Publication - <a href="#">Creativ photo on Photoshop CS6</a></div>
                     <div class="comment-link"><a href="#">View All</a></div>
                     
                     <div class="comment-info">
                      <div class="comment-avatar"><img src="images/users/1.jpg" alt=""></div>
                      <div class="comment-text"><strong>Alan Cook:</strong> Wow. It's a great article!
                      	<div class="comment-date">June 29 12:56 PM from <a href="#">San Francisco</a></div>
                      </div>
                      <div class="clear"></div>
                     </div>
                     
                     <div class="comment-info">
                      <div class="comment-avatar"><img src="images/users/11.jpg" alt=""></div>
                      <div class="comment-text"><strong>Maria Clark:</strong> Wow. It's a great article!
                      	<div class="comment-date">June 29 12:56 PM from <a href="#">San Francisco</a></div>
                      </div>
                      <div class="clear"></div>
                     </div>
                     
                     <div class="comment-info">
                      <div class="comment-avatar"><img src="images/users/4.jpg" alt=""></div>
                      <div class="comment-text"><strong>Melissa Waller:</strong> Wow. It's a great article!
                      	<div class="comment-date">June 29 12:56 PM from <a href="#">San Francisco</a></div>
                      </div>
                      <div class="clear"></div>
                     </div>
                    
                    <div class="clear"></div>
                    </div>
                    <!-- #comments post 1 end-->
                    
                    <!-- #comments post 1-->
                 	<div class="comment">
                     <div class="comment-post">Publication - <a href="#">Title News #2</a></div>
                     <div class="comment-link"><a href="#">View All</a></div>
                     
                     <div class="comment-info">
                      <div class="comment-avatar"><img src="images/users/6.jpg" alt=""></div>
                      <div class="comment-text"><strong>Alan Cook:</strong> Wow. It's a great article!
                      	<div class="comment-date">June 29 12:56 PM from <a href="#">San Francisco</a></div>
                      </div>
                      <div class="clear"></div>
                     </div>
                     
                     <div class="comment-info">
                      <div class="comment-avatar"><img src="images/users/7.jpg" alt=""></div>
                      <div class="comment-text"><strong>Maria Clark:</strong> Wow. It's a great article!
                      	<div class="comment-date">June 29 12:56 PM from <a href="#">San Francisco</a></div>
                      </div>
                      <div class="clear"></div>
                     </div>
                     
                     <div class="comment-info">
                      <div class="comment-avatar"><img src="images/users/9.jpg" alt=""></div>
                      <div class="comment-text"><strong>Melissa Waller:</strong> Wow. It's a great article!
                      	<div class="comment-date">June 29 12:56 PM from <a href="#">San Francisco</a></div>
                      </div>
                      <div class="clear"></div>
                     </div>
                    
                    </div>
                    <!-- #comments post 1 end-->
                 
                 </div> 
                   
              </div>
          
            
          </div>
        </div>
        <!--Tabs END-->  
	     
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
    
    $(document).ready(function() {
    
	  
   
    /*
 	$('#datatable_1').dataTable( {
		"bProcessing": true,
		"bDestroy": true,
		"bRetrieve": true,
		"sAjaxSource": "getBLOCKS.php"
	} );
    


    $('#datatable_1 tbody').click( function () {
    // Alert the contents of an element in a SPAN in the first TD
    	alert( $('td:eq(0) span', this).html() );
    } );
*/

    
    $("#BotonBusquedaPac").click(function(event) {
    	    var IdUs =156;
    	    var UserInput = $('#SearchUser').val();
    	    var MEDID = $('#MEDID').val();
            var queUrl ='getFullUsersLINK.php?Usuario='+UserInput+'&NReports=10&MEDID='+MEDID;
      	    
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
     	var NameMed = $('#IdMEDName').val();
     	var SurnameMed = $('#IdMEDSurname').val();
    	var From = $('#MEDID').val();
        var FromEmail = $('#IdMEDEmail').val();
        var Subject = 'Request conection';
        var Content = 'Dr. '+NameMed+' '+SurnameMed+' requests conection with user '+To;
    
    	
    	var cadena = '<?php echo $domain;?>/MsgInterno.php?Tipo=0&IDPac=0&To='+To+'&ToEmail=&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0';
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

 

  </body>
</html>

