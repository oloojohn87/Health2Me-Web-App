<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
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

if($queUsu)
{
	//$search = " AND (Res.Name like ? OR Res.Surname like ?) ";
    $search = " WHERE (Res.Name like ? OR Res.Surname like ?) ";
}
else
{
	$search=" LIMIT 0,50";
}


/*
$result = mysql_query("SELECT * FROM usuarios WHERE ( IdCreator = '$IdDoc' or IdCreator IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = '$MyGroup') or Identif IN (SELECT IdUs FROM doctorslinkusers WHERE IdMED = '$IdDoc')) ORDER BY Surname ASC");
$count=mysql_num_rows($result);
$UTotal =  $count;
*/
$UTotal =  0.1;
//$result = mysql_query("SELECT * FROM usuarios WHERE Password IS NULL AND ( IdCreator = '$IdDoc' or IdCreator IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = '$MyGroup') or Identif IN (SELECT IdUs FROM doctorslinkusers WHERE IdMED = '$IdDoc')) ORDER BY Surname ASC");

//$query = "SELECT * FROM usuarios WHERE (Password IS NULL AND ( IdCreator = '$IdDoc' or Identif IN (SELECT IdUs FROM doctorslinkusers WHERE IdMED = '$IdDoc'))) OR (Password IS NULL AND (IdCreator IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = '$MyGroup'))) $search ORDER BY Surname ASC";
//echo $query;
//return;

/*$main_query = $con->prepare("SELECT * FROM usuarios U LEFT JOIN doctorslinkusers D ON U.Identif = D.IdUs LEFT JOIN key_chain KC ON KC.owner = U.Identif WHERE 
                                        (   
                                            U.IdCreator = ? 
                                                    OR 
                                            U.IdCreator IN 
                                                            (
                                                                SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = ?
                                                            )
                                                    OR 
                                            D.IdMED = ?
                                        ) 
                                        AND (U.salt IS NULL OR U.IdUsRESERV IS NULL) AND KC.owner IS NULL ".$search);*/

$main_query = $con->prepare("SELECT Res.Identif, Res.Name, Res.Surname, Res.email, DLD.IdPac IS NOT NULL AS referred, LP.sum AS reports, PR.probestate, CM.connected FROM
        (
            (
                SELECT USU.Identif, USU.Name, USU.Surname, USU.email FROM usuarios USU 
                    INNER JOIN (    
                        SELECT DISTINCT A.idDoctor FROM doctorsgroups A 
                        INNER JOIN (
                            SELECT idGroup FROM doctorsgroups WHERE idDoctor=?
                        ) 
                        B ON B.idGroup = A.idGroup
                    ) 
                    DG ON DG.idDoctor = USU.IdCreator
                )
	                   
                UNION
                
                (
                    SELECT USU.Identif, USU.Name, USU.Surname, USU.email FROM usuarios USU 
                    INNER JOIN (
                        SELECT IdPac FROM doctorslinkdoctors WHERE IdMED2=?
                    ) DLD ON DLD.IdPac = USU.Identif
                )
                
                UNION
                
                (
                    SELECT USU.Identif, USU.Name, USU.Surname, USU.email FROM usuarios USU
                    INNER JOIN (
                        SELECT IdUs FROM doctorslinkusers WHERE IdMED=?
                    ) DLU ON DLU.IdUs = USU.Identif
                )
            ) AS Res 
            
                LEFT JOIN 
                
            (SELECT IdPac FROM doctorslinkdoctors WHERE IdMED = ? GROUP BY IdPac, IdMED) AS DLD 
            
                ON Res.Identif = DLD.IdPac
                
                LEFT JOIN 
            
            (SELECT DISTINCT IdUsu AS ID, COUNT(*) AS sum FROM lifepin GROUP BY IdUsu) AS LP 
            
                ON LP.ID = Res.Identif
                
                LEFT JOIN
                
            (SELECT patientID, IF(doctorPermission = 1 AND patientPermission = 1, 1, 0) AS probestate FROM probe WHERE doctorID = ?) AS PR
                
                ON PR.patientID = Res.Identif
                
                LEFT JOIN
                
            (SELECT Identif, IF(IdUsRESERV IS NOT NULL AND salt IS NOT NULL AND IdUsRESERV != '' AND salt != '', 1, 0) AS connected FROM usuarios) AS CM
                
                ON CM.Identif = Res.Identif
                
                ".$search);
            
         /*   WHERE 
            
                Res.Identif NOT IN (SELECT owner FROM key_chain)".$search);*/
                                        

$main_query->bindValue(1, $IdDoc, PDO::PARAM_INT);
$main_query->bindValue(2, $IdDoc, PDO::PARAM_INT);
$main_query->bindValue(3, $IdDoc, PDO::PARAM_INT);
$main_query->bindValue(4, $IdDoc, PDO::PARAM_INT);
$main_query->bindValue(5, $IdDoc, PDO::PARAM_INT);
if($queUsu)
{
    $main_query->bindValue(6, '%'.$queUsu.'%', PDO::PARAM_STR);
    $main_query->bindValue(7, '%'.$queUsu.'%', PDO::PARAM_STR);
}


$result = $main_query->execute();

$count = $main_query->rowCount();
$UConn =  $count;

$res = array();
while($row = $main_query->fetch(PDO::FETCH_ASSOC))
{
    if($row['reports'] == NULL)
        $row['reports'] = 0;
    $arr = array("id" => $row['Identif'], "name" => $row['Name'].' '.$row['Surname'], "email" => $row['email'], "referred" => $row['referred'], "reports" => $row['reports'], "probestate" => $row['probestate'], "connected" => $row['connected']);
    array_push($res, $arr);
}

echo json_encode($res);

/*
echo  '<table class="table table-mod" id="TablaPacModal" style="height:100px; width:100%; overflow-y:hidden; table-layout: fixed; border: 1px SOLID #CACACA;">';
echo '<thead>
<tr>
    <th style="width:200px;">Available User</th>
    <th style="width:100px;">Action</th>
    <th style="width:100px;">Type</th>
    <th style="width:50px;">Knows Me?</th>
</tr>
</thead>';
echo '<tbody>';

$TotMsg = 0;
$TotUpDr = 0;
$TotUpUs = 0;
//$row = mysql_fetch_array($result);





while ($row = $main_query->fetch(PDO::FETCH_ASSOC)) {
    $PatientId = $row['Identif'];
    $PatientEmail = $row['email'];
    $PatientPhone = $row['telefono'];
    $TempoPass = $row['TempoPass'];

    if ($row['Password']>'' ) {  $IsUserH2M = 'visible'; } else { $IsUserH2M = 'hidden'; }
    $IsOnVR = '';
    //Get Type of relationship
    if ($row['IdCreator'] == $IdDoc) 
    {
        $TypeRel='Direct';
        $Rel = 1;
        $Knows = 1;
        $TypeColor = '#54bc00';
        $KnowsMe = 'YES';
        $KnowsMeColor = '#54bc00';
        if ($TempoPass > '')
        {
            $ActionRel = 'Waiting Invitation Ack';
            $ActionColor = 'red';
        }
        else
        {
            $ActionRel = 'INVITE';
            $ActionColor = '#54bc00';
        }
    }
    else
    {
        $resultI1 = $con->prepare("SELECT id FROM doctors WHERE id IN (SELECT IdDoctor FROM doctorsgroups WHERE IdGroup = ?)");
        $resultI1->bindValue(1, $MyGroup, PDO::PARAM_INT);
        $resultI1->execute();

        $countI1 = $resultI1->rowCount();

        if ($countI1>0)
        {
            $TypeRel='Group';
            $Rel = 2;
            $Knows = 0;
            $TypeColor = '#22aaff';
            $KnowsMe = 'NO';
            $KnowsMeColor = '#22aaff';
            $IsOnVR = '';
            $ActionRel = 'Request Link';
            $ActionColor = '#22aaff';
        }

        $resultI2 = $con->prepare("SELECT IdUs,estado FROM doctorslinkusers WHERE IdMED = ? AND IdUs = ?");
        $resultI2->bindValue(1, $IdDoc, PDO::PARAM_INT);
        $resultI2->bindValue(2, $PatientId, PDO::PARAM_INT);
        $resultI2->execute();

        $countI2 = $resultI2->rowCount();
        $rowE = $resultI2->fetch(PDO::FETCH_ASSOC);
        $estado = $rowE['estado'];
        if ($countI2>0)
        {
            $IsOnVR = '<span class="label label-success" title="This user has given specific permission to connect with him" style="background-color:rgb(255,130,66);; margin-left:5px;">Specific</span>';
            //$TypeRel='via Report';
            //$TypeColor = 'red';
            $Rel = 3;
            $Knows = 1;
            $KnowsMe = 'YES';
            $KnowsMeColor = '#54bc00';
            if ($estado == '1')
            {
                $ActionRel = 'Waiting Link Ack';
                $ActionColor = 'red';
            }
            else
            {
                $ActionRel = 'INVITE';
                $ActionColor = '#54bc00';
            }
        }
    }

    if (!$row['email']) $row['email']='.';   


    $resultRef = $con->prepare("SELECT * FROM doctorslinkdoctors WHERE (IdMED = ? AND IdPac = ?) OR (IdMED2 = ? AND IdPac = ?) ");
    $resultRef->bindValue(1, $IdDoc, PDO::PARAM_INT);
    $resultRef->bindValue(2, $PatientId, PDO::PARAM_INT);
    $resultRef->bindValue(3, $IdDoc, PDO::PARAM_INT);
    $resultRef->bindValue(4, $PatientId, PDO::PARAM_INT);
    $resultRef->execute();

    $countRef = $resultRef->rowCount();
    if ($countRef > 0)  {  $IsReferred = 'visible'; } else { $IsReferred = 'hidden'; }

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


    echo '<tr  class="CFILAPATROWModal">
            <td id="'.$PatientId.'" id2="'.SCaps($row['Name']).' '.SCaps($row['Surname']).'" id3="'.$Rel.'" id4="'.$Knows.'" id5="'.$PatientEmail.'" id6="'.$PatientPhone.'" class="CFILAPATModal" style="height:14px;"><span style="font-size:14px; color:#54bc00; font-weight:normal;"><a  class="neutral" href="javascript:void(0)">'.SCaps($row['Name']).' '.SCaps($row['Surname']).'</a><i class="icon-plus-sign-alt" title="This patient has been referred between doctors" style="float:right; color:#22aeff; visibility:'.$IsReferred.';"> <span style="color:grey; font-size:10px;"></span></i><i class="icon-user" title="This patient is a user of Halth2Me himself" style="float:right; margin-right:50px; color:#54bc00; visibility:'.$IsUserH2M.';"> <span style="color:grey; font-size:10px;"></span></i></span>
            </td>
            <td style="text-align:center;">
                <span class="label label-success" style="background-color:'.$ActionColor.';">'.$ActionRel.'</span>
            </td>
            <td style="text-align:center;">
                <span class="label label-success" style="background-color:'.$TypeColor.';">'.$TypeRel.'</span>'.$IsOnVR.'
            </td>
            <td style=" text-align:center;">
                <span class="label label-success" style="background-color:'.$KnowsMeColor.';">'.$KnowsMe.'</span>
            </td>
          </tr>
    ';


}

echo '</tbody></table>';    
//echo '<input type="hidden" id="UTotal" Value="'.$UTotal.'"><input type="hidden" id="UConn" value="'.$UConn.'"><input type="hidden" id="TotMsg" value="'.$TotMsg.'"><input type="hidden" id="TotUpDr" value="'.$TotUpDr.'"><input type="hidden" id="TotUpUs" value="'.$TotUpUs.'">';
*/

function SCaps ($cadena)
{
return strtoupper(substr($cadena,0,1)).substr($cadena,1);
}    

?>
