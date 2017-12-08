 <?php
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			
  
  $doc_id = $_GET['docid'];
  $npi = $_GET['npi'];
  $dea = $_GET['dea'];
  
  $query = $con->prepare("UPDATE doctors SET npi = ?, dea = ? WHERE id = ?");
  $query->bindValue(1, $npi, PDO::PARAM_INT);
  $query->bindValue(2, $dea, PDO::PARAM_STR);
  $query->bindValue(3, $doc_id, PDO::PARAM_INT);
  $result = $query->execute();
  
  
  ?>
