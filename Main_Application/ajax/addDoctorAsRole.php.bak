<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="messages"; // Table name

	

$groupid=$_GET['groupid'];
$id=$_GET['id'];
$role=$_GET['role'];


/* Create a new mysqli object with database connection parameters */
   $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

   if(mysqli_connect_errno()) {
      echo "Connection Failed: " . mysqli_connect_errno();
      exit();
   }

   /* Create a prepared statement */
   if($stmt = $mysqli -> prepare("update doctorsgroups set Role=? where id=?")) {

      /* Bind parameters
         s - string, b - blob, i - int, etc */
      $stmt -> bind_param("ss",$role, $id);

      /* Execute it */
      $stmt -> execute();
	  
	
      /* Close statement */
      $stmt -> close();
   }
  
   
    /* Close connection */
   $mysqli -> close();

?>