<?php session_start();


	$docid=$_GET['docid'];

	require("environment_detail.php");
	 $dbhost = $env_var_db['dbhost'];
	 $dbname = $env_var_db['dbname'];
	 $dbuser = $env_var_db['dbuser'];
	 $dbpass = $env_var_db['dbpass'];
	 
	 $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

   if(mysqli_connect_errno()) {
      echo "Connection Failed: " . mysqli_connect_errno();
      exit();
   }
	
	
	$accesstoken="";
	if($stmt = $mysqli -> prepare("select AccessToken from boxuserdetails where id=?")) {

      /* Bind parameters
         s - string, b - blob, i - int, etc */
      $stmt -> bind_param("s", $docid);

      /* Execute it */
      $stmt -> execute();
	 
	 /* Bind results */
      $stmt -> bind_result($accesstoken);

      /* Fetch the value */
      $stmt -> fetch();
	  
	   /* Close statement */
      $stmt -> close();
   }
   
    if($accesstoken!="")
		echo 1;
	else
		echo 0;
		
   ?>