<?php
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

																								
/////////////////////////////THIS IS FOR CALCULATING CORRECT TOTAL PATIENTS////////////////////////////////
/*$MedID = $_POST['Doctor'];
$withGroup = 0;
if (isset($_POST['Group']))
{
    $withGroup = $_POST['Group'];
}
$resultDOC = $con->prepare("SELECT * FROM lifepin WHERE IdMed=?");
$resultDOC->bindValue(1, $MedID, PDO::PARAM_INT);
$resultDOC->execute();

$countDOC=$resultDOC->rowCount();
$r=0;
$EstadCanal = array(0,0,0,0,0,0,0,0,0,0);
$EstadCanalValid = array(0,0,0,0,0,0,0,0,0,0);
$EstadCanalNOValid = array(0,0,0,0,0,0,0,0,0,0);
$ValidationStatus = array(0,0,0,0,0,0,0,0,0,0,0);
while ($rowDOC = $resultDOC->fetch(PDO::FETCH_ASSOC))
{
    $Valid = $rowDOC['ValidationStatus'];
    $esvalido=0;
    //Add changes for null exception
    if (is_numeric($Valid)) {$ValidationStatus[$Valid] ++; $esvalido=1;}

    $Canal = $rowDOC['CANAL'];
    if (is_numeric($Canal)){
        $EstadCanal[$Canal] ++;
        if ($Valid==0 && $esvalido==1) {$EstadCanalValid[$Canal] ++;} else {$EstadCanalNOValid[$Canal] ++;}
    }

    $r++;  

}	

$ArrayPacientes = array();
$numeral=0;
$numeralF=0;
$antiguo = 30;
$NPackets = 0;

if($withGroup == 0)
{
    $resultPAC = $con->prepare("SELECT DISTINCT IdUs FROM 
    
    
    
    doctorslinkusers WHERE IdMED = ?
    UNION
    SELECT DISTINCT IdPac FROM doctorslinkdoctors WHERE IdMED2 = ? ");
    $resultPAC->bindValue(1, $MedID, PDO::PARAM_INT);
    $resultPAC->bindValue(2, $MedID, PDO::PARAM_INT);
    $resultPAC->execute();
}
else
{
    /*
     *  Query Documentation
     *
     *  1st Line:       Gets all users that this doctor has access to from doctorslinkusers
     *  2nd - 4th Line: Gets all users that any doctor that this doctor is in a group with has access to and appends them to the result set (Using UNION)
     *  3rd Line:       Gets all doctors that this doctor is in a group with by joining the doctorsgroups table to itself with conditions on IdGroup and IdDoctor
     *
     */
/*    $query_str = '
                    SELECT RES.IdUs, LP.count FROM
                    (
                        (SELECT DISTINCT DLU.IdUs FROM doctorslinkusers DLU WHERE DLU.IdMED = ?)
                            UNION 
                            
                        (SELECT DISTINCT DLU.IdUs FROM doctorslinkusers DLU INNER JOIN 
                                (SELECT DISTINCT DG_1.IdDoctor FROM doctorsgroups DG_1 INNER JOIN doctorsgroups DG_2 ON DG_2.IdDoctor = ? AND DG_1.IdGroup = DG_2.IdGroup) G
                        
                        ON DLU.IdMED = G.IdDoctor)
                    ) AS RES
                    	
                    	LEFT JOIN
                    	
                    (SELECT IdUsu,COUNT(*) AS count FROM lifepin GROUP BY IdUsu) AS LP ON LP.IdUsu = RES.IdUs
                   
                   ';
    $resultPAC = $con->prepare($query_str);
    $resultPAC->bindValue(1, $MedID, PDO::PARAM_INT);
    $resultPAC->bindValue(2, $MedID, PDO::PARAM_INT);
    $resultPAC->execute();
}

//$countPAC=mysql_num_rows($resultPAC);
while ($rowP1 = $resultPAC->fetch(PDO::FETCH_ASSOC))
{
    $ArrayPacientes[$numeral]=$rowP1['IdUs'];
    $numeral++;
    $antig = time()-strtotime($rowP1['Fecha']);
    $days = floor($antig / (60*60*24));
    if ($days<$antiguo) $numeralF++;

    $idEncontrado = $rowP1['IdUs'];

    $countPIN = $rowP1['count'];
    $NPackets += $countPIN;

}

$NPacketsMIOS = $NPackets;
$MIOS = $numeral;

///////////////////////////////////////////////////////////////////////////////////////////////////////////
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$queUsu = $_POST['Doctor'];
$NReports = $_POST['NReports'];
$daysExplore=empty($_GET['days']) ? 30: $_GET['days'];


$cadena = '';

$Num_Own_Patients = 0;
$Num_Group_Patients = 0;
$Num_Own_Reports = 0;
$Num_Group_Reports = 0;
$Num_Connected = 0;
$IdMed = $queUsu;


$resultPRE = '';
if ($withGroup == 1)
{
    /*
     *  Query Documentation
     *
     *  1st Line:       Gets all users that this doctor has access to from doctorslinkusers
     *  2nd - 4th Line: Gets all users that any doctor that this doctor is in a group with has access to and appends them to the result set (Using UNION)
     *  3rd Line:       Gets all doctors that this doctor is in a group with by joining the doctorsgroups table to itself with conditions on IdGroup and IdDoctor
     *
     */
   /* $query_str = '
                    SELECT RES.IdUs, U.salt FROM
                    (

                        (SELECT DISTINCT DLU.IdUs FROM doctorslinkusers DLU WHERE DLU.IdMED = 2543 AND DLU.IdPin IS NULL AND DLU.Estado IN (2,null))
                              UNION (SELECT DISTINCT DLU.IdUs FROM doctorslinkusers DLU INNER JOIN 
                                    (SELECT DISTINCT DG_1.IdDoctor FROM doctorsgroups DG_1 INNER JOIN doctorsgroups DG_2 ON DG_2.IdDoctor = 2543 AND DG_1.IdGroup = DG_2.IdGroup) G
                                ON DLU.IdMED = G.IdDoctor AND DLU.IdPin IS NULL AND DLU.Estado IN (2,null))

                    ) AS RES

                        LEFT JOIN

                    usuarios U ON RES.IdUs = U.identif
                 ';
    $resultPRE = $con->prepare($query_str);
	$resultPRE->bindValue(1, $IdMed, PDO::PARAM_INT);
    $resultPRE->bindValue(2, $IdMed, PDO::PARAM_INT);
	$resultPRE->execute();
}
else
{
    $resultPRE = $con->prepare("SELECT DLU.IdUs, U.salt FROM 
                                (
                                    SELECT DISTINCT IdUs FROM doctorslinkusers WHERE IdPin IS NULL and Idmed=2543 and Estado IN (2,null)
                                ) AS DLU 
                                    LEFT JOIN 
                                usuarios U 
                                    ON 
                                        DLU.IdUs = U.identif");
	$resultPRE->bindValue(1, $IdMed, PDO::PARAM_INT);
	$resultPRE->execute();
}
$Num_Group_Patients = $resultPRE->rowCount();
if($Num_Group_Patients > 0)
{
    while ($rowS = $resultPRE->fetch(PDO::FETCH_ASSOC))
    {
        $User_Reports = 0;
        $IdUsu = $rowS['IdUs'];
        if ($rowS['salt'] >'')
        {
            $Num_Connected++;
        }
    }
}


$query = '';

$result1_ids = array(-1);
$result1 = $con->prepare("SELECT IdPac FROM doctorslinkdoctors WHERE IdMED2 = ?");
$result1->bindValue(1, $queUsu, PDO::PARAM_INT);
$result1->execute();
while($result1_row = $result1->fetch(PDO::FETCH_ASSOC))
{
    array_push($result1_ids, $result1_row['IdPac']);
}

if ($withGroup == 1)
{
    /*
     *  Query Documentation
     *
     *  1st Line:       Gets all users that this doctor created from usuarios
     *  2nd Line:       Gets all users that were referred to this doctor from doctorslinkdoctors and appends them to the result set (Using UNION)
     *  3rd - 5th Line: Gets all users that any doctor that this doctor is in a group create and appends them to the result set
     *  4th Line:       Gets all doctors that this doctor is in a group with by joining the doctorsgroups table to itself with conditions on IdGroup and IdDoctor
     *
     */
  /*  $query_str = '
                    (SELECT U.Identif FROM usuarios U WHERE U.IdCreator = ?)
                        UNION (SELECT U.Identif FROM usuarios U INNER JOIN doctorslinkdoctors DLD ON U.Identif = DLD.IdPac AND DLD.IDMED2 = ?)
                        UNION (SELECT U.Identif FROM usuarios U INNER JOIN 
                            (SELECT DISTINCT DG_1.IdDoctor FROM doctorsgroups DG_1 INNER JOIN doctorsgroups DG_2 ON DG_2.IdDoctor = ? AND DG_1.IdGroup = DG_2.IdGroup) G
                        ON U.IdCreator = G.IdDoctor)
                 ';
    $query = $con->prepare($query_str);
	$query->bindValue(1, $queUsu, PDO::PARAM_INT);
    $query->bindValue(2, $queUsu, PDO::PARAM_INT);
    $query->bindValue(3, $queUsu, PDO::PARAM_INT);
	$query->execute();
	
}
else
{*/

$queUsu = $_POST['Doctor'];
$NReports = $_POST['NReports'];
$daysExplore=empty($_GET['days']) ? 30: $_GET['days'];
$withGroup = 0;
$cadena = '';
if (isset($_POST['Group']))
{
    $withGroup = $_POST['Group'];
}

$query_str = 'SELECT U.Identif, U.IdUsRESERV, U.salt FROM usuarios U

                INNER JOIN 
            (
                SELECT Identif FROM usuarios WHERE idcreator = ?

                   UNION

                SELECT IdUs as Identif FROM doctorslinkusers WHERE IdMED = ?

                   UNION

                SELECT IdPac as Identif FROM doctorslinkdoctors WHERE IdMED2 = ?';

if($withGroup == 1) {

    $query_str .= '
                UNION
                
                (
                    SELECT USU.Identif FROM usuarios USU 
                    INNER JOIN (    
                        SELECT DISTINCT A.idDoctor FROM doctorsgroups A 
                        INNER JOIN (
                            SELECT idGroup FROM doctorsgroups WHERE idDoctor=?
                        ) 
                        B WHERE B.idGroup = A.idGroup
                    ) 
                    DG WHERE DG.idDoctor = USU.IdCreator
                )';
}    

$query_str .= ') idus

        ON U.Identif = idus.Identif';

$query = $con->prepare($query_str);
$query->bindValue(1, $queUsu, PDO::PARAM_INT);
$query->bindValue(2, $queUsu, PDO::PARAM_INT);
$query->bindValue(3, $queUsu, PDO::PARAM_INT);
if($withGroup == 1) $query->bindValue(4, $queUsu, PDO::PARAM_INT);

$result = $query->execute();
$count = $query->rowCount();
$Num_Own_Patients = 0;
$Num_connected_Patients = 0;

if($count > 0)
{
    $Num_Own_Patients = $count;
}

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    if($row['IdUsRESERV'] != '' && $row['salt'] != '') $Num_connected_Patients++;
}


$cadena = array("NPatients" => $Num_Own_Patients, "NActive" => $Num_connected_Patients);

$encode = json_encode($cadena);
echo $encode; 


?>
