<?php

class DoctorStatsController extends BaseController {
    
    public function getIndex() {
        $med_id = Input::get('doc');
        //$med_id = 2806;
        $result = DB::select("SELECT * FROM lifepin WHERE IdMed=?", array($med_id));
        $countDOC = count($result);
        $r=0;
        $EstadCanal = array(0,0,0,0,0,0,0,0,0,0);
        $EstadCanalValid = array(0,0,0,0,0,0,0,0,0,0);
        $EstadCanalNOValid = array(0,0,0,0,0,0,0,0,0,0);
        $ValidationStatus = array(0,0,0,0,0,0,0,0,0,0,0);
        foreach($result as $rowDOC) {
            $Valid = $rowDOC->ValidationStatus;
		  	$esvalido=0;
			//Add changes for null exception
		  	if (is_numeric($Valid)) { $ValidationStatus[$Valid]++; $esvalido = 1; }
		  	$Canal = $rowDOC->CANAL;
		  	if (is_numeric($Canal)){
		  		$EstadCanal[$Canal]++;
		  		if ($Valid==0 && $esvalido==1) $EstadCanalValid[$Canal] ++;
                else $EstadCanalNOValid[$Canal]++;
            }
		  	$r++;   	
        }	
        $ArrayPacientes = array();
        $numeral=0;
        $numeralF=0;
        $antiguo = 30;
        $NPackets = 0; 
        $resultPAC = DB::select("SELECT distinct IdUs, Fecha FROM doctorslinkusers WHERE IdMED=? ", array($med_id));

        foreach($resultPAC as $rowP1) {
            $ArrayPatients[$numeral] = $rowP1->IdUs;
            $numeral++;
            $antig = time() - strtotime($rowP1->Fecha);
            $days = floor($antig / (60*60*24));
            if ($days < $antiguo) $numeralF++;
            
            $resultPIN = DB::select("SELECT * FROM lifepin WHERE IdUsu = ? ", array($rowP1->IdUs));
            $NPackets += count($resultPIN);
        }
		  
        $NPacketsMIOS = $NPackets;
        $MIOS = $numeral;
        $MIOSF = $numeralF; 
        $arr=array();
        $resultDTD = DB::select("SELECT * FROM doctorslinkdoctors WHERE IdMED=?", array($med_id));
		  
        foreach($resultDTD as $rowCRU) {
            $Autorizado = $rowCRU->IdMED2;
            $resultPAC2 = DB::select("SELECT * FROM doctorslinkusers WHERE IdMED=? ", array($Autorizado));
            foreach($resultPAC2 as $rowC2) {
                $idEncontrado = $rowC2->IdUs;
    		  	if (!in_array($idEncontrado, $ArrayPacientes)){
                    $ArrayPacientes[$numeral] = $idEncontrado;
                    $numeral++;
                    $antig = time()-strtotime($rowC2->Fecha);
                    $days = floor($antig / (60*60*24));
                    if ($days<$antiguo) $numeralF++;
                    $resultPIN = DB::select("SELECT * FROM lifepin WHERE IdUsu = ? ", array($idEncontrado));
                    $countPIN = count($resultPIN);
                    $NPackets= $NPackets + $countPIN;
                }
            }		   
        }  

		$CONACCESO = $numeral;
		$CONACCESOF = $numeralF;	
		$accesibles = $MIOSF; 
        $accesibles2 = $MIOS+.000001; 
        $from_reach1 = intval(number_format(($accesibles*100 / $accesibles2), 0, ',', ' '));       
        $accesibles = $CONACCESOF; 
        $accesibles2 = $CONACCESO + .000001; 
        $from_reach2 = intval(number_format(($accesibles*100 / $accesibles2), 0, ',', ' '));
		  
		if ($NPackets != 0) $porcentajeCreados = intval(number_format((100*$NPacketsMIOS / $NPackets), 0, ',', ' ')); 
        else $porcentajeCreados = 0;
		  				
		$arr['percentageReferred']=$porcentajeCreados; //gauge_value
		$arr['totalPats']=$MIOS; //members
		$arr['totalReach']=$CONACCESO; //reach
		$arr['fromReach1']=$from_reach1; //new_info
		$arr['fromReach2']=$from_reach2; //new_info_from_reach
						
		$resultado = json_encode ($arr);

		return $resultado;
    }
}
