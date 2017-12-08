<?php

require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];

$tbl_name="usuarios"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}

$probeID = $_POST['probeID'];
$q = intval($_POST['question']);

$responses = $con->prepare("SELECT * FROM proberesponse WHERE probeID = ? AND question = ? ORDER BY responseTime DESC");
$responses->bindValue(1, $probeID, PDO::PARAM_INT);
$responses->bindValue(2, $q, PDO::PARAM_INT);
$responses->execute();

$probe = $con->prepare("SELECT * FROM probe WHERE probeID = ?");
$probe->bindValue(1, $probeID, PDO::PARAM_INT);
$probe->execute();
$probe_row = $probe->fetch(PDO::FETCH_ASSOC);

$protocol = $con->prepare("SELECT * FROM probe_protocols WHERE protocolID = ?");
$protocol->bindValue(1, $probe_row['protocolID'], PDO::PARAM_INT);
$protocol->execute();
$protocol_row = $protocol->fetch(PDO::FETCH_ASSOC);

$question = $con->prepare("SELECT * FROM probe_questions WHERE id = ?");
$question->bindValue(1, $protocol_row['question'.$q], PDO::PARAM_INT);
$question->execute();
$question_row = $question->fetch(PDO::FETCH_ASSOC);
$max_scale = $question_row['answer_max'];
$min_scale = $question_row['answer_min'];
$inverted = $question_row['inverted'];

$units = array();
$unit = $con->prepare("SELECT * FROM probe_units WHERE probe_question = ?");
$unit->bindValue(1, $protocol_row['question'.$q], PDO::PARAM_INT);
$unit->execute();

while($unit_row = $unit->fetch(PDO::FETCH_ASSOC))
{
    array_push($units, array("value" => $unit_row['value'], "label" => $unit_row['label']));
}


$values = array();
$count = 0;
while($response = $responses->fetch(PDO::FETCH_ASSOC))
{
    if($response['response'] != NULL)
    {
        // add the response to the $values array
        array_push($values, array("value" => $response['response'], "date" => $response['responseTime']));
        
        // increment count
        $count++;
    }
}

$alerts = $con->prepare("SELECT * FROM notifications WHERE auxilary = ? AND type='PRBALR' ORDER BY created DESC");
$alerts->bindValue(1, $probeID, PDO::PARAM_INT);
$alerts->execute();
$alert_data = array();
$count_alerts = 0;
while($response = $alerts->fetch(PDO::FETCH_ASSOC))
{
/*
    if($response['response'] != NULL)
    {
*/
        // add the response to the $values array
        array_push($alert_data, array("message" => $response['message'], "date" => $response['created']));     
        // increment count
        $count_alerts++;
/*
    }
*/
}


$min_value = 0;
$max_value = 0;
$min_days = 0;
$max_days = 0;


$probealert = $con->prepare("SELECT * FROM probe_alerts WHERE probe = ?");
$probealert->bindValue(1, $probeID, PDO::PARAM_INT);
$probealert->execute();
while($probealert_row = $probealert->fetch(PDO::FETCH_ASSOC))
{
    $min_value = $probealert_row['start_value'];
    $max_value = $probealert_row['exp_value'];
    $min_days = $probealert_row['exp_day_1'];
    $max_days = $probealert_row['exp_day_2'];
    //$max_scale = $question_row['exp_value'];
}

$res = array("data" => $values, "units" => $units, "protocol_name" => $protocol_row['name'], "protocol_description" => $protocol_row['description'], "question" => $question_row['question_text'], "question_title" => $question_row['title'], "question_description" => $question_row['question_text'], "question_unit" => $question_row['unit'], "min_value" => $min_value, "max_value" => $max_value, "min_days" => $min_days, "max_days" => $max_days, "max_scale" => $max_scale, "min_scale" => $min_scale, "inverted" => $inverted,"probe_alerts" => $alert_data);

echo json_encode($res);

?>