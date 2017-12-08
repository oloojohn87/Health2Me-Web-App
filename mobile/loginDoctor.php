<?php
 //session_start();
 require("../environment_detail.php");
 require("../PasswordHash.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 
 
 //$name=$_POST['Nombre'];
 //$pass=$_POST['Password'];
 
 $email=$_POST['Nombre'];
 $pass=$_POST['Password'];
 
 
 $link = mysqli_connect("$dbhost", "$dbuser", "$dbpass","$dbname");

 
 if (!$link) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
 }
 
 
 
  
 if ($stmt = mysqli_prepare($link, "SELECT id,Name,Surname,IdMEDRESERV,salt FROM DOCTORS where IdMEDEmail=?")) {
	
	mysqli_stmt_bind_param($stmt, "s", $email);
	
	mysqli_stmt_execute($stmt);
	
	mysqli_stmt_store_result($stmt);
	
    $count = mysqli_stmt_num_rows($stmt);	
		
	if($count==1)
	{
		mysqli_stmt_bind_result($stmt, $docID,$docName,$docSurname,$IdMedRESERV,$addstring);
		
		mysqli_stmt_fetch($stmt);
		
		$correcthash=PBKDF2_HASH_ALGORITHM .":". PBKDF2_ITERATIONS. ":" . $addstring . ":" .$IdMedRESERV;
		
		if(validate_password($pass,$correcthash))
		{
			$post_data = array('success' => 1,
							   'docID' => $docID,
							   'docName' => $docName.' '.$docSurname,
							   'docEmail' => $email);
		}	
		else
		{
			$post_data = array('success' => 0,
						   'docID' => -1,
						   'docName' => '',
						   'docEmail' => '');
		}
		
				
	}
	else
	{
		$post_data = array('success' => 0,
						   'docID' => -1,
						   'docName' => '',
						   'docEmail' => '');
		
	}
	
	
	mysqli_stmt_close($stmt);
	
	$post_data = json_encode($post_data);
	
	echo $post_data;
	
 }
 mysqli_close($link);
?>
 
 
