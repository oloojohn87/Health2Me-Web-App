<?php
	ini_set("display_errors",0);
	require("environment_detailForLogin.php");
	
    //html PNG location prefix
	include "lib/classes/infographics.php";  

    
	$basefilename = 'test.png';
	$qrdata = $domain."/emergency.php?id=2094";
     
	$qrcode = new H2M_Qrcode();
	$qrcodeResults = $qrcode->create($basefilename, $qrdata);
	echo $qrcodeResults;
	
?>	