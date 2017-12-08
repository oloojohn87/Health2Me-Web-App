    <!DOCTYPE html>
<html lang="en"  class="body-error"><head>
    <meta charset="utf-8">
    <title>Health2.me API Guide</title>
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
    <meta name="description" content="">
    <meta name="author" content="">


    <link rel="shortcut icon" href="favicon.ico">
	<link href="css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="css/bootstrap-responsive.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
	<script src="js/jquery-1.9.1-autocomplete.js"></script>
	<script src="js/jquery-ui-autocomplete.js"></script>
	</head>

  <body>


	
	<?php
	require('environment_detailForLogin.php');
	$dbhost = $env_var_db['dbhost']; 
	$dbname = $env_var_db['dbname'];
	$dbuser = $env_var_db['dbuser'];
	$dbpass = $env_var_db['dbpass'];
	$hardcode = $env_var_db['hardcode'];
	
	// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }			


         echo "<div class='container-fluid'><div class='row row-fluid'>
		 <center><img src='images/health2meLOGO.png' width='150px'></br>
  
		 <b>Health2.me API User Resource Version 1.0</b></br>";
		
		if(isset($_GET['memportal']) && $_GET['memportal'] == 'show'){
		 echo "<span style='margin-left:20px;'><font size='3'><b><a href='APIGuide.php?memportal=hide'>Members Portal</a></b></font></span>";
		 }else{
		 echo "<span style='margin-left:20px;'><font size='3'><b><a href='APIGuide.php?memportal=show'>Members Portal</a></b></font></span>";
		 }
		 
		 if(isset($_GET['agentportal']) && $_GET['agentportal'] == 'show'){
		 echo "<span style='margin-left:20px;'><font size='3'><b><a href='APIGuide.php?agentportal=hide'>Agent Portal</a></b></font></span>";
		 }else{
		 echo "<span style='margin-left:20px;'><font size='3'><b><a href='APIGuide.php?agentportal=show'>Agent Portal</a></b></font></span>";
		 }
		 
		 if(isset($_GET['admin']) && $_GET['admin'] == 'show'){
		 echo "<span style='margin-left:20px;'><font size='3'><b><a href='APIGuide.php?admin=hide'>Admin Tools</a></b></font></span>";
		 }else{
		 echo "<span style='margin-left:20px;'><font size='3'><b><a href='APIGuide.php?admin=show'>Admin Tools</a></b></font></span>";
		 }
		
		echo "</div></center></br>";
		if(isset($_GET['memportal']) && $_GET['memportal'] == 'show'){
  echo "<p>Addition of candidates to health2.me can be uploaded via our REST API.  For each patient that you would like to insert into the system you must use a basic POST request.
		 <p>Please note that you <b>must</b> have a valid agent account in order to add new patients into the system. You may reference our Agent Portal in the link above.
		 <p>The resource URL for making post requests
		 </br><b>https://health2.me/newUserAPI.php</b>
		 <p></br>";
		 
		 echo "<ul>";
		 
		 if(isset($_GET['pat']) && $_GET['pat'] == 'show'){
		 echo "<li style='display:inline;margin-left:25px'><font size='3'><b><a href='APIGuide.php?pat=hide&memportal=show'>Create Patients</a></b></font></li>";
		 }else{
		 echo "<li style='display:inline;margin-left:25px'><font size='3'><b><a href='APIGuide.php?pat=show&memportal=show'>Create Patients</a></b></font></li>";
		 }
		 
		 if(isset($_GET['param']) && $_GET['param'] == 'show'){
		 echo "<li style='display:inline;margin-left:25px'><font size='3'><b><a href='APIGuide.php?param=hide&memportal=show'>Parameters</a></b></font></li>";
		 }else{
		 echo "<li style='display:inline;margin-left:25px'><font size='3'><b><a href='APIGuide.php?param=show&memportal=show'>Parameters</a></b></font></li>";
		 }
		 
		 if(isset($_GET['ret']) && $_GET['ret'] == 'show'){
		 echo "<li style='display:inline;margin-left:25px'><font size='3'><b><a href='APIGuide.php?ret=hide&memportal=show'>Returned Errors</a></b></font></li>";
		 }else{
		 echo "<li style='display:inline;margin-left:25px'><font size='3'><b><a href='APIGuide.php?ret=show&memportal=show'>Returned Errors</a></b></font></li>";
		 }
		 
		 echo "</ul>";
		 
		 
		 
		 if(isset($_GET['param']) && $_GET['param'] == 'show')
		 {
		 echo "<center><table class='table table-bordered' style='width:100%;'>
		 <tr>
		 <td>name</br><i><font size='1'>Required</font></i></td><td>The first name of the patient to be added to the system.</td>
		 </tr>
		 <tr>
		 <td>surname</br><i><font size='1'>Required</font></i></td><td>The last name of the patient to be added to the system.</td>
		 </tr>
		 <tr>
		 <td>dob</br><i><font size='1'>Required</font></i></td><td>This parameter must contain the date of birth of the patient.</br>Format: mm/dd/yyyy</td>
		 </tr>
		 <tr>
		 <td>gender</br><i><font size='1'>Required</font></i></td><td>This will indicate the gender of the patient.</td>
		 </tr>
		 <tr>
		 <td>email</td><td>Enter the full email of the patient to be uploaded</td>
		 </tr>
		 <tr>
		 <td>plan</br><i><font size='1'>Required</font></i></td><td>Enter the payment plan for the patient.</td>
		 </tr>
		 <tr>
		 <td>phone</br><i><font size='1'>Required</font></i></td><td>Enter the phone number of the patient.  The number may include many special characters such as (555)-223(5555), but 5552235555 is preferred.</td>
		 </tr>
		 <tr>
		 <td>agent</br><i><font size='1'>Required</font></i></td><td>Enter the unique agent ID in order to add patients to the system.  You can add agents using our add agent API.</td>
		 </tr>
		 <tr>
		 <td>security</br><i><font size='1'>Required</font></i></td><td>Enter the unique security ID in order to add patients to the system.  This will be provided to you by Health2.me</td>
		 </tr>
		 <tr>
		 <td>facility</br><i><font size='1'>Required</font></i></td><td>Enter the agent location.</td>
		 </tr>
		 </table></center>";
		 }
		 echo "</br>";
		 
		 
		 
		 if(isset($_GET['ret']) && $_GET['ret'] == 'show'){
		 echo "<center><table class='table table-bordered' style='width:100%;'>
		 <tr>
		 <td>'CODE':'1'</td><td>'SUCCESS':'Candidate has been successfully added to the system.'</td>
		 </tr>
		 <tr>
		 <td>'CODE':'101'</td><td>'ERROR':'Email is not valid.'</td>
		 </tr>
		 <tr>
		 <td>'CODE':'102'</td><td>'ERROR':'Phone number is not valid.'</td>
		 </tr>
		 <tr>
		 <td>'CODE':'201'</td><td>'ERROR':'Candidate phone is already known.'</td>
		 </tr>
		 <tr>
		 <td>'CODE':'202'</td><td>'ERROR':'Candidate email is already known.'</td>
		 </tr>
		 <tr>
		 <td>'CODE':'301'</td><td>'MISSING':'First name is required.'</td>
		 </tr>
		 <tr>
		 <td>'CODE':'302'</td><td>'MISSING':'Surname is required.'</td>
		 </tr>
		 <tr>
		 <td>'CODE':'303'</td><td>'MISSING':'Date of birth is required.'</td>
		 </tr>
		 <tr>
		 <td>'CODE':'304'</td><td>'MISSING':'Gender is required.'</td>
		 </tr>
		 <tr>
		 <td>'CODE':'305'</td><td>'MISSING':'Phone number is required.'</td>
		 </tr>
		 <tr>
		 <td>'CODE':'306'</td><td>'MISSING':'Security code is required.'</td>
		 </tr>
		 <tr>
		 <td>'CODE':'307'</td><td>'MISSING':'Agent ID is required.'</td>
		 </tr>
		 <tr>
		 <td>'CODE':'308'</td><td>'MISSING':'Facility location is required.'</td>
		 </tr>
		 <tr>
		 <td>'CODE':'309'</td><td>'MISSING':'Payment plan is required.'</td>
		 </tr>
		 </table></center>";
		 }
		 echo "</br>";
		 
		 if(isset($_GET['pat']) && $_GET['pat'] == 'show'){
		 echo "<form width='75%' method='post' action='".$hardcode."newUserAPI.php'>
 <input type='text' style='float:left;' id='name' name='name' maxlength='50' size='25' placeholder='First Name'/>
 <input id='field_id' type='hidden' style='width:0px;'></br></br>
 <input type='text' style='float:left;' id='surname' name='surname' maxlength='100' size='25' placeholder='Sur Name'/>
 <input id='field_id2' type='hidden' style='width:0px;'></br></br>
 <input type='date' id='dob' name='dob' placeholder='Birth Date'/></br>
 <input type='text' style='float:left;' id='email' name='email' maxlength='255' size='25' placeholder='Email'/>
 <input id='field_id3' type='hidden' style='width:0px;'></br></br>
 <input type='text' style='float:left;' id='phone' name='phone' maxlength='20' size='20' placeholder='Phone'/></br></br>
 <font size='1'><i><b>*If the member is in the United States please include the numeral 1 before the number.</b></i></font>
 <input id='field_id4' type='hidden' style='width:0px;'></br></br>
 <select name='plan' onchange='if (this.selectedIndex) familyPlan(this.selectedIndex);'>
 <option value='' disabled selected>Select the Plan</option>
<option value='0'>Individual Plan</option>
<option value='1'>Family Plan</option>
</select>
 <table style='background-color:white;'><tr><td width='50px'><label for='male'>Male</label>
 <input type='radio' class='radio' id='male' name='gender' value='1'/>
 </td><td width='50px'><label for='female'>Female</label>
 <input type='radio' class='radio' id='female' name='gender' value='0'/>
</td></tr></table>
<div id='form_container'>
</div>
 <p></br><input type='text' id='agent' name='agent' placeholder='Agent ID'/>
 <input type='password' id='security' name='security' placeholder='Agent Security Code'/>
 <select name='facility'>
 <option value='' disabled selected>Select the Facility</option>
<option value='0'>HTI-Colombia</option>
<option value='1'>HTI-Spain</option>
<option value='2'>HTI-RivaCare</option>
<option value='3'>HTI-24x7</option>
<option value='4'>HTI-Costa Rica</option>
<option value='5'>HTI-USA English</option>
<option value='6'>HTI-USA Spanish</option>
</select>
 <p></br><a id='dupe' class='btn'>Check for Duplicates</a>
 <input class='btn' type='submit' name='addPatient' value='Add Patient' />
 </form>";
 }
 }
 
 if(isset($_GET['agentportal']) && $_GET['agentportal'] == 'show'){
 echo "<p>Addition of agents to health2.me can be uploaded via our REST API.  For each agent that you would like to insert into the system you must use a basic POST request.
		 <p>The resource URL for making post requests
		 </br><b>".$hardcode."newAgentAPI.php</b>
		 <p></br>";
		 
		 echo "<ul>";
		 
		 if(isset($_GET['agent']) && $_GET['agent'] == 'show'){
		 echo "<li style='display:inline;margin-left:25px'><font size='3'><b><a href='APIGuide.php?agent=hide&agentportal=show#para'>Create Agents</a></b></font></li>";
		 }else{
		 echo "<li style='display:inline;margin-left:25px'><font size='3'><b><a href='APIGuide.php?agent=show&agentportal=show#para'>Create Agents</a></b></font></li>";
		 }
		 
		 if(isset($_GET['para']) && $_GET['para'] == 'show'){
		 echo "<li style='display:inline;margin-left:25px'><font size='3'><b><a href='APIGuide.php?para=hide&agentportal=show#para'>Parameters</a></b></font></li>";
		 }else{
		 echo "<li style='display:inline;margin-left:25px'><font size='3'><b><a href='APIGuide.php?para=show&agentportal=show#para'>Parameters</a></b></font></li>";
		 }
		 
		 if(isset($_GET['reta']) && $_GET['reta'] == 'show'){
		 echo "<li style='display:inline;margin-left:25px'><font size='3'><b><a href='APIGuide.php?reta=hide&agentportal=show#para'>Returned Errors</a></b></font></li>";
		 }else{
		 echo "<li style='display:inline;margin-left:25px'><font size='3'><b><a href='APIGuide.php?reta=show&agentportal=show#para'>Returned Errors</a></b></font></li>";
		 }
		 
		 echo "</ul>";
		 
		 if(isset($_GET['para']) && $_GET['para'] == 'show'){
		 echo "<center><table class='table table-bordered' style='width:100%;'>
		 <tr>
		 <td>agent</br><i><font size='1'>Required</font></i></td><td>Enter the agent id/username.</td>
		 </tr>
		 <tr>
		 <td>password</br><i><font size='1'>Required</font></i></td><td>Enter the agent password.</td>
		 </tr>
		 <tr>
		 <td>password_repeat</br><i><font size='1'>Required</font></i></td><td>Repeat the agent password.</td>
		 </tr>
		 <tr>
		 <td>email</br><i><font size='1'>Required</font></i></td><td>Add agent email.</td>
		 </tr>
		 <tr>
		 <td>security</td><td>Enter the security code provided to you by health2.me for identity verification.</td>
		 </tr>
		 </table></center>";
		 echo "</br>";
		 }
		 
		 
		 if(isset($_GET['reta']) && $_GET['reta'] == 'show'){
		 echo "<center><table class='table table-bordered' style='width:100%;'>
		 <tr>
		 <td>'CODE':'1'</td><td>'SUCCESS':'Agent has been successfully added to the system.'</td>
		 </tr>
		 <tr>
		 <td>'CODE':'101'</td><td>'ERROR':'Email is not valid.'</td>
		 </tr>
		 <tr>
		 <td>'CODE':'102'</td><td>'ERROR':'The passwords entered do not match.'</td>
		 </tr>
		 <tr>
		 <td>'CODE':'103'</td><td>'ERROR':'The agent id is already in the system.'</td>
		 </tr>
		 <tr>
		 <td>'CODE':'306'</td><td>'MISSING':'Security code is required.'</td>
		 </tr>
		 </table></center>";
		 }
		 echo "</br>";
		 
		 
 
 if(isset($_GET['agent']) && $_GET['agent'] == 'show'){
 echo "Agent accounts are created to increase security and prevent DDOS attacks.  This method also allows for much closer tracking of which agents are adding which users.<p>To view the tracking system please visit <a href='APIGuide.php?count=show&admin=show#reset'>Show Agent Stats</a> in the <a href='APIGuide.php?admin=show'>Admin Tools</a> above.</br></br>
 <form method='post' action='".$hardcode."newAgentAPI.php'>
 <input type='text' id='agent' name='agent' maxlength='50' size='25' placeholder='Agent ID'/>
 <input type='password' id='password' name='password' maxlength='100' size='25' placeholder='Password'/>
 <input type='password' id='password_repeat' name='password_repeat' maxlength='100' size='25' placeholder='Repeat Password'/>
 <input type='text' id='email' name='email' maxlength='255' size='25' placeholder='Email'/>
 <input type='text' id='security' name='security' maxlength='255' size='25' placeholder='Security Code'/>
</br>
<select name='facility'>
 <option value='' disabled selected>Select the Facility</option>
<option value='0'>Colombia</option>
<option value='1'>Spain</option>
<option value='2'>RivaCare</option>
</select>
 <p></br><input class='btn' type='submit' name='addAgent' value='Add Agent' />
 </form>";
 }
 }
 
 if(isset($_GET['admin']) && $_GET['admin'] == 'show'){
 echo "The administrative section can be used to reset passwords or track agent information.
 </br></br>
 <b>Agent Tracking:</b></br>In the Show Agent Stats link below, there is a table with all registered agents.  On the far right column will show the total number of registered members by that agent.
 </br>If that agent has registered members then the number will display as a link.  Once the link is clicked it will display all members registered by that agent.";
 echo "</br></br></br><ul>";
 
 if(isset($_GET['reset']) && $_GET['reset'] == 'show'){
 echo "<li style='display:inline;margin-left:25px'><b><font size='3'><a id='reset' href='APIGuide.php?reset=hide&admin=show#reset'>Reset Agent Password</a></font></b></li>";
 }else{
 echo "<li style='display:inline;margin-left:25px'><b><font size='3'><a href='APIGuide.php?reset=show&admin=show#reset'>Reset Agent Password</a></font></b></li>";
 }
 
 if(isset($_GET['count']) && $_GET['count'] == 'show'){
 echo "<li style='display:inline;margin-left:25px'><b><font size='3'><a id='reset' href='APIGuide.php?count=hide&admin=show#reset'>Show Agent Stats</a></font></b></li>";
 }else{
 echo "<li style='display:inline;margin-left:25px'><b><font size='3'><a href='APIGuide.php?count=show&admin=show#reset'>Show Agent Stats</a></font></b></li>";
 }

 if(isset($_GET['suspend_show']) && $_GET['suspend_show'] == 'show'){
 echo "<li style='display:inline;margin-left:25px'><b><font size='3'><a id='reset' href='APIGuide.php?suspend_show=hide&admin=show#reset'>Suspend Account Remotely</a></font></b></li>";
 }else{
 echo "<li style='display:inline;margin-left:25px'><b><font size='3'><a href='APIGuide.php?suspend_show=show&admin=show#reset'>Suspend Account Remotely</a></font></b></li>";
 }

if(isset($_GET['suspend_show']) && $_GET['suspend_show'] == 'show'){
echo "</br></br><p>If you are interested in suspending an account remotely.  Please review this ajax call...</br>
</br>
 var agent_id = 'agent id that was used to create account';</br>
 var agent_pass = 'corresponding agent pasword';</br>
 var suspend_id = 'id of member to suspend';</br></br>
$.post('".$hardcode."APIGuide.php', {suspend_submit : 'yes', agent_suspend : agent_id, password_suspend : agent_pass, suspend_id : suspend_id}, function(data)
      {
          console.log(data);
      });";
}
 
 if(isset($_GET['count']) && $_GET['count'] == 'show'){
 $query = $con->prepare("SELECT * FROM agents");
 $query->execute();
 
 echo "<table class='table table-bordered'><th>Agent ID</th><th>Email</th><th>Total Members Registered</th>";
 while($row = $query->fetch(PDO::FETCH_ASSOC))
 {
 echo "<tr>
 <td>".$row['username']."</td>
 <td>".$row['email']."</td>";
 if($row['t_registered'] > 0){
 echo "<td><a href='APIGuide.php?pullusers=".$row['idagents']."'>".$row['t_registered']."</a> <-- Click to view registered users</td>";
 }else{
 echo "<td> -==No Users Registered==-</td>";
 }
 echo "</tr>";
 }
 echo "</table>";
 }
 
 echo "</ul>";
 
 if(isset($_GET['reset']) && $_GET['reset'] == 'show'){
  echo "Please enter the agent ID and the password you would like to reset it to.</br></br></br>
  <form method='post' action='".$hardcode."agentPassAPI.php'>
 <input type='text' id='agent' name='agent' maxlength='50' size='25' placeholder='Agent ID'/>
 <input type='password' id='password' name='password' maxlength='100' size='25' placeholder='Password'/>
 <input type='password' id='password_repeat' name='password_repeat' maxlength='100' size='25' placeholder='Repeat Password'/>
 <input type='text' id='security' name='security' maxlength='255' size='25' placeholder='Security Code'/>'Reset Password'
 <p></br><input class='btn' type='submit' name='reset' value='Reset Agent Password' />
 </form>";
 }
 }
 
 if(isset($_GET['pullusers'])){
 $query = $con->prepare("SELECT * FROM agentslinkusers WHERE agent=?");
 $query->bindValue(1, $_GET['pullusers'], PDO::PARAM_INT);
 $query->execute();
 
 echo "<table class='table table-bordered'><th>ID</th><th>Name</th><th>Surname</th><th>Email</th><th>Number</th><th>Grant Access</th><th>Verified</th><th>Last IP Logged</th><th>Sign Up Date</th><th>Number of Calls</th><th>Location</th><th>Suspend?</th>";
 while($row = $query->fetch(PDO::FETCH_ASSOC)){
 $query2 = $con->prepare("SELECT * FROM usuarios WHERE Identif=?");
 $query2->bindValue(1, $row['user'], PDO::PARAM_INT);
 $query2->execute();
 
 $row2 = $query2->fetch(PDO::FETCH_ASSOC);
 if($row2['Verificado'] != null){
 $verified = 'Yes';
 }else{
 $verified = 'No';
 }
 if($row2['IPVALID'] != null){
 $ip = $row2['IPVALID'];
 }else{
 $ip = 'Never logged in';
 }
 if($row2['location'] != null){
 $location = $row2['location'];
 }else{
 $location = 'Location never added';
 }
 
 if($row2['Identif'] == ''){
 $show_id = 'Del';
 }else{
  $show_id = $row2['Identif'];
 }
 if($row2['Name'] == ''){
 $show_name = 'Del';
 }else{
 $show_name = $row2['Name'];
 }
 if($row2['Surname'] == ''){
 $show_surname = 'Del';
 }else{
 $show_surname = $row2['Surname'];
 }
 if($row2['email'] == ''){
 $show_email = 'Deleted';
 }else{
 $show_email = $row2['email'];
 }
 if($row2['telefono'] == ''){
 $show_tel = 'Deleted';
 }else{
 $show_tel = $row2['telefono'];
 }
 if($row2['GrantAccess'] == ''){
 $show_ga = 'Deleted';
 }else{
 $show_ga = $row2['GrantAccess'];
 }
 if($row2['suspended'] == 1){
 $show_susp = 'Suspended';
 }else{
 $show_susp = "<a href='APIGuide.php?suspend=".$show_id."'>Cancel</a>";
 }
 
 echo "<tr>
 <td width='5%'>".$show_id."</td>
 <td width='5%'>".$show_name."</td>
 <td width='5%'>".$show_surname."</td>
 <td width='15%'>".$show_email."</td>
 <td width='10%'>".$show_tel."</td>
 <td width='10%'>".$show_ga."</td>
 <td width='5%'>".$verified."</td>
 <td width='10%'>".$ip."</td>
 <td width='10%'>".$row2['signUpDate']."</td>
 <td width='10%'>".$row2['numberofPhoneCalls']."</td>
 <td width='15%'>".$location."</td>
 <td>".$show_susp."</td>
 </tr>";
 }
 echo "</table>";
 }
 
 if(isset($_GET['suspend'])){
  echo "Please enter the agent ID and the agent password to suspend this account.</br></br></br>";
  
  $query5 = $con->prepare("SELECT * FROM usuarios WHERE Identif=?");
  $query5->bindValue(1, $_GET['suspend'], PDO::PARAM_INT);
  $query5->execute();
  
  $row2 = $query5->fetch(PDO::FETCH_ASSOC);
  
  echo "<table class='table table-bordered'><th>ID</th><th>Name</th><th>Surname</th><th>Email</th><th>Number</th><th>Grant Access</th><th>Verified</th><th>Last IP Logged</th><th>Sign Up Date</th><th>Number of Calls</th><th>Location</th>";
  
  if($row2['Verificado'] != null){
 $verified = 'Yes';
 }else{
 $verified = 'No';
 }
 if($row2['IPVALID'] != null){
 $ip = $row2['IPVALID'];
 }else{
 $ip = 'Never logged in';
 }
 if($row2['location'] != null){
 $location = $row2['location'];
 }else{
 $location = 'Location never added';
 }
 
 if($row2['Identif'] == ''){
 $show_id = 'Del';
 }else{
  $show_id = $row2['Identif'];
 }
 if($row2['Name'] == ''){
 $show_name = 'Del';
 }else{
 $show_name = $row2['Name'];
 }
 if($row2['Surname'] == ''){
 $show_surname = 'Del';
 }else{
 $show_surname = $row2['Surname'];
 }
 if($row2['email'] == ''){
 $show_email = 'Deleted';
 }else{
 $show_email = $row2['email'];
 }
 if($row2['telefono'] == ''){
 $show_tel = 'Deleted';
 }else{
 $show_tel = $row2['telefono'];
 }
 if($row2['GrantAccess'] == ''){
 $show_ga = 'Deleted';
 }else{
 $show_ga = $row2['GrantAccess'];
 }
 
 echo "<tr>
 <td width='5%'>".$show_id."</td>
 <td width='5%'>".$show_name."</td>
 <td width='5%'>".$show_surname."</td>
 <td width='15%'>".$show_email."</td>
 <td width='10%'>".$show_tel."</td>
 <td width='10%'>".$show_ga."</td>
 <td width='5%'>".$verified."</td>
 <td width='10%'>".$ip."</td>
 <td width='10%'>".$row2['signUpDate']."</td>
 <td width='10%'>".$row2['numberofPhoneCalls']."</td>
 <td width='15%'>".$location."</td>
 </tr>";
  
  echo "<form method='post' action='".$hardcode."APIGuide.php'>
 <input type='text' name='agent_suspend' maxlength='50' size='25' placeholder='Agent ID'/>
 <input type='password' name='password_suspend' maxlength='100' size='25' placeholder='Password'/>
 <input type='hidden' name='suspend_id' value='".$_GET['suspend']."'/>
 <p></br><input class='btn' type='submit' name='suspend_submit' value='Suspend Member Account' />
 </form>";
 }
 
 if(isset($_POST['suspend_submit'])){
 $agent_id = $_POST['agent_suspend'];
 $agent_pass = $_POST['password_suspend'];
 $suspend_id = $_POST['suspend_id'];
 
  $query6 = $con->prepare("SELECT * FROM agentslinkusers WHERE user=?");
  $query6->bindValue(1, $suspend_id, PDO::PARAM_INT);
  $query6->execute();
  
  $row6 = $query6->fetch(PDO::FETCH_ASSOC);
  
  $query7 = $con->prepare("SELECT * FROM agents WHERE idagents=?");
  $query7->bindValue(1, $row6['agent'], PDO::PARAM_INT);
  $query7->execute();
  
  $row7 = $query7->fetch(PDO::FETCH_ASSOC);
  
  if($row7['username'] != $agent_id){
  echo "This member account was not created by ".$agent_id.". Please use the agent that created this account to suspend.";
  }elseif($row7['username'] == $agent_id){
  
	$result = $con->prepare("SELECT salt, password FROM agents WHERE username = ?");
	$result->bindValue(1, $agent_id, PDO::PARAM_STR);
	$result->execute();

	  $row = $result->fetch(PDO::FETCH_ASSOC);
	  
	  $blowfish_pre = '$2a$12$';
	  $blowfish_end = '$';
	  
	  $hashed_pass = crypt($agent_pass, $blowfish_pre . $row['salt'] . $blowfish_end);

	if($hashed_pass != $row['password']){
	die("You have not entered the correct security code for this agent to suspend this account. Please contact Health2.me support.");
	}else{
	$result_s = $con->prepare("UPDATE usuarios SET suspended = 1 WHERE Identif = ?");
	$result_s->bindValue(1, $suspend_id, PDO::PARAM_STR);
	$result_s->execute();
	
	echo "This account has been successfully suspended.";
	}

  }
  
 }
    echo "</div>"; 
        ?>
		<script type="text/javascript" >
		
		GetMemberList();
		GetSurnameList();
		GetEmailList();
		GetPhoneList();
		
		function familyPlan(value)
	{
	if(value == 2){
	var container = document.getElementById("form_container");
	container.appendChild(document.createElement("hr"));
	for (i=0;i<4;i++){
                // Append a node with a random text
                container.appendChild(document.createTextNode("First Name " + (i+1)));
                // Create an <input> element, set its type and name attributes
                var input = document.createElement("input");
                input.type = "text";
                input.name = "name" + i;
                container.appendChild(input);
                // Append a line break 
                container.appendChild(document.createElement("br"));
				/////////////////////////////////////////////////////////////////////////////////
				container.appendChild(document.createTextNode("Last Name " + (i+1)));
                // Create an <input> element, set its type and name attributes
                var input = document.createElement("input");
                input.type = "text";
                input.name = "surname" + i;
                container.appendChild(input);
                // Append a line break 
                container.appendChild(document.createElement("br"));
				/////////////////////////////////////////////////////////////////////////////////
				/////////////////////////////////////////////////////////////////////////////////
				container.appendChild(document.createTextNode("Gender (M|F)" + (i+1)));
                // Create an <input> element, set its type and name attributes
                var input = document.createElement("input");
                input.type = "text";
                input.name = "gender" + i;
                container.appendChild(input);
                // Append a line break 
                container.appendChild(document.createElement("br"));
				/////////////////////////////////////////////////////////////////////////////////
				/////////////////////////////////////////////////////////////////////////////////
				container.appendChild(document.createTextNode("Relation to owner " + (i+1)));
                // Create an <input> element, set its type and name attributes
                var input = document.createElement("input");
                input.type = "text";
                input.name = "relate" + i;
                container.appendChild(input);
                // Append a line break 
                container.appendChild(document.createElement("br"));
				/////////////////////////////////////////////////////////////////////////////////
				/////////////////////////////////////////////////////////////////////////////////
				container.appendChild(document.createTextNode("Date of birth " + (i+1)));
                // Create an <input> element, set its type and name attributes
                var input = document.createElement("input");
                input.type = "date";
                input.name = "dob" + i;
                container.appendChild(input);
                // Append a line break 
                container.appendChild(document.createElement("br"));
				/////////////////////////////////////////////////////////////////////////////////
				/////////////////////////////////////////////////////////////////////////////////
				container.appendChild(document.createTextNode("Email " + (i+1)));
                // Create an <input> element, set its type and name attributes
                var input = document.createElement("input");
                input.type = "text";
                input.name = "email" + i;
                container.appendChild(input);
                // Append a line break 
                container.appendChild(document.createElement("br"));
				/////////////////////////////////////////////////////////////////////////////////
				/////////////////////////////////////////////////////////////////////////////////
				container.appendChild(document.createTextNode("Phone " + (i+1)));
                // Create an <input> element, set its type and name attributes
                var input = document.createElement("input");
                input.type = "text";
                input.name = "phone" + i;
                container.appendChild(input);
                // Append a line break 
                container.appendChild(document.createElement("br"));
				/////////////////////////////////////////////////////////////////////////////////
				/////////////////////////////////////////////////////////////////////////////////
                // Create an <input> element, set its type and name attributes
                var input = document.createElement("input");
                input.type = "hidden";
                input.name = "family_count";
				input.value = 3;
                container.appendChild(input);
                // Append a line break 
                container.appendChild(document.createElement("br"));
				/////////////////////////////////////////////////////////////////////////////////
				container.appendChild(document.createElement("hr"));
            }
			}
	}
		
		function GetMemberList()
	{
		
		var queUrl ='getMemberList.php?name=yes';	
		//alert(queUrl);	
		var members='';	
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async:false,
			success: function(data)
			{
				members= data.items;
				//alert(data.items);
				
			},
            error: function(data, errorThrown){
               alert(errorThrown);
              } 
		});
		
		MemberListName=members;
//		alert(MemberList);	
		
	}
	
	function GetSurnameList()
	{
		
		var queUrl ='getMemberList.php?surname=yes';	
		//alert(queUrl);	
		var members='';	
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async:false,
			success: function(data)
			{
				members= data.items;
				//alert(data.items);
				
			},
            error: function(data, errorThrown){
               alert(errorThrown);
              } 
		});
		
		MemberListSurname=members;
//		alert(MemberList);	
		
	}
	
	function GetEmailList()
	{
		
		var queUrl ='getMemberList.php?email=yes';	
		//alert(queUrl);	
		var members='';	
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async:false,
			success: function(data)
			{
				members= data.items;
				//alert(data.items);
				
			},
            error: function(data, errorThrown){
               alert(errorThrown);
              } 
		});
		
		MemberListEmail=members;
//		alert(MemberList);	
		
	}
	
	function GetPhoneList()
	{
		
		var queUrl ='getMemberList.php?phone=yes';	
		//alert(queUrl);	
		var members='';	
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async:false,
			success: function(data)
			{
				members= data.items;
				//alert(data.items);
				
			},
            error: function(data, errorThrown){
               alert(errorThrown);
              } 
		});
		
		MemberListPhone=members;
//		alert(MemberList);	
		
	}
	
	$("#name").autocomplete({
        source: MemberListName,
        minLength: 1,
        select: function(event, ui) {
            // feed hidden id field
            $("#field_id").val(ui.item.id);
 //           alert($("#field_id").val());
        },
               
		// if user enter some drug which is not in our list
        change: function (event, ui) {
            if (ui.item === null) {
                $('#field_id').val('DB00000');
//				alert($("#field_id").val());
            }
        }
    });
	
	$("#surname").autocomplete({
        source: MemberListSurname,
        minLength: 1,
        select: function(event, ui) {
            // feed hidden id field
            $("#field_id2").val(ui.item.id);
 //           alert($("#field_id").val());
        },
               
		// if user enter some drug which is not in our list
        change: function (event, ui) {
            if (ui.item === null) {
                $('#field_id2').val('DB00000');
//				alert($("#field_id").val());
            }
        }
    });
	
	$("#email").autocomplete({
        source: MemberListEmail,
        minLength: 1,
        select: function(event, ui) {
            // feed hidden id field
            $("#field_id3").val(ui.item.id);
 //           alert($("#field_id").val());
        },
               
		// if user enter some drug which is not in our list
        change: function (event, ui) {
            if (ui.item === null) {
                $('#field_id3').val('DB00000');
//				alert($("#field_id").val());
            }
        }
    });
	
	$("#phone").autocomplete({
        source: MemberListPhone,
        minLength: 1,
        select: function(event, ui) {
            // feed hidden id field
            $("#field_id4").val(ui.item.id);
 //           alert($("#field_id").val());
        },
               
		// if user enter some drug which is not in our list
        change: function (event, ui) {
            if (ui.item === null) {
                $('#field_id4').val('DB00000');
//				alert($("#field_id").val());
            }
        }
    });
	
	$('#dupe').click(function() {
	var name = $('#name').val();
	var surname = $('#surname').val();
	var dob = $('#dob').val();
	var email = $('#email').val();
	var phone = $('#phone').val();

	checkForDupeAPI(name, surname, dob, email, phone);
	});
	
	function checkForDupeAPI(name, surname, dob, email, phone)
	{
		var n = 0;
		var queUrl ="checkForDupeAPI.php?name="+name+"&surname="+surname+"&dob="+dob+"&email="+email+"&phone="+phone;	
		//alert(queUrl);	
		
		$.ajax(
		{
			url: queUrl,
			dataType: "json",
			async:false,
			success: function(data)
			{
			dupeData = data.items;
				
				
			},
            error: function(data, errorThrown){
               alert('There are no duplicates with that information in the system.');
              } 
		});
		var numDupe = dupeData.length;
		alert('There are '+numDupe+' duplicate entries with that information.');

	}
	

</script>
  
