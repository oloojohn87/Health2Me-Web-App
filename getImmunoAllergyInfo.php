<?php
ini_set("display_errors", 0);
session_start(); 
require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];



$age[0] = 'B';   	$ageVal[0] = 0;
$age[1] = '1m';		$ageVal[1] = 1;
$age[2] = '2m';		$ageVal[2] = 2;
$age[3] = '4m';		$ageVal[3] = 4;
$age[4] = '6m';		$ageVal[4] = 6;
$age[5] = '12m';	$ageVal[5] = 12;
$age[6] = '15m';	$ageVal[6] = 15;
$age[7] = '18m';	$ageVal[7] = 18;
$age[8] = '2a';		$ageVal[8] = 2 * 12;		
$age[9] = '3a';		$ageVal[9] = 3 * 12;
$age[10] = '5a';	$ageVal[10] = 5 * 12;
$age[11] = '8a';	$ageVal[11] = 8 * 12;
$age[12] = '10a';	$ageVal[12] = 10 * 12;
$age[13] = '12a';	$ageVal[13] = 12 * 12;
$age[14] = '14a';	$ageVal[14] = 14 * 12;




$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                             PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
    die('Could not connect: ' . mysql_error());
}				

$UserID = $_GET['IdUsu'];
//$UserID = 1313;

$query = $con->prepare("select * from p_immuno where deleted=0 and idpatient=?");
$query->bindValue(1, $UserID, PDO::PARAM_INT);
$result = $query->execute();

$count=$query->rowCount();
$counter1 = 0; 

$cadena = '';
$rowCounter = 0;
$rowCounterAllergy = 0;
$latest_update = '1975-01-01 00:00:00';
$doctor_signed = -1;

while ($row2 = $query->fetch(PDO::FETCH_ASSOC)) {
    $Id[$counter1] = $row2['id'];
    $VaccCode[$counter1] = $row2['VaccCode'];
    $VaccName[$counter1] = $row2['VaccName'];
    $AllerCode[$counter1] = $row2['AllerCode'];
    $AllerName[$counter1] = $row2['AllerName'];
    $intensity[$counter1] = $row2['intensity'];
    $dateEvent[$counter1] = $row2['date'];
    $ageEvent[$counter1] = $row2['ageevent'];
    $deleted[$counter1] = $row2['deleted'];

    $doctor_signedP = $row2['doctor_signed'];
    $latest_updateP = $row2['latest_update'];
    if (strtotime($latest_updateP) > strtotime($latest_update)) { $latest_update = $latest_updateP; $doctor_signed = $doctor_signedP;}


    $slotAge[$counter1] = 0;
    $slotRow[$counter1] = 0;

    //THIS IF STATEMENT CHECKS TO SEE IF THE ROW IS A VACCINE OR ELSE IT IS ALLERGY
    if ((($VaccCode[$counter1] != '') || ($VaccName[$counter1] != '')) && !isset($_GET['allergy']))   // --------------------------- This entry is a Vaccine
    {
        $measureAge[$counter1] = substr($ageEvent[$counter1], strlen($ageEvent[$counter1])-1,1);
        $numericAge[$counter1] = substr($ageEvent[$counter1], 0, strlen($ageEvent[$counter1])-1);

        if (strtoupper($measureAge[$counter1]) == 'Y')
        {
            $monthsAge[$counter1] = intval($numericAge[$counter1]) * 12;
        }else
        {
            $monthsAge[$counter1] = intval($numericAge[$counter1]);
        }

        $pointerEntry[$rowCounter] = $counter1;
        $n = 0;
        $closer = 9999999;
        while ($n <= 14)
        {
            $difference = abs($monthsAge[$counter1] - $ageVal[$n]);
            if ($difference < $closer)
            {
                $closer = $difference;
                $slotAge[$counter1] = $n;
            }
            $n++;
        }
        // Check if there is another entry of the same vaccine in other previous row
        $y = 0;
        $foundRow = 0;
        while ($y < $rowCounter)
        {
            if ($VaccCode[$counter1] == $VaccCode[$y])
            {
                $foundRow = 1;
                $slotRow[$counter1] = $y;
            }
            $y++;
        }
        // If previous Vaccine not found asign a new row
        if ($foundRow == 0)
        {
            $slotRow[$counter1] = $rowCounter;
            $rowCounter++;		
        }
        //echo 'Slot:  '.$slotAge[$counter1].'   Row: '.$slotRow[$counter1].' Value:  '.$VaccCode[$counter1].'  ----   ';				
    }
    else        // ------------------------------------------------------------------------ This entry is an Allergy
    {
        $pointerEntry[$rowCounterAllergy] = $counter1;
        //echo 'row ='.$rowCounterAllergy.'   Pointer = '.$counter1;
        if(isset($_GET['allergy']) && $row2['AllerCode'] != ''){
            $rowCounterAllergy++;
        }
    }


    $counter1++;
}



echo '<style>';
echo 'div.timecell{';
echo '  background-color:#22aeff; margin:1px; text-align:center; color:white; font-size:12px;';
echo '  } ';
echo 'div.cellvacc{';
echo '  font-size:10px; color:white; margin:0 auto; margin-bottom:1px; line-height: 12px; background-color:#cacaca; text-align:center; width:30px;';
echo '  } ';
echo 'div.cellaller{';
echo '  font-size:12px; color:white; margin:0 auto; margin-bottom:1px; line-height: 14px; background-color:#cacaca; text-align:center; width:30px;';
echo '  } ';
echo 'td.Ttimecell{';
echo '  padding:0px; margin:0px; text-align:center; background-color: white; ';
echo '  } ';
echo 'tr.AllerRow{';
echo '  padding:0px; margin:0px; height:16px; text-align:center; background-color: white; ';
echo '  } ';
echo '</style>';

if(!isset($_GET['allergy'])){
    echo '				<div id="ImmunoInnerL" style="float:left; width:80%; height:100%; border:0px solid;">';

    echo '				<div  style="width:125%; height:20px; border:0px solid; text-align:center; background-color: #f39c12; color: white;line-height: 20px;">';
    if($_COOKIE["lang"] == 'th'){
        echo 'Calendario De Vacunación';
    }else{
        echo 'Vaccination Calendar';
    }
    echo '</div>';
}else{
    echo '<div  style="width:100%; height:20px; border:0px solid; text-align:center; background-color: #24CCC1; color: white;line-height: 20px;">';
    if($_COOKIE["lang"] == 'th'){
        echo 'Alergias';
    }else{
        echo 'Allergies';
    }
    echo '</div>';
}
//echo '				<div  style="width:125%; height:16px; border:0px solid; text-align:center; background-color: #22aeff;color: white;line-height: 16px;">Vaccination Calendar</div>';
echo '					<table style="margin-top:5px;">';
echo '						<tr style="padding:0px; margin:0px;">';
$n = 0;				
//LOOOPS THROUGH VACCINES AND CHECKS FOR COLOR AND AGE OF VACCINATION
while ($n < 15 && !isset($_GET['allergy']))
{
    $wide = 30 + (($n * 70) / 14);
    $lightness = 57 + (($n * 33) / 14);
    $newcolor = 'hsl(202, 100%, '.$lightness.'%)';
    echo '							<td class="Ttimecell" style="width:'.$wide.'px;"><div class="timecell" style="background-color:'.$newcolor.'">'.$age[$n].'</div></td>';		
    $n++;
};

echo '							';
echo '						</tr>';
$m = 0;

//DISPLAYS THE VACCINES IN THE VACCINE BOX
while ($m < 7)
{
    $maxlight = 50;
    $minlight = 10;
    $lightness = $minlight + (($m * ($maxlight-$minlight)) / 5);		
    $newcolor = 'hsl(108, 100%, '.$lightness.'%)';
    //echo $m.' ---- ';
    echo '						<tr style="padding:0px; margin:0px;">';
    $n = 0;
    while ($n < 15)
    {
        $filled = checkSlot($m,$n);
        if ($filled > -1&& !isset($_GET['allergy'])) 
        {
            echo '<td class="Ttimecell" style="width:20px;"><div class="cellvacc" style="background-color: '.$newcolor.'" title="'.$VaccName[$filled].'">'.substr($VaccCode[$filled],0,4).'</div></td>';
        }
        elseif(!isset($_GET['allergy']))
        {
            echo '<td class="Ttimecell" style="width:20px;"><div class="cellvacc" style="background-color: white;"></div></td>';
        }
        $n++;
    }
    echo '						</tr>';
    $m++;
}


echo '					</table>';
echo '				</div>';
echo '				<div id="ImmunoInnerR" style="float:left; width:0%; height:100%; border:0px solid;">';
//echo '				<div  style="width:78%; height:20px; border:0px solid; text-align:center; background-color: #f39c12; color: black;line-height: 20px;">Allergies</div>';
//echo '				<div  style="width:78%; height:16px; border:0px solid; text-align:center; background-color: #22aeff;color: white;line-height: 16px;">Allergies</div>';
echo '					<table style="margin-top:0px;width:100%;">';
$m = 0;

if($rowCounterAllergy == 0 && isset($_GET['allergy'])){
    echo '<tr class="AllerRow">
<td class="Ttimecell" style="width:40px;"><div class="cellaller" style="width:180px;margin-left:5px;margin-right:5px;">';
    if($_COOKIE["lang"] == 'th'){
        echo 'No Hay Entradas';
    }else{
        echo 'No Entries';
    }
    echo '</div><p><center><img width="75px" src="images/icons/general_user_error_icon.png" alt="No Data Icon"></center></td>
</tr>';
}

//DISPLAYS THE ALLERGIES IN THE ALLERGY BOX
while ($m <= ($rowCounterAllergy-1) && isset($_GET['allergy']))
{
    $iconR = "";
    if ($intensity[$pointerEntry[$m]]>3) 
        $iconR = '-webkit-animation: glow 2s linear infinite;';
    $titleAl = '';
    if ($intensity[$pointerEntry[$m]] == 4) 
        $titleAl = 'Very Strong Intensity Allergy'; 
    if ($intensity[$pointerEntry[$m]] == 5) 
        $titleAl = 'EXTREME Intensity Allergy'; 
    $maxlight = 50;
    $minlight = 20;
    $lightness = $minlight + (($intensity[$pointerEntry[$m]] * ($maxlight-$minlight)) / 5);		
    $newcolor = 'hsl(0, 100%, '.$lightness.'%)';
    if($AllerName[$pointerEntry[$m]] != "")
    {

        if($_COOKIE["lang"] == 'th')
        {
            if($AllerName[$pointerEntry[$m]] == 'Environmental')
            {
                $aller_title = 'Ambiental';
                $aller_display = substr($aller_title,0,8);
            }
            elseif($AllerName[$pointerEntry[$m]] == 'Foods')
            {
                $aller_title = 'Comidas';
                $aller_display = substr($aller_title,0,8);
            }
            elseif($AllerName[$pointerEntry[$m]] == 'Drugs')
            {
                $aller_title = 'Medicamentos';
                $aller_display = substr($aller_title,0,8);
            }
            elseif($AllerName[$pointerEntry[$m]] == 'Other')
            {
                $aller_title = 'Otros';
                $aller_display = substr($aller_title,0,8);
            }
            elseif($AllerName[$pointerEntry[$m]] == 'Nothing')
            {
                $aller_title = 'Nada';
                $aller_display = substr($aller_title,0,8);
            }
            else
            {
                $aller_title = $AllerName[$pointerEntry[$m]];
                $aller_display = substr($AllerName[$pointerEntry[$m]],0,8);
            }
        }
        else
        {
            $aller_title = $AllerName[$pointerEntry[$m]];
            $aller_display = substr($AllerName[$pointerEntry[$m]],0,8);
        }

        echo '						<tr class="AllerRow">';
        echo '							<td class="Ttimecell" style="width:40px;"><div class="cellaller" style="'.$iconR.'background-color:'.$newcolor.'; width:180px;margin-left:5px;margin-right:5px;" title="'.$aller_title.'">'.$aller_display.'</div></td>';			
        echo '							<td class="Ttimecell" style="width:20px;" title="'.$titleAl.'">';
        echo '						</tr>';
    }
    //echo $m.'<br>';
    //echo $AllerName[$pointerEntry[$m]].'<br>';
    //echo 'RCA : '.$rowCounterAllergy.'<br>';
    $m++;

}
echo '<input id="Idoctor_signed" value="'.$doctor_signed.'" style="width:20px; float:left; display:none;">';	
echo '<input id="Ilatest_update" value="'.$latest_update.'" style="width:120px; float:left; display:none;">';	
echo '				</div>';




function checkSlot ($row, $slot)
{
    $x = 0;
    $found = -1;
    while ($x <= ($GLOBALS['counter1']-1))
    {
        if (($GLOBALS['slotAge'][$x] == $slot)  && ($GLOBALS['slotRow'][$x] == $row)) $found = $x; //$GLOBALS['VaccCode'][$x];
        $x++;
    }
    return $found;
}

?>
