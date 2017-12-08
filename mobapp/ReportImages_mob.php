<?php

 require("environment_detail.php");
 set_time_limit(180);
 
 $MedID=28;
 
?>

<!DOCTYPE html> 
<html> 
<head> 
	<title>Page Title</title> 
	
	<meta name="viewport" content="width=device-width, initial-scale=1"> 

	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
	<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
	<link rel="stylesheet" type="text/css" href="mobapp/GridLoadingEffects/css/default.css" />
	<link rel="stylesheet" type="text/css" href="mobapp/GridLoadingEffects/css/component.css" />
	<script src="mobapp/GridLoadingEffects/js/modernizr.custom.js"></script>

</head> 
<body> 

<div data-role="page" data-theme="d">

	<div data-role="header" data-theme="a" data-position="fixed">
		<h1><img src="mobapp/Icono_H2M.png" alt="" ></h1>
	</div><!-- /header -->

	<div data-role="content" data-theme="d">	
		<div class="container">
			<!-- Top Navigation -->
			
			<ul class="grid effect-3" id="grid">
				
			
			</ul>
		</div>
		<script src="mobapp/GridLoadingEffects/js/masonry.pkgd.min.js"></script>
		<script src="mobapp/GridLoadingEffects/js/imagesloaded.js"></script>
		<script src="mobapp/GridLoadingEffects/js/classie.js"></script>
		<script src="mobapp/GridLoadingEffects/js/AnimOnScroll.js"></script>
		<script type="text/javascript">
            
        $(document).on("pageinit", function(){
		
			 $.mobile.loader.prototype.options.text = "loading";
			  $.mobile.loader.prototype.options.textVisible = false;
			  $.mobile.loader.prototype.options.theme = "a";
			  $.mobile.loader.prototype.options.html = "";
					
			//window.setTimeout(function(){ $("#login").click();}, 2000);
            /*settimeOut(function(){
			$("#login").trigger ('click');},4000);
            }); */   
            //apply overrides here
			var cadenaGUD = '<?php echo $domain;?>/getReportImages_mob.php';
			var RecTipo= LanzaAjax(cadenaGUD);
			$('#grid').load(cadenaGUD);
			
			setTimeout(function() {
			new AnimOnScroll( document.getElementById( 'grid' ), {
				minDuration : 0.4,
				maxDuration : 0.6,
				viewportFactor : 0.2
			} ); },4000);
			
				
				
				
				var theme = $.mobile.loader.prototype.options.theme,
				  msgText = $.mobile.loader.prototype.options.text,
				  textVisible = $.mobile.loader.prototype.options.textVisible,
				  html = "";
				
				$.mobile.loading( 'show', {
				  text: msgText,
				  textVisible: textVisible,
				  theme: theme,
				  html: html
				 });
			
				setTimeout(function(){				 
				$.mobile.loading( 'hide' );
				},4000);
			
			$(".attachments").live('click',function(){
				
				//alert('reached here');
				var screen=this;
				//alert (RecTipo);
				var queImg = $(".queIMG", screen).attr("id");
				var Channel=$(".channel",screen).attr("id");
				var extensionR = queImg.substr(queImg.length-3,3);
				var ImagenRaiz = queImg.substr(0,queImg.length-4);
				var subtipo = queImg.substr(3,2);  // Para los casos en que eMapLife+ (PROF) ya sube las imagenes a AMAZON y no a GODADDY
				//$ImageRaiz = substr($row['RawImage'],0,strlen($row['RawImage'])-4);
				
				
				if (extensionR=='pdf')
				{
					var cadena = '<?php echo $domain;?>/DecryptFile.php?rawimage='+queImg+'&queMed='+<?php echo $MedID ?>;
					var RecTipo = LanzaAjax (cadena);
		   	
					var contenTHURL = '<?php echo $domain; ?>/temp/<?php echo $MedID ;?>/PackagesTH_Encrypted/'+ImagenRaiz+'.png';  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
					var contenURL =   '<?php echo $domain; ?>/temp/<?php echo $MedID ;?>/Packages_Encrypted/'+ImagenRaiz+'.'+extensionR;  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
					//var conten = '<img id="ImagenN" src="'+contenURL+'" alt="">';
					//var conten =  '<iframe id="ImagenN" style="border:1px solid #666CCC; margin:0 auto; display:block;" title="PDF" src="'+contenURL+'" alt="'+queId+'" frameborder="1" scrolling="auto" height="850" width="600" ></iframe>';
					var conten = '<iframe src="http://docs.google.com/viewer?url='+contenURL+'&embedded=true" style="border:1px solid #666CCC; margin:0 auto;display:block;width:auto;height:400px;" frameborder="0"></iframe>';
					//var contenTH = '<img id="ImagenTH"  src="'+contenTHURL+'" alt="">';
				}
				else{
					if(parseInt(Channel)==7){
						var cadena = '<?php echo $domain;?>/DecryptFile.php?rawimage='+queImg+'&queMed='+<?php echo $MedID;?>;
						var RecTipo = LanzaAjax (cadena);
		   	
						//var contenTHURL = '<?php echo $domain; ?>/temp/<?php echo $MedID ;?>/PackagesTH_Encrypted/'+queImg; 
						
						var contenURL =   '<?php echo $domain; ?>/temp/<?php echo $MedID ;?>/Packages_Encrypted/'+queImg;
						//var conten = '<img src="<?php echo $domain;?>/temp/<?php echo $MedID ;?>/Packages_Encrypted/'+queImg+'" style="border:1px solid #666CCC; margin:0 auto; display:block;">';
						var conten='<img src="'+contenURL+'" style="border:1px solid #666CCC; margin:0 auto;display:block;width:auto;height:400px;" frameborder="0"></img>';
					}else{
					if (subtipo=='XX') { 
					
						var cadena = '<?php echo $domain;?>/DecryptFile.php?rawimage='+queImg+'&queMed='+<?php echo $MedID;?>;
						var RecTipo = LanzaAjax (cadena);
		   	
						var contenURL =   '<?php echo $domain; ?>/temp/<?php echo $MedID ;?>/Packages_Encrypted/'+queImg;
						//var contenTHURL = '<?php echo $domain; ?>/temp/<?php echo $MedID ;?>/PackagesTH_Encrypted/'+queImg; 
						//var conten = '<img src="<?php echo $domain;?>/temp/<?php echo $MedID ;?>/Packages_Encrypted/'+queImg+'" style="border:1px solid #666CCC; margin:0 auto; display:block;">';
						var conten='<img src="'+contenURL+'" style="border:1px solid #666CCC; margin:0 auto;display:block;width:auto;height:400px;" frameborder="0"></img>';
						}
					else{ 
						//var contenTHURL = '<?php echo $domain; ?>/eMapLife/PinImageSetTH/'+queImg;
						
						var contenURL='<?php echo $domain; ?>/eMapLife/PinImageSet/'+queImg;
						//var conten = '<img src="<?php echo $domain; ?>/eMapLife/PinImageSet/'+queImg+'" style="border:1px solid #666CCC; margin:0 auto; display:block;">';
						var conten='<img src="'+contenURL+'" style="border:1px solid #666CCC; margin:0 auto;display:block;width:auto;height:400px;" frameborder="0"></img>';
						}  // PONER /PinImageSetTH/ en lugar de /PinImageSet/ PARA CARGAR THUMBNAILS EN LUGAR DE IMAGENES COMPLETAS
					
				//if(urlExists(contenTHURL)) {}else { contenTHURL = '<?php $domain?>/eMapLife/PinImageSet/'+queImg;}
				}
					//var contenTH = '<img id="ImagenTH"  src="'+contenTHURL+'" alt="'+queId+'">';
				}
				
				
						$('#Imageviewer').html(conten);
						$.mobile.changePage($('#Reportviewer'), 'slideup');
			
			
			});
			
				
			
			
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
        });
    </script>
       
	</div><!-- /content -->

    <div data-role="footer" data-id="foo1" data-position="fixed">
	<div data-role="navbar">
		<ul>
			<li><a href="mobapp/dashboard_mob.php" data-ajax="false">Home</a></li>
			<li><a href="#">Capture</a></li>
			<li><a href="mobapp/Report_mob.php" data-ajax="false">Reports</a></li>
		</ul> 
	</div><!-- /navbar -->
</div><!-- /footer -->

</div><!-- /page -->

<!-- Page to testing PDF-->
	<div data-role="page" id="Reportviewer" data-theme="d" tabindex="0" class="ui-page ui-body-a" >

	<div data-role="header" data-theme="a">
		<h1><img src="mobapp/Icono_H2M.png" alt="" ></h1>
	</div><!-- /header -->

	<div data-role="content" data-theme="d">	
		
			
			<!--<div><iframe class='map' style='border:1px solid #666CCC; margin:0 auto; display:block;' src='test.pdf' frameborder='1' scrolling='auto'>
			</iframe>
			</div>-->
			
			<div id="Imageviewer"></div>
			<!--<iframe src="https://docs.google.com/viewer?url=http://dev.health2.me/mobapp/test.pdf&embedded=true" style="width:auto; height:400px;" frameborder="0"></iframe>
			<!--<object data='http://dev.health2.me/mobapp/test.pdf#' 
					type='application/pdf' 
					width='100%' 
					height='100%'>

			<p>It appears your Web browser is not configured to display PDF files. 
			No worries, just <a href='http://dev.health2.me/mobapp/test.pdf'>click here to download the PDF file.</a></p>

			</object> -->
			
		
	</div><!-- /content -->
	
	<div data-role="footer" data-id="foo1" data-position="fixed">
		<div data-role="navbar">
			<ul>
				<li><a href="ReportImages_mob.php" data-ajax="false">Back</a></li>				
			</ul> 
		</div><!-- /navbar -->
	<!--</div><!-- /footer -->
	</div>

</body>
</html>