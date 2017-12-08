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

$doctor1 = $_GET['doctor'];

$doctor1groups_ids = array(-1);
$doctor1groups = $con->prepare("SELECT DISTINCT idGroup FROM doctorsgroups WHERE idDoctor = ?");
$doctor1groups->bindValue(1, $doctor1, PDO::PARAM_INT);
$doctor1groups->execute();
while($doctor1groups_row = $doctor1groups->fetch(PDO::FETCH_ASSOC))
{
    array_push($doctor1groups_ids, $doctor1groups_row['idGroup']);
}

$doctor1doctors_ids = array(-1);
$doctor1doctors = $con->prepare("SELECT DISTINCT idDoctor FROM doctorsgroups WHERE idGroup IN (".implode(",", $doctor1groups_ids).")");
$doctor1doctors->execute();
while($doctor1doctors_row = $doctor1doctors->fetch(PDO::FETCH_ASSOC))
{
    array_push($doctor1doctors_ids, $doctor1doctors_row['idDoctor']);
}

$dlu_ids = array(-1);
$dlu = $con->prepare("SELECT DISTINCT IdUs FROM doctorslinkusers WHERE IdMED = ?");
$dlu->bindValue(1, $doctor1, PDO::PARAM_INT);
$dlu->execute();
while($dlu_row = $dlu->fetch(PDO::FETCH_ASSOC))
{
    array_push($dlu_ids, $dlu_row['IdUs']);
}

$dld_ids = array(-1);
$dld = $con->prepare("SELECT DISTINCT IdPac FROM doctorslinkdoctors WHERE IdMED2 = ?");
$dld->bindValue(1, $doctor1, PDO::PARAM_INT);
$dld->execute();
while($dld_row = $dld->fetch(PDO::FETCH_ASSOC))
{
    array_push($dld_ids, $dld_row['IdPac']);
}


$main_query = $con->prepare("SELECT DISTINCT Identif,Name,Surname,email FROM usuarios U 
                                                WHERE 
                                        (   
                                            U.IdCreator = ? 
                                                    OR 
                                            U.IdCreator IN 
                                                            (
                                                                ".implode(",", $doctor1doctors_ids)."
                                                            )
                                                    OR 
                                            U.Identif IN
                                                            (
                                                                ".implode(",", $dlu_ids)."
                                                            )
                                                    OR 
                                            U.Identif IN
                                                            (
                                                                ".implode(",", $dld_ids)."
                                                            )
                                        ) 
                                        AND (U.salt IS NOT NULL AND U.IdUsRESERV IS NOT NULL)");


$main_query->bindValue(1, $doctor1, PDO::PARAM_INT);
$main_query->execute();

$result = array();
while($row = $main_query->fetch(PDO::FETCH_ASSOC))
{
    array_push($result, $row);
}

echo json_encode($result);

?>