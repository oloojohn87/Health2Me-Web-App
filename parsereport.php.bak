<?php

/*
echo '<pre>';

$fichero='eurotext.tif';
$salida ='salphp';

//echo exeeurotext.tif salidac('tesseract document.tif result');
//echo exec('tesseract eurotext.tif result3');
try {
    $msg = array(); // TRY THIS 
    //exec("tes.exe eurotext.tif result4", $msg);
    exec("tesseract.exe eur.tif result4", $msg);
    var_dump($msg);
    
    var_export($msg);
} catch (Exception $e) {
    echo $e;
}

echo '</pre>';
*/

echo "Enter php";

$queURL=$_GET['URLIma'];

$pos = strpos($queURL, '.');
$exten = substr($queURL, 1+$pos,3);
$raiz = substr($queURL, 0,$pos);
/*
echo "************************COMPLETO : ".$queURL."---";
echo "************************EXTENSION : ".$exten."---";
echo "************************RAIZ : ".$raiz."---";
*/

if ($exten=='pdf')
{
$contenTHURL = $domain.'/PackagesTH/'.$raiz.'.png';  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
$contenURL =   $domain.'/Packages/'.$queURL;  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
	    	
//$remoto= $contenTHURL;
$remoto= $contenURL;
copy($remoto, 'PREVIO.PDF');

//$salida = pdf2text($remoto);
//echo "----PDF---PDF-------PDF---PDF---LECTURA 1 (TEXTO DENTRO DEL PDF): ".$salida."----PDF---PDF-------PDF---PDF---";

// TRANSFORMACIÓN DEL PDF A JPG (COMPLETO)
						$cadenaConvert = 'convert PREVIO.PDF -colorspace RGB -geometry 1480 pdfconvertido.jpg 2>&1';
						echo "EXEC IMAGEMAGIK --- PREVIO.PDF ----";
						$output = shell_exec($cadenaConvert);  
						echo "<pre>$output</pre>";
						echo "<br>\n"; 
						echo "DONE EXEC IMAGEMAGIK ---- PDFCONVERTIDO.JPG ----";
						echo "<br>\n"; 
// TRANSFORMACIÓN DEL PDF A JPG (COMPLETO)

echo '***************************************';
echo "<br>\n"; 	    
echo 'OCR: ';
echo "<br>\n"; 	    

$output = shell_exec('tesseract -l spa -psm 6 pdfconvertido.jpg result4 2>&1');
echo "<pre>$output</pre>";
echo "<br>\n"; 	    
echo '***************************************';
echo "<br>\n"; 	    

//$file = file_get_contents('result4.txt', true);
$filename = "result4.txt";
$handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
fclose($handle);
$file = $contents;

echo '---------------------------------------';
echo "<br>\n"; 	    
echo "CONTENIDO: ";
echo "<br>\n"; 	  
  
echo "*3INICIO3*";
echo $file;
echo "*3FINAL3*";


echo "<br>\n"; 	    
echo '---------------------------------------';



$output = shell_exec('tesseract  2>&1');
echo "<pre>$output</pre>";

}
else
{
//$remoto= 'http://www.monimed.com/eMapLife/PinImageSet/eML000013405937d802902e3223980b21e6dbfdea1.jpg';
$remoto= 'http://www.monimed.com/eMapLife/PinImageSet/'.$queURL;


echo "QUE URL: ";
echo $remoto;
//copy('http://example.com/image.php', 'local/folder/flower.jpg');


copy($remoto, 'flower.jpg');

echo '***************************************';
echo "<br>\n"; 	    
echo 'OCR: ';
echo "<br>\n"; 	    

$output = shell_exec('tesseract -l spa -psm 6 flower.jpg result4 2>&1');
echo "<pre>$output</pre>";

echo "<br>\n"; 	    
echo '***************************************';
echo "<br>\n"; 	    

//$file = file_get_contents('result4.txt', true);
$filename = "result4.txt";
$handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
fclose($handle);
$file = $contents;

echo '---------------------------------------';
echo "<br>\n"; 	    
echo "CONTENIDO: ";
echo "<br>\n"; 	  
  
echo "*3INICIO3*";
echo $file;
echo "*3FINAL3*";


echo "<br>\n"; 	    
echo '---------------------------------------';



$output = shell_exec('tesseract  2>&1');
echo "<pre>$output</pre>";
}














function pdf2text($filename) { 

    // Read the data from pdf file
    $infile = @file_get_contents($filename, FILE_BINARY); 
    if (empty($infile)) 
        return ""; 

    // Get all text data.
    $transformations = array(); 
    $texts = array(); 

    // Get the list of all objects.
    preg_match_all("#obj(.*)endobj#ismU", $infile, $objects); 
    $objects = @$objects[1]; 

    // Select objects with streams.
    for ($i = 0; $i < count($objects); $i++) { 
        $currentObject = $objects[$i]; 

        // Check if an object includes data stream.
        if (preg_match("#stream(.*)endstream#ismU", $currentObject, $stream)) { 
            $stream = ltrim($stream[1]); 

            // Check object parameters and look for text data. 
            $options = getObjectOptions($currentObject); 
            if (!(empty($options["Length1"]) && empty($options["Type"]) && empty($options["Subtype"]))) 
                continue; 

            // So, we have text data. Decode it.
            $data = getDecodedStream($stream, $options);  
            if (strlen($data)) { 
                if (preg_match_all("#BT(.*)ET#ismU", $data, $textContainers)) { 
                    $textContainers = @$textContainers[1]; 
                    getDirtyTexts($texts, $textContainers); 
                } else 
                    getCharTransformations($transformations, $data); 
            } 
        } 

    } 

    // Analyze text blocks taking into account character transformations and return results. 
    return getTextUsingTransformations($texts, $transformations); 
}

?>