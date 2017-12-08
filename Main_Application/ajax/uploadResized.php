<?php if ($_POST) {
    define('UPLOAD_DIR', 'uploads/');

	require("environment_detail.php");
	$dbhost = $env_var_db['dbhost'];
	$dbname = $env_var_db['dbname'];
	$dbuser = $env_var_db['dbuser'];
	$dbpass = $env_var_db['dbpass'];

	
    $img = $_POST['image'];
	$grpid=$_GET['groupid'];
    $img = str_replace('data:image/jpeg;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
	$file =  uniqid() . '.jpg';
	$path= UPLOAD_DIR . $file ;
    $success = file_put_contents($path, $data);
	
	
   // print $success ? $file : 'Unable to save the file.';
	
	
	/* Create a new mysqli object with database connection parameters */
   $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

   if(mysqli_connect_errno()) {
      echo "Connection Failed: " . mysqli_connect_errno();
      exit();
   }

   /* Create a prepared statement */
   if($stmt = $mysqli -> prepare("update groups set image=? where id=?")) {

      /* Bind parameters
         s - string, b - blob, i - int, etc */
      $stmt -> bind_param("ss",$file, $grpid);

      /* Execute it */
     $stmt -> execute();
	  
	
      /* Close statement */
     $stmt -> close();
   }
  
   
    /* Close connection */
   $mysqli -> close();
}

?>