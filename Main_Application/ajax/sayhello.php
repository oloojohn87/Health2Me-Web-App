<?php
header("Access-Control-Allow-Origin: *");
$usr=$_GET["usr"];
?>
<p>Welcome <?php print $usr; ?>!</p>
<p>Request received on: 
<?php

print date("l M dS, Y, H:i:s");
?>
</p>