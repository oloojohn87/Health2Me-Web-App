<?php
define('INCLUDE_CHECK',1);
require "logger.php";

$IDPIN = $_GET['IDPIN'];
$Content = $_GET['Content'];
$VIEWIdUser = $_GET['VIEWIdUser'];
$VIEWIdMed = $_GET['VIEWIdMed'];
if(isset($_GET['VIEWIP'])){
$VIEWIP = $_GET['VIEWIP'];
}
$MEDIO = $_GET['MEDIO'];

$ip = $_SERVER['REMOTE_ADDR'];


$retorno = LogBLOCKAMP ($IDPIN, $Content, $VIEWIdUser, $VIEWIdMed, $ip, $MEDIO);

print_r($retorno);

?>