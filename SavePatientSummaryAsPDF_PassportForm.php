<?php
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 $hardcode = $env_var_db['hardcode'];

session_start();
ini_set('max_execution_time', 600);
require_once('tcpdf/tcpdf.php');

$htmlContent = $_POST['htmlContent'];
echo "HTML Content in SavePatientSummaryAsPDF   ".$htmlContent;

$content = file_get_contents($hardcode."templates/pdfTemplate_1.html");
$content = str_replace("**Var**",$htmlContent,$content);
$str = 'data.html';
$fp = fopen($str, 'w');
fwrite($fp, $content);
fclose($fp);

//shell_exec('C:\xampp\wkhtmltopdf\bin\wkhtmltopdf '.$str.' demoData.pdf'); */
?>
