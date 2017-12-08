<?php
require("../environment_detail.php");
require("../PasswordHash.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="DOCTORS"; // Table name

$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

$name=$_POST['Nombre'];
//$IdMed=$_POST['id'];
$queModo = 1;

$confirm_code=md5(uniqid(rand()));

//Changes for adding encryption
$hashresult = explode(":", create_hash($_POST['Password']));
$IdUsRESERV= $hashresult[3];
$additional_string=$hashresult[2];

//$IdMEDRESERV=create_hash($_POST['Password']);
/*echo $hashresult;
echo "<br>";
echo $IdMEDRESERV;
echo "<br>";
echo $additional_string;*/

$q = mysql_query("Update Usuarios SET IdUsRESERV ='$IdUsRESERV',salt='$additional_string' where IdUsFIXEDNAME='$name'"); 


?>
<!DOCTYPE html> 
<html> 
<head> 
	<title>Health2me Password Reset</title> 
	
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
		
    <p>Your Password has been reset.</p>
	
	<p> It is important to verify your account before you start using Health2me. Please check your email and verify the account</p>
	
	<p> It is important to remember that in our endeavour to provide best security,Health2me use one way password hashing. That means password cannot be retrieved by anyone.</p>
	<p> If user forgets the password, it could only be reset using one-step verification process</p>
	
	<p> Please click on login tab below to login </p> 
	
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