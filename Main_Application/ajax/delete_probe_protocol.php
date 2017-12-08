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

$id = $_POST['id'];

$prot = $con->prepare("SELECT * FROM probe_protocols WHERE protocolID = ?");
$prot->bindValue(1, $id, PDO::PARAM_INT);
$prot->execute();
$protocol = $prot->fetch(PDO::FETCH_ASSOC);
for($i = 1; $i <= 5; $i++)
{
    if($protocol['question'.$i] != NULL)
    {
        $del = $con->prepare("DELETE FROM probe_units WHERE probe_question = ?");
        $del->bindValue(1, $protocol['question'.$i], PDO::PARAM_INT);
        $del->execute();

        $del2 = $con->prepare("DELETE FROM probe_questions WHERE id = ?");
        $del2->bindValue(1, $protocol['question'.$i], PDO::PARAM_INT);
        $del2->execute();
    }
}

$del = $con->prepare("DELETE FROM probe_protocols WHERE protocolID = ?");
$del->bindValue(1, $id, PDO::PARAM_INT);
$del->execute();

?>