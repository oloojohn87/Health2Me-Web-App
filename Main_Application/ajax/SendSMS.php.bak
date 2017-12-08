<?php
  /* Send an SMS using Twilio. You can run this file 3 different ways:
     *
     * - Save it as sendnotifications.php and at the command line, run 
     *        php sendnotifications.php
     *
     * - Upload it to a web host and load mywebhost.com/sendnotifications.php 
     *   in a web browser.
     * - Download a local server like WAMP, MAMP or XAMPP. Point the web root 
     *   directory to the folder containing this file, and load 
     *   localhost:8888/sendnotifications.php in a web browser.
     */
    // Include the PHP Twilio library. You need to download the library from 
    // twilio.com/docs/libraries, and move it into the folder containing this 
    // file.
    require "Services/Twilio.php";
     
    // Set our AccountSid and AuthToken from twilio.com/user/account
    $AccountSid = "ACc8d7e18334aea996eee97355a50fe672";
    $AuthToken = "484f8b70a66fe46fdb49f44900f891ec";
 
    // Instantiate a new Twilio Rest Client
    $client = new Services_Twilio($AccountSid, $AuthToken);
 
    /* Your Twilio Number or Outgoing Caller ID */
    $from = '34518880185';
 
    // make an associative array of server admins. Feel free to change/add your 
    // own phone number and name here.
    $people = array(
        "34608754342" => "Johnny"
    );
 
    // Iterate over all admins in the $people array. $to is the phone number, 
    // $name is the user's name
    foreach ($people as $to => $name) {
        // Send a new outgoing SMS */
        $body = "Bad news $name, the server is down and it needs your help";
        $client->account->sms_messages->create($from, $to, $body);
        echo "Sent message to $name";
    }  

?>