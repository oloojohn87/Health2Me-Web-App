<?php

	 require("../environment_detail.php");
	 $dbhost = $env_var_db['dbhost'];
	 $dbname = $env_var_db['dbname'];
	 $dbuser = $env_var_db['dbuser'];
	 $dbpass = $env_var_db['dbpass'];
	 
	 
	 
	$code=$_GET['code'];
	$state=$_GET['state'];
	$Idmed=$_GET['Idmed'];
	//$IdMEDEmail=$_GET['Email'];

	$ch = curl_init();

	$data = array(
			'grant_type' => 'authorization_code',
			'code' => $code,
			'client_id' => '60jejabv3c6zrpbxe0mss56pifqce3uo',
			'client_secret' => 'vCFR8ezL54EMb22HnmTCVOAa8gm23oho'
			);

	curl_setopt($ch, CURLOPT_URL, 'https://www.box.com/api/oauth2/token');

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
	// echo $result; 
	 
	// echo 'Idmed '.$Idmed;
	 
	// $json = '{"foo-bar": 12345}';

	$sessiondetails = json_decode($result);
	//print $sessiondetails->{'refresh_token'}; // 12345
    $accesstoken=$sessiondetails->{'access_token'};

	/* Create a new mysqli object with database connection parameters */
   $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

   if(mysqli_connect_errno()) {
      echo "Connection Failed: " . mysqli_connect_errno();
      exit();
   }
   
    if($stmt = $mysqli -> prepare("select IdMEDEmail from doctors where id=?")) {

      /* Bind parameters
         s - string, b - blob, i - int, etc */
      $stmt -> bind_param("s", $Idmed);

      /* Execute it */
      $stmt -> execute();
	 
	 /* Bind results */
      $stmt -> bind_result($Idemail);

      /* Fetch the value */
      $stmt -> fetch();
	  
	   /* Close statement */
      $stmt -> close();
   }
	  


   /* Create a prepared statement */
   if($stmt = $mysqli -> prepare("insert into boxuserdetails set ID=? ,IdMEDEmail=?,AccessToken=?,Refreshtoken=?")) {

      /* Bind parameters
         s - string, b - blob, i - int, etc */
      $stmt -> bind_param("ssss",$Idmed,$Idemail,$accesstoken,$sessiondetails->{'refresh_token'});

      /* Execute it */
      $stmt -> execute();
	  
	
      /* Close statement */
      $stmt -> close();
   }
  
   
     //code for creating a new folder called Health2me
   
	
	// echo $accesstoken;
	
    $ch = curl_init();
	$data = json_encode(array('name' => 'Health2me','parent' => array('id' => '0')));
	//echo $data;
	
	$res=createFolder($accesstoken,$data);
	
	 
	//$sessiondetails = json_decode($result);
	//echo $sessiondetails->{'item_collection'}; // 12345
	$response =json_decode($res, true);
	//echo count($response['item_collection']['entries'][0]['name']);
	//echo $response['id'];
	
	$data = json_encode(array('name' => 'Processed','parent' => array('id' => $response['id'])));
	//echo $data;
	
	$res11=createFolder($accesstoken,$data);
	
	$response11=json_decode($res11,true);
	
	 if($stmt = $mysqli -> prepare("update boxuserdetails set folderid=?,processedfolderid=? where ID=?")) {
	 //if($stmt = $mysqli -> prepare("update boxuserdetails set folderid=? where ID=?")) {

      /* Bind parameters
         s - string, b - blob, i - int, etc */
      $stmt -> bind_param("sss",$response['id'],$response11['id'],$Idmed);
	  //$stmt -> bind_param("ss",$response['id'],$Idmed);

      /* Execute it */
      $stmt -> execute();
	  
	
      /* Close statement */
      $stmt -> close();
   }
   
   
   /* Close connection */
	 $mysqli -> close();

	echo "Box account linked. A new folder 'Health2me' has been created. Please drop your files there";
	 
	function createFolder($accesstoken,$data) {
		$ch = curl_init();
	
		$headr = array();
		$headr[] = 'Authorization: Bearer '.$accesstoken;
		$headr[]= 'Content-Type: application/json';
		
		curl_setopt($ch, CURLOPT_URL, 'https://www.box.com/api/2.0/folders');

		curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
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
		 return $result; 
	}

?>
