<?php
$probeID=$_GET["probeID"];
?>

<!DOCTYPE html>
<html lang="en" style="background: #F9F9F9;"><head>
    <meta charset="utf-8">
    <title>Inmers - Center Management Console</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
	<link href="css/login.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">

<!--	<link rel="stylesheet" href="css/icon/font-awesome.css">-->
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" type="text/css" href="css/tooltipster.css" />

    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/basic.css" />
    <link rel="stylesheet" href="css/dropzone.css"/>
    <script src="js/dropzone.min.js"></script>
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
	
	<link href="css/demo_style.css" rel="stylesheet" type="text/css"/>
	<link href="css/smart_wizard.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="css/MooEditable.css">
	<link rel="stylesheet" href="css/jquery-ui-autocomplete.css" />
	<script src="js/jquery-1.9.1-autocomplete.js"></script>
	<script src="js/jquery-ui-autocomplete.js"></script>
		
	
	<link rel="stylesheet" type="text/css" href="js/uploadify/uploadify.css">
    <script type="text/javascript" src="js/uploadify/jquery.uploadify.min.js"></script> 
   
   

   

	<!--
	<link rel="stylesheet" href="css/icon/font-awesome.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
    -->
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
  <body style="background: #F9F9F9;" >
	<!--Header Start-->
	<div class="header" >
    	
           <a href="index.html" class="logo"><h1>health2.me</h1></a>
           
           <div class="pull-right">
           
            
           <!--Button User Start-->
		   <div class="btn-group pull-right" >
           
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
              <!--<span class="name-user"><strong>Welcome</strong> Dr, <Patient Name></span> -->
             
            </a>
                     </div>
          <!--Button User END-->  
          
          </div>
    </div>
    <!--Header END-->

    <!--Content Start-->
	<div id="content" style="background: #F9F9F9; padding-left:0px;">

		<div class="grid" class="grid span4" style="width:1000px; height:100px; margin: 0 auto; margin-top:30px; padding-top:30px;">

				<!--<h1>Thank You for providing your Health Status Update. Your Response was saved Successfully<h1>-->
				<center>
				<span style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto;  ">Please confirm that you want to stop receiving feedback Emails ?</span>
				<div class="clearfix"></div>
				<br>
				<a href="#" class="btn btn-success" data-dismiss="modal" id="stopProbe">Confirm</a>
				
				</center>
                <!--<span class="label label-info" style="left:0px; margin-left:30px; font-size:30px;">Feedback</span>
				
				<hr>-->
				
				<!--<div id="PendingTasks" style="width:940px; margin-left:30px; margin-right:0px; margin-top:30px;" >
                        <div class="grid" style="min-height:100px; max-height:300px;margin: 0 auto; ">
									
									<div class="clearfix"></div>
									<br><br>
									
									<span style="font: bold 18px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:250px; ">Dr.XYZ wants to know how you feel</span>
									<form name="myform" action="#" method="POST">
										<div align="center"><br>
											<input type="radio" name="group1" value="Awful"> Awful<br>
											<input type="radio" name="group1" value="Poor" checked> Poor<br>
											<input type="radio" name="group1" value="Good"> Good
											<input type="radio" name="group1" value="Very Good"> Very Good<br>
											<input type="radio" name="group1" value="Excellent"> Excellent<br>
											
										</div>
									</form>
									
									
									
                                    
                        </div>
               </div>-->
				
		</div>
	  
	</div>
	</div>

	<script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
	
	<script type="text/javascript" >
	
	
	$(document).ready(function() {
	
		
	});
	
	$('#stopProbe').live('click',function(){
		//alert('clicked Yes');
		var url="stopPatientFeedback.php?probeID="+<?php echo $probeID;?>;
		//alert(url);
		LanzaAjax(url);
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
	
	
	</script>
	
	
	
	
</body>
</html>