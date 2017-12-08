<?php
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
?>
<!DOCTYPE html>
<html lang="en" style="background-image: none;"><head>
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

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lte IE 8]><script type="text/javascript" src="/js/excanvas.min.js"></script><![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/icons/favicon.ico">
    
  </head>

  <body style="background-image: none;">

     
     <!--CONTENT MAIN START-->
     <div class="content" style="width:700px; margin:auto; height:1000px;" >

     <!--Wizard Start-->
        <div class="grid">
          <div class="grid-title">
            <div class="pull-left"> <span class="label label-info" style="font-size:18px; margin-left:10px; font-size:20px; background-color:orange; color:white;">health2.me Account Setup</span></div>
             
            <div class="pull-right"></div>
            <div class="clear"></div>
          </div>
          <div class="grid-content">

          </div>  
        </div>
      <!--Wizard END-->

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
        if($.browser.msie)
            {
                window.open('ie_error.html','_self',false);
            }
    
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

