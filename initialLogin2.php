<?php

require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }	

$access = false;
if(isset($_GET['InvalidAccess']))
{
        $access = false;
}

else
{
        $userId = $_GET['IdUsu'];
        $doctorId = $_GET['IdMed'];


        //getting the email id of temporary doctor and patient

        $query = $con->prepare("select IdMEDEmail from doctors where id=?");
        $query->bindValue(1,$doctorId,PDO::PARAM_INT);
        $query->execute();
        $row1 = $query->fetch(PDO::FETCH_ASSOC);
        $doctorEmail = $row1['IdMEDEmail'];

        $query = $con->prepare("select email from usuarios where Identif=?");
        $query->bindValue(1,$userId,PDO::PARAM_INT);
        $query->execute();
        $row1 = $query->fetch(PDO::FETCH_ASSOC);
        $userEmail = $row1['email'];


        //Checking whether the doctor is still within expiration time frame from the time patient sent the link
        $query = $con->prepare("select * from temporary_doctor_access where doctorId =? and expirationTime > now()");
        $query->bindValue(1,$doctorId,PDO::PARAM_INT);
        $query->execute();

        $countOfDoctor = $query->rowCount();

        $access = false;
        if($countOfDoctor >= 1)
            $access = true;
}


    
?>

<!DOCTYPE html>
<html lang="en" style="background: #F9F9F9;"><head>
    <meta charset="utf-8">
    <title>Inmers - Center Management Console</title>
    <link rel="icon" type="image/ico" href="favicon.ico"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/style_version1.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">  
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <!--<link rel="stylesheet" href="css/basic.css" /> -->
    
    <link rel="stylesheet" href="css/jquery-ui-1.8.16.custom.css" media="screen"  />
   <!-- <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" /> -->
    
    <link rel="stylesheet" href="css/fullcalendar.css" media="screen"  />
    <link rel="stylesheet" href="css/chosen.css" media="screen"  />
    <link rel="stylesheet" href="css/datepicker.css" >
    <link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css" />
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
 <!--   <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" href="css/toggle-switch.css">
    <link rel="stylesheet" href="css/dropzone_version1.css"/>
    <script src="js/dropzone.min_version1.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>

   
<style> 
#dragandrophandler
{
border:2px dotted #0B85A1;
width:400px;
color:#92AAB0;
text-align:center;vertical-align:middle;
padding:10px 10px 10px 10px;
margin-bottom:10px; margin-left:40px;
font-size:200%;
height:200px;
display:table-cell;
}   
.progressBar {
    width: 200px;
    height: 22px;
    border: 1px solid #ddd;
    border-radius: 5px; 
    overflow: hidden;
    display:inline-block;
    margin:0px 10px 5px 5px;
    vertical-align:top;
}
 
.progressBar div {
    height: 100%;
    color: #fff;
    text-align: right;
    line-height: 22px; /* same as #progressBar height if we want text middle aligned */
    width: 0;
    background-color: #0ba1b5; border-radius: 3px; 
}
.statusbar
{
    border-top:1px solid #A9CCD1;
    min-height:25px;
    width:400px;
    padding:10px 10px 0px 10px;
    vertical-align:top;
}
.statusbar:nth-child(odd){
    background:#EBEFF0;
}
.filename
{
display:inline-block;
vertical-align:top;
width:150px;
}
.filesize
{
display:inline-block;
vertical-align:top;
color:#30693D;
width:50px;
margin-left:10px;
margin-right:5px;
}
.abort{
    background-color:#A8352F;
    -moz-border-radius:4px;
    -webkit-border-radius:4px;
    border-radius:4px;display:inline-block;
    color:#fff;
    font-family:arial;font-size:13px;font-weight:normal;
    padding:4px 15px;
    cursor:pointer;
    vertical-align:top
    }
</style>
    

	
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lte IE 8]><script type="text/javascript" src="/js/excanvas.min.js"></script><![endif]-->
 
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="/images/icons/favicon.ico">
    	<style>
		.ui-progressbar {
		position: relative;
		}
		.progress-label {
		position: absolute;
		left: 50%;
		top: 4px;
		font-weight: bold;
		text-shadow: 1px 1px 0 #fff;
		}
	</style>
	<style>
	#overlay {
	  background-color: none;
	  position: auto;
	  top: 0; right: 0; bottom: 0; left: 0;
	  opacity: 1.0; /* also -moz-opacity, etc. */
	  
    }
	#messagecontent {
	  white-space: pre-wrap;   
	}
	</style>
	  <style>
		#progressbar .ui-progressbar-value {
		background-color: #ccc;
		}
	  </style>

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

  <body style="background: #F9F9F9;">
      
      <!-- MODAL VIEW TO FIND DOCTOR -->
    <div id="find_doctor_modal" title="Find Doctor" style="display:none; text-align:center; padding:20px;">
        <div id="Talk_Section_1" style="display: block;">
            <!--<input type="text" style="width: 90%; margin-top: 15px; margin-bottom: 15px; height: 20px; color: #CACACA; padding: 5px;" placeholder="Search for a doctor..." value="" />-->
            <style>
            .recent_doctor_button{
                    padding: 3px; 
                    width: 80%; 
                    margin: auto; 
                    color: #22aeff; 
                    background-color: #FBFBFB; 
                    height: 25px; 
                    border: 1px solid #CFCFCF;
                    outline: 0px;
                }
            .recent_doctor_button_selected{
                    border: 1px solid #22aeff;
                    background-color: #22aeff; 
                    color: #FFF;
                    padding: 3px; 
                    width: 80%; 
                    margin: auto; 
                    height: 25px;
                    outline: 0px;
                }
           
            </style>
            <div id="recent_doctors_section" style="display: block;"></div>
          
            <button style="width: 200px; heightL 30px; background-color: #22aeff; color: #FFF; border: 0px solid #FFF; margin: auto; margin-top: 15px; border-radius: 7px; outline: 0px;" id="find_doctor_button">Next</button>
        </div>
        <div id="Talk_Section_2" style="display: none;">
            <button style="width: 200px; heightL 30px; background-color: #22aeff; color: #FFF; border: 0px solid #FFF; margin: auto; margin-top: 15px; margin-left: 20px; border-radius: 7px; outline: 0px; float: left;" id="video_call_button">Video Call</button>
            <button style="width: 200px; heightL 30px; background-color: #22aeff; color: #FFF; border: 0px solid #FFF; margin: auto; margin-top: 15px; margin-right: 20px; border-radius: 7px; outline: 0px; float: right;" id="phone_call_button">Phone Call</button>
           
            
        </div>
        <div id="Talk_Section_3" style="display: none;">
            <br/>
            <p>No doctors are available at this time. Please try again later.</p>
           
            
        </div>
        <div id="Talk_Section_4" style="display: none;">
            <br/>
            <p>We are now calling your doctor, please wait...</p>
           
            
        </div>
    </div>
    <!-- END MODAL VIEW TO FIND DOCTOR -->

<input type="hidden" id="NombreEnt" value="<?php echo $NombreEnt; ?>">
<input type="hidden" id="PasswordEnt" value="<?php echo $PasswordEnt; ?>">
<input type="hidden" id="UserHidden">

	<!--Header Start-->
<!--	<div class="header" >
     	<input type="hidden" id="USERID" Value="<?php echo $UserID; ?>">	
    	<input type="hidden" id="MEDID" Value="<?php echo $MedID; ?>">	
    	<input type="hidden" id="IdMEDEmail" Value="<?php echo $MedUserEmail; ?>">	
    	<input type="hidden" id="IdMEDName" Value="<?php echo $MedUserName; ?>">	
    	<input type="hidden" id="IdMEDSurname" Value="<?php echo $MedUserSurname; ?>">	
    	<input type="hidden" id="IdMEDLogo" Value="<?php echo $MedUserLogo; ?>">
        <input type="hidden" id="USERNAME" Value="<?php echo $UserName; ?>">	
        <input type="hidden" id="USERSURNAME" Value="<?php echo $UserSurname; ?>">	
        <input type="hidden" id="USERPHONE" Value="<?php echo $UserPhone; ?>">	
  		
           <a href="index.html" class="logo"><h1>Health2me</h1></a>
           
           <div class="pull-right">
           
            
           <!--Button User Start-->
		   <div class="btn-group pull-right" >
           
            <a class="btn btn-profile dropdown-toggle" id="button-profile" data-toggle="dropdown" href="#">
              <!--<span class="name-user"><strong>Welcome</strong> <?php echo $email; ?></span> Commented out by Pallab --> 
             <span class="name-user"><strong>Welcome</strong> Guest </span>
              <span class="avatar" style="background-color:WHITE;"><img src="<?php echo $avat; ?>" alt="" ></span> 
              <span class="caret"></span>
            </a>
            <div class="dropdown-menu" id="prof_dropdown">
            <div class="item_m"><span class="caret"></span></div>
            <ul class="clear_ul" >
			 <!-- <li><a href="dashboard.php"><i class="icon-globe"></i> Home</a></li>
              <li><a href="medicalConfiguration.php"><i class="icon-cog"></i> Settings</a></li> -->
             <!-- <li><a href="logout.php"><i class="icon-off"></i> Sign Out</a></li> --> <!-- Commented out by Pallab -->
            </ul>
            </div>
          </div>
          <!--Button User END-->  
          
          </div>
    </div>
    <!--Header END-->
 
   	 <!--- VENTANA MODAL  This has been added to show individual message content which user click on the inbox messages ---> 
   	 <button id="message_modal" data-target="#header-message" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button> 
   	  <div id="header-message" class="modal hide" style="display: none;" aria-hidden="true">
         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                  Message Details
         </div>
         <div class="modal-body">
         <div class="formRow" style=" margin-top:-10px; margin-bottom:10px;">
             <span id="ToDoctor" style="color:#2c93dd; font-weight:bold;">TO <?php echo 'Dr. '.$NameDoctor.' '.$SurnameDoctor; ?></span><input type="hidden" id="IdDoctor" value='<?php echo $IdDoctor; ?>'/>
         </div>
         <textarea  id="messagedetails" class="span message-text" style="height:200px;" name="message" rows="1"></textarea>
         
		 <form id="replymessage" class="new-message">
                   <div class="formRow">
                        <label>Subject: </label>
                        <div class="formRight">
                            <input type="text" id="subjectname_inbox" name="name"  class="span"> 
                        </div>
                   </div>
				   <div class="formRow">
						<label>Attachments: </label>
						<div id="attachreportdiv" class="formRight">
							<input type="button" class="btn btn-success" value="Attach Reports" id="attachreports">
						</div>
				   </div>
                   <div class="formRow">
                        <label>Message:</label>
                        <div class="formRight tooltip-top" style="height:120px;">
                            <textarea  id="messagecontent_inbox" class="span message-text" name="message" style="height:90px;" rows="1"></textarea>
                            
                            <div class="clear"></div>
                        </div>
                   </div>
            </form>
			<div id="attachments" style="display:none">
			
			
			
			</div>
		 </div>
         <input type="hidden" id="Idpin">
        <!-- <input type="hidden" id="docId" value="<?php echo $IdMed; ?>"/> -->
         <input type="hidden" id="userId" value="<?php echo $IdUsu; ?>" />
         <div class="modal-footer">
		     <input type="button" class="btn btn-info" value="Send messages" id="sendmessages_inbox">
             <input type="button" class="btn btn-success" value="Attach" id="Attach">	
	         <input type="button" class="btn btn-success" value="Reply" id="Reply">			 
	         <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseMessage">Close</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 	
       
    <!--Content Start-->
	<div id="content" style="background: #F9F9F9; padding-left:0px;">
    
    	    
	 <!--SpeedBar Start--->
     <div class="speedbar">
     <div class="speedbar-content">
     <ul class="menu-speedbar">		
			 <!-- <li><a href="dashboard.php"><i class="icon-globe"></i> Home</a></li>
              <li><a href="medicalConfiguration.php"><i class="icon-cog"></i> Settings</a></li> -->
          <!--    <li><a href="logout.php" style="color:yellow;"><i class="icon-off"></i> Sign Out</a></li> Commented out by Pallab --> 
     </ul>

     
     </div>
     </div>
     <!--SpeedBar END-->
     
     <style>
.bottom_buttons{
    width: 200px;
    height: 60px;
    background-color: #22aeff;
    border-radius: 7px;
    border: 0px solid #FFF;
    color: #FFF;
    font-weight: bold;
}
</style>
     
     <!--CONTENT MAIN START-->
     <div class="content">
     
	     <div class="grid" class="grid span4" style="width:600px; height:400px; margin: 0 auto; margin-top:30px; padding-top:30px; text-align: center;">
          <?php
          
             if($access)
             {
             
          ?>
                <!-- Start of View Records, Create Account and Finish section -->             
                <div style="width: 200px; margin: auto;  height: 80px;">


                  <button  id = "ViewRecords" class="bottom_buttons" style="background-color: #54bc00;">View Records</button>
                   <p> [Records can be viewed for twenty four hours] </p>
                    <p><button id ="CreateAccount" class="bottom_buttons" style="background-color: #54bc00;">Create Account</button></p>
                    <p> [Create a trial account] </p>
                    <p><button id ="Finish" class="bottom_buttons" style="width:150px;height:40px;background-color: #22aeff;margin-left:150px;margin-top:70px">Finish</button></p>
                </div>
                <!-- End of View Records, Create Account and Finish section -->   
             
             <?php } else { ?>
             
             <div style="width: 80%; margin: auto; color: #FF3333;"><?php if(isset($_GET['InvalidAccess'])){ echo 'Invalid User';} else{ echo 'Sorry, this access link has expired, please request another link from your patient by clicking the button below.';}?>
             
              <p><button id ="RequestAccess" class="bottom_buttons" style="width:150px;height:40px;background-color: #22aeff;margin-left:7px;margin-top:110px;<?php if(isset($_GET['InvalidAccess'])){echo ' display: none;';} ?>">Request Access</button></p>
             </div>
             
             <?php } ?>
                
         </div>


        <!-- start of code for modal window for create account button -->

  <div id="createAccount" title = "Create Account" style="overflow:visible;display:none;">
        <div style="border:solid 1px #cacaca; margin-top:5px; padding:10px;">
		
			<table style="width:100%;background-color:white">
				<tr>
					<td style="width:20%"><span style="float: left;text-align:left;margin-top: 5px;font-size:14px;margin-left:5px">Name: </span></td>
					<td style="width:80%"><input id="NameCreateAccountPage" type="text" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:25px" placeholder="Enter Name"></td>
				</tr>
				<tr>
					<td style="width:20%"><span style="float: left;text-align:left;margin-top: 5px;font-size:14px;margin-left:5px">Surname: </span></td>
					<td style="width:80%"><input id="SurnameCreateAccountPage" type="text" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:25px" placeholder="Enter Surname"></td>
				</tr>
				<tr>
					<td style="width:20%"><span style="float: left;text-align:left;margin-top: 5px;font-size:14px;margin-left:5px">Email: </span></td>
					<td style="width:80%"><input id="EmailCreateAccountPage" type="text" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:25px" placeholder="Enter Email"></td>
				</tr>
				<tr>
					<td style="width:20%"><span style="float: left;text-align:left;margin-top: 5px;font-size:14px;margin-left:5px">Password: </span></td>
					<td style="width:80%"><input id="PasswordCreateAccountPage" class="password" type="password" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:25px";></td>
				</tr>
				<tr>
					<td style="width:20%"><span style="float: left;text-align:left;margin-top: 5px;font-size:14px;margin-left:5px">Repeat Password: </span></td>
					<td style="width:80%"><input id="RepeatPasswordCreateAccountPage" type="password"  style="margin-top: 10px;width: 95%; float:left;font-size:14px;;height:25px;"></td>
				</tr>
				<tr>
					<td style="width:20%"><span style="float: left;text-align:left;margin-top: 5px;font-size:14px;margin-left:5px">DOB: </span></td>
					<td style="width:80%"><input id="DOB" type="date" style="margin-top: 10px;width: 95%; float:left;font-size:14px;height:25px" placeholder="Enter Date of Birth"></td>
				</tr>
                <tr>
					<td style="width:20%"><span style="float: left;text-align:left;margin-top: 5px;font-size:14px;margin-left:5px">Gender: </span></td>
					<td style="width:80%"><input type ="radio" name="sex" value="male" style="float:left;"><span style="float: left; margin-left: 10px;">Male</span>
        <input type="radio" name="sex" value="female" style="margin-left:20px;float:left;"><span style="float: left; margin-left: 10px;">Female</span></td>
				</tr>
                
			</table>

        <a id="createAccountNewDoc" class="btn" style="width:150px; color:#FFF; float:right; margin-top:15px;margin-bottom:10px;background-color:#54bc00;"><span>Create Account</span></a>
		
    </div>
</div>     
<!-- End of code for modal window for create account button -->



     <!--CONTENT MAIN END-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>

    <!-- Libraries for notifications -->
    <script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
	<script src="realtime-notifications/pusher.min.js"></script>
	<script src="realtime-notifications/PusherNotifier.js"></script>
	<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
	<!--<script src="imageLens/jquery.js" type="text/javascript"></script>-->
	<script src="imageLens/jquery.imageLens.js" type="text/javascript"></script>
    <script>
		$(function() {
	    var pusher = new Pusher('d869a07d8f17a76448ed');
	    var channel_name=$('#MEDID').val();
		var channel = pusher.subscribe(channel_name);
		var notifier=new PusherNotifier(channel);
		
	  });
    </script>
    
<link type="text/css" href="css/bootstrap-timepicker.min.css" />

        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <!--<script src="js/bootstrap.min.js"></script>-->
        <script src="js/bootstrap-datepicker.js"></script>

            <!-- Libraries for notifications -->

        <script src="js/bootstrap.min.js"></script>

        <!--<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script> -->
        <!--<script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script> -->


        <script src="TypeWatch/jquery.typewatch.js"></script>

          <script type="text/javascript" >
            
            //Start of code for ViewRecords button
            $("#ViewRecords").on('click',function()
                 {
                   var doctorEmail = "";
                    <?php

                        if($doctorEmail != null)
                        {
                            echo 'doctorEmail = "'.$doctorEmail.'";';
                            

                        }
                    ?>
                     
                   location.replace("patientdetailMED-new-Send.php?IdUsu="+<?php echo $userId; ?>+"&Acceso="+'23432'+"&doctor_email="+doctorEmail);
                 });
              //End of code for ViewRecords button
              
             
            //Code for popping up the modal window for Create Account button
            $("#CreateAccount").on('click',function(){
                $("#createAccount").dialog({bigframe: true, width: 550, height: 400, modal: false});
                $(".ui-dialog .ui-dialog-titlebar").css("height", "40px");
              }); 

    
              var name,surname,email,password,repeatPassword,date,gender;
              var emailDocToBeAdded = '';
              <?php if($doctorEmail != null){echo 'emailDocToBeAdded = "'.$doctorEmail.'";';} ?>
    
    
              $('input:radio[name=sex]').click(function() {
              gender = $('input:radio[name=sex]:checked').val();

              }); // End of code for popping up the modal window of create account button
    
      
             //Code for createAccountNewDoc(the button in the create account modal window) button

        
             $("#createAccountNewDoc").on('click',function(){
        
                    name = $("#NameCreateAccountPage").val();
                    surname = $("#SurnameCreateAccountPage").val();
                    email = $("#EmailCreateAccountPage").val();
                    password = $("#PasswordCreateAccountPage").val();
                    repeatPassword = $("#RepeatPasswordCreateAccountPage").val();
                    date = $("#DOB").val();
                    var passwordStatus = 'correct';   

                    //Checking for validity of the password
                    if(password != repeatPassword)
                    {

                       $('<div></div>').dialog({width:300,height:200,modal: true,title: "Password Status",open: function() {
                               var markup = 'Password Incorrect';
                               $(this).html(markup); },
                               buttons: {
                               Ok: function() {
                               $( this ).dialog( "close" );
                                   }
                               }   }); 
                        passwordStatus = 'incorrect';
                    }

                    //Making ajax call for creating doctor account

                    if(passwordStatus == 'correct')
                    {
                        console.log("got here");
                        $.get("createAccountNewDoc.php?name="+name+"&surname="+surname+"&email="+email+"&password="+password+"&date="+date+"&gender="+gender+"&emailDoc="+emailDocToBeAdded,function(data,status)
                        {
                            alert("You account has been created!");
                        });


                        $("#createAccount").dialog("close");
                    }
         }); // End of code for create account button in create account modal window
              
              $("#Finish").on('click',function(data,success)
                  {
                     window.close();
                  });
              
              //Code for Request Access button
              
              $("#RequestAccess").on('click',function(data,status)
                                    
                 {
                 var doctorEmail = "";
                 var userEmail = "";
                 console.log("In Request Access");
                    <?php

                        if($doctorEmail != null && $userEmail != null)
                        {
                            echo 'doctorEmail = "'.$doctorEmail.'";';
                            echo 'userEmail = "'.$userEmail.'";';

                        }
                    ?>
                 console.log(doctorEmail+'  '+userEmail);
                $.post("send_resent_link_request.php",{doctor_email: doctorEmail,patient_email: userEmail },function(data,status){
                   
                     console.log(data);
                 
                 });
                     
                 alert("The request mail has been sent");
                 window.close();
                
                
                 }
                );

    </script>

    <!--<script src="js/bootstrap.min.js"></script> Removing it from here and placing at the initial of the script-->

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

 <?php

function queFuente ($numero)
{
$queF=10;
switch ($numero)
{
	case ($numero>999 && $numero<9999):	$queF = 30;
										break;
	case ($numero>99 && $numero<1000):	$queF = 50;
										break;
	case ($numero>0 && $numero<100):	$queF = 70;
										break;
}

return ($queF);

}

function queFuente2 ($numero1, $numero2)
{
$queF=10;
$numero= digitos($numero1)+digitos($numero2);
switch ($numero)
{
	case 2:	$queF = 60;
			break;
	case 3:	$queF = 55;
			break;
	case 4:	$queF = 50;
			break;
	case 5:	$queF = 45;
			break;
	case 6:	$queF = 40;
			break;
	case 7:	$queF = 35;
			break;
	case 8:	$queF = 30;
			break;
}

return ($queF);

}

function digitos ($numero)
{
$queF=0;

switch ($numero)
{
	case ($numero>999 && $numero<9999):	$queF = 4;
										break;
	case ($numero>99 && $numero<1000):	$queF = 3;
										break;
	case ($numero>0 && $numero<100):	$queF = 2;
										break;
}

return ($queF);

}
?>

  </body>
</html>
