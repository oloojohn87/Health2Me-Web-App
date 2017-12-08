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
	
    $("#email-request-doc").on('click',function(){
        var emailId = $("#doctor-email").val();
        var messageForDoc ='Not sure what this should be yet';
  
       /*send request to doctors*/    
       $.get("http://dev.health2.me/RequestReportsFromExternalDoc.php?emailId="+emailId+"&user="+user+"&message="+messageForDoc,function(data,status)
              {
				  
				  $('#request-message').fadeIn();
            
              });
        
        
  
    });
    
});     