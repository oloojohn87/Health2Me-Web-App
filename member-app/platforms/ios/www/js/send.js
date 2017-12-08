$( document ).ready(function() {  
    	 //$( init );
		//get variables from local storage set in index.js
        var temp = localStorage.getItem('mydata');
        var domain = '';
        var user = '';
        var num_reports = '';
        
        if(temp != null) {
            var dataJSON = JSON.parse(temp);
            domain=dataJSON.domain;
            user = dataJSON.user;
        }
	
	        if(temp != null) {
            var dataJSON = JSON.parse(temp);
            domain=dataJSON.domain;
            user = dataJSON.user;
        }
    
	
        var EntryTypegroup = 0;
        queUrl =domain+"mobile-member/get_num_reports.php?id="+user;
        var num_reports = LanzaAjax(queUrl);
        
        //get the first 20 reports
        queUrl =domain+'mobile-member/create_report_stream.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+user+'&MedID='+user+'&num_reports='+num_reports+'&isDoctor=0';
        var returnHTML = LanzaAjax(queUrl);
     
        $("#med-history").replaceWith(returnHTML); 
	
		$scroll_container = $('#container2');
		$('#left-scroll').click(function() {
			var current_position = $scroll_container.scrollLeft();
			var new_position = current_position-300;
			$scroll_container.animate({ scrollLeft: new_position}, "slow");
		
		});	
		$('#right-scroll').click(function() {
			var current_position = $scroll_container.scrollLeft();
			var new_position = current_position+300;
			$scroll_container.animate({ scrollLeft: new_position}, "slow");	
			
		});	


		//code for dragging and dropping thumbnails
		$.fn.draggable = function() {
  			var offset = null;
  			var start = function(e) {
    		var orig = e.originalEvent;
    		var pos = $(this).position();
    		offset = {
      			//x: orig.changedTouches[0].pageX - pos.left,
      			//y: orig.changedTouches[0].pageY - pos.top
    		};
  		};
  		var moveMe = function(e) {
    		e.preventDefault();
    		var orig = e.originalEvent;
    		$(this).css({
      			top: orig.changedTouches[0].pageY - offset.y,
      			left: orig.changedTouches[0].pageX - offset.x
    		});
			//$(this).css("height","100px");
			//$(this).css("width","60px");
			$(this).appendTo('#droppable');
			$(this).addClass('thumbnail');
			$("#start-text").hide();
  		};
  		this.bind("touchstart", start);
  		this.bind("touchmove", moveMe);	
		};

	
		$(".note").draggable({
			cursor: 'move',
    		containment: 'window',
			revert: 'valid',
			scroll: false,
    		helper: 'clone',
			appendTo: 'body',
			//grid: [ 80, 80 ],
			snap: ".ui-widget-header", 
			snapMode: "outer" 
			
		});
		$dropContainer = $("#droppable");
		$dropContainer.droppable({
			tolerance: 'intersect',
			activeClass: "ui-state-default",
			hoverClass: 'ui-state-active',
			appendTo: $dropContainer,
			
			drop: function (event, ui) {
										
					var dropElemId = ui.draggable.attr("id");
              		var dropElem = ui.draggable.html();
					clone = $(dropElem).clone(); // clone it and hold onto the jquery object
                    clone.id="newId";
					droppableOffset=$dropContainer.offset();
                    clone.css("position", "absolute");
					close.css("height", "25px");
          			clone.css("top", ui.absolutePosition.top);
                    clone.css("left", ui.absolutePosition.left);
					$this.addClass("thumbnail");
					$this.css("overflow", "hidden");
					$(this).append(clone);
					$(this).appendTo("#droppable");
					

            },
            over: function (event, ui) {
                       
					clone.draggable({
           						 grid: [50, 50],
        			});
            },
            out: function (event, ui) {
                        //$("#info").html("moving out!");
            }
		});

    
});     


 function init() {
  document.addEventListener("touchstart", touchHandler, true);
  document.addEventListener("touchmove", touchHandler, true);
  document.addEventListener("touchend", touchHandler, true);
  document.addEventListener("touchcancel", touchHandler, true);   
  }
  function touchHandler(event)
  {
  var touches = event.changedTouches,
  first = touches[0],
  type = "";
  switch(event.type)
  {
  case "touchstart": type = "mousedown"; break;
  case "touchmove":  type="mousemove"; break;        
  case "touchend":   type="mouseup"; break;
  default: return;
  }
  var simulatedEvent = document.createEvent("MouseEvent");
   simulatedEvent.initMouseEvent(type, true, true, window, 1,
                      first.screenX, first.screenY,
                      first.clientX, first.clientY, false,
                      false, false, false, 0/*left*/, null);
  first.target.dispatchEvent(simulatedEvent); 
  event.preventDefault();
   }	
   
/***************************************************************/
/*In: DirUrl- url of ajax call								   */
/*Out: String of data returned by ajax call					   */
/*Desc:  A function to handle ajax calls					   */
/***************************************************************/
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