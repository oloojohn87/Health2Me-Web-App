<?php

require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass']; 

// Connect to server and select databse.
 //KYLE$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
 mysql_select_db("$dbname")or die("cannot select DB");

$user = $_GET['user'];
$email = $_GET['emailDoc'];


$splittedUserName = explode(" ",$user);
$userName = $splittedUserName[0];
$userSurname = $splittedUserName[1];



$alias = $userName.".".$userSurname;


$query1 = mysql_query("select Identif,email from usuarios where alias = '$alias'");

$row1 = mysql_fetch_array($query1);
$patientId = $row1['Identif'];
$emailUser = $row1['email'];

$query2 = mysql_query("select id from doctors where IdMEDEmail = '$email'");
$row2 = mysql_fetch_array($query2);
$doctorId = $row2['id'];


?>

<!DOCTYPE html>

<html>
<head>
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
 <!--   <meta name="description" content=""> -->
  <!--  <meta name="author" content=""> -->

 <!--<link href="css/login.css" rel="stylesheet"> -->
  <link href="css/bootstrap.css" rel="stylesheet">

  <!--<link rel="stylesheet" href="css/icon/font-awesome.css"> -->
  <!-- <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"> -->
   <!-- <link rel="stylesheet" href="css/bootstrap-responsive.css"> -->
	<!--<link rel="stylesheet" type="text/css" href="css/tooltipster.css" /> -->

<!--  <link href="css/style.css" rel="stylesheet"> -->
<!--   <link href="css/bootstrap.css" rel="stylesheet"> -->
   
 <!--   <link rel="stylesheet" href="css/colorpicker.css"> -->
  <!--  <link rel="stylesheet" href="css/glisse.css?1.css"> -->
 <!--   <link rel="stylesheet" href="css/jquery.jgrowl.css"> -->
  <!--  <link rel="stylesheet" href="js/elfinder/css/elfinder.css" media="screen" /> -->
  <!--  <link rel="stylesheet" href="css/jquery.tagsinput.css" /> -->
  <!--  <link rel="stylesheet" href="css/demo_table.css" > -->
   <!-- <link rel="stylesheet" href="css/jquery.jscrollpane.css" > -->
  <!--  <link rel="stylesheet" href="css/validationEngine.jquery.css"> -->
 <!--   <link rel="stylesheet" href="css/jquery.stepy.css" /> -->
	
	<!--<link href="css/demo_style.css" rel="stylesheet" type="text/css"/> -->
	<!--<link href="css/smart_wizard.css" rel="stylesheet" type="text/css"/> -->
	
	<!--<link rel="stylesheet" href="css/jquery-ui-autocomplete.css" /> -->
   <!-- <script src="js/jquery-1.9.1-autocomplete.js"></script> -->
	<!--<script src="js/jquery-ui-autocomplete.js"></script> -->
    <!-- <link rel="stylesheet" href="css/icon/font-awesome.css"> -->
  <!--  <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"> -->
   <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
   
    <link rel="stylesheet" href="css/basic.css" />
    <link rel="stylesheet" href="css/jquery-ui-1.8.16.custom.css" media="screen"  />
    <link rel="stylesheet" href="css/chosen.css" media="screen"  />
    <link rel="stylesheet" href="css/dropzone.css"/>
    <script src="js/dropzone.min.js"></script>
    <link rel="stylesheet" href="css/fullcalendar.css" media="screen"  />
    <link rel="stylesheet" href="css/datepicker.css">
   
</head>
<body>

<div align="center" style="text-align: center">
        <img id="customHeaderImage" label="Header Image" editable="true" width="150" src="images/health2melogo-min2.122104.png" class="w640" border="0" align="top" style="display: inline; margin-top:30px;">
        <div style="font-size: 20px; margin-top: 0px; font-size: 16px; color:#489de5; font-family: "Cuprum", Arial, sans-serif; text-align: center; display: block;">unlocking health</div>
</div>    
<div class="grid" style="height:1050px;margin:60px;border: 3px solid grey;">
    <div class="grid" style="width:800px;height:750px; margin-top: 30px; margin-right: 30px; margin-left: -30px; border: 3px solid grey; float: right;">
    
  <div style="text-align:center;margin-top:40px;">
    <span style="font-size:30px;color:#22aeff;">Processed Reports</span>
  </div>
  
    <div id ="container" style="overflow: scroll; width: 800px; height: 450px;">
    </div>
    
    <div id="processedReportMetaData" class ="grid" style="display:none;width:750px;height:150px;margin-left:20px;margin-top:20px;border:3px dashed grey;">
  
  <div class="formRow" >
            <p>        
                <button id="ViewFilePreview" value = "" style="margin-top:10px;margin-left:40px;height:60px;width:100px;font-weight:bold;border-radius:7px; background-image: url(images/File-icons/48px/pdf.png);">Preview File</button>
                <label style="font-size:20px;float:right;margin-top:10px;color:#22aeff">Type of Report: 
                        <select name="ReportType" id="ReportType" oninput = "getReportType()">
                            <option value="Images">Images</option>
                            <option value="MRI">MRI</option>
                            <option value="Doctor Notes">Doctor Notes</option>
                            <option value="Prescriptions">Prescriptions</option>
                            <option value="Medications">Medications</option>  
                        </select>
                        <button id="Close" value="" style="margin-right:20px;border-radius:7px;font-size:bold">Close</button>
                    </label>
                
      </p>
   </div>
  <div>
            <p style="font-size:20px;float:right;margin-right:90px;color:#22aeff">Report Date:<input type="text" id="datepickerReport" class ="date_picker">
			</p>
  </div>
 </div>
 
</div>
    
<style>
.bottom_buttons{
    width: 200px;
    height: 30px;
    background-color: #22aeff;
    border-radius: 7px;
    border: 0px solid #FFF;
    float: right;
    color: #FFF;
    font-weight: bold;
}
</style>
<div style="width: 100%; height: 30px; float: right; padding-right: 30px; margin-top: 80px;">
<button  id = "Finish" class="bottom_buttons" style="margin-left: 10px;">Finish</button>
<button id ="CreateAccount" class="bottom_buttons" style="background-color: #54bc00;">Create Account</button>
</div>
    
<div id="dropArea" style="margin-top:60px;">
 <!-- <div style="text-align: left;margin-left:20px;margin-top:80px;"> -->
    <span style="font-size:30px;color:#22aeff;margin-left:40px">Please Drop Reports Here</span>
 <!-- </div> -->
				   
  <!--  <div style="left;margin-left:100px;"> -->
    <br/>
    <img src="images/arrow_down.png" style="width: 128px; height: 128px; margin-top:30px;margin-left: 12%;" />
   
 <!--   </div> -->
   <div id="dropzone" style="background: #F9F9F9; height:450px; width:420px;margin-top:40px;margin-left:40px;border:6px dashed grey; border-radius: 40px;">
    <form action="upload_dropzoneRequestReportsExtDoc.php" method="post" class="dropzone" id="ReportsDropzone" 
                           class= "dropzone dz-cliclable" style="overflow:scroll;background:#CACACA;height:450px; width:420px;border-radius: 40px;">
            <center style="color:white; font-size:50px;margin-top:80px">
                                &nbsp;&nbsp;Drop Area
            </center>
    </form>
  </div>
				
        
   <div style="margin-top: 160px;margin-left:40px">
            <span style="font-size:20px;">Message</span> <textarea name = "Message" id = "MessageForPatient"     style="height:80px;width:270px;" ></textarea>
   </div>
    
    
</div> 

    
</div>   
<!-- start of code for modal window for create account button -->
<div id="createAccount" style="display:none; text-align:center; padding:10px;">
    
   <p style="color:#22aeff">Name <input type = "text" id = "NameCreateAccountPage" style="width:200px;margin-left:20px;"></p>
    <p style="color:#22aeff">Surname <input type = "text" id = "SurnameCreateAccountPage" style="width:200px;margin-left:20px;"></p>
    <p style="color:#22aeff">Email of Doctor <input type = "text" id = "EmailIDCreateAccountPage" style="width:200px;margin-left:20px;"></p> 
   <p style="color:#22aeff">Password <input type = "password" id = "PasswordCreateAccountPage" style="width:200px;margin-left:20px;"></p> 
   <p style="color:#22aeff">Repeat Password <input type = "password" id ="RepeatPasswordCreateAccountPage"style="width:200px;margin-left:20px;"></p>
    <p style="color:#22aeff">DOB <input type="text" id ="datepickerCreateAccount" class = "date_picker"></p>
    <p id = "gender" style="color:#22aeff">Gender <input type ="radio" name="sex" value="male">Male<br>
    <input type="radio" name="sex" value="female">Female</p>
    <p><button id="createAccountNewDoc">Create Account</button></p>
    
          
</div>
<!-- End of code for modal window for create account button -->
   
    <div id ="previewModal"></div> 

    
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
    <script src="js/morris.js"></script>
    
	<script src="js/application.js"></script>
	<script type="text/javascript" src="js/jquery.tooltipster.js"></script>

	<script src="realtime-notifications/lib/gritter/js/jquery.gritter.min.js"></script>
	<link href="realtime-notifications/lib/gritter/css/jquery.gritter.css"rel="stylesheet" type="text/css" />
	
<!--<script type="text/javascript" src="js/jquery-2.0.0.min.js"></script>
<script type="text/javascript" src="js/jquery.smartWizard.js"></script>    -->

<script type="text/javascript">

var filecount = 0;
var num=0;
var orig_file_array = new Array();
var patientId = <?php echo $patientId ?>;
var doctorId = <?php echo $doctorId ?>;
var filename = new Array();
var filepointer = new Array();
var idString = 0;
var reportType,reportDate;
var gridToBeDeleted;





Dropzone.options.ReportsDropzone = {
			//autoProcessQueue: false,	
			//previewTemplate: '<span class="label label-info" style="left:0px; margin-left:30px; font-size:30px;">Patient Creator</span>',
			//maxFilesize:0,
			init: function() 
			{
				
               myDropzone1 = this; 
				
			this.on("sending", function(file, xhr, formData) {
					//formData.append("repdate", $('#datepicker1').val()); // Append all the additional input data of your form here!
					filepointer[filecount] = file;
                    formData.append("idus",patientId);
					//formData.append("tipo",60);
					formData.append("id",filecount);
					formData.append("doctorId",doctorId);
                    orig_file_array[filecount] = file.name;
                    filename[filecount] = file.name;
                    
                
					
					//alert('sending file');
					//document.getElementById("upload_count_label").innerHTML = upload_count + '/' + filecount;
					myDropzone1.processQueue();
                
                // Below code is called for displaying the grid of processed reports sub section whenever a file is dropped in drop                    area
                $("#container").append('<div id="processedReportMetaData" class ="grid"            style="display:none;width:750px;height:150px;margin-left:20px;margin-top:20px;border:3px dashed    grey;">'+$("#processedReportMetaData").html()+'</div>');
    
                $("#container").children('div').each(function(){if($(this).css("display") == 'none'){$(this).slideDown();}});
                
                $("#container").children('#processedReportMetaData').each(function(){
                    $(this).attr("id", "processedReportMetaData"+filecount);
                    
                    // Changing the id and value for Preview File button
                    $(this).find('#ViewFilePreview').each(function(){
                       $(this).attr("id", "ViewFilePreview"+filecount);
                        $(this).attr("value",filecount);
                    });
                    
                    //Changing the id for Close button
                    
                    $(this).find("#Close").each(function(){
                        $(this).attr("id", "Close"+filecount);
                        $(this).attr("value",filecount);
                        
                    });
                    
                });
                
                
                
                // Code for  Preview File button
                   $("#ViewFilePreview"+filecount).on('click',function(){
                       
                     current = $(this).attr('value');
                     $("#previewModal").html('<iframe src="dropzone_uploads/temporaryForFilePreview/'+filename[current]+'" style="width:600px;height:600px;"></iframe>');
                     
                     $("#previewModal").dialog({bigframe: true, width: 600, height: 600, modal: false});

                    });
                
                //Code for Close button
                
                $("#Close"+filecount).on('click',function(){
                    
                    current = $(this).attr('value');
                    gridToBeDeleted = "processedReportMetaData"+current;
                    
                    $(this).closest('#container').find('#'+gridToBeDeleted).each(function(){
                        $(this).remove();
                    });
                    
                    $.get("deleteFileFromLifePin.php?filename="+filename[current],function(data,status){
                    alert(data);    
                    });
                    
                    // Closing the filepreview in dropzone area
                    
                
                });
    
				//  code for datepicker text area in dob of create account page
               $("#datepickerReport").datepicker({
			      inline: true,
			     nextText: '&rarr;',
			     prevText: '&larr;',
			     showOtherMonths: true,
			     dateFormat: 'yy-mm-dd',
			     dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			     showOn: "button",
			     buttonImage: "images/calendar-blue.png",
			     buttonImageOnly: true,
			     changeYear: true ,
			     changeMonth: true,
			     yearRange: '1900:c',
	});
                
                
                
                
                filecount++;
				});
								
				this.on("error",function(file,errorMessage){
					//alert(file.name + " not uploaded properly");
					failed_uploads++;
				});
						
			}
		}; 
    
    
//Code for retrieving values of report type and report date
function getReportType(){
        reportType = $("#ReportType option:selected").text();
        reportDate = $("#datepickerReport").val();
        
    }
//Code for popping up the modal window for Create Account button
$("#CreateAccount").on('click',function(){
		$("#createAccount").dialog({bigframe: true, width: 550, height: 300, modal: false});
	}); 

//code for datepicker text area in dob of create account page
$("#datepickerCreateAccount" ).datepicker({
			inline: true,
			nextText: '&rarr;',
			prevText: '&larr;',
			showOtherMonths: true,
			dateFormat: 'yy-mm-dd',
			dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			showOn: "button",
			buttonImage: "images/calendar-blue.png",
			buttonImageOnly: true,
			changeYear: true ,
			changeMonth: true,
			yearRange: '1900:c',
	});
    
 
    
    var name,surname,email,password,repeatPassword,date,gender;
    var emailDocToBeAdded = '<?php echo $email ?>';
    
    
    $('input:radio[name=sex]').click(function() {
    gender = $('input:radio[name=sex]:checked').val();
    
    //Code for createAccountNewDoc button
        
        $("#createAccountNewDoc").on('click',function(){
        
        name = $("#NameCreateAccountPage").val();
        surname = $("#SurnameCreateAccountPage").val();
        email = $("#EmailIDCreateAccountPage").val();
        password = $("#PasswordCreateAccountPage").val();
        repeatPassword = $("#RepeatPasswordCreateAccountPage").val();
        date = $("#datepickerCreateAccount").val();
        
        //Making ajax call for creating doctor account
        $.get("createAccountNewDoc.php?name="+name+"&surname="+surname+"&email="+email+"&password="+password+"&date="+date+"&gender="+gender+"&emailDoc="+emailDocToBeAdded,function(data,status){
            
            alert(data);
        });
         
    });
    
     
    //Code for Finish button
        $("#Finish").on('click',function(){
                
            getReportType();
        
            var emailUser = '<?php echo $emailUser; ?>';
            var emailDoc ='<?php echo $email; ?>';
            var message = $("#MessageForPatient").val();
            
            $.get("sendReturnMail2User.php?emailUser="+emailUser+"&emailDoc="+emailDoc+"&message="+message,function(data,status)
    {
     alert(data);
    });
           // +"&reportType="+reportType+"&reportDate="+reportDate
                $.get("closingDropzone.php",function(data,success){});
                var url = $(location).attr('href');
                window.close(url);
});
});
    
    
    
    </script>
</body>
</html>
    