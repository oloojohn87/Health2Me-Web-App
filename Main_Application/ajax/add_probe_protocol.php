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

$doc = $_POST['doctor'];
$probe_name = $_POST['name'];
$probe_description = $_POST['description'];

$edit = 0;
if(isset($_POST['edit']))
{
    $edit = $_POST['edit'];
}
if(!$edit)
{
    $insert = $con->prepare("INSERT INTO probe_questions SET title = ?, question_text = ?, question_textSPA = ?, answer_type = ?, answer_min = ?, answer_max = ?, doctor = ?");
    $insert->bindValue(1, 'Well Being', PDO::PARAM_STR);
    $insert->bindValue(2, 'How are you feeling today?', PDO::PARAM_STR);
    $insert->bindValue(3, '¿Cómo te sientes hoy?', PDO::PARAM_STR);
    $insert->bindValue(4, 1, PDO::PARAM_INT);
    $insert->bindValue(5, 1, PDO::PARAM_INT);
    $insert->bindValue(6, 5, PDO::PARAM_INT);
    $insert->bindValue(7, $doc, PDO::PARAM_INT);
    $insert->execute();
    
    $question_id = $con->lastInsertId();
    $question_labels = ["Very Bad", "Bad", "Normal", "Good", "Very Good"];
    
    for($k = 0; $k < 5; $k++)
    {
        $unit = $con->prepare("INSERT INTO probe_units SET probe_question = ?, label = ?, value = ?");
        $unit->bindValue(1, $question_id, PDO::PARAM_INT);
        $unit->bindValue(2, $question_labels[$k], PDO::PARAM_STR);
        $unit->bindValue(3, $k + 1, PDO::PARAM_INT);
        $unit->execute();
    }
    
    $ins = $con->prepare("INSERT INTO probe_protocols SET name = ?, description = ?, question1 = ?, doctor = ?");
    $ins->bindValue(1, $probe_name, PDO::PARAM_STR);
    $ins->bindValue(2, $probe_description, PDO::PARAM_STR);
    $ins->bindValue(3, $question_id, PDO::PARAM_INT);
    $ins->bindValue(4, $doc, PDO::PARAM_INT);
    $ins->execute();

    echo $con->lastInsertId()."_".$question_id;

}
else
{
    $probe_id = $_POST['probe_id'];
    $probe = $con->prepare("UPDATE probe_protocols SET name = ?, description = ? WHERE protocolID = ?");
    $probe->bindValue(1, $probe_name, PDO::PARAM_STR);
    $probe->bindValue(2, $probe_description, PDO::PARAM_STR);
    $probe->bindValue(3, $probe_id, PDO::PARAM_INT);
    $probe->execute();

    echo $probe_id;
}
?>