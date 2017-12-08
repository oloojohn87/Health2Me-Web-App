<?php

session_start();

$file='/var/log/apache2/error.log';
$lastpos = 0;
while (true) {
    usleep(300000); //0.3 s
    clearstatcache(false, $file);
    $len = filesize($file);
    if ($len < $lastpos) {
        //file deleted or reset
        $lastpos = $len;
    }
    elseif ($len > $lastpos) {
        $f = fopen($file, "rb");
        if ($f === false)
            die();
        fseek($f, $lastpos);
        while (!feof($f)) {
            $buffer = fread($f, 4096);
            echo $buffer;
            flush();
        }
        $lastpos = ftell($f);
        fclose($f);
    }
}

?>