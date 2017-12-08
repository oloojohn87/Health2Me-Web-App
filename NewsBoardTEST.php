<?php
session_start();
 require("environment_detail.php");
 require("PasswordHash.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


$NombreEnt = $_SESSION['Nombre'];
$PasswordEnt = $_SESSION['Password'];
$MEDID = $_SESSION['MEDID'];
$Acceso = $_SESSION['Acceso'];
$privilege=$_SESSION['Previlege'];

?>
<!DOCTYPE html>
<html lang="en" style="background: #F9F9F9;"><head>
    <meta charset="utf-8">
    <title>Health2Me - HOME</title>
    <link rel="icon" type="image/ico" href="favicon.ico"/>
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
    
	<!--<link rel="stylesheet" href="css/icon/font-awesome.css">-->
   	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/H2MIcons.css" />
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" href="css/toggle-switch.css">
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

<input type="hidden" id="NombreEnt" value="<?php echo $NombreEnt; ?>">
<input type="hidden" id="PasswordEnt" value="<?php echo $PasswordEnt; ?>">
<input type="hidden" id="UserHidden">

<style>
div.OuterNEWS{
	float:left; 
	margin:10px; 
	padding:0px; 
	border:1px solid #cacaca; 
	border-radius:10px; 
	width:200px; 
	height:300px;	
}

div.HeaderNEWS{
	margin-left:-1px; 
	margin-top:-1px; 
	border:1px solid #cacaca; 
	border-radius:10px; 
	border-bottom-right-radius:0px; 
	border-bottom-left-radius:0px; 
	width:100%; 
	height:65px;	
}

div.MiddleNEWS{
	width:200px; 
	height:100px; 
	text-align:center; 
	display: table-cell; 
	vertical-align: middle;
}

div.FooterNEWS{
	width:200px; 
	height:50px; 
	text-align:center; 
	display: table-cell; 
	vertical-align: middle;
	background-color:;
}

div.DrName{
	float:left; 
	color:grey; 
	font-size:12px; 
	font-weight:bold; 
	text-align:center; 
	width:80px; 
	margin-top:10px; 
	color:#22aeff;	
}

div.PatName{
	float:left; 
	color:grey; 
	font-size:12px; 
	font-weight:normal; 
	text-align:center; 
	width:80px; 
	margin-top:-5px; 
	color:#54bc00;	
}

div.modNews{
	border:none; 
	float:left; 
	width:40px; 
	height:40px; 
	margin:10px; 
	font-size:12px; 
	background-color:#22aeff;
}

div.modNews2{
	border:none; 
	float:right; 
	width:30px; 
	height:30px; 
	margin:10px; 
	margin-top:-15px; 
	font-size:10px; 
	background-color:#00CC66;
}

</style>

    
    <!--Content Start-->
	<div id="content" style="background: #F9F9F9; padding-left:0px;">
	     <!--CONTENT MAIN START-->
	     <div class="content">
	     		<div class="grid" style="height:1000px; width:1000px; margin: 0 auto; margin-top:30px; padding:30px;">
		     		<div class="grid" style="float:left; width:600px; margin: 0 auto; min-height:100px; margin-top:30px; padding:10px;">
		     			<div class="OuterNEWS">
		     				<div class="HeaderNEWS">
		     					<div class="LetterCircleON modNews"><p style="margin:0px; padding:0px; margin-top:13px;">P K</p></div>
			 					<div class="DrName">Dr. T. Anthat</div>
			 					<div class="PatName">E. Thisisit</div>
		     					<div class="LetterCircleON modNews2"><p style="margin:0px; padding:0px; margin-top:8px;">A R</p></div>
		     				</div>
		     				<div class="MiddleNEWS">
		     					<icon class="icon-envelope icon-5x" style="color:#22aeff; display: inline-block; vertical-align: middle; "></icon>
		     				</div>	
		     			</div>
		     		</div>
		     		<div class="grid" style="float:left; width:600px; margin: 0 auto; min-height:100px; margin-top:30px; padding-top:30px;">
		     			<div style="float:left; margin:10px; border:1px solid #cacaca; border-radius:5px; width:100px; height:200px;">
		     				INSIDE
		     			</div>
		     		</div>
	     		</div>	
		 </div>    
     </div>
     
</body>


</html>
      </body>
</html>
