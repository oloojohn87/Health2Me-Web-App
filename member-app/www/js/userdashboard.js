$( document ).ready(function() {
    
	$("#clicker").click(function() {
  		alert( "Handler for .click() called." );
	});
	
	console.log( "now to load some donuts!" );
    //get variables from local storage set in index.js
        var temp = localStorage.getItem('mydata');
	
	    if(temp != null) {
            var dataJSON = JSON.parse(temp);
            domain=dataJSON.domain;
            user = dataJSON.user;
        }
    
    	/*DRAW DONUT*/
        console.log("getting here before");
		getSummaryData(user);
		console.log("getting here after");
        var canvas=document.getElementById("canvas");
        var ctx=canvas.getContext("2d");

        // define the donut
        var cX = Math.floor(canvas.width / 2);
        var cY = Math.floor(canvas.height / 2);
        var radius = Math.min(cX,cY)*.80;
	    var x = canvas.width / 2;
      	var y = canvas.height / 2;
      	//var radius = 70;
      	var startAngle = 0 - (Math.PI/2);
      	var counterClockwise = false;
	
		var AdminData = 0;
		var ColSeg = new Array();
	  	var SizSeg = new Array();
	  	var ImgSeg = new Array();
	  	var MaxValue = new Array();
	  	var UIValue = new Array();
	  	var NameSeg1 = new Array();
	  	var NameSeg2 = new Array();

        // the datapoints
        /*var data=[];
        data.push(37.34);
        data.push(28.60);
        data.push(1.78);
        data.push(10.84);
        data.push(10.74);
        data.push(10.70);

        // colors to use for each datapoint
        var colors=[];
        colors.push("#54bc00");
        colors.push("#f39c12");
        colors.push("#2c3e50");
        colors.push("#18bc9c");
        colors.push("#e74c3c");
        colors.push("#3498db");*/

        // track the accumulated arcs drawn so far
        var totalArc=0;

        // draw a wedge
        function drawWedge(percent, color) {
            // calc size of our wedge in radians
            var WedgeInRadians=percent/100*360 *Math.PI/180;
            // draw the wedge
            ctx.save();
            ctx.beginPath();
            ctx.moveTo(cX, cY);
            ctx.arc(cX, cY, radius, totalArc, totalArc+WedgeInRadians, false);
            ctx.closePath();
            ctx.fillStyle = color;
            ctx.fill();
            ctx.restore();
            // sum the size of all wedges so far
            // We will begin our next wedge at this sum
            totalArc+=WedgeInRadians;
			
			
        }

        // draw the donut one wedge at a time
        function drawDonut(SizSeg,ColorSeg, NameSeg1, NameSeg2, total){
			console.log(SizSeg.length);
			console.log(ColorSeg.length);
			console.log(total);
			XBox = 230;
			var widthSegment = 15;
            for(var i=0;i<SizSeg.length;i++){
				
				var PointAngle = (radius)-(widthSegment/2);
				midAngle = startAngle + (TranslateAngle(SizSeg[i]/total*100,total));
				
				var posx = cX + (PointAngle  * Math.cos (midAngle));
				var posy = cY + (PointAngle  * Math.sin (midAngle));
				
				console.log("Mid Angles : "+midAngle);
				
				if (midAngle < 1) {
					posx =230;
				}
				if (midAngle >= 1) {
					posx =30;
				}
			
                drawWedge(SizSeg[i]/total*100,ColorSeg[i]);
				
				// Label Text
				if (SizSeg[i] > 0) {
					drawLabel(NameSeg1[i], NameSeg2[i], ColorSeg[i],SizSeg[i], posx, posy);
				}
            }
            // cut out an inner-circle == donut
            ctx.beginPath();
            ctx.moveTo(cX,cY); 
            ctx.fillStyle=gradient;
            ctx.arc(cX, cY, radius*.8, 0, 2 * Math.PI, false);
            ctx.fill(); 
			
			
			
        }
		function drawIcon() {
			
		}
		function drawLabel(Name, Abbrev, Color, Size, XBox, YBox) {
			//Draw Label
			ctx.font = "10px Arial";
			ctx.fillStyle = '#b6b6b6';
			ctx.fillText(Name,XBox,YBox+8);
			ctx.fillText(Abbrev,XBox,YBox+8+10);
			     
			      
			// Divisory Line
			ctx.beginPath();
			ctx.lineWidth = 3;
			ctx.strokeStyle = Color;
			ctx.lineCap = 'round';
		 	ctx.moveTo(XBox +35, YBox);
			ctx.lineTo(XBox +35, YBox+20);
			ctx.stroke();
			
			// Section Percentage	  
			ctx.font = "bold 14px Arial";
			ctx.fillStyle = 'grey';
				  percentSeg = Size;
				  if (percentSeg == 100) labelSeg = 'OK'; else labelSeg = percentSeg + '';
				  ctx.fillText(labelSeg,XBox+40,YBox+5+10);
			      //context.stroke();
			
		}
		function drawInnerCircle() {
			// Draw Inner Circle
			var x = canvas.width / 2;
      		var y = canvas.height / 2;
			ctx.beginPath();
			ctx.arc(x,y,25,0,2*Math.PI);
			ctx.strokeStyle='rgba(100,100,100,.5)';
			ctx.stroke();
			
			ctx.fillStyle='rgb(84,188,0)';
			ctx.fill();
			
			var font = '22pt Arial';
		  	var message = total_reports+'';
		  	ctx.fillStyle = 'white';
		  	ctx.textAlign = 'left';
		  	ctx.textBaseline = 'top'; // important!
		  	ctx.font = font;
		  	var w = ctx.measureText(message).width;
		  	var TextH = 25;
		  	ctx.fillText(message, x-(w/2), y-(TextH-7));

		}
	

		
        // draw the background gradient
        var gradient = ctx.createLinearGradient(0,0,canvas.width,0);
        gradient.addColorStop(0, "#Fbfbfb");
        gradient.addColorStop(0.75, "#Fbfbfb");
        ctx.fillStyle = gradient;
        ctx.fillRect(0,0,canvas.width,canvas.height);

	
		var total_reports = getReportData();
        // draw the donut
        
		drawInnerCircle();
		
		
		$("#canvas").click(function(){
  			window.location.href="records.html";
		});
    
     $('h2m_button').on({
            'mouseover' : function() {
            $(this).attr('src','http://media02.hongkiat.com/css3-code-slim/css3-markup.jpg');
            },
            'mouseout' : function() {
                $(this).attr('src','http://www.w3.org/html/logo/downloads/HTML5_Logo_512.png');
            }
  });  

//Get Data for Donut
function getReportData() {
	
			// REPORTS GRAPH
			// Get Basic Icons and Colors for every type of report
 	  		var RepData = Array();
			// Ajax call to retrieve a JSON Array **php return array** 
			var queUrl = domain+'mobile-member/get_report_set.php';
			$.ajax(
			{
				url: queUrl,
				dataType: "json",
				async: false,
				success: function(data)
				{
					//alert('Data Fetched');
					RepData = data.items;
					
					console.log('**** RepData: *****');
					console.log("Rep Data : " + RepData);
					console.log('*******************');
					
				},
 	       		error: function (xhr, ajaxOptions, thrownError) {
	        		alert(xhr.status);
					alert(thrownError);
	       		}

			});	
			// Get Report Data for this user
			var TotalRep = 0;
 	  		var RepNumbers = Array();
			// Ajax call to retrieve a JSON Array **php return array** 
			var queUrl = domain+'mobile-member/get_report_numbers.php?User='+user;
        	console.log('url = '+queUrl);
			$.ajax(
			{
				url: queUrl,
				dataType: "json",
				async: false,
				success: function(data)
				{
					//alert('Data Fetched');
					RepNumbers = data.items;
					

					

				},
 	       		error: function (xhr, ajaxOptions, thrownError) {
	        		alert(xhr.status);
					alert(thrownError);
	       			}

			});
		
			var count = 0;
	  			
        
      		console.log('**** RepNumbers: *****');
      		console.log(RepNumbers);
      		console.log('*******************');
 	  
      		while (count < 9){
		  		SizSeg[count] = RepNumbers[count].number;
		  		NameSeg1[count] = RepData[count].title.substring(0, 4);
          		ColSeg[count] = RepData[count].color;
		  		NameSeg2[count] = RepData[count].abrev;
          
          		TotalRep = TotalRep + parseInt(SizSeg[count]);
		  		count++;
	  		}
	
		console.log("Total Rep : "+TotalRep);
			drawDonut(SizSeg, ColSeg, NameSeg1, NameSeg2, TotalRep);
			return TotalRep;
  } //End getReportData
	
	
	
  function getSummaryData(user)	{
	  var AdminData = 0;

		// Ajax call to retrieve a JSON Array **php return array** 
		var queUrl = domain+'mobile-member/getSummaryData.php?User='+user;
 		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async: false,
			success: function(data)
			{
				//alert('Data Fetched');
				ConnData = data.items;
				
				
				AdminData = ConnData[0].Data;
	  			PastDx = ConnData[1].Data;
	  			Medications = ConnData[2].Data;
	  			Immuno = ConnData[3].Data;
	  			Family = ConnData[4].Data;
	  			Habits = ConnData[5].Data;
	  			Allergies = ConnData[6].Data;
	  			console.log("Admin Data : "+AdminData);
	    		console.log("PastDX : "+PastDx);
	  			console.log("Medications : "+Medications);
	  			console.log("Immuno: "+Immuno);
	  			console.log("Family: "+Family);
	  			console.log("Habits: "+Habits);
	  			console.log("Allergies: "+Allergies);
			}
		});
  }
function TranslateAngle(x,maxim){
	    var y = (x * Math.PI * 2) / maxim;
	    return parseFloat(y);
    }

    
});
function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

