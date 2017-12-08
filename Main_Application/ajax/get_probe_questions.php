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

$doc_id = $_POST['doc_id'];
$id = 0;
if(isset($_POST['question_id']))
{
    $id = $_POST['question_id'];
}

if($id == 0)
{
    $questions = $con->prepare("SELECT * FROM probe_questions WHERE doctor = ? GROUP BY question_text");
    $questions->bindValue(1, $doc_id, PDO::PARAM_INT);
    $questions->execute();

    $result = array("titles" => array(), "units" => array(), "questions_en" => array(), "questions_es" => array(), "min" => array(), "max" => array(), "type" => array());

    $index = 0;
    while($row = $questions->fetch(PDO::FETCH_ASSOC))
    {
        array_push($result["titles"], array("label" => $row['title'], "index" => $index));
        array_push($result["units"], array("label" => $row['unit'], "index" => $index));
        array_push($result["questions_en"], array("label" => $row['question_text'], "index" => $index));
        array_push($result["questions_es"], array("label" => $row['question_textSPA'], "index" => $index));
        array_push($result["max"], array("label" => $row['answer_max'], "index" => $index));
        array_push($result["min"], array("label" => $row['answer_min'], "index" => $index));
        array_push($result["type"], array("label" => $row['answer_type'], "index" => $index));
        $index++;
    }

    echo json_encode($result);
}
else
{
    $question = $con->prepare("SELECT * FROM probe_questions WHERE id = ?");
    $question->bindValue(1, $id, PDO::PARAM_INT);
    $question->execute();
    $question_row = $question->fetch(PDO::FETCH_ASSOC);
    
    $units = $con->prepare("SELECT * FROM probe_units WHERE probe_question = ?");
    $units->bindValue(1, $id, PDO::PARAM_INT);
    $units->execute();
    $units_arr = array();
    while($units_row = $units->fetch(PDO::FETCH_ASSOC))
    {
        array_push($units_arr, $units_row);
    }
    echo json_encode(array("question" => $question_row, "units" => $units_arr));
}
?>