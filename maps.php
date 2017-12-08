<?php
$api_key="a5abdd8cdf06658ec19a37f85f594ab70f3c84e881fce3b63aa73d05052745ab"; // COMPLETE THIS WITH YOUR IPINFODB API KEY
$google_maps_api_key="AIzaSyBTZeRhmx1SaJl_n7kkI_2H2KSiqOy3SHQ"; // COMPLETE THIS WITH YOUR GOOGLE MAPS API KEY




$ip = $_GET['ipaddress'];

$xml = simplexml_load_file('http://api.ipinfodb.com/v2/ip_query.php?key='.$api_key.'&ip='.$ip.'&timezone=true');

$ip = $xml->Ip;
$status = $xml->Status;
$country = $xml->CountryName;
$region = $xml->RegionName;
$city = $xml->City;
$latitude = $xml->Latitude;
$longitude = $xml->Longitude;
$timezone = $xml->TimezoneName;

if($city=="")
	$city="Not Found";
	
if($region=="")
	$region="Not Found";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    
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
    <link rel="stylesheet" href="css/jvInmers.css">

    <link rel="stylesheet" type="text/css" href="css/tooltipster.css" />
    
<head>
	<script src="http://maps.google.com/maps?file=api&v=2&key=<?php echo $google_maps_api_key; ?>" type="text/javascript"></script>
	<script src="TypeWatch/jquery.typewatch.js"></script>
	<script type="text/javascript" >
	/*var timeoutTime = 3000;
	var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);

	function ShowTimeOutWarning()
	{
	alert ('Session expired');
	//window.location = 'timeout.html';
	return;
	}

	$(document).ready(function() {

    
        clearTimeout(timeoutTimer);
        timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
    });*/
	</script>
</head>
<body>
	<div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <div id="InfB" >
	                 	<h5>Location</h5>
                 </div>
    </div>
	<div align="center">
		<div align="center" style="width:470px;padding-top:20px;">
		<table width="50%" border="0" cellspacing="0" cellpadding="0" style="margin-top:5px;">
		 <tr>
 			<td width="58%" style="padding-right:10px;"><div align="right">Ip Address :</div></td>
 			<td width="42%"><?php echo $ip; ?></td>
 		</tr>
 		<tr>
 			<td style="padding-right:10px;"><div align="right">Country :</div></td>
 			<td><?php echo $country; ?></td>
 		</tr>
		<tr>
 			<td style="padding-right:10px;"><div align="right">Region :</div></td>
 			<td><?php echo $region; ?></td>
 		</tr>
 		<tr>
 			<td style="padding-right:10px;"><div align="right">City :</div></td>
 			<td><?php echo $city; ?></td>
 		</tr>
 		</table>
	</div>
	<div id="map" style="width: 400px; height: 250px; margin-top:10px;"></div>
		<script type="text/javascript">
		//alert("here");
			var map = new GMap(document.getElementById("map"));
			var point = new GPoint(<?php echo $longitude; ?>,<?php echo $latitude; ?>);
			map.centerAndZoom(point, 3);
			var marker = new GMarker(point);
			map.addOverlay(marker);
		//clearTimeout(timeoutTimer);	
		//alert("done");
		</script>
	</div>
	<div class="modal-footer">
	         <!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">--->
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal22">Close</a>
    </div>
	
</body>
</html>
