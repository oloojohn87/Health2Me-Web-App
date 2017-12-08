<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<link rel="stylesheet" href="css/network_styles.css">
<script type="text/javascript">
    var lang = new Lang('en');
    window.lang.dynamic('th', 'jquery-lang-js-master/js/langpack/th.json');


    //alert($.cookie('lang'));

    var langType = $.cookie('lang');

    if(langType == 'th'){
        window.lang.change('th');
        //alert('th');
    }

    if(langType == 'en'){
        window.lang.change('en');
        //alert('en');
    }


</script>-->
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


if (empty($_GET['Usuario'])) {
    $getUsu = "";
    //$probeFlag = false;
}
else {
    $getUsu = $_GET['Usuario'];
    //$probeFlag = true;
}

$queUsu = '%'.$getUsu.'%';
$UserDOB = $_GET['UserDOB'];
//$NReports = $_GET['NReports'];
$IdDoc = $_GET['IdDoc'];
$start = $_GET['start'];
$single_user = -1;
if(isset($_GET['SingleUser']))
    $single_user = $_GET['SingleUser'];
$numDisplay = 10;

$TotMsg = 0;
$TotUpDr = 0;
$TotUpUs = 0;
//$row = mysql_fetch_array($result);
/*
$connQuery = "SELECT * FROM usuarios WHERE Password IS NOT NULL AND ( IdCreator = '$IdDoc' OR IdCreator IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = '$MyGroup') OR Identif IN (SELECT IdUs FROM doctorslinkusers WHERE IdMED = '$IdDoc')) AND Surname like '%$queUsu%' ORDER BY Surname ASC";
$connResult = mysql_query($connQuery);
$count=mysql_num_rows($connResult);
$UConn =  $count;

$totalResult = mysql_query("SELECT * FROM usuarios WHERE Surname like '%$queUsu%' AND (IdCreator = '$IdDoc' OR IdCreator IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = '$MyGroup') OR Identif IN (SELECT IdUs FROM doctorslinkusers WHERE IdMED = '$IdDoc') OR Identif IN (SELECT IdPac FROM doctorslinkdoctors WHERE IdMED2 = '$IdDoc')) ORDER BY Surname ASC");
$count=mysql_num_rows($totalResult);
$UTotal =  $count;
*/
/*$result = $con->prepare("SELECT * FROM usuarios WHERE IdUsRESERV IS NOT NULL AND Surname like ? 
AND (IdCreator = ? OR IdCreator IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = ?) 
OR Identif IN (SELECT IdUs FROM doctorslinkusers WHERE IdMED = ?) 
OR Identif IN (SELECT IdPac FROM doctorslinkdoctors 
WHERE IdMED2 = ?)) ORDER BY Surname ASC LIMIT ?, $numDisplay");*/


if($single_user == -1)
{//$probeFlag = true;
    if(isset($_GET['filter'])){
        if($_GET['filter'] == 'trackUp') {
            $Var = 'P.creationDate, ';
            $orderVar = 'P.creationDate DESC, ';
            //$probeFlag = true;
        }
        else if($_GET['filter'] == 'prbUp') {
            $Var = 'P.creationDate, ';
            $orderVar = 'P.creationDate DESC, ';
            //$probeFlag = true;
        }
        else if($_GET['filter'] == 'sumUp') {
                $Var = 'S.Timestamp, ';
                $orderVar = 'S.Timestamp DESC, ';
                //$probeFlag = true;
        }else {
            if($_GET['filter'] == 'repUp') {
                $Var = 'L.FechaInput, ';
                $orderVar = 'L.FechaInput DESC, ';
            }
            
            else if($_GET['filter'] == 'msgUp') {
                $Var = 'M.fecha, ';
                $orderVar = 'M.fecha DESC, ';
            }

            else if($_GET['filter'] == 'apptUp') {
                $Var = 'A.date_created, ';
                $orderVar = 'A.date_created DESC, ';
            }
        }
    }
    else {
        $orderVar = '';
        $Var = '';
    }
    $sql = "SELECT SQL_CALC_FOUND_ROWS ".$Var."U.* FROM 
  
                (SELECT Identif AS id FROM usuarios WHERE IdCreator = :idDocforCreator
                UNION
                SELECT IdUs AS id FROM doctorslinkusers WHERE IdMED = :idDocforDLU
                UNION
                SELECT IdPac AS id FROM doctorslinkdoctors WHERE IdMED2 = :idDocforDLD) DL
                
                INNER JOIN
                
                usuarios U ON U.Identif = DL.id";

    
    //FILTER KICK-IN
    if(isset($_GET['filter'])){
        if($_GET['filter'] == 'trackUp') $sql .= " INNER JOIN
        (SELECT DISTINCT patientID, creationDate FROM probe WHERE doctorPermission = 1 AND patientPermission = 1 AND scheduledEndDate >= SYSDATE() AND doctorID = :idDocforTrkUp GROUP BY patientID) P ON DL.id = P.patientID"; 
        
        if($_GET['filter'] == 'prbUp') $sql .= " INNER JOIN
        (SELECT DISTINCT patientID, creationDate FROM probe WHERE scheduledEndDate >= SYSDATE() AND (creationDate BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) AND doctorID = :idDocforPrbUp GROUP BY patientID) P ON DL.id = P.patientID";
        
        if($_GET['filter'] == 'repUp') $sql .= " INNER JOIN
            (SELECT IdUsu, FechaInput FROM lifepin WHERE (FechaInput BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) GROUP BY IdUsu) L ON DL.id = L.IdUsu";

        if($_GET['filter'] == 'sumUp') $sql .= " INNER JOIN
        (SELECT DISTINCT IdUsu, Timestamp FROM p_log WHERE (Timestamp BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) GROUP BY IdUsu) S ON DL.id = S.IdUsu";

        if($_GET['filter'] == 'msgUp') $sql .= " INNER JOIN
        (SELECT DISTINCT patient_id, fecha FROM message_infrastructureuser WHERE sender_id = :idDocforMsgUp AND tofrom='to' AND status = 'new' AND sender_id IS NOT NULL AND (fecha BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) GROUP BY patient_id) M ON DL.id = M.patient_id";  

        if($_GET['filter'] == 'apptUp') $sql .= " INNER JOIN
    (SELECT DISTINCT pat_id, date_created FROM appointments WHERE med_id = :idDocforApptUp AND (date_created BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) GROUP BY pat_id) A ON DL.id = A.pat_id";  
    }

    
    $sql .= " where U.Surname like :qSurName or U.Name like :qName or U.IdUsFIXEDNAME like :qFixName ORDER BY ".$orderVar."U.Surname ASC LIMIT :limit, ".$numDisplay;
    
    $result = $con->prepare($sql);
    
    
    /*else {
        $result->bindValue(':idDocforGrp', $IdDoc, PDO::PARAM_INT);
        $result->bindValue(':idDocforDLD', $IdDoc, PDO::PARAM_INT);
        $result->bindValue(':idDocforDLU', $IdDoc, PDO::PARAM_INT);
        $result->bindValue(':idDocforCreator', $IdDoc, PDO::PARAM_INT);   
    }*/
    
    $result->bindValue(':idDocforCreator', $IdDoc, PDO::PARAM_INT);
    $result->bindValue(':idDocforDLU', $IdDoc, PDO::PARAM_INT);
    $result->bindValue(':idDocforDLD', $IdDoc, PDO::PARAM_INT);
    //FILTER BINDVALUES
    if(isset($_GET['filter'])){
         if($_GET['filter'] == 'trackUp') {
            $result->bindValue(':idDocforTrkUp', $IdDoc, PDO::PARAM_INT);
        }      
        /*else if($_GET['filter'] == 'repUp') {
            $result->bindValue(':idDocforRepUp', $IdDoc, PDO::PARAM_INT);
        }*/
        else if($_GET['filter'] == 'msgUp') {
            $result->bindValue(':idDocforMsgUp', $IdDoc, PDO::PARAM_INT);
        }
        else if($_GET['filter'] == 'prbUp') {
            $result->bindValue(':idDocforPrbUp', $IdDoc, PDO::PARAM_INT);
        }
        else if($_GET['filter'] == 'apptUp') {
            $result->bindValue(':idDocforApptUp', $IdDoc, PDO::PARAM_INT);
        }
    }
    $result->bindValue(':qSurName', $queUsu, PDO::PARAM_STR);
    $result->bindValue(':qName', $queUsu, PDO::PARAM_STR);
    $result->bindValue(':qFixName', $queUsu, PDO::PARAM_STR); 
    $result->bindValue(':limit', $start, PDO::PARAM_INT);
}
else
{
    $result = $con->prepare("Select * FROM usuarios WHERE Identif = ?");
    $result->bindValue(1, $single_user, PDO::PARAM_INT);
}

//$result->bindValue(1, '%'.$queUsu.'%', PDO::PARAM_STR);
//$result->bindValue(2, $IdDoc, PDO::PARAM_INT);
//$result->bindValue(3, $MyGroup, PDO::PARAM_INT);
//$result->bindValue(4, $IdDoc, PDO::PARAM_INT);
//$result->bindValue(5, $IdDoc, PDO::PARAM_INT);
//$result->bindValue(6, $start, PDO::PARAM_INT);
$result->execute();
$rows = $result->fetchAll(PDO::FETCH_ASSOC);

//GET THE LAST PAGE NUMBER
$last_page = $con->query('SELECT FOUND_ROWS()')->fetchColumn();

//echo "<div id='directory'><div id='show_rows'>";

foreach ($rows as $row) {
//while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

    //if (!$row['email']) 
    //$row['email']='.';   

    $PatientId = $row['Identif'];


    $vitals_count = 0;
    //echo $PatientId;
    
    /*
    //THIS CALCULATES THE VITALS FOR DOCTOR STATS ON MEMBER NETWORK
						//Habits...
						$result3 = $con->prepare("SELECT * FROM p_habits WHERE idpatient = ? AND (latest_update BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
						$result3->bindValue(1, $PatientId, PDO::PARAM_INT);
						$result3->execute();
						
						$count_habits = $result3->rowCount();
						$vitals_count = $vitals_count + $count_habits;
						
						//Vaccinations...
						$result4 = $con->prepare("SELECT * FROM p_immuno WHERE idpatient = ? AND VaccCode != '' AND (latest_update BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
						$result4->bindValue(1, $PatientId, PDO::PARAM_INT);
						$result4->execute();
						
						$count_vacc = $result4->rowCount();
						$vitals_count = $vitals_count + $count_vacc;
						
						//Allergies...
						$result4 = $con->prepare("SELECT * FROM p_immuno WHERE idpatient = ? AND AllerCode != '' AND (latest_update BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
						$result4->bindValue(1, $PatientId, PDO::PARAM_INT);
						$result4->execute();
						
						$count_aller = $result4->rowCount();
						$vitals_count = $vitals_count + $count_aller;
						
						//Family...
						$result5 = $con->prepare("SELECT * FROM p_family WHERE idpatient = ? AND (latest_update BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
						$result5->bindValue(1, $PatientId, PDO::PARAM_INT);
						$result5->execute();
						
						$count_family = $result5->rowCount();
						$vitals_count = $vitals_count + $count_family;
						
						//Medications...
						$result6 = $con->prepare("SELECT * FROM p_medication WHERE idpatient = ? AND (latest_update BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
						$result6->bindValue(1, $PatientId, PDO::PARAM_INT);
						$result6->execute();
						
						$count_meds = $result6->rowCount();
						$vitals_count = $vitals_count + $count_meds;
						
						//Diagnostics...
						$result7 = $con->prepare("SELECT * FROM p_diagnostics WHERE idpatient = ? AND (latest_update BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
						$result7->bindValue(1, $PatientId, PDO::PARAM_INT);
						$result7->execute();
						
						$count_diag = $result7->rowCount();
						$vitals_count = $vitals_count + $count_diag;
						//END CALCULATIONS...*/
    
    //New Report Update Within 30 Days
    $result2 = $con->prepare("SELECT IdUsu FROM lifepin WHERE IdUsu = ? AND (FechaInput BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
    $result2->bindValue(1, $PatientId, PDO::PARAM_INT);
    //$result2->bindValue(2, $IdDoc, PDO::PARAM_INT);
    $result2->execute();

    //OR Content = 'Report Verified'
    $count2 = $result2->rowCount();
    $new_reports = "";
    if ($count2 >0) $new_reports = "patient_row_button_active"; 

    //New Message Unread Within 30 Days
    $result3 = $con->prepare("SELECT tofrom FROM message_infrastructureuser WHERE receiver_id = ? AND patient_id = ? AND sender_id IS NOT NULL AND status='new' ");
    $result3->bindValue(1, $IdDoc, PDO::PARAM_INT);
    $result3->bindValue(2, $PatientId, PDO::PARAM_INT);
    $result3->execute();

    $row3 = $result3->fetch(PDO::FETCH_ASSOC);
    $new_messages = "";
    if ($row3['tofrom'] == 'to') $new_messages = "patient_row_button_active";
    else if ($row3['tofrom'] == 'from') $new_messages = "patient_row_button_half_active";


    //Browsing activity from the User
    /* This retrieves only the last 30 days
    $result2 = $con->prepare("SELECT * FROM bpinview USE INDEX(I1) WHERE VIEWIdUser = ? AND VIEWIdMed = ? AND (DateTimeSTAMP BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) ");
    $result2->bindValue(1, $PatientId, PDO::PARAM_INT);
    $result2->bindValue(2, $PatientId, PDO::PARAM_INT);
    $result2->execute();

    //$result2 = mysql_query("SELECT * FROM bpinview WHERE IDPIN IN (SELECT IdPin FROM lifepin WHERE IdUsu = '$PatientId') AND VIEWIdMed = '$PatientId' AND (DateTimeSTAMP BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) ");
    $count2 = $result2->rowCount();
    if ($count2 >0)
    {    
        $webactivWEB_color ='#54bc00';
        $webactivMOB_color ='#e0e0e0';
    } 
    else
    {
        $webactivWEB_color ='#e0e0e0';
        $webactivMOB_color ='#e0e0e0';
    }

    //Upload activity from Doctors
    $result2 = $con->prepare("SELECT * FROM bpinview USE INDEX(I1) WHERE VIEWIdUser = ? AND VIEWIdMed <> ? AND (DateTimeSTAMP BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) AND (Content = 'Report Uploaded') ");
    $result2->bindValue(1, $PatientId, PDO::PARAM_INT);
    $result2->bindValue(2, $PatientId, PDO::PARAM_INT);
    $result2->execute();

    //OR Content = 'Report Verified'
    $count2 = $result2->rowCount();
    if ($count2 >0)
    {    
        $visible_baloon3 = 'visible';
        $NewRepDoc = $count2;
        $repactivUpDr_color ='#54bc00';
    } 
    else
    {
        $visible_baloon3 = 'hidden';
        $NewRepDoc = 0;
        $repactivUpDr_color ='#e0e0e0';
    }
    $TotUpDr = $TotUpDr + $NewRepDoc;

    //Upload activity from Users
    $result2 = $con->prepare("SELECT * FROM bpinview USE INDEX(I1) WHERE VIEWIdUser = ? AND VIEWIdMed = ? AND (DateTimeSTAMP BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) AND (Content = 'Report Uploaded') ");
    $result2->bindValue(1, $PatientId, PDO::PARAM_INT);
    $result2->bindValue(2, $IdDoc, PDO::PARAM_INT);
    $result2->execute();

    $count2 = $result2->rowCount();
    $new_reports = "";
    //echo '  ********************** COUNT = '.$count2;
    if ($count2 >0)
    {    
        $visible_baloon2 = 'visible';
        $NewRepPat = $count2;
        $repactivUpUser_color ='#54bc00';
        $new_reports = "patient_row_button_active";
    } 
    else
    {
        $visible_baloon2 = 'hidden';
        $NewRepPat =0;
        $repactivUpUser_color ='#e0e0e0';
    }
    $TotUpUs = $TotUpUs + $NewRepPat;*/
    
    

    //Upload probes from Users
    $result4 = $con->prepare("SELECT * FROM probe WHERE patientID = ? && doctorID = ?");
    $result4->bindValue(1, $PatientId, PDO::PARAM_INT);
    $result4->bindValue(2, $IdDoc, PDO::PARAM_INT);
    $result4->execute();

    $row6 = $result4->fetch(PDO::FETCH_ASSOC);
    $num1 = $result4->rowCount();
    
    $probeID = $row6['probeID'];

    $Totprobe = 0;

    $result5 = $con->prepare("SELECT * FROM probe WHERE probeID = ? AND doctorPermission = 1 AND patientPermission = 1");
    $result5->bindValue(1, $row6['probeID'], PDO::PARAM_INT);
    $result5->execute();

    $count5 = $result5->rowCount();
    //echo '  ********************** COUNT = '.$count2;
    $Totprobe += $count5;
    
    $result10 = $con->prepare("SELECT * FROM notifications WHERE sender = ? AND is_sender_doctor = 0 AND receiver = ? AND is_receiver_doctor = 1 AND type = 'PRBALR' AND dismissed = 0 ORDER BY created DESC");
    $result10->bindValue(1, $PatientId, PDO::PARAM_INT);
    $result10->bindValue(2, $IdDoc, PDO::PARAM_INT);
    $result10->execute();

    $num_alerts = $result10->rowCount();

    $new_probes = "";
    if ($Totprobe >0)
    {    
        $new_probes = "patient_row_button_active";
        $probe_baloon = 'visible';
        $probe_color ='#54bc00';
    } 
    else
    {
        $probe_baloon = 'hidden';
        $probe_color ='#e0e0e0';
    }
    
    
    if($num_alerts > 0)
    {
        // alert
        $alert = $result10->fetch(PDO::FETCH_ASSOC);
        $probe_icon = '<div id="ALERT_'.$PatientId.'_'.$alert['id'].'_'.$alert['created'].'" class="CreateProbe patient_row_button '.$new_probes.'" style="background-color: #D84840; color: #FFF; border-color: #D84840; -webkit-animation: glow 1.5s linear infinite;" title="Alerted">
                <i id="'.$PatientId.'"  class="icon-signal"></i></div>';
    }
    else if($Totprobe > 0)
    {
        // probe responses received
        $probe_icon = '<div id="'.$PatientId.'" class="CreateProbe patient_row_button '.$new_probes.'" style="background-color: #22AEFF; color: #FFF; border-color: #22AEFF;" title="Active">
                <i id="'.$PatientId.'"  class="icon-signal"></i></div>';
    }
    else if($num1>=1 && ($row6['patientPermission'] == 0 || $row6['doctorPermission'] == 0))
    {
        // pending
        $probe_icon = '<div id="'.$PatientId.'" class="CreateProbe patient_row_button '.$new_probes.'" style="background-color: #EAEAEA; color: #22AEFF; border-color: #CCC;" title="Inactive">
                <i id="'.$PatientId.'"  class="icon-signal"></i></div>';
    }
    else if($num1>=1 && $row6['patientPermission'] == 1 && $row6['doctorPermission'] == 1)
    {
        // activated
        $probe_icon = '<div id="'.$PatientId.'" class="CreateProbe patient_row_button '.$new_probes.'" style="background-color: #96CD6A; color: #FFF; border-color: #96CD6A;" title="Purchased">
                <i id="'.$PatientId.'"  class="icon-signal"></i></div>';
    }
    else
    {
        // default
        $probe_icon = '<div id="'.$PatientId.'" class="CreateProbe patient_row_button '.$new_probes.'" style="background-color: #FFF; color: #22AEFF; border-color: #CCC;">
                <i id="'.$PatientId.'"  class="icon-signal"></i></div>';
    }
    
    $units = array();
    // probe's protocol
    $protocol = $con->prepare("SELECT * FROM probe_protocols WHERE protocolID = ?");
    $protocol->bindValue(1, $row6['protocolID'], PDO::PARAM_INT);
    $protocol->execute();
    $probe_protocol = $protocol->fetch(PDO::FETCH_ASSOC);
    $protocol_disabled_tags = array('', '', '', '', '');
    for($i = 0; $i < 5; $i++)
    {
        if($probe_protocol['question'.($i + 1)] != null)
        {
            $ques_unit = $con->prepare("SELECT * FROM probe_units WHERE probe_question = ? ORDER BY value ASC");
            $ques_unit->bindValue(1, $probe_protocol['question'.($i + 1)], PDO::PARAM_INT);
            $ques_unit->execute();
            
            $arr = array();
            
            while($ques_unit_row = $ques_unit->fetch(PDO::FETCH_ASSOC))
            {
                array_push($arr, array("label" => $ques_unit_row['label'], "value" => $ques_unit_row['value']));
            }
            array_push($units, $arr);
        }
    }
    
    
    if($probe_protocol['question1'] == NULL)
        $protocol_disabled_tags[0] = 'disabled';
    if($probe_protocol['question2'] == NULL)
        $protocol_disabled_tags[1] = 'disabled';
    if($probe_protocol['question3'] == NULL)
        $protocol_disabled_tags[2] = 'disabled';
    if($probe_protocol['question4'] == NULL)
        $protocol_disabled_tags[3] = 'disabled';
    if($probe_protocol['question5'] == NULL)
        $protocol_disabled_tags[4] = 'disabled';
    //THIS PULLS PROBE HISTORY FOR CHART.JS GRAPH//////////////////////////////////////////////////////////////////
    //$result3 = $con->prepare("SELECT * FROM proberesponse WHERE probeID = ? ORDER BY id ASC LIMIT 7");
    $result3 = $con->prepare("SELECT * FROM proberesponse WHERE probeID = ? ORDER BY question ASC, responseTime ASC");
    $result3->bindValue(1, $row6['probeID'], PDO::PARAM_INT);
    $result3->execute();
      
    $history_count = $result3->rowCount();
    
    // inside the $probe_array, create 5 arrays that represent each of the 5 possible questions that a probe can have
    $probe_array = array(/*array(), array(), array(), array(), array()*/);
    $probe_labels = array();
    $last_probe_question = -1;
    $last_probe_value = 0;
    $probe_notification_array = array(array('last_value' => -1, 'movement' => 0, 'updated' => null, 'question' => ''), 
                                      array('last_value' => -1, 'movement' => 0, 'updated' => null, 'question' => ''), 
                                      array('last_value' => -1, 'movement' => 0, 'updated' => null, 'question' => ''), 
                                      array('last_value' => -1, 'movement' => 0, 'updated' => null, 'question' => ''), 
                                      array('last_value' => -1, 'movement' => 0, 'updated' => null, 'question' => ''));
    $probe_notification_count = 0;
    while($probe_history = $result3->fetch(PDO::FETCH_ASSOC))
    {
        $date = date_create($probe_history['responseTime']);
        $format_date =  date_format($date,"d M");
        //echo "<script>console.log( 'Date: " .$format_date. "' );</script>";
        
        // only add the label if it doesn't already exist
        //if(!in_array($format_date, $probe_labels))
        //{
            $probe_labels[] = $format_date;
        //}
        
        // get the question of the probe that this response belongs to
        $question = 1;
        if(isset($probe_history['question']))
        {
            $question = $probe_history['question'];
        }
        
        // add the response to the appropriate array
        array_push($probe_array/*[$question - 1]*/, $probe_history['response']);
        
        // get question
        $ques = $con->prepare("SELECT question_text,unit FROM probe_questions WHERE id = ?");
        $ques->bindValue(1, $probe_protocol[('question'.$probe_history['question'])], PDO::PARAM_INT);
        $ques->execute();
        $ques_row = $ques->fetch(PDO::FETCH_ASSOC);
        $probe_notification_array[$question - 1]['question'] = $ques_row['question_text'];
        
        if($question == $last_probe_question)
        {
            if($probe_history['response'] > $last_probe_value)
                $probe_notification_array[$question - 1]['movement'] = 1;
            else if($probe_history['response'] < $last_probe_value)
                $probe_notification_array[$question - 1]['movement'] = -1;
            else
                $probe_notification_array[$question - 1]['movement'] = 0;
                
            if(isset($units[$question - 1]) && count($units[$question - 1]) > 0)
            {
                for($k = 0; $k < count($units[$question - 1]); $k++)
                {
                    if($probe_history['response'] <= $units[$question - 1][$k]['value'])
                    {
                        $probe_notification_array[$question - 1]['last_value'] = $units[$question - 1][$k]['label'];
                        break;
                    }
                }
            }
            else
            {
                $probe_notification_array[$question - 1]['last_value'] = $probe_history['response'].' '.$ques_row['unit'];
            }
            $probe_notification_array[$question - 1]['updated'] = $probe_history['responseTime'];
        }
        else
        {
            $last_probe_question = $question;
            if(isset($units[$question - 1]) && count($units[$question - 1]) > 1)
            {
                for($k = 0; $k < count($units[$question - 1]); $k++)
                {
                    if($probe_history['response'] <= $units[$question - 1][$k]['value'])
                    {
                        $probe_notification_array[$question - 1]['last_value'] = $units[$question - 1][$k]['label'];
                        break;
                    }
                }
            }
            else
            {
                $probe_notification_array[$question - 1]['last_value'] = $probe_history['response'].' '.$ques_row['unit'];
            }
            $probe_notification_array[$question - 1]['updated'] = $probe_history['responseTime'];
            $probe_notification_count++;
            
        }
        $last_probe_value = $probe_history['response'];
        
        /*if(strlen($ques_row['units']) > 0 && $ques_row['units'] != NULL)
        {
            if(substr_count($ques_row['units'], '_') > 0)
            {
                $units = explode('_', $ques_row['units']);
                $probe_notification_array[$question - 1]['last_value'] = $units[($probe_notification_array[$question - 1]['last_value'] - 1)];
            }
            else
            {
                $probe_notification_array[$question - 1]['last_value'] .= $ques_row['units'];
            }
        }*/
        
    }
    
    // build probe notification area
    $probe_notifications = '<div style="width: 30%; height: 40px; float: left; margin-left: 15px; border: 1px solid #D8D8D8; margin-top: -10px; border-radius: 5px; overflow: hidden;">';
    $probe_notifications_colors = array('#D84840', '#22AEFF', '#54BC00');
    $probe_notifications_icons = array('icon-arrow-down', '', 'icon-arrow-up');
    $probe_notifications_width = (((5 - $probe_notification_count) * 10) + 60) - 2;
    for($i = 0; $i < count($probe_notification_array); $i++)
    {
        $bg_col = '#E8E8E8';
        if($i % 2 == 0)
            $bg_col = '#FAFAFA';
        if($probe_notification_array[$i]['last_value'] != -1)
        {
            if($i == 0)
            {
                $probe_notifications .= '<div class="probe_display" style="width: '.$probe_notifications_width.'%; height: 20px; padding-left: 2%; padding-top: 10px; padding-bottom: 10px; background-color: '.$bg_col.'; float: left; overflow: hidden; color:'.$probe_notifications_colors[($probe_notification_array[$i]['movement'] + 1)].'; font-size: 18px;">';
            }
            else
            {
                $probe_notifications .= '<div class="probe_display" style="width: 8%; height: 20px; padding-left: 2%; padding-top: 10px; padding-bottom: 10px; background-color: '.$bg_col.'; float: left; overflow: hidden; color:'.$probe_notifications_colors[($probe_notification_array[$i]['movement'] + 1)].'; font-size: 18px;">';
            }
            $probe_notifications .= '<div style="width: 14px; height: 25px; font-size: 23px; float: left; margin-top: -5px;">';
            if($probe_notification_array[$i]['movement'] == 0)
                $probe_notifications .= '=';
            else
                $probe_notifications .= '<i class="'.$probe_notifications_icons[($probe_notification_array[$i]['movement'] + 1)].'" style="float: left; font-size: 18px; margin-top: 5px;"></i>';
            $probe_notifications .= '</div>';
            if($i == 0)
            {
                $probe_notifications .= '<div style="float: left; margin-left: 5px; margin-top: -5px; width: 86%;">';
            }
            else
            {
                $probe_notifications .= '<div style="float: left; margin-left: 5px; margin-top: -5px; width: 86%; display: none;">';
            }
            $probe_notifications .= '<div id="probe_info_button_'.$PatientId.'_'.($i + 1).'" style="float: right; font-size: 16px; margin-right: 5px; color: #777; margin-top: 5px;"><i class="icon-info-sign"></i></div>';
            $probe_notifications .= $probe_notification_array[$i]['last_value'];
            $probe_notifications .= '<div id="probe_date_label_'.$PatientId.'_'.($i + 1).'" style="color: #888; font-size: 10px; margin-top: -5px;">updated 2 days ago</div></div>';
            
            $probe_notifications .= '<script>
                                        Tipped.create("#probe_info_button_'.$PatientId.'_'.($i + 1).'", "'.$probe_notification_array[$i]['question'].'");
                                        
                                        $("#probe_date_label_'.$PatientId.'_'.($i + 1).'").text(moment("'.$probe_notification_array[$i]['updated'].'", "YYYY-MM-DD hh:mm:ss").fromNow());
                                    </script>';
            $probe_notifications .= '</div>';
        }
    }
    $probe_notifications .= '</div>';
    if($probe_notification_count == 0)
    {
        $probe_notifications = '<div style="width: 30%; height: 40px; float: left; margin-left: 15px; border: 1px solid #FFF; margin-top: -10px; border-radius: 5px; overflow: hidden;"></div>';
    }

    $missing_data = 7 - $history_count;
    if($missing_data > 0){
        for($i=0; $i<$missing_data; $i++){
            array_unshift($probe_labels, 'No Probe');
            array_unshift($probe_array, 0);
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /*Upload referrals from doctor
    $result2 = $con->prepare("SELECT * FROM doctorslinkdoctors WHERE (IdMED = ? OR IdMED2 = ?) AND IdPac = ? AND (Fecha BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
    $result2->bindValue(1, $IdDoc, PDO::PARAM_INT);
    $result2->bindValue(2, $IdDoc, PDO::PARAM_INT);
    $result2->bindValue(3, $PatientId, PDO::PARAM_INT);
    $result2->execute();

    $count2 = $result2->rowCount();
    //echo '  ********************** COUNT = '.$count2;
    if ($count2 >0)
    {    
        $referral_baloon = 'visible';
        $referral_color ='#54bc00';
    } 
    else
    {
        $referral_baloon = 'hidden';
        $referral_color ='#e0e0e0';
    }
    $referral_count = $count2;
    $new_consultations = "hidden";
    //Video consultations with doctor
    $result2 = $con->prepare("SELECT * FROM consults WHERE Doctor = ? AND Patient = ? AND Status='Completed' AND Type='video' AND (DateTime BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
    $result2->bindValue(1, $IdDoc, PDO::PARAM_INT);
    $result2->bindValue(2, $PatientId, PDO::PARAM_INT);
    $result2->execute();

    $count2 = $result2->rowCount();
    //echo '  ********************** COUNT = '.$count2;
    if ($count2 >0)
    {    
        $video_baloon = 'visible';
        $video_color ='#54bc00';
        $new_consultations = "visible";
    } 
    else
    {
        $video_baloon = 'hidden';
        $video_color ='#e0e0e0';
    }
    $video_count = $count2;

    //Phone consultations with doctor
    $result2 = $con->prepare("SELECT * FROM consults WHERE Doctor = ? AND Patient = ? AND Status='Completed' AND Type='phone' AND (DateTime BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
    $result2->bindValue(1, $IdDoc, PDO::PARAM_INT);
    $result2->bindValue(2, $PatientId, PDO::PARAM_INT);
    $result2->execute();

    $count2 = $result2->rowCount();
    //echo '  ********************** COUNT = '.$count2;
    if ($count2 >0)
    {    
        $phone_baloon = 'visible';
        $phone_color ='#54bc00';
        $new_consultations = "visible";
    } 
    else
    {
        $phone_baloon = 'hidden';
        $phone_color ='#e0e0e0';
    }
    $phone_count = $count2;*/
    
    //Consultations with doctor
    $result7 = $con->prepare("SELECT COUNT(*) FROM consults WHERE Doctor = ? AND Patient = ? AND Status='Completed' AND (DateTime BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
    $result7->bindValue(1, $IdDoc, PDO::PARAM_INT);
    $result7->bindValue(2, $PatientId, PDO::PARAM_INT);
    $result7->execute();

    $count7 = $result7->fetchColumn();
    //echo '  ********************** COUNT = '.$count2;
    $new_consultations = "hidden";
    if ($count7 >0) $new_consultations = "visible";
    
    //Summary Edits
    $result8 = $con->prepare("SELECT COUNT(*) FROM p_log WHERE (Timestamp BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE()) AND IdUsu = ?");
    $result8->bindValue(1, $PatientId, PDO::PARAM_INT);
    $result8->execute();
    $count8 = $result8->fetchColumn();
    
    $new_summary = "";
    if ($count8 >0) $new_summary = "patient_row_button_active";
    
    
    //Appointments with doctor
    $result9 = $con->prepare("SELECT COUNT(*) FROM appointments WHERE med_id = ? AND pat_id = ? AND (date_created BETWEEN SYSDATE()-INTERVAL 30 DAY AND SYSDATE())");
    $result9->bindValue(1, $IdDoc, PDO::PARAM_INT);
    $result9->bindValue(2, $PatientId, PDO::PARAM_INT);
    $result9->execute();

    $count9 = $result9->fetchColumn();
    $new_appointments = "";
    //echo '  ********************** COUNT = '.$count2;
    if ($count9 >0) $new_appointments = "patient_row_button_active";
    

    $current_encoding = mb_detect_encoding($row['Name'], 'auto');
    $show_text = iconv($current_encoding, 'ISO-8859-1', $row['Name']);

    $current_encoding = mb_detect_encoding($row['Surname'], 'auto');
    $show_text2 = iconv($current_encoding, 'ISO-8859-1', $row['Surname']); 

    $full_name = $show_text.' '.$show_text2;
    //die($full_name);
    
    // check if this patient has been referred to another doctor
    $result11 = $con->prepare("SELECT COUNT(*) FROM doctorslinkdoctors WHERE IdMED = ? AND IDPAC = ?");
    $result11->bindValue(1, $IdDoc, PDO::PARAM_INT);
    $result11->bindValue(2, $PatientId, PDO::PARAM_INT);
    $result11->execute();
    $referred = 'hidden';
    if($result11->fetchColumn() > 0)
    {
        $referred = 'visible';
    }
    
    
    echo '<div class="doctor_row_wrapper">
            <div class="doctor_row" id="onclick'.$PatientId.'" class="doctor_row" style="margin-bottom: 10px; position:relative;">
                <span onclick="grabTimeline('.$PatientId.');" class="doctor_row_resize icon-resize-full"></span>
									
                <div style="width:100%; height:22px;"></div>
                
                <div style="width: 17px; margin-left: 3px; height: 70px; float: left; margin-top: -22px; color: #54BC00;">
                    <div style="width: 100%; height: 20px; visibility: '.$referred.'">
                        <i class="icon-share-alt"></i>
                    </div>
                    <div style="width: 100%; height: 20px; margin-top: 33px; visibility: '.$new_consultations.'">
                        <i class="icon-facetime-video"></i>
                    </div>
                </div>
                <div id="'.$PatientId.'" class="CFILAPAT" style="height:20px; width: 20%; margin-left:10px; float:left;">
                    <a class="patient_name truncate" style="display:block; width: 100%;">'.SCaps($show_text).' '.SCaps($show_text2).'</a>
                    <div style="width:100%; margin-top:-8px;"></div>
                    <span class="truncate" style="font-size:10px; color:#BEBEBE; font-weight:normal;">'.$row['email'].'</span>
                </div>
                

                <!--
                <div style="width: 30%; height: 40px; float: left; margin-left: 15px; border: 1px solid #D8D8D8; margin-top: -10px; border-radius: 5px; overflow: hidden;">
                    <div class="probe_display" style="width: 8%; height: 20px; padding-left: 2%; padding-top: 10px; padding-bottom: 10px; background-color: #FAFAFA; float: left; overflow: hidden; color: #54BC00; font-size: 18px;">
                        <i class="icon-arrow-up"></i>
                    </div>
                    <div class="probe_display" style="width: 8%; height: 20px; padding-left: 2%; padding-top: 10px; padding-bottom: 10px; background-color: #E8E8E8; float: left; overflow: hidden; color: #D84840; font-size: 18px;">
                        <i class="icon-arrow-down"></i>
                    </div>
                    <div class="probe_display" style="width: 8%; height: 20px; padding-left: 2%; padding-top: 10px; padding-bottom: 10px; background-color: #FAFAFA; float: left; overflow: hidden; color: #22AEFF; font-size: 18px;">
                        <i class="icon-minus"></i>
                    </div>
                    <div class="probe_display" style="width: 58%; height: 20px; padding-left: 2%; padding-top: 10px; padding-bottom: 10px; background-color: #E8E8E8; float: left; overflow: hidden; color: #54BC00; font-size: 18px;">
                        <i class="icon-arrow-up" style="float: left"></i>
                        
                        <div style="float: left; margin-left: 5px; margin-top: -5px; width: 86%;">
                            <div id="probe_info_button_'.$PatientId.'_2" style="float: right; font-size: 16px; margin-right: 14px; color: #777; margin-top: 5px;"><i class="icon-info-sign"></i></div>
                            Good
                            <div style="color: #888; font-size: 10px; margin-top: -5px;">updated 2 days ago</div>
                        </div>
                        <script>
                                Tipped.create("#probe_info_button_'.$PatientId.'_2", "test");
                        </script>
                        
                    </div>
                    <div class="probe_display" style="width: 8%; height: 20px; padding-left: 2%; padding-top: 10px; padding-bottom: 10px; background-color: #FAFAFA; float: left; overflow: hidden; color: #D84840; font-size: 18px;">
                        <i class="icon-arrow-down"></i>
                    </div>
                </div>
                -->
                '.$probe_notifications.'
                



		      <div id="buttons_doctor" style="float:left; width:42%; margin-left: 5px;">



                    <div onclick="window.location.href = \'patientdetailMED-new.php?IdUsu='.$PatientId.'\'" class="patient_row_button '.$new_reports.'">
                        <i id="'.$PatientId.'"  class="icon-folder-open"></i>
                    </div>
                    <div onclick="openSummary('.$PatientId.');" class="patient_row_button '.$new_summary.'">
                        <i id="'.$PatientId.'"  class="icon-edit"></i>
                    </div>
                    <div onclick="createMessage('.$PatientId.', \''.$full_name.'\');"  class="patient_row_button '.$new_messages.'">
                        <i id="'.$PatientId.'"  class="icon-envelope-alt"></i>
                    </div>
                    '.$probe_icon.'
                    <div id="patientAppointments_'.$PatientId.'"  class="patient_row_button '.$new_appointments.'">
                        <i id="'.$PatientId.'"  class="icon-calendar "></i>
                    </div>
                    <div onclick="createDetails();"  class="patient_row_button">
                        <i id="'.$PatientId.'"  class="icon-info-sign"></i>
                    </div>
                </div>
	  
                <div style="width:100%; height:1px; float:left;"></div>
                <!--
                <div id="graph_probes_container" style="margin-top:17px; float:left; padding:10px; width: 350px;">';
                if($row6['probeID'] != ''){
                    echo '<div id="probegraph_question_'.$row6['probeID'].'" style="width: 350px; height: 35px; font-size: 12px; color: #777; overflow: hidden; text-overflow: ellipsis; line-height: 115%;">
                    </div>
                    <div id="probegraph_container_'.$row6['probeID'].'">
                        <canvas height="120" width="350" id="probegraph_'.$row6['probeID'].'"></canvas>
                    </div>
                    <div style="width: 150px; height: 20px; margin: auto;">
                        <button id="probebutton_'.$row6['probeID'].'_1" class="probe_chart_button" '.$protocol_disabled_tags[0].'>
                            <div style="margin-top: -3px;">1</div>
                        </button>
                        <button id="probebutton_'.$row6['probeID'].'_2" class="probe_chart_button" '.$protocol_disabled_tags[1].'>
                            <div style="margin-top: -3px;">2</div>
                        </button>
                        <button id="probebutton_'.$row6['probeID'].'_3" class="probe_chart_button" '.$protocol_disabled_tags[2].'>
                            <div style="margin-top: -3px;">3</div>
                        </button>
                        <button id="probebutton_'.$row6['probeID'].'_4" class="probe_chart_button" '.$protocol_disabled_tags[3].'>
                            <div style="margin-top: -3px;">4</div>
                        </button>
                        <button id="probebutton_'.$row6['probeID'].'_5" class="probe_chart_button" '.$protocol_disabled_tags[4].'>
                            <div style="margin-top: -3px;">5</div>
                        </button>
                    </div>';
                }else{
                    echo "This member has not responded with the status of their health.";
                }
            echo '</div>
            
           
		        <div id="timeline_label" style="font-size:12px; color:#54bc00; font-weight:normal; float:left; margin-top:20px; margin-left:30px;"><center>'.$full_name.'\'s Timeline</center></div>
            -->
		   
                <div id="timeline'.$PatientId.'" style="width: 95%; height:150px; float:left; position:relative; margin-left:20px; margin-bottom: 15px;"></div>
                <div style="width: 850px; margin: auto;">
                    <div id="probe_graph_'.$row6['probeID'].'_1"></div>
                    <div id="probe_question_'.$row6['probeID'].'_1" style="width: 98%; height: 20px; padding: 1%; border-radius: 5px; background-color: #FAFAFA; color: #888; margin-top: 5px; border: 1px solid #EEE; display: none;"></div>
                    <div id="probe_graph_'.$row6['probeID'].'_2" style="display: none;"></div>
                    <div id="probe_question_'.$row6['probeID'].'_2" style="width: 98%; height: 20px; padding: 1%; border-radius: 5px; background-color: #FAFAFA; color: #888; margin-top: 5px; border: 1px solid #EEE; display: none;"></div>
                    <div id="probe_graph_'.$row6['probeID'].'_3" style="display: none;"></div>
                    <div id="probe_question_'.$row6['probeID'].'_3" style="width: 98%; height: 20px; padding: 1%; border-radius: 5px; background-color: #FAFAFA; color: #888; margin-top: 5px; border: 1px solid #EEE; display: none;"></div>
                    <div id="probe_graph_'.$row6['probeID'].'_4" style="display: none;"></div>
                    <div id="probe_question_'.$row6['probeID'].'_4" style="width: 98%; height: 20px; padding: 1%; border-radius: 5px; background-color: #FAFAFA; color: #888; margin-top: 5px; border: 1px solid #EEE; display: none;"></div>
                    <div id="probe_graph_'.$row6['probeID'].'_5" style="display: none;"></div>
                    <div id="probe_question_'.$row6['probeID'].'_5" style="width: 98%; height: 20px; padding: 1%; border-radius: 5px; background-color: #FAFAFA; color: #888; margin-top: 5px; border: 1px solid #EEE; display: none;"></div>
                    <div style="width: 150px; height: 20px; margin: auto; margin-top: 15px;">
                        <button id="probebutton_'.$row6['probeID'].'_1" class="probe_chart_button" '.$protocol_disabled_tags[0].'>
                            <div style="margin-top: -3px;">1</div>
                        </button>
                        <button id="probebutton_'.$row6['probeID'].'_2" class="probe_chart_button" '.$protocol_disabled_tags[1].'>
                            <div style="margin-top: -3px;">2</div>
                        </button>
                        <button id="probebutton_'.$row6['probeID'].'_3" class="probe_chart_button" '.$protocol_disabled_tags[2].'>
                            <div style="margin-top: -3px;">3</div>
                        </button>
                        <button id="probebutton_'.$row6['probeID'].'_4" class="probe_chart_button" '.$protocol_disabled_tags[3].'>
                            <div style="margin-top: -3px;">4</div>
                        </button>
                        <button id="probebutton_'.$row6['probeID'].'_5" class="probe_chart_button" '.$protocol_disabled_tags[4].'>
                            <div style="margin-top: -3px;">5</div>
                        </button>
                    </div>
                </div>
            
            </div> 
        </div>';  /* Close doctor_row *//* Close doctor_row_wrapper */



    echo "<script type='text/javascript'>";
    
    echo "var data = {
    labels: [";
    foreach($probe_labels as $label){
        echo "'".$label."', ";
    }
    echo"],
    datasets: [
        {
            label: 'Probe Responses',
            fillColor: 'rgba(220,220,220,0.2)',
            strokeColor: 'rgba(220,220,220,1)',
            pointColor: 'rgba(220,220,220,1)',
            pointStrokeColor: '#22aeff',
            pointHighlightFill: '#54bc00',
            pointHighlightStroke: 'rgba(220,220,220,1)',
            data: [";
    foreach($probe_array as $probes){
        echo "'".$probes."', ";
    }
    
    $initial_chart = "]
        }
    ]
};
var options = {
        bezierCurve: true,
        scaleFontSize: 10,
        scaleFontColor: '#cacaca',
        /*scaleOverride: true,
        scaleSteps: 4,
        scaleStepWidth: 1,
        scaleStartValue: 1,*/
datasetFill: true
    };
</script>";
    
    echo $initial_chart;
/**/    
}

//UPDATING THE LAST PAGE HIDDEN INPUT ON THE PATIENTNETWORK.PHP FOR THIS QUERY COUNT
echo '<script type="text/javascript">
        $("input#last_page").val('.ceil(intval($last_page) / 10).');
      </script>';
/*
$probe_button_event = "
    <script>
        $('.probe_display').on('mouseenter', function()
        {
            var probe_notifications_width = (((5 - $(this).parent().children().length) * 10) + 60) - 2;
            var element = $(this).index();
            $(this).parent().children().each(function()
            {
                $(this).css('width', '8%');
                $(this).children('div').eq(1).css('display', 'none');
            });
            $(this).css('width', probe_notifications_width+'%');
            $(this).children('div').eq(1).css('display', 'block');
        });
        function load_probe_graph(element, id, question)
        {
            $.post('get_probe_graph_data.php', {probeID: id, question: question}, function(data, status)
            {
                var d = JSON.parse(data);
                console.log(d);
                if(d.data.length > 0)
                {
                    element.html('');
                    console.log(d);
                    element.H2M_ProbeGraph({data: d.data, height: 200, width: 850, units: d.units, question_unit: d.question_unit, title: d.question_title});
                    $('#probe_question_'+id+'_'+question).text(d.question);
                    if(question == 1)
                    {
                        $('#probe_question_'+id+'_1').css('display', 'block');
                    }
                }
                else
                {
                    element.html('');
                    console.log(d);
                    element.H2M_ProbeGraph({data: [], height: 200, width: 850, units: []});
                    $('#probe_question_'+id+'_'+question).text(d.question);
                }

            });
        }
        
        var sample_data = [{value: 3, date: '2015-01-05 15:33:00'}, {value: 1, date: '2015-01-06 15:30:00'}, {value: 5, date: '2015-01-10 15:31:00'}, {value: 3, date: '2015-01-07 15:31:00'}, {value: 5, date: '2015-01-08 15:32:00'}, {value: 4, date: '2015-01-12 15:37:00'}, {value: 4, date: '2015-01-09 15:31:00'}, {value: 5, date: '2015-01-11 15:30:00'}, {value: 2, date: '2015-01-14 15:31:00'}, {value: 2, date: '2015-01-13 15:36:00'}, {value: 1, date: '2015-01-14 15:32:00'}];
        var data_values = ['Very Bad', 'Bad', 'Normal', 'Good', 'Very Good'];
        $(\"div[id^='probe_graph_']\").H2M_ProbeGraph({data: sample_data, height: 200, width: 850, units: data_values});
            
        $('.probe_chart_button').unbind('click');
        $('.probe_chart_button').on('click', function()
        {
            console.log('Probe button pressed');
            var info = $(this).attr('id').split('_');
            var probe = info[1];
            var question = info[2];
            $(this).siblings().each(function()
            {
                if($(this).hasClass('probe_chart_button_selected'))
                    $(this).removeAttr('disabled');
                $(this).removeClass('probe_chart_button_selected').addClass('probe_chart_button');
            });
            $(this).removeClass('probe_chart_button').addClass('probe_chart_button_selected');
            $(this).attr('disabled', 'disabled');

            var self = $(this).parent().parent().children().eq(0);
            var graph_name = 'probe_graph_'+probe;
            var question_name = 'probe_question_'+probe;
            $('div[id^=\"'+graph_name+'\"]').css('display', 'none');
            $('div[id^=\"'+question_name+'\"]').css('display', 'none');
            $('#probe_graph_'+probe+'_'+question).css('display', 'block');
            $('#probe_question_'+probe+'_'+question).css('display', 'block');
        });

        $('div[id^=\"probe_graph_\"]').each(function()
        {
            var info = $(this).attr('id').split('_');
            var probe = info[2];
            var question = info[3];
            var self = $(this);
            if(question == 1)
            {
                $('#probebutton_'+probe+'_1').removeClass('probe_chart_button').addClass('probe_chart_button_selected');
                $('#probebutton_'+probe+'_1').attr('disabled', 'disabled');
            }
            //load_probe_graph(self, probe, question);
            $.getScript('js/h2m_probegraph.js', function()
            {

                $.post('get_probe_graph_data.php', {probeID: probe, question: question}, function(data, status)
                {
                    var d = JSON.parse(data);
                    if(d.data.length > 0)
                    {
                        self.html('');
                        self.H2M_ProbeGraph({data: d.data, height: 200, width: 850, units: d.units, question_unit: d.question_unit, title: d.question_title});
                        $('#probe_question_'+probe+'_'+question).text(d.question);
                        if(question == 1)
                        {
                            $('#probe_question_'+probe+'_1').css('display', 'block');
                        }
                    }
                    else
                    {
                        self.html('');
                        self.H2M_ProbeGraph({data: [], height: 200, width: 850, units: []});
                        $('#probe_question_'+probe+'_'+question).text(d.question);
                    }

                });
            });
            

        });
    </script>";

echo $probe_button_event;*/

//echo '</div></div>';

//echo '<input type="hidden" id="UTotal" Value="'.$UTotal.'"><input type="hidden" id="UConn" value="'.$UConn.'"><input type="hidden" id="TotMsg" value="'.$TotMsg.'"><input type="hidden" id="TotUpDr" value="'.$TotUpDr.'"><input type="hidden" id="TotUpUs" value="'.$TotUpUs.'">';


function SCaps ($cadena)
{
    return strtoupper(substr($cadena,0,1)).substr($cadena,1);
}    
unset($_GET['filter']);
?>
