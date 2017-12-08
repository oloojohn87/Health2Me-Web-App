	var med_id = $('#MEDID').val();
	var paciente='';
    var destino='';
	var Nondestino='';
    var IdPaciente = -1;
    var IdDoctor = -1;
    
	var timeoutTime = 18000000;
	//var timeoutTime = 300000;  //5minutes
	var timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);


	var active_session_timer = 60000; //1minute
	var sessionTimer = setTimeout(inform_about_session, active_session_timer);

	
$(window).load(function() {
	//alert("started");
    
    function getScript(url)
    {
        $.ajax(
        {
            url: url,
            dataType: "script",
            async: false
        });
    }
    
    function displaynotification(status,message){
        getScript('realtime-notifications/lib/gritter/js/jquery.gritter.min.js');
        var gritterOptions = {
                                title: status,
                                text: message,
                                image:'images/Icono_H2M.png',
                                sticky: false,
                                time: '3000'
                             };
        $.gritter.add(gritterOptions);

    }
    
    //var pusher = new Pusher('d869a07d8f17a76448ed');
    //var channel_name=$('#MEDID').val();
    //var channel = pusher.subscribe(channel_name);
    
    var push = new Push($("#MEDID").val(), window.location.hostname + ':3955');
    
    push.bind('notification', function(data) 
    {
        displaynotification('New Message', data);
    });
    
    
	$(".loader_spinner").fadeOut("slow");
	})

	//This function is called at regular intervals and it updates ongoing_sessions lastseen time
	function inform_about_session()
	{
		$.ajax({
			url: 'ongoing_sessions.php?userid='+med_id,
			success: function(data){
			//alert('done');
			}	
		});
		clearTimeout(sessionTimer);
		sessionTimer = setTimeout(inform_about_session, active_session_timer);
	}

	function ShowTimeOutWarning()
	{
		alert ('Session expired');
		var a=0;
		window.location = 'timeout.php';
	}

						// RADAR: INITIAL LOADING OF ARRAY AND LAYER SETUP
                        var stageScenery = new Kinetic.Stage({
                                container: 'CanvasContainer',
                                width: 450,
                                height: 350
                        });
        
                        var layer = new Kinetic.Layer();
                        var layerDr = new Kinetic.Layer();
                        var layerEvents = new Kinetic.Layer();
                        var layerArcs = new Kinetic.Layer();
               			var unidad = (Math.PI * 0.1);
               			
               			var PatientName = Array();
               			var PatientStage = Array();
               			var PatientDoctor = Array();
               			var PatientDoctorFULL = Array();
               			var DoctorId = Array();
               			var DoctorEmail = Array();
               			var PatientTTV = Array();
               			var DoctorsNames = Array();
               			var DoctorsNamesFULL = Array();
               			var DoctorsEmails = Array();
        
                        var IsOn = Array();
                        var IsOnCounter = 0;
                        var IsOnCounter2 = 0;
						var group=0;
						
						
						
	 function getInitialRadarData(){
		group=0;
		if($('#Group_toggle').is(":checked")) group=1;
        
        //getReferredPatients('getReferredPatients.php?group='+group);
		getReferredPatients('getReferredPatients.php?group=0');
        $thisreco =0;
        while ($thisreco <  ReferredPatients.length)
            {
                PatientName[$thisreco] = ReferredPatients[$thisreco].PatientName;
                PatientStage[$thisreco] = ReferredPatients[$thisreco].PatientStage;
                PatientDoctor[$thisreco] = ReferredPatients[$thisreco].PatientDoctor;
                PatientDoctorFULL[$thisreco] = ReferredPatients[$thisreco].PatientDoctorFULL;
                DoctorId[$thisreco] = ReferredPatients[$thisreco].IdMED2;
                DoctorEmail[$thisreco] = ReferredPatients[$thisreco].EmailDoctor;
                if ( PatientDoctor[$thisreco] < "   ")  PatientDoctor[$thisreco]=DoctorEmail[$thisreco];
                    PatientTTV[$thisreco] = ReferredPatients[$thisreco].TTV;
                    DoctorsNames[DoctorId[$thisreco]] = PatientDoctor[$thisreco];
        		    DoctorsNamesFULL[DoctorId[$thisreco]] = PatientDoctorFULL[$thisreco];
        		    DoctorsEmails[DoctorId[$thisreco]] = DoctorEmail[$thisreco];
        		    $thisreco ++;
            }
		}
		
						getInitialRadarData();
						
						var DrColor = Array();
               			
               			// Create array for Referrals (doctors)
               			var ReferralId = Array();
               			var ReferralName = Array();
               			var ReferralNameFULL = Array();
               			var ReferralStaP = Array();
               			var ReferralEndP = Array();
               			var ReferralNumPats = Array();
               			var ReferralTTV = Array();
               			var brokenReferral = Array();
               			$thisreco =0;
                        var ReferralNumber = 0;
                        var LatestPatient = 0;
                        var LatestDoctor = 0;
                        var SumTimes = 0;
                        var MeanTTV = 0;
                        while ($thisreco <=  ReferredPatients.length)
                        {
                        	
	                        if ($thisreco > 0 && (PatientDoctor[$thisreco] != LatestDoctor))
	                        {
		                        ReferralStaP[ReferralNumber] = LatestPatient;
		                        ReferralEndP[ReferralNumber] = $thisreco-1;

		                        ReferralId[ReferralNumber] = DoctorId[$thisreco-1];
		                        ReferralName[ReferralNumber] = DoctorsNames[DoctorId[$thisreco-1]];
								ReferralNameFULL[ReferralNumber] = DoctorsNamesFULL[DoctorId[$thisreco-1]];
								
								ReferralNumPats[ReferralNumber] = ReferralEndP[ReferralNumber] - ReferralStaP[ReferralNumber] + 1;
								ReferralTTV[ReferralNumber] = SumTimes;
								
								MeanTTV = MeanTTV + Math.round(ReferralTTV[ReferralNumber]/ReferralNumPats[ReferralNumber]);
								SumTimes = 0;		
								
								LatestPatient = $thisreco;
								LatestDoctor = PatientDoctor[$thisreco];
		                        ReferralNumber++;
	                        }
	                        
	                        SumTimes = SumTimes + parseInt(PatientTTV[$thisreco]);
               			    $thisreco ++;
                        }
                        
               			
               			DrColor[0]='rgb(255,66,66)';
               			DrColor[1]='rgb(105,120,250)';
               			DrColor[2]='orange';
               			DrColor[3]='rgb(255,66,166)'; 
               			DrColor[4]='rgb(122,199,59)';
               			DrColor[5]='rgb(115,100,59)';
               			DrColor[6]='rgb(115,150,59)';
               			DrColor[7]='rgb(115,250,59)';
               			DrColor[8]='rgb(115,187,59)';
               			DrColor[9]='rgb(115,187,159)';
						// RADAR: INITIAL LOADING OF ARRAY AND LAYER SETUP
    
						
	                    function getReferredPatients(serviceURL) 
	                    {

							$.ajax(
							{
								url: serviceURL,
								dataType: "json",
								async: false,
								success: function(data)
								{
									//alert('Data Fetched');
									ReferredPatients='';
									ReferredPatients = data.items;
								}
							});
						}
						
   
    var NPatients;
    var NReferrals;
    var MaxPatDisplay;  
	
	$(document).ready(loadRadarData);
	
	function loadRadarData() 
    {
	    // Draw Radar for the first time
	    NPatients = $thisreco-1;
		NReferrals = ReferralNumber;
		MaxPatDisplay = 20;
		if (NPatients>MaxPatDisplay)
		{
			Pages = Math.ceil(NPatients/MaxPatDisplay);
			PatientsPage = Math.ceil(NPatients/Pages);
		}
        else
		{
			Pages = 1;
			PatientsPage = NPatients;
			$("#MorePage").css('visibility','hidden');
			$("#LessPage").css('visibility','hidden');
			$("#pageNumber").css('visibility','hidden');
		}
	    //alert (' Referrals: '+NReferrals+' Patients: '+NPatients+' Pages: '+Pages+' at '+PatientsPage+' per page' );  
		ActualPage = 1;
		RadarPage();
	    // Draw Radar for the first time
    }
	    $("#MorePage").click(function(event) {
			if (ActualPage < Pages) 
	    	{
				ActualPage++; 
				RadarPage();
	    	}
		});
	    
	    $("#LessPage").click(function(event) {
			if (ActualPage > 1) 
	    	{
				ActualPage--; 
				RadarPage();
	    	}
		});
    
		function RadarPage()
		{
            layer.removeChildren();
            layerDr.removeChildren();
            layerEvents.removeChildren();
            layerArcs.removeChildren();
            layer.draw();
            layerDr.draw();
            layerEvents.draw();
            layerArcs.draw();
            PatIni = (ActualPage-1) * PatientsPage;
            PatEnd = (ActualPage) * PatientsPage;
            DrawRadarPatients (PatIni,PatEnd);
            $("#pageNumber").html('Page '+ActualPage+'/'+Pages);
		}

    	function DrawStats(init,end)
        {
			
		 	$("#NReferrals").html(ReferralNumber);
		 	$("#NPatients").html($thisreco-1);
		 	$("#NTime2Visit").html(Math.round(MeanTTV/ReferralNumber)+' days');
	    }			
    
        function DrawRadarPatients (startingPatient, endingPatient)
	    {
			var centerX = 200;
			var centerY = 175;
			var stagenum = 0;
			// Draw patients section 
			
			// This puts a limit if the endingpatient value is above maximum number of patients
			var endSequence = parseInt(endingPatient);
			if (endSequence >= PatientName.length) endSequence = parseInt(PatientName.length)-1;
	
	    	var NumberOfItems = endSequence - startingPatient +1;
			if (NumberOfItems !=  0) unidad = (Math.PI * 2 / NumberOfItems); else  unidad = (Math.PI);
			var canvas = $("#myCanvas");
			var context = canvas.get(0).getContext("2d");
			context.lineWidth = 1;
			context.beginPath(); // Start the path
			context.moveTo(200, 175); // Set the path origin
	
			var n = startingPatient;
			/*
			console.log(' ************************************ ');
			console.log('From: '+n+'  To: '+endingPatient);
			console.log('Total patients: '+PatientName.length);
			*/
			
			while (parseInt(n) <= endSequence)
			{
				drawingOrder = n - startingPatient;
				stage = PatientStage[n] ;
				switch (stage)
				{
					case '': 	stagenum = 25;
								break;
					case '1': 	stagenum = 25;
								break;
					case '2': 	stagenum = 50;
								break;
					case '3': 	stagenum = 75;
								break;
					case '4': 	stagenum = 100;
								break;
                }
				var angleShift = -(Math.PI*.5)+(drawingOrder*unidad);
							
				var wedgeP = new Kinetic.Wedge({
			        x: centerX,
			        y: centerY,
			        radius: stagenum,
			        angle: unidad,
			          fillRadialGradientStartPoint: 0,
			          fillRadialGradientStartRadius: 0,
			          fillRadialGradientEndPoint: 0,
			          fillRadialGradientEndRadius: stagenum,
			          fillRadialGradientColorStops: [0, '#cacaca', 1, 'grey'],
			        stroke: '#808080',
			        strokeWidth: 1,
			        rotation: angleShift,
			        opacity:0.9
			     });
			    // add the shape to the layer
			    layer.add(wedgeP);
				//			          fillRadialGradientColorStops: [0, 'red', 0.5, 'yellow', 1, 'blue'],

			
				wedgeP.on('touchstart click mouseover', function(evt) {
					var shape = evt.targetNode;
					this.setOpacity(0.8);
					this.setStroke('black');
					this.setStrokeWidth(2);
					layer.draw();	
					m = this.index;
					RealIndex = parseInt(m) + parseInt(startingPatient);
			        $('#DrName').html(RealIndex + ' ' + PatientName[RealIndex]);
			        $('#DrEmail').html(DoctorsNames[DoctorId[RealIndex]]);
					this.setOpacity(0.4);
					this.setStroke(DrColor[RealIndex]);
					this.setStrokeWidth(1);						
					//arc.setStrokeWidth(10);
				});
				
				//console.log(NumberOfItems+' Items  '+n+'   Name:'+PatientName[n]+'   stagenum:'+stagenum+'   angleshift:'+angleShift);
			
				n++;	
			}
			stageScenery.add(layer);
			
			// Draw doctors section (referrals)
			n = 0;
			maxReferrals = ReferralId.length;
			//console.log('***************************************');
			var countseries = 0;
					
			while (n <= maxReferrals)
			{
				decena = parseInt(n/10);
				secColor = n - (decena*10);
				
				mustDrawReferral = 0;
				if ((ReferralEndP[n] >= startingPatient) && (ReferralStaP[n] <= endSequence)) mustDrawReferral = 1; else mustDrawReferral = 0; 
				//console.log ('REFERRAL: '+n+'        ReferralEndPatient: '+ReferralEndP[n]+'     startingPatient: '+startingPatient);
				if (mustDrawReferral == 1)
				{
					countseries++;
					startDrawPat = ReferralStaP[n]- startingPatient;
					if (countseries == 1) {startDrawPat = 0; var FirstReferral = n;}
	
					if (ReferralEndP[n] <= endSequence) 
					{
						endDrawPat = ReferralEndP[n] - startingPatient; 
						brokenReferral[n] = 0;
					}
					else 
					{
						endDrawPat = endSequence - startingPatient;
						brokenReferral[n] = 1;
					}
					var inicio = -(Math.PI*.5) + (startDrawPat*unidad);
					var final =  -(Math.PI*.5) + (endDrawPat*unidad);							
					// 'wedge' is the pie slice that corresponds to a referral (doctor)	
					referralWidth = unidad * (endDrawPat-startDrawPat+1); 
					var wedge = new Kinetic.Wedge({
				        x: 200,
				        y: 175,
				        radius: 100,
				        angle: referralWidth,
				        fill: DrColor[secColor],
				        stroke: DrColor[secColor],
				        strokeWidth: 1,
				        rotation: inicio,
				        name: 'cosa',
				        opacity:0.40
				      });
				    
				    layerEvents.add(wedge);
					
					// 'arc' is the external arc outside the pie, representing a referral (doctor)
					
					DrawArcRef (inicio+0.1,inicio+referralWidth-0.1,DrColor[secColor]);
	
		  			// Position the label for the Doctor
					TextFontsize = 12;
					context.font = 'bold 12px Helvetica';
	                DrName = ReferralName[n].substr(0,12);
	                DrNameFULL = ReferralNameFULL[n].substr(0,12);
	                //DrName = n + ' ' + DrName;
	                if (ReferralName[n].length>11) DrName = DrName+'..';
	                var midArc = inicio + ((final-inicio)/2) ;
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
					context.rect(newX-5, newY-iniBox, textwidth+10, 16);
					var rect = new Kinetic.Rect({
				        x: newX-5,
				        y: newY-iniBox,
				        width: textwidth+10,
				        height: 16,
				        fill: DrColor[secColor],
				        stroke: 'grey',
				        strokeWidth: 1,
				        opacity:0.8
				      });
				    
				    // add the shape to the layer
					layerDr.add(rect);
					context.fillStyle = DrColor[secColor];
					context.fill();
					context.lineWidth = 1;
					context.strokeStyle = DrColor[secColor];
					context.stroke();
	
	
					context.fillStyle = 'white';
					context.font = 'bold 12px Helvetica';
					context.textBaseline = basePos;
					//context.fillText(DrName, 50, 100);
					context.fillText(DrName, newX, newY);
					var simpleText = new Kinetic.Text({
				        x: newX,
				        y: newY-iniBox+2,
				        text: DrName,
				        fontSize: 12,
				        fontFamily: 'Helvetica',
				        fill: 'white'
				      });
				    // add the shape to the layer
					layerDr.add(simpleText);
					
					// Add interaction
					wedge.on('touchstart click mouseover', function(evt) {
						document.body.style.cursor = 'pointer';		
						var shape = evt.targetNode;
						this.setOpacity(0.6);
						this.setStroke('black');
						this.setStrokeWidth(2);
						layerEvents.draw();
		
						m = this.index;
						RealM = m + FirstReferral;
						if (brokenReferral[m] == 0)  
                            $('#DrName').html(ReferralNameFULL[RealM]); 
                        else 
                            $('#DrName').html(ReferralNameFULL[RealM]+'../.');
						
						var translation = '';

						if(language == 'th'){
						translation = 'Miembros';
						}else if(language == 'en'){
						translation = 'Members';
						}
						
				        $('#DrEmail').html(DoctorsEmails[m]);
				        $('#DrPatients').html(ReferralNumPats[RealM]);
				        $('#AdditHtml').html(' '+translation);
				        //$('#DrTtoV').html(MeaMean nTTV.toFixed(1)+' days');
						$('#DrTtoVText').html('Time to Visit: '+Math.round(ReferralTTV[RealM]/ReferralNumPats[RealM])+' days');
		                //this.setFill(Acolor);
						this.setOpacity(0.4);
						this.setStroke(DrColor[m]);
						this.setStrokeWidth(1);		
						//arc.setStrokeWidth(10);
					});
					
					wedge.on('mouseout', function() {		//Added by Ankit
						document.body.style.cursor = 'default';
					});
		
					wedge.on('click', function() {
						var m = this.index;
						//var RealM = m + FirstReferral;
						var RealM = parseInt(m) + parseInt(FirstReferral);
						var DrId = ReferralId[RealM];
						
						//console.log(' IdOrden: '+RealM+'   IdDoctor'+DrId+'  Name: '+ReferralName[RealM]);
						
						var IdMed = $('#MEDID').val();
						var UserDOB='';
						var queUrl ='getSents.php?Doctor='+IdMed+'&DrEmail='+UserDOB+'&NReports=3&ToDoc='+DrId;
						$('#TablaSents').load(queUrl);
						$('#TablaSents').trigger('update');
						var txt='<b> Dr. '+DoctorsNames[DrId] + '</b>';
						$('#radartext').html(txt);
						$('#BotonBusquedaReset').show();
					});
	
	
		
				}
				
				/*
				console.log('- REFERRAL drawing: -' ); 
				console.log(n); 
				console.log(ReferralName[n]); 
				console.log('Starts in: '+ReferralStaP[n]+' Ends In:'+ReferralEndP[n]); 
				console.log(mustDrawReferral); 
				console.log('' ); 
				*/
				n++;
			}
			
	
			stageScenery.add(layer);
			stageScenery.add(layerDr);
			stageScenery.add(layerEvents);
			stageScenery.add(layerArcs);
	
			
	 }
    
	function DrawArcRef (ArcStart,ArcEnd,ArcColor)
    {
			var arc = new Kinetic.Shape({
		    drawFunc: function(canvas) {
		        var context = canvas.getContext();
		        var context = canvas.getContext('2d');
		        context.globalAlpha=0.7; // 0.8 opacity
				context.beginPath();
		        context.arc(200, 175, 120, ArcStart, ArcEnd, false);
		        canvas.stroke(this);
		    },
		    fill: ArcColor,
		    stroke: ArcColor,
		    opacity:0.7,
		    strokeWidth: 10,
			});
		    layerArcs.add(arc);	    
    }


    function drawReferredOutRadar()
    {
        $thisreco = 0;
        PatientName = Array();
        PatientStage = Array();
        PatientDoctor = Array();
        PatientDoctorFULL = Array();
        DoctorId = Array();
        DoctorEmail = Array();
        PatientTTV = Array();
        DoctorsNames = Array();
        DoctorsNamesFULL = Array();
        DoctorsEmails = Array();
        var group=0;
						
		if($('#Group_toggle').is(":checked")) group=1;
        
        getReferredPatients('getReferredPatients.php?group='+group);
       // getReferredPatients('getReferredPatients.php?group=1');
        
			
		    
		//alert('count:'+ ReferredPatients.length);
		//alert('patientnamearray:'+PatientName.length);
		
        while ($thisreco <  ReferredPatients.length)
        {
            PatientName[$thisreco] = ReferredPatients[$thisreco].PatientName;
            PatientStage[$thisreco] = ReferredPatients[$thisreco].PatientStage;
            PatientDoctor[$thisreco] = ReferredPatients[$thisreco].PatientDoctor;
            PatientDoctorFULL[$thisreco] = ReferredPatients[$thisreco].PatientDoctorFULL;
            DoctorId[$thisreco] = ReferredPatients[$thisreco].IdMED2;
            DoctorEmail[$thisreco] = ReferredPatients[$thisreco].EmailDoctor;
            if ( PatientDoctor[$thisreco] < "   ")  PatientDoctor[$thisreco]=DoctorEmail[$thisreco];
            PatientTTV[$thisreco] = ReferredPatients[$thisreco].TTV;
            DoctorsNames[DoctorId[$thisreco]] = PatientDoctor[$thisreco];
            DoctorsNamesFULL[DoctorId[$thisreco]] = PatientDoctorFULL[$thisreco];
            DoctorsEmails[DoctorId[$thisreco]] = DoctorEmail[$thisreco];
            $thisreco ++;
        }
        
        $thisreco = 0;
        ReferralId = Array();
        ReferralName = Array();
        ReferralNameFULL = Array();
        ReferralStaP = Array();
        ReferralEndP = Array();
        ReferralNumPats = Array();
        ReferralTTV = Array();
        brokenReferral = Array();
        ReferralNumber = 0;
        LatestPatient = 0;
        LatestDoctor = 0;
        SumTimes = 0;
        MeanTTV = 0;
	
        
        while ($thisreco <=  ReferredPatients.length)
        {
            if ($thisreco > 0 && (PatientDoctor[$thisreco] != LatestDoctor))
            {
                ReferralStaP[ReferralNumber] = LatestPatient;
                ReferralEndP[ReferralNumber] = $thisreco-1;

                ReferralId[ReferralNumber] = DoctorId[$thisreco-1];
                ReferralName[ReferralNumber] = DoctorsNames[DoctorId[$thisreco-1]];
                ReferralNameFULL[ReferralNumber] = DoctorsNamesFULL[DoctorId[$thisreco-1]];
                
                ReferralNumPats[ReferralNumber] = ReferralEndP[ReferralNumber] - ReferralStaP[ReferralNumber] + 1;
                ReferralTTV[ReferralNumber] = SumTimes;
                
                MeanTTV = MeanTTV + Math.round(ReferralTTV[ReferralNumber]/ReferralNumPats[ReferralNumber]);
                 
                SumTimes = 0;		

                LatestPatient = $thisreco;
                LatestDoctor = PatientDoctor[$thisreco];
                ReferralNumber++;
            }
            
            SumTimes = SumTimes + parseInt(PatientTTV[$thisreco]);
            $thisreco ++;
        }
        
        NPatients = $thisreco-1;
		NReferrals = ReferralNumber;
		MaxPatDisplay = 20;
		if (NPatients>MaxPatDisplay)
		{
			Pages = Math.ceil(NPatients/MaxPatDisplay);
			PatientsPage = Math.ceil(NPatients/Pages);
		}else
		{
			Pages = 1;
			PatientsPage = NPatients;
			$("#MorePage").css('visibility','hidden');
			$("#LessPage").css('visibility','hidden');
			$("#pageNumber").css('visibility','hidden');
		} 
		ActualPage = 1;
		//loadRadarData();
		RadarPage();
		$('#BotonBusquedaSents').trigger('click');
    }
        
    function drawReferredInRadar()
    {
        PatientName = Array();
        PatientStage = Array();
        PatientDoctor = Array();
        PatientDoctorFULL = Array();
        DoctorId = Array();
        DoctorEmail = Array();
        PatientTTV = Array();
        DoctorsNames = Array();
        DoctorsNamesFULL = Array();
        DoctorsEmails = Array();
		var group=0;
						
		if($('#Group_toggle').is(":checked")) group=1;
        
        
        getReferredPatients('getReferredInPatients.php?group='+group);
		//getReferredPatients('getReferredInPatients.php?group=1');
        
        $thisreco = 0;
    
        while ($thisreco <  ReferredPatients.length)
        {
            PatientName[$thisreco] = ReferredPatients[$thisreco].PatientName;
            PatientStage[$thisreco] = ReferredPatients[$thisreco].PatientStage;
            PatientDoctor[$thisreco] = ReferredPatients[$thisreco].PatientDoctor;
            PatientDoctorFULL[$thisreco] = ReferredPatients[$thisreco].PatientDoctorFULL;
            DoctorId[$thisreco] = ReferredPatients[$thisreco].IdMED2;
            DoctorEmail[$thisreco] = ReferredPatients[$thisreco].EmailDoctor;
            if ( PatientDoctor[$thisreco] < "   ")  PatientDoctor[$thisreco]=DoctorEmail[$thisreco];
            PatientTTV[$thisreco] = ReferredPatients[$thisreco].TTV;
            DoctorsNames[DoctorId[$thisreco]] = PatientDoctor[$thisreco];
            DoctorsNamesFULL[DoctorId[$thisreco]] = PatientDoctorFULL[$thisreco];
            DoctorsEmails[DoctorId[$thisreco]] = DoctorEmail[$thisreco];
            $thisreco ++;
        }
        
        var ReferralId = Array();
        var ReferralName = Array();
        var ReferralNameFULL = Array();
        var ReferralStaP = Array();
        var ReferralEndP = Array();
        var ReferralNumPats = Array();
        var ReferralTTV = Array();
        var brokenReferral = Array();
        $thisreco = 0;
        var ReferralNumber = 0;
        var LatestPatient = 0;
        var LatestDoctor = 0;
        var SumTimes = 0;
        var MeanTTV = 0;
        
        while ($thisreco <=  ReferredPatients.length)
        {
            if ($thisreco > 0 && (PatientDoctor[$thisreco] != LatestDoctor))
            {
                ReferralStaP[ReferralNumber] = LatestPatient;
                ReferralEndP[ReferralNumber] = $thisreco-1;

                ReferralId[ReferralNumber] = DoctorId[$thisreco-1];
                ReferralName[ReferralNumber] = DoctorsNames[DoctorId[$thisreco-1]];
                ReferralNameFULL[ReferralNumber] = DoctorsNamesFULL[DoctorId[$thisreco-1]];
                
                ReferralNumPats[ReferralNumber] = ReferralEndP[ReferralNumber] - ReferralStaP[ReferralNumber] + 1;
                ReferralTTV[ReferralNumber] = SumTimes;
                
                MeanTTV = MeanTTV + Math.round(ReferralTTV[ReferralNumber]/ReferralNumPats[ReferralNumber]);
                SumTimes = 0;		
                LatestPatient = $thisreco;
                LatestDoctor = PatientDoctor[$thisreco];
                ReferralNumber++;
            }
            
            SumTimes = SumTimes + parseInt(PatientTTV[$thisreco]);
            $thisreco ++;
        }
        
        NPatients = $thisreco-1;
		NReferrals = ReferralNumber;
		MaxPatDisplay = 20;
		if (NPatients>MaxPatDisplay)
		{
			Pages = Math.ceil(NPatients/MaxPatDisplay);
			PatientsPage = Math.ceil(NPatients/Pages);
		}else
		{
			Pages = 1;
			PatientsPage = NPatients;
			$("#MorePage").css('visibility','hidden');
			$("#LessPage").css('visibility','hidden');
			$("#pageNumber").css('visibility','hidden');
		}
  
		ActualPage = 1;
		RadarPage();
		//loadRadarData();
		$('#BotonBusquedaPermit').trigger('click');
    }


    $(window).bind('load', function(){
        $('#BotonBusquedaSents').trigger('click');
        $('#BotonBusquedaPermit').trigger('click');
    });
    
        
    var phase = 1;
    var ancho = $("#header-modal").width();
    $('#content_selpat').css('width',ancho);
    $('#content_seldr').css('width',ancho);
    $('#content_att').css('width',ancho);
    $('#content_addcom').css('width',ancho);
	$('#ScrollerContainer').css('width',ancho*5);
    
        
    $('body').bind('mousedown keydown', function(event) {
        clearTimeout(timeoutTimer);
        timeoutTimer = setTimeout(ShowTimeOutWarning, timeoutTime);
		//alert('Reset counter');
    });
  	
  	var user;
    var doctor;
	//changes for the attachments
    var reportcheck=new Array();
	var reportids='';
    
    /*setInterval(function() {
       $('#BotonBusquedaSents').trigger('click');
      }, 10000);*/

    var phaseReached = 0;
    var numReports = 0;
    $("#PhaseNext").click(function(event) {
    
        
        //       alert('phase'+phase);
       if (phase < 4) phase++; else 
        {
            //alert ('end of loop');   
            $('#CloseModal').trigger('click');
            $('#SendButton').trigger('click');
        }
       
        
       var ancho = $("#header-modal").width()*(phase-1);
         $("#ContentScroller").animate({ scrollLeft: ancho}, 175);
        if (phase > phaseReached)
        {
            phaseReached = phase;
        }
        if ((phase >= phaseReached && phase < 4) )
        {
            
            if (phase == 3)
            {
                numReports = 0;
            }else{
            
                $("#PhaseNext").attr("disabled", "true");
            }
        }
       switch (phase)
       {
        case 1:   $("#selpat").css("color","rgb(61, 147, 224)");
                    $("#seldr").css("color","#ccc");
                    $("#att").css("color","#ccc");
                    $("#addcom").css("color","#ccc");
                    break;     
        case 2:   $("#selpat").css("color","rgb(61, 147, 224)");
                    $("#seldr").css("color","rgb(61, 147, 224)");
                    $("#att").css("color","#ccc");
                    $("#addcom").css("color","#ccc");
                    break;     
        case 3:   $("#selpat").css("color","rgb(61, 147, 224)");
                    $("#seldr").css("color","rgb(61, 147, 224)");
                    $("#att").css("color","rgb(61, 147, 224)");
                    $("#addcom").css("color","#ccc");
                    createPatientReportsNEW ();
                    break;     
        case 4:   $("#selpat").css("color","rgb(61, 147, 224)");
                    $("#seldr").css("color","rgb(61, 147, 224)");
                    $("#att").css("color","rgb(61, 147, 224)");
                    $("#addcom").css("color","rgb(61, 147, 224)");
                    break; 
        default:    alert ('no phase detected');
                    break;
       }
        if (phase == 4){
        
            attachReports();
            showAttachmentIcon(numReports);
        } //$("#Attach").trigger('click');
    });
        
    $("#PhasePrev").click(function(event) {
        if (phase == 3) $("#Attach").trigger('click');
        if (phase >1) phase--; else 
        {
           // alert ('beginning of loop');    
        }
        if (phase < phaseReached)
        {
            $("#PhaseNext").removeAttr("disabled");
        }
        if (phase == 3)
        {
            numReports = 0;
            //$("#PhaseNext").attr("disabled", "true");
        }
        var ancho = $("#header-modal").width()*(phase-1);
        switch (phase)
        {
            case 1:   
                $("#selpat").css("color","rgb(61, 147, 224)");
                $("#seldr").css("color","#ccc");
                $("#att").css("color","#ccc");
                $("#addcom").css("color","#ccc");
                break;     
            case 2:   
                $("#selpat").css("color","rgb(61, 147, 224)");
                $("#seldr").css("color","rgb(61, 147, 224)");
                $("#att").css("color","#ccc");
                $("#addcom").css("color","#ccc");
                break;     
            case 3:   
                $("#selpat").css("color","rgb(61, 147, 224)");
                $("#seldr").css("color","rgb(61, 147, 224)");
                $("#att").css("color","rgb(61, 147, 224)");
                $("#addcom").css("color","#ccc");
                createPatientReportsNEW ();
                break;     
            case 4:   
                $("#selpat").css("color","rgb(61, 147, 224)");
                $("#seldr").css("color","rgb(61, 147, 224)");
                $("#att").css("color","rgb(61, 147, 224)");
                $("#addcom").css("color","rgb(61, 147, 224)");
                break; 
            default:    
                alert ('no phase detected');
                break;
        }
        $("#ContentScroller").animate({ scrollLeft: ancho}, 175);
    });
/*   
    $("#selpat").click(function(event) {
  	   var ancho = $("#header-modal").width()*0;
       $("#ContentScroller").animate({ scrollLeft: ancho}, 175);
    });
    $("#seldr").click(function(event) {
  	   var ancho = $("#header-modal").width()*1;
       $("#ContentScroller").animate({ scrollLeft: ancho}, 175);
    });
    $("#att").click(function(event) {
  	   var ancho = $("#header-modal").width()*2;
       $("#ContentScroller").animate({ scrollLeft: ancho}, 175);
    });
    $("#addcom").click(function(event) {
  	   var ancho = $("#header-modal").width()*3;
       $("#ContentScroller").animate({ scrollLeft: ancho}, 175);
    });
*/    
   
    $("#BotonBusquedaPac").click(function(event) {
    	    var IdUs =156;
    	    var UserInput = $('#SearchUser').val();
    	    var UserEmail = $('#SearchEmail').val();
    	    var IdUsFIXED = $('#SearchIdUsFIXED').val();
    	    var MEDID = $('#MEDID').val();
            var queUrl ='getFullUsersLINK.php?Usuario='+UserInput+'&NReports=10&MEDID='+MEDID+'&Email='+UserEmail+'&IdUsFIXED='+IdUsFIXED;
      	    
      	    $('#TablaPac').load(queUrl);
    	    //$('#TablaPac').trigger('click');
    	    $('#TablaPac').trigger('update');
  	    
    });
    
    $("#BotonBusquedaMen").click(function(event) {
    	    var IdUs =156;
    	    var UserInput = $('#SearchUser').val();
    	    var MEDID = $('#MEDID').val();
            var queUrl ='getMessages.php?Usuario='+UserInput+'&NReports=10&MEDID='+MEDID;
      	    
      	    $('#TablaPac').load(queUrl);
    	    //$('#TablaPac').trigger('click');
    	    $('#TablaPac').trigger('update');
  	    
    });
    
    $("#BotonBusquedaPacCOMP").live('click',function() {
	     var UserInput = $('#SearchUserT').val();
	     var UserDOB = '';
	     var IdMed = $('#MEDID').val();
	     var queUrl ='getSearchUsers.php?Usuario='+UserInput+'&UserDOB='+UserDOB+'&IdDoc='+IdMed+'&NReports=3';
    	 $('#TablaPac').load(queUrl);
    	 $('#TablaPac').trigger('update');
    	 $('#BotonBusquedaSents').trigger('click');
    });     

    $("#BotonBusquedaMedCOMP").live('click',function() {
	     var UserInput = $('#SearchDoctor').val();
	     var UserDOB = $('#DoctorEmail').val();
	     var queUrl ='getSearchDoctors.php?Doctor='+UserInput+'&DrEmail=&NReports=3';
    	 $('#TablaMed').load(queUrl);
    	 $('#TablaMed').trigger('update');
    }); 

    $("#BotonWizard").click(function(event) {
         phase = 1;
         phaseReached = 1;
         numReports = 0;
        $("#PhaseNext").attr("disabled", "true");
         var ancho = $("#header-modal").width()*0;
         $("#ContentScroller").animate({ scrollLeft: ancho}, 175);
        paciente='';
        destino='';
        TextoS ='<span style="color:grey;">Send </span><span style="color:#54bc00; font-size:30px;">   '+paciente+'   </span><span style="color:grey;"> to </span><span style="color:#22aeff; font-size:30px;">   '+destino+'   </span>';
	    if (paciente>'' && destino>'') TextoS = TextoS + '';
		IsOnCounter = 0;
		IsOnCounter2 = 0;
        $('#TextoSend').html(TextoS);
        $("#selpat").css("color","rgb(61, 147, 224)");
        $("#seldr").css("color","#ccc");
        $("#att").css("color","#ccc");
        $("#addcom").css("color","#ccc");
        $('#BotonModal').trigger('click');
        $('#SearchUserT').value = '';
        $('#SearchDoctor').value = '';
        $('#DoctorEmail').value = '';
        $('#TablaPac').empty();
        $('#TablaMed').empty();
    });
    
	//Added a change to for On-behalf functionality
	
	//click(function(event) {
		var IdMed = $('#MEDID').val();
		var url='getGroupDoctors.php?Doctor='+IdMed;
		RecTipo = LanzaAjax (url);
		$('#fill_doctors').html(RecTipo);
        if($("#doclist").children().length == 0)
        {
            $("#doclist").parent().parent().css('display', 'none');
        }
	//});
	
	//Added a toggle button for the distinguishing between archive and active
	$("#Cstate").click(function(event) {
		// decrypt=true;
   		 $('#BotonBusquedaSents').trigger('click');
   	});
	
	$('#Group_toggle').click(function(event) {
		
		/*getInitialRadarData();
		drawReferredOutRadar();
		$('#BotonBusquedaSents').trigger('click');*/
		
		var elem = $("#myTab").find(".active");
		//alert(elem.prop("tagName"));
		//var elem=document.getElementsByClassName("active");
		elem.trigger('click');
		/*if (typeof elem.onclick == "function") {
				elem.onclick.apply();
				alert();
		}*/
		if(document.getElementsByClassName("grid").style.backgroundColor != 'yellow')
			document.getElementsByClassName("grid").style.backgroundColor = 'yellow';
		else if(document.getElementsByClassName("grid").style.backgroundColor == 'yellow')
			document.getElementsByClassName("grid").style.backgroundColor = 'white';
		
		
		
	});
    //$("#BotonBusquedaSents").live('click',function() {
    $("#BotonBusquedaSents").click(function(event) {
 	     var IdMed = $('#MEDID').val();
	     var UserDOB = '';
		 var Username='';
		 var state=0;
		 var group=0;
		 if($('#SearchUserUSERFIXED').val() == ''){
			Username=99;
		 }else{
			Username= $('#SearchUserUSERFIXED').val();
		 }
		 
		 if ($('#Cstate').is(":checked")) state = 1;
		 
		 if($('#Group_toggle').is(":checked")) group=1;
		 //alert(state);
		 
		 $('#Wait11').show();
	     var queUrl ='getSents.php?Doctor='+IdMed+'&DrEmail='+UserDOB+'&NReports=3&Username='+Username+'&state='+state+'&group='+group;
	     //var queUrl ='getSents.php?Doctor='+IdMed+'&DrEmail='+UserDOB+'&NReports=3';
    	 //alert (queUrl);
		 
         $('#TablaSents').load(queUrl);
    	 $('#TablaSents').trigger('update');
		 $('#BotonBusquedaReset').hide();
		 $('#Wait11').hide();
		 $('#radartext').html("");
		 //layer.removeChildren();
		 //layer.draw();
         DrawStats();    
    }); 
	
	$("#BotonBusquedaReset").click(function(event) {
 	     var IdMed = $('#MEDID').val();
	     var UserDOB = '';
		 var Username='';
		 Username=99;
		 var queUrl ='getSents.php?Doctor='+IdMed+'&DrEmail='+UserDOB+'&NReports=3&Username='+Username;
    	 //alert (queUrl);
		 $('#Wait11').show();
         $('#TablaSents').load(queUrl);
    	 $('#TablaSents').trigger('update');
		 $('#radartext').html("");
		 $('#BotonBusquedaReset').hide();
		 $('#Wait11').hide();
		 /*timerrad=window.clearInterval(timerrad);
		 filterInterval();*/
		 //layer.removeChildren();
		 //layer.draw();
		 DrawStats();    
    });

    $(".ROWREF").live('click',function() {
        var myClass = $(this).attr("id");
		//var queMED = $("#MEDID").val();
		//document.getElementById('UserHidden').value=myClass;
		//alert(document.getElementById('UserHidden').value);
		window.location.replace('patientdetailMED-new.php?IdUsu='+myClass);
		//alert('patientdetailMED-new.php?IdUsu='+myClass);
     	//window.location.replace('patientdetailMED.php');
		}); 

	// Changes for revoking the connection
	$("#BotRevoke,#BotCancel").live('click',function(event){
		var idmed = $('#MEDID').val();
		
	    var conf=confirm("Are you sure that you want to revoke this referral connection?");
		
		if(conf){
	
		var id=$(this).parents(".CFILASents").attr('id');
		var revokeurl ='revokeReferralConnection.php?id='+id+'&docid='+idmed;
		RecTipo = LanzaAjax (revokeurl);

	   }

        event.stopPropagation();
        location.reload();
        

	
	});
	
	//Changes for archiving a referral connection
	$("#BotArchive").live('click',function(event){
	
	    var conf=confirm("Are you sure that you want to Archive this referral connection?");
		
		if(conf){
	
		var id=$(this).parents(".CFILASents").attr('id');
		var revokeurl ='ArchiveReferralConnection.php?id='+id;
		RecTipo = LanzaAjax (revokeurl);
		displayalertnotification('Referral connection Archived!');
	    $("#BotonBusquedaSents").trigger('click');

	   }

        event.stopPropagation();

	
	});
	
	
	// Changes for sending a reminder
	$("#BotReminder").live('click',function(event){
	
	    var conf=confirm("Are you sure you want to send a reminder to the Doctor again?");
		
		if(conf){
	
		var id=$(this).parents(".CFILASents").attr('id');
		//var idpac=$(this).parents(".CFILASents").attr('idpac');
		//var idmed=$(this).parents(".CFILASents").attr('idmed');
			
		var remindurl = 'SendReminder.php?id='+id;
		
		RecTipo = LanzaAjax (remindurl);
            //LIne added by Pallab
          //  console.log(RecTipo); //Pallab
            
		//alert ('url: '+remindurl+' Salida:  '+RecTipo);
		displayalertnotification('Reminder has been sent.');
	    $("#BotonBusquedaSents").trigger('click');

	   }

        event.stopPropagation();

	
	});

    $("#BotonBusquedaPermit").click(function(event) {
 	     var IdMed = $('#MEDID').val();
	     var UserDOB = '';
        
        var Username='';
		 if($('#SearchUserUSERFIXED').val().length == 0){
			Username=99;
		 }else{
			Username= $('#SearchUserUSERFIXED').val();
		 }
        
        console.log('Username: ' + Username);
         
		 var group=0;
		 if($('#Group_toggle').is(":checked")) group=1;
		 
	     var queUrl ='getPermits.php?Doctor='+IdMed+'&DrEmail='+UserDOB+'&NReports=3&Username='+Username+'&group='+group;;
    	 $('#TablaPermit').load(queUrl);
    	 $('#TablaPermit').trigger('update');
		 DrawStats();
    });       

   $(".CFILASents").live('click',function() {
     	 var myClass = $(this).attr("id");
 	    // alert (myClass);
 	     
 	     
 	     var IdMed = $('#MEDID').val();
	     var UserDOB = '';
	     var queUrl ='getPermits.php?Doctor='+IdMed+'&DrEmail='+UserDOB+'&NReports=3';
    	 $('#TablaPermit').load(queUrl);
    	 $('#TablaPermit').trigger('update');
		 
    });       
 
    function SendWelcomeMessage(){ 
             var content = $('#WelMes').val();
             var subject='I referred a new member to you.';
             reportids = reportids.replace(/\s+$/g,' ');
             var IdDocOrigin = $('#MEDID').val();
             var cadena = 'GetConnectionId.php?Tipo=1&IdPac='+IdPaciente+'&IdDoc='+IdDoctor+'&IdDocOrigin='+IdDocOrigin;
			 //alert (cadena);
		     RecTipo = LanzaAjax (cadena);
			 //alert (RecTipo);
             //alert ('IdPaciente: '+IdPaciente+' - '+'Sender: '+IdDocOrigin+' - '+'Attachments: '+reportids+' - '+'Receiver: '+IdDoctor+' - '+'Content: '+content+' - '+'subject: '+IdPaciente+' - '+'connection_id: '+RecTipo);
             var cadena='sendMessage.php?sender='+IdDocOrigin+'&receiver='+IdDoctor+'&patient='+IdPaciente+''+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id='+RecTipo;
             var RecTipo=LanzaAjax(cadena);
             //alert ('Answer of Messg Proc.?: '+RecTipo);
    };

    $('#sendmessages_inbox').live('click',function(){
             var sel=$('#doctorsdetails').find(":selected").attr('id');
             var content=$('#messagecontent_inbox').val().replace(/ /g,"sp0e").replace(/\r\n|\r|\n/g,"br8k");
             var subject=$('#subjectname_inbox').val();
             reportids = reportids.replace(/\s+$/g,' ');
            /*
              var cadena='sendMessage.php?sender=<?php // echo $MedID;?>&receiver=<?php // echo $otherdoc;?>&patient=<?php // echo $USERID;?>'+'&content='+content+'&subject='+subject+'&attachments='+reportids+'&connection_id=<?php // echo $referral_id;?>';
             */
             var RecTipo=LanzaAjax(cadena);
             $('#messagecontent_inbox').attr('value','');
             $('#subjectname_inbox').attr('value','');
             displaynotification('status',RecTipo);
            /*
              var cadena='push_server.php?FromDoctorName=<?php// echo $IdMEDName;?>&FromDoctorSurname=<?php // echo $IdMEDSurname;?>&Patientname=<?php // echo $MedUserName; ?>&PatientSurname=<?php // echo $MedUserSurname; ?>&IdUsu=<?php // echo $USERID;?>&message= New Message <br>From: Dr. <?php // echo $IdMEDName;?> <?php // echo $IdMEDSurname;?><br>Subject: '+(subject).replace(/RE:/,'')+'&channel='+<?php // echo $otherdoc?>;
             */
             var RecTipo=LanzaAjax(cadena);
             reportids='';
             $("#attachment_icon").remove();
             $('#message_modal').trigger('click');
  });
    
        
    
    var nonusertoken='-1';
        
    $("#SendButton").live('click',function() {
	     // Confirm
		 //alert ('sending...');
	     var subcadena='';
	     var CallPhone = 0;
		 var sendOnbehalf=0;
		 var Onbehalfdoc='';
        if($('#c2').is(":checked")){
            //alert("checked");
            subcadena =' (will send text message also)';
		    CallPhone = 1; 
        }
	     
        /*if ($('#c2').attr('checked')=='checked'){ 
             alert("checked");
	     	subcadena =' (will send text message also)';
		    CallPhone = 1; 
	     }*/
        
		 var IdDocOrigin = $('#MEDID').val();
	     var NameDocOrigin = $('#IdMEDName').val() ;
	     var SurnameDocOrigin = $('#IdMEDSurname').val() ;
		 var CellPhone = $('#cellphone').val();

	     reportids = reportids.replace(/\s+$/g,' ');
		 var RecTipo;
		 //Added for the sending on behalf
		 if ($('#c2_doc').is(":checked")){
             
	     	//subcadena =' (will send text message also)';
			Onbehalfdoc=$('#doclist').val();
		    sendOnbehalf = 1; 
			//alert(Onbehalfdoc);
	     }
		 
		 //Added for the special referral
		 var reftype=$('#referral_type').val();
        
        var pat_cancelled=-1;
        
	     //Start of new code added by Pallab - to handle non H2M doctors 
         var toEmail;
         if(doctor[0].Name == null)
            toEmail = $("#DoctorEmail").val();
         else
            toEmail = doctor[0].IdMEDEmail;
         //End of new code added by Pallab
            
            
        if(destino>'' || Nondestino > '') {
             
            //Start of New code added by Pallab to find whether the doctor is non h2m or not, if non h2m destino will be blank 
		   if(!(destino > ''))
            var r=confirm('Confirm sending member '+paciente+' to '+toEmail+' ?   '+subcadena);
           else
            var r=confirm('Confirm sending member '+paciente+' to '+destino+' ?   '+subcadena);
            //End of new code added by Pallab
	 	 
           if (r==true)
	 	   {
	    	
            if(destino>'') {
             
                // Update database table (1 or 2) and handle communication with Referral
                // Changed the below line in &ToEmail='+doctor[0].IdMEDEmail+' to &ToEmail='+toEmail
                 var cadena = 'SendReferral.php?Tipo=1&IdPac='+IdPaciente+'&IdDoc='+IdDoctor+'&IdDocOrigin='+IdDocOrigin+'&NameDocOrigin='+NameDocOrigin+'&SurnameDocOrigin='+SurnameDocOrigin+'&ToEmail='+toEmail+'&From='+'&Leido=0&Push=0&estado=1'+'&CallPhone='+CallPhone+'&CellPhone='+CellPhone+'&attachments='+reportids+'&Onbehalfdoc='+Onbehalfdoc+'&sendOnbehalf='+sendOnbehalf+'&reftype='+reftype;
              
                //alert (cadena);
                RecTipo = LanzaAjax (cadena);

                 console.log("From Send Referral"+RecTipo);

                //alert (RecTipo);
                
                $.post("addNotification.php", {type: "NEWREF", sender: IdDocOrigin, is_sender_doctor: true, receiver: IdDoctor, is_receiver_doctor: true, auxilary: IdPaciente}, function(data, status)
                {
                    $('#BotonBusquedaSents').trigger('click');
                    SendWelcomeMessage();
                });
                //location.reload();
                //alert (RecTipo);
                // Refresh table in this page accordingly
                
               
               
            }else if(Nondestino>'') {
                
                //alert("reached here");

             var cadena = 'SendReferral.php?Tipo=0&token='+nonusertoken+'&IdPac='+IdPaciente+'&IdDoc='+IdDoctor+'&IdDocOrigin='+IdDocOrigin+'&NameDocOrigin='+NameDocOrigin+'&SurnameDocOrigin='+SurnameDocOrigin+'&ToEmail='+Nondestino+'&From='+'&Leido=0&Push=0&estado=1'+'&CallPhone='+CallPhone+'&CellPhone='+CellPhone+'&attachments='+reportids+'&Onbehalfdoc='+Onbehalfdoc+'&sendOnbehalf='+sendOnbehalf+'&reftype='+reftype;
             //alert(cadena);

             RecTipo = LanzaAjax (cadena);
            //alert (RecTipo);
             $.post("addNotification.php", {type: "NEWREF", sender: IdDocOrigin, is_sender_doctor: true, receiver: IdDoctor, is_receiver_doctor: true, auxilary: IdPaciente}, function(data, status)
             {
                 $('#BotonBusquedaSents').trigger('click');
                 SendWelcomeMessage();
                    pat_cancelled = 0;
             });
            }
           
               
	 	   }else{

             alert('Member Referral Cancelled.');
			 pat_cancelled = 1;
			// location.reload();
		        }
		 }
		 //alert(RecTipo);
		 var res=parseInt(RecTipo);
        
         //alert(res);
		 if(res){
		 if(res==3){
		 alert('Referral request already present for this member!');
		 }else{
		 //displayalertnotification('Referral request sent!');
         
		 }}
		 else if(pat_cancelled == 0){
		 
		 alert('Although this email is already present in the system, your referral request has been sent. Please make sure you use the filter button for normal referral process!');
		 
		 }
		  //alert(RecTipo);
		 //}
		 
		 $('#TextoSend').html('');
		 destino='';
		 Nondestino='';
		// $("#attachment_icon").hide();
         //location.reload();
       
    });     


 
    $(".CFILADoctor").live('click',function() {
	    //$("#attachment_icon").hide();
     	var myClass = $(this).attr("id");
	 	getMedCreator(myClass);
	 	destino = "Dr. "+doctor[0].Name+" "+doctor[0].Surname; 
	 	IdDoctor = doctor[0].id;
	    PhoneDoctor = doctor[0].phone;
	    if (PhoneDoctor > '') $('#cellphone').val(PhoneDoctor);
	    //alert (destino);	
		Nondestino='';
	    TextoS ='<span style="color:grey;">Send </span><span style="color:#54bc00; font-size:30px;">   '+paciente+'   </span><span style="color:grey;"> to </span><span style="color:#22aeff; font-size:30px;">   '+destino+'   </span>';
	    if (paciente>'' && destino>'') TextoS = TextoS + '';
		$("#PhaseNext").removeAttr("disabled");
		$('#TextoSend').html(TextoS);
        $('#PhaseNext').trigger('click');
    }); 	

    $(".CFILAMODAL").live('click',function() {
	    //$("#attachment_icon").hide();
     	var myClass = $(this).attr("id");
	 	getUserData(myClass);
	 	paciente = user[0].Name+" "+user[0].Surname; 
	 	IdPaciente = user[0].Identif;
	    if(Nondestino>'')
		 TextoS ='<span style="color:grey;">Send </span><span style="color:#54bc00; font-size:30px;">   '+paciente+'   </span><span style="color:grey;"> to </span><span style="color:#22aeff; font-size:30px;">   '+Nondestino+'   </span>';
		else 
		 TextoS ='<span style="color:grey;">Send </span><span style="color:#54bc00; font-size:30px;">   '+paciente+'   </span><span style="color:grey;"> to </span><span style="color:#22aeff; font-size:30px;">   '+destino+'   </span>';
		if (paciente>'' && destino>'') TextoS = TextoS + '<p><span><input type="button" class="btn btn-info" value="Attach Reports" id="AttachButton" style="margin-top:10px;"></p>';
		else if (paciente>'' && Nondestino>'') TextoS = TextoS + '';
		$("#PhaseNext").removeAttr("disabled");
		$('#TextoSend').html(TextoS);
        $('#PhaseNext').trigger('click');
    }); 

    //Changes for adding a non-user
  
	$("#AddNonUser").live('click',function() 
    {
        if($("#DoctorEmail").val().length > 0)
        {
            //var myClass = $(this).attr("id");
            //getMedCreator(myClass);
            //$("#attachment_icon").hide();
            destino='';
            var docId = $("#DoctorEmail").val();
            console.log('adding doctor');
            $.get("getMedCreator.php?UserId=-1&email="+docId, function(data, status)
            {
                //console.log(JSON.parse(data));
                doctor = new Array();
                doctor=data.items;
                //doctor.push(JSON.parse(data));
                console.log(doctor[0]);
                /*if(doctor[0].id == undefined)
                {
                    doctor[0] = doctor[0].items;
                }*/
                IdDoctor = doctor[0].id;
                //alert (IdDoctor);	
                console.log(IdDoctor);
                Nondestino = docId; 
                nonusertoken=doctor[0].token;
                //IdDoctor = doctor[0].id;
                //alert (destino);	
                 TextoS ='<span style="color:grey;">Send </span><span style="color:#54bc00; font-size:30px;">   '+paciente+'   </span><span style="color:grey;"> to </span><span style="color:#22aeff; font-size:30px;">   '+Nondestino+'   </span>';
                if (paciente>'' && destino>'') 
                    TextoS = TextoS + '';
                else if (paciente>'' && Nondestino>'') 
                    TextoS = TextoS + '';
                $("#PhaseNext").removeAttr("disabled");
                $('#TextoSend').html(TextoS);
                $('#PhaseNext').trigger('click');
            },"json");

        }
    });
       
	 
	 
	//Changes for attaching reports
	$("#AttachButton").live('click',function(){
	createPatientReports();
	setTimeout(function(){$("#report_modal").trigger('click');},500);
	
	});

        
    $(".CheckContainer").live('mousedown',function(){
        var myClass = $(this).attr("id");
    
        var recoZ = 0;
        var Yaexiste = 0;


        while (recoZ < IsOnCounter)
        {
           if (IsOn[recoZ] == myClass ) 
            {
                IsOn[recoZ] = 0;
                IsOnCounter2--; 
                Yaexiste = 1;
            }
            recoZ++;
        }
        if (Yaexiste == 0) 
        { 
            IsOnCounter++ ; 
            IsOnCounter2++ ; 
            IsOn[IsOnCounter] = myClass;
        }
        $('#NumberRA').html (IsOnCounter2+' Reports Attached');
        if (IsOnCounter2 >0) 
        {
            $("#attachment_icon").css("display","visible");
            $("#attachment_icon").css("color","#22aeff");
            $("#attachment_icon").addClass("icon-spin");
        }
        else
        {
           // $("#attachment_icon").css("display","none");
            $("#attachment_icon").css("color","#ccc");
            $("#attachment_icon").removeClass("icon-spin");
        }
        
    });
        
    $('input[type=checkbox][id^="reportcol"]').live('click',function()
    {
        var val = (this.checked ? "1" : "0");
        if (val == 1)
        {
            numReports++;
        }
        else
        {
            numReports--;
        }
        if (numReports == 0)
        {
            //$("#PhaseNext").attr("disabled", "true");
        }
        else
        {
           
           // $("#PhaseNext").removeAttr("disabled");
        }
    });
        
    function attachReports(){
        reportids='';
        
        $('input[type=checkbox][id^="reportcol"]').each(
        function () {
				var sThisVal = (this.checked ? "1" : "0");
				//sList += (sList=="" ? sThisVal : "," + sThisVal);
				if(sThisVal==1)
                {
				    var idp=$(this).parents("div.attachments").attr("id");
				    //alert("Id "+idp+" selected"); 
				    reportcheck.push(this.id);
				    //messageid=messageid+idp+' ,';
				    reportids=reportids+idp+' ';
				 
				}
				
			
				
	});
	// alert(reportids);
	var conf=false;
    if(reportids>'')
		//conf=confirm("Confirm Attachments");
        conf=true;
	if(conf){
        //alert ('confirmed');
	//$("#AttachButton").attr('value','Reports Attached');

	//alert(reportids);
       
	}else{
        reportids='';
        for (i = 0 ; i < reportcheck.length; i++ ){

            document.getElementById(reportcheck[i]).checked = false;

        }
        reportcheck.length=0;
	//$("#Reply").trigger('click');
	}
	//setTimeout(function(){$("#report_modal").trigger('click');},50);
		
	}
        
        
  function showAttachmentIcon(isReportAttached){
  
        if (isReportAttached >0) 
        {
            $("#attachment_icon").css("display","visible");
            $("#attachment_icon").css("color","#22aeff");
            $("#attachment_icon").addClass("icon-spin");
        }
        else
        {
           // $("#attachment_icon").css("display","none");
            $("#attachment_icon").css("color","#ccc");
            $("#attachment_icon").removeClass("icon-spin");
        }
        
  }
	
	
  function createPatientReports(){
		var ElementDOM ='All';
		var EntryTypegroup ='0';
		var Usuario = IdPaciente   //$('#userId').val();
		var MedID =$('#MEDID').val();
		
		var queUrl ='createAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID;
      	//var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports=1226';
      	$("#attachments").load(queUrl);
    	$("#attachments").trigger('update');
		$("#attachments").show();
  
  }	
  function createPatientReportsNEW (){
		var ElementDOM ='All';
		var EntryTypegroup ='0';
		var Usuario = IdPaciente   //$('#userId').val();
		var MedID =$('#MEDID').val();
        //$("#Phase3Container").html(data);
        $("#ReportStream").html('');
		$("#H2M_Spin_Stream").show();
		setTimeout(function(){
			var queUrl ='createAttachmentStreamNEWTEST.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID;
            console.log(queUrl);
			//var queUrl ='CreateAttachmentStream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&Reports=1226';
            $.get(queUrl, function(data, status)
            {
                //$("#Phase3Container").html(data);
                $("#ReportStream").html(data);
                //$("#Phase3Container").trigger('update');
                $("#Phase3Container").show();
                $("#H2M_Spin_Stream").hide();
            });
			
			
			
			
		},1000);
 }	
	  

	function displayalertnotification(message){
	 
	  var gritterOptions = {
				   title: 'status',
				   text: message,
				   image:'images/Icono_H2M.png',
				   sticky: false,
				   time: '3000'
				  };
		$.gritter.add(gritterOptions);
	   
	  } 


        
    function getBlocks(serviceURL) {
    	$.ajax(
           {
           url: serviceURL,
           dataType: "json",
           async: false,
           success: function(data)
           {
           	blocks = data.items;
           }
         });
     }        

    $(".CFILA").live('click',function() {
     /*	var myClass = $(this).attr("id");
     	var NombreEnt = $('#NombreEnt').val();
     	var PasswordEnt = $('#PasswordEnt').val();
     	//window.location.replace('patientdetail.php?Nombre='+NombreEnt+'&Password='+PasswordEnt+'&IdUsu='+myClass);
     	//alert (myClass);
        $('#BotonModal').trigger('click');
      */
    });
    
    $(".view-button").live('click',function() {
     	var myClass = $(this).attr("id");
     	$('#queId').attr("value",myClass);
     	var NameMed = $('#IdMEDName').val();
     	var SurnameMed = $('#IdMEDSurname').val();
     	var PasswordEnt = $('#PasswordEnt').val();
        var MEDID = $('#MEDID').val();
        var MEDEmail = $('#IdMEDEmail').val();
    
        $('#BotonModal').trigger('click');
    });
  
    $("#ConfirmaLink").live('click',function() {
     	var To = $('#queId').val();
    	getUserData(To);
    
    	if (user[0].email==''){
        	var IdCreador = user[0].IdCreator;
	    	
	    	alert ('orphan user . Creator= '+IdCreador);
	    	
	    	getMedCreator(IdCreador);

	    	var NameMed = $('#IdMEDName').val();
	    	var SurnameMed = $('#IdMEDSurname').val();
	    	var From = $('#MEDID').val();
	    	var FromEmail = $('#IdMEDEmail').val();
	    	var Subject = 'Request conection from Dr. '+NameMed+' '+SurnameMed;
        
	    	var Content = 'Dr. '+NameMed+' '+SurnameMed+' is requesting to establish connection with your member named: '+user[0].Name+' '+user[0].Surname+' (UserId:  '+To+'). Please confirm, or just close this message to reject.';
    	
	    	var destino = "Dr. "+doctor[0].Name+" "+doctor[0].Surname; 
	    	var cadena = 'MsgInterno.php?Tipo=1&IdPac='+user[0].Identif+'&To='+doctor[0].id+'&ToEmail='+doctor[0].IdMEDEmail+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1';
	    	
	    	var RecTipo = LanzaAjax (cadena);
	    	
    	}
    	else
    	{
      	var NameMed = $('#IdMEDName').val();
     	var SurnameMed = $('#IdMEDSurname').val();
    	var From = $('#MEDID').val();
        var FromEmail = $('#IdMEDEmail').val();
        var Subject = 'Request conection ';
        
        var Content = 'Dr. '+NameMed+' '+SurnameMed+' is requesting to establish connection with you (UserId:  '+To+'). Please confirm, or just close this message to reject.';
    	
    	var cadena = 'MsgInterno.php?Tipo=0&IdPac=0&To='+To+'&ToEmail='+user[0].email+'&From='+From+'&FromEmail='+FromEmail+'&Subject='+Subject+'&Content='+Content+'&Leido=0&Push=0&estado=1';
				
		var RecTipo = 'Temporal';
	                     $.ajax(
                                {
                                url: cadena,
                                dataType: "html",
                                async: false,
                                complete: function(){ 
                                
                                },
                                success: function(data)
                                {
                                if (typeof data == "string") {
                                RecTipo = data;
                                }
                                }
                                });
                         
	   //alert (RecTipo);	    
	   //var Content = 'Dr. '+NameMed+' '+SurnameMed+' is requesting to establish connection with you (UserId:  '+To+'). Please click the button: </br><input type="button" href="www.inmers.com/ConfirmaLink?User='+To+'&Doctor='+From+'&Confirm='+RecTipo+'" class="btn btn-success" value="Confirm" id="ConfirmaLink" style="margin-top:10px; margin-bottom:10px;"> </br> to confirm, or just close this message to reject.';
	   
	   //EnMail(user[0].email, 'MediBANK Link Request', Content);  // NO SE USA AQUÍ, PERO SI FUNCIONA PERFECTAMENTE PARA ENVIAR MENSAJES DE EMAIL DESDE JAVASCRIPT
	   }
	   //$('#myModal').modal('hide');
	   $('#CloseModal').trigger('click');
	   $('#BotonBusquedaPac').trigger('click');

    });

    
    $('#Wait1')
    .hide()  // hide it initially
    .ajaxStart(function() {
        $(this).show();
    })
    .ajaxStop(function() {
        $(this).hide();
    }); 
    
  
  function getUserData(UserId) {
 	var cadenaGUD = 'getUserData.php?UserId='+UserId;
    $.ajax(
           {
           url: cadenaGUD,
           dataType: "json",
           async: false,
           success: function(data)
           {
           user = data.items;
           }
           });
    }

  function getMedCreator(UserId) {
 	var cadenaGUD = 'getMedCreator.php?UserId='+UserId;
    $.ajax(
           {
           url: cadenaGUD,
           dataType: "json",
           async: false,
           success: function(data)
           {
           doctor = data.items;
           }
           });
    }

  
  function EnMail (aQuien, Tema, Contenido)
  {
	  var cadena = 'EnMail.php?aQuien='+aQuien+'&Tema='+Tema+'&Contenido='+Contenido;
	  var RecTipo = 'Temporal';
	  $.ajax(
      {
          url: cadena,
          dataType: "html",
          async: false,
          complete: function(){ 
               },
          success: function(data)
               {
               if (typeof data == "string") {
               RecTipo = data;
                      }
               }
      });
	    
  }
  
  	function LanzaAjax (DirURL)
		{
		var RecTipo = 'SIN MODIFICACIÓN';
	    $.ajax(
           {
           url: DirURL,
           dataType: "html",
           async: false,
           complete: function(){ 
                    },
           success: function(data) {
                    if (typeof data == "string") {
                                RecTipo = data;
                                }
                     }
            });
		return RecTipo;
		}    

 	window.onload = function(){		
	 	
	 	var PaquetesSI = parseInt($('#PaquetesSI').val());
	 	var PaquetesNO = parseInt($('#PaquetesNO').val());
	 	var PTotal = PaquetesSI + PaquetesNO;
	 	var porcenSI = Math.floor((PaquetesSI*100)/PTotal);
	 	var porcenNO = Math.floor((PaquetesNO*100)/PTotal);
	 	Morris.Donut({
			element: 'MiDonut',
			colors: ['#0fa200','#ff5d5d'],
			formatter: function (y) { return  y +' %' },
			data: [
				{label: "IN USE", value: porcenSI},
				{label: "Not used", value: porcenNO}
				]
			});
		};
  