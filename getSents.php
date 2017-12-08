<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>

<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="doctors"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$queUsu = $_GET['Doctor'];
$UserDOB = $_GET['DrEmail'];
$NReports = $_GET['NReports'];
$Username=empty($_GET['Username'])   ? 99 : $_GET['Username'];
$Todoc=empty($_GET['ToDoc']) ? -1:$_GET['ToDoc'];
$state=empty($_GET['state'])? 0:$_GET['state'];

//Adding group functionality to see all the send connection referrals for the doctors in the group

$group=empty($_GET['group'])?0:$_GET['group'];

//$group=1;

if($group==1){

$ArrayDoctors= array();
$cnt=0;
$result_group=$con->prepare("select distinct idDoctor from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor=?)");
$result_group->bindValue(1, $queUsu, PDO::PARAM_INT);
$result_group->execute();

	while($row = $result_group->fetch(PDO::FETCH_ASSOC)){
		$ArrayDoctors[$cnt]=$row['idDoctor'];
		//echo 'DocId'.$cnt.' '.$ArrayDoctors[$cnt];
		$cnt++;
	}
	
	if($cnt==0){
		$ArrayDoctors[$cnt]=$userid;
		$cnt++;
	}

		echo  '<table class="table table-mod" id="TablaSents" style="height:100px; width:600px; overflow-y:hidden; table-layout: fixed; border: 1px SOLID #CACACA;">';
		echo '<thead><tr >
		<th style="width:300px;" lang="en">Member and Doctor</th>
		<th style="width:400px;" lang="en">Status</th>
		<th style="width:60px;" lang="en">Linked?</th>
		<th style="width:100px;" lang="en">Tools</th>
		</tr></thead>';
		echo '<tbody>';
		
 while($cnt>0){
 
	 $cnt--;
	//Adding repeat code-starts
	$queUsu=$ArrayDoctors[$cnt];
	//echo 'DocId'.$cnt.' '.$queUsu;
	
	$ArrayPacientes = array();
	if($Username!='99'){
	$numeral=0;
	$patient_result=$con->prepare("Select * from usuarios where Surname like ? or name like ? or IdUsFIXEDNAME like ?");
	$patient_result->bindValue(1, '%'.$Username.'%', PDO::PARAM_STR);
	$patient_result->bindValue(2, '%'.$Username.'%', PDO::PARAM_STR);
	$patient_result->bindValue(3, '%'.$Username.'%', PDO::PARAM_STR);
	$patient_result->execute();

		while($row = $patient_result->fetch(PDO::FETCH_ASSOC)){
			$ArrayPacientes[$numeral]=$row['Identif'];
			$numeral++;
		}
	}

	if($state==0){
	if($Todoc==-1){
	$query=$con->prepare("SELECT * FROM doctorslinkdoctors WHERE IdMED = ? and Archive IS NULL ORDER BY IdMED2 DESC");
	$query->bindValue(1, $queUsu, PDO::PARAM_INT);
	}else{
	$query=$con->prepare("SELECT * FROM doctorslinkdoctors WHERE IdMED = ? and IdMED2=? and Archive IS NULL ORDER BY IdMED2 DESC");
	$query->bindValue(1, $queUsu, PDO::PARAM_INT);
	$query->bindValue(2, $Todoc, PDO::PARAM_INT);
	}
	}else if($state==1){
	if($Todoc==-1){
	$query=$con->prepare("SELECT * FROM doctorslinkdoctors WHERE IdMED = ? and Archive=1 ORDER BY IdMED2 DESC");
	$query->bindValue(1, $queUsu, PDO::PARAM_INT);
	}else{
	$query=$con->prepare("SELECT * FROM doctorslinkdoctors WHERE IdMED = ? and IdMED2=? and Archive=1 ORDER BY IdMED2 DESC");
	$query->bindValue(1, $queUsu, PDO::PARAM_INT);
	$query->bindValue(2, $Todoc, PDO::PARAM_INT);
	}

	}
	//echo $query;
	//echo '<br>';
	$result = $query->execute();

	if($query->rowCount()){
		
		$qr=$con->prepare("select Name,Surname from doctors where Id=?");
		$qr->bindValue(1, $queUsu, PDO::PARAM_INT);
		$res = $qr->execute();
		
		$row00 = $qr->fetch(PDO::FETCH_ASSOC);
		
			$current_encoding = mb_detect_encoding($row00['Name'], 'auto');
			$show_text = iconv($current_encoding, 'ISO-8859-1', $row00['Name']);

			$current_encoding = mb_detect_encoding($row00['Surname'], 'auto');
			$show_text2 = iconv($current_encoding, 'ISO-8859-1', $row00['Surname']);
		
		$Name=$show_text;
		$Surname=$show_text2;
		
		echo '<tr style="height:10px; line-height:0;">';
		echo '<td><span style="font-size:16px; color:#2D26DF;">'.$Name.' '.$Surname.'</span></td>';
		echo '<td></td>';
		echo '<td></td>';
		echo '<td></td>';
		echo '</tr>';
		
	}	
		

	while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

	$referral_date=$row['Fecha'];
	
	//Added for new referral type
	$referral_type=$row['Type'];

	$referral_id = $row['id'];
	$result2B = $con->prepare("select stage,datevisit from referral_stage where referral_id=?");
	$result2B->bindValue(1, $referral_id, PDO::PARAM_INT);
	$result2B->execute();
	
	$row2B = $result2B->fetch(PDO::FETCH_ASSOC);
	$referral_stage=$row2B['stage'];
	$last_date=$row2B['datevisit'];
		   
	$seekthis = $row['IdMED2'];
	$IdReferral = $row['IdMED2'];     
	$result2 = $con->prepare("SELECT * FROM doctors WHERE id = ? ");
	$result2->bindValue(1, $seekthis, PDO::PARAM_INT);
	$result2->execute();
	
	$emailD2='';
	$NameD2='';
	$SurnameD2='';

	while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {
		$NameD2 = empty($row2['Name'])? '' : $row2['Name'];
		$SurnameD2 = empty($row2['Surname'])? '' : $row2['Surname'];
		$IdDoctor = $row2['id'];
		if($NameD2=='' and $SurnameD2==''){
		$emailD2=$row2['IdMEDEmail'];
		}
		$RoleD2 = $row2['Role'];
		$TreatD2 = '';
		if ($RoleD2 == '1') $TreatD2 = 'Dr.';
	}
	$seekthis = $row['IdPac'];
	$IdPatient = $row['IdPac']; 

	//Changes for filtering the patient name
	if($Username!='99'){
		if(!in_array($IdPatient, $ArrayPacientes)){
				continue;
		} 
	}

	$result2 = $con->prepare("SELECT * FROM usuarios WHERE Identif = ? ");
	$result2->bindValue(1, $seekthis, PDO::PARAM_INT);
	$result2->execute();

	while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {
		$NameP = empty($row2['Name'])? '' : $row2['Name'];
		$SurnameP = empty($row2['Surname'])? '' : $row2['Surname'];
		$idP = $row2['Identif'];
	}
        
    $current_encoding = mb_detect_encoding($NameP, 'auto');
    $NameP = iconv($current_encoding, 'ISO-8859-1', $NameP);

    $current_encoding = mb_detect_encoding($SurnameP, 'auto');
    $SurnameP = iconv($current_encoding, 'ISO-8859-1', $SurnameP);

	$validated = '<a href="#"><span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:red;"lang="en">NO</span></a>';
	if ($row['estado']==2) $validated = '<a href="#"><span class="label label-info" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color: #31B404;" lang="en">YES</span></a>';
	//echo '<tr id="'.$row['id'].'" style="height:10px; line-height:0;">';
	//echo '<td>'.date("F j, Y",strtotime($row['Fecha'])).'</td>';
	//echo '<td>'.$NameP.' '.$SurnameP.'</td>';
	$nomDoc = 'empty';
	if($NameD2=='' and $SurnameD2==''){
		$nomDoc = $emailD2;
		//$nomDoc = 'Dr. email';
	}else{
		$nomDoc =  $TreatD2.$NameD2.' '.$SurnameD2;
		//$nomDoc = 'Dr. Name Surname';
	}

	//echo '<td style="text-align:center;">YES</td>';
	//echo '<td style="text-align:center;">'.$validated.'</td>';
	/*
	if ($row['estado']==2)
	{
		echo '<td class="CFILASents" id="'.$row['id'].'"><div id="BotRevoke" style="margin-left:0px; margin-top:0px; float:left; " class="pull-left"><a href="#" class="btn" title="Send Invitation" style="width:80px;"><i class="icon-remove"></i>Revoke</a> </div></td>';
	}else
	{
		echo '<td class="CFILASents" id="'.$row['id'].'"><div id="BotCancel" style="margin-left:0px; margin-top:0px; float:left; " class="pull-left"><a href="#" class="btn" title="Send Invitation" style="width:80px;"><i class="icon-remove-sign" ></i>Cancel</a> </div></td>';
	}
	*/
	
	$switchA='LetterCircleOFF';
	$switchB='LetterCircleOFF';
	$switchC='LetterCircleOFF';
	$switchD='LetterCircleOFF';
	
	if($referral_type==1){
	
		$switchA='LetterRectangleOFF';
		$switchB='LetterRectangleOFF';
		$switchC='LetterRectangleOFF';
	}
	
	$daystovisit='n/a';
	
	if($referral_type==1){
	
		switch ($referral_stage){
		case -1:$switchA='LetterCircleRED';
				break;
		case 1: $switchA='LetterRectangleON';
				break;
		case 2: $switchA='LetterRectangleON';
				$switchB='LetterRectangleON';
				break;
		case 3: $switchA='LetterRectangleON';
				$switchB='LetterRectangleON';
				$switchC='LetterRectangleON';
				break;
		default:$whatever=0;
				break;
		}
	
	}else{
	
		switch ($referral_stage){
		case -1:$switchA='LetterCircleRED';
				break;
		case 1: $switchA='LetterCircleON';
				break;
		case 2: $switchA='LetterCircleON';
				$switchB='LetterCircleON';
				break;
		case 3: $switchA='LetterCircleON';
				$switchB='LetterCircleON';
				$switchC='LetterCircleON';
				break;
		case 4: $switchA='LetterCircleON';
				$switchB='LetterCircleON';
				$switchC='LetterCircleON';
				$switchD='LetterCircleON';
				break;
		default:$whatever=0;
				break;
		}
	
	}
	   
		
	$dayscolor = '#cacaca';
	$text_time='Time-Waiting';
	if ($referral_stage > 1)
	{
		$dayscolor = 'grey';
		$current_date = date("Y-m-d");
		//$current_date = $referral_date;
		$current_date = date("Y-m-d");
		$db_date = $last_date;//date("Y-m-d");
		//echo ' ***: '.$current_date.' / '.$db_date;
		//$diff = abs(strtotime($current_date) - strtotime($db_date));
		$diff = abs(strtotime($current_date) - strtotime($referral_date));
		$daystovisit = floor ($diff /  (60*60*24));
		if ($referral_stage == 4) {$dayscolor='orange'; $text_time='Time-to-Visit';}
	}
	 
	//$tbl_name="messages"; // Table name
	//$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
	//mysql_select_db("$dbname")or die("cannot select DB");	

	$IdMED= $queUsu;
	$patientid=$IdPatient;
	//echo "SELECT * FROM message_infrasture WHERE receiver_id='$IdMED' and patient_id='$patientid' and status='read' ORDER BY fecha DESC" ;
	$resultMC = $con->prepare("SELECT * FROM message_infrasture WHERE receiver_id=? and patient_id=? and status='new' ORDER BY fecha DESC");
	$resultMC->bindValue(1, $IdMED, PDO::PARAM_INT);
	$resultMC->bindValue(2, $patientid, PDO::PARAM_INT);
	$resultMC->execute();
	
	$countMC = $resultMC->rowCount();  

	$visible_baloon = 'hidden';
	$color_envelope = '#cacaca';    
	if ($countMC>0) {$visible_baloon = 'visible'; $color_envelope='#22aeff';}
	if (strlen($nomDoc) >18) $AddNom = '.. '; else $AddNom='';    
	if (strlen($NameP.' '.$SurnameP) >18) $AddNomP = '.. '; else $AddNomP='';    
	//New design for rows (sandbox)    
	
			$current_encoding = mb_detect_encoding($NameP, 'auto');
			$NameP = iconv($current_encoding, 'ISO-8859-1', $NameP);

			$current_encoding = mb_detect_encoding($SurnameP, 'auto');
			$SurnameP = iconv($current_encoding, 'ISO-8859-1', $SurnameP); 
			
			$current_encoding = mb_detect_encoding($nomDoc, 'auto');
			$nomDoc = iconv($current_encoding, 'ISO-8859-1', $nomDoc); 
	
	echo '<tr  style="height:10px; line-height:0;">';
	echo '<td class="ROWREF" id="'.$idP.'" style="height:10px; width:200px; line-height:0;">
	<div style="padding:10px;">
	<span style="font-size:16px; color:#54bc00; max-width:80px; padding-top:10px; height:10px; margin-bottom:-3px; display:inline-block;" class="truncate">'.$SurnameP.'.</span>
	<span style="font-size:16px; color:#54bc00;">'.strtoupper(substr($NameP,0,1)).'.</span>
	<span style="font-size:12px; color:grey; max-width:95px;">  to </span>
	<span style="font-size:16px; color:#22aeff; max-width:135px; padding-top:10px; height:10px; margin-bottom:-3px; display:inline-block;" class="truncate">'.substr($nomDoc,0,18).$AddNom.'</span>
	<img src="images/FlatH2M.png" alt="" width="15" height="15" style="float:right; margin-right:10px;">
	<div style="width:100%; margin-top:15px;"></div>
	<span style="font-size:10px; color:grey;">'.date("F j, Y",strtotime($row['Fecha'])).'</span>
	</div>
	</td>';

		
		//<span style="font-size:12px; color:grey;">Stages</span>
		//<div style="width:100%; margin-top:15px;"></div>
	echo '<td class="ROWREF" id="'.$idP.'" style="height:10px; width:200px; line-height:0;">
	<div class="'.$switchA.'" title="Stage A means MEMBER ACKNOWLEDGED" lang="en">A</div>
	<div class="'.$switchB.'" title="Stage B means MEMBER APPOINTED" lang="en">B</div>
	<div class="'.$switchC.'" title="Stage C means INFORMATION REVIEWED" lang="en">C</div>';
	if($referral_type!=1){
		echo '<div class="'.$switchD.'" title="Stage D means MEMBER AND DOCTOR MET" lang="en">D</div>';
	}else{
		echo '<div class="LetterRectangleInvisible" title="Stage D means MEMBER AND DOCTOR MET" lang="en"></div>';
	}
	echo '<div style="position:relative; left:45px; top:10px; height:10px; font-size:10px; color:grey; margin-left:45px; margin-top:0px; color:'.$dayscolor.'" lang="en">'.$text_time.'</div>
	<div style="float:left; width:100px; font-size:16px; color:orange; margin-left:45px; margin-top:15px; color:'.$dayscolor.'">'.$daystovisit.' <span lang="en">days</span></div>
	<i class="icon-envelope icon-2x" style="color:'.$color_envelope.'; margin-left:0px; margin-top:10px;"></i>
	<span style="visibility:'.$visible_baloon.'" class="H2MBaloon">'.$countMC.'</span>

	</td>';


	/*
	echo '<td style="height:10px; width:200px; line-height:0;">
	<div style="padding:10px; margin-top:0px;">
	<span style="font-size:22px; color:#54bc00; margin-left:20px;">A</span>
	<span style="font-size:22px; color:#54bc00; margin-left:20px;">B</span>
	<span style="font-size:22px; color:lightgrey; margin-left:20px;">C</span>
	<span style="font-size:22px; color:lightgrey; margin-left:20px;">D</span>
	<span style="font-size:20px; color:grey; margin-left:25px;">6.5 days</span>
	<i class="icon-envelope icon-2x" style="color:#cacaca; margin-left:25px;"></i>
	<span style="" class="H2MBaloon">2</span>
	<div style="width:100%; margin-top:15px;"></div>
	</div>
	</td>';
	*/
	  //<span style="font-size:12px; color:grey; margin-left:20px; font-style:italic;">Scheduled</span>

	echo '<td class="ROWREF" id="'.$idP.'" style="text-align:center; padding-top:18px;">'.$validated.'</td>';

	if ($row['estado']==2)
	{
		if($referral_stage==-1 or $referral_stage==0){
			echo '<td class="CFILASents" id="'.$row['id'].'" idpac="'.$idP.'" idMed="'.$IdDoctor.'" idDLD="'.$referral_id.'" style="text-align:center;"><div id="BotRevoke" style="text-align:center; margin-left:0px; margin-top:0px; font-size:10px;" class=""><a href="javascript:void(0)" class="btn" title="Revoke Referral" ><i class="icon-off"></i>Revoke</a></div></td>';
		}else if($referral_stage==4){
			echo '<td class="CFILASents" id="'.$row['id'].'" idpac="'.$idP.'" idMed="'.$IdDoctor.'" idDLD="'.$referral_id.'" style="text-align:center;"><div id="BotArchive" style="text-align:center; margin-left:0px; margin-top:4px; font-size:10px;" class=""><a href="javascript:void(0)" class="btn" title="Archive Referral" ><i class="icon-off"></i><span lang="en">Archive</span></a></div></td>';
		}else {
			//echo '<td class="CFILASents" id="'.$row['id'].'" idpac="'.$idP.'" idMed="'.$IdDoctor.'" idDLD="'.$referral_id.'" ><div id="BotReminder" style="margin-left:5px; margin-top:0px; margin-right:10px; float:left; " class="pull-left"><a href="javascript:void(0)" class="btn" title="Send a Reminder" style="text-align:center;  width:10px; font-size:12px margin-right:10px; color:blue;"><i class="icon-bell" ></i></a></div>';
			echo'<td class="CFILASents" id="'.$row['id'].'" idpac="'.$idP.'" idMed="'.$IdDoctor.'" idDLD="'.$referral_id.'" ><div id="BotCancel" style="margin-left:0px; margin-top:0px; text-align:center; "><a href="javascript:void(0)" class="btn" title="Cancel Referral" style="text-align:center;  width:10px; font-size:12px; color:red;"><i class="icon-remove" ></i></a></div></td>'; 
		}
	}else
	{
		echo '<td class="CFILASents" id="'.$row['id'].'" idpac="'.$idP.'" idMed="'.$IdDoctor.'" idDLD="'.$referral_id.'" ><div id="BotReminder" style="margin-left:5px; margin-top:0px; margin-right:10px; float:left; " class="pull-left"><a href="javascript:void(0)" class="btn" title="Send a Reminder" style="text-align:center;  width:10px; font-size:12px; color:blue;"><i class="icon-bell" ></i></a></div>';
		echo'<class="CFILASents" id="'.$row['id'].'" idpac="'.$idP.'" idMed="'.$IdDoctor.'" idDLD="'.$referral_id.'" ><div id="BotCancel" style="margin-left:0px; margin-top:0px;  float:center; " class="pull-left"><a href="javascript:void(0)" class="btn" title="Cancel Referral" style="text-align:center;  width:10px; font-size:12px; color:red;"><i class="icon-remove" ></i></a></div></td>'; 
	} 
		
	}

	
	
	 //Adding repeat code- ends
	 
	
 }
	echo '</tbody></table>';
	
}else{

//////////////////////////////////////////////////////////////////HERE IS WHERE THE CODE REALLY STARTS/////////////////////////////////



	$ArrayPacientes = array();
	if($Username!='99'){
	$numeral=0;
	$patient_result = $con->prepare("Select * from usuarios where Surname like ? or name like ? or IdUsFIXEDNAME like ?");
	$patient_result->bindValue(1, '%'.$Username.'%', PDO::PARAM_STR);
	$patient_result->bindValue(2, '%'.$Username.'%', PDO::PARAM_STR);
	$patient_result->bindValue(3, '%'.$Username.'%', PDO::PARAM_STR);
	$patient_result->execute();

		while($row = $patient_result->fetch(PDO::FETCH_ASSOC)){
			$ArrayPacientes[$numeral]=$row['Identif'];
			$numeral++;
		}
	}

	if($state==0){
	if($Todoc==-1){
	$query=$con->prepare("SELECT * FROM doctorslinkdoctors WHERE IdMED = ? and (Archive IS NULL AND Fecha BETWEEN NOW() - INTERVAL 30 DAY AND NOW()) ORDER BY IdMED2 DESC");
	$query->bindValue(1, $queUsu, PDO::PARAM_INT);
	}else{
	$query=$con->prepare("SELECT * FROM doctorslinkdoctors WHERE IdMED = ? and IdMED2=? and (Archive IS NULL AND Fecha BETWEEN NOW() - INTERVAL 30 DAY AND NOW()) ORDER BY IdMED2 DESC");
	$query->bindValue(1, $queUsu, PDO::PARAM_INT);
	$query->bindValue(2, $Todoc, PDO::PARAM_INT);
	}
	}else if($state==1){
	if($Todoc==-1){
	$query=$con->prepare("SELECT * FROM doctorslinkdoctors WHERE IdMED = ? and (Archive=1 OR Fecha < DATE_SUB(NOW(), INTERVAL 30 DAY)) ORDER BY IdMED2 DESC");
	$query->bindValue(1, $queUsu, PDO::PARAM_INT);
	}else{
	$query=$con->prepare("SELECT * FROM doctorslinkdoctors WHERE IdMED = ? and IdMED2=? and (Archive=1 OR Fecha < DATE_SUB(NOW(), INTERVAL 30 DAY)) ORDER BY IdMED2 DESC");
	$query->bindValue(1, $queUsu, PDO::PARAM_INT);
	$query->bindValue(2, $Todoc, PDO::PARAM_INT);
	}

	}elseif($state==2){
	if($Todoc==-1){
	$query=$con->prepare("SELECT * FROM doctorslinkdoctors WHERE IdMED = ? and Archive IS NULL ORDER BY IdMED2 DESC");
	$query->bindValue(1, $queUsu, PDO::PARAM_INT);
	}else{
	$query=$con->prepare("SELECT * FROM doctorslinkdoctors WHERE IdMED = ? and IdMED2=? and Archive IS NULL ORDER BY IdMED2 DESC");
	$query->bindValue(1, $queUsu, PDO::PARAM_INT);
	$query->bindValue(2, $Todoc, PDO::PARAM_INT);
	}
	}
	//echo $query;
	//echo '<br>';
	$result = $query->execute();

		echo  '<table class="table table-mod" id="TablaSents" style="height:100px; width:600px; overflow-y:hidden; table-layout: fixed; border: 1px SOLID #CACACA;">';
		echo '<thead><tr >
		<th style="width:300px;" lang="en">Member and Doctor</th>
		<th style="width:400px;" lang="en">Status</th>
		<th style="width:60px;" lang="en">Linked?</th>
		<th style="width:100px;" lang="en">Tools</th>
		</tr></thead>';
		echo '<tbody>';

	while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

	$referral_date=$row['Fecha'];
	
	//Added for new referral type
	$referral_type=$row['Type'];
	
	$referral_id = $row['id'];
	$result2B = $con->prepare("select stage,datevisit from referral_stage where referral_id=?");
	$result2B->bindValue(1, $referral_id, PDO::PARAM_INT);
	$result2B->execute();
	
	$row2B = $result2B->fetch(PDO::FETCH_ASSOC);
	$referral_stage=$row2B['stage'];
	$last_date=$row2B['datevisit'];
		   
	$seekthis = $row['IdMED2'];
	$IdReferral = $row['IdMED2'];     
	$result2 = $con->prepare("SELECT * FROM doctors WHERE id = ? ");
	$result2->bindValue(1, $seekthis, PDO::PARAM_INT);
	$result2->execute();
	
	$emailD2='';
	$NameD2='';
	$SurnameD2='';

	while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {
			$current_encoding = mb_detect_encoding($row2['Name'], 'auto');
			$show_name = iconv($current_encoding, 'ISO-8859-1', $row2['Name']);

			$current_encoding = mb_detect_encoding($row2['Surname'], 'auto');
			$show_name2 = iconv($current_encoding, 'ISO-8859-1', $row2['Surname']); 
	
		$NameD2 = empty($row2['Name'])? '' : $show_name;
		$SurnameD2 = empty($row2['Surname'])? '' : $show_name2;
		$IdDoctor = $row2['id'];
		if($NameD2=='' and $SurnameD2==''){
		$emailD2=$row2['IdMEDEmail'];
		}
		$RoleD2 = $row2['Role'];
		$TreatD2 = '';
		if ($RoleD2 == '1') $TreatD2 = 'Dr.';
	}
	$seekthis = $row['IdPac'];
	$IdPatient = $row['IdPac']; 

	//Changes for filtering the patient name
	if($Username!='99'){
		if(!in_array($IdPatient, $ArrayPacientes)){
				continue;
		} 
	}

	$result2 = $con->prepare("SELECT * FROM usuarios WHERE Identif = ? ");
	$result2->bindValue(1, $seekthis, PDO::PARAM_INT);
	$result2->execute();

	while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {
        $NameP = empty($row2['Name'])? '' : $row2['Name'];
		$SurnameP = empty($row2['Surname'])? '' : $row2['Surname'];
		$idP = $row2['Identif'];
	}
        
    $current_encoding = mb_detect_encoding($NameP, 'auto');
    $NameP = iconv($current_encoding, 'ISO-8859-1', $NameP);

    $current_encoding = mb_detect_encoding($SurnameP, 'auto');
    $SurnameP = iconv($current_encoding, 'ISO-8859-1', $SurnameP);

	$validated = '<a href="#"><span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color:red;">NO</span></a>';
	if ($row['estado']==2) $validated = '<a href="#"><span class="label label-info" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:5px; font-size:14px; background-color: #31B404;" lang="en">YES</span></a>';
	//echo '<tr id="'.$row['id'].'" style="height:10px; line-height:0;">';
	//echo '<td>'.date("F j, Y",strtotime($row['Fecha'])).'</td>';
	//echo '<td>'.$NameP.' '.$SurnameP.'</td>';
	$nomDoc = 'empty';
	if($NameD2=='' and $SurnameD2==''){
		$nomDoc = $emailD2;
		//$nomDoc = 'Dr. email';
	}else{
		$nomDoc =  $TreatD2.$NameD2.' '.$SurnameD2;
		//$nomDoc = 'Dr. Name Surname';
	}

	//echo '<td style="text-align:center;">YES</td>';
	//echo '<td style="text-align:center;">'.$validated.'</td>';
	/*
	if ($row['estado']==2)
	{
		echo '<td class="CFILASents" id="'.$row['id'].'"><div id="BotRevoke" style="margin-left:0px; margin-top:0px; float:left; " class="pull-left"><a href="#" class="btn" title="Send Invitation" style="width:80px;"><i class="icon-remove"></i>Revoke</a> </div></td>';
	}else
	{
		echo '<td class="CFILASents" id="'.$row['id'].'"><div id="BotCancel" style="margin-left:0px; margin-top:0px; float:left; " class="pull-left"><a href="#" class="btn" title="Send Invitation" style="width:80px;"><i class="icon-remove-sign" ></i>Cancel</a> </div></td>';
	}
	*/

	$switchA='LetterCircleOFF';
	$switchB='LetterCircleOFF';
	$switchC='LetterCircleOFF';
	$switchD='LetterCircleOFF';
	$daystovisit='n/a';
	
	
	if($referral_type==1){
	
		$switchA='LetterRectangleOFF';
		$switchB='LetterRectangleOFF';
		$switchC='LetterRectangleOFF';
	}
		
	if($referral_type==1){
	
		switch ($referral_stage){
		case -1:$switchA='LetterCircleRED';
				break;
		case 1: $switchA='LetterRectangleON';
				break;
		case 2: $switchA='LetterRectangleON';
				$switchB='LetterRectangleON';
				break;
		case 3: $switchA='LetterRectangleON';
				$switchB='LetterRectangleON';
				$switchC='LetterRectangleON';
				break;
		default:$whatever=0;
				break;
		}
	
	}else{
	
		switch ($referral_stage){
		case -1:$switchA='LetterCircleRED';
				break;
		case 1: $switchA='LetterCircleON';
				break;
		case 2: $switchA='LetterCircleON';
				$switchB='LetterCircleON';
				break;
		case 3: $switchA='LetterCircleON';
				$switchB='LetterCircleON';
				$switchC='LetterCircleON';
				break;
		case 4: $switchA='LetterCircleON';
				$switchB='LetterCircleON';
				$switchC='LetterCircleON';
				$switchD='LetterCircleON';
				break;
		default:$whatever=0;
				break;
		}
	
	}   
		
	$dayscolor = '#cacaca';
	$text_time='Time-Waiting';
	if ($referral_stage >= 1)
	{
		$dayscolor = 'grey';
		$current_date = date("Y-m-d");
		//$current_date = $referral_date;
		$current_date = date("Y-m-d");
		$db_date = $last_date;//date("Y-m-d");
		//echo ' ***: '.$current_date.' / '.$db_date;
		//$diff = abs(strtotime($current_date) - strtotime($db_date));
		$diff = abs(strtotime($current_date) - strtotime($referral_date));
		$daystovisit = floor ($diff /  (60*60*24));
		if ($referral_stage == 4) {$dayscolor='orange'; $text_time='Time-to-Visit';}
	}
	 
	//$tbl_name="messages"; // Table name
	//$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
	//mysql_select_db("$dbname")or die("cannot select DB");	

	$IdMED= $queUsu;
	$patientid=$IdPatient;
	//echo "SELECT * FROM message_infrasture WHERE receiver_id='$IdMED' and patient_id='$patientid' and status='read' ORDER BY fecha DESC" ;
	$resultMC = $con->prepare("SELECT * FROM message_infrasture WHERE receiver_id=? and patient_id=? and status='new' ORDER BY fecha DESC");
	$resultMC->bindValue(1, $IdMED, PDO::PARAM_INT);
	$resultMC->bindValue(2, $patientid, PDO::PARAM_INT);
	$resultMC->execute();
	
	$countMC = $resultMC->rowCount();   

	$visible_baloon = 'hidden';
	$color_envelope = '#cacaca';    
	if ($countMC>0) {$visible_baloon = 'visible'; $color_envelope='#22aeff';}
	if (strlen($nomDoc) >18) $AddNom = '.. '; else $AddNom='';    
	if (strlen($NameP.' '.$SurnameP) >18) $AddNomP = '.. '; else $AddNomP=''; 
        
    $current_encoding = mb_detect_encoding($NameP, 'auto');
    $NameP = iconv($current_encoding, 'ISO-8859-1', $NameP);

    $current_encoding = mb_detect_encoding($SurnameP, 'auto');
    $SurnameP = iconv($current_encoding, 'ISO-8859-1', $SurnameP); 
        
	//New design for rows (sandbox)    
	echo '<tr style="height:10px; line-height:0;">';
	echo '<td class="ROWREF" id="'.$idP.'" style="height:10px; width:200px; line-height:0;">
	<div style="padding:10px;">
	<span style="font-size:16px; color:#54bc00; max-width:80px; padding-top:10px; height:10px; margin-bottom:-3px; display:inline-block;" class="truncate">'.$SurnameP.'</span>
	<span style="font-size:16px; color:#54bc00;">'.strtoupper(substr($NameP,0,1)).'.</span>
	<span style="font-size:12px; color:grey;">  to </span>
	<span style="font-size:16px; color:#22aeff; max-width:135px; padding-top:10px; height:10px; margin-bottom:-3px; display:inline-block;" class="truncate">'.substr($nomDoc,0,18).$AddNom.'</span>
	<img src="images/FlatH2M.png" alt="" width="15" height="15" style="float:right; margin-right:10px;">
	<div style="width:100%; margin-top:15px;"></div>
	<span style="font-size:10px; color:grey;">'.date("F j, Y",strtotime($row['Fecha'])).'</span>
	</div>
	</td>';

		
		//<span style="font-size:12px; color:grey;">Stages</span>
		//<div style="width:100%; margin-top:15px;"></div>
	echo '<td class="ROWREF" id="'.$idP.'" style="height:10px; width:200px; line-height:0;">
	<div class="'.$switchA.'" title="Stage A means MEMBER ACKNOWLEDGED" lang="en">A</div>
	<div class="'.$switchB.'" title="Stage B means MEMBER APPOINTED" lang="en">B</div>
	<div class="'.$switchC.'" title="Stage C means INFORMATION REVIEWED" lang="en">C</div>';
	if($referral_type!=1){
		echo '<div class="'.$switchD.'" title="Stage D means MEMBER AND DOCTOR MET" lang="en">D</div>';
	}else{
		echo '<div class="LetterRectangleInvisible" title="Stage D means MEMBER AND DOCTOR MET" lang="en"></div>';
	}

	echo '<div style="position:relative; left:45px; top:10px; height:10px; font-size:10px; color:grey; margin-left:45px; margin-top:0px; color:'.$dayscolor.'" lang="en">'.$text_time.'</div>
	<div style="float:left; width:100px; font-size:16px; color:orange; margin-left:45px; margin-top:15px; color:'.$dayscolor.'">'.$daystovisit.' <span lang="en">days</span></div>
	<i class="icon-envelope icon-2x" style="color:'.$color_envelope.'; margin-left:0px; margin-top:10px;"></i>
	<span style="visibility:'.$visible_baloon.'" class="H2MBaloon">'.$countMC.'</span>

	</td>';


	/*
	echo '<td style="height:10px; width:200px; line-height:0;">
	<div style="padding:10px; margin-top:0px;">
	<span style="font-size:22px; color:#54bc00; margin-left:20px;">A</span>
	<span style="font-size:22px; color:#54bc00; margin-left:20px;">B</span>
	<span style="font-size:22px; color:lightgrey; margin-left:20px;">C</span>
	<span style="font-size:22px; color:lightgrey; margin-left:20px;">D</span>
	<span style="font-size:20px; color:grey; margin-left:25px;">6.5 days</span>
	<i class="icon-envelope icon-2x" style="color:#cacaca; margin-left:25px;"></i>
	<span style="" class="H2MBaloon">2</span>
	<div style="width:100%; margin-top:15px;"></div>
	</div>
	</td>';
	*/
	  //<span style="font-size:12px; color:grey; margin-left:20px; font-style:italic;">Scheduled</span>


	echo '<td class="ROWREF" id="'.$idP.'" style="text-align:center; padding-top:18px;">'.$validated.'</td>';

	if ($row['estado']==2)
	{
		if($referral_stage==-1 or $referral_stage==0){
			echo '<td class="CFILASents" id="'.$row['id'].'" idpac="'.$idP.'" idMed="'.$IdDoctor.'" idDLD="'.$referral_id.'" style="text-align:center;"><div id="BotRevoke" style="text-align:center; margin-left:0px; margin-top:0px; font-size:10px;" class=""><a href="javascript:void(0)" class="btn" title="Revoke Referral" ><i class="icon-off"></i>Revoke</a></div></td>';
		}else if($referral_stage==4){
			echo '<td class="CFILASents" id="'.$row['id'].'" idpac="'.$idP.'" idMed="'.$IdDoctor.'" idDLD="'.$referral_id.'" style="text-align:center;"><div id="BotArchive" style="text-align:center; margin-left:0px; margin-top:4px; font-size:10px;" class=""><a href="javascript:void(0)" class="btn" title="Archive Referral" ><i class="icon-off"></i><span lang="en">Archive</span></a></div></td>';
		}else {
			//echo '<td class="CFILASents" id="'.$row['id'].'" idpac="'.$idP.'" idMed="'.$IdDoctor.'" idDLD="'.$referral_id.'" ><div id="BotReminder" style="margin-left:5px; margin-top:0px; margin-right:10px; float:left; " class="pull-left"><a href="javascript:void(0)" class="btn" title="Send a Reminder" style="text-align:center;  width:10px; font-size:12px margin-right:10px; color:blue;"><i class="icon-bell" ></i></a></div>';
			echo'<td class="CFILASents" id="'.$row['id'].'" idpac="'.$idP.'" idMed="'.$IdDoctor.'" idDLD="'.$referral_id.'" ><div id="BotCancel" style="margin-left:0px; margin-top:0px; text-align:center;"><a href="javascript:void(0)" class="btn" title="Cancel Referral" style="text-align:center;  width:10px; font-size:12px; color:red;"><i class="icon-remove" ></i></a></div></td>'; 
		}
	}else
	{
		echo '<td class="CFILASents" id="'.$row['id'].'" idpac="'.$idP.'" idMed="'.$IdDoctor.'" idDLD="'.$referral_id.'" ><div id="BotReminder" style="margin-left:5px; margin-top:0px; margin-right:10px; float:left; " class="pull-left"><a href="javascript:void(0)" class="btn" title="Send a Reminder" style="text-align:center;  width:10px; font-size:12px; color:blue;"><i class="icon-bell" ></i></a></div>';
		echo'<class="CFILASents" id="'.$row['id'].'" idpac="'.$idP.'" idMed="'.$IdDoctor.'" idDLD="'.$referral_id.'" ><div id="BotCancel" style="margin-left:0px; margin-top:0px;  float:center; " class="pull-left"><a href="javascript:void(0)" class="btn" title="Cancel Referral" style="text-align:center;  width:10px; font-size:12px; color:red;"><i class="icon-remove" ></i></a></div></td>'; 
	} 
		
	}

	echo '</tbody></table>';    
}
?>
<script type="text/javascript">
if($.cookie('lang') != 'en')
{
    $.get('jquery-lang-js-master/js/langpack/'+initial_language+'.json', function(data, status)
    {
        var json = data;
        $('*[lang^=\"en\"]').each(function()
        {
            $(this).attr('original_eng_text', $(this).text());
            if(json.token.hasOwnProperty($(this).text()))
            {
                $(this).text(json.token[$(this).text()]);
            }
            else if(json.token.hasOwnProperty($(this).html()))
            {
                $(this).html(json.token[$(this).html()]);
            }
            else if ($(this).prop('tagName') == 'INPUT' && $(this).prop('type') == 'submit' || $(this).prop('type') == 'button' && json.token.hasOwnProperty($(this).val()))
            {
                $(this).val(json.token[$(this).val()]);
            }
            else if ($(this).prop('tagName') == 'INPUT' && $(this).prop('type') == 'text' && json.token.hasOwnProperty($(this).attr('placeholder')))
                        {
                            $(this).attr('placeholder', (json.token[$(this).attr('placeholder')]));
                        }
            else if(json.token.hasOwnProperty($(this).prop('title')))
                        {
                            $(this).prop('title', json.token[$(this).prop('title')]);
                        }
        });
    });
}
</script>