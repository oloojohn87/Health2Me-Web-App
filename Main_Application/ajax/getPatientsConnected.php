<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
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
	

</script>
<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
 

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

$queUsu = $_GET['Usuario'];
$UserDOB = $_GET['UserDOB'];
//$NReports = $_GET['NReports'];
$IdDoc = $_GET['IdDoc'];
$start = $_GET['start'];
$numDisplay = 20;

$result = $con->prepare("SELECT IdGroup FROM doctorsgroups WHERE IdDoctor = ?");
$result->bindValue(1, $IdDoc, PDO::PARAM_INT);
$result->execute();

$row = $result->fetch(PDO::FETCH_ASSOC);
$MyGroup = $row['IdGroup'];
//echo 'MY GROUP = '.$MyGroup.' **********  ';

echo  '<table class="table table-mod" id="TablaSents" style="height:100px; width:600px; overflow-y:hidden; table-layout: fixed; border: 1px SOLID #CACACA;">';
echo '<thead>
<tr>
    <th style="width:250px;" lang="en">Connected User</th>
    <th style="width:400px;" lang="en">Status</th>
    <th style="width:200px;" lang="en">Tools</th>
</tr>
</thead>';
echo '<tbody>';

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
$result = $con->prepare("SELECT * FROM usuarios WHERE IdUsRESERV IS NOT NULL AND Surname like ? 
AND (IdCreator = ? OR IdCreator IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = ?) 
OR Identif IN (SELECT IdUs FROM doctorslinkusers WHERE IdMED = ?) 
OR Identif IN (SELECT IdPac FROM doctorslinkdoctors 
WHERE IdMED2 = ?)) ORDER BY Surname ASC LIMIT ?, $numDisplay");

$result = $con->prepare("Select q.* from ((select USU.* from ".$dbname.".usuarios USU INNER JOIN ((select A.idDoctor from ".$dbname.".doctorsgroups A INNER JOIN (select idGroup from ".$dbname.".doctorsgroups where idDoctor=?) B where B.idGroup=A.idGroup)) DG where DG.idDoctor=USU.IdCreator)UNION(select USU.* from ".$dbname.".usuarios USU INNER JOIN (select IdPac from ".$dbname.".doctorslinkdoctors where IdMED2=?) DLD where DLD.IdPac=USU.Identif)UNION(select USU.* from ".$dbname.".usuarios USU INNER JOIN (select IdUs from ".$dbname.".doctorslinkusers where IdMED=?) DLU where DLU.IdUs=USU.Identif)UNION(Select * from ".$dbname.".usuarios where IdCreator=?))q where q.Surname like ? or q.Name like ? or q.IdUsFIXEDNAME like ? ORDER BY Surname ASC LIMIT ?, $numDisplay");
$result->bindValue(1, $IdDoc, PDO::PARAM_INT);
$result->bindValue(2, $IdDoc, PDO::PARAM_INT);
$result->bindValue(3, $IdDoc, PDO::PARAM_INT);
$result->bindValue(4, $IdDoc, PDO::PARAM_INT);
$result->bindValue(5, '%'.$queUsu.'%', PDO::PARAM_STR);
$result->bindValue(6, '%'.$queUsu.'%', PDO::PARAM_STR);
$result->bindValue(7, '%'.$queUsu.'%', PDO::PARAM_STR);
$result->bindValue(8, $start, PDO::PARAM_INT);

//$result->bindValue(1, '%'.$queUsu.'%', PDO::PARAM_STR);
//$result->bindValue(2, $IdDoc, PDO::PARAM_INT);
//$result->bindValue(3, $MyGroup, PDO::PARAM_INT);
//$result->bindValue(4, $IdDoc, PDO::PARAM_INT);
//$result->bindValue(5, $IdDoc, PDO::PARAM_INT);
//$result->bindValue(6, $start, PDO::PARAM_INT);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

    //if (!$row['email']) 
        //$row['email']='.';   
    
    $PatientId = $row['Identif'];
     //echo $PatientId;
    $resultRef = $con->prepare("SELECT * FROM doctorslinkdoctors WHERE (IdMED = ? AND IdPac = ?) OR (IdMED2 = ? AND IdPac = ?) ");
	$resultRef->bindValue(1, $IdDoc, PDO::PARAM_INT);
	$resultRef->bindValue(2, $PatientId, PDO::PARAM_INT);
	$resultRef->bindValue(3, $IdDoc, PDO::PARAM_INT);
	$resultRef->bindValue(4, $PatientId, PDO::PARAM_INT);
	$resultRef->execute();
	
    $countRef = $resultRef->rowCount();
    if ($countRef > 0)  {  $IsReferred = 'visible'; } else { $IsReferred = 'hidden'; }
        
    $result2 = $con->prepare("SELECT * FROM message_infrastructureuser WHERE sender_id = ? AND patient_id = ? AND status='new' ");
	$result2->bindValue(1, $IdDoc, PDO::PARAM_INT);
	$result2->bindValue(2, $PatientId, PDO::PARAM_INT);
	$result2->execute();
	
    $count2 = $result2->rowCount();
        
    if ($count2 >0)
    {    
        $visible_baloon = 'visible';
        $unread = $count2;
        $message_color ='#22aeff';
        $addiclass='CENVELOPE';
    } 
    else
    {
        $visible_baloon = 'hidden';
        $unread = 0;
        $message_color ='#e0e0e0';
        $addiclass='';
    }
    $TotMsg = $TotMsg + $unread;
    
    //Browsing activity from the User
    // This retrieves only the last 30 days
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
        $repactivUpDr_color ='#22aeff';
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
	$result2->bindValue(2, $PatientId, PDO::PARAM_INT);
	$result2->execute();
	
    $count2 = $result2->rowCount();
    //echo '  ********************** COUNT = '.$count2;
    if ($count2 >0)
    {    
        $visible_baloon2 = 'visible';
        $NewRepPat = $count2;
        $repactivUpUser_color ='#54bc00';
    } 
    else
    {
        $visible_baloon2 = 'hidden';
        $NewRepPat =0;
        $repactivUpUser_color ='#e0e0e0';
    }
    $TotUpUs = $TotUpUs + $NewRepPat;
    
			$current_encoding = mb_detect_encoding($row['Name'], 'auto');
			$show_text = iconv($current_encoding, 'ISO-8859-1', $row['Name']);

			$current_encoding = mb_detect_encoding($row['Surname'], 'auto');
			$show_text2 = iconv($current_encoding, 'ISO-8859-1', $row['Surname']); 
        
    echo '<tr  class="CFILAPATROW">
            <th id="'.$PatientId.'" class="CFILAPAT" style="height:20px;"><span style="font-size:16px; color:#54bc00; font-weight:normal;"><a  class="neutral" href="javascript:void(0)">'.SCaps($show_text).' '.SCaps($show_text2).'</a><i class="icon-plus-sign-alt" title="This patient has been referred between doctors" style="float:right; color:#22aeff; visibility:'.$IsReferred.';"> <span style="color:grey; font-size:10px;"></span></i></span>
                <div style="width:100%; margin-top:-8px;"></div>
                <a class="neutral" href="javascript:void(0)"><span style="font-size:10px; color:grey; font-weight:normal;">'.$row['email'].'</span></a>
                <span style="font-size:10px; color:#22aeff; font-weight:normal; margin-left:10px;">('.$row['Identif'].')</span>
            </th>
            <th style="text-align:center;">
            <div>
                <span style="color:'.$message_color.';" title="New messages present"><a class="neutral" style="color:'.$message_color.'"><i id="'.$PatientId.'"  class="icon-envelope-alt icon-2x '.$addiclass.'"></i></a></span>
                <span style="visibility:'.$visible_baloon.'" class="H2MBaloon" >'.$unread.'</span>
                <span style="color:'.$repactivUpUser_color.'; margin-left:30px;" title="The Patient uploaded information in the past 30 days"><i class="icon-circle-arrow-up icon-2x "></i></span>
                <span style="visibility:'.$visible_baloon2.'; background-color:black;" class="H2MBaloon" >'.$NewRepPat.'</span>
                <span style="color:'.$repactivUpDr_color.'; margin-left:5px;" title="Doctors uploaded information in the past 30 days"><i class="icon-arrow-up icon-2x "></i></span>
                <span style="visibility:'.$visible_baloon3.'; background-color:grey;" class="H2MBaloon" >'.$NewRepDoc.'</span>
                <span style="color:'.$webactivWEB_color.'; margin-left:30px;" title="Desktop browsing activity in the past 30 days"><i class="icon-rss icon-2x "></i></span>
                <span style="color:'.$webactivMOB_color.'; margin-left:5px;" title="Mobile browsing activity in the past 30 days"><i class="icon-mobile-phone icon-2x "></i></span>
           </div>
            </th>
            <th style=" text-align:center;">
                <div style="float:left; margin-left:20px;">
                    <!--<div id="'.$PatientId.'"  id3="BRevoke" class="BRevoke" style="float:left; text-align:center; margin-left:0px; margin-top:0px; " class=""><a href="#" class="btn" title="Send Invitation" style="text-align:center;  width:40px; font-size:10px; font-weight:normal;"><i class="icon-off"></i>Rev</a></div>-->
                    <div id="'.$PatientId.'" id2="msg" id3="BMessage" class="BMessage" style="float:left; text-align:center; margin-left:30px; margin-top:0px; " class=""><a href="#" class="btn" title="Send Invitation" style="text-align:center;  width:80px; font-size:10px; font-weight:normal;"><i class="icon-envelope"></i lang="en">Send Msg</a></div>
                </div>
            </th>
          </tr>';


}

echo '</tbody></table>';

//echo '<input type="hidden" id="UTotal" Value="'.$UTotal.'"><input type="hidden" id="UConn" value="'.$UConn.'"><input type="hidden" id="TotMsg" value="'.$TotMsg.'"><input type="hidden" id="TotUpDr" value="'.$TotUpDr.'"><input type="hidden" id="TotUpUs" value="'.$TotUpUs.'">';


function SCaps ($cadena)
{
return strtoupper(substr($cadena,0,1)).substr($cadena,1);
}    

?>
