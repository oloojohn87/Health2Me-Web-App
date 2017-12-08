<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

$search_string = empty($_GET['search_string']) ? 'a': $_GET['search_string'];


$lib  = simplexml_load_file("icd10en.xml");
$children = $lib->children(); 

$n =0;
$m =0;
$max_retrieval = 50;
$cadena = '';

foreach($children as $node){    
    
    if ($node->getName() == 'Class'){
        $Nombre = $node->Rubric->Label;
        $codICD10 = $node->attributes()->code;
        $Nombre_search = strtolower($Nombre);
        $search_string = strtolower($search_string);
        if ((strpos($Nombre_search,$search_string) !== false) && ($m < $max_retrieval))
        {
            $m++;
            if ($m > 1) $cadena.=',';
            $cadena.='{"disName":"'.$Nombre.'","ICD10Code":"'.$codICD10.'"}';    
        };
     }
    $n++;
    
}

echo '{"items":['.($cadena).']}'; 

?>