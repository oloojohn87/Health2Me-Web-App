<?php

require('fpdf.php');

$pdf = new FPDF();

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Helvetica', '', 24);
$pdf->Cell(40, 10, 'Hello World !', 1);
$output = $pdf->Output();

file_put_contents("test.pdf", $output);

?>