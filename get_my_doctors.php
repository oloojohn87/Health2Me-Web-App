<?php
require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];


$tbl_name="doctors"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

if(isset($_POST['idpac']))
{
    $idpac = $_POST['idpac'];

    $res = $con->prepare("SELECT * FROM my_doctors WHERE IdPac = ?");
    $res->bindValue(1, $idpac, PDO::PARAM_INT);
    $res->execute();
    $result = array();
    while($row = $res->fetch(PDO::FETCH_ASSOC))
    {
        $email = '';
        if($row['Email'] != NULL)
        {
            $email = $row['Email'];
        }
        $hash = md5( strtolower( trim( $email ) ) );
        $row['hash'] = $hash;
        array_push($result, $row);
    }
    
    $size = count($result);
    $emails = array();
    for($i = 0; $i < $size; $i++)
    {
        array_push($emails, "'".$result[$i]['Email']."'");
    }
    
    if(count($emails) > 0)
    {
        $found_emails = array();
        $res = $con->prepare("SELECT IdMEDEmail FROM doctors WHERE IdMEDEmail IN (".implode(",", $emails).")");
        $res->execute();
        while($row = $res->fetch(PDO::FETCH_ASSOC))
        {
            array_push($found_emails, $row['IdMEDEmail']);
        }

        for($i = 0; $i < $size; $i++)
        {
            if(in_array($result[$i]['Email'], $found_emails))
            {
                $result[$i]['H2M'] = 1;
            }
            else
            {
                $result[$i]['H2M'] = 0;
            }
        }
    }

    echo json_encode($result);
}
else
{
    $iddoc = $_POST['iddoc'];
    
    $res = $con->prepare("SELECT * FROM my_doctors WHERE id = ?");
    $res->bindValue(1, $iddoc, PDO::PARAM_INT);
    $res->execute();
    $row = $res->fetch(PDO::FETCH_ASSOC);
    echo json_encode($row);
}
?>
