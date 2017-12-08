/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
$( document ).ready(function() { 
    
    //get family members
    /*var data = {
        domain: 'http://dev.health2.me/',
        user: '2094'
    };

    localStorage.setItem('mydata', JSON.stringify(data));  // mydata is the name of storage*/
    //initialize temp folders&permission
    
}); 

var app = {
    // Application Constructor
    initialize: function() {
        this.bindEvents();
    },
    // Bind Event Listeners
    //
    // Bind any events that are required on startup. Common events are:
    // 'load', 'deviceready', 'offline', and 'online'.
    bindEvents: function() {
        document.addEventListener('deviceready', this.onDeviceReady, false);
    },
    // deviceready Event Handler
    //
    // The scope of 'this' is the event. In order to call the 'receivedEvent'
    // function, we must explicitly call 'app.receivedEvent(...);'
    onDeviceReady: function() {
        app.receivedEvent('deviceready');
    },
    // Update DOM on a Received Event
    receivedEvent: function(id) {
        var parentElement = document.getElementById(id);
        var listeningElement = parentElement.querySelector('.listening');
        var receivedElement = parentElement.querySelector('.received');

        listeningElement.setAttribute('style', 'display:none;');
        receivedElement.setAttribute('style', 'display:block;');

        console.log('Received Event: ' + id);
    }
};
 function validation_check(){

  var username = $("#username").val();
  var password = $("#password").val();	 
  
  if (username == "") {
   	alert("Please Enter Username.");
   	$("#username").focus(); 
	return false;  
  } 
  else if (password == "") 
  {
   	alert("Please Enter Password.");
   	$("#password").focus();
	return false; 
  }
  else
   {	

	   
	    $.post("http:///dev.health2.me/mobile-member/login.php", {user: username, pass:password}, function(data, status)
        {
			//set user info here
		
        	console.log(data);
			var obj = JSON.parse(data);
			
			user = obj.user;
			firstname = obj.firstname;
			surname = obj.surname;
			family = obj.family;
			
			for ( var i = 0; i < family.members.length; i++) {
				console.log(family.members[i].fname);
				console.log(family.members[i].fid);
			}

			console.log("User : "+user);
			console.log("Firstname : "+firstname);
			console.log("Surname : "+surname);
			
			var data = {
        		domain: 'http://dev.health2.me/',
        		user: user,
				firstname : firstname,
				surname : surname,
				family : family
    		};
			console.log("getting here");
    		localStorage.setItem('mydata', JSON.stringify(data));  // mydata is the name of storage
			 		

			//redirect to userdashboard.html
			window.location.href="userdashboard.html";

	   		return false; //DEBUG
        });
	   
	  
   }
  return false;	 
 }
  
 function success(){
  	alert("success"); 
  	onDeviceReady();
 }
