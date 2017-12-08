<?php

// Include the Twilio PHP library
    require 'Services/Twilio.php';
 
    // Twilio REST API version
    $version = '2010-04-01';
 
    // Set our AccountSid and AuthToken
    $sid = "AC109c7554cf28cdfe596e4811c03495bd";
    $token = "26b187fb3258d199a6d6edeb7256ecc1";
 
    $client = new Services_Twilio($sid, $token);
 
// Get an object from its sid. If you do not have a sid,
// check out the list resource examples on this page
   $call = $client->account->calls->get("CA225df80c3e2ca3b8e45662da3eb2d3aa");
   echo $call->status;
?>