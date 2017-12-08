<?php

$IdRef = $_GET['id'];
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>


<Response>
<Gather action="handle-user-feedback-input.php?IdRef=<?php echo $IdRef?>" numDigits="1">
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

