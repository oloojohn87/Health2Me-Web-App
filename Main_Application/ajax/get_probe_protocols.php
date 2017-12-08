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

$id = -1;
if(isset($_POST['id']))
{
    $id = $_POST['id'];
}

if($id != -1)
{
    $res = $con->prepare("SELECT * FROM probe_protocols WHERE protocolID = ?");
    $res->bindValue(1, $id, PDO::PARAM_INT);
    $res->execute();
    
    $protocol = $res->fetch(PDO::FETCH_ASSOC);
    
    $titles = array('', '', '', '', '');
    $questions_en = array('', '', '', '', '');
    $questions_es = array('', '', '', '', '');
    $min = array(1, 1, 1, 1, 1);
    $max = array(5, 5, 5, 5, 5);
    $answer_types = array(1, 1, 1, 1, 1);
    
    $result = array("name" => $protocol['name'], "description" => $protocol['description']);
    
    for($i = 0; $i < 5; $i++)
    {
        if($protocol['question'.($i + 1)] != NULL)
        {
            $q = $con->prepare("SELECT * FROM probe_questions WHERE id = ?");
            $q->bindValue(1, $protocol['question'.($i + 1)], PDO::PARAM_INT);
            $q->execute();
            $question_row = $q->fetch(PDO::FETCH_ASSOC);
            
            $units = array();
            $q = $con->prepare("SELECT * FROM probe_units WHERE probe_question = ? ORDER BY value ASC");
            $q->bindValue(1, $protocol['question'.($i + 1)], PDO::PARAM_INT);
            $q->execute();
            while($unit_row = $q->fetch(PDO::FETCH_ASSOC))
            {
                array_push($units, array("value" => $unit_row['value'], "label" => $unit_row['label']));
            }

            $result['question_title_'.($i + 1)] = $question_row['title'];
            $result['question_en_'.($i + 1)] = $question_row['question_text'];
            $result['question_es_'.($i + 1)] = $question_row['question_textSPA'];
            $result['question_unit_'.($i + 1)] = $question_row['unit'];
            $result['answer_type_'.($i + 1)] = $question_row['answer_type'];
            $result['answer_max_'.($i + 1)] = $question_row['answer_max'];
            $result['answer_min_'.($i + 1)] = $question_row['answer_min'];
            $result['units_'.($i + 1)] = $units;
        }
        else
        {
            $result['question_title_'.($i + 1)] = '';
            $result['question_en_'.($i + 1)] = '';
            $result['question_es_'.($i + 1)] = '';
            $result['question_unit_'.($i + 1)] = '';
            $result['answer_type_'.($i + 1)] = 1;
            $result['answer_max_'.($i + 1)] = 5;
            $result['answer_min_'.($i + 1)] = 1;
            $result['units_'.($i + 1)] = [];
        }
    }
    
    echo json_encode($result);
}
else
{
    $doc_id = $_POST['doctor'];

    $res = $con->prepare("SELECT * FROM probe_protocols WHERE doctor = ?");
    $res->bindValue(1, $doc_id, PDO::PARAM_INT);
    $res->execute();

    $result = array();
    $questions = array();
    while($row = $res->fetch(PDO::FETCH_ASSOC))
    {
        $q = array();
        for($i = 1; $i <= 5; $i++)
        {
            if($row['question'.$i] != NULL)
            {
                $ques = $con->prepare("SELECT * FROM probe_questions WHERE id = ?");
                $ques->bindValue(1, $row['question'.$i], PDO::PARAM_INT);
                $ques->execute();
                $ques_row = $ques->fetch(PDO::FETCH_ASSOC);
                if($ques_row != null)
                    array_push($q, array("index" => $i, "text" => $ques_row['question_text']));
            }
        }
        $questions[($row['protocolID'])] = $q;
        array_push($result, $row);
    }

    echo json_encode(array("protocols" => $result, "questions" => $questions));
}

?>