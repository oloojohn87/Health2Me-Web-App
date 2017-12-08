<?php
require("environment_detailForLogin.php");
session_start();
if(isset($_SESSION['Acceso']))
    unset($_SESSION['Acceso']); 

//$code=$_GET['Confirm'];
$userlogin=$_GET['userlogin'];
$IdUsu=$_GET['idp'];

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
	<input type="hidden" name="uid"/>
	<input type="hidden" name="pass"/>
     	  <!--- VENTANA MODAL  ---> 
   	  <!--<button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button>-->
   	<!--  <div id="header-modal" class="modal hide" style="display: none; height:320px;" aria-hidden="true">
 <button id="BotonMod" data-target="#header-modal" data-toggle="modal" class="btn" style="float:right; margin-right:10px; display:none;"><i class="icon-indent-left"></i>New</button>

         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <h4>New User Account</h3>
         </div>
         
         <div class="modal-body" id="ContenidoModal" style="height:170px;">
           <!--<p>
           You will receive an email with a confirmation link at the email address you indicated. Please follow the link to verify your identity and complete the sign up process. 
           </p>-->
         <!--   <p>
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
           <!--  <a href="#" class="btn btn-success" data-dismiss="modal" id="GrabaDatos">Proceed</a>
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal">Cancel</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL  ---> 
      
      <!--- VENTANA MODAL  2 ---> 
   	  <!--<button id="BotonModal" data-target="#header-modal" data-toggle="modal" class="btn btn-warning" style="display: none;">Modal with Header</button> -->
   	  <div id="header-modal2" class="modal hide" style="display: none; height:320px;" aria-hidden="true">
 <button id="BotonMod2" data-target="#header-modal2" data-toggle="modal" class="btn" style="float:right; margin-right:10px; display:none;"><i class="icon-indent-left"></i>New</button>

         <div class="modal-header">
             <button class="close" type="button" data-dismiss="modal">×</button>
                 <h4>New User Account</h3>
         </div>
         
         <div class="modal-body" id="ContenidoModal2" style="height:170px;">
           <p>
           Reset your password. 
           </p>
           
         </div>
         <input type="hidden" id="queId">
        
         <div class="modal-footer">
	         <!--<input type="button" class="btn btn-success" value="Confirm" id="ConfirmaLink">
             <a href="#" class="btn btn-success" data-dismiss="modal" id="GrabaDatos">Proceed</a> -->
             <a href="#" class="btn btn-primary" data-dismiss="modal" id="CloseModal2">OK</a>
         </div>
      </div>  
	  <!--- VENTANA MODAL 2 ---> 

     
    <div id="container_demo" >
        <!--<a class="hiddenanchor" id="toregister"></a>-->
        <!--<a class="hiddenanchor" id="tologin"></a> -->
        <div id="wrapper" style="top:50px;">
            <div id="login" class="animate form position">
           <form  id="Formlogin" method="post" action="login.php" class="form-login" > 
                    <div class="content-login">

                    <a href="#" class="logo"></a>
                            <span class="label label-info" style="font-size:18px; margin-left:70px;">MEDICAL USER ACCESS</span>
                   
                    <div class="inputs">
                        <i class="icon-user first-icon"></i><input id="Nombre" name="Nombre" type="text" class="first-input" placeholder="<?php echo $userlogin;?>" value="<?php echo $userlogin;?>" />
                        <div class="clear"></div>
                        <i class="icon-key"></i><input id="Password" name="Password" type="password" class="last-input" placeholder="password" />
						<input type="hidden" name="PatientID" value="<?php echo $IdUsu?>"/>
						<input type="hidden" name="refid" value="9a9a2rtd"/>
                    </div>
                    
                    <div class="remember">
                    	<input type="checkbox" id="c2" name="cc" checked="checked" />
            			<label for="c2"><span></span> Remember Me</label>
                    </div>
                    <div id="ForgotPassword" class="link"><a href="#">Forgot Password?</a></div>
                    <div class="clear"></div>
                    <div class="button-login"><input type="button"  id="signin_ref" class="btn btn-large btn-primary" style="margin-left:130px" value="Sign In"/></div>
					<div class="clearfix"></div>
					<div class="clearfix"></div>
                    </div>
                    <div class="footer-login">
                     <div class="pull-left "></div>
                     <div class="pull-right"></div>
                     <div class="clear"></div>
                    </div>          
                   
                </form>
                
                
              
            </div> 
                 
        </div>
    </div>  
    
   
    
    

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

<!--    <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.0.min.js"></script>-->
    <script type="text/javascript" src="js/jquery.tooltipster.js"></script>
    <script>
		
        $(document).ready(function() {
            
            if($.browser.msie)
            {
                window.open('ie_error.html','_self',false);
            }
            
        $('.tooltip').tooltipster();
       
        
        $('#Nombre').attr('readonly','readonly');
		
        $('#ForgotPassword').bind("click", function(){
	     	
	     	$('#ContenidoModal2').html('<p>Please enter your email to reset password:</p><p><input id="ResPas" name="ResPas" type="text" class="last-input" placeholder="email" title="Please insert your email" style="padding-left:10px; margin-top:5px;" /></p>');
	        $('#BotonMod2').trigger('click');
	        
        });	   
   	    $('#CloseModal2').bind("click", function(){
	     	
	     	var queEmail = $('#ResPas').val();
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
	        {alert ('User not found in database. Please try with another email address.');}
	        else
	        {
		        if (queTip>1){alert ('More than one user for that email address. Please contact health2.me support.');}
	        else{
		        // ENVIO DE EMAIL DE RECORDATORIO
		        var cadena = '/ResetUser.php?email='+queEmail;
		        //alert (cadena);
		        var RecTipo2 = LanzaAjax (cadena);
				alert(RecTipo2);
	         	}
	        }
	        
	        
        });
		
		
		$('#signin_ref').live('click',function(){
		
		   //alert("Logging");
		   $('#Formlogin').method="post";
		   $('#Formlogin').submit();
		
		
		
		});
		
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
	     }

	     if (dato>maximo){
		     var mensaje = 'Value too high';
		     alarma (mensaje,selector);
		     quevalor = '';
		     pasa =0;
		     $('#ValorGlobal').val('-1');
	     }	

	     if (dato<minimo){
		     var mensaje = 'Value too low';
		     alarma (mensaje,selector);
		     quevalor = '';
		     pasa =0;
		     $('#ValorGlobal').val('-1');
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
			     		  }
			     		  break;
				 case 12: if (dato.length<2)   // 12: COMPRUEBA QUE LA LONGITUD SEA AL MENOS DE 2 CARACTERES
				 		  {
				 		  var mensaje = 'Value must be at least 2 characters long';
			     		  alarma (mensaje,selector);
			     		  quevalor = '';
			     		  pasa = 0;
			     		  $('#ValorGlobal').val('-1');
			     		  }
			     		  break;
				 case 13: if (dato.length<6)   // 13: COMPRUEBA QUE LA LONGITUD SEA AL MENOS DE 6 CARACTERES
				 		  {
				 		  var mensaje = 'Value must be at least 6 characters long';
			     		  alarma (mensaje,selector);
			     		  quevalor = '';
			     		  pasa = 0;
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
	
	 function CheckPrevio(tipo,selector){
	     var dato = $(selector).val();
	     var quevalor = dato;
	     var pasa = 1;
	     
	     var queTipo=1;
	     var cadena = 'CheckPrevio.php?valor='+dato+'&queTipo='+queTipo;
	     var RecTipo = LanzaAjax (cadena);
	    
	     //alert (RecTipo);
	     
	     if (RecTipo>0){
		     var mensaje = 'User/Value already exists';
		     alarma (mensaje,selector);
		     quevalor = '';
		     pasa = 0;
		     $('#ValorGlobal').val('-1');
	     }

	     if (pasa==1) {alarma('',selector); $('#ValorGlobal').val('0'); }
	     $(selector).val(quevalor);
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
	 if (mensaje=='') { var tama = '0px'; $(selector).css ('background-color','white'); }else { var tama = '40px'; $(selector).css ('background-color','#FFF8DC'); }
	 
	 $('.MsgAlarma2').animate({
		    height: tama
		      }, 500, function() {
			      $(".MsgAlarma2").html('<p style="font-size:18px; color:white; margin:10px; top:10px; position:relative;">'+mensaje+'</p>');
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

