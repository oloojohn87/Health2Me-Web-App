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

$queUsu = $_POST['EnviaUserid'];
$queModo = $_POST['EnviaModo']; //1: Actualizar,  2: CREAR
$queTipoUsuario = $_POST['EnviaTipoUsuario']; //1: MEDICO,  2: PACIENTE

$queDoB = $_POST['dp32'];
$queGender = $_POST['gender'];
$queOrden = $_POST['orderOB'];
$queIdUsFIXED = $_POST['VIdUsFIXED'];

$queUserName = $_POST['Vname'];
$queUserSurname = $_POST['Vsurname'];
$queUserPassword = $_POST['Vpassword'];
$queIdUsFIXEDNAME = $_POST['VIdUsFIXEDNAME'];

$queUserEmail = $_POST['Vemail'];
$queUserTelefono = $_POST['Vphone'];


echo "MODO: ";
echo $queModo;
echo "   --- Usuario: ";
echo $queUsu;

if ($queModo == '1')
{
	$q = $con->prepare("UPDATE usuarios SET Fnac = ?, Sexo = ?, Orden = ?, IdUsFIXED = ?, Name = ?, Surname = ?, IdUsRESERV = ?, Password = ?, IdUsFIXEDNAME = ?, email = ?, telefono = ?  WHERE Identif = ?");
    $q->bindValue(1, $queDoB, PDO::PARAM_STR);
    $q->bindValue(2, $queGender, PDO::PARAM_INT);
    $q->bindValue(3, $queOrden, PDO::PARAM_INT);
    $q->bindValue(4, $queIdUsFIXED, PDO::PARAM_INT);
    $q->bindValue(5, $queUserName, PDO::PARAM_STR);
    $q->bindValue(6, $queUserSurname, PDO::PARAM_STR);
    $q->bindValue(7, $queUserPassword, PDO::PARAM_STR);
    $q->bindValue(8, $queUserPassword, PDO::PARAM_STR);
    $q->bindValue(9, $queIdUsFIXEDNAME, PDO::PARAM_STR);
    $q->bindValue(10, $queUserEmail, PDO::PARAM_STR);
    $q->bindValue(11, $queUserTelefono, PDO::PARAM_STR);
    $q->bindValue(12, $queUsu, PDO::PARAM_INT);
    $q->execute();
}
else
{
	$q = $con->prepare("INSERT INTO usuarios SET Fnac = ?, Sexo = ?, Orden = ?, IdUsFIXED = ?, Name = ?, Surname = ?, IdUsRESERV = ?, Password = ?, IdUsFIXEDNAME = ?, email = ?, telefono = ?");
    $q->bindValue(1, $queDoB, PDO::PARAM_STR);
    $q->bindValue(2, $queGender, PDO::PARAM_INT);
    $q->bindValue(3, $queOrden, PDO::PARAM_INT);
    $q->bindValue(4, $queIdUsFIXED, PDO::PARAM_INT);
    $q->bindValue(5, $queUserName, PDO::PARAM_STR);
    $q->bindValue(6, $queUserSurname, PDO::PARAM_STR);
    $q->bindValue(7, $queUserPassword, PDO::PARAM_STR);
    $q->bindValue(8, $queUserPassword, PDO::PARAM_STR);
    $q->bindValue(9, $queIdUsFIXEDNAME, PDO::PARAM_STR);
    $q->bindValue(10, $queUserEmail, PDO::PARAM_STR);
    $q->bindValue(11, $queUserTelefono, PDO::PARAM_STR);
    $q->execute();
}

$salto="location:UserAccount.php?Acceso=23432&Nombre=".$queIdUsFIXEDNAME."&Password=".$queUserPassword."&MEDID=".$queUserEmail;
header($salto);

?>