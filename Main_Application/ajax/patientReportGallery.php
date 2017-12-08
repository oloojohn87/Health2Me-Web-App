<?php
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$NombreEnt = $_GET['Nombre'];
$PasswordEnt = $_GET['Password'];
//$MEDID = $_GET['MEDID'];
$Acceso = $_GET['Acceso'];
$IdUsu = $_GET['IdUsu'];

$IdMed=0;

if(isset($_GET['Idmed'])){
	$IdMed=$_GET['Idmed'];
}
 
if ($Acceso != '23432')
{
echo "Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Inmers HomePage</a></h2>";
die;
}
				
// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		

$result = $con->prepare("SELECT * FROM usuarios where Identif=?");
$result->bindValue(1, $IdUsu, PDO::PARAM_INT);
$result->execute();

$count=$result->rowCount();
$row = $result->fetch(PDO::FETCH_ASSOC);
$success ='NO';
if($count==1){
	$success ='SI';
	$USERID = $row['Identif'];
//	$MedUserEmail= $row['IdMEDEmail'];
	$MedUserName = $row['Name'];
	$MedUserSurname = $row['Surname'];
	$IdUsFIXED = $row['IdUsFIXED'];
	$IdUsFIXEDNAME = $row['IdUsFIXEDNAME'];
	$IdUsRESERV = $row['IdUsRESERV'];
	$email = $row['email'];

//	$MedUserLogo = $row['ImageLogo'];
	
}
else
{
echo "MEDICAL USER NOT VALID. Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Inmers HomePage</a></h2>";
die;
}

//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks");
$result = $con->prepare("SELECT * FROM lifepin");
$result->execute();
?>
<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>health2.me Patient Detail</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    
    <link rel="stylesheet" href="css/jquery-ui-1.8.16.custom.css" media="screen"  />
    <link rel="stylesheet" href="css/fullcalendar.css" media="screen"  />
    <link rel="stylesheet" href="css/chosen.css" media="screen"  />
    <link rel="stylesheet" href="css/datepicker.css" >
    <link rel="stylesheet" href="css/colorpicker.css">
    <link rel="stylesheet" href="css/glisse.css?1.css">
    <link rel="stylesheet" href="css/jquery.jgrowl.css">
    <link rel="stylesheet" href="js/elfinder/css/elfinder.css" media="screen" />
    <link rel="stylesheet" href="css/jquery.tagsinput.css" />
    <link rel="stylesheet" href="css/demo_table.css" >
    <link rel="stylesheet" href="css/jquery.jscrollpane.css" >
    <link rel="stylesheet" href="css/validationEngine.jquery.css">
    <link rel="stylesheet" href="css/jquery.stepy.css" />
    
	<link rel="stylesheet" href="css/icon/font-awesome.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
    <link rel="stylesheet" href="css/jvInmers.css">



    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/icons/favicon.ico">
    <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-37863944-1']);
  _gaq.push(['_setDomainName', 'health2.me']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>    
  </head>

  <body onload="$('.note').trigger('click');">

			<input type="hidden" id="MEDID" Value="<?php echo $IdMed; ?>">	
	    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $IdMEDEmail; ?>">	
	    	<input type="hidden" id="IdMEDName" Value="<?php echo $IdMEDName; ?>">	
	    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $IdMEDSurname; ?>">	
	    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedLogo; ?>">	
	     	<!-- <input type="hidden" id="USERDID" Value="<?php echo $USERID; ?>">	-->
	<!--Header Start-->
	<div class="header" >
    		
           <a href="index.html" class="logo"><h1>Health2me</h1></a>
           
           <div class="pull-right">
           
           <!--Notifications Start-->  
           <div class="notifications-head">
           
           <!--Messages Start-->
           <div class="btn-group m_left hide-mobile" >
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
    	     	<span class="notification">531</span><span class="triangle-1"></span><i class="icon-comments"></i><span class="caret"></span>
            </a>
            <div class="dropdown-menu">
            
              <span class="triangle-2"></span>
              <div class="ichat">
               <div class="ichat-messages">
               	<div class="ichat-title">
                  <div class="pull-left">New Messages</div>
                  <div class="pull-right"><span>Update 4*</span></div>
                  <div class="clear"></div>
                </div>
                
                <div class="imessage">
                  <div class="iavatar"><img src="images/doctors/michaelrlICON.jpg" alt=""></div>
                  <div class="imes">
                  	<div class="iauthor">Dr. Michael rl</div>
                    <div class="itext">All right. Thank you.</div>
                  </div>
                  <div class="idelete"><a href="#"><span><i class="icon-remove"></i></span></a></div>
                  <div class="clear"></div>
                </div>
                <div class="imessage">
                  <div class="iavatar"><img src="images/users/6.jpg" alt=""></div>
                  <div class="imes">
                  	<div class="iauthor">Emma Clark</div>
                    <div class="itext">Can I help you?</div>
                  </div>
                  <div class="idelete"><a href="#"><span><i class="icon-remove"></i></span></a></div>
                  <div class="clear"></div>
                </div>
                <div class="imessage">
                  <div class="iavatar"><img src="images/users/2.jpg" alt=""></div>
                  <div class="imes">
                  	<div class="iauthor">Blake Washington</div>
                    <div class="itext">Can I help you?</div>
                  </div>
                  <div class="idelete"><a href="#"><span><i class="icon-remove"></i></span></a></div>
                  <div class="clear"></div>
                </div>
                
                <div class="ichat-link"><a href="#">InBox</a> <a href="#">OutBox</a> <a href="#">Spam</a> <a href="#">Trash</a>
                <div class="clear"></div>
                </div>
                
                </div>
                <a href="#" class="iview">View all</a><a href="#" class="imark">Mark all read</a>
               
              </div>
            
            </div>
          </div>
          <!--Messages END--> 
          
          <!--Recent Activity Start-->
           <div class="btn-group pull-left hide-mobile" >
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
    	     	<span class="notification">77</span><span class="triangle-1"></span><i class="icon-bell"></i><span class="caret"></span>
            </a>
            <div class="dropdown-menu">
            
              <span class="triangle-2"></span>
              <div class="ichat">
               <div class="ichat-messages">
               	<div class="ichat-title">
                  <div class="pull-left">Recent Activity</div>
                  <div class="pull-right"><span>Update 14*</span></div>
                  <div class="clear"></div>
                </div>
                
                <div class="r_activity">
                <div class="imessage">
                  <div class="r_icon"><a href="#"><i class="icon-camera"></i></a></div>
                  <div class="r_info">
                  	<div class="r_name"><strong>Alan Cook</strong> a new photo</div>
                    <div class="r_text"><i class="icon-time"></i> 3 hours ago</div>
                    <div class="r_link"><a href="#">View...</a></div>
                  </div>
                  <div class="clear"></div>
                </div>
                <div class="imessage">
                  <div class="r_icon"><a href="#"><i class="icon-thumbs-up"></i></a></div>
                  <div class="r_info">
                  	<div class="r_name"><strong>Isaac Donaldson</strong> like: BMW E36</div>
                    <div class="r_text"><i class="icon-time"></i> 5 hours ago</div>
                    <div class="r_link"><a href="#">View...</a></div>
                  </div>
                  <div class="clear"></div>
                </div>
                <div class="imessage">
                  <div class="r_icon"><a href="#"><i class="icon-user"></i></a></div>
                  <div class="r_info">
                  	<div class="r_name">Registered new user</div>
                    <div class="r_text"><i class="icon-time"></i> 15th of May - 06:21</div>
                    <div class="r_link"><a href="#">View...</a></div>
                  </div>
                  <div class="clear"></div>
                </div>
                </div>
               
                
                </div>
                <a href="#" class="iview">View all</a><a href="#" class="imark">Mark all read</a>
               
              </div>
            
            </div>
          </div>
          <!--Recent Activity END--> 
            
          </div><!--Notifications END-->
            
           <!--Button User Start-->
		   <div class="btn-group pull-right" >
           
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
              <span class="name-user"><strong>Welcome</strong>, <?php echo $MedUserName.' '.$MedUserSurname; ?></span> 
             <?php 
             $hash = md5( strtolower( trim( $email ) ) );
             $avat = 'identicon.php?size=29&hash='.$hash;
			?>	
              <span class="avatar" style="background-color:WHITE;"><img src="<?php echo $avat; ?>" alt="" ></span> 
              <span class="caret"></span>
            </a>
            <div class="dropdown-menu" id="prof_dropdown">
            <div class="item_m"><span class="caret"></span></div>
            <ul class="clear_ul" >
              <li><a href="#"><i class="icon-globe"></i> Home</a></li>
              <li><a href="#"><i class="icon-comments"></i> Messages</a></li>
              <li><a href="#"><i class="icon-cog"></i> Settings</a></li>
              <li><a href="index.html"><i class="icon-off"></i> Sign Out</a></li>
            </ul>
            </div>
          </div>
          <!--Button User END-->  

          
          </div>
    </div>
    <!--Header END-->

  
    <!--Content Start-->
	<div id="content" style="padding-left:0px; background: #F9F9F9; ">
   
	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     
     <ul class="menu-speedbar">
         <li><a href="dashboard.php?Acceso=23432&Nombre=<?php echo $NombreEnt;?>&Password=<?php echo $PasswordEnt;?>&MEDID=<?php echo $MedID;?>">Dashboard</a></li>
    	 <li><a href="patients.php?Acceso=23432&Nombre=<?php echo $NombreEnt;?>&Password=<?php echo $PasswordEnt;?>&MEDID=<?php echo $MedID;?>">Patients</a></li>
         <li><a href="medicalConnections.php?Acceso=23432&Nombre=<?php echo $NombreEnt;?>&Password=<?php echo $PasswordEnt;?>&MEDID=<?php echo $MedID;?>">Connections</a></li>
         <li><a href="medicalConfiguration.php?Acceso=23432&Nombre=<?php echo $NombreEnt;?>&Password=<?php echo $PasswordEnt;?>&MEDID=<?php echo $MedID;?>">Configuration</a></li>
         <li><a href="">Doctors</a></li>
         <li><a href="">Centers</a></li>
         <li><a href="index.html" style="color:yellow;">Sign Out</a></li>

     </ul>

     
     </div>
     </div>
     <!--SpeedBar END-->

     <!--Search Start-->   
     <div class="search">
     <form class="search-form">
     	<input type="text" name="" value="" placeholder="Enter keywords">
     </form>
	 <div class="clear"></div>	
     </div>
     <!--Search END-->
     
     <?php             // AREA PRINCIPAL DE ASOCIACIÓN DE LA INFORMACIÓN DEL PACIENTE  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
     //$IdUsu=131;
     
     $sql=$con->prepare("SELECT * FROM usuarios where Identif =?");
	 $sql->bindValue(1, $IdUsu, PDO::PARAM_INT);
	 
     $q = $sql->execute();
     $row = $sql->fetch(PDO::FETCH_ASSOC);
     
     $Name = $row['Name'];
     $Surname = $row['Surname'];
     
     // Meter tipos en un Array
     $sql=$con->prepare("SELECT * FROM tipopin");
     $q = $sql->execute();
     
     $Tipo[0]='N/A';
     while($row=$sql->fetch(PDO::FETCH_ASSOC)){
     	$Tipo[$row['Id']]=$row['NombreEng'];
     }
     // Meter clases en un Array
     $Clase[0]='Episode';
     $Clase[1]='Check or Preventive';
     $Clase[2]='Isolated Report';
     $Clase[3]='Drug Data';
     
     
     ?>

     <!--CONTENT MAIN START-->

     
     <!--CONTENT MAIN START-->
          <div class="content">
          
               	  <!--- VENTANA MODAL  ---> 
   	  <!--<button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>-->
   	  <div id="header-modal" class="modal hide" style="display: none; height:470px;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <h4>Set Classification</h3>
         </div>
         
         <div class="modal-body" id="ContenidoModal" style="height:320px;">
             <div  id="RepoThumb" style="width:70px; float:right; -webkit-box-shadow: 3px 3px 14px rgba(25, 25, 25, 0.5); -moz-box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5); box-shadow:3px 3px 14px rgba(25, 25, 25, 0.5);"></div>
           <div class="ContenDinamico">
	         <p><H4>Class:  </H3>
	               <div class="formRight" stytle="width:50px;">
		               <select name="Clases" id="Clases" data-placeholder="Select Class (reason for this data ?)" class="chzn-select chosen_select" multiple tabindex="5" >
                            <option value=""></option>
                            <optgroup label="Episodes (user folder)">
                              <option>Epi 1</option>
                              <option>Epi 2</option>
                            <optgroup>
                            <optgroup label="Routine / Checks">
                              <option>Routine / Checks</option>
                            </optgroup>
                            <optgroup label="Isolated Data">
                              <option>Isolated Data</option>
                            </optgroup>
                            <optgroup label="Drug Related Data">
                              <option>Drug Related Data</option>
                           </optgroup>
                          </select>
                       </div>   
              <button id="BotonAddClase"  class="btn btn-small" style=""><i class="icon-plus-sign"></i>Add New Episode (Class)</button>
 
	         </p>
	         <p><H4>Type:  </H3>
	         	    <div class="formRight">
		               <select name="Tipos" id="Tipos" data-placeholder="Select Type (is it a report, an image, etc, ?)" class="chzn-select chosen_select" multiple tabindex="5">
                            <option value=""></option>
                            <optgroup label="Imaging Tests">
                              <option>Epi 1</option>
                              <option>Epi 2</option>
                            <optgroup>
                            <optgroup label="Lab Tests">
                              <option>Routine / Checks</option>
                            </optgroup>
                            <optgroup label="Physician Reports">
                              <option>Isolated Data</option>
                            </optgroup>
                         </select>
                    </div>   

	         </p>
	         <p><H5>Clinical Area:  </H3></p>
         </div>
         </div>
         <input type="hidden" id="queId">
         <div class="modal-footer">
	         <!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">--->
             <a href="#" class="btn btn-success" data-dismiss="modal" id="GrabaDatos">Update Data</a>
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 


        <div class="grid" class="grid span4" style="width:1000px; margin: 0 auto; margin-top:30px; padding-top:30px; padding-left:20px;">

        <span class="label label-success" style="left:0px; margin-left:10px; margin-top:40px; font-size:30px;">Reports Board</span>
        <div class="clearfix" style="margin-bottom:20px;"></div>
		<span id="NombreComp" style="font: bold 24px Arial, Helvetica, sans-serif; color: #3D93E0; cursor: auto; margin-left:20px; "><?php echo $MedUserName;?> <?php echo $MedUserSurname;?></span>
		<span id="IdUsFIXED" style="font-size: 12px; color: #3D93E0; font-weight: normal; font-family: Arial, Helvetica, sans-serif; display: block; margin-left:20px;"><?php echo $IdUsFIXED;?></span>
		<span id="IdUsFIXEDNAME" style="font-size: 14px; color: GREY; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:20px;"><?php echo $IdUsFIXEDNAME;?></span>
	  	<span id="email" style="font-size: 14px; color: #5EB529; font-weight: bold; font-family: Arial, Helvetica, sans-serif; margin-left:20px;"><?php echo $email;?></span>

       	<div class="grid-content overflow">
       	</div>

        <!--Image Gallery Box Start-->
        <div class="grid" style="width:95%; ">
          <div class="grid-title">
           <div class="pull-left">   
	                          <div class="pull-left" style="margin-left:20px; margin-top:-5px;" onclick="history.back();"><a class="btn"><i class="icon-folder-open"></i> Back to Main Clinical Records Screen</a> </div>
 
           </div>
           <div class="pull-right">
               
           </div>
           <div class="clear"></div>   
          </div>
        
           
           <div class="grid-content overflow">
             <ul class="stack" data-count="3">

<?php
 require("environment_detail.php");

 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];



	 $tbl_name="usuarios"; // Table name
		
	 // Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }		
		
		//$queUsu = $_GET['Usuario'];
		//$queMed = $_GET['IdMed'];
	 $queUsu = $IdUsu;
	 $queMed = $IdMed;


     $sql=$con->prepare("SELECT * FROM usuarios where Identif =?");
	 $sql->bindValue(1, $queUsu, PDO::PARAM_INT);
	 
	 
     $q = $sql->execute();
     $row = $sql->fetch(PDO::FETCH_ASSOC);
     
     $Name = $row['Name'];
     $Surname = $row['Surname'];
     
     // Meter tipos en un Array
     $sql=$con->prepare("SELECT * FROM tipopin");
     $q = $sql->execute();
     
     $Tipo[0]='N/A';
     while($row=$sql->fetch(PDO::FETCH_ASSOC)){
     	$Tipo[$row['Id']]=$row['NombreEng'];
     }
     
     $Tipo[999]='N/A';
     // Meter clases en un Array
     $Clase[999]='Episode';
     $Clase[0]='Episode';
     $Clase[1]='Check or Preventive';
     $Clase[2]='Isolated Report';
     $Clase[3]='Drug Data';

	 $email = $row['email'];
     $hash = md5( strtolower( trim( $email ) ) );
	 $avat = 'identicon.php?size=50&hash='.$hash;


//             "media":"'$domaindev'/images/ReportsGeneric.png",



$cadena='{
    "timeline":
    {
        "headline":"Health Events",
        "type":"default",
        "text":"<p>User Id.: '.$queUsu.'</p>",
        "asset": {
            "media":"'.$domain.'/images/ReportsGeneric.png", 
            "credit":"(c) health2.me",
            "caption":"Use side arrows for browsing"
        },
        "date": [               
        ';



//getting IdPin for blind reports

//$blindReprtId=array();
//$blindReprtId=blindReports($MedID,$queUsu);
//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks ORDER BY FechaInput DESC");

$sql_query=$con->prepare("select distinct(idDoctor) from doctorsgroups where idDoctor IN (select Idcreator FROM usuarios where Identif=?) or idGroup IN (select idGroup from doctorsgroups where idDoctor IN (select Idcreator FROM usuarios where Identif=?))");
$sql_query->bindValue(1, $queUsu, PDO::PARAM_INT);
$sql_query->bindValue(2, $queUsu, PDO::PARAM_INT);


	$res=$sql_query->execute();
	
	$privateDoctorID=array();
	$num=0;
	while($rowp=$sql_query->fetch(PDO::FETCH_ASSOC)){
		$privateDoctorID[$num]=$rowp['idDoctor'];
		$num++;
	}
	/*if($privateDoctorID==null)
		$privateDoctorID[0]=$MedID;*/
	
$sql_que=$con->prepare("select Id from tipopin where Agrup=9");
	$res=$sql_que->execute();
	
	$privatetypes=array();
	$num1=0;
	while($rowpr=$sql_que->fetch(PDO::FETCH_ASSOC)){
		$privatetypes[$num1]=$rowpr['Id'];
		$num1++;
}
#####changes for blind report#########
/*$sql1="SELECT Idpin,Tipo FROM lifepin where IdUsu ='$queUsu' and Tipo NOT IN (select Id from tipopin where Agrup=9) and IdMed IS NOT NULL and (IdMed NOT IN ((select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor= '$MedID'))) 
and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (Select idmed from doctorslinkdoctors where idmed2='$MedID' and IdPac='$queUsu'))))";
//and IdMed NOT IN (Select idmed from doctorslinkdoctors where idmed2='$MedID' and IdPac='$queUsu'))";*/
$MedID = $IdMed;

$sql1=$con->prepare("SELECT Idpin,Tipo FROM lifepin where IdUsu = ? and Tipo NOT IN (select Id from tipopin where Agrup=9) and IdMed !=0 and IdMed IS NOT NULL and IdMed!=? and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor=?)) and IdMed NOT IN (select idmed from doctorslinkdoctors where idmed2=? and IdPac=? and estado=2) and IdMed NOT IN (select distinct(idDoctor) from doctorsgroups where idGroup IN (select idGroup from doctorsgroups where idDoctor IN (Select idmed from doctorslinkdoctors where idmed2=? and IdPac=? and estado=2)))");
$sql1->bindValue(1, $queUsu, PDO::PARAM_INT);
$sql1->bindValue(2, $MedID, PDO::PARAM_INT);
$sql1->bindValue(3, $MedID, PDO::PARAM_INT);
$sql1->bindValue(4, $MedID, PDO::PARAM_INT);
$sql1->bindValue(5, $queUsu, PDO::PARAM_INT);
$sql1->bindValue(6, $MedID, PDO::PARAM_INT);
$sql1->bindValue(7, $queUsu, PDO::PARAM_INT);



$q1=$sql1->execute();

	$size=0;
	$blindReportId=array();
	while($row1=$sql1->fetch(PDO::FETCH_ASSOC)){
		
		$ReportId=$row1['Idpin'];
		$type=$row1['Tipo'];
		/*if($type==null)
			$type=-1;*/
		if(in_array($type,$privatetypes)){
			if(!in_array($MedID,$privateDoctorID)){
				continue;
			}
		}
		$query=$con->prepare("SELECT estado FROM doctorslinkusers where IdMed=? and IdUs=? and Idpin=? ");
		$query->bindValue(1, $MedID, PDO::PARAM_INT);
		$query->bindValue(2, $queUsu, PDO::PARAM_INT);
		$query->bindValue(3, $ReportId, PDO::PARAM_INT);
		
		
		$q11=$query->execute();
		if($rowes=$query->fetch(PDO::FETCH_ASSOC)){
			$estad=$rowes['estado'];
			if($estad==1){
				$blindReportId[$size]=$ReportId;
				$size++;
			}
		}else{
			$blindReportId[$size]=$ReportId;
			$size++;
		}
		
	}
	
$result = $con->prepare("SELECT * FROM lifepin WHERE IdUsu=? ORDER BY Fecha DESC LIMIT 50");
$result->bindValue(1, $queUsu, PDO::PARAM_INT);
$result->execute();

$numero=$result->rowCount();
$n=0;

while ($row = $result->fetch(PDO::FETCH_ASSOC))
{    
 
	$extensionR = substr($row['RawImage'],strlen($row['RawImage'])-3,3);
	$ImageRaiz = substr($row['RawImage'],0,strlen($row['RawImage'])-4);
	$type=$row['Tipo'];
	if(!in_array($row['IdPin'], $blindReportId)){
				
		  //For private report functionality
		  if(in_array($type,$privatetypes)){
     		if(!in_array($MedID,$privateDoctorID)){
     				continue;
			}
		 }
		
		  if ($extensionR!='jpg')
			{
				$selecURL =$domain.'/PackagesTH/'.$ImageRaiz.'.png';
				$selecURLAMP =$domain.'/Packages/'.$ImageRaiz.$extensionR;
			}
			else {
			if	($row['CANAL']==7){
				$selecURL =$domain.'/PackagesTH/'.$row['RawImage'];
				$selecURLAMP =$domain.'/Packages/'.$row['RawImage'];
			} else {
				$subtipo = substr($row['RawImage'], 3 , 2);
				if ($subtipo=='XX')  { $selecURL =$domain.'/Packages/'.$row['RawImage']; }
				else { $selecURL =$domain.'/eMapLife/PinImageSetTH/'.$row['RawImage']; }
				// COMPROBACIÓN DE EXISTENCIA DEL ARCHIVO (PARA LOS CASOS DE EMAPLIFE iOS o ANDROID QUE TODAVIA NO GENERAN THUMBNAILS Y NO REFERENCIAN AL DIRECTORIO -TH
				$file = $selecURL;
				$file_headers = @get_headers($file);
				if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
			  	  	$exists = false;
			  	  	$selecURL =$domain.'/eMapLife/PinImageSet/'.$row['RawImage'];
			  	  }
			  	  else {
				  	  $exists = true;
				  	  }
				}
			}
	}else{
				 $selecURL =$domain.'/PackagesTH/lockedfile.png';
				 $selecURLAMP =$domain.'/PackagesTH/lockedfile.png';
		  }

if (!$row['Tipo']){$indi = 999;}else{$indi = $row['Tipo'];};
//echo $Tipo[$indi];
//echo $Tipo[$indi];

//if (!$row['EvRuPunt']){$indi2 = 999;}else{$indi2 = $row['EvRuPunt'];}; 

     $Evento = $row['Evento'];
     $sqlE=$con->prepare("SELECT * FROM usueventos where IdUsu =? and IdEvento =? ");
	 $sqlE->bindValue(1, $queUsu, PDO::PARAM_INT);
	 $sqlE->bindValue(2, $Evento, PDO::PARAM_INT);
	 
	 
     $qE = $sqlE->execute();
     $rowE=$sqlE->fetch(PDO::FETCH_ASSOC);
     $EventoALFA = $rowE['Nombre'];
     
     if (!$row['EvRuPunt']){
    	 $indi2 = 999; 
    	 $salida=$EventoALFA; 
     }else{
     	$indi2 = $row['EvRuPunt']; 
     	$salida=$Clase[$indi2]; 
     }; 

if ($n>0) $cadena=$cadena.',';
$n++;



//$FechaFor =  date('j/n/y H:i:s',strtotime($row['Fecha']));
$FechaFor =  date('n/j/Y H:i:s',strtotime($row['Fecha']));

?>
 
                <li style="text-align:center;"><a href="#"><img style="width:120px;" src="<?php echo $selecURL; ?>" rel="group1" data-glisse-big="<?php echo $selecURL; ?>" alt="" class="tl" title="" /></a>
                <div class="action-image">
                
                     <!--
                     <a href="#" class="border-link-right" data-target="#header-modal" data-toggle="modal" id="<?php echo $row['IdPin']; ?>">Classify</a>
                     <a href="#">Delete</a>
					 -->
                  </div>
                </li>
          
<?php
};
?>                
                
                
                
            </ul>
       		<div class="clear"></div>
        	<div class="space-floor"></div>
           </div>
        </div>
        <!--Image Gallery Box END-->
        
        </div>
        
     </div>
	</div>
    <!--Content END-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
   
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/bootstrap-colorpicker.js"></script>
    <script src="js/google-code-prettify/prettify.js"></script>
   
    <script src="js/jquery.flot.min.js"></script>
    <script src="js/jquery.flot.pie.js"></script>
    <script src="js/jquery.flot.orderBars.js"></script>
    <script src="js/jquery.flot.resize.js"></script>
    <script src="js/graphtable.js"></script>
    <script src="js/fullcalendar.min.js"></script>
    <script src="js/chosen.jquery.min.js"></script>
    <script src="js/autoresize.jquery.min.js"></script>
    <script src="js/jquery.tagsinput.min.js"></script>
    <script src="js/jquery.autotab.js"></script>
    <script src="js/elfinder/js/elfinder.min.js" charset="utf-8"></script>
	<script src="js/tiny_mce/tiny_mce.js"></script>
    <script src="js/validation/languages/jquery.validationEngine-en.js" charset="utf-8"></script>
	<script src="js/validation/jquery.validationEngine.js" charset="utf-8"></script>
    <script src="js/jquery.jgrowl_minimized.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/jquery.mousewheel.js"></script>
    <script src="js/jquery.jscrollpane.min.js"></script>
    <script src="js/jquery.stepy.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/raphael.2.1.0.min.js"></script>
    <script src="js/justgage.1.0.1.min.js"></script>
	<script src="js/glisse.js"></script>
    
	<script src="js/application.js"></script>
    <script type="text/javascript" >
    
    $(document).ready(function() {
    
      $('.border-link-right').click( function () {
	    
	    var queId = $(this).attr("id");
	    
	    alert (queId);
	    
	    var queImg = $(".THUMB", this).attr("id");
	    var queTip = $(".cat-green", this).attr("id");
	    var queClas = $(".note-title", this).attr("id");
	    var queFecha = $(".note-more", this).attr("id");
	    
	    
	    var contenTHURL = 'http://www.monimed.com/eMapLife/PinImageSetTH/'+queImg;  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
	    //if(urlExists(contenTHURL)) {}else { contenTHURL = 'http://www.monimed.com/eMapLife/PinImageSet/'+queImg;}

	    var conten = '<img id="ImagenN" src="http://www.monimed.com/eMapLife/PinImageSet/'+queImg+'" alt="">';
	    var contenTH = '<img id="ImagenTH" src="'+contenTHURL+'" alt="">';
	    //alert (queClas);
			    	
	    //$('div.grid-content').html(conten);
	    $('#AreaConten').html(conten);
	    $('#RepoThumb').html(contenTH);
	    
	    //$('div.pull-left.a').html(queTip);
	    $('#AreaTipo').html(queTip);
	    
	    //$('.ClClas').html(queClas);
	    $('#AreaClas').html(queClas);
	    
	    //$('div.grid-title-label').html('<span class="label label-warning" style="font-size:16px;">'+queFecha+'</span>');
	    $('#AreaFecha').html('<span class="label label-warning" style="font-size:16px;">'+queFecha+'</span>');
	    
	    var queUrl ='getTipoClase.php?BlockId='+queId;
      	    
      	$('.ContenDinamico').load(queUrl);
    	    //$('#TablaPac').trigger('click');
    	$('.ContenDinamico').trigger('update');
	    
	    });

   
 
	}); 		
	</script>

  </body>
</html>

