<?php
session_start();
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$NombreEnt = $_SESSION['Nombre'];
$PasswordEnt = $_SESSION['Password'];
$MEDID = $_SESSION['MEDID'];
$UserID = $_SESSION['UserID'];
$Acceso = $_SESSION['Acceso'];
$privilege=$_SESSION['Previlege'];
$calleeID = $_GET['calleeID'];
if ($Acceso != '23432')
{
echo "Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Health2me Dashboard</a></h2>";
die;
}

					// Connect to server and select databse.
//KYLE$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");
if(isset($_SESSION['MEDID']))
{
$result = mysql_query("SELECT * FROM doctors where id='$MEDID'"); 
	$count=mysql_num_rows($result);
	$row = mysql_fetch_array($result);
	$success ='NO';
	if($count==1)
	{
		
		$IdMedRESERV=$row['IdMEDRESERV'];
		$addstring=$row['salt'];
		$correcthash=PBKDF2_HASH_ALGORITHM .":". PBKDF2_ITERATIONS. ":" . $addstring . ":" .$IdMedRESERV;
		$success ='SI';
		$MedID = $row['id'];
		$MedUserEmail= $row['IdMEDEmail'];
		$MedUserName = $row['Name'];
		$MedUserSurname = $row['Surname'];
		$MedUserLogo = $row['ImageLogo'];
		$IdMedFIXED = $row['IdMEDFIXED'];
		$IdMedFIXEDNAME = $row['IdMEDFIXEDNAME'];
		$MedUserRole = $row['Role'];
		if ($MedUserRole=='1') $MedUserTitle ='Dr. '; else $MedUserTitle =' '; 
				
	}

else
{
echo "USER NOT VALID. Incorrect credentials for login";
echo "<br>\n"; 	
echo "<h2><a href='".$domain."'>Return to Health2me Dashboard</a></h2>";
die;
}
}



//BLOCKSLIFEPIN $result = mysql_query("SELECT * FROM blocks");
$result = mysql_query("SELECT * FROM lifepin");

?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>My Weemo Application</title>
<!-- Quick-Start: Step 2 -->
    <script type="text/javascript" src="../js/jquery-1.9.1.min.js"></script>
<!-- Quick-Start: Step 3 -->
    <script type="text/javascript" src="https://download.weemo.com/js/webappid/42b6r0yr5470"></script>
</head>
<body>

    <script type="text/javascript">
        // Initializze a object for current call
        var current_call = null;
		var calleeID = <?php if(isset($calleeID)) echo "'".$calleeID."'";?>;
		var  callerID= <?php if(isset($MedUserEmail)) echo "'".$MedUserEmail."'";
						else echo $_SESSION['MEDID'];	?>;
/* Quick-Start: Step 4 */ 
        // Initialize the Main Object with WebAppIdentifier, Token, Debug and DisplayName
        var weemo = new Weemo("42b6r0yr5470", callerID, "internal");
/* Quick-Start: Step 5 */ 
        weemo.initialize();
        // Set this method to 1 if you want use the javascript console log
        weemo.setDebugLevel(1);
/* Quick-Start: Step 6 */ 
        weemo.onWeemoDriverNotStarted = function(downloadUrl) {
                    alert('WeemoDriver not detected, copy and paste this following url on your browser: '+downloadUrl);
            };

        // Get the Connection Handler callback when the JavaScript is connected to WeempoDriver
        weemo.onConnectionHandler = function(message, code) {
            if(window.console)
                console.log("Connection Handler : " + message + ' ' + code);
            switch(message) {
/* Quick-Start: Step 7 */      
                // Authenticate token and webappId
                case 'connectedWeemoDriver':
                    weemo.authenticate();
                break; 
                case 'sipOk':
/* Quick-Start: Step 8-1 */                          
                    weemo.setDisplayName(callerID)
/* Quick-Start: Step 8 */  
                // Create a conference room and report that user is connected
                    weemo.createCall(calleeID, "internal", calleeID);
                    document.getElementById('stat').value = "User authenticate";
                break;
                case 'loggedasotheruser':
                // force connection, kick other logged users
                    weemo.authenticate(1);
                break;
            }
        }
        // Call Object is created by callback, this function permits to catch events comming from the call object
        weemo.onCallHandler = function(callObj, args) {
            current_call = callObj;
            if (args.type == "call" && args.status == "terminated") {
                document.getElementById('stat').value = "Call Terminated";
            }
        }       
    </script>
	  <label for="calleeID">Callee ID</label>
	 <input type="text" id="calleeID" name="debug" maxlength="42"/>
    <br/>
    <input type="text" id="stat" name="debug" maxlength="42"/>
    <br/>
    <!-- Send commands to call object -->
    <button type="button" onclick="current_call.videoStart();"> Show video </button>
    <button type="button" onclick="current_call.videoStop();"> Stop video </button>
    <button type="button" onclick="current_call.audioMute();"> Mute </button>
    <button type="button" onclick="current_call.audioUnMute();"> UnMute </button>
    <br/>
    <!-- Hang Up the call -->
    <button type="button" onclick="current_call.hangup();"> Hang Up </button>

</body>
</html>