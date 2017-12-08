<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
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

  $link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
  mysql_select_db("$dbname")or die("cannot select DB");
  
  function cleanquery($string)
{
  if (get_magic_quotes_gpc())
  {
  $string = stripslashes($string);
  }
  $string = mysql_real_escape_string($string);
  return $string;
}
  
$queUsu = cleanquery($_POST['Doctor']);
$NReports = cleanquery($_POST['NReports']);

$withGroup = $_POST['Group'];

$colorQuery = $con->query("SELECT type FROM doctors WHERE id = ".$queUsu);
$colorResult = $colorQuery->fetch(PDO::FETCH_ASSOC);
$colorStandard = '#22AEFF';
$trColor = '#CACACA';
$trFrame = '#CACACA';
if(strpos($colorResult['type'], 'CATA') !== false) {
    $colorStandard = 'Red';
    $trColor = 'DarkRed';
    $trFrame = 'Red';
}
    

if ($withGroup == 0) {
    
    
    /* The File has been totally rewritten by Mars */
    
    $query_str =   "SELECT idus.Identif, U.Name, U.Surname, 
                    IFNULL(L.UPReport,0) AS UPreport, 
                    SUM(IF(M.tofrom = 'to',1,0)) AS Msg, 
                    SUM(IF(M.tofrom = 'from',1,0)) AS MsgSent, 

                    TIMESTAMPDIFF(HOUR, GREATEST(IFNULL(L.FechaInput, '1900-01-01'), IFNULL(M.fecha, '1900-01-01'), IFNULL(C.lastActive, '1900-01-01'), IFNULL(B.latest_update, '1900-01-01'), IFNULL(F.latest_update, '1900-01-01'), IFNULL(D.latest_update, '1900-01-01'), IFNULL(H.latest_update, '1900-01-01'), IFNULL(I.latest_update, '1900-01-01'), IFNULL(m.latest_update, '1900-01-01')), NOW()) AS recent,

                    TIMESTAMPDIFF(SECOND, GREATEST(IFNULL(L.FechaInput, '1900-01-01'), IFNULL(M.fecha, '1900-01-01'), IFNULL(C.lastActive, '1900-01-01'), IFNULL(B.latest_update, '1900-01-01'), IFNULL(F.latest_update, '1900-01-01'), IFNULL(D.latest_update, '1900-01-01'), IFNULL(H.latest_update, '1900-01-01'), IFNULL(I.latest_update, '1900-01-01'), IFNULL(m.latest_update, '1900-01-01')), NOW()) AS compare,
                    
                    0 AS gr 

                    FROM

                    /* GETTING PATIENT IDS FROM IdCreator, DLU and DLD */
                    ( SELECT Identif FROM usuarios WHERE IdCreator = ? AND TIMESTAMPDIFF(DAY, signUpDate, NOW()) <= 30

                      UNION

                      SELECT IdUs as Identif FROM doctorslinkusers WHERE IdMED = ?

                      UNION

                      SELECT IdPac as Identif FROM doctorslinkdoctors WHERE IdMED2 = ?

                    ) idus
                    
                    /* JUST JOINING PURE usuarios TABLE FOR PATIENT NAMES */
                    LEFT JOIN usuarios U ON U.Identif = idus.Identif
                    
                    /* lifepin TABLE WHICH MATCHES TO THE ABOVE PATIENT IDS WITHIN 30 DAYS FROM NOW AND NOT MARKED AS DELETED */
                    LEFT JOIN (SELECT COUNT(IdPin) AS UPReport, IdUsu, FechaInput FROM lifepin WHERE markfordelete IS NULL AND TIMESTAMPDIFF(DAY, FechaInput, NOW()) <= 30 GROUP BY IdUsu ) L ON L.IdUsu = idus.Identif
                    
                    /* message_infrastructureuser TABLE WHICH MATCHES TO THE ABOVE PATIENT IDS WITHIN 30 DAYS FROM NOW */
                    LEFT JOIN (SELECT patient_id, fecha, tofrom FROM message_infrastructureuser WHERE TIMESTAMPDIFF(DAY, fecha, NOW()) <= 30 GROUP BY message_id) M ON M.patient_id = idus.Identif 
                    
                    /* consults TABLE WHICH MATCHES TO THE ABOVE PATIENT IDS WITHIN 30 DAYS FROM NOW WHEN THE PATIENT HAD A CONSULTATION WITH THE DOCTOR ONLY. JUST FOR SHOWING THE PATIENT */
                    LEFT JOIN (SELECT Patient, Doctor, lastActive FROM consults WHERE TIMESTAMPDIFF(DAY, lastActive, NOW()) <= 30 GROUP BY consultationId) C ON C.Patient = idus.Identif AND C.Doctor = ?
                    
                    /* basicemrdata TABLE WHICH MATCHES TO THE ABOVE PATIENT IDS WITHIN 30 DAYS FROM NOW. JUST FOR SHOWING THE PATIENT */
                    LEFT JOIN (SELECT IdPatient, latest_update FROM basicemrdata WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY IdPatient) B ON B.IdPatient = idus.Identif
                    
                    /* p_family TABLE WHICH MATCHES TO THE ABOVE PATIENT IDS WITHIN 30 DAYS FROM NOW. JUST FOR SHOWING THE PATIENT */
                    LEFT JOIN (SELECT idpatient, latest_update FROM p_family WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) F ON F.idpatient = idus.Identif
                    
                    /* p_diagnostics TABLE WHICH MATCHES TO THE ABOVE PATIENT IDS WITHIN 30 DAYS FROM NOW. JUST FOR SHOWING THE PATIENT */
                    LEFT JOIN (SELECT idpatient, latest_update FROM p_diagnostics WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) D ON D.idpatient = idus.Identif
                    
                    /* p_habits TABLE WHICH MATCHES TO THE ABOVE PATIENT IDS WITHIN 30 DAYS FROM NOW. JUST FOR SHOWING THE PATIENT */
                    LEFT JOIN (SELECT idpatient, latest_update FROM p_habits WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) H ON H.idpatient = idus.Identif
                    
                    /* p_immuno TABLE WHICH MATCHES TO THE ABOVE PATIENT IDS WITHIN 30 DAYS FROM NOW. JUST FOR SHOWING THE PATIENT */
                    LEFT JOIN (SELECT idpatient, latest_update FROM p_immuno WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) I ON I.idpatient = idus.Identif
                    
                    /* p_medication TABLE WHICH MATCHES TO THE ABOVE PATIENT IDS WITHIN 30 DAYS FROM NOW. JUST FOR SHOWING THE PATIENT */
                    LEFT JOIN (SELECT idpatient, latest_update FROM p_medication WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) m ON m.idpatient = idus.Identif

                    GROUP BY idus.Identif
                    /* LIMITS SET FOR RECENT 30 DAYS FROM NOW */
                    HAVING recent < 720 and compare < 2592000
                    ORDER BY compare, U.Surname, U.Name";
                    
                    
    $query = $con->prepare($query_str);
                            
	$query->bindValue(1, $queUsu, PDO::PARAM_INT);
	$query->bindValue(2, $queUsu, PDO::PARAM_INT);    
    $query->bindValue(3, $queUsu, PDO::PARAM_INT);
    $query->bindValue(4, $queUsu, PDO::PARAM_INT);
    
}
else {
     /*
     *  Query Documentation
     *
     *  Same as above but added 2 INNER JOIN of doctorsgroups to compare the IdDoctor with the assigned doctor of the table and another one for connecting that IdDoctor to be the same IdGroup with $queUsu to each subquery. 
     *  THE LAST gr COLUMN DETERMINES THE BACKGROUND COLOR WHETHER THE PATIENT IS THE DOCTOR'S OWN OR OF A DOCTOR IN THE DOCTOR'S GROUP
     */
    $query_str = "SELECT idus.Identif, U.Name, U.Surname, 
                  IFNULL(L.UPReport,0) AS UPreport, 
                  SUM(IF(M.tofrom = 'to',1,0)) AS Msg, 
                  SUM(IF(M.tofrom = 'from',1,0)) AS MsgSent, 

                  TIMESTAMPDIFF(HOUR, GREATEST(IFNULL(L.FechaInput, '1900-01-01'), IFNULL(M.fecha, '1900-01-01'), IFNULL(Con.lastActive, '1900-01-01'), IFNULL(B.latest_update, '1900-01-01'), IFNULL(F.latest_update, '1900-01-01'), IFNULL(D.latest_update, '1900-01-01'), IFNULL(H.latest_update, '1900-01-01'), IFNULL(I.latest_update, '1900-01-01'), IFNULL(m.latest_update, '1900-01-01')), NOW()) AS recent,

                  TIMESTAMPDIFF(SECOND, GREATEST(IFNULL(L.FechaInput, '1900-01-01'), IFNULL(M.fecha, '1900-01-01'), IFNULL(Con.lastActive, '1900-01-01'), IFNULL(B.latest_update, '1900-01-01'), IFNULL(F.latest_update, '1900-01-01'), IFNULL(D.latest_update, '1900-01-01'), IFNULL(H.latest_update, '1900-01-01'), IFNULL(I.latest_update, '1900-01-01'), IFNULL(m.latest_update, '1900-01-01')), NOW()) AS compare,
                  
                  MIN(idus.gr) AS gr

                  FROM

                ( SELECT Identif, 0 AS gr FROM usuarios WHERE IdCreator = ? AND TIMESTAMPDIFF(DAY, signUpDate, NOW()) <= 30

                  UNION

                  SELECT DLU.IdUs AS Identif, IF(DLU.IdMED = ?,0,1) AS gr FROM doctorslinkusers DLU 
                    INNER JOIN doctorsgroups DG1 ON DLU.IdMED = DG1.idDoctor 
                    INNER JOIN doctorsgroups DG2 ON DG1.idGroup = DG2.idGroup 
                  WHERE DG2.idDoctor = ?

                  UNION

                  SELECT IdPac as Identif, IF(DLD.IdMED2 = ?,0,1) AS gr FROM doctorslinkdoctors DLD
                    INNER JOIN doctorsgroups DG1 ON DLD.IdMED2 = DG1.idDoctor 
                    INNER JOIN doctorsgroups DG2 ON DG1.idGroup = DG2.idGroup 
                  WHERE DG2.idDoctor = ?

                 ) idus
                 
                LEFT JOIN usuarios U ON U.Identif = idus.Identif
                LEFT JOIN (SELECT COUNT(IdPin) AS UPReport, IdUsu, FechaInput FROM lifepin WHERE markfordelete IS NULL AND TIMESTAMPDIFF(DAY, FechaInput, NOW()) <= 30 GROUP BY IdUsu) L ON L.IdUsu = idus.Identif
                LEFT JOIN (SELECT patient_id, fecha, tofrom FROM message_infrastructureuser WHERE TIMESTAMPDIFF(DAY, fecha, NOW()) <= 30 GROUP BY message_id) M ON M.patient_id = idus.Identif 
                LEFT JOIN (SELECT Patient, Doctor, lastActive FROM consults C
                    INNER JOIN doctorsgroups DG1 ON C.Doctor = DG1.idDoctor 
                    INNER JOIN doctorsgroups DG2 ON DG1.idGroup = DG2.idGroup 
                WHERE DG2.idDoctor = ? AND TIMESTAMPDIFF(DAY, C.lastActive, NOW()) <= 30 GROUP BY C.consultationId
                ) Con ON Con.Patient = idus.Identif
		
                LEFT JOIN (SELECT IdPatient, latest_update FROM basicemrdata WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY IdPatient) B ON B.IdPatient = idus.Identif
                LEFT JOIN (SELECT idpatient, latest_update FROM p_family WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) F ON F.idpatient = idus.Identif
                LEFT JOIN (SELECT idpatient, latest_update FROM p_diagnostics WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) D ON D.idpatient = idus.Identif
                LEFT JOIN (SELECT idpatient, latest_update FROM p_habits WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) H ON H.idpatient = idus.Identif
                LEFT JOIN (SELECT idpatient, latest_update FROM p_immuno WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) I ON I.idpatient = idus.Identif
                LEFT JOIN (SELECT idpatient, latest_update FROM p_medication WHERE TIMESTAMPDIFF(DAY, latest_update, NOW()) <= 30 GROUP BY idpatient) m ON m.idpatient = idus.Identif

                GROUP BY idus.Identif
                HAVING recent < 720 AND compare < 2592000
                ORDER BY compare, U.Surname, U.Name";
                    
                    
    $query = $con->prepare($query_str);
                            
	$query->bindValue(1, $queUsu, PDO::PARAM_INT);
	$query->bindValue(2, $queUsu, PDO::PARAM_INT);
    $query->bindValue(3, $queUsu, PDO::PARAM_INT);
    $query->bindValue(4, $queUsu, PDO::PARAM_INT);
    $query->bindValue(5, $queUsu, PDO::PARAM_INT);
    $query->bindValue(6, $queUsu, PDO::PARAM_INT);

}
try {
    $query->execute();
} catch (PDOException $e) {
    echo $e->getMessage();
}
$count = $query->rowCount();
//$result = $query->fetchAll(PDO::FETCH_ASSOC);

//Start of new code added by Pallab
$height = 47*$count + 25;

     if($height > 495)
     {
         $height = 'height:495px;';
         $overflow = 'overflow:scroll;';
     }
     else
         $height = "height:".$height."px;";
         

//End of new code added by Pallab
$html = '<div style="'.$height.$overflow.'"><table style="background-color:white; width:100%; "><tr style="border-bottom:1px solid '.$trFrame.'; height:25px; background-color:'.$trColor.'; color:white;"><td style="width:20px; padding-left:15px;"><p style="margin-bottom:0px;"><span style="font-size:14px;" lang="en">item</span></p></td><td style="width:100px; padding-left:15px;"><p style="margin-bottom:0px;"><span style="font-size:14px;" lang="en">Member Name</span></p></td><td style="width:10px;" lang="en">Rep</td><td style="width:5px;"></td><td style="width:10px;" lang="en">Msg</td><td style="width:40px; text-align: center;" lang="en">Time</td></tr>';

while($row = $query->fetch(PDO::FETCH_ASSOC)) {
    if($row['gr'] == 1) {
        if(strpos($colorResult['type'], 'CATA') !== false) $tr_bgcolor = "#FFEBEB;";
        else $tr_bgcolor = "#EBF5FF;";
    }
    else $tr_bgcolor = "white;";
    
    if ($row['recent'] > 24*14) {	
            $WhatLabel = 'weeks';
            $validDif = floor($row['recent']/(24*7));
    }
    else if ($row['recent'] >= 24) {	
            $WhatLabel = 'days';
            $validDif = floor($row['recent']/24);
    }
    else {
        $WhatLabel = 'hours';
        $validDif = floor($row['recent']);
    }
    
    switch(true)
    {
        case ($WhatLabel == 'weeks' && $validDif >= 4): 					
            $WhatColor = '#e9e9e9';
            break;
        case ($WhatLabel == 'weeks' && $validDif > 0 && $validDif < 4): 	
            $WhatColor = '#cacaca';
            break;
        case ($WhatLabel == 'days' && $validDif > 0 && $validDif < 14): 	
            $WhatColor = 'grey';
            break;
        case ($WhatLabel == 'hours' && $validDif >= 0 && $validDif < 24): 	
            $WhatColor = 'black';
            break; 
        default:								
            $WhatColor = 'blue';
            break;
    }
    
    $html .= '<tr style="height:46px; border-bottom:1px solid '.$trFrame.'; background:'.$tr_bgcolor.'">';
    $html .= '<td style=" width:20px; color:'.$colorStandard.'; font-size:12px;" title="NEW MESSAGES &#013;NEW REPORTS">';
    $html .= '<div style="width:100%; text-align:center; font-size:14px; color:'.($row['Msg'] == 0 && $row['MsgSent'] == 0 ? '#e9e9e9' : ''.$colorStandard.'').';"><icon class="icon-envelope"></icon></div>';
    $html .= '<div style="width:100%; text-align:center; margin-top:-5px; font-size:14px; color:'.($row['UPreport'] == 0 ? '#e9e9e9' : ''.$colorStandard.'').';"><icon class="icon-folder-open"></icon></div></td>';
    $html .= '<td style="padding:6px;"><a href="patientdetailMED-new.php?IdUsu='.$row['Identif'].'" style="text-decoration:none;"><div class="truncate" style="line-height:1; color: #54bc00; font-size:14px;">'.$row["Name"].'</div><div class="truncate" style="color: #54bc00; font-size:14px; margin-top:0px;"> '.strtoupper($row["Surname"]).'</div></a></td>';						
    $html .= '<td style="width:10px; color:'.$colorStandard.'; font-size:12px; text-align: center;" title="Updated Reports">';
    $html .= $row['UPreport'].'</td>';
    $html .= '<td style="width:5px;"></td>';

    $html .= '<td style="width:10px; color:'.$colorStandard.'; font-size:12px;" title="Message Received / Sent">';
    $html .= $row['Msg'].'/'.$row['MsgSent'].'</td>';
    
    $html .= '<td style="width:40px; text-align:center; color: white; background-color:'.$WhatColor.'; "><div style="width:100%; font-size:18px;" title="Time since last recorded activity for this patient">'.$validDif.'</div><div style="width:100%; font-size:10px; margin-top:-5px;" lang="en">'.$WhatLabel.'</div></td></tr>';
}

$html .= '</table></div>';

echo $html;

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
            else if(json.token.hasOwnProperty($(this).prop('title')))
                        {
                            $(this).prop('title', json.token[$(this).prop('title')]);
                        }
        });
    });
}
</script>
