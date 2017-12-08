<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
//require "identicon.php";
 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="messages"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$queBlock = $_GET['BlockId'];

//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks WHERE id='$queBlock' ");
$result = $con->prepare("SELECT * FROM lifepin WHERE IdPin=? ");
$result->bindValue(1, $queBlock, PDO::PARAM_INT);
$result->execute();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

$sERU = $row['EvRuPunt'];
$sEvento = $row['Evento'];
$sTipo = $row['Tipo'];
if ($sERU>0)
{
$sEvento = '99';
}
$queUsu = $row['IdUsu'];

/*echo '	         <p><H4>Class:  </H3>';
echo '	               <div class="formRight" stytle="width:50px;">';
echo '		               <select name="Clases" id="Clases" data-placeholder="Select Class (reason for this data ?)" class="chzn-select chosen_select" multiple tabindex="5" >';
//echo '                            <option value=""></option>';
echo '                            <optgroup label="Episodes (user folder)" id="Episodios">';



//$q = mysql_query("SELECT * FROM blocks WHERE NeedACTION=1 ORDER BY id DESC ");

$result2 = $con->prepare("SELECT * FROM usueventos WHERE IdUsu=? ORDER BY IdEvento ASC");
$result2->bindValue(1, $queUsu, PDO::PARAM_INT);
$result2->execute();

//$result2 = mysql_query("SELECT * FROM usueventos WHERE IdUsu='$queUsu'");
$tamano = 0;
$UltimoEvento = $tamano;
while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {
	$nombreEv[$tamano]=$row2['Nombre'];
	$valorEv[$tamano]=$row2['IdEvento'];

echo '	  							<option  value="0'.$valorEv[$tamano].'">'.$nombreEv[$tamano].'</option>';
	$UltimoEvento = $row2['IdEvento'];
$tamano++;
}

echo '                            </optgroup>';
echo '                            <optgroup label="Routine / Checks">';
echo '                             <option value="199" value2="99">Routine / Checks</option>';
echo '                            </optgroup>';
echo '                            <optgroup label="Isolated Data">';
echo '                              <option value="299" value2="99">Isolated Data</option>';
echo '                            </optgroup>';
echo '                            <optgroup label="Drug Related Data">';
echo '                              <option value="399" value2="99">Drug Related Data</option>';
echo '                           </optgroup>';
echo '                          </select>';
echo '                       </div>   ';
echo '              <button id="BotonAddClase"  class="btn btn-small" style=""><i class="icon-plus-sign"></i>Add New Episode (Class)</button>';
echo '              <button id="BotonElimClase"  class="btn btn-small" style=""><i class="icon-remove"></i>Remove Episode (Class)</button>';*/
echo ' ';
echo '	         </p>';
echo '	         <p><H4>Type:  </H3>';
echo '	         	    <div class="formRight">';
echo '		               <select name="Tipos" id="Tipos" data-placeholder="Select Type (is it a report, an image, etc, ?)" class="chzn-select chosen_select" multiple tabindex="5" style="height:200px">';
//echo '                            <option value=""></option>';
$rg=0;
while ($rg<10)
{
	$rg++;
	$queQuery =$con->prepare('SELECT * FROM tipopin WHERE Agrup = ?');
	$queQuery->bindValue(1, $rg, PDO::PARAM_INT);
	$result2 = $queQuery->execute();
	if($result2){
	$row2 = $queQuery->fetch(PDO::FETCH_ASSOC);
	
	echo '                            <optgroup label="'.$row2['GroupName'].'">';
	}
	$queQuery =$con->prepare('SELECT * FROM tipopin WHERE Agrup = ?');
	$queQuery->bindValue(1, $rg, PDO::PARAM_INT);
	$result2 = $queQuery->execute();
	$tamano = 0;
	if($result2){
	while ($row2 = $queQuery->fetch(PDO::FETCH_ASSOC)) 
	{
		if ($tamano==0) $adiciona = ' (generic) '; else $adiciona = ' ';
		$nombreTipo[$tamano]=$row2['NombreEng'].$adiciona;
		$valorTipo[$tamano]=$row2['Id'];
		echo '	  							<option value='.$valorTipo[$tamano].'>'.$nombreTipo[$tamano].'</option>';
		$tamano++;
	}
	}
	echo '                            </optgroup>';
}

echo '                         </select>';
echo '                    </div>   ';
echo '';
echo '	         </p>';
echo '        <input type="hidden" id="queUsu" value="'.$queUsu.'"/>';
echo '        <input type="hidden" id="queBlock" value="'.$queBlock.'"/>';
echo '        <input type="hidden" id="UltimoEvento" value=""/>'; //'.$UltimoEvento.'
echo '        <input type="hidden" id="SelecERU" value="'.$sERU.'"/>';
echo '        <input type="hidden" id="SelecEvento" value="'.$sEvento.'"/>';
echo '        <input type="hidden" id="SelecTipo" value="'.$sTipo.'"/>';


//echo '	         <p><H5>Clinical Area:  </H3></p>';
//echo '        <option> PRUEBA </option>';


}


?>