function Draw(canvas_id)
{
    /*
     *  PROPERTIES
     */
    var canvas = null;
    var ctx = null;
    var flag = false;
    var prevX = 0;
    var currX = 0;
    var prevY = 0;
    var currY = 0;
    var dot_flag = false;
    
    var style = "red";
    var line_width = 3;
    
    /*
     *  PRIVATE METHODS
     */
    
    function draw() 
    {
        ctx.beginPath();
        ctx.moveTo(prevX, prevY);
        ctx.lineTo(currX, currY);
        ctx.strokeStyle = style;
        ctx.lineWidth = line_width;
        ctx.closePath();
        ctx.stroke();
    }
    
    /*
     *  PUBLIC METHODS
     */
    
    this.begin = function(mouse) 
    {
        prevX = currX;
        prevY = currY;
        currX = mouse.x;
        currY = mouse.y;
        

        flag = true;
        dot_flag = true;
        
        if (dot_flag) 
        {
            ctx.beginPath();
            ctx.fillStyle = style;
            ctx.fillRect(currX, currY, 2, 2);
            ctx.closePath();
            dot_flag = false;
        }
    }
    
    this.move = function(mouse)
    {
        if(flag) 
        {
            prevX = currX;
            prevY = currY;
            currX = mouse.x;
            currY = mouse.y;
            draw();
        }
    }
    
    this.end = function(mouse)
    {
        flag = false;
    }
    
    
    
    
    
    /*
     *  INITIALIZE
     */
    
    canvas = document.getElementById(canvas_id);
    ctx = canvas.getContext("2d");
    ctx.lineJoin = "round";
    var w = canvas.width;
    var h = canvas.height;
}