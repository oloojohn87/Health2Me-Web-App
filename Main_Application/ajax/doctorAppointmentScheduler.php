<?php
/*KYLE
session_start();
 require("environment_detail.php");
 require_once("displayExitClass.php");
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
$exit_display = new displayExitClass();

$exit_display->displayFunction(1);
die;
}

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

$result = mysql_query("SELECT * FROM usuarios where Identif='$UserID'");
$count=mysql_num_rows($result);
$row = mysql_fetch_array($result);
$success ='NO';
$user_phone = '';
if($count==1){
	
    $UserID = $row['Identif'];
	$UserEmail= $row['email'];
    $UserName = $row['Name'];
    $UserSurname = $row['Surname'];
    //$UserLogo = $row['ImageLogo'];
    $IdUsFIXED = $row['IdUsFIXED'];
    $IdUsFIXEDNAME = $row['IdUsFIXEDNAME'];
    $user_phone = $row['telefono'];
    $privilege=1;
}
else
{
$exit_display = new displayExitClass();

$exit_display->displayFunction(3);
die;
}

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
    <!--<link rel="stylesheet" href="css/fullcalendar.css" media="screen"  />-->
    <link rel="stylesheet" href="css/chosen.css" media="screen"  />
    <!--<link rel="stylesheet" href="css/datepicker.css" >-->
    <link rel="stylesheet" href="css/zebra-datepicker-bootstrap.css" type="text/css">
    <link rel="stylesheet" href="css/colorpicker.css">
    <link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css" />
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
    
    <style>
    .ui-datepicker {
        padding: 0.1em 0.1em 0;
        width: 11em;
    }
    
    .ui-widget {
        font-family: Helvetica,Arial,sans-serif;
        font-size: 14px;
    }
    
    .ui-datepicker th {
        border: 0 none;
        font-weight: normal;
        padding: 0.2em 0.1em;
        text-align: center;
    }
    
    .ui-datepicker th span {
        font-size: 11px;
    }
    
    .ui-datepicker td span, .ui-datepicker td a {
        padding: 0.1em;
    }
    
    .ui-datepicker td {
        padding: 0.9px;
    }
    
    .ui-datepicker .ui-state-highlight {
        height: 12px;
        margin-bottom: 0;
    }
    
    .ui-state-default, .ui-widget-content .ui-state-default, 
    .ui-widget-header .ui-state-default {
        font-size: 10px;
        font-weight: normal;
        text-align: center;
    }
    
    .ui-datepicker .ui-datepicker-title {
        line-height: 13px;
    }
    
    .ui-datepicker .ui-datepicker-title span {
        font-size: 11px;
    }
    
    .ui-datepicker .ui-datepicker-prev span, 
    .ui-datepicker .ui-datepicker-next span {
        margin-left: -8px;
        margin-top: -8px;
    }
    
    .ui-datepicker .ui-datepicker-prev, 
    .ui-datepicker .ui-datepicker-next {
        height: 15px;
        top: 1px;
        width: 15px;
    }
    
    .ui-datepicker-next-hover .ui-icon {
        height: 16px;
        width: 16px;
    }
    </style>
	
	<script src="js/jquery.min.js"></script>
    
	<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>	
	<script type="text/javascript" src="js/modernizr.2.5.3.min.js"></script>	
	
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->

 
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/icons/favicon.ico">
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
	  /*KYLE
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
	*//*KYLE
	.thumb.scroll {
		overflow: hidden;
	}	
	
		.thumb.scroll .thumb-detail {
			bottom:-280px;
		}
	
	
	/*
	* CSS3 Flip
	*/	/*KYLE
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
<input type="hidden" id="pat_id" value="<?php echo $UserID; ?>">
<input type="hidden" id="pat_phone" value="<?php echo $user_phone; ?>">
<input type="hidden" id="PasswordEnt" value="<?php echo $PasswordEnt; ?>">
<input type="hidden" id="UserHidden">
      
      <style>
        .blueButton{
             width: 200px; 
             margin-left: 5px;
             height: 30px;
             text-align: center;
             background-color: #3d94f6;
             color: #FFFFFF;
             border-style: solid; 
             border-width: 1px; 
             border-color: #3d94f6; 
             margin-bottom: 10px;
             border-radius:4px;
             float: left;
        }
        .blueButton:disabled{
             background-color: #B0E6FF;
             border-color: #B0E6FF; 
             cursor: default;
        }
      </style>
      <div id="modalContents" style="display:none; text-align:center; padding:20px;" title="How would you like to connect?">
          <div style="width: 500px; height: 30px; margin-left: auto; margin-right: auto; margin-top: 20px; margin-bottom: -20px;">
              <button class="blueButton" id="video_call_button">
                Video Call
              </button>
              <button class="blueButton" style="margin-left: 80px;" id="phone_call_button" disabled>
                Phone Call
              </button>
          </div>
    </div>
      <div id="modalContents2" style="display:none; text-align:center; padding:20px;" title="What type of appointment would you like?">
          <div style="width: 500px; height: 30px; margin-left: auto; margin-right: auto; margin-top: 20px; margin-bottom: -20px;">
              <button class="blueButton" id="video_call_button_app">
                Video Call
              </button>
              <button class="blueButton" style="margin-left: 80px;" id="phone_call_button_app" disabled>
                Phone Call
              </button>
          </div>
    </div>

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
         <style>
            .doctorList{
                 width: 550px; 
                 margin-left: 7px;
                 height: 30px; 
                 border-style: solid; 
                 border-width: 1px; 
                 border-color: #E2E2E2; 
                 margin-bottom: 10px;
                 border-radius: 4px;
                 float: left;
                 overflow: hidden;
            }
             .doctorScheduleButton{
                 width: 200px; 
                 margin-left: 5px;
                 height: 30px;
                 text-align: center;
                 background-color: #3d94f6;
                 color: #FFFFFF;
                 border-style: solid; 
                 border-width: 1px; 
                 border-color: #3d94f6; 
                 margin-bottom: 10px;
                 border-radius:4px;
                 float: left;
            }
             .scheduleButton{
                 width: 130px; 
                 margin-left: 5px;
                 height: 30px;
                 text-align: center;
                 background-color: #3d94f6;
                 color: #FFFFFF;
                 border-style: solid; 
                 border-width: 1px; 
                 border-color: #3d94f6; 
                 margin-bottom: 10px;
                 border-radius:4px;
                 float: left;
            }
             .cancelButton{
                 width: 130px; 
                 margin-left: 5px;
                 height: 30px;
                 text-align: center;
                 background-color: #F67A7A;
                 color: #FFFFFF;
                 border-style: solid; 
                 border-width: 1px; 
                 border-color: #F67A7A; 
                 margin-bottom: 10px;
                 border-radius:4px;
                 float: left;
            }
              .menuButton{
                 width: 100px; 
                 height: 25px;
                 text-align: center;
                 background-color: #EAEAEA;
                 color: #000000;
                 border-style: solid; 
                 border-width: 1px; 
                 border-color: #E3E3E3;
                 margin-left: -5px;
                 color: #858585;
            }
             .doctorCallButton{
                 width: 100px; 
                 margin-left: 5px;
                 height: 30px;
                 text-align: center;
                 background-color: #3d94f6;
                 color: #FFFFFF;
                 border-style: solid; 
                 border-width: 1px; 
                 border-color: #3d94f6; 
                 margin-bottom: 10px;
                 border-radius:4px;
                 float: left;
            }
             .doctorCallButton:disabled{
                 background-color: #B0E6FF;
                 border-color: #B0E6FF; 
                 cursor: default;
            }
             .scheduleButton:disabled{
                 background-color: #B0E6FF;
                 border-color: #B0E6FF; 
                 cursor: default;
            }
             .page_button{
                 background-color: #3d94f6; 
                 border: 0px solid black;
                 margin-left: 7px;
                 margin-right: 7px;
                 border-radius: 10px;
                 padding: 5px;
                 padding-bottom: 8px;
                 color: #FFF;
                 width: 20px;
                 height: 20px;
             }
             .page_button:disabled{
                 background-color: #B0E6FF; 
                 border: 0px solid black;
             }
            
            
         </style>
		<div class="grid" class="grid span4" style="width:1000px; height:630px; margin: 0 auto; margin-top:30px;">
            <div style="width: 430px; float: right; margin-right: 130px; margin-top: 10px; margin-bottom: -10px;">
                <div class="controls" style="float: left; width: 280px;">
                    <div class="input-append">
                        <input class="span7" id="search_bar" style="width: 230px;" size="16" type="text"><button class="btn" type="button" id="search_bar_button">Search</button>
                    </div>
                </div>
                <div style="float: left; width:150px;">
                    <label class="checkbox toggle candy" onclick="" style="width:150px; background-color: #2d3035;">
                        <input type="checkbox" id="online_toggle" name="CRows" checked="checked"  />
                        <p >
                            <span>All</span>
                            <span>Online</span>
                        </p>
            
                        <a class="slide-button" style="background-color: #38a3d4;" ></a>
                    </label>
                </div>
                
            </div>
			<div style="margin-left: 35px; margin-bottom: -25px; margin-top: 15px;">
                <button class="menuButton" id="name_button">
                   Name
                    <i class="icon-arrow-up" style="margin-left: 3px;"></i>
                </button>
                <button class="menuButton" style="border-left:1px solid #D6D6D6;" id="rating_button">
                   Rating
                    <i class="" style="margin-left: 3px;"></i>
                </button>
                <button class="menuButton" style="border-left:1px solid #D6D6D6;" id="rate_button">
                   Price Rate
                    <i class="" style="margin-left: 3px;"></i>
                </button>
            </div>
            <div  id="Doctors" class="grid" class="grid span4" style="height:520px; padding:20px;width:90%;display:block;margin-left: auto; margin-right: auto; overflow-y: scroll; overflow-x: hidden;">
				<div id="doctors_list" style="display:block; vertical-align:top; width: 100%; height: 252px;">
                    
                    
                </div>	
                
                
			</div>
            
            
            
            <div  id="Schedule" class="grid" class="grid span4" style="height:200px; padding:20px;width:90%;display:table;margin-left: auto; margin-right: auto; margin-top: 15px; display: none;">
                <style>
                    .timeCellIndicator{
                        border-style: solid; 
                        border-width: 1px;
                        border-color: #BABABA;
                        height: 15px; 
                        width: 36px;
                    }
                    .timeCellIndicatorOff{
                        height: 15px; 
                        width: 9px;
                        margin-top: -1px;
                        float: left;
                    }
                    .timeCellIndicatorOn{
                        background-color: #3d94f6;
                        height: 15px;
                        width: 9px;
                        float: left;
                    }
                    .timeCellIndicatorMarked{
                        background-color: #A5A5A5;
                        border-style: solid; 
                        border-width: 1px;
                        border-color: #B2B2B2;
                        border-right-color: #FFFFFF;
                        height: 15px;
                        margin-top: -1px;
                        width: 36px;
                    }
                    .timeLabel{
                        color: #CACACA; 
                        font-size: 10px; 
                        margin-left: -10px;
                    }
                    .timeCell{
                        height: 40px; 
                        width: 36px; 
                        float: left;
                        margin-left: 1px;
                    }
                </style>
                
                <h2 style="font-size: 16px; color: #3d94f6; margin: 2px; padding: 0px; margin-top: -15px;">Available Times</h2>
                <div class="timeCell" id="tc1" ><div class="timeCellIndicator" style="border-top-left-radius: 3px; border-bottom-left-radius: 3px; -webkit-border-top-left-radius: 3px; -moz-border-top-left-radius: 3px;  -webkit-border-bottom-left-radius: 3px; -moz-border-bottom-left-radius: 3px;"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff" ></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">12 am</span></div>
                <div class="timeCell" id="tc2"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">1 am</span></div>
                <div class="timeCell" id="tc3"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">2 am</span></div>
                <div class="timeCell" id="tc4"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">3 am</span></div>
                <div class="timeCell" id="tc5"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">4 am</span></div>
                <div class="timeCell" id="tc6"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">5 am</span></div>
                <div class="timeCell" id="tc7"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">6 am</span></div>
                <div class="timeCell" id="tc8"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">7 am</span></div>
                <div class="timeCell" id="tc9"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">8 am</span></div>
                <div class="timeCell" id="tc10"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">9 am</span></div>
                <div class="timeCell" id="tc11"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">10 am</span></div>
                <div class="timeCell" id="tc12"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">11 am</span></div>
                <div class="timeCell" id="tc13"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">12 pm</span></div>
                <div class="timeCell" id="tc14"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">1 pm</span></div>
                <div class="timeCell" id="tc15"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">2 pm</span></div>
                <div class="timeCell" id="tc16"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">3 pm</span></div>
                <div class="timeCell" id="tc17"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">4 pm</span></div>
                <div class="timeCell" id="tc18"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">5 pm</span></div>
                <div class="timeCell" id="tc19"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">6 pm</span></div>
                <div class="timeCell" id="tc20"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">7 pm</span></div>
                <div class="timeCell" id="tc21"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">8 pm</span></div>
                <div class="timeCell" id="tc22"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">9 pm</span></div>
                <div class="timeCell" id="tc23"><div class="timeCellIndicator"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div></div><span class="timeLabel">10 pm</span></div>
                <div class="timeCell" id="tc24"><div class="timeCellIndicator" style="border-top-right-radius: 3px; border-bottom-right-radius: 3px; -webkit-border-top-right-radius: 3px; -moz-border-top-right-radius: 3px;  -webkit-border-bottom-right-radius: 3px; -moz-border-bottom-right-radius: 3px;"><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff"></div><div class="timeCellIndicatorOff" ></div></div><span class="timeLabel">11 pm</span></div>
                
                <div style="width: 100%; height: 100px;">
                    <span style="color: #B3B3B3; font-size: 14px; float: right; margin-top: 20px;">Select Timezone:   
                       <select name="DropDownTimezone" class="timezonepicker" id="timezone_picker">
                          <option value="-12.0">(GMT -12:00) Eniwetok, Kwajalein</option>
                          <option value="-11.0">(GMT -11:00) Midway Island, Samoa</option>
                          <option value="-10.0">(GMT -10:00) Hawaii</option>
                          <option value="-9.0">(GMT -9:00) Alaska</option>
                          <option value="-8.0">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
                          <option value="-7.0">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
                          <option value="-6.0">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
                          <option value="-5.0" selected>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
                          <option value="-4.0">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
                          <option value="-3.5">(GMT -3:30) Newfoundland</option>
                          <option value="-3.0">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
                          <option value="-2.0">(GMT -2:00) Mid-Atlantic</option>
                          <option value="-1.0">(GMT -1:00 hour) Azores, Cape Verde Islands</option>
                          <option value="0.0">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
                          <option value="1.0">(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
                          <option value="2.0">(GMT +2:00) Kaliningrad, South Africa</option>
                          <option value="3.0">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
                          <option value="3.5">(GMT +3:30) Tehran</option>
                          <option value="4.0">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
                          <option value="4.5">(GMT +4:30) Kabul</option>
                          <option value="5.0">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
                          <option value="5.5">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
                          <option value="5.75">(GMT +5:45) Kathmandu</option>
                          <option value="6.0">(GMT +6:00) Almaty, Dhaka, Colombo</option>
                          <option value="7.0">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
                          <option value="8.0">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
                          <option value="9.0">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
                          <option value="9.5">(GMT +9:30) Adelaide, Darwin</option>
                          <option value="10.0">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
                          <option value="11.0">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
                          <option value="12.0">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
                        </select>
                    </span>
                    <div style="width: 300px; height: 100px;">
                        <span style="color: #B3B3B3; font-size: 14px; margin-top: 20px; float: left;">Select Date:</span><input type="text" style="padding: 3px; background-color: #FFFFFF; width: 140px; margin-top: 20px; margin-left: 7px; float: right;" size="10" id="schedule_date" class="datepicker" />
                    </div>
                    
                    
                    <style>
                        select.timezonepicker {
                           width: 400px;
                           padding: 3px;
                           font-size: 14px;
                           color: #B3B3B3;
                           line-height: 1;
                           border: 1;
                           border-radius: 4;
                           height: 24px;
                            background: url(images/dropdown_arrow.png) no-repeat right #FFF;
                            -webkit-appearance: none;
                            margin-left: 5px;
                            margin-right: 15px;
                        }
                    </style>
                    
                </div>
                <div style="width: 100%; height: 100px;">
                    
                    <style>
                    .segmented-control {
                        width: 410px;
                        height: 25px;
                        padding: 1px;
                        margin-top: -25px;
                        margin-left: 115px;
                        margin-right: 7px;
                    }
                    
                    .segmented-control li {
                        background-color: #3d94f6;
                        float: left;
                        border-left-style: solid;
                        border-left-color: #7AAFF6;
                        border-left-width: 1px;
                    }
                    
                    .segmented-control a {
                        color: #fff;
                        display: block;
                        width: 100px;
                        height: 25px;
                    }
                    .segmented-control li:first-child {
                        border-left-width: 0px;
                    }
                    .segmented-control a {
                        color: #fff;
                        font-size: 12px;
                        line-height: 24px;
                        text-align: center;
                        text-decoration: none;
                        display: block;
                        width: 100px;
                        height: 25px;
                    }
                    .segmented-control li:active {
                        background-color: #3d94f6;
                    }
                    
                    .segmented-control li:active a {
                        text-shadow: 0 1px 0 rgba(255, 255, 255, .1);
                        position: relative;
                        top: 1px;
                    }
                    .segmented-control li:target {
                    
                        background-color: #7AAFF6;
                    }
                    .segmented-control li:target a {color: #fff; text-shadow: 0 1px 0 #BBB;}
                    .segmented-control li:target:active {
                        background-color: #7AAFF6;
                            
                    }
                    </style>
                    
                    <span style="color: #B3B3B3; font-size: 14px; float: right; margin-top: 5px;">Select Duration:
                        <input type="hidden" id="current_duration" value="15" />
                        <ul class="segmented-control">
                            <li value="15" id="15" style="list-style-type: none; border-top-left-radius: 4px; border-bottom-left-radius: 4px;"><a href="#15" id="15_l">15 Min</a></li>
                            <li value="30" id="30" style="list-style-type: none;"><a href="#30" id="30_l">30 Min</a></li>
                            <li value="45" id="45" style="list-style-type: none;"><a href="#45" id="45_l">45 Min</a></li>
                            <li value="60" id="60" style="list-style-type: none; border-top-right-radius: 4px; border-bottom-right-radius: 4px;"><a href="#60" id="60_l">1 Hr</a></li>
                        </ul>
                    </span>
                    <div style="width: 300px; height: 30px;">
                        <span style="color: #B3B3B3; font-size: 14px; float: left; margin-top: 5px;">Select Start Time:</span> <input id="start_time" value="" type="text" style="width: 135px; float: right;" />
                    </div>
                    <div style="width: 280px; height: 30px; margin-left: auto; margin-right: auto; margin-top: 25px; margin-bottom: -25px;">
                        <button id="place_schedule_button" class="scheduleButton" style="display: inline;">Schedule</button>
                        <button id="cancel_button" class="cancelButton" style="display: inline;">Cancel</button>
                    </div>
                </div>
            
            
		</div>
     </div>
     <!--CONTENT MAIN END-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/zebra_datepicker.js"></script>
    <script src="js/jquery.timepicker.js"></script>

    <!-- Libraries for notifications -->
    <script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
	<script src="realtime-notifications/pusher.min.js"></script>
	<script src="realtime-notifications/PusherNotifier.js"></script>
	<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
	<!--<script src="imageLens/jquery.js" type="text/javascript"></script>-->
	<script src="imageLens/jquery.imageLens.js" type="text/javascript"></script>
    <script src="js/timezones.js" type="text/javascript"></script>
    <script>
		/*$(function() {
	    var pusher = new Pusher('d869a07d8f17a76448ed');
	    var channel_name=$('#MEDID').val();
		var channel = pusher.subscribe(channel_name);
		var notifier=new PusherNotifier(channel);
		
	  });*//*KYLE
    </script>
    <!-- Libraries for notifications -->

    <script src="TypeWatch/jquery.typewatch.js"></script>
    <script type="text/javascript" >
    $(document).ready(function() {
        
        console.log(get_timezone_offset());
        var current_timezone = get_timezone_offset();
        if(current_timezone != 'none')
        {
            var v = parseInt(current_timezone.substr(0, 3));
            var m = parseInt(current_timezone.substr(4, 2));
            if(m != 0)
            {
                $("#timezone_picker").val(v+(m / 60.0));
            }
            else
            {
                $("#timezone_picker").val(v+'.0');
            }
        }
        var doctors = new Array();
        var current_doctor = -1;
        var current_page = 0;
        var current_num_pages = 0;
        var current_date = null;
        window.location = document.getElementById('15_l').href;
        
        $('#start_time').timepicker({ 'scrollDefaultNow': true , 'step': 15});
        
        function loadTimeslots(js_date)
        {
            for(var o = 1; o <= 24; o++)
            {
                $('#tc'+o).children(".timeCellIndicator").children().first().attr("class", "timeCellIndicatorOff");
                $('#tc'+o).children(".timeCellIndicator").children().first().next().attr("class", "timeCellIndicatorOff");
                $('#tc'+o).children(".timeCellIndicator").children().last().prev().attr("class", "timeCellIndicatorOff");
                $('#tc'+o).children(".timeCellIndicator").children().last().attr("class", "timeCellIndicatorOff");
            }
            var wkdy = js_date.getUTCDay();
            var next_wkdy = wkdy + 1;
            var prev_wkdy = wkdy - 1;
            if(next_wkdy > 6)
            {
                next_wkdy = 0;
            }
            if(prev_wkdy < 0)
            {
                prev_wkdy = 6;
            }
            
            for(var i = 0; i < doctors[current_doctor].timeslots.length; i++)
            {
                var timeslot = doctors[current_doctor].timeslots[i];
                var slotweek = new Date(2000, 1, 2, 0, 0, 0, 0);
                slotweek.setUTCFullYear(parseInt(timeslot.substr(14, 4)),parseInt(timeslot.substr(18, 2)) - 1,parseInt(timeslot.substr(20, 2)));
                slotweek.setHours(0);
                slotweek.setMinutes(0);
                var slotweek_end = new Date();
                slotweek_end.setUTCDate(slotweek.getDate()+6);
                slotweek_end.setHours(23);
                slotweek_end.setMinutes(59);
                slotweek_end.setSeconds(59);
                if(js_date.getTime() >= slotweek.getTime() && js_date.getTime() <= slotweek_end.getTime())
                {
                    var start_hour = parseInt(timeslot.substr(1, 2));
                    var start_minute = parseInt(timeslot.substr(3, 2));
                    var end_hour = parseInt(timeslot.substr(5, 2));
                    var end_minute = parseInt(timeslot.substr(7, 2));
                    var add_zone = 0;
                    if(timeslot.substr(9, 1) == '+')
                    {
                        add_zone = 1;
                    }
                    if(add_zone)
                    {
                        var delta_hour = parseInt(timeslot.substr(10, 2));
                        var delta_minute = parseInt(timeslot.substr(12, 2));
                        start_hour -= delta_hour;
                        end_hour -= delta_hour;
                        start_minute -= delta_minute;
                        end_minute -= delta_minute;
                        if(start_minute >= 60)
                        {
                            start_minute -= 60;
                            start_hour += 1;
                            
                        }
                        else if(start_minute < 0)
                        {
                            start_minute += 60;
                            start_hour -= 1;
                        }
                        if(end_minute >= 60)
                        {
                            end_minute -= 60;
                            end_hour += 1;
                            
                        }
                        else if(end_minute < 0)
                        {
                            end_minute += 60;
                            end_hour -= 1;
                        }
                        
                    }
                    else
                    {
                        var delta_hour = parseInt(timeslot.substr(10, 2));
                        var delta_minute = parseInt(timeslot.substr(12, 2));
                        start_hour += delta_hour;
                        end_hour += delta_hour;
                        start_minute += delta_minute;
                        end_minute += delta_minute;
                        if(start_minute >= 60)
                        {
                            start_minute -= 60;
                            start_hour += 1;
                            
                        }
                        else if(start_minute < 0)
                        {
                            start_minute += 60;
                            start_hour -= 1;
                        }
                        if(end_minute >= 60)
                        {
                            end_minute -= 60;
                            end_hour += 1;
                            
                        }
                        else if(end_minute < 0)
                        {
                            end_minute += 60;
                            end_hour -= 1;
                        }
                    }
                    var currentTimezone = parseFloat($("#timezone_picker").val());
                    add_zone = 0;
                    if(currentTimezone >= 0)
                    {
                        add_zone = 1;
                    }
                    var currentTimezoneHour = Math.floor(currentTimezone);
                    var currentTimezoneMinute = (currentTimezone - currentTimezoneHour) * 60;
                    if(add_zone == 0)
                    {
                        currentTimezoneHour = Math.ceil(currentTimezone);
                        currentTimezoneMinute = (currentTimezone - currentTimezoneHour) * 60;
                    }
                    start_hour += currentTimezoneHour;
                    end_hour += currentTimezoneHour;
                    start_minute += currentTimezoneMinute;
                    end_minute += currentTimezoneMinute;
                    if(start_minute >= 60)
                    {
                        start_minute -= 60;
                        start_hour += 1;
                        
                    }
                    else if(start_minute < 0)
                    {
                        start_minute += 60;
                        start_hour -= 1;
                    }
                    if(end_minute >= 60)
                    {
                        end_minute -= 60;
                        end_hour += 1;
                        
                    }
                    else if(end_minute < 0)
                    {
                        end_minute += 60;
                        end_hour -= 1;
                    }
                    if(parseInt(doctors[current_doctor].timeslots[i].charAt(0)) == next_wkdy)
                    {
                        start_hour += 24;
                        end_hour += 24;
                    }
                    else if(parseInt(doctors[current_doctor].timeslots[i].charAt(0)) == prev_wkdy)
                    {
                        start_hour -= 24;
                        end_hour -= 24;
                    }
                    if(parseInt(doctors[current_doctor].timeslots[i].charAt(0)) == wkdy || parseInt(doctors[current_doctor].timeslots[i].charAt(0)) == next_wkdy || parseInt(doctors[current_doctor].timeslots[i].charAt(0)) == prev_wkdy)
                    {   
                        if(start_hour < 24 && end_hour > 0)
                        {
                            if(start_hour >= 0)
                            {
                                var start = start_minute / 15;
                                var end = end_minute / 15;
                                if(start <= 3)
                                {
                                    $("#tc"+(start_hour + 1)).children(".timeCellIndicator").children().last().attr("class", "timeCellIndicatorOn");
                                    if(start <= 2)
                                    {
                                        $("#tc"+(start_hour + 1)).children(".timeCellIndicator").children().last().prev().attr("class", "timeCellIndicatorOn");
                                        if(start <= 1)
                                        {
                                            $("#tc"+(start_hour + 1)).children(".timeCellIndicator").children().first().next().attr("class", "timeCellIndicatorOn");
                                            if(start == 0)
                                            {
                                                $("#tc"+(start_hour + 1)).children(".timeCellIndicator").children().first().attr("class", "timeCellIndicatorOn");
                                            }
                                        }
                                    }
                                }
                            }
                            var k = start_hour + 2;
                            if(k < 1)
                            {
                                k = 1;
                            }
                            var k2 = end_hour;
                            if(k2 > 24)
                            {
                                k2 = 24
                            }
                            for(; k <= k2; k++)
                            {
                                $("#tc"+k).children(".timeCellIndicator").children().first().attr("class", "timeCellIndicatorOn");
                                $("#tc"+k).children(".timeCellIndicator").children().first().next().attr("class", "timeCellIndicatorOn");
                                $("#tc"+k).children(".timeCellIndicator").children().last().prev().attr("class", "timeCellIndicatorOn");
                                $("#tc"+k).children(".timeCellIndicator").children().last().attr("class", "timeCellIndicatorOn");
                            }
                            if(end_hour < 24 && end >= 1)
                            {
                                $("#tc"+(end_hour + 1)).children(".timeCellIndicator").children().first().attr("class", "timeCellIndicatorOn");
                                if(end >= 2)
                                {
                                    $("#tc"+(end_hour + 1)).children(".timeCellIndicator").children().first().next().attr("class", "timeCellIndicatorOn");
                                    if(end >= 3)
                                    {
                                        $("#tc"+(end_hour + 1)).children(".timeCellIndicator").children().last().prev().attr("class", "timeCellIndicatorOn");
                                    }
                                }
                            }
                        }
                        
                    }
                }
                
            }
            
            for(var i = 0; i < doctors[current_doctor].appointments.length; i++)
            {
                var appt = doctors[current_doctor].appointments[i];
                
                var app_date = new Date(js_date);
                app_date.setFullYear(parseInt(appt.substr(0, 4)),parseInt(appt.substr(4, 2)) - 1,parseInt(appt.substr(6, 2)));
                var tomorrow = new Date();
                tomorrow.setDate(js_date.getDate()+1);
                var yesterday = new Date();
                yesterday.setDate(js_date.getDate()-1);
                if(app_date.toString() === js_date.toString() || app_date.toString() == tomorrow.toString() || app_date.toString() == yesterday.toString())
                {
                    
                    var hour = parseInt(appt.substr(8, 2));
                    var minute = parseInt(appt.substr(10, 2));
                    
                    if(app_date.toString() == tomorrow.toString())
                    {
                        start_hour += 24;
                        end_hour += 24;
                    }
                    else if(app_date.toString() == yesterday.toString())
                    {
                        start_hour -= 24;
                        end_hour -= 24;
                    }
                    
                    
                    var timezone_offset_h = parseInt(appt.substr(16, 2));
                    var timezone_offset_m = parseInt(appt.substr(18, 2));
                    if(appt.substr(15, 1) == '+')
                    {
                        hour -= timezone_offset_h;
                        minute -= timezone_offset_m;
                    }
                    else
                    {
                        hour += timezone_offset_h;
                        minute += timezone_offset_m;
                    }
                    var currentTimezone = parseFloat($("#timezone_picker").val());
                    add_zone = 0;
                    if(currentTimezone >= 0)
                    {
                        add_zone = 1;
                    }
                    var currentTimezoneHour = Math.floor(currentTimezone);
                    var currentTimezoneMinute = (currentTimezone - currentTimezoneHour) * 60;
                    if(add_zone == 0)
                    {
                        currentTimezoneHour = Math.ceil(currentTimezone);
                        currentTimezoneMinute = (currentTimezone - currentTimezoneHour) * 60;
                    }
                    hour += currentTimezoneHour;
                    minute += currentTimezoneMinute;
                    
                    if(minute >= 60)
                    {
                        minute -= 60;
                        hour += 1;
                        
                    }
                    else if(minute < 0)
                    {
                        minute += 60;
                        hour -= 1;
                    }
                    var duration = (parseInt(appt.substr(12, 3)) / 15);
                    if(duration > 0 && minute == 0)
                    {
                        $("#tc"+(hour + 1)).children(".timeCellIndicator").children().first().attr("class", "timeCellIndicatorOff");
                        duration--;
                    }
                    if(duration > 0 && minute <= 15)
                    {
                        $("#tc"+(hour + 1)).children(".timeCellIndicator").children().first().next().attr("class", "timeCellIndicatorOff");
                        duration--;
                    }
                    if(duration > 0 && minute <= 30)
                    {
                        $("#tc"+(hour + 1)).children(".timeCellIndicator").children().last().prev().attr("class", "timeCellIndicatorOff");
                        duration--;
                    }
                    if(duration > 0 && minute <= 45)
                    {
                        $("#tc"+(hour + 1)).children(".timeCellIndicator").children().last().attr("class", "timeCellIndicatorOff");
                        duration--;
                    }
                    for(k = hour + 2; k <= 24 && duration > 0; k++)
                    {
                        if(duration > 0)
                        {
                            $("#tc"+k).children(".timeCellIndicator").children().first().attr("class", "timeCellIndicatorOff");
                            duration--;
                        }
                        if(duration > 0)
                        {
                            $("#tc"+k).children(".timeCellIndicator").children().first().next().attr("class", "timeCellIndicatorOff");
                            duration--;
                        }
                        if(duration > 0)
                        {
                            $("#tc"+k).children(".timeCellIndicator").children().last().prev().attr("class", "timeCellIndicatorOff");
                            duration--;
                        }
                        if(duration > 0)
                        {
                            $("#tc"+k).children(".timeCellIndicator").children().last().attr("class", "timeCellIndicatorOff");
                            duration--;
                        }
                        
                    }
                }
                
                
            }
        };
        
        function validateAppointment()
        {
            if($("#start_time").val().length > 0)
            {
                var start_hour = parseInt($("#start_time").val().substr(0, $("#start_time").val().length - 5));
                var start_minute = parseInt($("#start_time").val().substr($("#start_time").val().length - 4, 2));
                var zone = $("#start_time").val().substr($("#start_time").val().length - 2, 2);
                if(zone == 'pm' && start_hour != 12)
                {
                    start_hour += 12;
                }
                else if(start_hour == 12 && zone == 'am')
                {
                    start_hour = 0;
                }
                var valid = true;
                var duration = (parseInt($("#current_duration").val()) / 15);
                console.log(start_hour);
                console.log(duration);
                for(var k = start_hour + 1; k <= 24 && duration > 0; k++)
                {
                    if(((k == (start_hour + 1) && start_minute <= 0) || k > (start_hour + 1)) && duration > 0)
                    {
                        duration--;
                        if($("#tc"+k).children(".timeCellIndicator").children().first().attr("class") == "timeCellIndicatorOff")
                        {
                            valid = false;
                            console.log("Invalid: " + k + ":1");
                            break;
                        }
                    }
                    if(((k == (start_hour + 1) && start_minute <= 15) || k > (start_hour + 1)) && duration > 0)
                    {
                        duration--;
                        if($("#tc"+k).children(".timeCellIndicator").children().first().next().attr("class") == "timeCellIndicatorOff")
                        {
                            valid = false;
                            console.log("Invalid: " + k + ":2");
                            break;
                        }
                    }
                    if(((k == (start_hour + 1) && start_minute <= 30) || k > (start_hour + 1)) && duration > 0)
                    {
                        duration--;
                        if($("#tc"+k).children(".timeCellIndicator").children().last().prev().attr("class") == "timeCellIndicatorOff")
                        {
                            valid = false;
                            console.log("Invalid: " + k + ":3");
                            break;
                        }
                    }
                    if(((k == (start_hour + 1) && start_minute <= 45) || k > (start_hour + 1)) && duration > 0)
                    {
                        duration--;
                        if($("#tc"+k).children(".timeCellIndicator").children().last().attr("class") == "timeCellIndicatorOff")
                        {
                            valid = false;
                            console.log("Invalid: " + k + ":4");
                            break;
                        }
                    }
                    
                }
                if(valid)
                {
                    console.log("valid");
                    $('button.scheduleButton').removeAttr('disabled');
                }
                else
                {
                    console.log("Invalid");
                    $('button.scheduleButton').attr('disabled', 'true');
                }
                
            }
            else
            {
                $('button.scheduleButton').attr('disabled', 'true');
            }
        }
        
        $('input.datepicker').Zebra_DatePicker({direction: true, format: 'M d, Y', onSelect: function(formatted_date, standard_date, js_date, element) {
            current_date = js_date;
            loadTimeslots(current_date);
            validateAppointment();
            
        }, onClear: function() { 
            for(var o = 1; o <= 24; o++)
            {
                $('#tc'+o).children(".timeCellIndicator").children().first().attr("class", "timeCellIndicatorOff");
                $('#tc'+o).children(".timeCellIndicator").children().first().next().attr("class", "timeCellIndicatorOff");
                $('#tc'+o).children(".timeCellIndicator").children().last().prev().attr("class", "timeCellIndicatorOff");
                $('#tc'+o).children(".timeCellIndicator").children().last().attr("class", "timeCellIndicatorOff");
            }
            current_date = null;
        }});
        
        $("#timezone_picker").change(function(){
            if(current_date != null)
            {
                loadTimeslots(current_date);
            }
        });
        
        /*
            Timeslot string format: "whhmmHHMMsOOooYYYYMMDD"
            
            w - the weekday for the timeslot 0 (Sunday) - 6 (Saturday)
            h - the hour for the starting time (military format)
            m - the minute for the starting time
            H - the hour for the ending time (military format)
            M - the minute for the ending time
            s - the sign for the timezone offset (+ or -) (either if timezone is GMT)
            O - the timezone offset in hours
            o - the timezone offset in minutes
            Y - the year of the week that this timeslot belongs to
            M - the month of the week that this timeslot belongs to
            D - the day of the week that this timeslow belongs to (must be a sunday)
            
            i.e. "308001200+0320140420" - Wednesday from 8 am to noon +3 from GMT at the week from Apr 20, 2014 - Apr 26, 2014
            
            
            Appointment string format: "YYYYMMDDHHMMfffsOOoo"
            
            Y - the year of the appointment
            M - the month of the appointment
            D - the day of the appointment
            H - the hour of the appointment
            M - the minute of the appointment
            f - the duration of the appointment in minutes
            s - the sign for the timezone offset (+ or -) (either if timezone is GMT)
            O - the timezone offset in hours
            o - the timezone offset in minutes
            
            i.e. "201404241100030-0600" - 11:00 am April 24, 2014 lasting 30 minutes -6 from GMT
        *//*KYLE
        function displayDoctors(page, num_pages)
        {
         
            var num_doctors = doctors.length;
            var new_html = '';
            for(var i = 0; i < num_doctors; i++)
            {
                var date = new Date();
                date = new Date(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(), date.getUTCHours(), date.getUTCMinutes(), date.getUTCSeconds());
                var go = true;
                if($("#search_bar").val().length > 0)
                {
                    var patt = new RegExp($("#search_bar").val(), "i");
                    if(patt.test(doctors[i].name + ' ' + doctors[i].surname) == false)
                    {
                        go = false;
                    }
                }
                if(!$('#online_toggle').is(":checked") && ((!doctors[i].online && doctors[i].phone.length == 0) || !isDateWithinTimeslots(date, doctors[i].timeslots)))
                {
                    go = false;
                }
                if(go)
                {
                    var total = 0;
                    var amount = 0;
                    for(var z = 0; z < 10; z++)
                    {
                        total += doctors[i].rating[z];
                        amount += (doctors[i].rating[z] * (z + 1));
                    }
                    amount = (amount / total);
                    new_html += '<div><div class="doctorList"><div style="width: 210px; float:left; margin-left: 4px; margin-top: 4px;"><p style="color: #B3B3B3; font-size: 16px;">';
                    new_html += 'Dr. ' + doctors[i].name + ' ' + doctors[i].surname;
                    new_html += '</p></div><div style="width: 150px; float:right; margin-right: 15px; margin-top: 4px;"><p style="color: #B3B3B3; font-size: 16px; text-align: right;">$';
                    new_html += doctors[i].rate;
                    new_html += ' / Hour</p></div><div style="display: block; overflow: hidden; height:22px; width: 130px; float:right; margin-top: 3px; margin-right: 5px;"><img src="images/stars.png" style="margin-top: ';
                    new_html += -22 * Math.round(amount);
                    new_html += 'px; float: left; " /><p style="float: left; color: #CACACA; font-size: 10px;">('+total+')</p></div><div style="width: 98%; height: 50px; margin-top: 40px; margin-left: 4px;"><span style="color: #A3A3A3; font-size: 14px;">Location: ';
                    new_html += doctors[i].location;
                    new_html += '<br/>Speciality: ';
                    new_html += doctors[i].speciality;
                    new_html += '</span></div></div><button id="sch_';
                    new_html += i;
                    new_html += '" class="doctorScheduleButton">Schedule Appointment</button><button id="call_';
                    new_html += i;
                    new_html += '" class="doctorCallButton"';
                    
                    if((!doctors[i].online && doctors[i].phone.length == 0) || !isDateWithinTimeslots(date, doctors[i].timeslots))
                    {
                        
                        new_html += ' disabled>Unavailable';
                    }
                    else
                    {
                        new_html += '>Call Now';
                    }
                    new_html += '</button></div>';
                }
            }
            //new_html += '';
            new_html += '<div style="float: left; width: 100%; height: 30px;"><center><button class="icon-chevron-left page_button" id="prev_page"';
            if(page == 0)
            {
                new_html += 'disabled';
            }
            new_html += '></button>'+(page + 1)+' of '+Math.ceil(num_pages / 20)+'<button class="icon-chevron-right page_button" id="next_page"';
            if(page == Math.floor(num_pages / 20))
            {
                new_html += 'disabled';
            }
            new_html += '></button></center></div>';
            current_page = page;
            current_num_pages = Math.floor(num_pages / 20);
            $("#doctors_list").html(new_html);
            
            $( "div.doctorList" ).on( "mouseenter", function() {
                $(this).stop().animate({height:'100px'}, 500);
            }).on( "mouseleave", function() {
                $(this).stop().animate({height:'30px'}, 500);
            });
        }
        function getDoctors(ord, ascending, spec, loc, online, search)
        {
            doctors.length = 0;
            $.post("getTelemedDoctors.php", {page: current_page, order: ord, asc: ascending, timezone: current_timezone, location: loc, online_only: online, search_term: search}, function(data,status)
            {
                var items = JSON.parse(data);
                var num = 0;
                for(var i = 0; i < items.length; i++)
                {
                    doctors.push({name: items[i].name, surname: items[i].surname, rating: items[i].rating, rate: items[i].hourly_rate, online: items[i].online, speciality: items[i].speciality, location: items[i].location, id: items[i].id, timeslots: items[i].timeslots, appointments: items[i].appointments, phone: items[i].phone});
                    num = items[i].count;
                }
                displayDoctors(current_page, num);
            });
        }
        getDoctors(0, 1, '', '', 0, '');
        
        function updateAvailability()
        {
            $.get("getTelemedAvailability.php", function(data, status)
            {
                var items = JSON.parse(data);
                for(var h = 0; h < doctors.length; h++)
                {
                    if(items[doctors[h].id] != null)
                    {
                        doctors[h].online = items[doctors[h].id];
                    }
                }
                var date = new Date();
                date = new Date(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(), date.getUTCHours(), date.getUTCMinutes(), date.getUTCSeconds());
                $("#doctors_list").children('div').each(function(i) {
                    var offset = parseInt($(this).children(".doctorCallButton").attr("id").substr(5, $(this).children(".doctorCallButton").attr("id").length - 5));
                    if((!doctors[offset].online && doctors[offset].phone.length == 0) || !isDateWithinTimeslots(date, doctors[offset].timeslots))
                    {
                        $(this).children(".doctorCallButton").attr("disabled", "disabled");
                        $(this).children(".doctorCallButton").text("Unavailable");
                    }
                    else
                    {
                        $(this).children(".doctorCallButton").removeAttr("disabled");
                        $(this).children(".doctorCallButton").text("Call Now");
                    }
                });
            });
        }
        setInterval(function(){updateAvailability();}, 3000);
        $('#next_page').live('click',function(e)
        {
            if(current_page < current_num_pages)
            {
                current_page += 1;
                get_doctors(0, 1, '', '', 0, '');
            }
        });
        $('#prev_page').live('click',function(e)
        {
            if(current_page > 0)
            {
                current_page -= 1;
                get_doctors(0, 1, '', '', 0, '');
            }
        });
            
        $('#name_button').live('click',function(e)
        {
            $("#rating_button").children("i").attr("class", "");
            $("#rate_button").children("i").attr("class", "");
            if($(this).children("i").attr("class") == "icon-arrow-up")
            {
                $(this).children("i").attr("class", "icon-arrow-down");
                doctors.sort(function(a,b){return (a.surname + a.name)<(b.surname + b.name);});
            }
            else if($(this).children("i").attr("class") == "icon-arrow-down")
            {
                $(this).children("i").attr("class", "icon-arrow-up");
                doctors.sort(function(a,b){return (b.surname + b.name)<(a.surname + a.name);});
            }
            else
            {
                $(this).children("i").attr("class", "icon-arrow-up");
                doctors.sort(function(a,b){return (b.surname + b.name)<(a.surname + a.name);});
            }
            $("#Schedule").fadeOut(150, "linear", function(){$("#Doctors").animate({height: "520px"}, 300);});
            displayDoctors();
            
        });
            
        $('#rate_button').live('click',function(e)
        {
            $("#rating_button").children("i").attr("class", "");
            $("#name_button").children("i").attr("class", "");
            if($(this).children("i").attr("class") == "icon-arrow-up")
            {
                $(this).children("i").attr("class", "icon-arrow-down");
                doctors.sort(function(a,b){return a.rate<b.rate;});
            }
            else if($(this).children("i").attr("class") == "icon-arrow-down")
            {
                $(this).children("i").attr("class", "icon-arrow-up");
                doctors.sort(function(a,b){return b.rate<a.rate;});
            }
            else
            {
                $(this).children("i").attr("class", "icon-arrow-up");
                doctors.sort(function(a,b){return b.rate<a.rate;});
            }
            $("#Schedule").fadeOut(150, "linear", function(){$("#Doctors").animate({height: "520px"}, 300);});
            displayDoctors();
            
        });
            
        $('#rating_button').live('click',function(e)
        {
            $("#name_button").children("i").attr("class", "");
            $("#rate_button").children("i").attr("class", "");
            if($(this).children("i").attr("class") == "icon-arrow-up")
            {
                $(this).children("i").attr("class", "icon-arrow-down");
                doctors.sort(function(a,b){
                    var a_val = 0;
                    var b_val = 0;
                    for(var z = 1; z <= 10; z++)
                    {
                        var val = z - 5;
                        if(val > 0)
                        {
                            val -= 1;
                        }
                        val = Math.abs(val);
                        if(z <= 5)
                        {
                            a_val += (a.rating[z-1] * (-1 * Math.pow(2, val)));
                            b_val += (b.rating[z-1] * (-1 * Math.pow(2, val)));
                        }
                        else
                        {
                            a_val += (a.rating[z-1] * Math.pow(2, val));
                            b_val += (b.rating[z-1] * Math.pow(2, val));
                        }
                    }
                    return b_val<a_val;
                });
            }
            else if($(this).children("i").attr("class") == "icon-arrow-down")
            {
                $(this).children("i").attr("class", "icon-arrow-up");
                doctors.sort(function(a,b){
                    var a_val = 0;
                    var b_val = 0;
                    for(var z = 0; z < 10; z++)
                    {
                        var val = z - 5;
                        if(val > 0)
                        {
                            val -= 1;
                        }
                        val = Math.abs(val);
                        if(z <= 5)
                        {
                            a_val += (a.rating[z] * (-1 * Math.pow(2, val)));
                            b_val += (b.rating[z] * (-1 * Math.pow(2, val)));
                        }
                        else
                        {
                            a_val += (a.rating[z] * Math.pow(2, val));
                            b_val += (b.rating[z] * Math.pow(2, val));
                        }
                    }
                    return a_val<b_val;
                });
            }
            else
            {
                $(this).children("i").attr("class", "icon-arrow-up");
                doctors.sort(function(a,b){
                    var a_val = 0;
                    var b_val = 0;
                    for(var z = 0; z < 10; z++)
                    {
                        var val = z - 5;
                        if(val > 0)
                        {
                            val -= 1;
                        }
                        val = Math.abs(val);
                        if(z <= 5)
                        {
                            a_val += (a.rating[z] * (-1 * Math.pow(2, val)));
                            b_val += (b.rating[z] * (-1 * Math.pow(2, val)));
                        }
                        else
                        {
                            a_val += (a.rating[z] * Math.pow(2, val));
                            b_val += (b.rating[z] * Math.pow(2, val));
                        }
                    }
                    return a_val<b_val;
                });
            }
            $("#Schedule").fadeOut(150, "linear", function(){$("#Doctors").animate({height: "520px"}, 300);});
            displayDoctors();
        });
        
        
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
            
        $('button.doctorScheduleButton').live('click',function(e)
        {
            e.preventDefault();
            current_date = null;
            $("#schedule_date").val("");
            $("#start_time").val("");
            $("#current_duration").val(0);
            $('button.scheduleButton').attr('disabled', 'true');
            for(var o = 1; o <= 24; o++)
            {
                $('#tc'+o).children(".timeCellIndicator").children().first().attr("class", "timeCellIndicatorOff");
                $('#tc'+o).children(".timeCellIndicator").children().first().next().attr("class", "timeCellIndicatorOff");
                $('#tc'+o).children(".timeCellIndicator").children().last().prev().attr("class", "timeCellIndicatorOff");
                $('#tc'+o).children(".timeCellIndicator").children().last().attr("class", "timeCellIndicatorOff");
            }
            current_doctor = parseInt(this.id.substr(4, this.id.length - 4));
            $("#Doctors").animate({height: "252px"}, 300, function(){$("#Schedule").fadeIn(150, "linear");});
            
        });
        $('#phone_call_button').live('click',function(e)
         {
            $("#modalContents").dialog("close");
            $.post("start_telemed_phonecall.php", {pat_phone: $("#pat_phone").val(), doc_phone: doctors[current_doctor].phone, doc_id: doctors[current_doctor].id, pat_id: $("#pat_id").val(), doc_name: (doctors[current_doctor].name + ' ' + doctors[current_doctor].surname), pat_name: $("#NombreEnt").val().replace(".", " ")}, function(data, status)
            {
                console.log(data);
            });
         });
        $('#video_call_button').live('click',function(e)
         {
            $("#modalContents").dialog("close");
            $.post("start_telemed_videocall.php", {pat_phone: $("#pat_phone").val(), doc_phone: doctors[current_doctor].phone, doc_id: doctors[current_doctor].id, pat_id: $("#pat_id").val(), doc_name: (doctors[current_doctor].name + ' ' + doctors[current_doctor].surname), pat_name: $("#NombreEnt").val().replace(".", " ")}, function(data, status)
            {
                if(data == 1)
                {
                    window.open("telemedicine_patient.php?MED=" + doctors[current_doctor].id + "&PAT=" + $("#pat_id").val(),"Telemedicine","height=650,width=700,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes");
                }
                else
                {
                    alert("There was an error. Please try again later");
                }
            });
         });
        $('button.doctorCallButton').live('click',function(e)
        {
            current_doctor = parseInt(this.id.substr(5, this.id.length - 5));
            if($("#pat_phone").val().length == 0 || doctors[current_doctor].phone.length == 0)
                $("#phone_call_button").attr("disabled", "disabled");
            else
                $("#phone_call_button").removeAttr("disabled");
            if(!doctors[current_doctor].online)
            {
                $("#video_call_button").attr("disabled", "disabled");
            }
            else
            {
                $("#video_call_button").removeAttr("disabled");
            }
            $("#modalContents").dialog({bgiframe: true, width: 550, height: 150, modal: true});
        });
        $('button.cancelButton').live('click',function(e)
        {
            e.preventDefault();
            $("#Schedule").fadeOut(150, "linear", function(){$("#Doctors").animate({height: "520px"}, 300);});
            
        });
        $('#15_l').live('click',function(e)
        {
            $('#current_duration').val(15);
            validateAppointment();
        });
        $('#30_l').live('click',function(e)
        {
            $('#current_duration').val(30);
            validateAppointment();
        });
        $('#45_l').live('click',function(e)
        {
            $('#current_duration').val(45);
            validateAppointment();
        });
        $('#60_l').live('click',function(e)
        {
            $('#current_duration').val(60);
            validateAppointment();
        });
        $('#start_time').on('changeTime', function() {
            validateAppointment();
        });
        $("#timezone_picker").change(function() {
            validateAppointment();
        });
        $('#search_bar_button').live('click',function(e){
            displayDoctors();
        });
        $('#online_toggle').live('click',function(){
            displayDoctors();
        });
        
        function addAppointment(video)
        {
            var d = current_date.getFullYear().toString() + "-";
            var month = (current_date.getMonth() + 1);
            var day = current_date.getDate();
            if(month < 10)
            {
                d += "0";
            }
            d += month.toString() + "-";
            if(day < 10)
            {
                d += "0";
            }
            d += day.toString() + " ";
            var hour = parseInt($("#start_time").val().substr(0, $("#start_time").val().length - 5));
            var minute = parseInt($("#start_time").val().substr($("#start_time").val().length - 4, 2));
            var zone = $("#start_time").val().substr($("#start_time").val().length - 2, 2);
            if(zone == 'pm' && hour != 12)
            {
                hour += 12;
            }
            else if(hour == 12 && zone == 'am')
            {
                hour = 0;
            }
            d += hour.toString() + ":";
            if(minute < 10)
            {
                d += "0";
            }
            d += minute.toString() + ":00";
            var dur = parseInt($("#current_duration").val());
            var currentTimezone = parseFloat($("#timezone_picker").val());
            add_zone = 0;
            var timezone = "";
            if(currentTimezone >= 0)
            {
                add_zone = 1;
            }
            else
            {
                timezone += "-";
            }
            var currentTimezoneHour = Math.floor(currentTimezone);
            var currentTimezoneMinute = (currentTimezone - currentTimezoneHour) * 60;
            if(add_zone == 0)
            {
                currentTimezoneHour = Math.ceil(currentTimezone);
                currentTimezoneMinute = (currentTimezone - currentTimezoneHour) * 60;
                currentTimezoneHour *= -1;
            }
            if(currentTimezoneHour < 10)
            {
                timezone += "0";
            }
            timezone += currentTimezoneHour + ":";
            if(currentTimezoneMinute < 10)
            {
                timezone += "0";
            }
            timezone += currentTimezoneMinute + ":00";
            $.post("add_appointment.php", {med_id: doctors[current_doctor].id, pat_id: $("#pat_id").val(), duration: dur, date: d, video: video, timezone: timezone}, function(data, status)
           {
               if(parseInt(data) == 1)
               {
                    $("#Schedule").fadeOut(150, "linear", function(){$("#Doctors").animate({height: "520px"}, 300);});
               }
               else
               {
                   alert("An error occured. Please try again with a different date");
               }
           });
        }
        $('button.scheduleButton').live('click',function(e)
        {
            e.preventDefault();
            if($("#pat_phone").val().length == 0 || doctors[current_doctor].phone.length == 0)
                $("#phone_call_button_app").attr("disabled", "disabled");
            else
                $("#phone_call_button_app").removeAttr("disabled");
            $("#modalContents2").dialog({bgiframe: true, width: 550, height: 150, modal: true});
            
            
        });
        $('#phone_call_button_app').live('click',function(e)
         {
            
             $("#modalContents2").dialog("close");
             addAppointment(0);
         });
        $('#video_call_button_app').live('click',function(e)
         {
            
             $("#modalContents2").dialog("close");
             addAppointment(1);
         });
        
            
            
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
        function generateDate(timeslot, is_start)
        {
            var str = timeslot.substr(14, 4) + "-" + timeslot.substr(18, 2) + "-" + timeslot.substr(20, 2) + " ";
            if(is_start)
            {
                str += timeslot.substr(1, 2) + ":" + timeslot.substr(3, 2);
            }
            else
            {
                str += timeslot.substr(5, 2) + ":" + timeslot.substr(7, 2);
            }
            str += ":00";
            var d = new Date(str);
            d = new Date(d.getUTCFullYear(), d.getUTCMonth(), d.getUTCDate(), d.getUTCHours(), d.getUTCMinutes(), d.getUTCSeconds());
            d.setDate(d.getDate()+parseInt(timeslot.substr(0, 1)));
            var is_add = false;
            if(timeslot.substr(9, 1) == '+')
            {
                is_add = true;
            }
            if(is_add)
            {
                d.setHours(d.getHours() - parseInt(timeslot.substr(10, 2)));
                d.setMinutes(d.getMinutes() - parseInt(timeslot.substr(12, 2)));
            }
            else
            {
                d.setHours(d.getHours() + parseInt(timeslot.substr(10, 2)));
                d.setMinutes(d.getMinutes() + parseInt(timeslot.substr(12, 2)));
            }
            is_add = false;
            if(current_timezone.substr(0, 1) == '+')
            {
                is_add = true;
            }
            if(is_add)
            {
                d.setHours(d.getHours() + parseInt(current_timezone.substr(1, 2)));
                d.setMinutes(d.getMinutes() + parseInt(current_timezone.substr(4, 2)));
            }
            else
            {
                d.setHours(d.getHours() - parseInt(current_timezone.substr(1, 2)));
                d.setMinutes(d.getMinutes() - parseInt(current_timezone.substr(4, 2)));
            }
            return d;
            
        }
        function isDateWithinTimeslots(date, timeslots)
        {
            var res = false;
            for(var i = 0; i < timeslots.length; i++)
            {
                var start = generateDate(timeslots[i], true);
                var end = generateDate(timeslots[i], false);
                if(date >= start && date <= end)
                {
                    res = true;
                    break;
                }
                
            }
            return res;
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
