<?php
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
    <Say language="en" voice="woman">Health 2</Say>
    <Say language="en" voice="woman"></Say>
    <Say language="en" voice="woman"></Say>
    
    <Gather action="handle-user-inputPAT.php?IdRef=<?php echo $IdRef?>" numDigits="1">
        <Say language="en" voice="man" >Hello,  <?php echo ''; echo $NamePatient; ?>.</Say>
        <Say language="en" voice="man" >This is a Health2Me response center.</Say>
        <Say language="en" voice="man">If you want more information go to: </Say>
        <Say language="en" voice="man">Or send an email to admin@inmers.us.</Say>
        <Say language="en" voice="man"></Say>
    </Gather>

    <Say language="en" voice="woman">Thank you!.</Say>
    <Say language="en" voice="woman">Gretings from Health 2 Me.</Say>
    <Say language="en" voice="woman">Bye!</Say>
</Response>
