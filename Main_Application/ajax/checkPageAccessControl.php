<?php

function checkAccessPage($page){
    
    //session_start();
    require("environment_detail.php");
  
     $dbhost = $env_var_db['dbhost'];
     $dbname = $env_var_db['dbname'];
     $dbuser = $env_var_db['dbuser'];
     $dbpass = $env_var_db['dbpass'];
	 


    $tbl_name="doctors"; // Table name

    // Connect to server and select databse.
    $con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    if (!$con)
      {
      die('Could not connect: ' . mysql_error());
    }	

    $DocId=$_SESSION['MEDID'];

    $result = $con->prepare("select pc.accessid,pc.page from pageAccessControl pc INNER JOIN (select idGroup from doctorsgroups where idDoctor=?) dg where dg.idGroup=pc.groupid and pc.page=?");
    //$result->bindValue(1, $confirm_code, PDO::PARAM_STR);
    $result->bindValue(1, $DocId, PDO::PARAM_INT);
    $result->bindValue(2, $page, PDO::PARAM_STR);
    $result->execute();


        if($result->rowCount()>=1)   
        {
            $row = $result->fetchAll(PDO::FETCH_OBJ);
            //echo json_encode($row); 
            //$data= json_encode($row);
            return '{"items":'. json_encode($row) .'}'; 
        }

    /*while($row = $result->fetch(PDO::FETCH_ASSOC)){
        $Access=$row['accessid'];
        $page=$row['page']
    }*/
}


?>