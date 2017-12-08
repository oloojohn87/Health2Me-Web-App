<?php
 require("environment_detail.php");

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 $hardcode = $env_var_db['hardcode'];

$htmlContent = $_GET['htmlContent'];

$content = file_get_contents($hardcode."templates/pdfTemplate.html");
$content = str_replace("**Var**",$htmlContent,$content);
$str = 'data.html';
$fp = fopen($str, 'w');
fwrite($fp, $content);

shell_exec('C:\xampp\wkhtmltopdf\bin\wkhtmltopdf '.$str.' demoData.pdf');
?>
