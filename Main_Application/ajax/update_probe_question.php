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


$id = $_POST['id'];
$question = $_POST['question'];
$protocol = $_POST['protocol'];
$doctor = $_POST['doctor'];

if(isset($_POST['clear']) && $_POST['clear'] == 1)
{
    // clear
    $del = $con->prepare("DELETE FROM probe_questions WHERE id = ?");
    $del->bindValue(1, $id, PDO::PARAM_INT);
    $del->execute();
    
    $del2 = $con->prepare("DELETE FROM probe_units WHERE probe_question = ?");
    $del2->bindValue(1, $id, PDO::PARAM_INT);
    $del2->execute();
    
    $upd = $con->prepare("UPDATE probe_protocols SET question".$question." = NULL WHERE protocolID = ?");
    $upd->bindValue(1, $protocol, PDO::PARAM_INT);
    $upd->execute();
}
else
{
    // add or update
    $title = $_POST['title'];
    $question_en = $_POST['question_en'];
    $question_es = $_POST['question_es'];
    $min = $_POST['min'];
    $max = $_POST['max'];
    $unit = $_POST['unit'];
    $units = json_decode($_POST['units'], true);
    $units_size = count($units);
    if($id == 0)
    {
        $ins = $con->prepare("INSERT INTO probe_questions SET title = ?, question_text = ?, question_textSPA = ?, answer_min = ?, answer_max = ?, unit = ?, doctor = ?, answer_type = 2");
        $ins->bindValue(1, $title, PDO::PARAM_STR);
        $ins->bindValue(2, $question_en, PDO::PARAM_STR);
        $ins->bindValue(3, $question_es, PDO::PARAM_STR);
        $ins->bindValue(4, $min, PDO::PARAM_INT);
        $ins->bindValue(5, $max, PDO::PARAM_INT);
        $ins->bindValue(6, $unit, PDO::PARAM_STR);
        $ins->bindValue(7, $doctor, PDO::PARAM_INT);
        $ins->execute();

        $new_id = $con->lastInsertId();

        $upd = $con->prepare("UPDATE probe_protocols SET question".$question." = ? WHERE protocolID = ?");
        $upd->bindValue(1, $new_id, PDO::PARAM_INT);
        $upd->bindValue(2, $protocol, PDO::PARAM_INT);
        $upd->execute();
        
        for($i = 0; $i < $units_size; $i++)
        {
            $ins2 = $con->prepare("INSERT INTO probe_units SET value = ?, label = ?, probe_question = ?");
            $ins2->bindValue(1, $units[$i]['end'], PDO::PARAM_INT);
            $ins2->bindValue(2, $units[$i]['title'], PDO::PARAM_STR);
            $ins2->bindValue(3, $new_id, PDO::PARAM_INT);
            $ins2->execute();
        }

        echo $new_id;

    }
    else
    {
        $upd = $con->prepare("UPDATE probe_questions SET title = ?, question_text = ?, question_textSPA = ?, answer_min = ?, answer_max = ?, unit = ? WHERE id = ?");
        $upd->bindValue(1, $title, PDO::PARAM_STR);
        $upd->bindValue(2, $question_en, PDO::PARAM_STR);
        $upd->bindValue(3, $question_es, PDO::PARAM_STR);
        $upd->bindValue(4, $min, PDO::PARAM_INT);
        $upd->bindValue(5, $max, PDO::PARAM_INT);
        $upd->bindValue(6, $unit, PDO::PARAM_STR);
        $upd->bindValue(7, $id, PDO::PARAM_INT);
        $upd->execute();
        
        $del = $con->prepare("DELETE FROM probe_units WHERE probe_question = ?");
        $del->bindValue(1, $id, PDO::PARAM_INT);
        $del->execute();
        
        for($i = 0; $i < $units_size; $i++)
        {
            $ins2 = $con->prepare("INSERT INTO probe_units SET value = ?, label = ?, probe_question = ?");
            $ins2->bindValue(1, $units[$i]['end'], PDO::PARAM_INT);
            $ins2->bindValue(2, $units[$i]['title'], PDO::PARAM_STR);
            $ins2->bindValue(3, $id, PDO::PARAM_INT);
            $ins2->execute();
        }
        
        echo $id;
    }
}


?>
