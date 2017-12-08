<?php 
if($_GET['grant_access'] == 'HTI-COL'){
echo '<?xml version="1.0" encoding="UTF-8" ?>' ?>
<Response>
    <Redirect><?php echo 'twiML_temp/'.$_GET['conference_id'].'.php?grant_access=hti' ; ?></Redirect>
</Response>
<?php
}else if($_GET['grant_access'] == 'HTI-RIVA'){
echo '<?xml version="1.0" encoding="UTF-8" ?>' ?>
<Response>
    <Redirect><?php echo 'twiML_temp/'.$_GET['conference_id'].'.php?grant_access=riva' ; ?></Redirect>
</Response>
<?php
}else{
echo '<?xml version="1.0" encoding="UTF-8" ?>' ?>
<Response>
    <Gather <?php echo 'action="twiML_temp/'.$_GET['conference_id'].'.php"' ; ?> timeout="6" numDigits="1">
        <Say><?php echo urldecode($_GET['pat_name']); ?> would like to start a phone consultation with you. Would you like to accept? Please press any digit to accept the consultation.</Say><!-- Changing this to ask for digit 2 to identify as doctor -->
    </Gather>
    <Say>Consultation denied. Good bye!</Say>
    <Redirect><?php echo 'twiML_temp/'.$_GET['conference_id'].'.php' ; ?></Redirect>
</Response>
<?php } ?>
