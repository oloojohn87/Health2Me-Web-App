<?php
require("environment_detailForLogin.php");
		
		//SET DB CONNECTION/
		$dbhost = $env_var_db["dbhost"];
		$dbname = $env_var_db["dbname"];
		$dbuser = $env_var_db["dbuser"];
		$dbpass = $env_var_db["dbpass"];
		
		$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}
		
$email = $_GET['email'];
$key = $_GET['key'];

$query = $con->prepare("SELECT * FROM key_chain WHERE key_hash = ? && type='confirm' && maker_type='doctor' ");
$query->bindValue(1, $key, PDO::PARAM_STR);
$result = $query->execute();
$count = $query->rowCount();

$row = $query->fetch(PDO::FETCH_ASSOC);

if($count == 1){
	$query2 = $con->prepare("SELECT * FROM doctors WHERE id = ?");
	$query2->bindValue(1, $row['maker'], PDO::PARAM_STR);
	$result2 = $query2->execute();
	$count2 = $query2->rowCount();
	
	$row2 = $query2->fetch(PDO::FETCH_ASSOC);
	
	if($count2 == 1){
		$query3 = $con->prepare("SELECT * FROM usuarios WHERE email = ?");
		$query3->bindValue(1, $email, PDO::PARAM_STR);
		$result3 = $query3->execute();
		$count3 = $query3->rowCount();
	
		$row3 = $query3->fetch(PDO::FETCH_ASSOC);
		
		if($count3 == 1){
			$query4 = $con->prepare("SELECT * FROM doctorslinkusers WHERE idmed=? && idus=? && idpin IS NULL");
			$query4->bindValue(1, $row2['id'], PDO::PARAM_INT);
			$query4->bindValue(2, $row3['Identif'], PDO::PARAM_INT);
			$result4 = $query4->execute();
			$count4 = $query4->rowCount();
			
			if($count4 == 0){
				$query5 = $con->prepare("insert into doctorslinkusers(idmed,idus,fecha,idpin,estado,confirm,tipo) values(?,?,now(),NULL,2,?,1)");
				$query5->bindValue(1, $row2['id'], PDO::PARAM_INT);
				$query5->bindValue(2, $row3['Identif'], PDO::PARAM_INT);
				$query5->bindValue(3, 'Created by Dr. Id= '.$row2['id'].'', PDO::PARAM_STR);
				$result5 = $query5->execute();
				if($result5){
					echo '<!DOCTYPE html>
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


						<!-- Le fav and touch icons -->
						<link rel="shortcut icon" href="images/icons/favicon.ico">
						
						<script type="text/javascript">

					  var _gaq = _gaq || [];
					  _gaq.push(["_setAccount", "UA-37863944-1"]);
					  _gaq.push(["_setDomainName", "health2.me"]);
					  _gaq.push(["_trackPageview"]);

					  (function() {
						var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true;
						ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
						var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga, s);
					  })();

					</script>
						
					  </head>

					  <body>


						<!--Header Start-->
						<div class="header" >
								
							   <a href="#" class="logo"><h1></h1></a>
							   
							   <div class="pull-right">
							   
							 
							  
							  </div>
						</div>
						<!--Header END-->
						

						 
						 <div class="error-bg">
						  <div class="error-s">
						   
							<div class="error-number"><img src="images/health2meLOGO.png" width="150" /img></div>
							<div class="error-number" style="font-size:20px; margin-top:15px;">unlocking health</div>
							<div class="error-text">
							<p>Dr. '.$row2['Name'].' '.$row2['Surname'].' now has access to your patient records. </p> 
							
									<p><a href="SignIn.html">Please Login to view patient details.</a></p>
							</div>
							<div class="error-text"></div>
						   
							<!--<div class="error-number" style="font-size:20px; margin-top:15px;">social health networking</div>-->
						   
							
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
					';
				}
			}else{
				echo "DLU already exists";
			}
		}else{
			die('This email is not attached to a user.');
		}
	}else{
		die('This doctor key no longer works.');
	}
}else{
	die('This confirmation key does not exist.');
}


?>