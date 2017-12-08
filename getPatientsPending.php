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
$NReports = $_GET['NReports'];
$IdDoc = $_GET['IdDoc'];

$result = $con->prepare("SELECT IdGroup FROM doctorsgroups WHERE IdDoctor = ?");
$result->bindValue(1, $IdDoc, PDO::PARAM_INT);
$result->execute();

$row = $result->fetch(PDO::FETCH_ASSOC);
$MyGroup = $row['IdGroup'];


// Pending connection: Users of other doctors of my group( not including me) with an entry on DLU (patient-me) with pending confirmation 
// ***shorthand:  all users pending DLU confirmation 
//$result = mysql_query("SELECT * FROM usuarios WHERE Identif IN (SELECT IdUs FROM doctorslinkusers WHERE IdMED = '$IdDoc' AND estado<>'2') ");
// Pending invitation: Users of connected to me (owned or  DLU connected) with TempoPass not null (awaiting confirmation)
//$result = mysql_query("SELECT * FROM usuarios WHERE TempoPass IS NOT NULL AND (IdCreator = '$IdDoc' OR Identif IN (SELECT IdUs FROM doctorslinkusers WHERE IdMED = '$IdDoc'))");


$result = $con->prepare("SELECT * FROM usuarios WHERE (Identif IN (SELECT IdUs FROM doctorslinkusers WHERE IdMED = ? AND estado<>'2')) OR (TempoPass <> ''  AND (IdCreator = ? OR Identif IN (SELECT IdUs FROM doctorslinkusers WHERE IdMED = ?))) ");
$result->bindValue(1, $IdDoc, PDO::PARAM_INT);
$result->bindValue(2, $IdDoc, PDO::PARAM_INT);
$result->bindValue(3, $IdDoc, PDO::PARAM_INT);

//Added by Pallab
$msc=microtime(true); 
$result->execute();
$msc=microtime(true)-$msc;  //Pallab
echo $msc.' seconds';


$countPending = $result->rowCount();

if ($countPending == '0')
{
}
else
{    
        echo  '<table class="table table-mod" id="TablaPending" style="height:100px; width:100%; overflow-y:hidden; table-layout: fixed; border: 1px SOLID #CACACA;">';
        echo '<thead>
        <tr>
            <th style="width:150px;">User</th>
            <th style="width:100px;">Password</th>
            <th style="width:100px;">Pending</th>
            <th style="width:100px;">Status</th>
            <th style="width:100px;">Tools</th>
        </tr>
        </thead>';
        echo '<tbody>';

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        
        $PatientId = $row['Identif'];
        
        $StatusP='';
        $LINKPending ='';
        $INVITPending ='';
            
        $resultDLU = $con->prepare("SELECT * FROM doctorslinkusers WHERE IdUs = ? AND IdMED = ? AND estado<>'2'");
		$resultDLU->bindValue(1, $PatientId, PDO::PARAM_INT);
		$resultDLU->bindValue(2, $IdDoc, PDO::PARAM_INT);
		$resultDLU->execute();
		
        //$rowDLU = mysql_fetch_array($resultDLU);
        $countDLU = 0;
        $countDLU = $resultDLU->rowCount();
        if ($countDLU > 0){
            $StatusP = '<span class="label label-success" title="" style="background-color:red; margin-left:5px;">Link Request</span>';
            //$LINKPending = 'DLU waiting (IdUs= '.$PatientId.', IdMED= '.$IdDoc.')';
            $LINKPending = 'Not yet Linked';
            $TypeRevoke = 1;
        } 
            
        $TempoPass = $row['TempoPass'];
        
        if ($TempoPass > ''){
            $StatusP = '<span class="label label-success" title="" style="background-color:#22aeff; margin-left:5px;">Invitation Ack.</span>';
            //$INVITPending = 'Sent Invitation (TempoPass = '.$TempoPass.')';
            $INVITPending = 'Not H2M user';
            $TypeRevoke = 2;
        }
            
            
        $resultRef = $con->prepare("SELECT * FROM doctorslinkdoctors WHERE (IdMED = ? AND IdPac = ?) OR (IdMED2 = ? AND IdPac = ?) ");
		$resultRef->bindValue(1, $IdDoc, PDO::PARAM_INT);
		$resultRef->bindValue(2, $PatientId, PDO::PARAM_INT);
		$resultRef->bindValue(3, $IdDoc, PDO::PARAM_INT);
		$resultRef->bindValue(4, $PatientId, PDO::PARAM_INT);
		$resultRef->execute();
		
		
        $countRef = $resultRef->rowCount();
        if ($countRef > 0)  {  $IsReferred = 'visible'; } else { $IsReferred = 'hidden'; }
        
        if ($row['IdUsRESERV']>'' ) {  $IsUserH2M = 'visible'; } else { $IsUserH2M = 'hidden'; }
        //$PasswordDis ='-'.$row['Password'].'- ** -'.$TempoPass.'-';
        $PasswordDis =$TempoPass;
           
        echo '<tr  class="CFILAPATROWModal">
                <th id="'.$row['Identif'].'" class="CFILAPATPending" style="height:14px;"><span style="font-size:14px; color:#54bc00; font-weight:normal;"><a  class="neutral" href="javascript:void(0)">'.SCaps($row['Name']).' '.SCaps($row['Surname']).'</a><i class="icon-plus-sign-alt" title="This patient has been referred between doctors" style="float:right; color:#22aeff; visibility:'.$IsReferred.';"> <span style="color:grey; font-size:10px;"></span></i><i class="icon-user" title="This patient is a user of Halth2Me himself" style="float:right; margin-right:50px; color:#54bc00; visibility:'.$IsUserH2M.';"> <span style="color:grey; font-size:10px;"></span></i></span>
                </th>
                <th style="text-align:center;">
                    '.$PasswordDis.'
                </th>
                <th style="text-align:center;">
                    '.$StatusP.'
                </th>
                <th style="text-align:center;">
                    '.$LINKPending.' '.$INVITPending.'
                </th>
                <th style="text-align:center;" class="CFILAPATPendingBot" >
                   <div style="text-align:center;">
                       <div  class="CFILAPATPendingBot2" id="'.$PatientId.'"  id2="BRevoke" id3="'.$TypeRevoke.'"  class="BRevoke" style="text-align:center; margin-left:0px; margin-top:0px; " class=""><a href="#" class="btn" title="Send Invitation" style="text-align:center;  color:red; width:60px; font-size:10px; font-weight:normal;"><i class="icon-off"></i>Revoke</a></div>
                       <!--<div  class="CFILAPATPendingBot3" id="'.$PatientId.'"  id2="BResend" class="BRevoke" style="float:left; text-align:center; margin-left:0px; margin-top:0px; " class=""><a href="#" class="btn" title="Send Again" style="margin-left:20px; text-align:center; color:green;  width:40px; font-size:10px; font-weight:normal;"><i class="icon-mail-reply"></i></a></div>--->
                   </div>
               </th>
              </tr>
        ';
        
        
        }
        
        echo '</tbody></table>';    
        //echo '<input type="hidden" id="UTotal" Value="'.$UTotal.'"><input type="hidden" id="UConn" value="'.$UConn.'"><input type="hidden" id="TotMsg" value="'.$TotMsg.'"><input type="hidden" id="TotUpDr" value="'.$TotUpDr.'"><input type="hidden" id="TotUpUs" value="'.$TotUpUs.'">';
}

function SCaps ($cadena)
{
return strtoupper(substr($cadena,0,1)).substr($cadena,1);
}    

?>