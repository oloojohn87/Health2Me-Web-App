<?php
include "phpqrcode/qrlib.php";  

// base class with member properties and methods
/*****************************************************************/
/*****************************************************************/

class H2M_Infographics {
	/*Desc: class to handle infographics*/
	
	function create () {
		echo $hello;
	}
   
}// end of class H2M_Infographics

/*****************************************************************/
/*****************************************************************/

class H2M_Qrcode extends H2M_Infographics {
	/*Desc: class to handle qrcodes*/
	

	function create($basefilename, $qrdata){
	/****************************************************************/
	/*IN: (string, string)											*/
	/*	$basefilename : name of file qrcode will create in temp		*/
	/*  $qrdata : string, web address or data for qrimage to encode */
	/*OUT: (string) returns the html for the qrcode					*/
	/*DESC: function to create qrcodes								*/	
	/****************************************************************/
	
		$PNG_TEMP_DIR = 'temp/QRCodes/';    
		$errorCorrectionLevel = 'L';
		$matrixPointSize = 4;
		$filename=$PNG_TEMP_DIR.$basefilename;

		//ofcourse we need rights to create temp dir
    	if (!file_exists($PNG_TEMP_DIR)) {
        	mkdir($PNG_TEMP_DIR, 0777, true);
		}
		$filename = $PNG_TEMP_DIR.$basefilename.md5($qrdata.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        QRcode::png($qrdata, $filename, $errorCorrectionLevel, $matrixPointSize, 2);   

		//display generated file  
		/*return '<img width="100px" height="100px" src="'.$PNG_TEMP_DIR.basename($filename).'" /><hr/>';*/
		return $PNG_TEMP_DIR.basename($filename);

	 }
	
}// end of class H2M_Qrcode
/*****************************************************************/
/*****************************************************************/
