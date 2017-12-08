<?php  
session_start();

require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


$json=$_GET ['json'];

//$obj = json_decode($json,true);

//echo $obj[0]["pre_patenctemp"];
//var_dump($obj);

$patID = $_GET['idusu'];
$docID=$_SESSION['MEDID'];


//$patID=1;
//$docID=1;
 /*Create a new mysqli object with database connection parameters */
   $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

   if(mysqli_connect_errno()) {
      echo "Connection Failed: " . mysqli_connect_errno();
      exit();
   }

   //$id=null;
   /* Create a prepared statement */
   //if($stmt = $mysqli -> prepare("insert into clearallergy_patient_enc_form set pre_vs_temp=?,pre_vs_bp=?,pre_vs_pulse=?,pre_vs_resp=?,type_testing=?,pat_tol_proc=?,pat_tol_explaination=?,pat_instructions=?,pat_voice_concern=?,pat_voice_con_exp=?,post_vs_temp=?,post_vs_bp=?,post_vs_pulse=?,post_vs_resp=?")) {
   if($stmt = $mysqli -> prepare("insert into clear_allergy_templates_data set patientID=?,DoctorID=?,JSON_DATA=?, date=now()")){
      /* Bind parameters
         s - string, b - blob, i - int, etc */
      $stmt -> bind_param("sss", $patID,$docID,$json);

      /* Execute it */
     $stmt -> execute();
	  
	  //$res = $stmt->get_result();

      /* Bind results */
      //$stmt -> bind_result($idgrp,$idmed);

      /* Fetch the value */
      //$stmt -> fetch();

    /*  for ($row_no = ($res->num_rows - 1); $row_no >= 0; $row_no--) {
			$res->data_seek($row_no);
			var_dump($res->fetch_assoc());
		}
		$res->close();*/

	  $id=$stmt->insert_id;  
      /* Close statement */
      $stmt -> close();
   }
   
  echo $id;
  
?>