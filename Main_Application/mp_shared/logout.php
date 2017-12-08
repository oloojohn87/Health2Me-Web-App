<?php
session_start();
ini_set("display_errors", 0);
require("../environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

// if logging out to swtich family member, the session should not be destroyed
$destroy_session = 1;
if(isset($_POST['logging_out_family_member']) && $_POST['logging_out_family_member'] == true)
{
    $destroy_session = 0;
}

if(isset($_SESSION['MEDID']) && $destroy_session == 1){
$query = $con->prepare("select * from ongoing_sessions where userid=?");
$query->bindValue(1, $_SESSION['MEDID'], PDO::PARAM_INT);


$result=$query->execute();
$count=$query->rowCount();
if($count==1)
{
	shell_exec("rm -r  temp/".$_SESSION['MEDID']);
}

$query = $con->prepare("delete from ongoing_sessions where userid=? and ip=?");
$query->bindValue(1, $_SESSION['MEDID'], PDO::PARAM_INT);
$query->bindValue(2, $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
$query->execute();
}

if($destroy_session == 1)
{
    unset($_SESSION['Acceso']); 
}
//unset($_SESSION['decrypt']);

if(isset($_SESSION['UserID']) && $destroy_session == 1){
//unset($_SESSION['UserID']);
session_destroy();
}
if(!isset($_SESSION['CustomLook'])){
$_SESSION['CustomLook'] = '';
}
?>

<!DOCTYPE html>
<html lang="en"  class="body-error"><head>
    <meta charset="utf-8">
    <title>health2.me</title>
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="../../css/style.css" rel="stylesheet">
    <link href="../../css/bootstrap.css" rel="stylesheet">
    
    <link rel="apple-touch-icon" href="../../images/icon.png"/>
    
    <link rel="stylesheet" href="../../css/jquery-ui-1.8.16.custom.css" media="screen"  />
    <link rel="stylesheet" href="../../css/fullcalendar.css" media="screen"  />
    <link rel="stylesheet" href="../../css/chosen.css" media="screen"  />
    <link rel="stylesheet" href="../../css/datepicker.css" >
    <link rel="stylesheet" href="../../css/colorpicker.css">
    <link rel="stylesheet" href="../../css/glisse.css?1.css">
    <link rel="stylesheet" href="../../css/jquery.jgrowl.css">
    <link rel="stylesheet" href="../../js/elfinder/css/elfinder.css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.tagsinput.css" />
    <link rel="stylesheet" href="../../css/demo_table.css" >
    <link rel="stylesheet" href="../../css/jquery.jscrollpane.css" >
    <link rel="stylesheet" href="../../css/validationEngine.jquery.css">
    <link rel="stylesheet" href="../../css/jquery.stepy.css" />
    
	<link rel="stylesheet" href="../../css/icon/font-awesome.css">
    <link rel="stylesheet" href="../../css/bootstrap-responsive.css">
    <?php
    if ($_SESSION['CustomLook']=="COL") { ?>
        <link href="../../css/styleCol.css" rel="stylesheet">
    <?php } ?>


    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../../images/icons/favicon.ico">
    
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
    		
           <?php
			if(isset($_GET['access']) && $_GET['access'] == 'hti-riva'){
				echo "<a href='index-col.html?access=riva' style='background-image:url(images/RivaCare_Logo.png);display:block;width:325px;height:42px;float:left;'></a>";
			}elseif(isset($_GET['access']) && $_GET['access'] == 'hti-24x7'){
				echo "<a href='index-col.html?access=24x7' style='background-image:url(http://24x7hellodoctor.com/img/logo-24x7-hellodoctor.jpg);background-size: 250px 42px;background-repeat: no-repeat;display:block;width:255px;height:42px;float:left;'></a>";
			}else{
				echo '<a href="index-col.html" class="logo"><h1>Health2me</h1></a>';
			}
		  ?>
           
           <div class="pull-right">
           
         
          
          </div>
    </div>
    <!--Header END-->
    

     
     <div class="error-bg">
      <div class="error-s">
        <!--<div class="error-number">Health2me</div>-->
        <?php
            if ($_SESSION['CustomLook']=="COL" && $_GET['access'] != 'hti-riva'  && $_GET['access'] != 'hti-24x7') {
            echo '<div class="error-number"><img src="../../images/llamaaldoctor_trans.png" width="350" /img></div>
            <div class="error-number" style="font-size:20px; margin-top:15px;">PDMS</div>
        
		<div class="error-text">You have been logged out successfully !!</div>
		<a class="error-text" href="index-col.html" style="color: #2eb82e; text-decoration: underline;"><center>Click here to return to Homepage</center></a>';
	    } elseif(isset($_GET['access']) && $_GET['access'] == 'hti-riva'){
		echo '<div class="error-number"><img src="../../images/RivaCare_Logo.png" width="350" /img></div>
            <div class="error-number" style="font-size:20px; margin-top:15px;">unlocking health</div>

            <div class="error-text">version 1.0.2</div>
		  <div class="error-text">You have been logged out successfully !!</div>
		  <a class="error-text" href="http://www.rivacare.com" style="color: #2eb82e; text-decoration: underline;"><center>Click here to return to RivaCare Homepage</center></a>';
	    }elseif(isset($_GET['access']) && $_GET['access'] == 'hti-24x7'){
		echo '<div class="error-number"><img style="margin-left:-30px;" src="../../images/logo-24x7-hellodoctor.png" width="350" /img></div>
            <div class="error-number" style="font-size:20px; margin-top:15px;">unlocking health</div>

            <div class="error-text">version 1.0.2</div>
		  <div class="error-text">You have been logged out successfully !!</div>
		  <a class="error-text" href="http://24x7hellodoctor.com/" style="color: #2eb82e; text-decoration: underline;"><center>Click here to return to 24X7 Homepage</center></a>';

	    }else {
            echo '<div class="error-number"><img src="../../images/health2meLOGO.png" width="350" /img></div>
            <div class="error-number" style="font-size:20px; margin-top:15px;">unlocking health</div>

            <div class="error-text">version 1.0.2</div>
		  <div class="error-text">You have been logged out successfully !!</div>
		  <a class="error-text" href="../index.html" style="color: #2eb82e; text-decoration: underline;"><center>Click here to return to Health2.me Homepage</center></a>';
	   } ?>
        
        
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

<!-- Comment by Ankit -->
<!-- Comment Added by Debraj -->
<!-- Comment By Raswitha -->
