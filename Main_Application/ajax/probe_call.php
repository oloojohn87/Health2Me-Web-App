<?php

require("environment_detailForLogin.php");
require("push_server.php");
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


$probeID = $_GET['probeID'];

//EXTRACT THE PROBE LANGUAGE
$probeLanguage = 'en';
//if($probe_row['probeLanguage'] != 'en') $probeLanguage = $probe_row['probeLanguage'];

$probe = $con->prepare("SELECT * FROM probe WHERE probeID = ?");
$probe->bindValue(1, $probeID, PDO::PARAM_INT);
$probe->execute();
$probe_row = $probe->fetch(PDO::FETCH_ASSOC);

$protocol = $con->prepare("SELECT * FROM probe_protocols WHERE protocolID = ?");
$protocol->bindValue(1, $probe_row['protocolID'], PDO::PARAM_INT);
$protocol->execute();
$protocol_row = $protocol->fetch(PDO::FETCH_ASSOC);

$question = $con->prepare("SELECT * FROM probe_questions WHERE id = ?");
$question->bindValue(1, $protocol_row[ 'question'.$probe_row['currentQuestion'] ], PDO::PARAM_INT);
$question->execute();
$question_row = $question->fetch(PDO::FETCH_ASSOC);

$doc = $con->prepare("SELECT Surname,Gender FROM doctors WHERE id = ?");
$doc->bindValue(1, $probe_row['doctorID'], PDO::PARAM_INT);
$doc->execute();
$doc_row = $doc->fetch(PDO::FETCH_ASSOC);

$pat = $con->prepare("SELECT Name,Surname,telefono,language,IdUsRESERV,salt FROM usuarios WHERE Identif = ?");
$pat->bindValue(1, $probe_row['patientID'], PDO::PARAM_INT);
$pat->execute();
$pat_row = $pat->fetch(PDO::FETCH_ASSOC);

$probeLanguage = $probe_row['probeLanguage'];

/*if(!empty($pat_row['IdUsRESERV']) && !empty($pat_row['salt'])) {
    $patLang = 'en';
    if($pat_row['language'] == 'th') $patLang = 'es';
    else $patLang = $pat_row['language'];

    if($probeLanguage != $patLang) $probeLanguage = $patLang;
}*/

echo '<?xml version="1.0" encoding="UTF-8"?>';

?>

<Response>
    <?php
        if($probe_row['currentQuestion'] == 1)
        {
            $gender = 'he';
            if($doc_row['Gender'] == 0)
                $gender = 'she';
            
            if($probeLanguage == 'es')
            {
                $gender = 'él';
                if($doc_row['Gender'] == 0)
                    $gender = 'ella';
            }
            //echo '<Pause length="1" />';
            if($probeLanguage == 'en')
            {
                echo '<Say language="en" voice="man">Hello '.$pat_row['Name'].' '.$pat_row['Surname'].'. Doctor '.$doc_row['Surname'].' is requesting information so that '.$gender.' can treat you better. Please answer the following questions with your phone\'s keypad.</Say>';
            }
            else if($probeLanguage == 'es')
            {
               echo '<Say language="es" voice="woman">Hola '.$pat_row['Name'].' '.$pat_row['Surname'].'. El doctor '.$doc_row['Surname'].' está solicitando información para que '.$gender.' puede te tratar mejor. Por favor, responde las siguientes preguntas con el teclado de su teléfono.</Say>';
            }
            //echo '<Pause length="1" />';
        }
        if($question_row['answer_type'] == 2)
        {
            echo '<Gather action="handle_probe_call.php?probeID='.$probeID.'" finishOnKey="#" timeout="8">';
        }
        else
        {
            echo '<Gather action="handle_probe_call.php?probeID='.$probeID.'" numDigits="1" timeout="8">';
        }


        
    ?>
        
    <?php 
        if($probeLanguage == 'en')
        {
            echo '<Say language="en" voice="man">';
            echo 'Question '.$probe_row['currentQuestion'].': '.$question_row['title'].'. '.$question_row['question_text'].'.';
            //echo '<Pause length="1" />';
            if($question_row['answer_type'] != 3)
            {
                echo ' Please enter a number between '.$question_row['answer_min'].' and '.$question_row['answer_max'].'.';
            }
            if($question_row['answer_type'] == 2)
            {
                echo ' Press # when done.';
            }
            if($question_row['answer_type'] == 3)
            {
                echo ' Press 1 for yes, or 2 for no.';
            }
        }
        else if($probeLanguage == 'es')
        {
            echo '<Say language="es" voice="woman">';
            echo 'Pregunta '.$probe_row['currentQuestion'].': '.$question_row['title'].'. '.$question_row['question_textSPA'].'.';
            //echo '<Pause length="1" />';
            if($question_row['answer_type'] != 3)
            {
                echo ' Por favor, presione un número entre '.$question_row['answer_min'].' y '.$question_row['answer_max'].'.';
            }
            if($question_row['answer_type'] == 2)
            {
                echo ' Presione almohadilla cuando haya terminado.';
            }
            if($question_row['answer_type'] == 3)
            {
                echo ' Presione 1 para si, o 2 para no.';
            }
        }
    ?>
        </Say>
    </Gather>
    
</Response>
        
