<?php session_start();


	$uniqueid=$_GET['uniqueID'];



	 require("../environment_detail.php");
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
      $stmt -> bind_param("s", $uniqueid);

      /* Execute it */
      $stmt -> execute();
	 
	 /* Bind results */
      $stmt -> bind_result($accesstoken);

      /* Fetch the value */
      $stmt -> fetch();
	  
	   /* Close statement */
      $stmt -> close();
   }
   
	if($accesstoken!=""){
	
			echo "The Box is already linked for this account. Please contact admin for more details!";
			
	
	}else{
	
		$MY_CLIENT_ID="60jejabv3c6zrpbxe0mss56pifqce3uo";
		$REDIRECT_URI="BOX/BoxChannelClientDetails.php?Idmed=".$uniqueid;

		$authorizeURL="https://www.box.com/api/oauth2/authorize?response_type=code&client_id=".$MY_CLIENT_ID."&redirect_uri=".$REDIRECT_URI."&state=qwertyuiop1234";

		//echo $authorizeURL ;

		header("Location: $authorizeURL");

	
	}

		


?>
