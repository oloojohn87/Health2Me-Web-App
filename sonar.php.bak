<!DOCTYPE html>
 
<html>
        <head>
               <title>H2M Referrals Sonar testbed</title>
                <meta charset="utf-8">
 
                <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
 
                <script type="text/javascript">
               			
               			var unidad = (Math.PI * 0.1);
               			
               			var PatientName = Array();
               			var PatientStage = Array();
               			var PatientDoctor = Array();
               			var PatientTTV = Array();
               			
               			PatientName[0] = 'namerftg surname';
			   			PatientStage[0] = 2;
			   			PatientDoctor[0] = 41;
			   			PatientTTV[0] = 8;
               			
               			PatientName[1] = 'n234ame surnamfge';
			   			PatientStage[1] = 4;
			   			PatientDoctor[1] = 41;
			   			PatientTTV[1] = 70;
               			
               			PatientName[2] = '344name surdfname';
			   			PatientStage[2] = 2;
			   			PatientDoctor[2] = 41;
			   			PatientTTV[2] = 11;
               			
               			PatientName[3] = 'nXXXame surRRRname';
			   			PatientStage[3] = 3;
			   			PatientDoctor[3] = 22;
			   			PatientTTV[3] = 17;
               			
               			PatientName[4] = '2222name sFFurname';
			   			PatientStage[4] = 1;
			   			PatientDoctor[4] = 22;
			   			PatientTTV[4] = 4;
               			
               			PatientName[5] = 'nhhhame iiisurname';
			   			PatientStage[5] = 3;
			   			PatientDoctor[5] = 16;
			   			PatientTTV[5] = 0;
               			
               			PatientName[6] = 'n11ame 11surname';
			   			PatientStage[6] = 4;
			   			PatientDoctor[6] = 16;
			   			PatientTTV[6] = 0;
               			
               			PatientName[7] = '888name 888name';
			   			PatientStage[7] = 3;
			   			PatientDoctor[7] = 16;
			   			PatientTTV[7] = 0;
               			
               			PatientName[8] = '00name 00surname';
			   			PatientStage[8] = 1;
			   			PatientDoctor[8] = 16;
			   			PatientTTV[8] = 2;
               			
               			PatientName[9] = 'wwwme wwwame';
			   			PatientStage[9] = 2;
			   			PatientDoctor[9] = 16;
			   			PatientTTV[9] = 12;

               			PatientName[10] = 'na66je s66jame';
			   			PatientStage[10] = 3;
			   			PatientDoctor[10] = 16;
			   			PatientTTV[10] = 0;

               			PatientName[11] = 'nammmmm mmmmmsjame';
			   			PatientStage[11] = 2;
			   			PatientDoctor[11] = 29;
			   			PatientTTV[11] = 0;

               			PatientName[12] = 'nae55 ee555ame';
			   			PatientStage[12] = 4;
			   			PatientDoctor[12] = 33;
			   			PatientTTV[12] = 0;

               			PatientName[13] = 'na..e55 ee5..55ame';
			   			PatientStage[13] = 2;
			   			PatientDoctor[13] = 33;
			   			PatientTTV[13] = 0;

               			PatientName[14] = 'na--e55 ee5--55ame';
			   			PatientStage[14] = 2;
			   			PatientDoctor[14] = 33;
			   			PatientTTV[14] = 4;

               			PatientName[15] = 'naxxe55 ee555xxxame';
			   			PatientStage[15] = 4;
			   			PatientDoctor[15] = 33;
			   			PatientTTV[15] = 21;

               			PatientName[16] = 'nae55ppp ee555ameppp';
			   			PatientStage[16] = 3;
			   			PatientDoctor[16] = 33;
			   			PatientTTV[16] = 8;

               			PatientName[17] = 'naiiie55 iiee555ame';
			   			PatientStage[17] = 3;
			   			PatientDoctor[17] = 33;
			   			PatientTTV[17] = 3;

						var DrColor = Array();
               			
               			DrColor[0]='rgb(115,187,59)';
               			DrColor[1]='rgb(105,120,250)';
               			DrColor[2]='rgb(255,66,66)'; 
               			DrColor[3]='orange';
               			DrColor[4]='rgb(115,0,59)';
               			DrColor[5]='rgb(115,100,59)';
               			DrColor[6]='rgb(115,150,59)';
               			DrColor[7]='rgb(115,250,59)';
               			DrColor[8]='rgb(115,187,59)';
               			DrColor[9]='rgb(115,187,159)';
               			
                        $(document).ready(function() {
							 	
							 	
							 	var reco = PatientName.length;
							 	var n = 0;
							 	var m = 0;
							 	var actualDr = -1;
							 	var initDr = 0;
							 	var endDr = 9999;
							 	var T2V = 0;
							 	var T2Vparc = 0;
							 	while (n < reco)
							 	{
							 		Slice(n,PatientStage[n],200,175);
							 		T2Vparc = T2Vparc + PatientTTV[n];	
							 		if (PatientDoctor[n] != actualDr && n>0 )
							 		{
							 			endDr = n-1;
							 			SliceDr (initDr,endDr,4,DrColor[m], PatientName[n-1]);
							 			m++;
							 			if (m > 9) m = 0 ;
							 			initDr = n;
								 		actualDr = PatientDoctor[n];
							 		}	
							 		
							 		n++;
							 	}
							 	endDr = n-1;
							 	SliceDr (initDr,endDr,4,'rgb(115,187,59)',PatientName[n-1]);
							 	T2V = T2Vparc / (n-1);
							 	T2V = T2V.toFixed(1);
							 	$("#NReferrals").html(m+1);
							 	$("#NPatients").html(reco);
							 	$("#NTime2Visit").html(T2V+' days');
							 			
							 	/*
								Slice(0,2);	
								Slice(1,3);	
								Slice(2,2);	
								Slice(3,2);	
								Slice(4,3);	
								Slice(5,3);	
								SliceDr(0,2,3,'rgb(115,187,59)');
								SliceDr(3,5,4,'rgb(115,187,159)');
								Slice(6,2);	
								Slice(7,3);	
								Slice(8,1);	
								Slice(9,3);	
								Slice(10,4);	
								Slice(11,3);	
								SliceDr(6,11,3,'rgb(255,66,66)');
								*/
								
								$("#MyRadar").click(function(event) {
									//alert ('ok');
									var canvas = $("#myCanvas");
									var context = canvas.get(0).getContext("2d");
									context.save();
									context.rotate(0.37);
									context.restore();
									});
								
                        });


						function Slice(order,stage,centerX,centerY){
								var stagenum = 15;
								//var centerX = 200;
								//var centerY = 175;
								switch (stage)
								{
									case 1: 	stagenum = 25;
												break;
									case 2: 	stagenum = 50;
												break;
									case 3: 	stagenum = 75;
												break;
									case 4: 	stagenum = 100;
												break;
								}
								var canvas = $("#myCanvas");
								var context = canvas.get(0).getContext("2d");
								context.lineWidth = 1;
										
								context.beginPath(); // Start the path
								context.moveTo(200, 175); // Set the path origin
								//context.lineTo(440, 40); // Set the path destination
								var inicio = -(Math.PI*.5)+(order*unidad);
								var sumas = unidad /2;							
								context.arc(centerX, centerY, stagenum, inicio,(inicio+ unidad), false); // Draw a circle
								context.closePath(); // Close the path
								context.fillStyle = "#3d93e0";
								context.globalAlpha=0.99; // Half opacity
								context.fill(); // Fill the path
							 	context.strokeStyle = '#3d93e0';
      							context.strokeStyle = 'blue';
      							context.stroke();
						};

						function SliceDr(startPatient, endPatient, stage, Acolor, DrName){
								var stagenum = 15;
								switch (stage)
								{
									case 1: 	stagenum = 25;
												break;
									case 2: 	stagenum = 50;
												break;
									case 3: 	stagenum = 75;
												break;
									case 4: 	stagenum = 100;
												break;
								}
								var canvas = $("#myCanvas");
								var context = canvas.get(0).getContext("2d");
								context.lineWidth = 1;
								
								context.beginPath(); // Start the path
								context.moveTo(200, 175); // Set the path origin

								var inicio = -(Math.PI*.5) + (startPatient*unidad);
								var final =  -(Math.PI*.5) + ((endPatient+1)*unidad);							
								context.arc(200, 175, stagenum, inicio,final, false); // Draw a circle
								context.closePath(); // Close the path
								//context.fillStyle = "#3d93e0";
								context.fillStyle = Acolor;
								context.globalAlpha=0.4; // Half opacity
								context.fill(); // Fill the path
							 	//context.strokeStyle = '#3d93e0';
      							context.strokeStyle = Acolor;
      							context.stroke();
      							
      							var arcpad = 0.09;
								inicio = inicio + arcpad;
     							final = final - arcpad;
     							
     							context.globalAlpha=0.8; // 0.8 opacity
								
								context.beginPath(); // Start the path
      							//context.moveTo(200, 200); // Set the path origin
								context.arc(200, 175, 120, inicio, final, false); // Draw a circle
								context.lineWidth = 15;
								//context.closePath(); // Close the path
								context.strokeStyle = Acolor;
      							context.stroke();

	  							// Position the label for the Doctor
	  							DrName = DrName.substr(0,8);
	  							TextFontsize = 12;
	  							var midArc = inicio + ((final-inicio)/2) ;
	  							//var newX = 200 + (120 * Math.sin(midArc)) ;
	  							//var newY = 175 + (120 * Math.cos(midArc)) ;
	  							var newX = 200 + (140 * Math.cos(midArc)) ;
	  							var newY = 175 + (140 * Math.sin(midArc)) ;
	  							var textmetrics = context.measureText(DrName);
	  							var textwidth = textmetrics.width;
	  							var textheight = TextFontsize * 1.5;
	  							if (newX < 200) 
	  							{
	  								newX = newX - textwidth;
								}
								if (newY < 175)
	  							{
		  							var basePos = 'bottom';
		  							var iniBox = 15 ;
	  							} 
	  							else
	  							{
		  							var basePos = 'top';
		  							var iniBox = 0;
	  							}
	  							
	  							// Draw Box around text
								context.beginPath();
//								context.rect(newX, newY-iniBox, textwidth, textheight);
								context.rect(newX-5, newY-iniBox, textwidth+10, 16);
								context.fillStyle = Acolor;
								context.fill();
								context.lineWidth = 1;
								context.strokeStyle = Acolor;
								context.stroke();


	  							context.fillStyle = 'white';
								context.font = 'bold 12px Helvetica';
								context.textBaseline = basePos;
								//context.fillText(DrName, 50, 100);
								context.fillText(DrName, newX, newY);
								
															

						};


						
                </script>
        </head>
 
        <body>


			<div style="width:100%; text-align:center; float:left;">
            	<div style="width:1030px; text-align:center; margin:0 auto; border: 1px solid #cacaca; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px;">
            	<div style="width:100%; margin-top:15px;">
                <span id="MyRadar" style="left: 0px; margin-left: 20px; margin-top: 20px; margin-bottom: 5px; font-size: 16px; background-color: #22aeff; padding: 1px 4px 2px; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; font-size: 22px; font-weight: bold; line-height: 22px; color: #ffffff; white-space: nowrap; vertical-align: baseline; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;"> Referral's Radar&copy</span>
            	</div>
	                <div style="width:300px; height:350px; float:left; padding-top:50px; /*border:solid;*/" id="BoxLeft">
		                <?php 
		                	$indice=12;
		                	$indiceM = 25;
		                	$PacketsVistos = 3; 
		                	$C5='rgba(105,120,250,';
							$C0='rgba(115,187,59,';
		  				//rgba(115,187,59,0.99)
 		  				?>
		               	<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<div style="width:100px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:30px;" title="This figure indicates the number of individual patients that have accessed information created by this user">
		  	    		
		  	    		<div style="height:80px; width:100px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
			  	    		<p id="NReferrals" style=" font-size:<?php echo queFuente($indice);?>px; font-weight:bold; color: <?php echo $C5.'0.99)' ?>; margin-top:0px; font-family:Arial;"><?php echo $indice ?></p>
		  	    		</div>
		  	    		
		  	    		<div style="width:100px;  text-align:center; margin:0px; background-color: <?php echo $C5.'0.99)' ?>; border:1px solid <?php echo $C5.'0.99)' ?>; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; ">Referrals</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<div style="width:100px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; margin-left:10px; " title="This figure indicates the number of individual doctors that have accessed information created by this user">
		  	    		
		  	    		<div style="height:80px; width:100px;  text-align:center; margin:0px;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
			  	    		<p id="NPatients" style=" font-size:<?php echo queFuente($indiceM);?>px; font-weight:bold; color: <?php echo $C0.'0.99)' ?>;  margin-top:0px; font-family:Arial;"><?php echo $indiceM ?></p>
		  	    		</div>
		  	    		
		  	    		<div style="width:100px;  text-align:center; margin:0px; background-color: <?php echo $C0.'0.99)' ?>; border:1px solid <?php echo $C0.'0.99)' ?>; margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; ">Patients</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		  	    		<div style="margin-top:15px; margin-left:45px; width:180px; border:1px solid rgba(194,194,194,1); float:left;  -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; " title="This figure indicates the number of individual pieces of information (packets) created by this user that have benn accessed in total">
		  	    		
		  	    		<div style="height:80px; width:180px;  text-align:center; margin:0px; display: table;">  <!-- Font-size: 70 para 2 digitos, 50 para 3 digitos, 30 para 4 digitos -->
			  	    		<p id="NTime2Visit" style=" font-size:<?php echo queFuente($PacketsVistos);?>px; font-weight:bold; color: rgba(255,66,66,0.99);  margin-top:0px; font-family:Arial; font-size:<?php echo queFuente($PacketsVistos)/2;?>px; display: table-cell; vertical-align: middle;"><?php echo $PacketsVistos.' days'; ?></p>
		  	    		</div>
		  	    		
		  	    		<div style="width:180px;  text-align:center; margin:0px; background-color: rgba(255,66,66,0.99); border:1px solid rgba(255,66,66,0.99); margin-left:-1px;  -webkit-border-bottom-right-radius: 5px; -moz-border-bottom-right-radius: 5px; border-bottom-right-radius: 5px; -webkit-border-bottom-left-radius: 5px; -moz-border-bottom-left-radius: 5px; border-bottom-left-radius: 5px;">
		  	    		<p style="font-family: arial; font-size:18px; color:white; padding:5px; margin:0px; ">Time to Visit</p>
		  	    		</div>	
		  	    		
		  	    		</div>
		  	    		<!---- CAJA DE PRESENTACIÓN DE NÚMEROS ---->
		                
		                
	                </div>
					<canvas id="myCanvas" width="400" height="350" style="width:400px; height:350px; margin:0 auto; /*border:solid*/; ">
	                        <!-- Insert fallback content here -->
	                </canvas>
	                <div style="width:300px; height:350px; float:right; text-align:left; padding-top:50px; /*border:solid;*/" id="BoxRight">
		             	<span id="DrName" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:20px; font-weight:bold; color:#3d93e0;">Dr. Nameajdh Surnamelkd</span>   
		             	<span id="DrEmail" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:14px;  color:#3d93e0;">namea.surnamelkd@mail.com</span>   
		             	<div style="width:100%; margin-bottom:15px;"></div>
		             	<span id="DrPatients" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:18px;  color:rgb(115,187,59);">3</span>   
		             	<span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:14px;  color:grey;"> patients referred</span>   
		             	<div style="width:100%;"></div>
		             	<span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:14px;  color:grey;"> Time to Visit: </span>   
		             	<span id="DrTtoV" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:18px;  color:rgb(115,187,59);">5 days</span>   

					 	<div style="width:100%; margin-bottom:45px;"></div>
		             	
		             	<span id="DrName" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:20px; font-weight:bold; color:rgb(115,187,59);">Patname Patsurname</span>   
		             	<span id="DrEmail" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:14px;  color:rgb(115,187,59);">npatnaa.supatied@mail.com</span>   
		             	<div style="width:100%; margin-bottom:15px;"></div>
		             	<span style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:14px;  color:grey;"> Time to Visit: </span>   
		             	<span id="DrTtoV" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:18px;  color:#3d93e0;">5 days</span>   
		             	
		                
	                </div>
            	</div>
			</div>    
        </body>
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

function queFuente3 ($numero)
{
$queF=10;
switch ($numero)
{
	case ($numero>999 && $numero<9999):	$queF = 40;
										break;
	case ($numero>99 && $numero<1000):	$queF = 60;
										break;
	case ($numero>0 && $numero<100):	$queF = 80;
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

</html>
      </body>
</html>
