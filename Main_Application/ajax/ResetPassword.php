<?php
define('INCLUDE_CHECK',1);
require "logger.php";
 

$CodigoBusca = $_GET['token'];

require("environment_detailForLogin.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];
$tbl_name="doctors"; // Table name
					
// Connect to server and select databse.
$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");

?>
<!DOCTYPE html>
<html lang="en"  class="body-error"><head>
    <meta charset="utf-8">
    <title>Inmers - Password RESET</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/login.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">

	<!--<link rel="stylesheet" href="css/icon/font-awesome.css">-->
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
	<link rel="stylesheet" type="text/css" href="css/tooltipster.css" />


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

  <body>
      <div id="error_bar" style="position: relative;width:100%;height:53px;color:white;background-color: rgb(232, 58, 60);display:none"><div id="error_msg" style="position: absolute;margin-top:15px;margin-left:440px "></div>
        <div id="close_error_bar" style="margin-left:1200px;">
            <i class="icon-remove-circle icon-4x"></i>
       </div></div>
      
	<div id="container_demo" >
		 <div id="wrapper" style="top:50px;">
			 
         	<div class="animate form position">
                <input type="hidden" id="tokenID" value="<?php echo $CodigoBusca; ?>"/>
                <form  id="resetpwdForm" method="post" action="reset.php" class="form-login"> 
                    <div class="content-login">
                    <a href="#" class="logo"></a>
                    <span id="estaLabel" class="label label-info" style="font-size:18px; margin-left:70px;">MEDICAL USER ACCOUNT</span>
                    <input id="ValorGlobal" type="hidden" value="-1" />
                    <div class="inputs">
                         	
                     	<div class="clear" style="margin-bottom:10px"></div>
                     	
                       <!-- <div class="MsgAlarma2" style="height:0px; -webkit-border-radius: 3px; border-radius: 3px; margin-bottom:20px; background-color:#F77E45">
                     	<!-- -webkit-border-radius: 3px; border-radius: 3px; margin-bottom:20px; background-color:grey;  -->
                     	<!--</div> -->
                        <span class="label label-info" style="font-size:14px; margin-bottom:20px;">Password Reset</span>
                        <div class="clear"></div>
                     	</br>
						<i class="icon-user first-icon"></i><input id="Nombre"  name="Nombre" type="text" class="first-input" placeholder="e-mail used for signup" title="Please enter your emailID"/>
                        <div class="clear"></div>
                        <i class="icon-key"></i><input id="XPassword" name="Password" type="password" class="intermediate-input" placeholder="new password" title="Please enter your new password (at least 8 characters, use numbers and letters, no punctuation signs or other characters)"/>
                        <div class="clear"></div>
                        <i class="icon-key"></i><input id="XPassword2" name="Password2" type="password" class="intermediate-input" placeholder="repeat password" title="Please repeat your new password (at least 8 characters, use numbers and letters, no punctuation signs or other characters)"/>
                        <div class="clear"></div>
                       	<!--<input id="accesscode" name="code" type="password" class="last-input" placeholder="Health2me Access Code" title="Please Enter the Access Code provided by Health2Me" />-->
                    </div>
                    
                    
                    <!--<div class="button-login"><input id="BotonEnviaF" type="submit"  class="btn btn-large" style="width:100%;" value="Reset Password"></div>-->
                    <div style="margin: auto; margin-top: 10px;">
                            <button class="submit_button_doctor" id="BotonEnviaF">Reset Password</button>
                            
                    </div>
                    
                    
                    </div>
                    
                    <div class="footer-login">
                     <!--<div class="pull-left ">Want login?</div>
                     <div class="pull-right"><a href="/SignIn.html" class="to_register">Sign In</a></div>-->
                     <div class="clear"></div>
                    </div>
                </form>
            </div>
			
		</div>
	</div>
	
	  <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>

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
    
	<script src="js/application.js"></script>-->

<!--    <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.0.min.js"></script>-->
    <script type="text/javascript" src="js/jquery.tooltipster.js"></script>
    <script>
	
        $(document).ready(function() {
            
        $('.tooltip').tooltipster();
       
	   
        /* $('#Nombre').bind("focusout", function(){
           // alert('In Nombre focusout');
	       CheckValorTipo(12,$(this));
   		});*/
        
        var emailID=getEmailID($('#tokenID').val());
        $('#Nombre').val(emailID);
        $("#Nombre" ).attr( "disabled", "disabled" );
            
        $('#XPassword').bind("focusout", function(){
	     CheckValorTipo(11,$(this));
   		});
        $('#XPassword2').bind("focusout", function(){
	     CheckPass('#XPassword','#XPassword2');
	     CheckValorTipo(11,$(this));
   		});
		
		$('#accesscode').bind("focusout", function(){
		 CheckValorTipo(14,$(this));
	     
   		});	
            
               
        $('#BotonEnviaF').bind("click", function(){
	        var dato= 0;
	        var todos = 0;
            
          
	        $("#Nombre").removeAttr("disabled");
            $('#Nombre').val(emailID);
       
	        //$('#Nombre').trigger('focusout');
	        //dato = $('#ValorGlobal').val(); if (dato!=0) {todos=1;}
	        $('#XPassword2').trigger('focusout');
	        dato = $('#ValorGlobal').val(); if (dato!=0) {todos=1;}
	        $('#XPassword').trigger('focusout');
	        dato = $('#ValorGlobal').val(); if (dato!=0) {todos=1;}
			
	        if (todos!=1){
                
                 $('#resetpwdForm').method="post";
			     $('#resetpwdForm').submit();
                
            
            }else{alert('Invalid values entered!');}
    	});
			
		 function CheckValorTipo(tipo,selector){
	     var dato = $(selector).val();
	     var quevalor = dato;
	     var pasa = 1;
	     if (tipo>10){
		 
		 switch (tipo){
                 
         /* case 12: if ((function(token){

               // alert('validating email entered');
                 //var userid=$('#Nombre').val(); 
                 //var url='validateResetEmailID.php?token='+token+'&ID='+userid+'&whichone=1';
                var url='validateResetEmailID.php?token='+token+'&whichone=1';
                //alert(url);
                 var check=LanzaAjax(url);
                //alert('emailvalidationreturn'+check);
                 if(check=='0'){return 1;}else{return check;}}
              )($('#tokenID').val()))  // 11: COMPRUEBA QUE LA LONGITUD SEA AL MENOS DE 8 CARACTERES
          {
          var mensaje = 'Invalid EmailID or UserID';
          alarma (mensaje,selector);
          quevalor = '';
          pasa = 0;
          $('#ValorGlobal').val('-1');
          }
          break;*/
		 
		  case 11: if (dato.length<8)   // 11: COMPRUEBA QUE LA LONGITUD SEA AL MENOS DE 8 CARACTERES
				 		  {
				 		  var mensaje = 'Value must be at least 8 characters long';
			     		  alarma (mensaje,selector);
			     		  quevalor = '';
			     		  pasa = 0;
			     		  $('#ValorGlobal').val('-1');
			     		  }
			     		  break;
		  case 14: var RecTipo=LanzaAjax ("/CheckPrivateCode.php?code="+dato);
						  if(RecTipo=='false'){
							var mensaje = 'Invalid Access Code';
						    alarma(mensaje,selector);
							quevalor='';
							pasa=0;
							$('#ValorGlobal').val('-1');
						  
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
            
     function getEmailID (token){
     
         var url='validateResetEmailID.php?token='+token+'&whichone=1';
         //alert(url);
         var check=LanzaAjax(url);
         //alert('emailvalidationreturn'+check);
         if(check=='0'){$("#close_error_bar").hide();alarma('Request invalid or expired! <a href="SignIn.html#" style="color:white">click here to make a new request for password reset</a>','')}else{return check;}
     
     }
     
            
     function LanzaAjax (DirURL){
        //alert('in ajax call');
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
        }	     else{
		     alarma ('',selector1);
		     alarma ('',selector2);
		     $('#ValorGlobal').val('0'); 
	     }

     	//var dato = pass1.length; 
     }
	 
	 function alarma(mensaje,selector)
	 {
	 /*if (mensaje=='') { var tama = '0px'; if (selector!='')$(selector).css ('background-color','white'); }else { var tama = '40px'; if (selector!='') $(selector).css ('background-color','#FFF8DC');}
     
	 
	 $('.MsgAlarma2').animate({
		    height: tama
		      }, 500, function() {
			      $(".MsgAlarma2").html('<p style="font-size:18px; color:white; margin:10px; top:10px; position:relative;">'+mensaje+'</p>');
		});*/

    if(mensaje==''){

    }else{

        $("#error_msg").html('<p style="font-size:18px; color:white;text-align:center">'+mensaje+'</p>')
            $('#error_bar').slideDown('slow', function () {
                  // Animation complete.
             });
              
              $('html,body').animate({
              scrollTop: $("#error_bar").offset().top},
              'slow');
        }
      
      
       $("#close_error_bar").bind("click", function(){
       

           $("#error_bar").slideUp('slow');
           $("#error_msg").html('');
       
       });

    }
	 
         
	 
	});
 </script>
	
</body>
</html>
     	
