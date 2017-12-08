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

$doc = $_GET['doc'];

$ArrayPatients = array();
$numeral = 0;
$numeralF = 0;
$antiguo = 30;
$NPackets = 0;

$resultPAC = $con->prepare("SELECT distinct IdUs FROM doctorslinkusers WHERE IdMED = ?");
$resultPAC->bindValue(1, $doc, PDO::PARAM_INT);
$resultPAC->execute();

while ($rowP1 = $resultPAC->fetch(PDO::FETCH_ASSOC))
{
    $ArrayPatients[$numeral] = $rowP1['IdUs'];
    $numeral++;
    $antig = time() - strtotime($rowP1['Fecha']);
    $days = floor($antig / (60*60*24));
    if ($days<$antiguo) 
        $numeralF++;
    
    $resultPIN = $con->prepare("SELECT * FROM lifepin WHERE IdUsu = ? ");
    $resultPIN->bindValue(1, $rowP1['IdUs'], PDO::PARAM_INT);
    $resultPIN->execute();
    $NPackets += $resultPIN->rowCount();
}

$MIOS = $numeral;
$MIOSF = $numeralF;
$NPacketsMIOS = $NPackets;

$resultCRU = $con->prepare("SELECT * FROM doctorslinkdoctors WHERE IdMED=?");
$resultCRU->bindValue(1, $doc, PDO::PARAM_INT);
$resultCRU->execute();

while ($rowCRU = $resultCRU->fetch(PDO::FETCH_ASSOC))
{
    $Autorizado = $rowCRU['IdMED2'];
    $resultPAC2 = $con->prepare("SELECT * FROM doctorslinkusers WHERE IdMED=? ");
    $resultPAC2->bindValue(1, $Autorizado, PDO::PARAM_INT);
    $resultPAC2->execute();

    while ($rowC2 = $resultPAC2->fetch(PDO::FETCH_ASSOC))
    {
        $idEncontrado = $rowC2['IdUs'];
        if (!in_array($idEncontrado, $ArrayPatients))
        {
            $ArrayPatients[$numeral] = $idEncontrado;
            $numeral++;
            $antig = time()-strtotime($rowC2['Fecha']);
            $days = floor($antig / (60*60*24));
            if ($days < $antiguo) 
                $numeralF++;
            
            $resultPIN = $con->prepare("SELECT * FROM lifepin WHERE IdUsu = ? ");
            $resultPIN->bindValue(1, $idEncontrado, PDO::PARAM_INT);
            $resultPIN->execute();
            $NPackets += $resultPIN->rowCount();

        }
    }		 

}

$CONACCESO = $numeral;
$CONACCESOF = $numeralF;

$gauge_value = 0;
if($NPackets > 0)
{
    $gauge_value = number_format(((100 * $NPacketsMIOS) / $NPackets), 0, ',', ' ');
}
$result = array();
$result['members'] = $MIOS;
$result['members_size'] = 70 - (strlen(strval($MIOS)) * 20) + 40;
$result['reach'] = $CONACCESO;
$result['reach_size'] = 70 - (strlen(strval($CONACCESO)) * 20) + 40;
$result['new_info'] = number_format((($MIOSF * 100) / ($MIOS+.000001)), 0, ',', ' ');
$result['new_info_from_reach'] = number_format((($CONACCESOF * 100) / ($CONACCESO+.000001)), 0, ',', ' ');
$result['gauge_value'] = $gauge_value;

echo json_encode($result);
?>