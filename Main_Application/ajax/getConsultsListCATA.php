<?php
/* The File has been totally rewritten by Mars */
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

$colorQuery = $con->query("SELECT grant_access FROM doctors WHERE id = ".$queUsu);
$colorResult = $colorQuery->fetch(PDO::FETCH_ASSOC);
$colorStandard = '#22AEFF';
$trColor = '#CACACA';
$trFrame = '#CACACA';
if($colorResult['grant_access'] == 'CATA') {
    $colorStandard = 'Red';
    $trColor = 'DarkRed';
    $trFrame = 'Red';
}
    

if ($withGroup == 0) {
     
    $query_str = "SELECT COUNT(*) FROM reservation WHERE status IS NOT 'CANCELED' OR status IS NOT 'MISSED' AND provider_id = ? AND date LIKE ?";    
                    
    $query = $con->prepare($query_str);
                            
	$query->bindValue(1, $queUsu, PDO::PARAM_INT);
    $query->bindValue(2, $queUsu, PDO::PARAM_INT);
    $query->bindValue(3, $queUsu, PDO::PARAM_INT);
    $query->bindValue(4, $queUsu, PDO::PARAM_INT);
    
    
}
else {
     /* when group */

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
$html = '<div style="'.$height.$overflow.'"><table style="background-color:white; width:100%; "><tr style="border-bottom:1px solid '.$trFrame.'; height:25px; background-color:'.$trColor.'; color:white;"><!--td style="width:20px; padding-left:15px;"><p style="margin-bottom:0px;"><span style="font-size:14px;" lang="en">item</span></p></td--><td style="width:100px; padding-left:15px;"><p style="margin-bottom:0px;"><span style="font-size:14px;" lang="en">Member Name</span></p></td><td style="width:45px;" lang="en">Created</td><td style="width:5px;"></td><td style="width:45px;" lang="en">Reserved Time</td><td style="width:40px; text-align: center;" lang="en">Remaining</td></tr>';

while($row = $query->fetch(PDO::FETCH_ASSOC)) {
    if($row['gr'] == 1) {
        if($colorResult['grant_access'] == 'CATA') $tr_bgcolor = "#FFEBEB;";
        else $tr_bgcolor = "#EBF5FF;";
    }
    else $tr_bgcolor = "white;";
    
    if ($row['remain'] > 59) {	
            $WhatLabel = 'hours';
            $validDif = floor($row['remain']/60);
    }
    else if ($row['remain'] > 60*24) {	
            $WhatLabel = 'days';
            $validDif = floor($row['remain']/(60*24));
    }
    else if ($row['remain'] > 60*24*7) {	
            $WhatLabel = 'weeks';
            $validDif = floor($row['remain']/(60*24*7));
    }
    else {
        $WhatLabel = 'minutes';
        $validDif = floor($row['recent']);
    }
    
    switch(true)
    {
        case ($WhatLabel == 'hours' && validDif > 0 && $validDif < 24): 	
            $WhatColor = 'grey';
            break;
        case ($WhatLabel == 'days' && $validDif > 0 && $validDif <= 7): 	
            $WhatColor = '#cacaca';
            break;
        case ($WhatLabel == 'weeks' && validDif > 0): 	
            $WhatColor = '#e9e9e9';
            break;
        default:								
            $WhatColor = 'black';
            break;
    }
    
    $html .= '<tr style="height:46px; border-bottom:1px solid '.$trFrame.'; background:'.$tr_bgcolor.'">';
    //$html .= '<td style=" width:20px; color:'.$colorStandard.'; font-size:12px;" title="NEW MESSAGES &#013;NEW REPORTS">';
   //$html .= '<div style="width:100%; text-align:center; font-size:14px; color:'.($row['Msg'] == 0 && $row['MsgSent'] == 0 ? '#e9e9e9' : ''.$colorStandard.'').';"><icon class="icon-envelope"></icon></div>';
    //$html .= '<div style="width:100%; text-align:center; margin-top:-5px; font-size:14px; color:'.($row['UPreport'] == 0 ? '#e9e9e9' : ''.$colorStandard.'').';"><icon class="icon-folder-open"></icon></div></td>';
    $html .= '<td style="padding:6px;"><a href="../provider/patientdetail/html/PatientDetails.php?IdUsu='.$row['Identif'].'" style="text-decoration:none;"><div class="truncate" style="line-height:1; color: #54bc00; font-size:14px;">'.$row["Name"].'</div><div class="truncate" style="color: #54bc00; font-size:14px; margin-top:0px;"> '.strtoupper($row["Surname"]).'</div></a></td>';						
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
    $.get('jquery-lang-js-master/js/langpack/'+initial_language+'.json', function(data, status)
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
