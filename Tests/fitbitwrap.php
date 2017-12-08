<?php

require 'fitbitphp.php';

$fitbit = new FitBitPHP(c3ea24f2e71f471497525b402a886c8c, bff22d7becc84efa8f6c08139c57b872);


$fitbit->setUser('jvinals');
$json = $fitbit->getProfile();

print_r($json);

?>