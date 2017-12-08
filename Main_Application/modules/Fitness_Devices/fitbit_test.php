<?php
$exec = shell_exec("curl -X POST -i -H 'Authorization: OAuth oauth_consumer_key=\"749d4da9ae430c762862a75a558ec37f\", oauth_nonce=\"tlqfo95s353dpf1o0893n6vhlj\", oauth_signature=\"fot3fvj6fmmap9mi6pbileel5t\", oauth_signature_method=\"HMAC-SHA1\", oauth_timestamp=\"1427819660\", oauth_version=\"1.0\"' https://api.fitbit.com/oauth/authorize");
echo $exec;
?>
