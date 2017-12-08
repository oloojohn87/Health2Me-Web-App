<?php

session_start();

// Include the main TCPDF library 
require_once('tcpdf/tcpdf.php');
require("environment_detail.php");
error_reporting(E_ERROR | E_PARSE);
set_time_limit(60);

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

 
$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 048');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', 'B', 20);

// add a page
$pdf->AddPage();

$pdf->Write(0, 'Immuno Therapy Schedule', '', 0, 'L', true, 0, false, false, 0);

$pdf->SetFont('helvetica', '', 10 );


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *

IMPORTANT:
If you are printing user-generated content, tcpdf tag can be unsafe.
You can disable this tag by setting to false the K_TCPDF_CALLS_IN_HTML
constant on TCPDF configuration file.

For security reasons, the parameters for the 'params' attribute of TCPDF
tag must be prepared as an array and encoded with the
serializeTCPDFtagParameters() method (see the example below).

 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
$text="input1234";


$html = '

                           							
                            <div style="width: 98%; height: 640px; margin-left: 2%">
                            <table cellspacing="0" cellpadding"0" border="1">
<tr bgcolor="#C0C0C0"> 
<th colspan="8"> <p><center>TAKE HOME **RED** Vial 1 Immunotherapy Schedule </p></center></th>
</tr>
<tr>
<th colspan="8">Name: $text $text 
		DOB: <input type="date" id="Imm_thrpy_red_dob" value="" style="border:hidden;">
		Date Started:<input type="date" id="Imm_thrpy_red_strt_date" value="" style="border:hidden;">
</th>
</tr>

<tr bgcolor="#C0C0C0">
<th colspan="8"><center> Vial 1 Dilution 1:1 Red </center> </th>
</tr>

<tr>
  <th style="width: 200;" colspan="2">Record Date and location of injection</th>
  <th colspan="2"> 1 Dos     e per week </th>
  <th colspan="1">Patients Initial</th>
  <th colspan="1">Date</th>
  <th colspan="2">Reaction</th>
</tr>
<tr>
  <th colspan="2" align="left">1)<input type="text" id="Imm_thrpy_red_record1" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.05 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_redinitial1" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_1" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_1" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">2)<input type="text" id="Imm_thrpy_red_record2" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.10 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_redinitial2" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_2" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_2" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">3)<input type="text" id="Imm_thrpy_red_record3" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.15 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_redinitial3" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_3" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_3" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">4)<input type="text" id="Imm_thrpy_red_record4" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.20 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_redinitial4" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_4" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_4" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">5)<input type="text" id="Imm_thrpy_red_record5" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.25 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_redinitial5" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_5" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_5" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">6)<input type="text" id="Imm_thrpy_red_record6" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.30 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_redinitial6" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_6" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_6" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">7)<input type="text" id="Imm_thrpy_red_record7" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.35 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_red_initial7" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_7" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_7" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">8)<input type="text" id="Imm_thrpy_red_record8" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.40 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_redinitial8" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_8" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_8" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">9)<input type="text" id="Imm_thrpy_red_record9" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.45 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_redinitial9" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_9" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_9" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">10)<input type="text" id="Imm_thrpy_red_record10" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.50 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_red_initial10" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_red_date_10" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_red_reaction_10" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8" align="left"> Patient Signature/Date Completed: <input type="text" id="Imm_thrpy_P_redsign" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8" align="left"> Staff Signature/Date Reviewed: <input type="text" id="Imm_thrpy_S_redsign" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8"> Remember to bring your EPI-PEN, BENADRYL AND SHARPS CONTAINER TO YOUR NEXT APPOINTMENT</br>ON<input type="date" id="Imm_thrpy_on_date_red" value="" style="border:hidden;"> @<input type="time" id="Imm_thrpy_at_time_red" value="" style="border:hidden;"> You will receive your next vial at this time. </th>
</tr>

</table>
<p>abc</P>
                            
 </div>';

    
    
    
    
$pdf->writeHTML($html, true, false, false, false, '');
$pdf->lastPage();


//$pdf->AddPage();

$html='

<div style="width: 98%; height: 640px; margin-left: 2%; float: left;">
                                        
                                <table cellspacing="0" cellpadding"0" border="1">
<tr bgcolor="#C0C0C0"> 
<th colspan="8"> TAKE HOME **YELLOW** Vial 2 Immunotherapy Schedule </th>
</tr>
<tr>
<th colspan="8">Name: <input type="text" id="Imm_thrpy_yel_name" value="" style="border:hidden;"> 
		DOB: <input type="date" id="Imm_thrpy_yel_dob" value="" style="border:hidden;">
		Date Started:<input type="date" id="Imm_thrpy_yel_strt_date" value="" style="border:hidden;">
</th>
</tr>

<tr bgcolor="#C0C0C0">
<th colspan="8"><center> Vial 2 Dilution 1:10 Yellow </center> </th>
</tr>

<tr>
  <th style="width: 200;" colspan="2";>Record Date and location of injection</th>
  <th colspan="2"> 1 Dose per week </th>
  <th colspan="1">Patients Initial</th>
  <th colspan="1">Date</th>
  <th colspan="2">Reaction</th>
</tr>
<tr>
  <th colspan="2" align="left">1)<input type="text" id="Imm_thrpy_yel_record1" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.05 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_yelinitial1" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_yel_date_1" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_yel_reaction_1" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">2)<input type="text" id="Imm_thrpy_yel_record2" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.10 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_yelinitial2" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_yel_date_2" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_yel_reaction_2" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">3)<input type="text" id="Imm_thrpy_yel_record3" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.15 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_yelinitial3" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_yel_date_3" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_yel_reaction_3" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">4)<input type="text" id="Imm_thrpy_yel_record4" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.20 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_yelinitial4" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_yel_date_4" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_yel_reaction_4" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">5)<input type="text" id="Imm_thrpy_yel_record5" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.25 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_yelinitial5" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_yel_date_5" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_yel_reaction_5" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">6)<input type="text" id="Imm_thrpy_yel_record6" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.30 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_yelinitial6" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_yel_date_6" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_yel_reaction_6" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">7)<input type="text" id="Imm_thrpy_yel_record7" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.35 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_yelinitial7" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_yel_date_7" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_yel_reaction_7" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">8)<input type="text" id="Imm_thrpy_yel_record8" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.40 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_yelinitial8" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_yel_date_8" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_yel_reaction_8" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">9)<input type="text" id="Imm_thrpy_yel_record9" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.45 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_yelinitial9" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_yel_date_9" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_yel_reaction_9" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">10)<input type="text" id="Imm_thrpy_yel_record10" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.50 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_yelinitial10" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_yel_date_10" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_yel_reaction_10" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8" align="left"> Patient Signature/Date Completed: <input type="text" id="Imm_thrpy_P_yelsign" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8" align="left"> Staff Signature/Date Reviewed: <input type="text" id="Imm_thrpy_S_yelsign" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8"> Remember to bring your EPI-PEN BANADRYL AND CURRENT VIAL TO YOUR NEXT APPOINTMENT</br>ON<input type="date" id="Imm_thrpy_on_date_yel" value="" style="border:hidden;"> @<input type="time" id="Imm_thrpy_at_time_yel" value="" style="border:hidden;"> YOU WILL RECEIVE NEXT VIAL AT THIS TIME. </th>
</tr>

</table>
         <p></p>                        
 
</div>
';

 $pdf->writeHTML($html, true, false, false, false, '');
$pdf->lastPage();

$html='

<div style="width: 98%; height: 500px; margin-left: 2%; float: left;">
                                        
                                <table cellspacing="0" cellpadding"0" border="1">
<tr bgcolor="#C0C0C0"> 
<th colspan="8"> TAKE HOME Vial 3 **BLUE** Immunotherapy Schedule </th>
</tr>
<tr>
<th colspan="8">Name: <input type="text" id="Imm_thrpy_blue_name" value="" style="border:hidden;"> 
		DOB: <input type="date" id="Imm_thrpy_blue_dob" value="" style="border:hidden;">
		Date Started:<input type="date" id="Imm_thrpy_blue_strt_date" value="" style="border:hidden;">
</th>
</tr>

<tr bgcolor="#C0C0C0">
<th colspan="8"><center> Vial 3 Dilution 1:100 Blue </center> </th>
</tr>

<tr>
  <th style="width: 200;" colspan="2">Record Date and location of injection</th>
  <th colspan="2"> 1 Dose per week </th>
  <th colspan="1">Patients Initial</th>
  <th colspan="1">Date</th>
  <th colspan="2">Reaction</th>
</tr>
<tr>
  <th colspan="2" align="left">1)<input type="text" id="Imm_thrpy_blue_record1" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.05 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_blueinitial1" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_blue_date_1" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_blue_reaction_1" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">2)<input type="text" id="Imm_thrpy_blue_record2" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.10 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_blueinitial2" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_blue_date_2" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_blue_reaction_2" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">3)<input type="text" id="Imm_thrpy_blue_record3" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.15 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_blueinitial3" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_blue_date_3" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_blue_reaction_3" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">4)<input type="text" id="Imm_thrpy_blue_record4" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.20 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_blueinitial4" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_blue_date_4" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_blue_reaction_4" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">5)<input type="text" id="Imm_thrpy_blue_record5" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.25 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_blueinitial5" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_blue_date_5" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_blue_reaction_5" value="" style="border:hidden;"></th>
</tr>


<tr> 
<th colspan="8" align="left"> Patient Signature/Date Completed: <input type="text" id="Imm_thrpy_P_bluesign" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8" align="left"> Staff Signature/Date Reviewed: <input type="text" id="Imm_thrpy_S_bluesign" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8"> Remember to bring your EPI-PEN, BANADRYL AND CURRENT VIAL TO YOUR NEXT APPOINTMENT</br>ON<input type="date" id="Imm_thrpy_on_date_blue" value="" style="border:hidden;"> @<input type="time" id="Imm_thrpy_at_time_blue" value="" style="border:hidden;"> YOU WILL RECEIVE NEXT VIAL AT THIS TIME. </th>
</tr>

</table>
                            
                                
                         </div>';

$pdf->writeHTML($html, true, false, false, false, '');
$pdf->lastPage();

$html='
               <div style="width: 98%; height: 500px; margin-left: 2%; float: left;">
                                        
<table cellspacing="0" cellpadding"0" border="1">
<tr bgcolor="#C0C0C0"> 
<th colspan="8"> TAKE HOME Vial 4 **GREEN** Immunotherapy Schedule </th>
</tr>
<tr>
<th colspan="8">Name: <input type="text" id="Imm_thrpy_gr_name" value="" style="border:hidden;"> 
		DOB: <input type="date" id="Imm_thrpy_gr_dob" value="" style="border:hidden;">
		Date Started:<input type="date" id="Imm_thrpy_gr_strt_date" value="" style="border:hidden;">
</th>
</tr>

<tr bgcolor="#C0C0C0">
<th colspan="8"><center> Vial 4 Dilution 1:1000 GREEN </center> </th>
</tr>

<tr>
  <th style="width: 200;" colspan="2">Record Date and location of injection</th>
  <th colspan="2"> 1 Dose per week </th>
  <th colspan="1">Patients Initial</th>
  <th colspan="1">Date</th>
  <th colspan="2">Reaction</th>
</tr>
<tr>
  <th colspan="2" align="left">1)<input type="text" id="Imm_thrpy_gr_record1" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.05 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_grinitial1" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_gr_date_1" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_gr_reaction_1" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">2)<input type="text" id="Imm_thrpy_gr_record2" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.10 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_grinitial2" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_gr_date_2" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_gr_reaction_2" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">3)<input type="text" id="Imm_thrpy_gr_record3" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.15 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_grinitial3" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_gr_date_3" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_gr_reaction_3" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">4)<input type="text" id="Imm_thrpy_gr_record4" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.20 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_grinitial4" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_gr_date_4" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_gr_reaction_4" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">5)<input type="text" id="Imm_thrpy_gr_record5" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.25 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_grinitial5" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_gr_date_5" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_gr_reaction_5" value="" style="border:hidden;"></th>
</tr>


<tr> 
<th colspan="8" align="left"> Patient Signature/Date Completed: <input type="text" id="Imm_thrpy_P_grsign" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8" align="left"> Staff Signature/Date Reviewed: <input type="text" id="Imm_thrpy_S_grsign" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8"> Remember to bring your EPI-PEN, BANADRYL AND CURRENT VIAL TO YOUR NEXT APPOINTMENT</br>ON<input type="date" id="Imm_thrpy_on_date_gr" value="" style="border:hidden;"> @<input type="time" id="Imm_thrpy_at_time_gr" value="" style="border:hidden;"> YOU WILL RECEIVE NEXT VIAL AT THIS TIME. </th>
</tr>

</table>            
                                
                         </div>';

$pdf->writeHTML($html, true, false, false, false, '');
$pdf->lastPage();

$html='

<div style="width: 98%; height: 500px; margin-left: 2%; float: left;">
<table cellspacing="0" cellpadding"0" border="1">
<tr bgcolor="#C0C0C0"> 
<th colspan="8"> TRAINING PHASE/Vial 5 *SILVER* Immunotherapy Schedule </th>
</tr>
<tr>
<th colspan="8">Name: <input type="text" id="Imm_thrpy_sil_name" value="" style="border:hidden;"> 
		DOB: <input type="date" id="Imm_thrpy_sil_dob" value="" style="border:hidden;">
		Date Started:<input type="date" id="Imm_thrpy_sil_strt_date" value="" style="border:hidden;">
</th>
</tr>

<tr bgcolor="#C0C0C0">
<th colspan="8"><center> Vial 5 Dilution 1:10000 Silver </center> </th>
</tr>

<tr>
  <th style="width: 200;" colspan="2">Record Date and location of injection</th>
  <th colspan="2"> 1 Dose per week </th>
  <th colspan="1">Patients Initial</th>
  <th colspan="1">Date</th>
  <th colspan="2">Reaction</th>
</tr>
<tr>
  <th colspan="2" align="left">1)<input type="text" id="Imm_thrpy_sil_record1" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.05 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_silinitial1" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_sil_date_1" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_sil_reaction_1" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">2)<input type="text" id="Imm_thrpy_sil_record2" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.10 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_silinitial2" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_sil_date_2" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_sil_reaction_2" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">3)<input type="text" id="Imm_thrpy_sil_record3" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.15 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_silinitial3" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_sil_date_3" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_sil_reaction_3" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">4)<input type="text" id="Imm_thrpy_sil_record4" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.20 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_silinitial4" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_sil_date_4" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_sil_reaction_4" value="" style="border:hidden;"></th>
</tr>
<tr>
  <th colspan="2" align="left">5)<input type="text" id="Imm_thrpy_sil_record5" value="" style="border:hidden;"></th>
  <th colspan="2"> 0.25 </th>
  <th colspan="1"><input type="text" id="Imm_thrpy_P_silinitial5" value="" style="border:hidden;"></th>
  <th colspan="1"><input type="date" id="Imm_thrpy_sil_date_5" value="" style="border:hidden;"></th>
  <th colspan="2"><input type="text" id="Imm_thrpy_sil_reaction_5" value="" style="border:hidden;"></th>
</tr>


<tr> 
<th colspan="8" align="left"> Patient Signature/Date Completed: <input type="text" id="Imm_thrpy_P_silsign" value="" style="border:hidden;"></th>
</tr>

<tr> 
<th colspan="8" align="left"> Staff Signature/Date Reviewed: <input type="text" id="Imm_thrpy_S_silsign" value="" style="border:hidden;"></th>
</tr>

</table>
<p><b>*Dispense GREEN vial @4th visit. PATIENT WILL BEGIN GREEN VIAL AFTER 5TH INJECTION FROM SILVER*</b></P>             
                                    
                            </div>';

$pdf->writeHTML($html, true, false, false, false, '');
$pdf->lastPage();

$pdf->Output('Immuno_Therapy_schedule.pdf', 'I');