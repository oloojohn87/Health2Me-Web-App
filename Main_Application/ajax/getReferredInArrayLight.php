<?php
ini_set("display_errors", 0);
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

$queUsu = $_GET['Doctor'];
$NReports=empty($_GET['NReports']) ? 30: $_GET['NReports'];
$daysExplore=empty($_GET['days']) ? 30: $_GET['days'];
$TypeEntry=empty($_GET['TypeEntry']) ? 0: $_GET['TypeEntry'];
$withGroup = 0;
if (isset($_GET['Group']))
{
    $withGroup = $_GET['Group'];
}

$colorQuery = $con->query("SELECT type FROM doctors WHERE id = ".$queUsu);
$colorResult = $colorQuery->fetch(PDO::FETCH_ASSOC);
$colorStandard = '#22AEFF';
$trColor = '#CACACA';
$trFrame = '#CACACA';
if(strpos($colorResult['type'],'CATA') !== false) {
    $colorStandard = 'Red';
    $trColor = 'DarkRed';
    $trFrame = 'Red';
}

if ($TypeEntry == 'in')
{
    if ($withGroup == 0)
    {
        $result = $con->prepare("
            SELECT RES.*, RS.stage, RS.datevisit, D.Name AS DocName, D.Surname AS DocSurname, D.Role AS DocRole, U.Name AS PatName, U.Surname AS PatSurname, MI.count AS NewMesCount, MI.fecha AS NewMesFecha, MI2.count AS MesCount  FROM 
                    (
                        SELECT * FROM doctorslinkdoctors WHERE IdMED2 = ? ORDER BY Fecha DESC LIMIT 10 
                    ) RES

                        LEFT JOIN

                    referral_stage RS ON RS.referral_id = RES.id

                        LEFT JOIN

                    doctors D ON D.id = RES.IdMED

                        LEFT JOIN

                    usuarios U ON U.identif = RES.IdPac

                        LEFT JOIN

                    (SELECT COUNT(*) AS count, status, MAX(fecha) AS fecha, patient_id FROM message_infrasture WHERE receiver_id = ? AND status = 'new' AND DATEDIFF(NOW(), fecha) <= ? GROUP BY status,patient_id) AS MI ON RES.IdPac = MI.patient_id

                        LEFT JOIN

                    (SELECT COUNT(*) AS count, status, patient_id FROM message_infrasture WHERE receiver_id = ? AND DATEDIFF(NOW(), fecha) <= ? GROUP BY patient_id) AS MI2 ON RES.IdPac = MI2.patient_id");
        $result->bindValue(1, $queUsu, PDO::PARAM_INT);
        $result->bindValue(2, $queUsu, PDO::PARAM_INT);
        $result->bindValue(3, $daysExplore, PDO::PARAM_INT);
        $result->bindValue(4, $queUsu, PDO::PARAM_INT);
        $result->bindValue(5, $daysExplore, PDO::PARAM_INT);
        $result->execute(); 
    }
    else
    {
        /*
         *  Query Documentation
         *
         *  1st Line:       Gets all referrals to this doctor from doctorslinkdoctors
         *  2nd - 4th Line: Gets all referrals that were referred to any doctor that this doctor is in a group with and appends them to the result set (Using UNION)
         *  3rd Line:       Gets all doctors that this doctor is in a group with by joining the doctorsgroups table to itself with conditions on IdGroup and IdDoctor
         *
         */

        $query_str = '
                        SELECT RES.*, RS.stage, RS.datevisit, D.Name AS DocName, D.Surname AS DocSurname, D.Role AS DocRole, U.Name AS PatName, U.Surname AS PatSurname, MI.count AS NewMesCount, MI.fecha AS NewMesFecha, MI2.count AS MesCount  FROM 
                    (
                        (SELECT DLD.id,DLD.IdMED,DLD.IdMED2,DLD.IdPac,DLD.Fecha,DLD.FechaConfirm,DLD.Type FROM doctorslinkdoctors DLD WHERE DLD.IdMED2 = ?)
                            UNION (SELECT DLD.id,DLD.IdMED,DLD.IdMED2,DLD.IdPac,DLD.Fecha,DLD.FechaConfirm,DLD.Type FROM doctorslinkdoctors DLD INNER JOIN 
                                (SELECT DISTINCT DG_1.IdDoctor FROM doctorsgroups DG_1 INNER JOIN doctorsgroups DG_2 ON DG_2.IdDoctor = ? AND DG_1.IdGroup = DG_2.IdGroup) G
                            ON DLD.IdMED2 = G.IdDoctor)
                            ORDER BY Fecha DESC LIMIT 10
                    ) RES

                        LEFT JOIN

                    referral_stage RS ON RS.referral_id = RES.id

                        LEFT JOIN

                    doctors D ON D.id = RES.IdMED

                        LEFT JOIN

                    usuarios U ON U.identif = RES.IdPac

                        LEFT JOIN

                    (SELECT COUNT(*) AS count, status, MAX(fecha) AS fecha, patient_id FROM message_infrasture WHERE receiver_id = ? AND status = "new" AND DATEDIFF(NOW(), fecha) <= ? GROUP BY status,patient_id) AS MI ON RES.IdPac = MI.patient_id

                        LEFT JOIN

                    (SELECT COUNT(*) AS count, status, patient_id FROM message_infrasture WHERE receiver_id = ? AND DATEDIFF(NOW(), fecha) <= ? GROUP BY patient_id) AS MI2 ON RES.IdPac = MI2.patient_id
                     ';


        $result = $con->prepare($query_str); 
        $result->bindValue(1, $queUsu, PDO::PARAM_INT);
        $result->bindValue(2, $queUsu, PDO::PARAM_INT);
        $result->bindValue(3, $queUsu, PDO::PARAM_INT);
        $result->bindValue(4, $daysExplore, PDO::PARAM_INT);
        $result->bindValue(5, $queUsu, PDO::PARAM_INT);
        $result->bindValue(6, $daysExplore, PDO::PARAM_INT);
        $result->execute(); 
    }
}else
{
    if ($withGroup == 0)
    {
        $result = $con->prepare("
                SELECT RES.*, RS.stage, RS.datevisit, D.Name AS DocName, D.Surname AS DocSurname, D.Role AS DocRole, U.Name AS PatName, U.Surname AS PatSurname, MI.count AS NewMesCount, MI.fecha AS NewMesFecha, MI2.count AS MesCount  FROM 
                    (
                        SELECT * FROM doctorslinkdoctors WHERE IdMED = ? ORDER BY Fecha DESC LIMIT 10
                    ) RES

                        LEFT JOIN

                    referral_stage RS ON RS.referral_id = RES.id

                        LEFT JOIN

                    doctors D ON D.id = RES.IdMED2

                        LEFT JOIN

                    usuarios U ON U.identif = RES.IdPac

                        LEFT JOIN

                    (SELECT COUNT(*) AS count, status, MAX(fecha) AS fecha, patient_id FROM message_infrasture WHERE receiver_id = ? AND status = 'new' AND DATEDIFF(NOW(), fecha) <= ? GROUP BY status,patient_id) AS MI ON RES.IdPac = MI.patient_id

                        LEFT JOIN

                    (SELECT COUNT(*) AS count, status, patient_id FROM message_infrasture WHERE receiver_id = ? AND DATEDIFF(NOW(), fecha) <= ? GROUP BY patient_id) AS MI2 ON RES.IdPac = MI2.patient_id
");
        $result->bindValue(1, $queUsu, PDO::PARAM_INT);
        $result->bindValue(2, $queUsu, PDO::PARAM_INT);
        $result->bindValue(3, $daysExplore, PDO::PARAM_INT);
        $result->bindValue(4, $queUsu, PDO::PARAM_INT);
        $result->bindValue(5, $daysExplore, PDO::PARAM_INT);
        $result->execute(); 
    }
    else
    {
        /*
         *  Query Documentation
         *
         *  1st Line:       Gets all referrals that this doctor referred out from doctorslinkdoctors
         *  2nd - 4th Line: Gets all referrals that any doctor that this doctor is in a group with referred out and appends them to the result set (Using UNION)
         *  3rd Line:       Gets all doctors that this doctor is in a group with by joining the doctorsgroups table to itself with conditions on IdGroup and IdDoctor
         *
         */
        $query_str = '
                            SELECT RES.*, RS.stage, RS.datevisit, D.Name AS DocName, D.Surname AS DocSurname, D.Role AS DocRole, U.Name AS PatName, U.Surname AS PatSurname, MI.count AS NewMesCount, MI.fecha AS NewMesFecha, MI2.count AS MesCount  FROM 
                    (
                        (SELECT DLD.id,DLD.IdMED,DLD.IdMED2,DLD.IdPac,DLD.Fecha,DLD.FechaConfirm,DLD.Type FROM doctorslinkdoctors DLD WHERE DLD.IdMED = ?)
                            UNION (SELECT DLD.id,DLD.IdMED,DLD.IdMED2,DLD.IdPac,DLD.Fecha,DLD.FechaConfirm,DLD.Type FROM doctorslinkdoctors DLD INNER JOIN 
                                (SELECT DISTINCT DG_1.IdDoctor FROM doctorsgroups DG_1 INNER JOIN doctorsgroups DG_2 ON DG_2.IdDoctor = ? AND DG_1.IdGroup = DG_2.IdGroup) G
                            ON DLD.IdMED = G.IdDoctor)
                            ORDER BY Fecha DESC LIMIT 10
                    ) RES

                        LEFT JOIN

                    referral_stage RS ON RS.referral_id = RES.id

                        LEFT JOIN

                    doctors D ON D.id = RES.IdMED2

                        LEFT JOIN

                    usuarios U ON U.identif = RES.IdPac

                        LEFT JOIN

                    (SELECT COUNT(*) AS count, status, MAX(fecha) AS fecha, patient_id FROM message_infrasture WHERE receiver_id = ? AND status = "new" AND DATEDIFF(NOW(), fecha) <= ? GROUP BY status,patient_id) AS MI ON RES.IdPac = MI.patient_id

                        LEFT JOIN

                    (SELECT COUNT(*) AS count, status, patient_id FROM message_infrasture WHERE receiver_id = ? AND DATEDIFF(NOW(), fecha) <= ? GROUP BY patient_id) AS MI2 ON RES.IdPac = MI2.patient_id

                     ';


        $result = $con->prepare($query_str); 
        $result->bindValue(1, $queUsu, PDO::PARAM_INT);
        $result->bindValue(2, $queUsu, PDO::PARAM_INT);
        $result->bindValue(3, $queUsu, PDO::PARAM_INT);
        $result->bindValue(4, $daysExplore, PDO::PARAM_INT);
        $result->bindValue(5, $queUsu, PDO::PARAM_INT);
        $result->bindValue(6, $daysExplore, PDO::PARAM_INT);
        $result->execute(); 
    }
}
$n=0;
$count = $result->rowCount();
$cadena = '';
$html = '<div style="width:100%;">
            <table style="background-color:white; width:100%;">
                <tr style="border-bottom:1px solid '.$trFrame.'; height:25px; background-color:'.$trColor.'; color:white;">
                    <td style="width:20px; text-align:center;">
                        <p style="margin-bottom:0px; text-align:center;"><span style="font-size:14px;" lang="en">Comm</span></p>
                    </td>
                    <td style="width:100px;">
                        <p style="margin-bottom:0px; text-align:center;"><span style="font-size:14px;" lang="en">Patient/Doctor</span></p>
                    </td>
                    <td style="width:60px; text-align:center;" lang="en">Stage</td>
                    <td style="width:5px;"></td>
                    <td style="width:40px; text-align:center;" lang="en">Time</td>';

// Report all errors except E_NOTICE
error_reporting(E_ALL ^ E_NOTICE);
$IterationRef = array();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

    $referral_id = $row['id'];
    $referral_date = $row['Fecha'];

    //Adding changes for the new referrral type
    $referral_type=$row['Type'];

    $referral_dateCONF = $row['FechaConfirm'];
    $referral_stage=$row['stage'];
    switch ($referral_stage){
        case 1: $LetStage='A';
        break;
        case 2: $LetStage='B';
        break;
        case 3: $LetStage='C';
        break;
        case 4: $LetStage='D';
        break;
    }

    $IdPac = $row['IdPac'];

    if ($TypeEntry == 'in')
    {
        $IdDoctor = $row['IdMED'];
        $DoctorSelf = $row['IdMED2'];
        $IterationRef[$IdPac]=0;

    }else
    {
        $IdDoctor = $row['IdMED2'];
        $DoctorSelf = $row['IdMED'];
        $IterationRef[$IdPac]++;
    }

    //IT DISTINGUISHES THE TD BG_COLOR BY WHETHER IT'S IN GROUP
    if($DoctorSelf == $queUsu) $tr_bgcolor = "white;";
    else $tr_bgcolor = "#EBF5FF;";

    $NameD2 = $row['DocName'];
    $SurnameD2 = $row['DocSurname'];
    $RoleD2 = $row['DocRole'];
    $TreatD2 = '';
    if ($RoleD2 == '1') $TreatD2 = 'Dr.';


    $NameP = $row['PatName'];
    $SurnameP = $row['PatSurname'];


    // Check if any of this patients has new MESSAGES from DOCTORS
    /*$resultMES = $con->prepare("SELECT sender_id,receiver_id,fecha,attachments,status FROM message_infrasture WHERE patient_id = ? AND receiver_id = ? AND DATEDIFF(NOW(), fecha) <= ? ORDER BY fecha DESC ");
    $resultMES->bindValue(1, $IdPac, PDO::PARAM_INT);
    $resultMES->bindValue(2, $queUsu, PDO::PARAM_INT);
    $resultMES->bindValue(3, $daysExplore, PDO::PARAM_STR);
    $resultMES->execute();

    $MessagesReceived=0;
    $MessagesReceivedNEW=0;
    $NewestdateMessage=0;
    while ($rowMES = $resultMES->fetch(PDO::FETCH_ASSOC)) {
        if ($rowMES['status'] == 'new') { $MessagesReceivedNEW++; $NewestdateMessage = $rowMES['fecha'];}
        $MessagesReceived++;
    }*/

    $MessagesReceived=0;
    $MessagesReceivedNEW=0;
    $NewestdateMessage=0;
    if($row['NewMesCount'] != NULL)
        $MessagesReceivedNEW = $row['NewMesCount'];
    if($row['NewMesFecha'] != NULL)
        $NewestdateMessage = $row['NewMesFecha'];
    if($row['MesCount'] != NULL)
        $MessagesReceived = $row['MesCount'];

    $CircleColor = '';
    if ($TypeEntry == 'in')
    {
        $CircleColor='orange';
    }
    else 
    {
        $CircleColor='#54bc00';
    }
    if ($n < $count-1)
    {
        $tr_style = "height:30px; border-bottom:1px solid ".$trFrame.";";
    }
    else 
    {    
        $tr_style .= "height:30px; border:none;";
    }
    $MsgColor = '';
    $Iteration = '';
    if ($MessagesReceivedNEW > 0 ) 
    {
        $MsgColor = $colorStandard;
    }
    else
    {
        $MsgColor = '#e9e9e9';
    }
    $html .= '<tr style="'.$tr_style.' background:'.$tr_bgcolor.'">';
    $html .= '<td style="width:20px;"><div style="width:100%; text-align:center; margin-top:-5px; font-size:20px; color:'.$MsgColor.';"><icon class="icon-envelope" title="NEW MESSAGES"></icon></div></td>';
    if ($IterationRef[$IdPac] > 1) 
    {
        $Iteration = '('.$IterationRef[$IdPac].')'; 
    }
    $html .= '<td style="padding: 6px;"><a href="patientdetailMED-new.php?IdUsu='.$IdPac.'" style="text-decoration:none;"><div class="truncate" style="line-height:1; color: #54bc00; font-size:14px;">'.BeauStr($SurnameP).', '.strtoupper($NameP[0]).' '.$Iteration.'</div><div class="truncate" style="color: '.$colorStandard.'; font-size:12px; margin-top:0px;"> '.strtoupper($SurnameD2).'</div></a></td>';
    $St1='OFF';	
    $St2='OFF';	
    $St3='OFF';	
    $St4='OFF';	
    $CircleC1 = '';
    $CircleC2 = '';
    $CircleC3 = '';
    $CircleC4 = '';
    if($referral_type > 0)
    {
        switch ($referral_stage)
        {
            case '1': 
            $St1='ON';
            $CircleC1 = $colorStandard;
            break;
            case '2': 
            $St1='ON';	
            $St2='ON';
            $CircleC1 = $colorStandard;
            $CircleC2 = $colorStandard;
            break;
            case '3': 
            $St1='ON';	
            $St2='ON';	
            $St3='ON';	
            $CircleC1 = $colorStandard;
            $CircleC2 = $colorStandard;
            $CircleC3 = $colorStandard;
            break;

            default: 	
            break;
        }
        $title = "A: PATIENT has been ACKNOWLEDGED &#013;B: INFORMATION has been REVIEWED &#013;C: Comments has been Added";
        $html .= '<td style="width:66px;" title="'.$title.'">';
        $html .= '<div style="width: 12px;height: 12px;font-size: 11px; margin-left:2px; background-color:'.$CircleC1.';" class="LetterRectangle'.$St1.'">A</div>';
        $html .= '<div style="width: 12px;height: 12px;font-size: 11px; margin-left:2px; background-color:'.$CircleC2.';" class="LetterRectangle'.$St2.'">B</div>';
        $html .= '<div style="width: 12px;height: 12px;font-size: 11px; margin-left:2px; background-color:'.$CircleC3.';" class="LetterRectangle'.$St3.'">C</div></td>';

    }
    else
    {
        switch ($referral_stage)
        {
            case '1': 
            $St1='ON';
            $CircleC1 = $CircleColor;
            break;
            case '2': 
            $St1='ON';	
            $St2='ON';
            $CircleC1 = $CircleColor;
            $CircleC2 = $CircleColor;
            break;
            case '3': 
            $St1='ON';	
            $St2='ON';	
            $St3='ON';	
            $CircleC1 = $CircleColor;
            $CircleC2 = $CircleColor;
            $CircleC3 = $CircleColor;
            break;
            case '4': 
            $St1='ON';	
            $St2='ON';	
            $St3='ON';	
            $St4='ON';	
            $CircleC1 = $CircleColor;
            $CircleC2 = $CircleColor;
            $CircleC3 = $CircleColor;
            $CircleC4 = $CircleColor;
            break;
            default: 	
            break;
        }
        $title = "A: PATIENT has been ACKNOWLEDGED &#013;B: INFORMATION has been REVIEWED &#013;C: Comments has been Added &#013;D: DOCTOR and PATIENT have been MET";
        $html .= '<td style="width:66px;" title="'.$title.'">';
        $html .= '<div style="width: 12px;height: 12px;font-size: 11px; margin-left:2px; background-color:'.$CircleC1.';" class="LetterCircle'.$St1.'">A</div>';
        $html .= '<div style="width: 12px;height: 12px;font-size: 11px; margin-left:2px; background-color:'.$CircleC2.';" class="LetterCircle'.$St2.'">B</div>';
        $html .= '<div style="width: 12px;height: 12px;font-size: 11px; margin-left:2px; background-color:'.$CircleC3.';" class="LetterCircle'.$St3.'">C</div>';
        $html .= '<div style="width: 12px;height: 12px;font-size: 11px; margin-left:2px; background-color:'.$CircleC4.';" class="LetterCircle'.$St4.'">D</div></td>';
    }

    $html .= '<td style="width:5px;"></td>';
    //Column 4 TIME
    $now = time(); // or your date as well
    $your_date = strtotime($referral_date);
    $datediff = $now - $your_date;
    $temp_diff = $datediff/(3600);
    $validDif = '';
    $WhatLabel = '';
    if ($temp_diff < 24)
    {
        $validDif = floor($temp_diff);
        $WhatLabel = 'hours';
    }
    else
    {
        $validDif = floor($temp_diff/24);
        $WhatLabel = 'days';
    }

    $WhatColor = '';                   
    switch(true)
    {
        case ($validDif >= 0 && $validDif <= 2 || $WhatLabel == 'hours'): 	
        $WhatColor = 'black';
        break;
        case ($validDif > 2 && $validDif <= 7 && $WhatLabel == 'days'): 	
        $WhatColor = 'grey';
        break;
        case ($validDif > 7 && $validDif < 30 && $WhatLabel == 'days'): 	
        $WhatColor = '#cacaca';
        break;
        case ($validDif >= 30 && $WhatLabel == 'days'): 					
        $WhatColor = '#e9e9e9';
        break;
        default:								
        $WhatColor = 'blue';
        break;
    }
    $html .= '<td style="width:40px; text-align:center; color: white; background-color: '.$WhatColor.'; "><div style="width:100%; font-size:18px;">'.$validDif.'</div><div style="width:100%; font-size:10px; margin-top:-5px;" lang="en">'.$WhatLabel.'</div></td>';

    $n++;


}
$html .= '</table></div>';

//$encode = json_encode($cadena);
//echo '{"items":['.($cadena).']}';
echo $html;

function SanDate($entrydate)
{
    $newDate = date("m/d/Y H:i:s", strtotime($entrydate));
    return $newDate;
}
function BeauStr ($EString)
{
    $FirstChar = strtoupper(substr($EString, 0, 1));
    $ELen = strlen($EString);
    $RemainChar = strtolower(substr($EString, 1, $ELen));
    $MExit = $FirstChar.''.$RemainChar;
    return $MExit;
}

?>
<script type="text/javascript">
if($.cookie('lang') != 'en')
{
    $.get('../../../../jquery-lang-js-master/js/langpack/'+initial_language+'.json', function(data, status)
    {
        var json = data;
        $('*[lang^=\"en\"]').each(function()
        {
            $(this).attr('original_eng_text', $(this).text());
            if(json.token.hasOwnProperty($(this).text()))
            {
                $(this).text(json.token[$(this).text()]);
            }
            else if(json.token.hasOwnProperty($(this).html()))
            {
                $(this).html(json.token[$(this).html()]);
            }
            else if ($(this).prop('tagName') == 'INPUT' && $(this).prop('type') == 'submit' || $(this).prop('type') == 'button' && json.token.hasOwnProperty($(this).val()))
            {
                $(this).val(json.token[$(this).val()]);
            }
            else if ($(this).prop('tagName') == 'INPUT' && $(this).prop('type') == 'text' && json.token.hasOwnProperty($(this).attr('placeholder')))
                        {
                            $(this).attr('placeholder', (json.token[$(this).attr('placeholder')]));
                        }
        });
    });
}
</script>
