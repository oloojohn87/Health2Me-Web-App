<?php
session_start();
require_once('tcpdf/tcpdf.php');
//save.php code
 
//Show the image
/*echo '<img src="'.$_POST['img_val'].'" />';

//Get the base-64 string"" from data
$filteredData=substr($_POST['img_val'], strpos($_POST['img_val'], ",")+1);
 
//Decode the string
$unencodedData=base64_decode($filteredData);
 
//Save the image
file_put_contents('TestImage.png', $unencodedData);*/

$data = explode("'",$_POST['data']);
$text = $data[1];

$content = file_get_contents("templates/pdfTemplate.html");
$content = str_replace("**Var**",$text,$content);

$fp = fopen('data.html', 'w');
fwrite($fp, $content);

?>
