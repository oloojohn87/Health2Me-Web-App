$( document ).ready(function() {  
        
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
    
        var EntryTypegroup = 0;
        queUrl =domain+"mobile-member/get_num_reports.php?id="+user;
        var num_reports = LanzaAjax(queUrl);
        
        //get the first 20 reports
		get_reports( user, domain, num_reports, 0 );
		
		
		$(this).addClass("active");
		var queid = $(this).attr("id");
		var ElementDOM="";
	
		$("#box-1").click(function() {
			//alert("hello");	
			//$("#Lab Tests ").parent().parent().hide()
			get_reports( user, domain, num_reports, 1 );
		});	
		$("#box-2").click(function() {
			get_reports( user, domain, num_reports, 7 );
		});	
		$("#box-3").click(function() {	
			get_reports( user, domain, num_reports, 2 );
		});	
		$("#box-4").click(function() {
			get_reports( user, domain, num_reports, 6 );
		});		
		$("#box-5").click(function() {
			get_reports( user, domain, num_reports, 1 );
		});	
		$("#box-6").click(function() {
			get_reports( user, domain, num_reports, 5 );
		});	
		$("#box-7").click(function() {
			get_reports( user, domain, num_reports, 7 );
		});	
		$("#box-8").click(function() {
			get_reports( user, domain, num_reports, 4 );
		});	
		
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
	

   
});    
/********************************************************/
/*In: Group												*/
/*Out : EntryTypeGroup									*/
/*Desc: 												*/
function get_reports(user,domain, num_reports, entry_type_group){
	
	//num_reports;
	   	queUrl =domain+'mobile-member/create_report_stream.php?ElementDOM=na&EntryTypegroup='+entry_type_group+'&Usuario='+user+'&MedID='+user+'&num_reports='+num_reports+'&isDoctor=0';
		
		var returnHTML = LanzaAjax(queUrl);
		$("#med-history").html(returnHTML);
		
}	

var lastRectipo='';
function get_more_reports(user, queid, offset)
{
		var EntryTypegroup =queid;
		var Usuario = $('#userId').val();
		var MedID =$('#MEDID').val();
        if(MedID < 0)
        {
            MedID = $("#USERID").val();
        }
        $("#stream_load_indicator").css("display", "block");
        var isDoctor = 1;
        if(Usuario == MedID)
        {
            isDoctor = 0;
        }
        
            
            var queUrl ='createReportStreamDocGroupNEWTEST.php?ElementDOM=na&EntryTypegroup='+EntryTypegroup+'&Usuario='+Usuario+'&MedID='+MedID+'&offset='+offset+'&jump='+jump+'&isDoctor='+isDoctor;
        
		var RecTipo='<span class="label label-success" style="left:0px; margin-left:0px; margin-top:20px; margin-bottom:20px; font-size:14px; text-shadow:none; text-decoration:none;background-color:red">Could not load Reports due to Internet issues</span>';
				
				$.ajax(
				   {
				   url: queUrl,
				   dataType: "html",
				   
				   complete: function(){ 
							},
				   success: function(data) {
							if (typeof data == "string") {
									
										RecTipo = data;
										offset1=offset+jump;

							   }

							if(RecTipo != lastRectipo)
							{
							
                                var count = countOccurences(RecTipo, "note");  
				                var new_width=$("#ascroll").width()+(count * 160);			//set new width
                                
                                Elementwidth=new_width;
                                
                                console.log("new_width: "+Elementwidth);
                                
                                $("#ascroll").css("width", new_width+"px");
                                
                                $('#ascroll').children().eq($('#ascroll').children().length - 2).after(RecTipo);
                                setTimeout(function() {highlightattachedreports();},1000);  
                                $("#stream_load_indicator").css("display", "none");

                                lastRectipo=RecTipo;
                                if(count < 8)    
                                { 
                                    $('#ascroll').children().last().css("display", "none");
                                    $('#ascroll').css("width", $('#ascroll').width() - 70);
                                }
							}
				},
				   error: function(data){
						displaynotification('Failed to Load Data','');
				   }
					
				});

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
 		 		