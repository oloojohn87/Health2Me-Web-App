<?php

require("environment_detail.php");
require("push_server.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];


$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

Push_Probe_Email('Lima', 'blima@inmers.us', 35, 0);

//Send_Feedback_SMS('Lima', 'John Smith', '19723756339', 35, 0, 1);

//Health_Feedback_Call('Bruno', 'Lima', 'John', 'Smith', '19723756339', 35, 0);

?>