<?php
    require("environment_detail.php");
    $dbhost = $env_var_db['dbhost'];
    $dbname = $env_var_db['dbname'];
    $dbuser = $env_var_db['dbuser'];
    $dbpass = $env_var_db['dbpass'];

    $con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    if (!$con)
    {
        die('Could not connect: ' . mysql_error());
    }

    $email = '';
    $number_id = '';
    $name_id = '';
    if(isset($_GET['name']) && isset($_GET['number']) && isset($_GET['email']))
    {
        $email = $_GET['email'];
        $number_id = $_GET['number'];
        $name_id = $_GET['name'];
        /*$id = $_GET['user'];
        $result = $con->prepare("SELECT * FROM usuarios WHERE Identif = ?");
        $result->bindValue(1, $id, PDO::PARAM_INT);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $email = $row['email'];
        $number_id = $row['IdUsFIXED'];
        $name_id = $row['Alias'];*/
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
    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    
    <link rel="apple-touch-icon" href="images/icon.png"/>
    
	<link rel="stylesheet" href="css/icon/font-awesome.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">



    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/icons/favicon.ico">
    
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
    		
           <a href="index.html" class="logo"><h1>I</h1></a>
           
           <div class="pull-right">
           
         
          
          </div>
    </div>
    <!--Header END-->
    

     
    <div class="error-bg" id="main_menu">
        <div class="error-s">
        <!--<div class="error-number">Health2me</div>-->
            <div style="width: 500px; height: 300px; border-radius: 15px; padding: 25px; background-color: #FFF;">
                <div style="text-align: center; font-size: 24px; color: #54BC02; height: 30px;">Member Account Created!</div>
                <div style="text-align: left; font-size: 16px; color: #777; margin-top: 10px; height: 80px;">
                    You will receive an email with a confirmation link to the email address you used to create the account. Please follow the link to complete the sign up process.
                </div>
                <div style="text-align: left; font-size: 16px; color: #777; margin-top: 10px; height: 130px;">
                    <div style="width: 40%; float: left;">Your email:</div> <div style="width: 60%; float: left; color: #54BC02"><?php echo $email; ?></div>
                    <div style="width: 40%; float: left;">Your number ID:</div> <div style="width: 60%; float: left; color: #54BC02"><?php echo $number_id; ?></div>
                    <div style="width: 40%; float: left;">Your name ID:</div> <div style="width: 60%; float: left; color: #54BC02"><?php echo $name_id; ?></div>
                </div>
                
                <div style="width: 40%; height: 40px; margin: auto;">
                    <button onclick="window.location='SignInUSER.php';" style="background-color: #54BC02; width: 100%; height: 40px; border-radius: 5px; color: #FFF; outline: 0px; border: 0px solid #FFF; font-size: 18px;">Sign In</button>
                </div>
            </div>
        </div>
    </div>

    
 


  </body>
</html>

<!-- Comment by Ankit -->
<!-- Comment Added by Debraj -->
<!-- Comment By Raswitha -->
