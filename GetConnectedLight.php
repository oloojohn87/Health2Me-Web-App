<?php
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

$queUsu = $_POST['Doctor'];
$NReports = $_POST['NReports'];
$daysExplore=empty($_GET['days']) ? 30: $_GET['days'];
$withGroup = 0;
if (isset($_POST['Group']))
{
    $withGroup = $_POST['Group'];
}

$dg_ids = array(-1);
if($withGroup != 0)
{
    $dg = $con->prepare("SELECT idGroup FROM doctorsgroups WHERE idDoctor= ?");
	$dg->bindValue(1, $queUsu, PDO::PARAM_INT);
	$dg->execute();
    while($dg_row = $dg->fetch(PDO::FETCH_ASSOC))
    {
        array_push($dg_ids, $dg_row['idGroup']);
    }
}

//Get Information about Referrals
$result = '';
if ($withGroup == 0)
{
    $result = $con->prepare("SELECT id FROM doctorslinkdoctors WHERE IdMED = ? ");
	$result->bindValue(1, $queUsu, PDO::PARAM_INT);
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
                    (SELECT DLD.id FROM doctorslinkdoctors DLD WHERE DLD.IdMED = ?)
                        UNION (SELECT DLD.id FROM doctorslinkdoctors DLD INNER JOIN 
                            (SELECT DISTINCT DG_1.IdDoctor FROM doctorsgroups DG_1 INNER JOIN doctorsgroups DG_2 ON DG_2.IdDoctor = ? AND DG_1.IdGroup = DG_2.IdGroup) G
                        ON DLD.IdMED = G.IdDoctor)
                 ';
    
    $result = $con->prepare($query_str);
	$result->bindValue(1, $queUsu, PDO::PARAM_INT);
	$result->bindValue(2, $queUsu, PDO::PARAM_INT);
	$result->execute();
}
$Number_Patients_IReferred = $result->rowCount();

if ($withGroup == 0)
{
    $result = $con->prepare("SELECT id FROM doctorslinkdoctors WHERE IdMED2 = ? ");
	$result->bindValue(1, $queUsu, PDO::PARAM_INT);
	$result->execute();
}
else
{
    /*
     *  Query Documentation
     *
     *  1st Line:       Gets all referrals that were referred to this doctor from doctorslinkdoctors
     *  2nd - 4th Line: Gets all referrals that were referred to any doctor that this doctor is in a group with and appends them to the result set (Using UNION)
     *  3rd Line:       Gets all doctors that this doctor is in a group with by joining the doctorsgroups table to itself with conditions on IdGroup and IdDoctor
     *
     */
    $query_str = '
                    (SELECT DLD.id FROM doctorslinkdoctors DLD WHERE DLD.IdMED2 = ?)
                        UNION (SELECT DLD.id FROM doctorslinkdoctors DLD INNER JOIN 
                            (SELECT DISTINCT DG_1.IdDoctor FROM doctorsgroups DG_1 INNER JOIN doctorsgroups DG_2 ON DG_2.IdDoctor = ? AND DG_1.IdGroup = DG_2.IdGroup) G
                        ON DLD.IdMED2 = G.IdDoctor)
                 ';
    
    $result = $con->prepare($query_str);
	$result->bindValue(1, $queUsu, PDO::PARAM_INT);
	$result->bindValue(2, $queUsu, PDO::PARAM_INT);
	$result->execute();
}
$Number_Patients_Referred2Me = $result->rowCount();

if ($withGroup == 0)
{
    $result = $con->prepare("SELECT distinct(IdMED2) FROM doctorslinkdoctors WHERE IdMED = ? ");
	$result->bindValue(1, $queUsu, PDO::PARAM_INT);
	$result->execute();
}
else
{
    /*
     *  Query Documentation
     *
     *  1st Line:       Gets all doctors that received a referrals from this doctor from doctorslinkdoctors
     *  2nd - 4th Line: Gets all doctors that received a referral from any doctor that this doctor is in a group with and appends them to the result set (Using UNION)
     *  3rd Line:       Gets all doctors that this doctor is in a group with by joining the doctorsgroups table to itself with conditions on IdGroup and IdDoctor
     *
     */
    $query_str = '
                    (SELECT DISTINCT DLD.IdMED2 FROM doctorslinkdoctors DLD WHERE DLD.IdMED = ?)
                        UNION (SELECT DISTINCT DLD.IdMED2 FROM doctorslinkdoctors DLD INNER JOIN 
                            (SELECT DISTINCT DG_1.IdDoctor FROM doctorsgroups DG_1 INNER JOIN doctorsgroups DG_2 ON DG_2.IdDoctor = ? AND DG_1.IdGroup = DG_2.IdGroup) G
                        ON DLD.IdMED = G.IdDoctor)
                 ';
    
    $result = $con->prepare($query_str);
	$result->bindValue(1, $queUsu, PDO::PARAM_INT);
	$result->bindValue(2, $queUsu, PDO::PARAM_INT);
	$result->execute();
    /*
    $result = $con->prepare("SELECT distinct(IdMED2) FROM doctorslinkdoctors WHERE IdMED = ? OR IdMED IN (".implode(',', $dg_ids).")");
	$result->bindValue(1, $queUsu, PDO::PARAM_INT);
	$result->bindValue(2, $queUsu, PDO::PARAM_INT);
	$result->execute();*/
}
$Number_Doctors_IReferred = $result->rowCount();

if ($withGroup == 0)
{
    $result = $con->prepare("SELECT distinct(IdMED) FROM doctorslinkdoctors WHERE IdMED2 = ? ");
	$result->bindValue(1, $queUsu, PDO::PARAM_INT);
	$result->execute();
}
else
{
    /*
     *  Query Documentation
     *
     *  1st Line:       Gets all doctors that referred a patient to this doctor from doctorslinkdoctors
     *  2nd - 4th Line: Gets all doctors that referred a patient to any doctor that this doctor is in a group with and appends them to the result set (Using UNION)
     *  3rd Line:       Gets all doctors that this doctor is in a group with by joining the doctorsgroups table to itself with conditions on IdGroup and IdDoctor
     *
     */
    $query_str = '
                    (SELECT DISTINCT DLD.IdMED FROM doctorslinkdoctors DLD WHERE DLD.IdMED2 = ?)
                        UNION (SELECT DISTINCT DLD.IdMED FROM doctorslinkdoctors DLD INNER JOIN 
                            (SELECT DISTINCT DG_1.IdDoctor FROM doctorsgroups DG_1 INNER JOIN doctorsgroups DG_2 ON DG_2.IdDoctor = ? AND DG_1.IdGroup = DG_2.IdGroup) G
                        ON DLD.IdMED2 = G.IdDoctor)
                 ';
    
    $result = $con->prepare($query_str);
	$result->bindValue(1, $queUsu, PDO::PARAM_INT);
	$result->bindValue(2, $queUsu, PDO::PARAM_INT);
	$result->execute();
    /*
    $result = $con->prepare("SELECT distinct(IdMED) FROM doctorslinkdoctors WHERE IdMED2 = ? OR IdMED2 IN (".implode(',', $dg_ids).")");
	$result->bindValue(1, $queUsu, PDO::PARAM_INT);
	$result->bindValue(2, $queUsu, PDO::PARAM_INT);
	$result->execute();*/
}
$Number_Doctors_Referred2Me = $result->rowCount();

$cadena = array("IN" => $Number_Patients_Referred2Me, "OUT" => $Number_Patients_IReferred,
               "DRIN" => $Number_Doctors_Referred2Me, "DROUT" => $Number_Doctors_IReferred);  

$encode = json_encode($cadena);
echo $encode; 


?>