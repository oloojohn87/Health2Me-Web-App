<?php
//    header("content-type: text/xml");
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    $IdRef = $_GET['IdRef'];	
    $NameDoctor = $_GET['NameDoctor'];
    $SurnameDoctor = $_GET['SurnameDoctor'];
	$NamePatient = $_GET['NamePatient'];
	$SurnamePatient = $_GET['SurnamePatient'];
    $IdProtocolo = 1;



?>
<Response>
    <Say language="en" voice="woman">Health 2 Me Clinical Support Center</Say>
    <Say language="en" voice="woman"></Say>
    <Say language="en" voice="woman"></Say>
    
    <Gather action="handle-user-feedback-input.php?IdRef=<?php echo $IdRef?>" numDigits="1">
        <Say language="en" voice="man" >Hello,  <?php echo $NamePatient.' '.$SurnamePatient ?>.</Say>
        <Say language="en" voice="man" >This is a feedback call  from  Health 2 Me to know the status of your health.</Say>
        <Say language="en" voice="man" ><?php echo 'Doctor '; echo $NameDoctor.' '.$SurnameDoctor?> wants to know how you feel so that he can treat you better.</Say>
		<Say language="en" voice="man">If you feel Very Bad, press 1.</Say>
		<Say language="en" voice="man">If you feel Bad, press 2.</Say>
		<Say language="en" voice="man">If you feel Normal, press 3.</Say>
		<Say language="en" voice="man">If you feel Good, press 4.</Say>
		<Say language="en" voice="man">If you feel Very Good, press 5.</Say>
		<Say language="en" voice="man">If you wish to stop receiving phone calls, press 7</Say>
		<Say language="en" voice="man">To repeat options, press 8</Say>
		
    </Gather>

    <Say language="en" voice="man">Sorry, I didn't get your response.</Say>
	<Say></Say>
	<Redirect method="GET">phoneoptions.php?id=<?php echo $IdRef;?></Redirect>
    
</Response>