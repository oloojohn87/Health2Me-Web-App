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



/* Create a new mysqli object with database connection parameters */
   $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

   if(mysqli_connect_errno()) {
      echo "Connection Failed: " . mysqli_connect_errno();
      exit();
   }
   
   
    /* Create a prepared statement */
   if($stmt = $mysqli -> prepare("select idGroup,idDoctor from doctorsgroups where  id=?")) {

      /* Bind parameters
         s - string, b - blob, i - int, etc */
      $stmt -> bind_param("s", $id);

      /* Execute it */
      $stmt -> execute();
	  
	  //$res = $stmt->get_result();

      /* Bind results */
      $stmt -> bind_result($idgrp,$idmed);

      /* Fetch the value */
      $stmt -> fetch();

    /*  for ($row_no = ($res->num_rows - 1); $row_no >= 0; $row_no--) {
			$res->data_seek($row_no);
			var_dump($res->fetch_assoc());
		}
		$res->close();*/

      /* Close statement */
      $stmt -> close();
   }

   /* Create a prepared statement */
   if($stmt = $mysqli -> prepare("update groups set refincharge=? where id=?")) {

      /* Bind parameters
         s - string, b - blob, i - int, etc */
      $stmt -> bind_param("ss",$idmed, $idgrp);

      /* Execute it */
      $stmt -> execute();
	  
	
      /* Close statement */
      $stmt -> close();
   }
  
   
    /* Close connection */
   $mysqli -> close();

?>