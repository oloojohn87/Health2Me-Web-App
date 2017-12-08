<?php
$file = file_get_contents('medPDF.pdf');
$encoded = base64_encode($file);
echo $encoded;

//$decode = base64_decode($encoded);
//$put = file_put_contents('decoded_file.pdf', $decode);
//echo $put;
?>