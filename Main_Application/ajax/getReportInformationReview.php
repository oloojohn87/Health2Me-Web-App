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

$referral_id=$_GET['referralid'];
//$stage=$_GET['stage'];
$fechaconfirm=0;
$attachments_dld=0;

$sql=$con->prepare("SELECT * FROM doctorslinkdoctors where id=?");
$sql->bindValue(1, $referral_id, PDO::PARAM_INT);
$q = $sql->execute();

if($q){
$row = $sql->fetch(PDO::FETCH_ASSOC);
$doc_id=$row['IdMED2'];
$fechaconfirm=$row['FechaConfirm'];
$USERID=$row['IdPac'];
$attachments_dld=$row['attachments'];
}

	$reportviewed=0;
	if($attachments_dld!=0){
		$report_id=explode(" ",$attachments_dld);
		$cntt=count($report_id);
		$i=0;
		//Remember to add the check for date of the reports viewed. It should always be greater than appointment schedule date
		while($cntt>0){
		$getinfo = $con->prepare("select id from bpinview USE INDEX(I1) where viewIdUser=? and viewIdMed=? and content='Report Viewed' and IDPIN=? and DateTimeSTAMP>?");
		$getinfo->bindValue(1, $USERID, PDO::PARAM_INT);
		$getinfo->bindValue(2, $doc_id, PDO::PARAM_INT);
		$getinfo->bindValue(3, $report_id[$i], PDO::PARAM_INT);
		$getinfo->bindValue(4, $fechaconfirm, PDO::PARAM_STR);
		$getinfo->execute();
		
		$cnt_info=$getinfo->rowCount();
		/*echo $cnt_info;
		echo 'i '.$report_id[$i];
		echo '<br>';*/
		if($cnt_info)
			$reportviewed=1;
		else
			$reportviewed=0;
		$i++;
		$cntt--;
		}
	}
//$count=mysql_num_rows($result);
echo $reportviewed;
?>