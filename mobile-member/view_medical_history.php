<?php
header("Access-Control-Allow-Origin: *");
// This functions copies exactly the code in function CreaTimeline, and replaces the code for the injection of timeline elements with the code for injection of "Reports Stream" html elements from the type desired

require("../environment_detail.php");

    //Connect to the database    
    $dbhost = $env_var_db['dbhost'];
    $dbname = $env_var_db['dbname'];
    $dbuser = $env_var_db['dbuser'];
    $dbpass = $env_var_db['dbpass'];


    $tbl_name="usuarios"; // Table name
		
    $con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    if (!$con)
    {
        die('Could not connect: ' . mysql_error());
    }	



    $num_reports=0;
   
     if($num_reports==0)
    {
	   echo '<span class="label label-info" style="padding-top:60px; margin-bottom:5px; font-size:14px; color:#777777;">No Data Found</span>';
     }

?>