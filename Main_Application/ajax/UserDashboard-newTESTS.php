<?php
session_start();
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$NombreEnt = $_SESSION['Nombre'];
$PasswordEnt = $_SESSION['Password'];
$MEDID = $_SESSION['MEDID'];
$UserID = $_SESSION['UserID'];
$Acceso = $_SESSION['Acceso'];
$privilege=$_SESSION['Previlege'];
if ($Acceso != '23432')
{
echo "Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Health2me Dashboard</a></h2>";
die;
}

//echo 'USER:'.$UserID;
					// Connect to server and select databse.
//KYLE$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

$result = mysql_query("SELECT * FROM usuarios where Identif='$UserID'");
$count=mysql_num_rows($result);
$row = mysql_fetch_array($result);
$success ='NO';
if($count==1){
	
    $UserID = $row['Identif'];
	$UserEmail= $row['email'];
    $UserName = $row['Name'];
    $UserSurname = $row['Surname'];
    //$UserLogo = $row['ImageLogo'];
    $IdUsFIXED = $row['IdUsFIXED'];
    $IdUsFIXEDNAME = $row['IdUsFIXEDNAME'];
    $privilege=1;
}
else
{
echo "USER DATA INCOMPLETE. No Doctor assigned to this User";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Health2me Dashboard</a></h2>";
die;
}

if (!file_exists('temp/'.$UserID)) 
{
    mkdir('temp/'.$UserID, 0777, true);
	mkdir('temp/'.$UserID.'/Packages_Encrypted', 0777, true);
	mkdir('temp/'.$UserID.'/PackagesTH_Encrypted', 0777, true);
}


$result = mysql_query("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$row = mysql_fetch_array($result);
$_SESSION['decrypt']=$row['pass'];
$_SESSION['isPatient']=1;


?>
<!DOCTYPE html>
<html lang="en" style="background: #F9F9F9;"><head>
    <meta charset="utf-8">
    <title>Inmers - Center Management Console</title>
    <link rel="icon" type="image/ico" href="favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
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
    <!--<link rel="stylesheet" type="text/css" href=href="css/googleAPIFamilyCabin.css">-->
      <script type="text/javascript" src="js/42b6r0yr5470"></script>
	<link rel="stylesheet" href="css/icon/font-awesome.css">
 <!--   <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" href="css/toggle-switch.css">
	
	<script src="js/jquery.min.js"></script>
    
	<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>	
	<script type="text/javascript" src="js/modernizr.2.5.3.min.js"></script>	
	
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lte IE 8]><script type="text/javascript" src="/js/excanvas.min.js"></script><![endif]-->
 
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="/images/icons/favicon.ico">
    	<style>
		.ui-progressbar {
		position: relative;
		}
		.progress-label {
		position: absolute;
		left: 50%;
		top: 4px;
		font-weight: bold;
		text-shadow: 1px 1px 0 #fff;
		}
	</style>
	<style>
	#overlay {
	  background-color: none;
	  position: auto;
	  top: 0; right: 0; bottom: 0; left: 0;
	  opacity: 1.0; /* also -moz-opacity, etc. */
	  
    }
	#messagecontent {
	  white-space: pre-wrap;   
	}
	</style>
	  <style>
		#progressbar .ui-progressbar-value {
		background-color: #ccc;
		}
	  </style>

	 <style type="text/css"> 
		#container {
		margin: 0 auto;
		width: 665px	
	}
	
	.thumb {
		display:block;
		width:200px;
		height:140px;
		position:relative;
		margin-bottom:20px;
		margin-right:20px;
		float:left;
	}
	
		.thumb-wrapper {
			display:block;
			width:100%;
			height:100%;
		}
	
		.thumb p {
			width:100%;
			height:100%;
			position:absolute;
			display:block;			
					
		}
		
		.thumb .thumb-detail {
			display:block;
			width:100%;
			height:100%;
			position:absolute;			
			background:#fff;
			font-family:arial;
			font-weight:bold;
			font-size:16px;			
		}
		
		.thumb .thumb-detail a {
			display:block;
			width:100%;
			height:100%;
			text-transform:uppercase;
			font-weight:bold;	
			color:#333;
			text-decoration:none;		
			font-family: 'Open Sans', sans-serif;
			letter-spacing:-1px;
			padding:10px;	
			font-size:18px;
		}		
	
	/*
	* Without CSS3
	*/
	.thumb.scroll {
		overflow: hidden;
	}	
	
		.thumb.scroll .thumb-detail {
			bottom:-280px;
		}
	
	
	/*
	* CSS3 Flip
	*/	
	.thumb.flip {
		-webkit-perspective:800px;		
		   -moz-perspective:800px;
		    -ms-perspective:800px;		   		
		     -o-perspective:800px;
  		        perspective:800px;
	}
	
		.thumb.flip .thumb-wrapper {
			-webkit-transition: -webkit-transform 1s;
			   -moz-transition: -moz-transform 1s;
			    -ms-transition: -moz-transform 1s;
			     -o-transition: -moz-transform 1s;
			        transition: -moz-transform 1s;
			-webkit-transform-style: preserve-3d;
			   -moz-transform-style: preserve-3d;			
			    -ms-transform-style: preserve-3d;			
			     -o-transform-style: preserve-3d;			
					  transform-style: preserve-3d;			
		}
		
		.thumb.flip .thumb-detail {
			-webkit-transform: rotateY(-180deg);
			   -moz-transform: rotateY(-180deg);
			    -ms-transform: rotateY(-180deg);
			     -o-transform: rotateY(-180deg);
			        transform: rotateY(-180deg);			   			
		}
		
		.thumb.flip p,
		.thumb.flip .thumb-detail {
			-webkit-backface-visibility: hidden;
			   -moz-backface-visibility: hidden;
			    -ms-backface-visibility: hidden;
			     -o-backface-visibility: hidden;
			        backface-visibility: hidden;
		}
		
		
		.thumb.flip .flipIt {
			-webkit-transform: rotateY(-180deg);
			   -moz-transform: rotateY(-180deg);			
			    -ms-transform: rotateY(-180deg);			
			     -o-transform: rotateY(-180deg);			
			        transform: rotateY(-180deg);			
		}
	 
	 </style>
	  
	  
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
<script type="text/javascript">
	$(function () {
	

		if ($('html').hasClass('csstransforms3d')) {	
		
			$('.thumb').removeClass('scroll').addClass('flip');		
			$('.thumb.flip').hover(
				function () {
					$(this).find('.thumb-wrapper').addClass('flipIt');
				},
				function () {
					$(this).find('.thumb-wrapper').removeClass('flipIt');			
				}
			);
			
		} else {

			$('.thumb').hover(
				function () {
					$(this).find('.thumb-detail').stop().animate({bottom:0}, 500, 'easeOutCubic');
				},
				function () {
					$(this).find('.thumb-detail').stop().animate({bottom: ($(this).height() * -1) }, 500, 'easeOutCubic');			
				}
			);

		}
	
	});
	</script>
  </head>

  <body style="background: #F9F9F9;">

<input type="hidden" id="NombreEnt" value="<?php echo $NombreEnt; ?>">
<input type="hidden" id="PasswordEnt" value="<?php echo $PasswordEnt; ?>">
<input type="hidden" id="UserHidden">

	<!--Header Start-->
	<div class="header" >
			<a href="index.html" class="logo"><h1>Health2me</h1></a>
			<div class="pull-right">
                      
           <!--Button User Start-->
		   <div class="btn-group pull-right" >
           
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
              <span class="name-user"><strong>Welcome</strong> <?php echo $UserName.' '.$UserSurname; ?></span> 
             <?php 
             $hash = md5( strtolower( trim( $UserEmail ) ) );
             $avat = 'identicon.php?size=29&hash='.$hash;
			?>	
              <span class="avatar" style="background-color:WHITE;"><img src="<?php echo $avat; ?>" alt="" ></span> 
              <span class="caret"></span>
            </a>
            <div class="dropdown-menu" id="prof_dropdown">
            <div class="item_m"><span class="caret"></span></div>
            <ul class="clear_ul" >
			<li>
			 <?php if ($privilege==1)
					echo '<a href="dashboard.php">';
				   else if($privilege==2)
					echo '<a href="patients.php">';
			 ?>
			<i class="icon-globe"></i> Home</a></li>
              
              <li><a href="medicalConfiguration.php"><i class="icon-cog"></i> Settings</a></li>
              <li><a href="logout.php"><i class="icon-off"></i> Sign Out</a></li>
            </ul>
            </div>
          </div>
          <!--Button User END-->  
          
          </div>
    </div>
    <!--Header END-->
 
   	 
    <!--Content Start-->
	<div id="content" style="background: #F9F9F9; padding-left:0px;">
    
    	    
	     
     
     <!--CONTENT MAIN START-->
     <div class="content">
		<div class="grid" class="grid span4" style="width:1000px; height:900px; margin: 0 auto; margin-top:30px; padding-top:30px;">
			
			<div id="container">
				<div class="thumb scroll" style="width:300px;height:160px">
					<div class="thumb-wrapper">
						<!--<img src="" alt="">-->
						<p id="ConnectSCROLL" style="overflow:hidden;height:100%;width:100%;background-color:#1d92d7;position:absolute">		             		 	
								<span style="float:left; width:45%; display:table; text-align:center;">
									<span style="display:table-cell; vertical-align:middle;">
										<p id="Connect2" style="color:white; font-size:75px;margin-top:65px; font-weight:bold; text-align:left; margin-left:5px; padding-top:8px;">
										60%
										</p>
									</span>	
								</span>
								<span style="float:right;width:55%; display:table; text-align:center;">
									<span style="display:table-cell; vertical-align:middle;">
										<p style="color:white; font-weight:bold;margin-top:55px; text-align:left; line-height:100%; width:90%; padding-top:8px;">
											<span id="Connect2T1" style="width:200px; font-size:30px; text-align:right; margin-right:5px;margin-left:25px">Summary</span><br/><br/>
											<span id="Connect2T2" style="width:100%; font-size:25px; font-weight:bold; text-align:left; margin-left:25px">Completed</span>
										</p>
									</span>
								</span>
						</p>
						<!--</img>		-->
						
						<div class="thumb-detail" style="background-color:#1d92d7;">
							<a id="ConsultDoctor"  class="btn" title="Consult Doctor to Edit Demographics" style="text-align:center; padding:5px; width:70px; height:80px; color:#22aeff; float:right; margin-right: 40px;margin-top:30px; margin-bottom: -20px;cursor:pointer;">
								<span> <i class="icon-user-md icon-3x"></i>Doctor</span>
							</a>
							
							<a id="EditDemographics" class="btn" title="Edit Demographics" style="text-align:center; padding:5px; width:70px; height:80px; color:#22aeff; float:right; margin-right: 60px;margin-top:30px; margin-bottom: -20px;cursor:pointer;">
								<span><i class="icon-edit icon-3x"></i>Edit</span>
							</a>
						</div>
					</div>
				</div>		
				
				<div class="thumb scroll" style="width:300px;height:160px">
					<div class="thumb-wrapper">
						
						<p id="ConnectSCROLL" style="overflow:hidden;height:100%;width:100%;background-color:#54bc00;position:absolute">		             		 	
							<span style="float:left; width:45%; display:table; text-align:center;">
								<span style="float:left; width:45%; display:table; text-align:center;">
									<span style="display:table-cell; vertical-align:middle;">
										<p id="Connect21" style="color:white; font-size:75px;margin-top:65px; font-weight:bold; text-align:left; margin-left:35px; padding-top:8px;">
										14
										</p>
									</span>	
								</span>
								<span style="float:right;width:55%; display:table; text-align:center;">
									<span style="display:table-cell; vertical-align:middle;">
										<p style="color:white; font-weight:bold;margin-top:55px; text-align:left; line-height:100%; width:90%; padding-top:8px;">
											<span id="Connect2T11" style="width:200px; font-size:30px; text-align:right; margin-right:5px;margin-left:25px">Reports</span><br/><br/>
											<span id="Connect2T21" style="width:100%; font-size:25px; font-weight:bold; text-align:left; margin-left:25px">Stored</span>
										</p>
									</span>
								</span>
						</p>
						
						<div class="thumb-detail">
							<a href="#">
								I dont know what to put here
							</a>
						</div>
					</div>
				</div>
				
			</div>
			
 		     
		</div>
     </div>
     <!--CONTENT MAIN END-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-ui.min.js"></script>

    <!-- Libraries for notifications -->
    <script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
	<script src="realtime-notifications/pusher.min.js"></script>
	<script src="realtime-notifications/PusherNotifier.js"></script>
	<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
	<!--<script src="imageLens/jquery.js" type="text/javascript"></script>-->
	<script src="imageLens/jquery.imageLens.js" type="text/javascript"></script>
    <script>
		/*$(function() {
	    var pusher = new Pusher('d869a07d8f17a76448ed');
	    var channel_name=$('#MEDID').val();
		var channel = pusher.subscribe(channel_name);
		var notifier=new PusherNotifier(channel);
		
	  });*/
    </script>
    <!-- Libraries for notifications -->

    <script src="TypeWatch/jquery.typewatch.js"></script>
    <script type="text/javascript" >
    
	var timeoutTime = 18000000;
	//var timeoutTime = 300000;  //5minutes
	var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);


	var active_session_timer = 60000; //1minute
	var sessionTimer = setTimeout(inform_about_session, active_session_timer);

    var reportcheck = new Array();
   
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
	
	setInterval(function() {
    		//$('#newinbox').trigger('click');
      }, 10000);

    function displaynotification(status,message){
  
  var gritterOptions = {
			   title: status,
			   text: message,
			   image:'images/Icono_H2M.png',
			   sticky: false,
			   time: '3000'
			  };
	$.gritter.add(gritterOptions);
	
  }

  $("#EditDemographics").live('click',function(){
	//alert('clicked');
	window.location.replace("medicalPassport.php");
  });
  
  $("#ConsultDoctor").live('click',function(){
	alert('clicked doctor');
  });
  
  
  
  
  function fun()
  {
	alert('clicked');
  }
  
  
  
  
  
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

