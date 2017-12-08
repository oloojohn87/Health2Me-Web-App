<?php

echo 'Confirmed. Please click here to continue: ';
echo '<a href="'.$domain.'"/signup.php">MediBANK sign up</a>';

goto salida;

$mystring = '1966080800000000 kajsdhakjdhs&KASDHD';
$findme   = ' ';
$pos = strpos($mystring, $findme);
echo "<br>\n"; 	

if (!$pos){
//Find the first non-numeric
$d=0;
$n=0;
$quepos=0;
while ($d==0)
{
	$cosa = substr($mystring,$n,1);
	if (!is_numeric($cosa))
	{
		$quepos=$n;
		$d=1;
	}
	$n++;
	if ($n > strlen($mystring)){
		$d=1;
	}
}
	echo substr($mystring,$quepos);
}
else
{
	echo "<br>\n"; 	
	echo $pos;
	echo "<br>\n"; 	
	echo 'Substring: ';
	echo substr($mystring,$pos);
}

salida:

?>