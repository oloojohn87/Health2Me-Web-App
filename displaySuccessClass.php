<?php
class displaySuccessClass{
public function displayFunction($exitType){
echo "
<!DOCTYPE html>
<html lang='en'  class='body-error'><head>
    <meta charset='utf-8'>
    <title>health2.me</title>
    <!--<meta name='viewport' content='width=device-width, initial-scale=1.0'>-->
    <meta name='description' content=''>
    <meta name='author' content=''>
    <!-- Le styles -->
    <link href='css/style.css' rel='stylesheet'>
    <link href='css/bootstrap.css' rel='stylesheet'>
    
    <link rel='apple-touch-icon' href='images/icon.png'/>
    
    <link rel='stylesheet' href='css/jquery-ui-1.8.16.custom.css' media='screen'  />
 
    <link rel='stylesheet' href='css/chosen.css' media='screen'  />
    
    <link rel='stylesheet' href='css/glisse.css?1.css'>
    
    <link rel='stylesheet' href='css/jquery.tagsinput.css' />
    <link rel='stylesheet' href='css/jquery.jscrollpane.css' >
 
    
	<link rel='stylesheet' href='css/icon/font-awesome.css'>
    <link rel='stylesheet' href='css/bootstrap-responsive.css'>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src='http://html5shim.googlecode.com/svn/trunk/html5.js'></script>
    <![endif]-->
    <!--[if lte IE 8]><script type='text/javascript' src='/js/excanvas.min.js'></script><![endif]-->
    <!-- Le fav and touch icons -->
    <link rel='shortcut icon' href='/images/icons/favicon.ico'>
    
    
    
  </head>
  <body>
	<!--Header Start-->
	<div class='header' >
    		
           <a href='index.html' class='logo'><h1>I</h1></a>
           
           <div class='pull-right'>
           
         
          
          </div>
    </div>
    <!--Header END-->
    
     
     <div class='error-bg'>
      <div class='error-s'>
        <!--<div class='error-number'>Health2me</div>-->
        <div class='error-number'><img src='images/health2meLOGO.png' width='350' /img></div>";
		if($exitType == 4){
        echo "<div class='error-number' style='font-size:20px; margin-top:0px; padding:0px; border:0ox;'>unlocking health</div>";
		echo "<!--<div class='error-number' style='font-size:20px; margin:0px; padding:0px; border:0px;'>social health networking</div>-->";
		}else{
		echo "<div class='error-number' style='font-size:20px; margin-top:15px;'>unlocking health</div>";
		echo "<!--<div class='error-number' style='font-size:20px; margin-top:15px;'>social health networking</div>-->";
		}
        
        echo "<div class='error-text' style='margin-top:10px;'>version 1.1</div>";
		
		if($exitType == 6){
		echo "<div class='error-text'>Password Successfully Changed.</div>";
		}
		//if($exitType != 4){
		echo "<a class='error-text' href='index.html'><center>Click here to return Inmers Homepage</center></a>";
        //}
		
      echo "</div>
     </div>
    
 
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src='js/jquery.min.js'></script>
    <script src='js/jquery-ui.min.js'></script>
   
    <script src='js/bootstrap.min.js'></script>
   
    <script src='js/google-code-prettify/prettify.js'></script>
   
    
    <script src='js/chosen.jquery.min.js'></script>
    
    <script src='js/jquery.jscrollpane.min.js'></script>
    
    <script src='js/jquery.validate.min.js'></script>
    
  </body>
</html>
";
}
}
?>
