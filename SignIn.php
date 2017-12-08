<?php
session_start();
if(isset($_SESSION['Acceso']))
    unset($_SESSION['Acceso']); 
?>

<!DOCTYPE html>
<html lang="en"  class="body-error"><head>
    <meta charset="utf-8">
    <title>Inmers - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/login.css" rel="stylesheet">
    <?php
        if ($_COOKIE["inmers_hti"]=="col") {
    ?>
        <link href="css/loginCol.css" rel="stylesheet">
    <?php } ?>
    <link href="css/bootstrap.css" rel="stylesheet">

	<!--<link rel="stylesheet" href="css/icon/font-awesome.css">-->
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" type="text/css" href="css/tooltipster.css" />
    <link rel="stylesheet" href="build/css/intlTelInput.css">
    
    <link rel="stylesheet" href="css/jquery-ui-1.8.16.custom.css" media="screen"  />


    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
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
 <script type="text/javascript" src="js/zopimchat.js"></script>
  </head>

  <body>
	<input type="hidden" name="uid"/>
	<input type="hidden" name="pass"/>
     	  <!--- VENTANA MODAL  ---> 
   	  <!--<button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>-->
      <div id="error_bar" style="position: relative;width:100%;height:53px;color:white;background-color: rgb(232, 58, 60);display:none"><div id="error_msg" style="position: absolute;margin-top:15px;margin-left:440px "></div>
        <div id="close_error_bar" style="margin-left:1200px;">
            <i class="icon-remove-circle icon-4x"></i>
       </div></div>
      
   	  <div id="header-modal" class="modal hide" style="display: none; height:320px;" aria-hidden="true">
 <button id="BotonMod" data-target="#header-modal" data-toggle="modal" class="btn" style="float:right; margin-right:10px; display:none;"><i class="icon-indent-left"></i>New</button>

         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <h4>New User Account</h3>
         </div>
         
         <div class="modal-body" id="ContenidoModal" style="height:170px;">
           <p>
           You will receive an email with a confirmation link at the email address you indicated. Please follow the link to verify your identity and complete the sign up process. 
           </p>
            <p>
           Your email: <span id="IDRESERV" class="label label-success" style="margin-right:200px; float:right; font-size:14px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace; background-color:#22AEFF;;" title="Your email, valid for signin purposes"></span>
           <p><p>
           Your Number id: <span id="VIdUsFIXED2" class="label label-success" style="margin-right:200px; float:right; font-size:14px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace;" title="This will be the first part of your flexible identification tag"></span>
           <p>
           <p>
           Your Name id: <span id="VIdUsFIXEDNAME2" class="label label-success" style=" margin-right:200px; float:right; font-size:14px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace;"  title="This will be the second part of your flexible identification tag"></span>
           </p>
           <p>
           Please use your Name id for sign in purposes.
           </p>
           
         </div>
         <input type="hidden" id="queId">
        
         <div class="modal-footer">
	         <!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">--->
             <a href="#" class="btn btn-success" data-dismiss="modal" id="GrabaDatos">Proceed</a>
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal">Cancel</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 
      
      <!--- VENTANA MODAL  2 ---> 
   	  <!--<button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>-->
   	  <div id="header-modal2" class="modal hide" style="display: none; height:385px;" aria-hidden="true">
		<button id="BotonMod2" data-target="#header-modal2" data-toggle="modal" class="btn" style="float:right; margin-right:10px; display:none;"><i class="icon-indent-left"></i>New</button>

         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <h4 class="logo-small" style="margin-left: 199px;"></h4>
                 <div style="text-align:center;">
                    <span class="label label-info" style="font-size:14px;">RESET PASSWORD</span>
                 </div>
         </div>
         
         <div class="modal-body" style="height:170px;">
             
             
             <div class="MsgStatus2" style="height:0px; -webkit-border-radius: 3px; border-radius: 3px; margin-bottom:20px; background-color: #3498db;">                   
             </div>
           
             <div id="ContenidoModal2"></div>
         </div>
         <input type="hidden" id="queId">
        
         <div class="modal-footer">
	         <!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">
             <a href="#" class="btn btn-success" data-dismiss="modal" id="GrabaDatos">Proceed</a>--->
             <input type="button" class="btn btn-primary" value="Proceed" id="Proceed">
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal2">OK</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL 2 ---> 

     
    <div id="container_demo" >
        <a class="hiddenanchor" id="toregister"></a>
        <a class="hiddenanchor" id="tologin"></a>
        <div id="wrapper" style="top:50px;">
          
            <div id="login" class="animate form position">
                <form id="FormLOGIN" method="post" action="login.php" class="form-login" > 
                    <div class="content-login">

                    <a href="/" class="logo"></a>
                            <div style="text-align:center;">
                            <span class="label label-info" style="font-size:18px;">DOCTOR ACCESS</span>
                            </div>
                    <div class="inputs" style="margin-top: 13px;">

                        <i class="icon-envelope first-icon"></i><input id="loginID" name="Nombre" type="text" class="first-input" placeholder="Username or Email" />
                        <div class="clear"></div>
                        <i class="icon-key"></i><input id="loginPass" name="Password" type="password" class="last-input" placeholder="password" />
                    </div>
                        
                        
                    <div class="remember">
                    	<input type="checkbox" id="c2" name="cc" checked="checked" />
            			<label for="c2"><span></span> Remember Me</label>
                    </div>
                        
                        
                    <!-- Start of new code by Pallab for Remember Me-->

                            <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
                            <script>
                                $(function() {

                                    if (localStorage.chkbx && localStorage.chkbx != '') {
                                        $('#c2').attr('checked', 'checked');
                                        $('#loginID').val(localStorage.usrname);
                                        $('#loginPass').val(localStorage.pass);
                                    } else {
                                        $('#c2').removeAttr('checked');
                                        $('#loginID').val('');
                                        $('#loginPass').val('');
                                    }

                                    $('#c2').click(function() {

                                        if ($('#c2').is(':checked')) {
                                            // save username and password
                                            localStorage.usrname = $('#loginID').val();
                                            localStorage.pass = $('#loginPass').val();
                                            localStorage.chkbx = $('#rc2').val();
                                        } else {
                                            localStorage.usrname = '';
                                            localStorage.pass = '';
                                            localStorage.chkbx = '';
                                        }
                                    });
                                });

                            </script>
                        <!-- End of new code by Pallab for Remember Me -->
                        
                        
                    <div id="ForgotPassword" class="link"><a href="#">Forgot Password?</a></div>
                    <div class="clear"></div>
                
                    <div id="SignIn" style="margin: auto;margin-top:10px;cursor: pointer" class="submit_button_doctor">
                            <div style="padding-top:7px;margin-left:165px">Sign In<div id="spin-icon" style="display:none;float:right;margin-right:10px;margin-top:-3px"><i class="icon-spinner icon-spin icon-2x"></i></div></div>
                            
                            
                    </div>
                    </div>
                    
                    <div class="footer-login">
                     <div class="pull-left ">Don't have an account?</div>
                     <div class="pull-right" id="create_account"><a href="#toregister" class="to_register">Create Account</a></div>
                     <div class="clear"></div>
                    </div>
                   
                </form>
                
                <div class="info-message">
                    <div class="alert alert-info">        
                    To create a new account, click "Create Account"
                    </div>
                </div>
              
            </div>
			
			
     
            <div id="register" class="animate form position">
                <form  id="FormSU" method="post" action="AddUser.php" class="form-login"> 
                    <div class="content-login">
                    <a href="/" class="logo"></a>
                    <span id="estaLabel" class="label label-info" style="font-size:18px; margin-left:70px;">MEDICAL USER ACCOUNT</span>
                    <input id="ValorGlobal" type="hidden" value="-1" />
                    <div class="inputs">
                         	
                     	<div class="clear"></div>
                     	<div class="MsgAlarma2" style="height:0px; -webkit-border-radius: 3px; border-radius: 3px; margin-bottom:20px; background-color: rgb(232, 58, 60);">
                     	<!-- -webkit-border-radius: 3px; border-radius: 3px; margin-bottom:20px; background-color:grey;  -->
                     	</div>
                     	<!--<span class="label label-info" style="font-size:14px; margin-bottom:20px;" >Numeric Id</span>
                     	<span id="VIdUsFIXED" class="label label-info" style="float:right; font-size:12px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace;" title="This will be the first part of your flexible identification tag"></span>-->
                     	<input type="hidden" value="" id="VIdUsFIXEDINSERT" Name="VIdUsFIXED">
                     	<input type="hidden" value="" id="VIdUsFIXEDNAMEINSERT" Name="VIdUsFIXEDNAME">
                     	<div class="clear"></div> 
                     	</br>
	                    <i class="icon-calendar first-icon"></i><input id="Year" name="Year" type="text" class="first-input" placeholder="Year of Birth" style="width:100px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" title="Please insert your year of birth" />
	                    <i class="icon-calendar first-icon" style="left:165px; margin-top:-35px;"></i><input id="Month" name="Month" type="text" class="first-input" placeholder="Month" style="width:50px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" title="Please insert your month of birth"/>
	                    <i class="icon-calendar first-icon" style="left:260px; margin-top:-35px;"></i><input id="Day" name="Day" type="text" class="first-input" placeholder="Day" style="width:50px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" title="Please inser your day of birth" />
	                    <div class="clear"></div>
                        

	                    <i class="icon-group first-icon"></i>
	                    <input name="G" type="text" class="first-input" placeholder="" style="width:7px;" readonly/>
	                    <select id="Gender" name="Gender" style="width:150px;" title="Please inser your gender">
		                    <option value="">Select Gender:</option>
		                    <option value="0">Female</option>
		                    <option value="1">Male</option>
		                </select>
	                    
	                
 	                    
 	                    <!--style="border:solid; opacity:100;"-->
 	                    <!--
 	                    <div class="tooltip" title="Order of birth: leave 0 if not part of a multiple birth, otherwise indicate your orther of birth"> 
	                     		<p>This div has a d</p>
	                    </div>
	                    -->
 	                    <i class="icon-info-sign first-icon" style="left:235px; margin-top:-35px;"></i><input id="OrderOB" name="OrderOB" type="text" class="first-input"  title="Order of birth (leave 0 if not part of a multiple birth)" placeholder="Order" style="width:40px; margin-left:10px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" />
 	                    <div class="clear"></div>
 	           
                        </br>
                        
                        <span id="estaLabel2" class="label label-info" style="font-size:14px; margin-bottom:20px;" >Text Id</span>
                     	<span id="VIdUsFIXEDNAME" class="label label-info" style="float:right; font-size:12px; padding:5px 10px 5px 10px; font-family: “Andale Mono”, AndaleMono, monospace;"  title="This will be the second part of your flexible identification tag"></span>
                     	
                     	<div class="clear"></div>
                     	</br>
 
                        <i class="icon-user first-icon"></i><input id="Vname" name="Vname" type="text" class="first-input" placeholder="Name" style="width:120px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" title="Please insert your name (one word only, no signs or special characters)"/>
                        <i class="icon-user first-icon" style="left:185px; margin-top:-35px;"></i><input id="Vsurname"  name="Vsurname" type="text" class="first-input" placeholder="Surname" style="width:120px; border-bottom-right-radius:3px; border-bottom-left-radius:3px;" title="Please insert your surname (one word only, no signs or special characters, no middle initial)"/>
                        <div class="clear"></div>
                        </br>
                        
                        <span class="label label-info" style="font-size:14px; margin-bottom:20px;">Security & Privacy</span>
                     	<img src="images/load/4.gif" alt="" style="margin-left:50px; " id="Wait1">
                        <div class="clear"></div>
                     	</br>
                        <i class="icon-key" style="margin-top:10px"></i><input id="XPassword" name="Password" type="password" class="first-input" placeholder="password" title="Please insert your password (at least 8 characters, use numbers and letters, no punctuation signs or other characters)"/>
                        <div class="clear"></div>
                        <i class="icon-key"></i><input id="XPassword2" name="Password2" type="password" class="intermediate-input" placeholder="repeat password" title="Please repeat your password (at least 8 characters, use numbers and letters, no punctuation signs or other characters)"/>
                        <div class="clear" style="margin-bottom:-10px"></div>
                        <!--<i class="icon-phone"></i>--><input id="phone" name="phone" type="text" class="intermediate-input" placeholder="phone" title="Please insert your phone number (just numbers, no special characters or punctuation signs)" style="width:358px;height:32px;margin-top:-30px"/>
                        <div class="clear" style="margin-top: 9px;"></div>
                        <i class="icon-envelope-alt"></i><input id="email" name="email" type="text" class="last-input" placeholder="email" title="Please insert your email" />
						<div class="clear"></div>
                        <i class="icon-key"></i><input id="accesscode" name="code" type="password" class="last-input" placeholder="Access Code" title="Please Enter the Access Code provided by Health2Me" />
                    </div>
                    
                    <!--<div class="button-login"><input id="BotonEnviaF" type="button"  class="btn btn-large" style="width:100%;" value="Create Account"></div>-->
                    <div style="margin-top:9px"> <div style="width:9%;float:left"><input type="checkbox" id="checkcol_terms" name="cc"/><label style="margin-top:0px;" for="checkcol_terms"><span></span></label> </div><div style="width:91%;float:left"><p>By clicking the button, you agree to the <a href="/legal/tos.html" tabindex="7" target="_blank">terms of service</a> and <a href="/legal/privacy.html" tabindex="9" target="_blank">privacy policy</a></p>
                    </div></div>
                    <div style="margin: auto;margin-top:62px;cursor: pointer" class="submit_button_doctor">
                            <div id="BotonEnviaF" style="padding-top:7px;margin-left:134px">Create Account</div>
                            
                    </div>
                    
                    
                    
                    </div>
                    
                    <div class="footer-login">
                     <div class="pull-left ">Want login?</div>
                     <div class="pull-right" id='sign_in'><a href="#tologin" class="to_register">Sign In</a></div>
                     <div class="clear"></div>
                    </div>
                </form>
            </div>
			
			<div id="SpecialAlert1" style="margin-top:500px; padding:10px; background-color:red; border-radius:3px;display:none">
				<p style="font-size:12px;"><font color="white"><center>We are measuring an Internet Speed less than 4 MBPS</center></p> 
				<p><center>This may prevent some features from functioning properly</center></p> </font>
            </div>
             
        </div>
    </div>  
    
   
    
    

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/jquery-ui.min.js"></script>
   
    <script src="js/bootstrap.min.js"></script>
    <!--<script src="js/bootstrap-datepicker.js"></script>
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
	<script src="js/tiny_mce/tiny_mce.js"></script>-->
    <script src="js/validation/languages/jquery.validationEngine-en.js" charset="utf-8"></script>
	<script src="js/validation/jquery.validationEngine.js" charset="utf-8"></script>
    <!--<script src="js/jquery.jgrowl_minimized.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/jquery.mousewheel.js"></script>
    <script src="js/jquery.jscrollpane.min.js"></script>
    <script src="js/jquery.stepy.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/raphael.2.1.0.min.js"></script>
    <script src="js/justgage.1.0.1.min.js"></script>
	<script src="js/glisse.js"></script>
    
	<script src="js/application.js"></script>-->


    <script src="build/js/intlTelInput.js"></script>
    <script src="js/isValidNumber.js"></script>

<!--    <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.0.min.js"></script>-->
    <script type="text/javascript" src="js/jquery.tooltipster.js"></script>
    <script>
		
				
		var imageAddr = "images/speedtest.jpg" + "?n=" + Math.random();
		var startTime, endTime;
		var downloadSize = 12288;
		var download = new Image();
		download.onload = function () {
			endTime = (new Date()).getTime();
			showResults();
		}
		startTime = (new Date()).getTime();
		download.src = imageAddr;

		function showResults() {
			var duration = (endTime - startTime) / 1000;
			var bitsLoaded = downloadSize * 8;
			var speedBps = (bitsLoaded / duration).toFixed(2);
			var speedKbps = (speedBps / 1024).toFixed(2);
			var speedMbps = (speedKbps / 1024 * 10).toFixed(2);
			/*alert("Your connection speed is: \n" + 
				   speedBps + " bps\n"   + 
				   speedKbps + " kbps\n" + 
				   speedMbps + " Mbps\n" );
			*/
			
			if(speedMbps < 4)
			{
					
				 document.getElementById('SpecialAlert1').style.display = 'block';
			}
				   
				   
		}

		
		$('#create_account').bind("click", function(){
            
            document.getElementById('SpecialAlert').style.display = 'none';
            document.getElementById('login').style.display = 'none';
		});
	
		$('#sign_in').bind("click", function(){
			document.getElementById('SpecialAlert').style.display = 'block';
			document.getElementById('login').style.display = 'block';
		});
	
        $(document).ready(function() {
            //check for the cookie
            cookieValue = $.cookie("inmers_hti");
            if (cookieValue == "main") {
                $('link[rel=stylesheet][href="css/loginCol.css"]').remove();
            }
            if($.browser.msie)
            {
                window.open('ie_error.html','_self',false);
            }
		
		var browser = {
        chrome: false,
        mozilla: false,
        opera: false,
        msie: false,
        safari: false,
		unknown : false
    };
	//alert('here');
	
    var sUsrAg = navigator.userAgent;
	if(sUsrAg.indexOf("Chrome") > -1) {
        browser.chrome = true;
		//alert('chrome');
    } else if (sUsrAg.indexOf("Safari") > -1) {
        browser.safari = true;
		//alert('safari');
    } else if (sUsrAg.indexOf("Opera") > -1) {
        browser.opera = true;
		//alert('opera');
    } else if (sUsrAg.indexOf("Firefox") > -1) {
        browser.mozilla = true;
		//alert('mozilla');
    } else if (sUsrAg.indexOf("MSIE") > -1) {
        browser.msie = true;
		//alert('ie');
    } else 	{
		browser.unknown = true;
		//alert('Unknown Browser');
	}
    
	if(browser.msie || browser.unknown)
	{
		alert("Health2ME does not recommend using this browser");
	}
	
		
        $('.tooltip').tooltipster();
            
        $("#Year,#Month, #Day, #Gender, #OrderOB,#Vname,#Vsurname,#XPassword,#XPassword2,#email,#phone,#accesscode").bind("focusin",function(){
        
             removehighlightederrorfield($(this));
        
        });
       
        $('#Year').bind("focusout", function(){
        
	     CheckValor(1900,2020,$(this));
	     getIdUsFIXED();  		
   		});
        $('#Month').bind("focusout", function(){
         //removehighlightederrorfield($(this));
	     CheckValor(1,12,$(this));
	     getIdUsFIXED();  		
   		});
        $('#Day').bind("focusout", function(){
	     CheckValor(1,31,$(this));
	     getIdUsFIXED();  		
   		});
        $('#Gender').bind("focusout change", function(){
  	      	var dato = $("#Gender").val();
	      	if (dato!='0' && dato!='1'){ alarma ('Please select appropriate gender','#Gender')}else{alarma ('','#Gender')}
	     getIdUsFIXED(); 
	      		
   		});
        $('#OrderOB').bind("focusout", function(){
	   	     CheckValor(0,5,$(this));
	   	     getIdUsFIXED();  		
   		});   
   		$('#Vname').bind("focus", function(){
	      	var dato = $("#Gender").val();
	      	if (dato!='0' && dato!='1'){ alarma ('Please select appropriate gender','#Gender')}
	      	return('abc');
   		});	

   		$('#Vname').bind("focusout", function(){
         CheckValorTipo(12,$(this));
         getIdUsFIXEDNAME();
   		});	
   		$('#Vsurname').bind("focusout", function(){
         CheckValorTipo(12,$(this));
	     getIdUsFIXEDNAME();
   		});			    
    
        $('#XPassword').bind("focusout", function(){
	     CheckValorTipo(11,$(this));
   		});
        $('#XPassword2').bind("focusout", function(){
	     CheckPass('#XPassword','#XPassword2');
	     CheckValorTipo(11,$(this));
   		});
      
        $('#email').bind("focusout", function(){
	     $('#IDRESERV').html($(this).val());
	     //alert($(this).val());
	     //CheckValorTipo(13,$(this));
         CheckValorTipo(15,$(this));  
         valid = $('#ValorGlobal').val();
	     if (valid != '-1'){
             CheckPrevio (1,$(this));
         }     
         });
  		
   		$('#accesscode').bind("focusout", function(){
			CheckValorTipo(14,$(this));
	     
   		});	
        
              //Phone number validation
        $("#phone").intlTelInput();
            
        $('#phone').bind("focusout", function(){
            
           // alert('validating Phone Number');
        
            if($("#phone").intlTelInput("isValidNumber")==false)
            {
                //alert('Invalid Phone Number');
                //$('#Phone').focus();
                //return;
                alarma ('Invalid Phone Number','#phone');
                $('#ValorGlobal').val('-1');
            }
            else
            {	
                //$('#Phone').val($('#Phone').val().replace(/-/g, '')); //remove dashes
                $('#phone').val($('#phone').val().replace(/\s+/g, '')); //remove spaces
                $('#ValorGlobal').val('0')
            }
        
        });
		
            var checktermscondition=0;
        //Adding terms and condition check box validation
            $('input[type=checkbox][id^="checkcol_terms"]').live('click',function() {
    // this represents the checkbox that was checked
	
               if($(this).is(':checked')){

                   checktermscondition=1;
                   alarma('You have read and agree to the terms of service and privacy policy','464');
               }else{

                    checktermscondition=0;
               }

            });

        $('#BotonEnviaF').bind("click", function(){
	        var dato= 0;
	        var todos = 0;
	        
	        $('#Year').trigger('focusout');
	        dato = $('#ValorGlobal').val(); if (dato!=0) {todos=1;}
	        if(dato!=0){
                $('#Month').trigger('focusout');
	       	    dato = $('#ValorGlobal').val(); if (dato!=0) {todos=1;}
            }
            if(dato!=0){
                $('#Day').trigger('focusout');
                dato = $('#ValorGlobal').val(); if (dato!=0) {todos=1;}
            }
            if(dato!=0){
                $('#Gender').trigger('focusout');
                dato = $('#ValorGlobal').val(); if (dato!=0) {todos=1;}
            }
            if(dato!=0){
                $('#OrderOB').trigger('focusout');
                dato = $('#ValorGlobal').val(); if (dato!=0) {todos=1;}
            }
            if(dato!=0){
                $('#Vname').trigger('focusout');
                dato = $('#ValorGlobal').val(); if (dato!=0) {todos=1;}
            }
            if(dato!=0){
                $('#Vsurname').trigger('focusout');
                dato = $('#ValorGlobal').val(); if (dato!=0) {todos=1;}
            }
            if(dato!=0){
                $('#XPassword2').trigger('focusout');
                dato = $('#ValorGlobal').val(); if (dato!=0) {todos=1;}
            }
            if(dato!=0){
                $('#XPassword').trigger('focusout');
                dato = $('#ValorGlobal').val(); if (dato!=0) {todos=1;}
            }
            if(dato!=0){
                $('#email').trigger('focusout');
                dato = $('#ValorGlobal').val(); if (dato!=0) {todos=1;}
            }
            if(dato!=0){
                $('#phone').trigger('focusout');
                dato = $('#ValorGlobal').val(); if (dato!=0) {todos=1;}
            }
            if(dato!=0){
                $('#accesscode').trigger('focusout');
                dato = $('#ValorGlobal').val(); if (dato!=0) {todos=1;}
            }
			
	        if (todos!=1){
                if(checktermscondition==0){

                    alert('Please read the terms & privacy policy agreement and click the checkbox to finalise your account creation process with Health2Me','');

                }else{
                
                      setTimeout(function(){
                      $('#BotonMod').trigger('click');
                      //alert('A confirmation email has been sent, please check your email to confirm your account credentials.');
                      },1000);
                }
            
            }
          
               
    	});

        $('#GrabaDatos').bind("click", function(){
	        $('#FormSU').method="post";
			$('#FormSU').submit();
	    });

        $('.to_register').bind("click", function(){
              
                var cookieValue = $.cookie("inmers_hti");
                if (cookieValue == "col") {
                      url='http://www.hellodoctor.us/register';
                      window.location.assign(url);
                }    
                
        });      
            
            
        $('#SignIn').bind("click", function(){
            
            $("#spin-icon").show();
            
            var url='validatelogin.php';
                    
            setTimeout(function(){
                var jqxhr = $.post( "validatelogin.php",{ Nombre: $("#loginID").val(), Password: $("#loginPass").val()}).done(function( data ) {
                    $("#spin-icon").hide();
                    if(data=='0'){
                         $( "#login" ).effect('shake', {distance: 60},90);
                         //alarma('Invalid user id or password','');

                    }else if (data=='1'){

                        $('#FormLOGIN').method="post";
                        $('#FormLOGIN').submit();                
                    }else{
                        
                        $( "#login" ).effect('shake', {distance: 60},90);
                        //alarma('Invalid user id or password','');
                    }
                });
            },500);
            //$('#FormLOGIN').method="post";
			//$('#FormLOGIN').submit();
                          
                          
        });
            
          //Added code to detect enter key in input fields while signIn
       $('#loginID,#loginPass').keypress(function (e) {
             var key = e.which;
             
             if(key == 13)  // the enter key code
              {
                 //alert('key press'+key);
                $('#SignIn').trigger("click");
                 return false;  
              }
         });
        
            
        $('#ForgotPassword').bind("click", function(){
            
            
            $(".MsgStatus2").html('');
            $(".MsgStatus2").height(0);
            $('#ContenidoModal2').show();
            $('#Proceed').show();
            $('#CloseModal2').hide();
	     	/*$('#ContenidoModal2').html('<p>Please enter your email to reset password:</p><p><input id="ResPas" name="ResPas" type="text" class="last-input" placeholder="email" title="Please insert your email" style="padding-left:10px; margin-top:5px;" /></p>');*/
            $('#ContenidoModal2').html('<p>Please enter your email to reset password:</p><p><span><input id="ResPas" name="ResPas" type="text" class="last-input" placeholder="email" title="Please insert your email" style="padding-left:10px; margin-top:5px;" /></span><span id="icon_ok" style="margin-left:25px;color:#58b820;display:none;"><span style="margin-right:10px">Email Found</span><i class="icon-ok-circle icon-2x"></i></span><span id="icon_error" style="margin-left:25px;color:#f54f4f;display:none;"><span style="margin-right:10px">Invalid Email</span><i class="icon-remove-circle icon-2x"></i></span></p>');
          
	        $('#BotonMod2').trigger('click');
	        
        });
   	   
            var queEmail='';
   	   
   	    $('#Proceed').bind("click", function(){
	     	
            queEmail = $('#ResPas').val();
          	var cadena = '/CheckPrevio2.php?valor='+queEmail+'&queTipo=1';
	        var RecTipo = LanzaAjax (cadena);
	     /*
	        var loc1 =   RecTipo.indexOf("#");
	        var subca1 = RecTipo.substr(loc1+1);
	        var queTip = RecTipo.substr(0, loc1);
	       
	        var loc2 =   subca1.indexOf("#");
	        var subca2 = subca1.substr(loc2+1);
	        var IdUsFIXED = subca1.substr(0, loc2);

	        var loc3 =   subca2.indexOf("#");
	        var subca3 = subca2.substr(loc3+1);
	        var IdUsFIXEDNAME = subca2.substr(0, loc3);
	       
	        var loc4 =   subca3.indexOf("#");
	        var subca4 = subca3.substr(loc4+1);
	        var IdUsRESERV = subca3.substr(0, loc4);
            */
            var queTip = parseInt(RecTipo);
            

	        /*
	        alert (RecTipo);
	        alert (queTip);
	        alert (IdUsFIXED);
		    alert (IdUsFIXEDNAME);
		    alert (IdUsRESERV);
            */
	        
          if (queTip==0)
	        {//alert ('Patient not found in database. Please try with another email address.');
                $('#icon_error').show();
                setTimeout(function() {nxtstep(queTip);},800);
            }
	        else
	        {
		        if (queTip>1){//alert ('More than one user for that email address. Please contact health2.me support.');
                        $('#icon_error').show();
                        setTimeout(function() {nxtstep(queTip);},800);
                        }
	        else{
                
                
                $('#icon_ok').show();
                nxtstep(queTip);
		        // ENVIO DE EMAIL DE RECORDATORIO
                
	         	}
	        }  
	        
        });  
            
            
         function nxtstep(val){
              setTimeout(function() {
               $('#ContenidoModal2').hide();
               $('#Proceed').hide();
               $('#CloseModal2').show();
                           
              if(val==0){
                   showstatus('Patient not found in database. Please try with another email address.');
              }else if(val>1){
                  
                   showstatus('More than one user for that email address. Please contact health2.me support.');
              }else{
                    var cadena = '/ResetUser.php?email='+queEmail;
                        //alert (cadena);
                        var RecTipo2 = LanzaAjax (cadena);
                        //alert(RecTipo2);
                        //var mensaje = 'Email already exist';
                         showstatus(RecTipo2);
              }
                

                             
                         }, 50);
         }
   	    $('#Wait1')
   	    	.hide()  // hide it initially
   	    	.ajaxStart(function() {
	   	    	//alert ('ajax start');
	   	    	$('#Wait1').show();
	   	    	$(this).show();
	   	    	})
	   	    .ajaxStop(function() {
		   	    $(this).hide();
		}); 
   	function validateEmail(email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if( !emailReg.test( email ) ) {
            return false;
        } else {
            return true;
        }
     }	       	
     function CheckValor(minimo, maximo,selector){
	     var dato = $(selector).val();
	     var quevalor = dato;
	     var pasa = 1;
	     if (!form_input_is_numeric(dato)){
		     var mensaje = 'Value not numeric';
		     alarma (mensaje,selector);
		     quevalor = '';
		     pasa = 0;
		     $('#ValorGlobal').val('-1');
             highlighterrorfield(selector);
	     }

	     if (dato>maximo){
		     var mensaje = 'Value too high';
		     alarma (mensaje,selector);
		     quevalor = '';
		     pasa =0;
		     $('#ValorGlobal').val('-1');
             highlighterrorfield(selector);
	     }	

	     if (dato<minimo){
		     var mensaje = 'Value too low';
		     alarma (mensaje,selector);
		     quevalor = '';
		     pasa =0;
		     $('#ValorGlobal').val('-1');
             highlighterrorfield(selector);
             //$("#"+selector).css{'border','2px solid rgb(232, 58, 60)'};
	     }	

	     if (pasa==1) {alarma('',selector); $('#ValorGlobal').val('0'); }
	     $(selector).val(quevalor);
	 }
            
    
     function CheckValorTipo(tipo,selector){
	     var dato = $(selector).val();
	     var quevalor = dato;
	     var pasa = 1;
	     if (tipo>10){
		     switch (tipo){
				 case 11: if (dato.length<8)   // 11: COMPRUEBA QUE LA LONGITUD SEA AL MENOS DE 8 CARACTERES
				 		  {
				 		  var mensaje = 'Value must be at least 8 characters long';
			     		  alarma (mensaje,selector);
			     		  quevalor = '';
			     		  pasa = 0;
			     		  $('#ValorGlobal').val('-1');
                          highlighterrorfield(selector);
			     		  }
			     		  break;
				 case 12: if (dato.length<2)   // 12: COMPRUEBA QUE LA LONGITUD SEA AL MENOS DE 2 CARACTERES
				 		  {
				 		  var mensaje = 'Value must be at least 2 characters long';
			     		  alarma (mensaje,selector);
			     		  quevalor = '';
			     		  pasa = 0;
			     		  $('#ValorGlobal').val('-1');
                          highlighterrorfield(selector);
			     		  }
			     		  break;
				 case 13: if (dato.length<6)   // 13: COMPRUEBA QUE LA LONGITUD SEA AL MENOS DE 6 CARACTERES
				 		  {
				 		  var mensaje = 'Value must be at least 6 characters long';
			     		  alarma (mensaje,selector);
			     		  quevalor = '';
			     		  pasa = 0;
			     		  $('#ValorGlobal').val('-1');
                          highlighterrorfield(selector);
			     		  }
			     		  break;
				 case 14: var RecTipo=LanzaAjax ("CheckPrivateCode.php?code="+dato);
						  if(RecTipo=='false'){
							var mensaje = 'Invalid Access Code';
						    alarma(mensaje,selector);
							quevalor='';
							pasa=0;
							$('#ValorGlobal').val('-1');
						    highlighterrorfield(selector);
						  }
						  break;
                case 15: if (validateEmail(dato)==false)   // check for valid email format
				 		  {         
				 		  var mensaje = 'Value must be a valid email format';
                          //alert(dato+"  "+mensaje);
                          alarma (mensaje,selector);
			     		  quevalor = '';
			     		  pasa = 0;
			     		  $('#ValorGlobal').val('-1');
                          highlighterrorfield(selector);
			     		  } 
                          break;
			     default: var mensaje = 'Unknown error';
			     		  alarma (mensaje,selector);
			     		  quevalor = '';
			     		  pasa = 0; 
			     		  $('#ValorGlobal').val('-1');
			     		  break;		  
			     
		     }
	     }

	     if (pasa==1) {alarma('',selector); $('#ValorGlobal').val('0'); }
	     $(selector).val(quevalor);
	 }

    
     function CheckPass(selector1,selector2){
     	var dato1 = $(selector1).val();
        var dato2 = $(selector2).val(); 
        if (dato1 != dato2)
        {
	       	 var mensaje = 'Values must be the same';
		     alarma (mensaje,selector1);
		     alarma (mensaje,selector2);
		     quevalor = '';
			 $('#ValorGlobal').val('-1');
            highlighterrorfield(selector1);
            highlighterrorfield(selector2);
        }	     else{
		     alarma ('',selector1);
		     alarma ('',selector2);
		     $('#ValorGlobal').val('0'); 
	     }

     	//var dato = pass1.length; 
      
	 }
	
	 function CheckPrevio(tipo,selector){
	     var dato = $(selector).val();
	     var quevalor = dato;
	     var pasa = 1;
	     
	        
	     var queTipo=1;
        if (dato != ''){
	     var cadena = 'CheckPrevio.php?valor='+dato+'&queTipo='+queTipo;
	     var RecTipo = LanzaAjax (cadena);
	    
             if (RecTipo>0){
                 var mensaje = 'Email already exists';
                 alarma (mensaje,selector);
                 quevalor = '';
                 pasa = 0;
                 $('#ValorGlobal').val('-1');
                  highlighterrorfield(selector);
             }
       
            
        }else {
          pasa=1;
         }
	     if (pasa==1) {alarma('',selector); $('#ValorGlobal').val('0'); }
	     $(selector).val(quevalor);
	 }

            
    function highlighterrorfield(selectr){
         // alert('error'); 
         $(selectr).css('border', '2px solid rgb(232, 58, 60)'); 
    
     }
    
    function removehighlightederrorfield(selectr){
         // alert('error'); 
         $(selectr).css('border', ''); 
    
     }
     
     
	function LanzaAjax (DirURL)
		{
		var RecTipo = 'SIN MODIFICACIÓN';
		$.ajax(
           {
           url: DirURL,
           dataType: "html",
           async: false,
           complete: function(){ //alert('Completed');
                    },
           success: function(data) {
                    if (typeof data == "string") {
                                RecTipo = data;
                                }
                     }
            });
		return RecTipo;
		}    


	 function alarma(mensaje,selector)
	 {
	 /*if (mensaje=='') { var tama = '0px'; $(selector).css ('background-color','white'); }else { var tama = '40px'; $(selector).css ('background-color','#FFF8DC'); }
	 
	 $('.MsgAlarma2').animate({
		    height: tama
		      }, 500, function() {
			      $(".MsgAlarma2").html('<p style="font-size:18px; color:white; margin:10px; top:10px; position:relative;">'+mensaje+'</p>');
		});*/
         
          if(selector=='464'){
            $('#error_bar').css('background-color','#0590d0');
          }
          $("#error_msg").html('<p style="font-size:18px; color:white;text-align:center">'+mensaje+'</p>')
           if (mensaje=='') {
           }else{
               
               
                    $("#close_error_bar").hide();
                    $('#error_bar').fadeIn('slow');
                    $('#error_bar').fadeOut(2500);
                         
              /* $('#error_bar').slideDown('slow', function () {
                    // Animation complete.
               });*/

                $('html,body').animate({
                scrollTop: $("#error_bar").offset().top},
                'slow');
           }
	 }
            
    
     $("#close_error_bar").bind("click", function(){
     
     
        $("#error_bar").slideUp('slow');
     
        $("#error_msg").html('');
     
     
     
     });
            
            
    function showstatus(statusmsg){
         
         //if (statusmsg=='') { var tama = '0px'; $(selector).css ('background-color','white'); }else { var tama = '40px'; $(selector).css ('background-color','#FFF8DC'); }
     
        if (statusmsg=='') { var tama = '0px';}else{ var tama = '60px';}
         
        
         $('.MsgStatus2').animate({
		    height: tama
		      }, 500, function() {
			      $(".MsgStatus2").html('<p style="font-size:18px; color:white; margin:10px; top:10px; position:relative;">'+statusmsg+'</p>');
		});
     
     }
            
            
    

     function getIdUsFIXED(){
    		var year = $("#Year").val();
    		var month = $("#Month").val();
    		var day = $("#Day").val();
    		
    		var fnacnum = year+FormatNumberLength(month, 2)+FormatNumberLength(day, 2);
    		var gender = chkb($("#Gender").is(':checked'));
   		
    		var gender = $("#Gender").val();
    		var orderOB = $("#OrderOB").val();
    		if (gender==0){ gender='0';}
    		if (orderOB==0){ orderOB='0';}
   		
    		var VIdUsFIXED = fnacnum+gender+orderOB;
   			$('#VIdUsFIXED').html(VIdUsFIXED);
   			$('#VIdUsFIXED2').html(VIdUsFIXED);
   			$('#VIdUsFIXEDINSERT').val(VIdUsFIXED);
   		
    	}
 
     function getIdUsFIXEDNAME(){
    		var vname = $("#Vname").val().toLowerCase().replace(".","").replace(" ","");
    		var vsurname = $("#Vsurname").val().toLowerCase().replace(".","").replace(" ","");
    		
    		var VIdUsFIXEDNAME = vname+'.'+vsurname;
    		$('#VIdUsFIXEDNAME').html(VIdUsFIXEDNAME);
    		$('#VIdUsFIXEDNAME2').html(VIdUsFIXEDNAME);
    		$('#VIdUsFIXEDNAMEINSERT').val(VIdUsFIXEDNAME);
    	}

    function chkb(bool){
	    if(bool)
	    	return 1;
	    	return 0;
	   }
	 
	function form_input_is_numeric(input){
		   return !isNaN(input);
		   }
      
	function FormatNumberLength(num, length) {
    	var r = "" + num;
    	while (r.length < length) {
        	r = "0" + r;
        	}
        return r;
        }
    
   
       
    });
    </script>
  </body>
</html>

