<!-- Create language switcher instance and set default language to en-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-cookie.js" charset="utf-8" type="text/javascript"></script>
<script src="jquery-lang-js-master/js/jquery-lang.js" charset="utf-8" type="text/javascript"></script>
<script src="/jquery-cookie-master/jquery.cookie.js"></script>
<script type="text/javascript">
	var lang = new Lang('en');
	window.lang.dynamic('th', 'jquery-lang-js-master/js/langpack/th.json');
	
	var langType = $.cookie('lang');

if(langType == 'th'){
var language = 'th';
}else{
var language = 'en';
}

if(langType == 'th'){
window.lang.change('th');
//alert('th');
}

if(langType == 'en'){
window.lang.change('en');
//alert('en');
}
</script>
<?php
 //   echo '<table><tr><td>TEST</td></tr></table>';
 
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
 		/* $dbhost = "dev.health2.me";
		 $dbname = "monimed";
		 $dbuser = "monimed";
		 $dbpass = "ardiLLA98";*/
 
$tbl_name="usuarios"; // Table name

$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$queUsu = $_GET['Usuario'];
$Group=$_GET['Group'];

echo "Location".$location;
//$queUsu=null;
if($queUsu==null or $queUsu==" " or $queUsu=="" or $queUsu==-111){
	$queUsu="";
}
//New code added by Pallab
else
{
  $Group = -1;
}
//$NReports = $_GET['NReports'];
$IdMed = $_GET['IdMed'];
//$queUsu = 32;

echo  '<table class="table table-mod" id="TablaPac">';
//Changed in below line - removed the Id-Fixed, changed Username to email and N.Reports to Number of Reports  and to show the total reports
echo '<thead><tr id="FILA" class="CFILA"><th lang="en">Identifier</th><th lang="en">First Name</th><th lang="en">Last Name</th><th lang="en">Username</th><th lang="en">Total Reports</th></tr></thead>';
echo '<tbody>';

//$ArrayPacientes = new SplFixedArray(99999);
$ArrayPacientes = array();
$numeral=0;



$resultPRE=$con->prepare("Select q.* from ((select USU.* from monimed.usuarios USU INNER JOIN ((select A.idDoctor from monimed.doctorsgroups A INNER JOIN (select idGroup from monimed.doctorsgroups where idDoctor=?) B where B.idGroup=A.idGroup)) DG where DG.idDoctor=USU.IdCreator)UNION(select USU.* from monimed.usuarios USU INNER JOIN (select IdPac from monimed.doctorslinkdoctors where IdMED2=?) DLD where DLD.IdPac=USU.Identif)UNION(select USU.* from monimed.usuarios USU INNER JOIN (select IdUs from monimed.doctorslinkusers where IdMED=?) DLU where DLU.IdUs=USU.Identif)UNION(Select * from monimed.usuarios where IdCreator=?))q where q.Surname like ? or q.Name like ? or q.IdUsFIXEDNAME like ?");

$resultPRE->bindValue(1, $IdMed, PDO::PARAM_INT);
$resultPRE->bindValue(2, $IdMed, PDO::PARAM_INT);
$resultPRE->bindValue(3, $IdMed, PDO::PARAM_INT);
$resultPRE->bindValue(4, $IdMed, PDO::PARAM_INT);
$resultPRE->bindValue(5, '%'.$queUsu.'%', PDO::PARAM_STR);
$resultPRE->bindValue(6, '%'.$queUsu.'%', PDO::PARAM_STR);
$resultPRE->bindValue(7, '%'.$queUsu.'%', PDO::PARAM_STR);

$resultPRE->execute();


//Start of code for storing the identif of members

$userIds = array();


while ($rowPRE = $resultPRE->fetch(PDO::FETCH_ASSOC))
{
   
     array_push($userIds,$rowPRE['Identif']);

}
//End of code for storing the identif of members



//Start of code for checking whether the patient has the search location
$userIdsSatisfyLocation = array();
foreach($userIds as &$value)
{

          $query = "select IdPatient from basicemrdata where IdPatient = ? and City like ?";
          $locationSearch = $con->prepare($query);
          $locationSearch->bindValue(1,$value,PDO::PARAM_INT);
          $locationSearch->bindValue(2,'%'.$location.'%',PDO::PARAM_STR);
          $locationSearch->execute();
          $result = $locationSearch->fetch(PDO::FETCH_ASSOC);
    
          if($result['IdPatient'])
          {
             array_push($userIdsSatisfyLocation,$result['IdPatient']);
             
          }
    
    
}



//End of code for checking whether the patient has the search location


foreach($userIdsSatisfyLocation as $value)
{
 
          $query = "select * from usuarios where Identif = ?";
          $userSearch = $con->prepare($query);
          $userSearch->bindValue(1,$value,PDO::PARAM_INT); 
          $userSearch->execute();
    
        
                    while ($rowPRE = $userSearch->fetch(PDO::FETCH_ASSOC)) {

                        $PassPac = $rowPRE['IdUsRESERV'];
                            if ($PassPac > ' ')
                            {
                                $medalla ='<i class="fam-award-star-gold-3"></i>';
                            }
                            else
                            {
                            $medalla ='';
                            }
                            // Ver si el paciente tiene cuenta propia (viendo si tiene password)

                                $current_encoding = mb_detect_encoding($rowPRE['Name'], 'auto');
                                $UserName = iconv($current_encoding, 'ISO-8859-1', $rowPRE['Name']);

                                $current_encoding = mb_detect_encoding($rowPRE['Surname'], 'auto');
                                $UserSurname = iconv($current_encoding, 'ISO-8859-1', $rowPRE['Surname']); 

                            $countPIN=0;
                            $countactualPIN=0;
                            $idEncontrado=$rowPRE['Identif'];
                            $TotalPIN= $con->prepare("SELECT count(*) as numreports FROM lifepin WHERE (markfordelete IS NULL or markfordelete=0) and IdUsu = ? and emr_old=0 ");
                            $TotalPIN->bindValue(1, $idEncontrado, PDO::PARAM_INT);
                            $TotalPIN->execute();


                            if($rowNUM=$TotalPIN->fetch(PDO::FETCH_ASSOC)){
                                   $countPIN=$rowNUM['numreports'];
                            }



                            $dluPIN=$con->prepare("select * from monimed.doctorslinkusers where IdUs=? and IdMED=?");
                            $dluPIN->bindValue(1, $idEncontrado, PDO::PARAM_INT);
                            $dluPIN->bindValue(2, $IdMed, PDO::PARAM_INT);



                            $result = $dluPIN->execute();
                            $dluqquery='';
                            $num=$dluPIN->rowCount();

                                        //echo "report".$num;    

                                        $i=0;
                                        if($num>0){

                                            while($rowdlu = $dluPIN->fetch(PDO::FETCH_ASSOC)){
                                                    $dluqquery='';

                                                    if($rowdlu['IdPIN']==null){

                                                    $dluqquery="UNION(select lp.* from monimed.lifepin lp INNER JOIN (select IdMED from monimed.doctorslinkusers where IdUs=? and IdMED=? and IdPIN IS NULL) dlu where dlu.IdMED=lp.IdMED and lp.IdUsu=?)";
                                                    //break;

                                                    }else{

                                                    if($rowdlu['IdPIN']!=null){

                                                        $dluqquery="UNION(select lp.* from monimed.lifepin lp INNER JOIN (select * from monimed.doctorslinkusers where IdUs=? and IdMED=?) dlu where dlu.IdPIN=lp.IdPin and lp.IdUsu=?)";
                                                        //break;
                                                        //$i++;
                                                    }

                                                    }

                                            }
                                        }

                           //echo $dluqquery;

                            $loadquery="(select LP.* from monimed.lifepin LP INNER JOIN ((select A.idDoctor from monimed.doctorsgroups A INNER JOIN (select idGroup from monimed.doctorsgroups where idDoctor=?) B where B.idGroup=A.idGroup) UNION (select Id from monimed.doctors where Id=?) UNION (select IdMED from monimed.doctorslinkdoctors where IdMED2=? and IdPac=?)) AB where LP.IdMed=AB.idDoctor and IdUsu=? and (LP.markfordelete=0 or LP.markfordelete is null) and (LP.IsPrivate=0 or LP.IsPrivate is null) and NOT (LP.Tipo IN (select Id from monimed.tipopin where Agrup=9) and LP.IdMED!=?) and LP.emr_old=0)UNION(select * from monimed.lifepin where IdMED=? and IdUsu=? and (markfordelete=0 or markfordelete is null) and emr_old=0)".$dluqquery;

                            //echo $loadquery;

                             $viewablePIN=$con->prepare($loadquery);

                             /*$viewablePIN=$con->prepare("select q.* from (((select LP.* from monimed.lifepin LP INNER JOIN ((select A.idDoctor from monimed.doctorsgroups A INNER JOIN (select idGroup from monimed.doctorsgroups where idDoctor=?) B where B.idGroup=A.idGroup) UNION (select Id from monimed.doctors where Id=?) UNION (select IdMED from monimed.doctorslinkdoctors where IdMED2=? and IdPac=?)) AB where LP.IdMed=AB.idDoctor and IdUsu=? and (LP.markfordelete=0 or LP.markfordelete is null) and (LP.IsPrivate=0 or LP.IsPrivate is null) and NOT (LP.Tipo IN (select Id from monimed.tipopin where Agrup=9) and LP.IdMED!=?) and LP.emr_old=0)UNION(select * from monimed.lifepin where IdMED=? and IdUsu=? and (markfordelete=0 or markfordelete is null) and emr_old=0)UNION".$dluqquery.")) q");*/


                                $viewablePIN->bindValue(1, $IdMed, PDO::PARAM_INT);
                                $viewablePIN->bindValue(2, $IdMed, PDO::PARAM_INT);
                                $viewablePIN->bindValue(3, $IdMed, PDO::PARAM_INT);
                                $viewablePIN->bindValue(4, $idEncontrado, PDO::PARAM_INT);
                                $viewablePIN->bindValue(5, $idEncontrado, PDO::PARAM_INT);
                                $viewablePIN->bindValue(6, $IdMed, PDO::PARAM_INT);
                                $viewablePIN->bindValue(7, $IdMed, PDO::PARAM_INT);
                                $viewablePIN->bindValue(8, $idEncontrado, PDO::PARAM_INT);
                                $viewablePIN->bindValue(9, $idEncontrado, PDO::PARAM_INT);
                                $viewablePIN->bindValue(10, $IdMed, PDO::PARAM_INT);
                                $viewablePIN->bindValue(11, $idEncontrado, PDO::PARAM_INT);
                                //$viewablePIN->bindValue(12, $idEncontrado, PDO::PARAM_INT);
                                $viewablePIN->execute();


                            $hasfullaccess=0;
                            if($viewablePIN->fetch(PDO::FETCH_ASSOC)){
                                   $countactualPIN=$viewablePIN->rowCount();
                                   $hasfullaccess=1;
                            }


                               // echo 'countPIN '.$countactualPIN;



                                if($hasfullaccess==1){
                                          $viewable=$con->prepare("Select count(*) as patreport from monimed.lifepin where (IdMed IS NULL or IdMed=0 or IdMed=?) and (markfordelete IS NULL or markfordelete=0) and IdUsu=?");
                                          $viewable->bindValue(1, $idEncontrado, PDO::PARAM_INT);
                                          $viewable->bindValue(2, $idEncontrado, PDO::PARAM_INT);
                                          $viewable->execute();

                                            if($row=$viewable->fetch(PDO::FETCH_ASSOC))
                                                $numPIN=$row['patreport'];

                                      // echo '$numPIN '.$numPIN;
                                        $countactualPIN+=$numPIN;          //If Idpin is null the doctor has full access to the patients

                                }

                            /*else if($hasfullaccess==0){


                            //if($countactualPIN==0){
                            $viewable=$con->prepare("Select IdPIN from doctorslinkusers where IdMed=? and IdUs=? and IdPIN IS NOT NULL");
                                 //$viewable=$con->prepare("Select IdPIN from doctorslinkusers where IdMed=? and IdUs=?");
                                 $viewable->bindValue(1, $IdMed, PDO::PARAM_INT);
                                 $viewable->bindValue(2, $idEncontrado, PDO::PARAM_INT);
                                 $viewable->execute();


                                        $i=0;
                                        $num=$viewable->rowCount();
                                        if($num>0){

                                            while($row = $viewable->fetch(PDO::FETCH_ASSOC)){

                                                  $docuserIdPin[$i]=$row['IdPIN'];
                                                  $hasfullaccess=2;
                                                  $i++;    

                                            }
                                        }


                                    $countactualPIN+=$i;             //The number of IdPINs in the DLU table for this doctor
                                }*/


                           $NReports=$countactualPIN."/".$countPIN;

                            echo '<tr class="CFILA" id="'.$rowPRE['Identif'].'"><td>'.$rowPRE['IdUsFIXED'].'</td>';
                            echo '<td>'.$UserName.'</td>';
                            echo '<td style="font-weight:bold;"><a href="#"><i class="fam-resultset-next"></i>'.$UserSurname.'  '.$medalla.'</a></td>';
                            echo '<td>'.$rowPRE['email'].'</td>';
                            echo '<td>'.$NReports.'</td></tr>';




                    }
    
}
                    echo '</tbody></table>';    

//echo $Group;
    

?>