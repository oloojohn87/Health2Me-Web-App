<?php
/*KYLE
     require("environment_detail.php");
     $dbhost = $env_var_db['dbhost'];
     $dbname = $env_var_db['dbname'];
     $dbuser = $env_var_db['dbuser'];
     $dbpass = $env_var_db['dbpass'];

     $link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
     mysql_select_db("$dbname")or die("cannot select DB"); 

     $doctorId = $_GET['doctorId'];

     //echo "Doctor ID:".$doctorId;
     $query = mysql_query("select * from doctors where id =".$doctorId);
     $count = mysql_num_rows($query);
     if($count == 1)
     {       
         mysql_query("delete from doctors where id =".$doctorId);
     }

     
      else
      {
          echo "Records already viewed,Invalid User";
      }
?>