<?php
/*require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
$NombreEnt = $_GET['Nombre'];
$PasswordEnt = $_GET['Password'];
if ($PasswordEnt != 'a' && $PasswordEnt != 'Houston333')
{
echo "Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='". $domain."'>Return to Inmers HomePage</a></h2>";
die;
}

					
					// Connect to server and select databse.
$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");


// BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks");
$result = mysql_query("SELECT * FROM lifepin");

?>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Inmers Dashboard (BETA)</title>  
    
    <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script src="/js/jquery.dataTables.js" type="text/javascript"></script>
       
    <link rel="stylesheet" type="text/css" href="css/estilosbasicos.css" />  
    <link rel="stylesheet" type="text/css" href="css/demo_table_jui.css" />  
    <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.4.custom.css" /> 
    <script type="text/javascript" >
    
    $(document).ready(function() {
    

    $('.PH').click(function () {

	    window.location.href = "<?php echo $domain;?>/index.html";
    
    });
    
    $('#datatables').dataTable({
                    "sPaginationType":"full_numbers",
                    "aaSorting":[[3, "desc"]],
                    "bJQueryUI":true
                });
    
    });        
            
    			 setInterval(function() {
						
						location.reload();
   				 		//$('#loaddiv').fadeOut('slow').load('reload.php').fadeIn("slow");
   				 		
   				 		}, 10000);  
   				 		
	</script>
	
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
	
	<body style="background-color: #ececec;">
		<div class="BannerSup">
			<img src="inmerslogoPQ.png" width="150" style="left:15%; top:20px; position:relative;"/>
			
			<!--
			<p style="position:relative; margin-left:20%; top:12%; font-size:24px; font-family:Palatino;">
		       <span style="color:white; text-shadow: 2px 2px 2px #000000;">health2.me</span>
	    	</p>
	    	-->
	    	<div class="PH" style="left:850px;" />
     	</div>
		
		<div class="ContenedorMid">
		
		<div class="BannerMid">
	
		
		<div id="loaddiv">
		<table id="datatables" class="display" style="font-family: arial; font-size:12px;">
                <thead>
                    <tr>
                        <th>Origin</th>
                        <th>User Id</th>
                        <th>User Id NAME</th>
                        <th>Date</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Action</th>
                        
                   </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysql_fetch_array($result)) {
                        ?>
                        <tr>
                            <td><?=$row['IdMEDEmail']?></td>
                            <td><?=$row['IdUsFIXED']?></td>
                            <td><?=$row['IdUsFIXEDNAME']?></td>
                            <td><?=$row['FechaEmail']?></td>
                            <td><?=$row['RawImage']?></td>
                            <td><?=$row['ValidationStatus']?></td>                            
                            <td><?=$row['NextAction']?></td>
                         </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
		</div>    


		</div>
		
		<!--
		<div style="height:480px; width:50%; background-color:grey; overflow:scroll; color:white; margin-top:10px; margin-left:20px; float:right;">
			<p id="Consola" style="margin-left:10%; margin-top:; font-size:12px; font-family:Verdana; ">

	    	</p>
     	</div>
     	-->	
<a href="http://www.site24x7.com/login/status.do?execute=StatusReport&u=true&p=%2BihkYQtlxO2XIdMQdMp%2B3OD1N12FiMnoo3NMIyk2Atn2ibJ3WfccdQ%3D%3D" style="color:#FFFFFF;text-decoration:none;cursor:pointer;"><span style="text-align:center;display:inline-block;text-transform:0px;text-indent:2px;background-color:#FF6600;font-weight:bold;line-height:12px;margin:0px;padding:0px;border:1px solid #E84B00;  font-family:Verdana; font-size:9px;"><b>Website Uptime</b><b style="background-color:#FFFFFF; color:#000000;padding:0px 2px 0px 3px;"><script type="text/javascript" src="http://ext1.site24x7.com/website-uptime.html?v=%2BihkYQtlxO1gfnrJt4GksnOqpbormAO5"></script></b></span></a>
     		
		</div>	
      	<div class="BannerInf">
	
     	</div>
     	
     	
     	

	</body>
</html>*/