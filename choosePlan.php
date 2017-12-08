<?php
session_start();
if(isset($_SESSION['Acceso']))
    unset($_SESSION['Acceso']); 

?>


<!DOCTYPE html>
<html lang="en"  class="body-error"><head>
    <meta charset="utf-8">
    <title>Inmers - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/login.css" rel="stylesheet">
    <?php
        if ($_COOKIE["inmers_hti"]=="col") {
    ?>
        <link href="css/loginCol.css" rel="stylesheet">
    <? } ?>

    <link href="css/bootstrap.css" rel="stylesheet">

	<!--<link rel="stylesheet" href="css/icon/font-awesome.css">-->
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" type="text/css" href="css/tooltipster.css" />
    <link rel="stylesheet" href="build/css/intlTelInput.css">

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

<script type="text/javascript" src="js/zopimchat.js"></script>
<!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
/*window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//v2.zopim.com/?2MtPbkSnwlPlIVIVYQMfPNXnx6bGJ0Rj';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');*/
</script>
<!--End of Zopim Live Chat Script-->  
    
    
  </head>

  <body>
	<input type="hidden" name="uid"/>
	<input type="hidden" name="pass"/>
     	  <!--- VENTANA MODAL  ---> 
   	  <!--<button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>-->
      <div id="error_bar" style="position: relative;width:100%;height:53px;color:white;background-color: rgb(232, 58, 60);display:none"><div id="error_msg" style="position: absolute;margin-top:15px;margin-left:440px ">the error message</div>
        <div id="close_error_bar" style="margin-left:1200px;">
            <i class="icon-remove-circle icon-4x"></i>
       </div></div>
   	  

    <style>
    li{
        color: #54BC02;
        margin-bottom: 12px;
    }
    </style>
    <div style="width: 1000px; height: 580px; margin:auto; background-color: #FFF; border: 1px solid #BBB; border-radius: 5px; margin-top: 20px; text-align: center; font-size: 24px;">
        <br/><span style="color: #54BC02">Choose a Plan</span><br/>
        <div style="width: 29%; height: 460px; margin-top: 50px; margin-left: 3%; float: left; background-color: #F8F8F8; border-radius: 5px; border: 1px solid #DBDBDB; cursor: pointer;">
            <div style="width: 100%; height: 40px; padding-top: 15px; background-color: #54BC02; border-top-left-radius: 5px; border-top-right-radius: 5px; color: #FFF; text-align: center; font-size: 24px;">
                Free
            </div>
            <ul style="font-size: 14px; margin-top: 20px; text-align: left; margin-bottom: 30px;">
                <li><span style="color: #666;">Up to 10 report uploads</span></li>
                <li><span style="color: #666;">Up to 3 send and upload requests</span></li>
                <li><span style="color: #666;">No mobile messaging app</span></li>
                <li><span style="color: #666;">No access to instant consultations</span></li>
                <li><span style="color: #666;">No capability to edit report metatags</span></li>
                <li><span style="color: #666;">Single user only</span></li>
            </ul>
            <h4 style="text-align: left; margin-left: 12px;">GP Consultations: <span style="color:#54BC02;">$40</span></h4>
            <h4 style="text-align: left; margin-left: 12px;">Specialist Consultations: <span style="color:#54BC02;">$70</span></h4>
            <div style="width: 90%; height: 40px; padding-top: 15px; margin: auto; margin-top: 55px; background-color: #54BC02; border-radius: 5px; color: #FFF; text-align: center; font-size: 18px;">
                FREE
            </div>
        </div>
        <div style="width: 29%; height: 460px; margin-top: 50px; margin-left: 3%; float: left; background-color: #F8F8F8; border-radius: 5px; border: 1px solid #DBDBDB; cursor: pointer;">
            <div style="width: 100%; height: 40px; padding-top: 15px; background-color: #54BC02; border-top-left-radius: 5px; border-top-right-radius: 5px; color: #FFF; text-align: center; font-size: 24px;">
                Premium
            </div>
            <ul style="font-size: 14px; margin-top: 20px; text-align: left; margin-bottom: 30px;">
                <li><span style="color: #666;">Unlimited report uploads</span></li>
                <li><span style="color: #666;">Unlimited send and upload requests</span></li>
                <li><span style="color: #666;">Access to mobile messaging app</span></li>
                <li><span style="color: #666;">Access to instant consultations</span></li>
                <li><span style="color: #666;">Capability to edit report metatags</span></li>
                <li><span style="color: #666;">Single user only</span></li>
            </ul>
            <h4 style="text-align: left; margin-left: 12px;">GP Consultations: <span style="color:#54BC02;">$20</span></h4>
            <h4 style="text-align: left; margin-left: 12px;">Specialist Consultations: <span style="color:#54BC02;">$35</span></h4>
            <div style="width: 90%; height: 40px; padding-top: 15px; margin: auto; margin-top: 55px; background-color: #54BC02; border-radius: 5px; color: #FFF; text-align: center; font-size: 18px;">
                $8 / Month
            </div>
        </div>
        <div style="width: 29%; height: 460px; margin-top: 50px; margin-left: 3%; float: left; background-color: #F8F8F8; border-radius: 5px; border: 1px solid #DBDBDB; cursor: pointer;">
            <div style="width: 100%; height: 40px; padding-top: 15px; background-color: #54BC02; border-top-left-radius: 5px; border-top-right-radius: 5px; color: #FFF; text-align: center; font-size: 24px;">
                Family
            </div>
            <ul style="font-size: 14px; margin-top: 20px; text-align: left; margin-bottom: 30px;">
                <li><span style="color: #666;">Unlimited report uploads</span></li>
                <li><span style="color: #666;">Unlimited send and upload requests</span></li>
                <li><span style="color: #666;">Access to mobile messaging app</span></li>
                <li><span style="color: #666;">Access to instant consultations</span></li>
                <li><span style="color: #666;">Capability to edit report metatags</span></li>
                <li><span style="color: #666;">Multiple users for a single family</span></li>
            </ul>
            <h4 style="text-align: left; margin-left: 12px;">GP Consultations: <span style="color:#54BC02;">$20</span></h4>
            <h4 style="text-align: left; margin-left: 12px;">Specialist Consultations: <span style="color:#54BC02;">$35</span></h4>
            <div style="width: 90%; height: 40px; padding-top: 15px; margin: auto; margin-top: 55px; background-color: #54BC02; border-radius: 5px; color: #FFF; text-align: center; font-size: 18px;">
                $14 / Month
            </div>
        </div>
    </div>  
    
   
    
    

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>

    <script src="js/jquery-ui.min.js"></script>
   
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/bootstrap-colorpicker.js"></script>
    <script src="js/google-code-prettify/prettify.js"></script>
    <script src="js/jquery.cookie.js"></script>
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

    <script src="build/js/intlTelInput.js"></script>
    <script src="js/isValidNumber.js"></script>

<!--    <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.0.min.js"></script>-->
    <script type="text/javascript" src="js/jquery.tooltipster.js"></script>
    <script type="text/javascript" src="js/signIn.min.js"></script>
  </body>
</html>
</html>