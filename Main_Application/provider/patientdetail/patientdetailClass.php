<?php 
class patientdetailClass extends userConstructClass{
    
    public $telemed_on = false;
    public $telemed_value = 0;
    public $open_modal = 0;
    public $NombreEnt = "";
    public $PasswordEnt = "";
    public $Acceso = "";
    public $BOTH_SESSION = 0;
    public $IdUsu = '';
    public $IdMed = '';
    public $MedID = '';
    public $privilege = '';
    public $IdMEDEmail= "";
    public $IdMEDName = "";
    public $IdMEDSurname = "";
    public $tabToOpen = 0;
    public $appointment_id = 0;
    public $pass = "";
    public $USERID = "";
    public $npi = 0;
    public $dea = 0;
    public $startTime = '';
    public $num_multireferral = 0;
    public $IdUsFIXED = "";
    public $IdUsFIXEDNAME = "";
    public $CANAL = '';
    public $referral_id = 0;
    public $otherdoc = 0;
    public $otherdocname='';
    public $otherdocSurename='';
    public $MedUserName = '';
    public $doctor_tracking_cost = 0;
    public $doctor_consulting_cost = 0;
    public $email = '';
    public $IdUsPassword = '';
    public $GrantAccess = '';
    public $doc_id = 0;
    public $mem_id = 0;
    public $credits = 0;
    public $MedLogo;
    public $referralselection = 0;
    public $referral_stage = 1;
    public $attachments_dld = 0;
    public $showreferralsectionarray=array();
    public $otherdocarray= array();
    public $otherdocnamearray=array();
    public $otherdocSurnamearray=array();
    public $otherdoctoremailarray=array();
    public $referral_id_array=array();
    //Added for new referral type
    public $referral_type_array=array();
    public $referral_stage_array=array();
    public $fechaconfirm_array=array();
    public $attachments_dld_array=array();
    public $estado_ref=array();
    public $doc_id_array=array();

    public $referralcolors=array("#0B701B", "#FC9856", "#4673D1", "#4673D1", "#725AF1", "#ECBE78", "#CDA7E2", "#0B701B", "#FC9856", "#4673D1", "#4673D1");   
    
    
    function __construct(){
		parent::__construct();
        
        $BOTH_SESSION = $_SESSION['MEDID'];
        $IdUsu = $_GET['IdUsu'];
        
        if (isset($_SESSION['CustomLook']) && $_SESSION['CustomLook']=="COL") { 
            echo '<link href="../../../../css/styleCol.css" rel="stylesheet">';
        }
        
        /*if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
            // last request was more than 30 minutes ago
            session_unset();     // unset $_SESSION variable for the run-time 
            session_destroy();   // destroy session data in storage
            echo "<META http-equiv='refresh' content='0;URL=index.html'>";
        }
        
        $_SESSION['LAST_ACTIVITY'] = time();*/ // update last activity time stamp

        if(isset($_GET['TELEMED']))
        {
            $this->telemed_on = true;

            if (isset($_GET['TELEMED']) && $_GET['TELEMED']==1) $this->telemed_value = 1;
            else if (isset($_GET['TELEMED']) && $_GET['TELEMED']==2) $this->telemed_value = 2;  
        }
        
        if(isset($_GET['OPENMODAL'])) $this->open_modal = $_GET['OPENMODAL'];
        if(isset($_SESSION['Nombre'])) $this->NombreEnt = $_SESSION['Nombre'];

       
        if(isset($_GET['Acceso'])) $this->Acceso = $_GET['Acceso'];
        else if(isset($_SESSION['Acceso'])) $this->Acceso = $_SESSION['Acceso'];
     
        if(isset($this->BOTH_SESSION) && isset($_SESSION['Previlege']) /*&& $BOTH_SESSION != $IdUsu*/) 
        {

            $this->IdMed = $this->BOTH_SESSION;
            $this->MedID = $IdMed;
            $this->privilege = $_SESSION['Previlege'];
        }
        else
        {
            $this->IdMed = -1;
            $this->MedID = -1;
            $this->IdMEDEmail= "";
            $this->IdMEDName = "";
            $this->IdMEDSurname = "";
            $this->tabToOpen=0;
            $this->appointment_id=0;
        }
        
        if(isset($_SESSION['decrypt']))
        {
            $this->pass = $_SESSION['decrypt'];	
        }

        if ($this->Acceso != '23432')
        {
            $exit_display = new displayExitClass();
            $exit_display->displayFunction(1);
            die;
        }

        $result = $this->con->prepare("SELECT * FROM usuarios where Identif=?");
        $result->bindValue(1, $IdUsu, PDO::PARAM_INT);
        $result->execute();

        $count = $result->rowCount();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $success ='NO';
        if($count==1){
            $success ='SI';
            $this->USERID = $row['Identif'];
        //	$MedUserEmail= $row['IdMEDEmail'];
            $this->MedUserName = $row['Name'];
            $this->MedUserSurname = $row['Surname'];
            $this->IdUsFIXED = $row['IdUsFIXED'];
            $this->IdUsFIXEDNAME = $row['IdUsFIXEDNAME'];
            $this->IdUsRESERV = $row['IdUsRESERV'];
            $this->IdUsPassword = $row['IdUsRESERV'];
            $this->email = $row['email'];
            $this->GrantAccess = $row['GrantAccess'];

        //	$MedUserLogo = $row['ImageLogo'];

        }
        else
        {
            $exit_display = new displayExitClass();
            $exit_display->displayFunction(2);
        die;
        }

        $this->doc_id = $this->IdMed;
        $this->mem_id = $this->IdUsu;

        $checker = new checkPatientsClass();
        $checker->setters($mem_id, $doc_id);
        $checker->checker();
        
        
        if($row['GrantAccess'] != 'HTI-COL' && $row['GrantAccess'] != 'HTI-PR' && $row['GrantAccess'] != 'HTI-ECU' && $row['GrantAccess'] != 'HTI-MEX' && $row['GrantAccess'] != 'HTI-SPA' && $row['GrantAccess'] != 'HTI-CR'){
            if($checker->status1 != 'true' && $checker->status2 != 'true' && $checker->status3 != 'true' && $checker->status4 != 'true' && $_GET['TELEMED'] != 2 && $_GET['TELEMED'] != 1){
                $exit_display = new displayExitClass();
                $exit_display->displayFunction(5);
                die();
            }else if($checker->status5 == 'false'){
                $exit_display = new displayExitClass();
                $exit_display->displayFunction(-1);
                die();
            }
        }
        
        if($this->MedID >= 0)
        {
            $result = $this->con->prepare("SELECT * FROM doctors where id=?");
            $result->bindValue(1, $IdMed, PDO::PARAM_INT);
            $result->execute();

            $count = $result->rowCount();
            $row = $result->fetch(PDO::FETCH_ASSOC);
            //$success ='NO';
            if($count==1){
                //$success ='SI';
                //$MedID = $row['id'];
                $this->IdMEDEmail= $row['IdMEDEmail'];
                $this->IdMEDName = $row['Name'];
                $this->IdMEDSurname = $row['Surname'];
                $this->MedLogo = $row['ImageLogo'];
                $this->npi = $row['npi'];
                $this->dea = $row['dea'];
                $this->credits = $row['credits'];
                $this->doctor_tracking_cost = $row['tracking_price'];
                $this->doctor_consult_cost = $row['consult_price'];
            }

            
            $app_res = $this->con->prepare("SELECT * FROM appointments WHERE med_id=?");
            $app_res->bindValue(1, $this->IdMed, PDO::PARAM_INT);
            $app_res->execute();

            if($app_row = $app_res->fetch(PDO::FETCH_ASSOC))
            {
                $this->appointment_id = $app_row['id'];
            }
        }


        //Global variable for blind reports.
        //$blindReportId=array();
        //CreaTimeline($IdUsu,$IdMed,$pass);


        $fechaconfirm=0;
        $i=0;

        if($this->MedID >= 0) {
            //$sql="SELECT * FROM doctorslinkdoctors where Idpac ='$IdUsu' and (IdMED='$IdMed' or IdMED2='$IdMed') and estado=2 ";
            $sql = $this->con->prepare("SELECT * FROM doctorslinkdoctors where Idpac =? and (IdMED=? or IdMED2=?) ");
            $sql->bindValue(1, $this->IdUsu, PDO::PARAM_INT);
            $sql->bindValue(2, $this->IdMed, PDO::PARAM_INT);
            $sql->bindValue(3, $this->IdMed, PDO::PARAM_INT);
            $q = $sql->execute();

            if($q){
                $cnt=$sql->rowCount();
                if($cnt>=1){
                    $this->num_multireferral=$cnt;
                    $this->multireferral=1;
                    while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                        if($row['estado']==1)
                            $this->estado_ref[$i]=$row['estado'];				
                        else if($row['estado']==2)
                            $this->estado_ref[$i]=$row['estado'];
                        $this->referral_type_array[$i]=$row['Type'];
                            if($row['IdMED2']==$IdMed){
                                $this->otherdocarray[$i]=$row['IdMED'];
                                $this->showreferralsectionarray[$i]=1;
                                $this->referral_id_array[$i]=$row['id'];

                            }else if($row['IdMED']==$IdMed) {
                                $this->otherdocarray[$i]=$row['IdMED2'];
                                $this->showreferralsectionarray[$i]=2;
                                $this->referral_id_array[$i]=$row['id'];			
                            }
                            //echo "******************************".$otherdocarray[$i];
                            $getname = $this->con->prepare("select Name,Surname,IdMEDEmail from doctors where id=?");
                            $getname->bindValue(1, $this->otherdocarray[$i], PDO::PARAM_INT);
                            $getname->execute();

                            $row11 = $getname->fetch(PDO::FETCH_ASSOC);
                            $this->otherdocnamearray[$i] = $row11['Name'];				
                            $this->otherdocSurnamearray[$i] = $row11['Surname'];

                            if($this->otherdocnamearray[$i]=='' and $this->otherdocSurnamearray[$i]=='')
                                $this->otherdoctoremailarray[$i]=$row11['IdMEDEmail'];
                        
                            $this->fechaconfirm_array[$i]=$row['FechaConfirm'];
                            $this->attachments_dld_array[$i]=$row['attachments'];
                            $i++;
                        }
                    }
                }

            //Update the referrral stages
            if($this->showreferralsection!=0){
            //echo "".$referral_id."<br>";
            $this->doc_id=0;
            if($this->showreferralsection==1){
                $this->doc_id = $this->IdMed;
            }else{
                $this->doc_id = $this->otherdoc;
            }
            $getstage = $this->con->prepare("select stage from referral_stage where referral_id=?");
            $getstage->bindValue(1, $this->referral_id, PDO::PARAM_INT);
            $getstage->execute();

            $row13 = $getstage->fetch(PDO::FETCH_ASSOC);
            $this->referral_stage=$row13['stage'];
            if($this->referral_stage==1){
            //Code for appointment from events table

            $getschedule=$this->con->prepare("select * from events where userid=? and patient=? and start>?");
            $getschedule->bindValue(1, $this->doc_id, PDO::PARAM_INT);
            $getschedule->bindValue(2, $this->USERID, PDO::PARAM_INT);
            $getschedule->bindValue(3, $fechaconfirm, PDO::PARAM_STR);
            $getschedule->execute();

            $cnt = $getschedule->rowCount();

            if($cnt>=1){
            $result22=$this->con->prepare("update referral_stage set stage=2 where referral_id=?");
            $result22->bindValue(1, $this->referral_id, PDO::PARAM_INT);
            $result22->execute();

            $this->referral_stage=2;
            Push_notification($this->IdMed,"Referral Appointment Stage completed!");
            Push_notification($this->otherdoc,"Referral Appointment Stage completed!");

            }

            }
            if($this->referral_stage==2){
                //Code for information review from bpinview
                $reportviewed=false;
                if($this->attachments_dld!=0){
                    $report_id=explode(" ",$this->attachments_dld);
                    $cntt=count($this->report_id);
                    $i=0;
                    //Remember to add the check for date of the reports viewed. It should always be greater than appointment schedule date
                    while($cntt>0){
                        $getinfo = $this->con->prepare("select id from bpinview USE INDEX(I1) where viewIdUser=? and viewIdMed=? and content='Report Viewed' and IDPIN=?");
                        $getinfo->bindValue(1, $this->USERID, PDO::PARAM_INT);
                        $getinfo->bindValue(2, $this->doc_id, PDO::PARAM_INT);
                        $getinfo->bindValue(3, $this->report_id[$i], PDO::PARAM_INT);
                        $getinfo->execute();


                        $cnt_info = $getinfo->rowCount();
                        if($cnt_info)
                            $reportviewed=true;
                        else
                            $reportviewed=false;
                        $i++;
                        $cntt--;
                    }
                    /*$getinfo = mysql_query("select id from bpinview where viewIdUser='$USERID' and viewIdMed='$doc_id' and content='Report Viewed'");
                    $cnt_info=mysql_num_rows($getinfo);*/
                    //echo "".$cnt_info."<br>";
                    if($reportviewed)
                    {
                        $result33 = $this->con->prepare("update referral_stage set stage=3 where referral_id=?");
                        $result33->bindValue(1, $this->referral_id, PDO::PARAM_INT);
                        $result33->execute();

                        $referral_stage=3;
                        Push_notification($this->IdMed,"Referral report view stage completed!");
                        Push_notification($this->otherdoc,"Referral report view stage completed!");
                    }
                }else {
                    $getinfo = $this->con->prepare("select id from bpinview USE INDEX(I1) where viewIdUser=? and viewIdMed=? and content='Report Viewed'");
                    $getinfo->bindValue(1, $this->USERID, PDO::PARAM_INT);
                    $getinfo->bindValue(2, $this->doc_id, PDO::PARAM_INT);
                    $getinfo->execute();

                    $cnt_info = $getinfo->rowCount();
                    //echo "".$cnt_info."<br>";
                    if($cnt_info>3)
                    {
                    $result44 = $this->con->prepare("update referral_stage set stage=3 where referral_id=?");
                    $result44->bindValue(1, $this->referral_id, PDO::PARAM_INT);
                    $result44->execute();

                    $this->referral_stage=3;
                    Push_notification($this->IdMed,"Referral report view stage completed!");
                    Push_notification($this->otherdoc,"Referral report view stage completed!");
                    }

                }

            }

            }else{

            //check if multireferral is enabled
            if($this->multireferral==1){

                for($i=0; $i < $this->num_multireferral; $i++){

                //Add code for automatically handling the comments stage 3 for new referral

                
                if($this->showreferralsectionarray[$i]==1){
                $this->doc_id_array[$i]=$IdMed;
                }else{
                $this->doc_id_array[$i]=$otherdocarray[$i];
                }
                $getstage = $this->con->prepare("select stage from referral_stage where referral_id=?");
                $getstage->bindValue(1, $this->referral_id_array[$i], PDO::PARAM_INT);
                $getstage->execute();

                $row13 = $getstage->fetch(PDO::FETCH_ASSOC);
                $this->referral_stage_array[$i]=$row13['stage'];
                if($this->referral_stage_array[$i]==1){
                //Code for appointment from events table

                //Added changes. Work from here.#task170
                $getschedule=$this->con->prepare("select * from events where userid=? and patient=? and start>?");
                $getschedule->bindValue(1, $this->doc_id_array[$i], PDO::PARAM_INT);
                $getschedule->bindValue(2, $this->USERID, PDO::PARAM_INT);
                $getschedule->bindValue(3, $this->fechaconfirm_array[$i], PDO::PARAM_STR);
                $getschedule->execute();

                $cnt = $getschedule->rowCount();

                if($cnt>=1){
                $quick_query=$this->con->prepare("update referral_stage set stage=2 where referral_id=?");
                $quick_query->bindValue(1, $this->referral_id_array[$i], PDO::PARAM_INT);
                $quick_query->execute();

                $this->referral_stage_array[$i]=2;
                Push_notification($this->IdMed,"Referral Appointment Stage completed!",2);
                Push_notification($this->otherdocarray[$i],"Referral Appointment Stage completed!",2);

                }

                }
                if($this->referral_stage_array[$i]==2){
                    //Code for information review from bpinview
                    $reportviewed=false;
                    if($this->attachments_dld_array[$i]!=0){
                        $report_id=explode(" ",$this->attachments_dld_array[$i]);
                        $cntt=count($this->report_id);
                        $j=0;
                        //Remember to add the check for date of the reports viewed. It should always be greater than appointment schedule date
                        while($cntt>0){
                        $getinfo = $this->con->prepare("select id from bpinview USE INDEX(I1) where viewIdUser=? and viewIdMed=? and content='Report Viewed' and IDPIN=?");
                        $getinfo->bindValue(1, $this->USERID, PDO::PARAM_INT);
                        $getinfo->bindValue(2, $this->doc_id_array[$i], PDO::PARAM_INT);
                        $getinfo->bindValue(3, $this->report_id[$j], PDO::PARAM_INT);
                        $getinfo->execute();

                        $cnt_info = $getinfo->rowCount();
                        if($cnt_info)
                            $reportviewed=true;
                        else
                            $reportviewed=false;
                        $j++;
                        $cntt--;
                        }
                        /*$getinfo = mysql_query("select id from bpinview where viewIdUser='$USERID' and viewIdMed='$doc_id' and content='Report Viewed'");
                        $cnt_info=mysql_num_rows($getinfo);*/
                        //echo "".$cnt_info."<br>";
                        if($reportviewed)
                        {
                        $quick_query=$this->con->prepare("update referral_stage set stage=3 where referral_id=?");
                        $quick_query->bindValue(1, $this->referral_id_array[$i], PDO::PARAM_INT);
                        $quick_query->execute();

                        $this->referral_stage_array[$i]=3;
                        Push_notification($this->IdMed,"Referral report view stage completed!",2);
                        Push_notification($this->otherdocarray[$i],"Referral report view stage completed!",2);
                        }
                    }else {
                        $getinfo = $this->con->prepare("select id from bpinview USE INDEX(I1) where viewIdUser=? and viewIdMed=? and content='Report Viewed'");
                        $getinfo->bindValue(1, $this->USERID, PDO::PARAM_INT);
                        $getinfo->bindValue(1, $this->doc_id_array[$i], PDO::PARAM_INT);
                        $getinfo->execute();

                        $cnt_info=$getinfo->rowCount();
                        //echo "".$cnt_info."<br>";
                        if($cnt_info>3)
                        {
                        $quick_query=$this->con->prepare("update referral_stage set stage=3 where referral_id=?");
                        $quick_query->bindValue(1, $this->referral_id_array[$i], PDO::PARAM_INT);
                        $quick_query->execute();

                        $this->referral_stage_array[$i]=3;
                        Push_notification($this->IdMed,"Referral report view stage completed!",2);
                        Push_notification($this->otherdocarray[$i],"Referral report view stage completed!",2);
                        }

                    }

                }



                }	

             } }

            $enc_result = $this->con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
            $enc_result->execute();
            $row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);
            $enc_pass=$row_enc['pass'];
            //BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM BLOCKS");
            //$result = mysql_query("SELECT * FROM LifePin");


            //Code to decide tab to open by default
            //0:ALL
            //1:Highlighted

            $this->tabToOpen=0;
            if($this->multireferral>0)
            {
                $highlightedReports = array();
                $count=0;
                $query = $this->con->prepare("SELECT attachments FROM doctorslinkdoctors where Idpac =? and (IdMED=? or IdMED2=?) ");
                $query->bindValue(1, $this->IdUsu, PDO::PARAM_INT);
                $query->bindValue(2, $this->IdMed, PDO::PARAM_INT);
                $query->bindValue(3, $this->IdMed, PDO::PARAM_INT);
                $result=$query->execute();

                while($row = $query->fetch(PDO::FETCH_ASSOC))
                {
                    $attachmentString = $row['attachments'];
                    $repID = explode(" ",$attachmentString);
                    for($i=0;$i<count($repID);$i++)
                    {
                        if(strlen($repID[$i])>0 && $repID[$i]!=0)
                        {
                            $highlightedReports[$count] = $repID[$i];		
                            $count++;
                        }
                    }

                }
                if($count>0)
                {
                    $this->tabToOpen=1;
                }
                else
                {
                    $this->tabToOpen=0;
                }
            }

        }

        //DATA FOR TIMER START
        $this->startTime = 0;
        if(isset($this->BOTH_SESSION))
        {
            $this->doctorId = $this->BOTH_SESSION;


            $DateTime = 0;
            date_default_timezone_set('America/Chicago');

            $sql=$this->con->prepare("SELECT DateTime FROM ".$this->dbname.".consults where Doctor=".$this->doctorId." ORDER BY DateTime DESC LIMIT 1;");
            $sql->execute();
            $timeZone = date_default_timezone_get();

            $resultRow = $sql->fetch(PDO::FETCH_ASSOC);
            $lastDate = $resultRow['DateTime'];


            //for testing DEBUG
            //$lastDate = '2014-08-18 11:50:13';

            //if you found a consultation
            if ($lastDate) {
                $this->startTime = time()-strtotime($lastDate);
                if ($this->startTime < 0) $this->startTime = 0;       
            } 
        }
        
        if($this->GrantAccess == 'HTI-RIVA'){
            echo "<a href='UserDashboardHTI.php' style='background-image:url(images/RivaCare_Logo.png);display:block;width:325px;height:42px;float:left;'></a>";
        }elseif($this->GrantAccess == 'HTI-24X7'){
            echo "<a href='http://24x7hellodoctor.com/' style='background-image:url(http://24x7hellodoctor.com/img/logo-24x7-hellodoctor.jpg); background-size: 250px 42px;background-repeat: no-repeat;display:block;width:255px;height:42px;float:left;'></a>";
        }elseif($this->GrantAccess == 'HTI-COL'){
            echo '<a href="index-col.html" class="logo"><h1>Health2me</h1></a>';
        }else{
            echo '<a href="index.html" class="logo"><h1>Health2me</h1></a>';
        }  
    }
}
?>