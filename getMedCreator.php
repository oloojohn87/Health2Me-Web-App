<?php
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$queUsu = $_GET['UserId'];
$email = '';
if(isset($_GET['email']))
{
    $email = $_GET['email'];
}
//$queUsu = 32;

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}


try {
    
if($email == ''){
    $sql = "SELECT * FROM doctors WHERE id = ? ";
    $query = $con->prepare($sql);
    $query->bindValue(1, $queUsu, PDO::PARAM_INT);
}else{
    $sql = "SELECT * FROM doctors WHERE IdMEDEmail= ?";
    $query = $con->prepare($sql);
    $query->bindValue(1, $email, PDO::PARAM_STR);
}




   
    
    $query->execute();
    if($query->rowCount()>=1)   
    {
        $row = $query->fetchAll(PDO::FETCH_OBJ);
        //echo json_encode($row); 
        //$data= json_encode($row);
	    echo '{"items":'. json_encode($row) .'}'; 
    }
    else
    {
        $confirm_code = md5(uniqid(rand()));
        
        $query2 = $con->prepare("INSERT INTO doctors SET Surname=?,previlege=1,token=?");
        $query2->bindValue(1, $email, PDO::PARAM_STR);
        //$query2->bindValue(2, $email, PDO::PARAM_STR);
        $query2->bindValue(2, $confirm_code, PDO::PARAM_STR);
        $query2->execute();
        unset($query2);

        //New Code added by Pallab as it was throwing Integrity Constraint in below insert query into temporary doctor access
        $query3 = $con->prepare("SELECT * FROM doctors where token = ?");
        $query3->bindValue(1, $confirm_code, PDO::PARAM_STR);
        //$query3->bindValue(1,$email, PDO::PARAM_STR);
        $query3->execute();
        $row11 = $query3->fetchAll(PDO::FETCH_ASSOC);
        unset($query3);
        
        //$data= json_encode($row11);
        echo '{"items":'. json_encode($row11) .'}'; 
        //echo json_encode($row); 
    }
} catch(PDOException $e) {
	  echo '{"error":{"text":'. $e->getMessage() .'}}'; 
   // return;
}

//echo '{"items":'.$data.'}'; 


    

?>
