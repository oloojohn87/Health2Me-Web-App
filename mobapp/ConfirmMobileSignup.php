<?php
define('INCLUDE_CHECK',1);
require "../logger.php";
 

$CodigoBusca = $_GET['token'];

require("../environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
$tbl_name="DOCTORS"; // Table name
					
// Connect to server and select databse.
$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

$ip = $_SERVER['REMOTE_ADDR'];
//echo "<br> Your IP address : " . $ip;


$q = mysql_query("UPDATE Usuarios SET FechaVer = NOW(), Verificado=1 ,confirmcode='********', IPVALID='$ip' WHERE confirmcode='$CodigoBusca' ");

/*
echo 'CODIGO = ';
echo $CodigoBusca;
echo "CONFIRMATION";*/

//$salto='location:SignIn.html';

//header($salto);
?>
<!DOCTYPE html> 
<html> 
<head> 
	<title>Health2me Account Confirmation</title> 
	
	<meta name="viewport" content="width=device-width, initial-scale=1"> 

	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
	<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
    <style type="text/css" media="screen">
        .ui-page { -webkit-backface-visibility: hidden; }
    </style>
        <script type="text/javascript">
            
        $(document).bind("pageinit", function(){
            $("#id").click(function(event) {
                alert ('click');
            });    
            //apply overrides here
        });
    </script>

</head> 
<body> 

<div data-role="page" data-theme="d">

	<div data-role="header" data-theme="a">
		<h1><img src="Icono_H2M.png" alt="" ></h1>
	</div><!-- /header -->

	<div data-role="content" data-theme="d" >	
		
    <p><b>Thank You for the confirmation.</p>
	
	<p><b> Your IP address <?php echo $ip; ?> has been capture for security reasons.</p>

	<p> Please click on the login tab below to sign in from your mobile device. </p>
   
	</div>
	
	<div data-role="footer" data-id="foo1" data-position="fixed">
	<div data-role="navbar">
		<ul>
			<li><a href="signIn.html" data-ajax="false">Login</a></li>								
		</ul> 
			</div><!-- /navbar -->
		</div><!-- /footer -->
	</div><!-- /page -->
</body>
</html>