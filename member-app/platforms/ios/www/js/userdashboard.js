$( document ).ready(function() {
    console.log( "now to load some donuts!" );
    
    
    /*DRAW DONUT*/
    
        var canvas=document.getElementById("canvas");
        var ctx=canvas.getContext("2d");

        // define the donut
        var cX = Math.floor(canvas.width / 2);
        var cY = Math.floor(canvas.height / 2);
        var radius = Math.min(cX,cY)*.80;

        // the datapoints
        var data=[];
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
        colors.push("#3498db");

        // track the accumulated arcs drawn so far
        var totalArc=0;

        // draw a wedge
        function drawWedge2(percent, color) {
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
        function drawDonut(){
            for(var i=0;i<data.length;i++){
                drawWedge2(data[i],colors[i]);
            }
            // cut out an inner-circle == donut
            ctx.beginPath();
            ctx.moveTo(cX,cY); 
            ctx.fillStyle=gradient;
            ctx.arc(cX, cY, radius*.8, 0, 2 * Math.PI, false);
            ctx.fill(); 
        }

        // draw the background gradient
        var gradient = ctx.createLinearGradient(0,0,canvas.width,0);
        gradient.addColorStop(0, "#Fbfbfb");
        gradient.addColorStop(0.75, "#Fbfbfb");
        ctx.fillStyle = gradient;
        ctx.fillRect(0,0,canvas.width,canvas.height);

        // draw the donut
        drawDonut();
    
     $('h2m_button').on({
            'mouseover' : function() {
            $(this).attr('src','http://media02.hongkiat.com/css3-code-slim/css3-markup.jpg');
            },
            'mouseout' : function() {
                $(this).attr('src','http://www.w3.org/html/logo/downloads/HTML5_Logo_512.png');
            }
  });   


    
});

