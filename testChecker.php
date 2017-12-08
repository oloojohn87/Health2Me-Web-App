<?php
require('getFullUsersMEDCLASS.php');

$doc_id = 2641;
$mem_id = 2158;

$checker = new checkPatientsClass();

$checker->setters($mem_id, $doc_id);
$checker->checker();
$checker->display();
?>