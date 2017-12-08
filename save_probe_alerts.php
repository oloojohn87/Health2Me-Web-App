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
$pat = $_POST['patient'];
$probe = $con->prepare("SELECT probeID FROM probe WHERE doctorID = ? AND patientID = ?");
$probe->bindValue(1, $doc, PDO::PARAM_INT);
$probe->bindValue(2, $pat, PDO::PARAM_INT);
$probe->execute();
$probe_row = $probe->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['clear']) && $_POST['clear'] == true)
{
    $del = $con->prepare("DELETE FROM probe_alerts WHERE probe = ?");
    $del->bindValue(1, $probe_row['probeID'], PDO::PARAM_INT);
    $del->execute();
    echo 1;
}
else
{
    $alerts = json_decode($_POST['alerts'], true);
    

    $al = $con->prepare("SELECT * FROM probe_alerts WHERE probe = ?");
    $al->bindValue(1, $probe_row['probeID'], PDO::PARAM_INT);
    $al->execute();

    $res = true;

    $indexes = array();

    while($al_row = $al->fetch(PDO::FETCH_ASSOC))
    {
        array_push($indexes, $al_row['question']);
        $success = true;
        if(!isset($alerts[($al_row['question']) - 1]['start_value']) || strlen($alerts[($al_row['question']) - 1]['start_value']) == 0)
            $success = false;
        if(!isset($alerts[($al_row['question']) - 1]['exp_value']) || strlen($alerts[($al_row['question']) - 1]['exp_value']) == 0)
            $success = false;
        if(!isset($alerts[($al_row['question']) - 1]['exp_day_1']) || strlen($alerts[($al_row['question']) - 1]['exp_day_1']) == 0)
            $success = false;
        if(!isset($alerts[($al_row['question']) - 1]['exp_day_2']) || strlen($alerts[($al_row['question']) - 1]['exp_day_2']) == 0)
            $success = false;

        if($success)
        {
            $upd = $con->prepare("UPDATE probe_alerts SET start_value = ?, exp_value = ?, exp_day_1 = ?, exp_day_2 = ?, tolerance = ? WHERE probe = ? AND question = ?");
            $upd->bindValue(1, $alerts[($al_row['question']) - 1]['start_value'], PDO::PARAM_INT);
            $upd->bindValue(2, $alerts[($al_row['question']) - 1]['exp_value'], PDO::PARAM_INT);
            $upd->bindValue(3, $alerts[($al_row['question']) - 1]['exp_day_1'], PDO::PARAM_INT);
            $upd->bindValue(4, $alerts[($al_row['question']) - 1]['exp_day_2'], PDO::PARAM_INT);
            $upd->bindValue(5, $alerts[($al_row['question']) - 1]['tolerance'], PDO::PARAM_INT);
            $upd->bindValue(6, $probe_row['probeID'], PDO::PARAM_INT);
            $upd->bindValue(7, $al_row['question'], PDO::PARAM_INT);
            $upd->execute();

        }
        else
        {
            $res = false;
        }

    }

    $alerts_count = count($alerts);
    for($i = 0; $i < $alerts_count; $i++)
    {
        if(!in_array(($i + 1), $indexes))
        {
            $success = true;
            if(!isset($alerts[$i]['start_value']) || strlen($alerts[$i]['start_value']) == 0)
                $success = false;
            if(!isset($alerts[$i]['exp_value']) || strlen($alerts[$i]['exp_value']) == 0)
                $success = false;
            if(!isset($alerts[$i]['exp_day_1']) || strlen($alerts[$i]['exp_day_1']) == 0)
                $success = false;
            if(!isset($alerts[$i]['exp_day_2']) || strlen($alerts[$i]['exp_day_2']) == 0)
                $success = false;

            if($success)
            {
                $upd = $con->prepare("INSERT INTO probe_alerts SET start_value = ?, exp_value = ?, exp_day_1 = ?, exp_day_2 = ?, tolerance = ?, probe = ?, question = ?");
                $upd->bindValue(1, $alerts[$i]['start_value'], PDO::PARAM_INT);
                $upd->bindValue(2, $alerts[$i]['exp_value'], PDO::PARAM_INT);
                $upd->bindValue(3, $alerts[$i]['exp_day_1'], PDO::PARAM_INT);
                $upd->bindValue(4, $alerts[$i]['exp_day_2'], PDO::PARAM_INT);
                $upd->bindValue(5, $alerts[$i]['tolerance'], PDO::PARAM_INT);
                $upd->bindValue(6, $probe_row['probeID'], PDO::PARAM_INT);
                $upd->bindValue(7, ($i + 1), PDO::PARAM_INT);
                $upd->execute();

            }
        }
    }


    if($res)
        echo 1;
    else
        echo 0;
}

?>