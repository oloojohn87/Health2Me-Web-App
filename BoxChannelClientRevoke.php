<?php

	 require("../environment_detail.php");
	 $dbhost = $env_var_db['dbhost'];
	 $dbname = $env_var_db['dbname'];
	 $dbuser = $env_var_db['dbuser'];
	 $dbpass = $env_var_db['dbpass'];
	 
	 
	 
	//$code=$_GET['code'];
	//$state=$_GET['state'];
	$Idmed=$_GET['Idmed'];
	//$IdMEDEmail=$_GET['Email'];
	
	/* Create a new mysqli object with database connection parameters */
   $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

   if(mysqli_connect_errno()) {
      echo "Connection Failed: " . mysqli_connect_errno();
      exit();
   }
	
	if($stmt = $mysqli -> prepare("select AccessToken,folderid from boxuserdetails where id=?")) {

      /* Bind parameters
         s - string, b - blob, i - int, etc */
      $stmt -> bind_param("s", $Idmed);

      /* Execute it */
      $stmt -> execute();
	 
	 /* Bind results */
      $stmt -> bind_result($accesstoken,$folderid);

      /* Fetch the value */
      $stmt -> fetch();
	  
	   /* Close statement */
      $stmt -> close();
   }
   
   
    deleteFolder($accesstoken,$folderid);		//Delete all the files and folders inside Health2me 

	$ch = curl_init();

	$data = array(
			'client_id' => '60jejabv3c6zrpbxe0mss56pifqce3uo',
			'client_secret' => 'vCFR8ezL54EMb22HnmTCVOAa8gm23oho',
			'token' => $accesstoken
			);

	curl_setopt($ch, CURLOPT_URL, 'https://www.box.com/api/oauth2/revoke');

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_POST, 1);

	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	if( ! $result = curl_exec($ch)) 
	{ 
		trigger_error(curl_error($ch)); 
	} 
	 curl_close($ch); 
	 echo $result; 
	 
	 //echo 'Idmed '.$Idmed;
	 
	// $json = '{"foo-bar": 12345}';

   
    if($stmt = $mysqli -> prepare("delete from boxuserdetails where id=?")) {

      /* Bind parameters
         s - string, b - blob, i - int, etc */
      $stmt -> bind_param("s", $Idmed);

      /* Execute it */
      $stmt -> execute();
	 
	 /* Bind results */
     // $stmt -> bind_result($Idemail);

      /* Fetch the value */
    //  $stmt -> fetch();
	  
	   /* Close statement */
      $stmt -> close();
   }
	  
 
   
	echo "Box account delinked. Folder 'Health2me' has been deleted.";
	 
	function deleteFolder($accesstoken,$folderid) {
		$ch = curl_init();
	
		$headr = array();
		$headr[] = 'Authorization: Bearer '.$accesstoken;
		$headr[]= 'Content-Type: application/json';
		
		curl_setopt($ch, CURLOPT_URL, 'https://www.box.com/api/2.0/folders/'.$folderid.'?recursive=true');

		//curl_setopt($ch, CURLOPT_URL, $URL);

		curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		//curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		if( ! $result = curl_exec($ch)){ 
				trigger_error(curl_error($ch)); 
		} 
		curl_close($ch); 
	}

?>
