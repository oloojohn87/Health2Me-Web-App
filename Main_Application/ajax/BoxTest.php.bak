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
	   if($stmt = $mysqli -> prepare("select AccessToken,folderid,processedfolderid from boxuserdetails")) {

		  /* Execute it */
		  $stmt -> execute();
		 
		  $res = $stmt->get_result();
		   $count=0;
		   for ($row_no = ($res->num_rows - 1); $row_no >= 0; $row_no--) {
				$res->data_seek($row_no);
				//var_dump($res->fetch_assoc());
				$token[$count]=$res->fetch_assoc();
				$count++;
				 //var_dump($refreshtoken);
			}
			$res->close();

		  /* Close statement */
		  $stmt -> close();
	    }

		 $ch = curl_init();

	
		$headr = array();
		$headr[] = 'Authorization: Bearer '.$token[0]['AccessToken'];

		$URL='https://www.box.com/api/2.0/folders/'.$token[0]['folderid'].'?fields=type,name,item_collection';

		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		//curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	
		if( ! $result = curl_exec($ch)){ 
				trigger_error(curl_error($ch)); 
		} 
		 curl_close($ch); 
		echo $result; 
		// echo '<br><br>';
		 
		 
		$response =json_decode($result, true);
		
		echo $response;
		$fileid=$response['item_collection']['entries'][1]['id'];
		
		/*$ch = curl_init();
		
		$URL='https://www.box.com/api/2.0/files/'.$fileid.'?fields=extension';
		
		curl_setopt($ch, CURLOPT_URL, $URL);

		curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		//curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch,CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		if( ! $result = curl_exec($ch)){ 
				trigger_error(curl_error($ch)); 
		} 
		curl_close($ch); 
		echo $result; 
		
		
		*/
		//$fileid=$token[0]['folderid'];
	/*	$folderid=$token[0]['processedfolderid'];
		$data = json_encode(array('parent' => array('id' => $folderid)));
		echo $data;
		
		
		$ch = curl_init();
		
		$URL='https://www.box.com/api/2.0/files/'.$fileid.'/copy';
		
		curl_setopt($ch, CURLOPT_URL, $URL);

		curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		if( ! $result = curl_exec($ch)){ 
				trigger_error(curl_error($ch)); 
		} 
		curl_close($ch); 
		echo $result;
		 */
?>