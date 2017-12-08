<?php
session_start();
 require("../environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$NombreEnt = $_SESSION['Nombre'];
$PasswordEnt = $_SESSION['Password'];
$USERID = $_SESSION['USERID'];
$Acceso = $_SESSION['Acceso'];
//$privilege=$_SESSION['Previlege'];
if ($Acceso != '23432')
{
echo "Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."/mobapp/Index.html'>Return to Health2me Dashboard</a></h2>";
die;
}

					// Connect to server and select databse.
$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

$result = mysql_query("SELECT * FROM Usuarios where identif='$USERID'");
$count=mysql_num_rows($result);
$row = mysql_fetch_array($result);
$success ='NO';
if($count==1){
	$success ='SI';
	$USERID = $row['identif'];
	$UserEmail= $row['email'];
	$UserName = $row['Name'];
	$UserSurname = $row['Surname'];
	//$MedUserLogo = $row['ImageLogo'];
	$IdUsFIXED = $row['IdUsFIXED'];
	$IdUsFIXEDNAME = $row['IdUsFIXEDNAME'];
	
}
else
{
echo "USER NOT VALID. Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."/mobapp/Index.html'>Return to Health2me Dashboard</a></h2>";
die;
}


//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM BLOCKS");
$report_result = mysql_query("SELECT * FROM LifePin where IdUsu='$USERID'");
$report_count=mysql_num_rows($report_result);

?>
<!DOCTYPE html> 
<html> 
<head> 
	<title>Page Title</title> 
	
	<meta name="viewport" content="width=device-width, initial-scale=1"> 

	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
	 <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
	 
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
			
			var connectiondetails='';
			
			getConnectedDoctor();
			for (var i = 0, len = doctor.length; i < len; ++i) {
				connectiondetails=connectiondetails+'<li>Connected to Dr.'+doctor[i].Name+' '+doctor[i].Surname+'</li>';			
			}
	
			$("#dlu_connection").html(connectiondetails);			

			function getConnectedDoctor() {
			var cadenaGUD = '<?php echo $domain;?>/mobapp/getConnectedDoctors_mob.php?MEDID=<?php echo $MedID?>';
			//alert (cadenaGUD);
			$.ajax(
				   {
				   url: cadenaGUD,
				   dataType: "json",
				   async: false,
				   success: function(data)
				   {
				   //alert ('success');
				   doctor = data.items;
				   }
				   });
			}
			
            //apply overrides here
        });
    </script>

</head> 
<body> 

<div data-role="page" data-theme="d" id="home">

	<div data-role="header" data-theme="a">
		<h1><img src="Icono_H2M.png" alt="" ></h1>
	</div><!-- /header -->

	<div data-role="content" data-theme="d" >	
		
    <p id="output"><b>Welcome <?php echo $UserName;?> <?php echo $UserSurname; ?></p>
	
	<p style="float:left"><b>Member Since XXXXX-XX</b></p>
	<p style="float:left"><b> You have total <?php echo $report_count; ?> reports</p>
	<p style="float:right;margin-right:20px"><i class="icon-user-md icon-4x"></i><br><span style="margin-left:8px"><b>CSA</b></span></p>
	
    <p style="margin-top:50%"><ul id="dlu_connection"></ul></p>
	
	<div class="ui-grid-a" style="margin-top:10%">
			<div class="ui-block-a"><div class="ui-bar ui-bar-c">
			<!--<label for="basic">Name:</label>-->
			<input type="button" value="print" data-theme="a" data-mini="true">
			</div></div>
			<div class="ui-block-b"><div class="ui-bar ui-bar-c" >
			<!--<label for="basic">Surname:</label>-->
			<input type="button" value="Send" data-theme="a" data-mini="true">
			</div></div>
	</div>
	   
	</div>
	
	<div data-role="footer" data-id="foo1" data-position="fixed">
	<div data-role="navbar">
		<ul>
			<li><a href="#">Home</a></li>
			<li><a href="#">Capture</a></li>
			<li><a href="Report_mob.php" data-ajax="false">Reports</a></li>					
		</ul> 
			</div><!-- /navbar -->
		</div><!-- /footer -->
	</div><!-- /page -->
</body>
</html>
