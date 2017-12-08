<?php

	require("../environment_detail.php");
	 $dbhost = $env_var_db['dbhost'];
	 $dbname = $env_var_db['dbname'];
	 $dbuser = $env_var_db['dbuser'];
	 $dbpass = $env_var_db['dbpass'];
	 
	 
	 /* Create a new mysqli object with database connection parameters */
   $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

   if(mysqli_connect_errno()) {
      echo "Connection Failed: " . mysqli_connect_errno();
      exit();
   }
    
   
    /* Create a prepared statement */
   if($stmt = $mysqli -> prepare("select ID,Refreshtoken from boxuserdetails")) {

      /* Bind parameters
         s - string, b - blob, i - int, etc */
     // $stmt -> bind_param("s", $id);

      /* Execute it */
      $stmt -> execute();
	  
	  $res = $stmt->get_result();

      /* Bind results */
     // $stmt -> bind_result($refreshtoken);

      /* Fetch the value */
      //$stmt -> fetch();
		
	   $count=0;
       for ($row_no = ($res->num_rows - 1); $row_no >= 0; $row_no--) {
			$res->data_seek($row_no);
			//var_dump($res->fetch_assoc());
			$token[$count]=$res->fetch_assoc();
			$count++;
			 //var_dump($refreshtoken);
		}
		$res->close();
		
		/*$res = $stmt->get_result();
		$refreshtoken = $res->fetch_assoc();*/

      /* Close statement */
      $stmt -> close();
   }
   
   //var_dump($refreshtoken);
   //echo $token[0]['Refreshtoken'];
   
   if($count==0){
		echo "Box Channel Presently has no active accounts.....";
		
	}
   
   for($i=0;$i<count($token);$i++){
	   
	   
	    echo "Fetching refresh tokens.....";
		
		$ch = curl_init();

		$data = array(
				'grant_type' => 'refresh_token',
				'refresh_token' => $token[$i]['Refreshtoken'],
				'client_id' => '60jejabv3c6zrpbxe0mss56pifqce3uo',
				'client_secret' => 'vCFR8ezL54EMb22HnmTCVOAa8gm23oho'
			);
				
		/*$headr = array();
		$headr[] = 'Authorization: Bearer '.$accesstoken;*/

		$URL='https://www.box.com/api/oauth2/token';

		curl_setopt($ch, CURLOPT_URL, $URL);

		//curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
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
		
		echo "Token details : ";
		echo $result; 
		 
		  
		$sessiondetails = json_decode($result);
		//print $sessiondetails->{'refresh_token'}; // 12345
		$accesstoken=$sessiondetails->{'access_token'};

	   /* Create a new mysqli object with database connection parameters */
	   $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

	   if(mysqli_connect_errno()) {
		  echo "Connection Failed: " . mysqli_connect_errno();
		  exit();
	   }

	   /* Create a prepared statement */
	  if($stmt = $mysqli -> prepare("update boxuserdetails set AccessToken=?,Refreshtoken=? where ID=?")) {

		  /* Bind parameters
			 s - string, b - blob, i - int, etc */
		  $stmt -> bind_param("sss",$accesstoken,$sessiondetails->{'refresh_token'},$token[$i]['ID']);

		  /* Execute it */
		  $stmt -> execute();
		  
		
		  /* Close statement */
		  $stmt -> close();
	   }
	   
	   echo "Tokens updated for client: ".$token[$i]['ID'];
      
   
   }
	

?>