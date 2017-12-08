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

    
    <!--Content Start-->
	<div id="content" style="background: #F9F9F9; padding-left:0px;">
	     <!--CONTENT MAIN START-->
	     <div class="content">
	     		<div class="grid" style="width:1000px; margin: 0 auto; margin-top:30px; padding-top:30px;">
		     		<div class="grid" style="width:900px; margin: 0 auto; margin-top:30px; padding:30px; overflow:auto; text-align:center;">
			                <div id="CanvasContainer" style="width:400px; height:400px; margin:0 auto; border:1px solid grey;"></div>

		     		</div>
		     		<div class="grid" style="width:950px; margin: 0 auto; margin-top:30px; margin-bottom:30px; padding-top:30px;">
		     			
		     		</div>
	     		</div>	
		 </div>    
     </div>
    <!-- Placed at the end of the document so the pages load faster -->
    <!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>-->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
	<script src="js/kinetic-v4.5.5.min.js"></script>


    <script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
    <link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
    <script type="text/javascript" >

	$(window).load(function() {
    
    var stageScenery = new Kinetic.Stage({
            container: 'CanvasContainer',
            width: 400,
            height: 400
    });
        
    var layer = new Kinetic.Layer();
	var unidad = (Math.PI * 0.1);
	
	var OrbitArray = Array();
	var n=0;
	
	while (n < 5)
	{


		var orbit1 = new Kinetic.Shape({
		    m : n,
		    drawFunc: function(canvas) {
		        var context = canvas.getContext('2d');
		        context.globalAlpha=0.8; // 0.8 opacity
				context.beginPath();
		        context.arc(200, 200, 30*this.attrs.m, 0,2*Math.PI, false);
		        canvas.stroke(this);
		        //canvas.fillStroke(this);
		    },
		    fill: 'blue',
		    stroke: 'grey',
		    strokeWidth: 10,
		});
	
		OrbitArray[n] = orbit1;
	    OrbitArray[n].on("click", function() {
	           alert ('click');
	           OrbitHover(this.attrs.id);
	        });
	    layer.add(OrbitArray[n]);
	    /*
	    layer.on('click', function(evt) {
			// get the shape that was clicked on
			var shape = evt.targetNode;
			alert('you clicked on \"' + shape.getName() + '\"');
		});    
	    */    
		n++;	
	}
	
	var arc = new Kinetic.Shape({
    drawFunc: function(canvas) {
		var ctx = canvas.getContext();
        ctx.beginPath();
        ctx.lineWidth = 4;
        var startAngle = 0;
        var endAngle = 135 * Math.PI / 180;
        ctx.arc(50, 50, 40, startAngle, endAngle, false);
        ctx.stroke();
        canvas.fillStroke(this);
		},
		fill: 'blue'
	});
	arc.on('click', function() {
    	alert("click detected");
		});

	layer.add(arc);

	
	stageScenery.add(layer);

    });
     
    function OrbitHover(OrbitIndex){
		alert ('Orbit #: '+OrbitIndex);
	    
    }; 
    
    $(document).ready(function() {



		});
     
	
    </script> 
</body>


</html>

